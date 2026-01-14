<?php

use App\Http\Controllers\PortalOrdersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalInventoryController;
use App\Http\Controllers\PortalAnalyticsController;




Route::get('/', [PortalOrdersController::class, 'index'])->name('orders.index');
Route::post('/orders', [PortalOrdersController::class, 'store'])->name('orders.create');
Route::get('/orders/{id}', [PortalOrdersController::class, 'show'])->name('orders.show');
Route::get('/orders/{id}/poll', [PortalOrdersController::class, 'poll'])->name('orders.poll');
Route::get('/inventory', [PortalInventoryController::class, 'index'])->name('inventory.index');
Route::post('/inventory/upload', [PortalInventoryController::class, 'upload'])->name('inventory.upload');
Route::get('/analytics', [PortalAnalyticsController::class, 'index'])
    ->name('analytics.dashboard');

Route::post('/analytics/rebuild', [PortalAnalyticsController::class, 'rebuild'])
    ->name('analytics.rebuild');
