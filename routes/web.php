<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Customer Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/place', [CheckoutController::class, 'placeOrder'])->name('place');
});

// Placeholder for Auth routes (Login/Register)
Route::get('/login', fn () => 'Login Page Coming Soon')->name('login');
Route::get('/register', fn () => 'Register Page Coming Soon')->name('register');
Route::get('/profile', fn () => 'Profile Page Coming Soon')->name('profile');

// Admin Panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/delivery', [\App\Http\Controllers\Admin\DeliveryManController::class, 'index'])->name('delivery.index');
    Route::post('/delivery', [\App\Http\Controllers\Admin\DeliveryManController::class, 'store'])->name('delivery.store');
    Route::delete('/delivery/{deliveryMan}', [\App\Http\Controllers\Admin\DeliveryManController::class, 'destroy'])->name('delivery.destroy');
});
