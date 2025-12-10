<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-store text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold">{{ $tenant->name }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard.index') }}" class="px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('tenant.products.index') }}" class="px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-box mr-2"></i>Productos
                    </a>
                    <a href="{{ route('tenant.orders.index') }}" class="px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-shopping-cart mr-2"></i>Pedidos
                    </a>
                    <a href="{{ route('tenant.settings.index') }}" class="px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-cog mr-2"></i>Configuración
                    </a>
                    <a href="/tienda/{{ $tenant->slug }}" target="_blank" class="px-3 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-external-link-alt mr-2"></i>Ver Mi Tienda
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Panel de Administración</h2>
            <p class="text-gray-600 mt-2">Bienvenido a tu tienda, gestiona tus productos y pedidos desde aquí</p>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Productos -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Mis Productos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['my_products'] ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-box text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Pedidos -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pedidos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['my_orders'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Ingresos -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Ingresos Totales</p>
                        <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['my_revenue'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Comisión -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Comisión ({{ $tenant->commission_rate ?? 0 }}%)</p>
                        <p class="text-2xl font-bold text-red-600">${{ number_format($stats['my_commission'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <i class="fas fa-receipt text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ganancias Netas -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <p class="text-sm opacity-90">Tus Ganancias Netas (después de comisión)</p>
            <p class="text-4xl font-bold mt-2">${{ number_format($stats['my_earnings'] ?? 0, 2) }}</p>
        </div>

        <!-- Accesos Rápidos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('tenant.products.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <i class="fas fa-box text-2xl text-blue-600 mr-3"></i>
                    <h3 class="font-semibold text-lg text-gray-800">Gestionar Productos</h3>
                </div>
                <p class="text-gray-600 text-sm">Agrega, edita o elimina productos de tu tienda</p>
            </a>

            <a href="{{ route('tenant.orders.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <i class="fas fa-shopping-cart text-2xl text-green-600 mr-3"></i>
                    <h3 class="font-semibold text-lg text-gray-800">Ver Pedidos</h3>
                </div>
                <p class="text-gray-600 text-sm">Gestiona los pedidos de tus clientes</p>
            </a>

            <a href="{{ route('tenant.settings.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center mb-3">
                    <i class="fas fa-credit-card text-2xl text-purple-600 mr-3"></i>
                    <h3 class="font-semibold text-lg text-gray-800">Configurar Pago</h3>
                </div>
                <p class="text-gray-600 text-sm">Conecta tu cuenta de MercadoPago</p>
            </a>
        </div>

        <!-- Últimos Pedidos -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Últimos Pedidos</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(isset($recentOrders) && count($recentOrders) > 0)
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($order->total ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if(($order->status ?? '') === 'completed') bg-green-100 text-green-800
                                        @elseif(($order->status ?? '') === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p>No hay pedidos recientes</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Productos Más Vendidos</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendidos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(isset($topProducts) && count($topProducts) > 0)
                            @foreach($topProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${{ number_format($product->price ?? 0, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->sold_count ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format(($product->price ?? 0) * ($product->sold_count ?? 0), 2) }}</td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                <p>Aún no hay ventas</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
