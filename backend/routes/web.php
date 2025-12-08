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
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController;

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

// Public Shop Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/productos', [ShopProductController::class, 'index'])->name('shop.index');
Route::get('/productos/buscar', [ShopProductController::class, 'search'])->name('shop.search');
Route::get('/categoria/{slug}', [ShopProductController::class, 'category'])->name('shop.category');
Route::get('/producto/{slug}', [ShopProductController::class, 'show'])->name('shop.product');

// Cart Routes
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');

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
    Route::post('/coupons/validate', [CouponWebController::class, 'validateCoupon'])->name('coupons.validate');
    Route::resource('coupons', CouponWebController::class)->except(['show']);
    
    // Orders Management
    Route::get('/orders', [OrderWebController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderWebController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderWebController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Users Management
    Route::resource('users', UserWebController::class)->except(['create', 'store']);
    Route::get('/users/create', [UserWebController::class, 'create'])->name('users.create');
    Route::post('/users', [UserWebController::class, 'store'])->name('users.store');
    
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
    Route::post('/settings/update-email', [SettingsWebController::class, 'updateEmail'])->name('settings.update-email');
});
