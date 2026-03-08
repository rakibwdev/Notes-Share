<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DeliveryManController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Resource Routes
    Route::apiResource('categories', CategoryController::class)->names('api.categories');
    Route::apiResource('products', ProductController::class)->names('api.products');
    Route::apiResource('batches', BatchController::class)->names('api.batches');
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::apiResource('delivery-men', DeliveryManController::class)->names('api.delivery-men');
    Route::apiResource('orders', OrderController::class)->names('api.orders');
    Route::apiResource('banners', BannerController::class)->names('api.banners');
});

// Public Product Discovery (For Mobile App)
Route::get('/public/products', [ProductController::class, 'index']);
Route::get('/public/banners', [BannerController::class, 'index']);
