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
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health Check (sin versionado)
Route::get('/health', [HealthController::class, 'check']);
Route::get('/version', [HealthController::class, 'version']);

// Endpoint raíz de la API
Route::get('/v1', function () {
    return response()->json([
        'message' => 'Tienda Multi-Tenant API v1',
        'status' => 'online',
        'version' => '1.0.0',
        'endpoints' => [
            'health' => '/api/health',
            'version' => '/api/version',
            'auth' => '/api/v1/login',
            'register' => '/api/v1/register',
            'products' => '/api/v1/products',
            'categories' => '/api/v1/categories',
            'cart' => '/api/v1/cart',
            'orders' => '/api/v1/orders',
            'documentation' => 'https://github.com/Fabian-C741/ProyecTienda'
        ],
        'timestamp' => now()->toIso8601String()
    ]);
});

// Rutas públicas
Route::prefix('v1')->middleware(['rate.limit:120,1'])->group(function () {
    
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
    
    // Reseñas públicas
    Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
});

// Rutas protegidas (requieren autenticación)
Route::prefix('v1')->middleware(['auth:sanctum', 'audit', 'rate.limit:60,1'])->group(function () {
    
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
    
    // Reseñas de productos
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);
    Route::put('/products/{productId}/reviews/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/products/{productId}/reviews/{reviewId}', [ReviewController::class, 'destroy']);
    Route::post('/products/{productId}/reviews/{reviewId}/respond', [ReviewController::class, 'respond']);
    
    // Upload de imágenes
    Route::post('/upload/image', [UploadController::class, 'uploadImage']);
    Route::post('/upload/multiple', [UploadController::class, 'uploadMultiple']);
    Route::delete('/upload/image', [UploadController::class, 'deleteImage']);
    
    // Webhooks de pagos (sin autenticación pero con validación)
    Route::post('/webhooks/mercadopago', [PaymentController::class, 'webhookMercadoPago'])->withoutMiddleware(['auth:sanctum', 'audit']);
    Route::post('/webhooks/stripe', [PaymentController::class, 'webhookStripe'])->withoutMiddleware(['auth:sanctum', 'audit']);
    Route::post('/webhooks/paypal', [PaymentController::class, 'webhookPayPal'])->withoutMiddleware(['auth:sanctum', 'audit']);
});

// Rutas de administración (requieren roles específicos)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'audit'])->group(function () {
    
    // Moderación de reseñas
    Route::put('/reviews/{reviewId}/moderate', [ReviewController::class, 'moderate'])
        ->middleware('role:tenant_admin|super_admin');
    
    // Dashboard y estadísticas
    Route::get('/dashboard/stats', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'stats'])
        ->middleware('role:tenant_admin|vendedor|super_admin');
    Route::get('/dashboard/revenue-chart', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'revenueChart'])
        ->middleware('role:tenant_admin|super_admin');
    
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
