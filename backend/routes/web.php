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
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\TenantProductController;
use App\Http\Controllers\Tenant\TenantOrderController;
use App\Http\Controllers\Tenant\TenantSettingsController;
use App\Http\Controllers\Storefront\StorefrontController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

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

// Public Shop Routes (dominio principal)
Route::domain(config('app.main_domain', 'ingreso-tienda.kcrsf.com'))->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/productos', [ShopProductController::class, 'index'])->name('shop.index');
    Route::get('/productos/buscar', [ShopProductController::class, 'search'])->name('shop.search');
    Route::get('/categoria/{slug}', [ShopProductController::class, 'category'])->name('shop.category');
    Route::get('/producto/{slug}', [ShopProductController::class, 'show'])->name('shop.product');
});

// Storefront Routes (Subdominios de cada vendedor: {tenant}.ingreso-tienda.kcrsf.com)
Route::domain('{subdomain}.' . config('app.main_domain', 'ingreso-tienda.kcrsf.com'))
    ->middleware('subdomain.tenant')
    ->name('storefront.')
    ->group(function () {
        Route::get('/', [StorefrontController::class, 'home'])->name('home');
        Route::get('/productos', [StorefrontController::class, 'products'])->name('products');
        Route::get('/producto/{productSlug}', [StorefrontController::class, 'product'])->name('product');
        Route::get('/nosotros', [StorefrontController::class, 'about'])->name('about');
        Route::get('/contacto', [StorefrontController::class, 'contact'])->name('contact');
        Route::get('/pagina/{slug}', [StorefrontController::class, 'page'])->name('page');
    });

// Storefront Routes alternativas (para desarrollo local sin subdominios)
Route::prefix('tienda')->name('storefront.alt.')->group(function () {
    Route::get('/{slug}', [StorefrontController::class, 'home'])->name('home');
    Route::get('/{slug}/productos', [StorefrontController::class, 'products'])->name('products');
    Route::get('/{slug}/nosotros', [StorefrontController::class, 'about'])->name('about');
    Route::get('/{slug}/contacto', [StorefrontController::class, 'contact'])->name('contact');
    Route::get('/{slug}/producto/{productSlug}', [StorefrontController::class, 'product'])->name('product');
    Route::get('/{slug}/pagina/{pageSlug}', [StorefrontController::class, 'page'])->name('page');
});

// Authentication Routes
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::get('/tenant/login', [AuthController::class, 'showAdminLogin'])->name('tenant.login');
Route::get('/super-admin/login', [AuthController::class, 'showAdminLogin'])->name('super-admin.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token, 'email' => request()->email]);
})->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Cart Routes
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/carrito/cupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/carrito/cupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/procesar', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pedido/exito/{order}', [CheckoutController::class, 'success'])->name('order.success');
});

// ========================================
// DASHBOARD UNIFICADO
// Un solo dashboard que detecta el rol del usuario
// y muestra el panel correspondiente
// ========================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
});

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function() {
        return redirect()->route('dashboard.index');
    });
    Route::get('/dashboard', function() {
        return redirect()->route('dashboard.index');
    })->name('dashboard');
    
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
    
    // Reports
    Route::get('/reports', [ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/export', [ReportController::class, 'exportOrders'])->name('reports.export');
    
    // Settings
    Route::get('/settings', [SettingsWebController::class, 'index'])->name('settings.index');
    Route::post('/settings/clear-cache', [SettingsWebController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/update-password', [SettingsWebController::class, 'updatePassword'])->name('settings.update-password');
    Route::post('/settings/update-email', [SettingsWebController::class, 'updateEmail'])->name('settings.update-email');
});

// Tenant Panel Routes (for vendors/tenant admins)
Route::prefix('tenant')->middleware(['auth', 'tenant'])->name('tenant.')->group(function () {
    // Dashboard redirige al unificado
    Route::get('/dashboard', function() {
        return redirect()->route('dashboard.index');
    })->name('dashboard');
    
    // Products CRUD
    Route::resource('products', TenantProductController::class);
    
    // Orders Management
    Route::get('/orders', [TenantOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [TenantOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [TenantOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Settings
    Route::get('/settings', [TenantSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [TenantSettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::post('/settings/branding', [TenantSettingsController::class, 'updateBranding'])->name('settings.branding');
    Route::post('/settings/design', [TenantSettingsController::class, 'updateDesign'])->name('settings.design');
    Route::post('/settings/social', [TenantSettingsController::class, 'updateSocial'])->name('settings.social');
    Route::post('/settings/seo', [TenantSettingsController::class, 'updateSeo'])->name('settings.seo');
    Route::post('/settings/hero', [TenantSettingsController::class, 'updateHero'])->name('settings.hero');
});

// Super Admin Panel Routes (gestión global del sistema)
Route::prefix('super-admin')->middleware(['auth', 'super.admin'])->name('super-admin.')->group(function () {
    // Dashboard redirige al unificado
    Route::get('/dashboard', function() {
        return redirect()->route('dashboard.index');
    })->name('dashboard');
    
    // Tenants Management
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');
    Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [SuperAdminController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [SuperAdminController::class, 'showTenant'])->name('tenants.show');
    Route::get('/tenants/{tenant}/edit', [SuperAdminController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [SuperAdminController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [SuperAdminController::class, 'destroyTenant'])->name('tenants.destroy');
    Route::post('/tenants/{tenant}/toggle-status', [SuperAdminController::class, 'toggleTenantStatus'])->name('tenants.toggle-status');
    
    // Users Management (all users)
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/details', [SuperAdminController::class, 'getUserDetails'])->name('users.details');
    Route::get('/users/{id}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/reset-password', [SuperAdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [SuperAdminController::class, 'deleteUser'])->name('users.delete');
    
    // Commissions & Revenue
    Route::get('/commissions', [SuperAdminController::class, 'commissions'])->name('commissions');
});

