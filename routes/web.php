<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [ShopController::class, 'index'])->name('landing');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
});

Route::middleware('auth')->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/purchase/{product}', [CheckoutController::class, 'purchase'])->name('purchase.product');
});
