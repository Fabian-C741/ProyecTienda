<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online - Multi-Tenant E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
    <div class="text-center text-white px-6">
        <div class="mb-8">
            <i class="fas fa-shopping-bag text-8xl mb-4"></i>
        </div>
        <h1 class="text-6xl font-bold mb-4">🛍️ Tienda Online</h1>
        <p class="text-2xl mb-8 text-white text-opacity-90">Sistema Multi-Tenant E-Commerce</p>
        
        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 max-w-2xl mx-auto mb-8">
            <div class="grid grid-cols-2 gap-6 text-left">
                <div>
                    <h3 class="font-bold text-xl mb-2">✅ API REST Completa</h3>
                    <p class="text-sm text-white text-opacity-80">Endpoints para autenticación, productos, órdenes y pagos</p>
                </div>
                <div>
                    <h3 class="font-bold text-xl mb-2">🔐 Autenticación Segura</h3>
                    <p class="text-sm text-white text-opacity-80">Laravel Sanctum con roles y permisos</p>
                </div>
                <div>
                    <h3 class="font-bold text-xl mb-2">💳 Múltiples Pagos</h3>
                    <p class="text-sm text-white text-opacity-80">Mercado Pago, Stripe, PayPal integrados</p>
                </div>
                <div>
                    <h3 class="font-bold text-xl mb-2">⭐ Sistema de Reviews</h3>
                    <p class="text-sm text-opacity-80">Calificaciones y comentarios de productos</p>
                </div>
            </div>
        </div>
        
        <div class="space-x-4">
            <a href="/admin/dashboard" class="inline-block px-8 py-4 bg-white text-indigo-600 rounded-full font-bold text-lg hover:bg-opacity-90 transition shadow-lg">
                <i class="fas fa-tachometer-alt mr-2"></i>Panel Admin
            </a>
            <a href="/api/v1" class="inline-block px-8 py-4 bg-indigo-600 bg-opacity-20 backdrop-blur-sm text-white border-2 border-white rounded-full font-bold text-lg hover:bg-opacity-30 transition">
                <i class="fas fa-code mr-2"></i>Ver API
            </a>
        </div>
        
        <div class="mt-12 text-sm text-white text-opacity-70">
            <p>API URL: <code class="bg-black bg-opacity-20 px-2 py-1 rounded">https://ingreso-tienda.kcrsf.com/api/v1</code></p>
            <p class="mt-2">Documentación: <a href="https://github.com/Fabian-C741/ProyecTienda" class="underline">GitHub</a></p>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
