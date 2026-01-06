<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'orders-api',
        'timestamp' => now()->toIso8601String(),
    ]);
});
