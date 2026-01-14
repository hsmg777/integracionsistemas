<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Api\AnalyticsController;


Route::get('/health', [HealthController::class, 'show']);

Route::post('/auth/token', [AuthTokenController::class, 'token']);



// Protegido con JWT:
Route::middleware('jwt')->group(function () {

    Route::get('/analytics/daily', [AnalyticsController::class, 'daily']);
    Route::post('/analytics/build', [AnalyticsController::class, 'build']);


    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/reserve', [InventoryController::class, 'reserve']);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
});
