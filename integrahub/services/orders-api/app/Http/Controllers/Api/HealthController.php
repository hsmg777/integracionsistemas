<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Throwable;

class HealthController extends Controller
{
    public function show()
    {
        $db = ['ok' => false];
        $rabbit = ['ok' => false];

        try {
            DB::select('SELECT 1');
            $db['ok'] = true;
        } catch (Throwable $e) {
            $db['error'] = $e->getMessage();
        }

        try {
            $conn = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'rabbitmq'),
                (int) env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASS', 'guest'),
                env('RABBITMQ_VHOST', '/'),
                insist: false,
                login_method: 'AMQPLAIN',
                login_response: null,
                locale: 'en_US',
                connection_timeout: 2,
                read_write_timeout: 2
            );
            $conn->close();
            $rabbit['ok'] = true;
        } catch (Throwable $e) {
            $rabbit['error'] = $e->getMessage();
        }

        $ok = $db['ok'] && $rabbit['ok'];

        return response()->json([
            'service' => 'orders-api',
            'ok' => $ok,
            'time' => now()->toIso8601String(),
            'deps' => [
                'db' => $db,
                'rabbitmq' => $rabbit,
            ],
        ], $ok ? 200 : 503);
    }
}
