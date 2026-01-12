<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthTokenController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'orders-api',
        'timestamp' => now()->toIso8601String(),
    ]);
});

Route::post('/auth/token', [AuthTokenController::class, 'token']);
// Protegido con JWT:
Route::middleware('jwt')->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
});
