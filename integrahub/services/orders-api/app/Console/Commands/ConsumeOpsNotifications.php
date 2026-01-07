<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPIOWaitException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class ConsumeOpsNotifications extends Command
{
    protected $signature = 'consume:ops-notifications';
    protected $description = 'Consume ops notifications from RabbitMQ';

    public function handle(): int
    {
        $exchange = env('NOTIFICATIONS_EXCHANGE', 'notifications');
        $queue    = env('OPS_QUEUE', 'ops.notifications');
        $binding  = env('OPS_BINDING', 'ops.#');

        $this->info("OPS listening: {$queue} (bind: {$binding})");

        while (true) {
            $conn = null;
            $ch = null;

            try {
                $conn = $this->connect();
                $ch = $conn->channel();

                $ch->exchange_declare($exchange, 'topic', false, true, false);
                $ch->queue_declare($queue, false, true, false, false);
                $ch->queue_bind($queue, $exchange, $binding);

                $ch->basic_qos(null, 1, null);

                $ch->basic_consume($queue, '', false, false, false, false, function (AMQPMessage $msg) {
                    $this->line('[OPS] ' . $msg->getBody());
                    $msg->ack();
                });

                // Loop estable: timeout corto para no “morir” cuando no hay mensajes.
                while ($ch->is_consuming()) {
                    try {
                        $ch->wait(null, false, 1);
                    } catch (AMQPTimeoutException|AMQPIOWaitException $e) {
                        // No hay mensajes / interrupted syscall: seguimos vivos.
                        continue;
                    }
                }
            } catch (Throwable $e) {
                $this->error('OPS consumer crashed: ' . $e->getMessage());
                sleep(2);
            } finally {
                try { if ($ch) $ch->close(); } catch (Throwable $e) {}
                try { if ($conn) $conn->close(); } catch (Throwable $e) {}
            }
        }

        // never reached
        // return self::SUCCESS;
    }

    private function connect(): AMQPStreamConnection
    {
        $host = env('RABBITMQ_HOST', 'rabbitmq');
        $port = (int) env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'guest');
        $pass = env('RABBITMQ_PASS', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');

        $connectionTimeout = (float) env('RABBITMQ_CONNECTION_TIMEOUT', 3.0);
        $rwTimeout         = (float) env('RABBITMQ_RW_TIMEOUT', 120.0);

        // Para evitar “Missed server heartbeat” en demos/containers:
        $heartbeat         = (int) env('RABBITMQ_HEARTBEAT', 0);

        return new AMQPStreamConnection(
            $host,
            $port,
            $user,
            $pass,
            $vhost,
            false,
            'AMQPLAIN',
            null,
            'en_US',
            $connectionTimeout,
            $rwTimeout,
            null,
            false,
            $heartbeat
        );
    }
}
