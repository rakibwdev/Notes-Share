<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
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

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Prescription Upload
Route::post('/prescriptions/upload', [\App\Http\Controllers\PrescriptionController::class, 'store'])->name('prescriptions.store');

// Customer Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/search-suggestions', [ProductController::class, 'search'])->name('products.search-suggestions');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
});

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/order/{order}', [ProfileController::class, 'showOrder'])->name('profile.order-details');
    
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/place', [CheckoutController::class, 'placeOrder'])->name('place');
    });
});

// Admin Panel
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('batches', \App\Http\Controllers\Admin\BatchController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'printInvoice'])->name('orders.invoice');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleAdmin'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Prescription Management
    Route::get('/prescriptions', [\App\Http\Controllers\Admin\PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/{prescription}', [\App\Http\Controllers\Admin\PrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::patch('/prescriptions/{prescription}/status', [\App\Http\Controllers\Admin\PrescriptionController::class, 'updateStatus'])->name('admin.prescriptions.update-status');

    Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/delivery', [\App\Http\Controllers\Admin\DeliveryManController::class, 'index'])->name('delivery.index');
    Route::post('/delivery', [\App\Http\Controllers\Admin\DeliveryManController::class, 'store'])->name('delivery.store');
    Route::delete('/delivery/{deliveryMan}', [\App\Http\Controllers\Admin\DeliveryManController::class, 'destroy'])->name('delivery.destroy');
});
