<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardWebController;
use App\Http\Controllers\Admin\ProductWebController;
use App\Http\Controllers\Admin\OrderWebController;
use App\Http\Controllers\Admin\CategoryWebController;
use App\Http\Controllers\Admin\CouponWebController;
use App\Http\Controllers\Admin\UserWebController;
use App\Http\Controllers\Admin\ReviewWebController;
use App\Http\Controllers\Admin\PaymentGatewayWebController;
use App\Http\Controllers\Admin\SettingsWebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function() {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [DashboardWebController::class, 'index'])->name('dashboard');
    
    // Products CRUD
    Route::get('/products/search', [ProductWebController::class, 'search'])->name('products.search');
    Route::resource('products', ProductWebController::class);
    
    // Categories CRUD
    Route::resource('categories', CategoryWebController::class)->except(['show']);
    
    // Coupons CRUD
    Route::post('/coupons/validate', [CouponWebController::class, 'validate'])->name('coupons.validate');
    Route::resource('coupons', CouponWebController::class)->except(['show']);
    
    // Orders Management
    Route::get('/orders', [OrderWebController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderWebController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderWebController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Users Management
    Route::get('/users', [UserWebController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserWebController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserWebController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserWebController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserWebController::class, 'destroy'])->name('users.destroy');
    
    // Reviews Management
    Route::get('/reviews', [ReviewWebController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{productReview}', [ReviewWebController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{productReview}', [ReviewWebController::class, 'destroy'])->name('reviews.destroy');
    
    // Payment Gateways
    Route::get('/payment-gateways', [PaymentGatewayWebController::class, 'index'])->name('payment-gateways.index');
    Route::get('/payment-gateways/{paymentGateway}/edit', [PaymentGatewayWebController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('/payment-gateways/{paymentGateway}', [PaymentGatewayWebController::class, 'update'])->name('payment-gateways.update');
    
    // Settings
    Route::get('/settings', [SettingsWebController::class, 'index'])->name('settings.index');
    Route::post('/settings/clear-cache', [SettingsWebController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/update-password', [SettingsWebController::class, 'updatePassword'])->name('settings.update-password');
});
