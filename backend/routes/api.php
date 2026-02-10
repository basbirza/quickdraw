<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DataSubjectController;
use App\Http\Controllers\Api\HeroImageController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Homepage Content
Route::get('/hero-images', [HeroImageController::class, 'index']);

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Newsletter - Rate limited to 5 requests per minute per IP
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->middleware('throttle:5,1');

// Orders (Checkout) - Rate limited to 3 orders per minute per IP
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:3,1');

// Authentication - Customer login/register
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,60');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

// Customer Account - Requires authentication
Route::prefix('customer')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/orders', [CustomerController::class, 'orders']); // Order history
    Route::get('/orders/{orderNumber}', [CustomerController::class, 'showOrder']); // Single order
    Route::post('/returns/request', [CustomerController::class, 'requestReturn']); // Return request
    Route::put('/profile', [CustomerController::class, 'updateProfile']); // Update profile
    Route::delete('/account', [CustomerController::class, 'deleteAccount']); // Delete account (GDPR)
});

// GDPR Data Subject Rights - REQUIRES AUTHENTICATION
Route::prefix('data-subject')->middleware(['auth:sanctum', 'throttle:3,60'])->group(function () {
    Route::post('/export', [DataSubjectController::class, 'export']); // Article 15
    Route::post('/delete', [DataSubjectController::class, 'delete']); // Article 17
});
