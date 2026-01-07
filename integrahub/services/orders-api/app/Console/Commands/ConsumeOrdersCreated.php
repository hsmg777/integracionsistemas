<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use RuntimeException;
use Throwable;

class ConsumeOrdersCreated extends Command
{
    protected $signature = 'consume:orders-created';
    protected $description = 'Consume orders.created events from RabbitMQ';

    public function handle(): int
    {
        $host = env('RABBITMQ_HOST', 'rabbitmq');
        $port = (int) env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'guest');
        $pass = env('RABBITMQ_PASS', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');

        $exchange = env('RABBITMQ_EXCHANGE', 'orders');
        $queue = env('RABBITMQ_QUEUE', 'orders.created');
        $routingKey = env('RABBITMQ_ROUTING_KEY', 'orders.created');

        $retryExchange = env('RABBITMQ_RETRY_EXCHANGE', 'orders.retry');
        $dlqExchange = env('RABBITMQ_DLQ_EXCHANGE', 'orders.dlq');

        $dlqQueue = env('RABBITMQ_DLQ_QUEUE', 'orders.created.dlq');
        $dlqRoutingKey = env('RABBITMQ_DLQ_ROUTING_KEY', 'orders.created.dlq');

        $retrySteps = [
            ['queue' => env('RABBITMQ_RETRY_QUEUE_1', 'orders.created.retry.5s'),  'rk' => env('RABBITMQ_RETRY_RK_1', 'orders.created.retry.5s'),  'ttl' => 5000],
            ['queue' => env('RABBITMQ_RETRY_QUEUE_2', 'orders.created.retry.15s'), 'rk' => env('RABBITMQ_RETRY_RK_2', 'orders.created.retry.15s'), 'ttl' => 15000],
            ['queue' => env('RABBITMQ_RETRY_QUEUE_3', 'orders.created.retry.60s'), 'rk' => env('RABBITMQ_RETRY_RK_3', 'orders.created.retry.60s'), 'ttl' => 60000],
        ];
        $maxAttempts = (int) env('RABBITMQ_MAX_ATTEMPTS', 3); // total intentos: 3 => attempt 0,1,2; luego DLQ

        $inventoryUrl = env('INVENTORY_RESERVE_URL'); // ej: http://inventory_api/api/reserve
        $paymentUrl   = env('PAYMENT_CHARGE_URL');    // ej: http://payment_api/api/charge

        $conn = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        $ch = $conn->channel();

        $ch->exchange_declare($exchange, 'topic', false, true, false);
        $ch->exchange_declare($retryExchange, 'direct', false, true, false);
        $ch->exchange_declare($dlqExchange, 'direct', false, true, false);

        $ch->queue_declare($queue, false, true, false, false);
        $ch->queue_bind($queue, $exchange, $routingKey);

        foreach ($retrySteps as $step) {
            $args = new AMQPTable([
                'x-message-ttl' => $step['ttl'],
                'x-dead-letter-exchange' => $exchange,
                'x-dead-letter-routing-key' => $routingKey,
            ]);

            $ch->queue_declare($step['queue'], false, true, false, false, false, $args);
            $ch->queue_bind($step['queue'], $retryExchange, $step['rk']);
        }

        $ch->queue_declare($dlqQueue, false, true, false, false);
        $ch->queue_bind($dlqQueue, $dlqExchange, $dlqRoutingKey);

        $this->info("Listening queue: {$queue} (routingKey: {$routingKey})");

        $callback = function (AMQPMessage $msg) use (
            $ch,
            $exchange,
            $retryExchange,
            $dlqExchange,
            $dlqRoutingKey,
            $retrySteps,
            $maxAttempts,
            $inventoryUrl,
            $paymentUrl
        ) {
            $body = $msg->getBody();
            $headers = $this->getHeaders($msg);
            $attempt = (int) ($headers['x-attempt'] ?? 0);
            $headers['x-first-seen-at'] = $headers['x-first-seen-at'] ?? now()->toIso8601String();

            try {
                $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            } catch (Throwable $e) {
                $this->error('Invalid message: invalid JSON');
                $this->publishRaw($ch, $dlqExchange, $dlqRoutingKey, $body, array_merge($headers, [
                    'x-error' => 'invalid_json',
                ]));
                $msg->ack();
                return;
            }

            $orderId = (int) ($data['order_id'] ?? 0);
            if ($orderId <= 0) {
                $this->error('Invalid message: missing order_id');
                $this->publishRaw($ch, $dlqExchange, $dlqRoutingKey, $body, array_merge($headers, [
                    'x-error' => 'missing_order_id',
                ]));
                $msg->ack();
                return;
            }

            $locked = Order::query()
                ->whereKey($orderId)
                ->where('status', 'PENDING')
                ->update(['status' => 'PROCESSING']);

            if ($locked === 0) {
                $this->warn("Order {$orderId} skipped (status != PENDING)");
                $msg->ack();
                return;
            }

            try {
                $order = Order::find($orderId);
                if (!$order) {
                    throw new RuntimeException('order_not_found');
                }

                $this->reserveInventory($inventoryUrl, $data);
                $this->chargePayment($paymentUrl, $data);

                $order->status = 'CONFIRMED';
                $order->save();

                $event = $this->translateEvent('orders.confirmed', $order, $data);
                $this->publishJson($ch, $exchange, 'orders.confirmed', $event, $headers);

                $this->info("Order {$orderId} -> CONFIRMED");
                $msg->ack();
                return;
            } catch (Throwable $e) {
                $this->warn("Order {$orderId} failed: {$e->getMessage()} (attempt {$attempt})");

                $willRetry = $attempt < ($maxAttempts - 1);

                if ($willRetry) {
                    Order::query()->whereKey($orderId)->update(['status' => 'PENDING']);

                    $stepIndex = min($attempt, count($retrySteps) - 1);
                    $step = $retrySteps[$stepIndex];

                    $nextHeaders = array_merge($headers, [
                        'x-attempt' => $attempt + 1,
                        'x-last-error' => $e->getMessage(),
                    ]);

                    $this->publishRaw($ch, $retryExchange, $step['rk'], $body, $nextHeaders);
                    $this->info("Requeued to retry: {$step['rk']} (nextAttempt " . ($attempt + 1) . ")");
                    $msg->ack();
                    return;
                }

                Order::query()->whereKey($orderId)->update(['status' => 'REJECTED']);
                $order = Order::find($orderId);

                if ($order) {
                    $event = $this->translateEvent('orders.rejected', $order, $data, [
                        'reason' => $e->getMessage(),
                    ]);
                    $this->publishJson($ch, $exchange, 'orders.rejected', $event, $headers);
                }

                $this->publishRaw($ch, $dlqExchange, $dlqRoutingKey, $body, array_merge($headers, [
                    'x-attempt' => $attempt,
                    'x-error' => $e->getMessage(),
                ]));

                $this->error("Order {$orderId} -> REJECTED (sent to DLQ)");
                $msg->ack();
                return;
            }
        };

        $ch->basic_qos(null, 1, null);
        $ch->basic_consume($queue, '', false, false, false, false, $callback);

        while ($ch->is_consuming()) {
            $ch->wait();
        }

        $ch->close();
        $conn->close();

        return self::SUCCESS;
    }

    private function getHeaders(AMQPMessage $msg): array
    {
        $props = $msg->get_properties();
        if (!isset($props['application_headers'])) {
            return [];
        }
        $table = $props['application_headers'];
        return $table instanceof AMQPTable ? $table->getNativeData() : [];
    }

    private function publishRaw($ch, string $exchange, string $routingKey, string $body, array $headers = []): void
    {
        $msg = new AMQPMessage($body, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable($headers),
        ]);
        $ch->basic_publish($msg, $exchange, $routingKey);
    }

    private function publishJson($ch, string $exchange, string $routingKey, array $payload, array $headers = []): void
    {
        $this->publishRaw($ch, $exchange, $routingKey, json_encode($payload, JSON_UNESCAPED_UNICODE), $headers);
    }

    private function translateEvent(string $event, Order $order, array $data, array $extra = []): array
    {
        return array_merge([
            'event' => $event,
            'occurred_at' => now()->toIso8601String(),
            'order_id' => $order->id,
            'correlation_id' => $order->correlation_id,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'customer_email' => $order->customer_email,
        ], $extra);
    }

    private function reserveInventory(?string $url, array $data): void
    {
        if (!$url) {
            $items = $data['items'] ?? [];
            foreach ($items as $it) {
                $sku = strtoupper((string) ($it['sku'] ?? ''));
                if ($sku === 'FAIL_INV' || $sku === 'NO_STOCK') {
                    throw new RuntimeException('inventory_insufficient');
                }
            }
            return;
        }

        $this->callWithCircuitBreaker('inventory', $url, $data);
    }

    private function chargePayment(?string $url, array $data): void
    {
        if (!$url) {
            $email = strtolower((string) ($data['customer_email'] ?? ''));
            $total = (float) ($data['total_amount'] ?? 0);
            if (str_contains($email, 'failpay') || $total >= 9999) {
                throw new RuntimeException('payment_declined');
            }
            return;
        }

        $this->callWithCircuitBreaker('payment', $url, $data);
    }

    private function callWithCircuitBreaker(string $name, string $url, array $payload): void
    {
        $openUntilKey = "cb:{$name}:open_until";
        $failsKey = "cb:{$name}:fails";

        $openUntil = Cache::get($openUntilKey);
        if ($openUntil && now()->lessThan($openUntil)) {
            throw new RuntimeException("{$name}_circuit_open");
        }

        try {
            $res = Http::timeout(2)
                ->retry(2, 200)
                ->post($url, $payload);

            if (!$res->successful()) {
                throw new RuntimeException("{$name}_http_" . $res->status());
            }

            Cache::forget($failsKey);
            Cache::forget($openUntilKey);
        } catch (Throwable $e) {
            $fails = (int) Cache::get($failsKey, 0);
            $fails++;
            Cache::put($failsKey, $fails, 60);

            if ($fails >= 3) {
                Cache::put($openUntilKey, now()->addSeconds(30), 60);
                Cache::forget($failsKey);
            }

            throw new RuntimeException("{$name}_failed");
        }
    }
}
