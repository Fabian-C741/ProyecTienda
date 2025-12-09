<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-crown text-purple-600 text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-900">Panel Super Admin</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Tenants -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tiendas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_tenants'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-check-circle"></i> {{ $stats['active_tenants'] }} activas
                        </p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-store text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Productos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_products'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">En todas las tiendas</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-box text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ingresos Totales</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_revenue'], 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $stats['total_orders'] }} pedidos</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Commissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Comisiones</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_commissions'], 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ number_format(($stats['total_commissions'] / max($stats['total_revenue'], 1)) * 100, 1) }}% promedio
                        </p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-4">
                        <i class="fas fa-percentage text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('super-admin.tenants') }}" class="bg-white hover:bg-gray-50 rounded-lg shadow p-6 text-center transition">
                <i class="fas fa-store text-blue-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Gestionar Tiendas</h3>
                <p class="text-sm text-gray-600 mt-1">Ver, crear y editar tiendas</p>
            </a>

            <a href="{{ route('super-admin.users') }}" class="bg-white hover:bg-gray-50 rounded-lg shadow p-6 text-center transition">
                <i class="fas fa-users text-green-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Usuarios</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $stats['total_users'] }} usuarios totales</p>
            </a>

            <a href="{{ route('super-admin.commissions') }}" class="bg-white hover:bg-gray-50 rounded-lg shadow p-6 text-center transition">
                <i class="fas fa-chart-line text-purple-600 text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Comisiones</h3>
                <p class="text-sm text-gray-600 mt-1">Reporte detallado</p>
            </a>

            <a href="{{ route('super-admin.tenants.create') }}" class="bg-blue-600 hover:bg-blue-700 rounded-lg shadow p-6 text-center transition">
                <i class="fas fa-plus-circle text-white text-3xl mb-3"></i>
                <h3 class="text-lg font-semibold text-white">Nueva Tienda</h3>
                <p class="text-sm text-blue-100 mt-1">Crear tienda nueva</p>
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Tenants -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-store text-gray-600 mr-2"></i>Últimas Tiendas
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($recentTenants as $tenant)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center">
                                @if($tenant->logo)
                                    <img src="{{ asset('storage/' . $tenant->logo) }}" alt="{{ $tenant->name }}" class="w-10 h-10 rounded-full mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-gray-600 font-semibold">{{ strtoupper(substr($tenant->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $tenant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $tenant->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($tenant->status === 'active')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle"></i> Activa
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle"></i> Inactiva
                                    </span>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $tenant->commission_rate }}% comisión</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No hay tiendas creadas aún</p>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('super-admin.tenants') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todas las tiendas <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-shopping-bag text-gray-600 mr-2"></i>Últimos Pedidos
                    </h2>
                </div>
                <div class="p-6">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="font-medium text-gray-900">#{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->tenant->name ?? 'Sin tienda' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->name ?? 'Cliente' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                @if($order->payment_status === 'paid')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check"></i> Pagado
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No hay pedidos recientes</p>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todos los pedidos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1"></i>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Panel de Súper Administrador</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><i class="fas fa-check mr-2"></i>Tienes acceso total a todas las tiendas y configuraciones</li>
                        <li><i class="fas fa-check mr-2"></i>Puedes crear, editar y desactivar tiendas</li>
                        <li><i class="fas fa-check mr-2"></i>Visualiza comisiones y estadísticas globales</li>
                        <li><i class="fas fa-check mr-2"></i>Gestiona usuarios de todo el sistema</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
