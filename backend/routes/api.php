<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\HealthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health Check (sin versionado)
Route::get('/health', [HealthController::class, 'check']);
Route::get('/version', [HealthController::class, 'version']);

// Rutas públicas
Route::prefix('v1')->group(function () {
    
    // Autenticación
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Productos públicos
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/products/{id}/related', [ProductController::class, 'relatedProducts']);

    // Categorías públicas
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/products', [CategoryController::class, 'products']);

    // Tenants públicos
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('/tenants/{slug}', [TenantController::class, 'show']);

    // Carrito (guest y autenticado)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
});

// Rutas protegidas (requieren autenticación)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/password', [AuthController::class, 'changePassword']);

    // Órdenes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'create']);
    Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel']);

    // Pagos
    Route::post('/payments/mercadopago/create', [PaymentController::class, 'createMercadoPago']);
    Route::post('/payments/stripe/create', [PaymentController::class, 'createStripe']);
    Route::post('/payments/paypal/create', [PaymentController::class, 'createPayPal']);
    
    // Webhooks de pagos (sin autenticación pero con validación)
    Route::post('/webhooks/mercadopago', [PaymentController::class, 'webhookMercadoPago'])->withoutMiddleware('auth:sanctum');
    Route::post('/webhooks/stripe', [PaymentController::class, 'webhookStripe'])->withoutMiddleware('auth:sanctum');
    Route::post('/webhooks/paypal', [PaymentController::class, 'webhookPayPal'])->withoutMiddleware('auth:sanctum');
});

// Rutas de administración (requieren roles específicos)
Route::prefix('v1/admin')->middleware(['auth:sanctum'])->group(function () {
    
    // Super Admin - Gestión de Tenants
    Route::middleware(['role:super_admin'])->group(function () {
        Route::apiResource('tenants', TenantController::class)->except(['index', 'show']);
        Route::post('/tenants/{id}/activate', [TenantController::class, 'activate']);
        Route::post('/tenants/{id}/deactivate', [TenantController::class, 'deactivate']);
    });

    // Tenant Admin - Gestión de productos
    Route::middleware(['role:tenant_admin|super_admin'])->group(function () {
        Route::apiResource('products', \App\Http\Controllers\Api\Admin\ProductController::class);
        Route::post('/products/{id}/publish', [\App\Http\Controllers\Api\Admin\ProductController::class, 'publish']);
        Route::post('/products/{id}/unpublish', [\App\Http\Controllers\Api\Admin\ProductController::class, 'unpublish']);
        
        Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class);
        
        Route::get('/orders', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
        Route::put('/orders/{id}/status', [\App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);
        
        Route::apiResource('payment-gateways', \App\Http\Controllers\Api\Admin\PaymentGatewayController::class);
    });
});
