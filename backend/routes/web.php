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
// NOTA: Mantenido para compatibilidad futura con VPS (requiere SSL wildcard)
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

// Storefront Routes por PATH (Producción con SSL estándar: /tienda/{slug})
// Compatible con hosting compartido sin SSL wildcard
// Rate limited: 100 requests por minuto para prevenir ataques DDoS
Route::prefix('tienda')->middleware(['path.tenant', 'throttle:100,1'])->name('tienda.')->group(function () {
    Route::get('/{slug}', [StorefrontController::class, 'home'])->name('home');
    Route::get('/{slug}/productos', [StorefrontController::class, 'products'])->name('products');
    Route::get('/{slug}/producto/{productSlug}', [StorefrontController::class, 'product'])->name('product');
    Route::get('/{slug}/nosotros', [StorefrontController::class, 'about'])->name('about');
    Route::get('/{slug}/contacto', [StorefrontController::class, 'contact'])->name('contact');
    Route::get('/{slug}/pagina/{pageSlug}', [StorefrontController::class, 'page'])->name('page');
    
    // Autenticación dentro del contexto de la tienda
    Route::get('/{slug}/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/{slug}/registro', [AuthController::class, 'register']);
    Route::get('/{slug}/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/{slug}/login', [AuthController::class, 'login']);
    
    // Carrito dentro del contexto de la tienda
    Route::get('/{slug}/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/{slug}/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/{slug}/carrito/actualizar/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{slug}/carrito/eliminar/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/{slug}/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/{slug}/carrito/cupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/{slug}/carrito/cupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
    
    // Checkout y pedidos dentro del contexto de la tienda (requiere auth)
    Route::middleware('auth')->group(function () {
        Route::get('/{slug}/mis-pedidos', [StorefrontController::class, 'orders'])->name('orders');
        Route::get('/{slug}/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/{slug}/checkout/procesar', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/{slug}/pedido/exito/{order}', [CheckoutController::class, 'success'])->name('order.success');
    });
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

// ========================================
// VENDOR PANEL (tenant_admin)
// Panel de administración para vendedores
// Prefijo: /vendedor
// Rate limited: 60 requests por minuto
// ========================================
Route::prefix('vendedor')->middleware(['auth', 'tenant', 'throttle:60,1'])->name('vendedor.')->group(function () {
    // Products CRUD
    Route::get('/productos', [TenantProductController::class, 'index'])->name('productos.index');
    Route::get('/productos/crear', [TenantProductController::class, 'create'])->name('productos.create');
    Route::post('/productos', [TenantProductController::class, 'store'])->name('productos.store');
    Route::get('/productos/{product}/editar', [TenantProductController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{product}', [TenantProductController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{product}', [TenantProductController::class, 'destroy'])->name('productos.destroy');
    
    // Orders Management
    Route::get('/pedidos', [TenantOrderController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{order}', [TenantOrderController::class, 'show'])->name('pedidos.show');
    Route::put('/pedidos/{order}/estado', [TenantOrderController::class, 'updateStatus'])->name('pedidos.updateStatus');
    
    // Settings
    Route::get('/configuracion', [TenantSettingsController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/general', [TenantSettingsController::class, 'updateGeneral'])->name('configuracion.general');
    Route::post('/configuracion/tienda', [TenantSettingsController::class, 'updateStore'])->name('configuracion.tienda');
    Route::post('/configuracion/apariencia', [TenantSettingsController::class, 'updateAppearance'])->name('configuracion.apariencia');
    Route::post('/configuracion/mercadopago', [TenantSettingsController::class, 'updateMercadoPago'])->name('configuracion.mercadopago');
    Route::post('/configuracion/marca', [TenantSettingsController::class, 'updateBranding'])->name('configuracion.marca');
    Route::post('/configuracion/diseno', [TenantSettingsController::class, 'updateDesign'])->name('configuracion.diseno');
    Route::post('/configuracion/redes', [TenantSettingsController::class, 'updateSocial'])->name('configuracion.redes');
    Route::post('/configuracion/seo', [TenantSettingsController::class, 'updateSeo'])->name('configuracion.seo');
    Route::post('/configuracion/hero', [TenantSettingsController::class, 'updateHero'])->name('configuracion.hero');
});

// ========================================
// SUPER ADMIN PANEL
// Panel de administración global del sistema
// Prefijo: /super-admin
// ========================================
Route::prefix('super-admin')->middleware(['auth', 'super.admin'])->name('super-admin.')->group(function () {
    // Dashboard propio del super admin
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    // Tenants Management
    Route::get('/tenants', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'tenants'])->name('tenants');
    Route::get('/tenants/create', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createTenant'])->name('tenants.create');
    Route::post('/tenants', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'storeTenant'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'showTenant'])->name('tenants.show');
    Route::get('/tenants/{tenant}/edit', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editTenant'])->name('tenants.edit');
    Route::put('/tenants/{tenant}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updateTenant'])->name('tenants.update');
    Route::delete('/tenants/{tenant}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'destroyTenant'])->name('tenants.destroy');
    Route::post('/tenants/{tenant}/toggle-status', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'toggleTenantStatus'])->name('tenants.toggle-status');
    
    // Users Management (all users)
    Route::get('/users', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/details', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'getUserDetails'])->name('users.details');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/reset-password', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'deleteUser'])->name('users.delete');
    
    // Commissions & Revenue
    Route::get('/commissions', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'commissions'])->name('commissions');
    
    // Vendor Requests Management
    Route::get('/vendor-requests', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'vendorRequests'])->name('vendor-requests');
    Route::get('/vendor-requests/{id}', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'showVendorRequest'])->name('vendor-requests.show');
    Route::post('/vendor-requests/{id}/approve', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'approveVendorRequest'])->name('vendor-requests.approve');
    Route::post('/vendor-requests/{id}/reject', [\App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'rejectVendorRequest'])->name('vendor-requests.reject');
});

// Public Vendor Registration Routes
Route::get('/registro-vendedor', [\App\Http\Controllers\VendorRequestController::class, 'showForm'])->name('vendor.request.form');
Route::post('/registro-vendedor', [\App\Http\Controllers\VendorRequestController::class, 'submitRequest'])->name('vendor.request.submit');

