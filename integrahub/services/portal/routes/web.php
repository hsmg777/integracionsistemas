<?php

use App\Http\Controllers\PortalOrdersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortalOrdersController::class, 'index'])->name('orders.index');
Route::post('/orders', [PortalOrdersController::class, 'store'])->name('orders.create');
Route::get('/orders/{id}', [PortalOrdersController::class, 'show'])->name('orders.show');
Route::get('/orders/{id}/poll', [PortalOrdersController::class, 'poll'])->name('orders.poll');

