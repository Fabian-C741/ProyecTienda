<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-crown text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold">Super Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('super-admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('super-admin.tenants') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-store mr-2"></i>Tiendas
                    </a>
                    <a href="{{ route('super-admin.users') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-users mr-2"></i>Usuarios
                    </a>
                    <a href="{{ route('super-admin.commissions') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-dollar-sign mr-2"></i>Comisiones
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded hover:bg-indigo-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Tenants -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Tiendas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_tenants'] }}</p>
                        <p class="text-green-600 text-sm mt-1">
                            <i class="fas fa-check-circle"></i> {{ $stats['active_tenants'] }} activas
                        </p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-4">
                        <i class="fas fa-store text-indigo-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Productos</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_products']) }}</p>
                        <p class="text-gray-600 text-sm mt-1">En todas las tiendas</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-box text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Órdenes</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</p>
                        <p class="text-gray-600 text-sm mt-1">Todas las tiendas</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Ingresos Totales</p>
                        <p class="text-3xl font-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</p>
                        <p class="text-gray-600 text-sm mt-1">De todas las ventas</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-dollar-sign text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Usuarios</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-gray-600 text-sm mt-1">Clientes y vendedores</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-users text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Commission Rate -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Comisión Promedio</p>
                        <p class="text-3xl font-bold text-gray-800">10%</p>
                        <p class="text-gray-600 text-sm mt-1">Por cada venta</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-4">
                        <i class="fas fa-percent text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Tenants & Orders -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Tenants -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-store mr-2 text-indigo-600"></i>Tiendas Recientes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentTenants as $tenant)
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="bg-indigo-100 rounded-full w-12 h-12 flex items-center justify-center mr-4">
                                    <i class="fas fa-store text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $tenant->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $tenant->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $tenant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $tenant->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('super-admin.tenants') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                            Ver todas las tiendas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-shopping-cart mr-2 text-green-600"></i>Órdenes Recientes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <div>
                                <h3 class="font-semibold text-gray-800">Orden #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->tenant->name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->customer_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">${{ number_format($order->total_amount, 2) }}</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart Placeholder -->
        <div class="bg-white rounded-lg shadow mt-6 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line mr-2 text-blue-600"></i>Ingresos Mensuales
            </h2>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
                <p class="text-gray-500">Gráfica de ingresos (implementar con Chart.js)</p>
            </div>
        </div>
    </main>
</body>
</html>
