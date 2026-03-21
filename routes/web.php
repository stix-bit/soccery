<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductSearchController;

Route::get('/', [ShopController::class, 'index'])->name('landing');

Auth::routes();

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])
    ->middleware('throttle:6,1')
    ->name('verification.resend');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/search', [ProductSearchController::class, 'index'])->name('search.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('product-images/{image}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');
    Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('charts', [ChartController::class, 'index'])->name('charts.index');

    Route::resource('brands', BrandController::class);
    Route::post('brands/import', [BrandController::class, 'import'])->name('brands.import');
    Route::post('brands/{brand}/restore', [BrandController::class, 'restore'])->name('brands.restore');

    Route::resource('categories', CategoryController::class);
    Route::post('categories/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::post('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/purchase/{product}', [CheckoutController::class, 'addToCart'])->name('purchase.product');

    Route::get('/cart', [CheckoutController::class, 'cart'])->name('cart.index');
    Route::post('/cart/{product}', [CheckoutController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/{product}', [CheckoutController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{product}', [CheckoutController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.process');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
