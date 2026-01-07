<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitPublisher
{
    public function publish(string $routingKey, array $payload): void
    {
        $host = env('RABBITMQ_HOST', 'rabbitmq');
        $port = (int) env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'guest');
        $pass = env('RABBITMQ_PASS', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');

        $exchange = env('RABBITMQ_EXCHANGE', 'orders');
        $queue = env('RABBITMQ_QUEUE', 'orders.created');

        $conn = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        $ch = $conn->channel();

        $ch->exchange_declare($exchange, 'topic', false, true, false);
        $ch->queue_declare($queue, false, true, false, false);
        $ch->queue_bind($queue, $exchange, $routingKey);

        $msg = new AMQPMessage(
            json_encode($payload, JSON_UNESCAPED_UNICODE),
            [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]
        );

        $ch->basic_publish($msg, $exchange, $routingKey);

        $ch->close();
        $conn->close();
    }
}
