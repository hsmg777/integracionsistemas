<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'orders-api',
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::get('/orders', [OrderController::class, 'index']);
