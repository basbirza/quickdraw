<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Admin - Shipping Labels
Route::get('/orders/{order}/print-label', [OrderController::class, 'printLabel'])
    ->name('orders.print-label');

Route::get('/orders/print-labels-bulk', [OrderController::class, 'printLabelsBulk'])
    ->name('orders.print-labels-bulk');
