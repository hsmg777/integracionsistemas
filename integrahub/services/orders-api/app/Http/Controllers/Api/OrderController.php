<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\RabbitPublisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        $status = $request->query('status');

        $query = Order::query()->orderByDesc('id');

        if (is_string($status) && $status !== '') {
            $query->where('status', strtoupper($status));
        }

        $paginator = $query->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => collect($paginator->items())->map(fn (Order $o) => [
                'id' => $o->id,
                'correlation_id' => $o->correlation_id,
                'customer_email' => $o->customer_email,
                'total_amount' => $o->total_amount,
                'currency' => $o->currency,
                'status' => $o->status,
                'created_at' => $o->created_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    public function store(StoreOrderRequest $request, RabbitPublisher $publisher): JsonResponse
    {
        $validated = $request->validated();

        $order = Order::query()->create([
            'correlation_id' => (string) Str::uuid(),
            'customer_email' => $validated['customer_email'] ?? null,
            'total_amount' => $validated['total_amount'],
            'currency' => strtoupper($validated['currency'] ?? 'USD'),
            'status' => 'PENDING',
            'items' => $validated['items'],
            'payload' => $validated,
        ]);

        $routingKey = env('RABBITMQ_ROUTING_KEY', 'orders.created');

        $publisher->publish($routingKey, [
            'event' => 'orders.created',
            'order_id' => $order->id,
            'correlation_id' => $order->correlation_id,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'customer_email' => $order->customer_email,
            'items' => $order->items,
            'created_at' => now()->toIso8601String(),
        ]);

        return response()->json([
            'id' => $order->id,
            'correlation_id' => $order->correlation_id,
            'status' => $order->status,
        ], 201);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json([
            'id' => $order->id,
            'correlation_id' => $order->correlation_id,
            'customer_email' => $order->customer_email,
            'total_amount' => $order->total_amount,
            'currency' => $order->currency,
            'status' => $order->status,
            'items' => $order->items,
            'created_at' => $order->created_at?->toIso8601String(),
        ]);
    }
}
