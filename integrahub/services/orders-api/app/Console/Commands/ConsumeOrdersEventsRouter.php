<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPIOWaitException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class ConsumeOrdersEventsRouter extends Command
{
    protected $signature = 'consume:orders-events-router';
    protected $description = 'Routes orders.confirmed / orders.rejected to notifications exchange (ops/customer)';

    public function handle(): int
    {
        while (true) {
            try {
                $this->runRouter();
            } catch (\Throwable $e) {
                $this->error("ROUTER crashed: {$e->getMessage()}");
                sleep(1);
            }
        }
    }

    private function runRouter(): void
    {
        $host = env('RABBITMQ_HOST', 'rabbitmq');
        $port = (int) env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'guest');
        $pass = env('RABBITMQ_PASS', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');

        $ordersExchange = env('RABBITMQ_EXCHANGE', 'orders');
        $ordersEventsQueue = env('ORDERS_EVENTS_QUEUE', 'orders.events');
        $bind1 = env('ORDERS_EVENTS_BINDING_1', 'orders.confirmed');
        $bind2 = env('ORDERS_EVENTS_BINDING_2', 'orders.rejected');

        $notificationsExchange = env('NOTIFICATIONS_EXCHANGE', 'notifications');

        $conn = new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $pass,
            $vhost,
            false,
            'AMQPLAIN',
            null,
            'en_US',
            3.0,
            60.0
        );

        $ch = $conn->channel();

        $ch->exchange_declare($ordersExchange, 'topic', false, true, false);
        $ch->exchange_declare($notificationsExchange, 'topic', false, true, false);

        $ch->queue_declare($ordersEventsQueue, false, true, false, false);
        $ch->queue_bind($ordersEventsQueue, $ordersExchange, $bind1);
        $ch->queue_bind($ordersEventsQueue, $ordersExchange, $bind2);

        $this->info("Router listening: {$ordersEventsQueue} (bind: {$bind1}, {$bind2})");

        $callback = function (AMQPMessage $msg) use ($ch, $notificationsExchange) {
            $data = json_decode($msg->getBody(), true);

            if (!is_array($data)) {
                $this->error("Router: invalid JSON");
                $msg->ack();
                return;
            }

            $event = (string)($data['event'] ?? '');
            if (!in_array($event, ['orders.confirmed', 'orders.rejected'], true)) {
                $this->warn("Router: ignored event={$event}");
                $msg->ack();
                return;
            }

            $headers = $this->getHeaders($msg);
            $headers['x-routed-by'] = 'orders-events-router';
            $headers['x-routed-at'] = now()->toIso8601String();

            $opsKey = $event === 'orders.confirmed' ? 'ops.orders.confirmed' : 'ops.orders.rejected';
            $custKey = $event === 'orders.confirmed' ? 'customer.orders.confirmed' : 'customer.orders.rejected';

            $opsPayload = [
                'type' => 'ops_notification',
                'event' => $event,
                'occurred_at' => $data['occurred_at'] ?? now()->toIso8601String(),
                'order_id' => $data['order_id'] ?? null,
                'correlation_id' => $data['correlation_id'] ?? null,
                'status' => $data['status'] ?? null,
                'total_amount' => $data['total_amount'] ?? null,
                'currency' => $data['currency'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'reason' => $data['reason'] ?? null,
            ];

            $custPayload = [
                'type' => 'customer_notification',
                'event' => $event,
                'occurred_at' => $data['occurred_at'] ?? now()->toIso8601String(),
                'order_id' => $data['order_id'] ?? null,
                'correlation_id' => $data['correlation_id'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'status' => $data['status'] ?? null,
                'message' => $event === 'orders.confirmed'
                    ? 'Tu pedido fue confirmado.'
                    : 'Tu pedido fue rechazado. Por favor revisa tu pago o disponibilidad.',
            ];

            $this->publishJson($ch, $notificationsExchange, $opsKey, $opsPayload, $headers);
            $this->publishJson($ch, $notificationsExchange, $custKey, $custPayload, $headers);

            $this->info("Routed event={$event} -> {$opsKey}, {$custKey}");
            $msg->ack();
        };

        $ch->basic_qos(null, 5, null);
        $ch->basic_consume($ordersEventsQueue, '', false, false, false, false, $callback);

        while ($ch->is_consuming()) {
            try {
                $ch->wait(null, false, 5);
            } catch (AMQPTimeoutException $e) {
                continue;
            } catch (AMQPIOWaitException $e) {
                continue;
            }
        }

        $ch->close();
        $conn->close();
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

    private function publishJson($ch, string $exchange, string $routingKey, array $payload, array $headers = []): void
    {
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);

        $out = new AMQPMessage($body, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => new AMQPTable($headers),
        ]);

        $ch->basic_publish($out, $exchange, $routingKey);
    }
}
