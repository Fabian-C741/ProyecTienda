<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comisiones - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Super Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('super-admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('super-admin.tenants') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-store"></i> Tenants
                    </a>
                    <a href="{{ route('super-admin.users') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                    <a href="{{ route('super-admin.commissions') }}" class="text-blue-600 font-semibold">
                        <i class="fas fa-dollar-sign"></i> Comisiones
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Reporte de Comisiones</h2>
            <p class="text-gray-600">Comisiones generadas por cada tienda</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ventas Totales</p>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalSales, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Comisiones Totales</p>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($totalCommissions, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-store text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Tiendas Activas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $tenants->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commissions Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Desglose por Tienda</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tienda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedidos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ventas Totales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Comisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ganancia Tienda</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tenants as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item['tenant']->logo)
                                            <img src="{{ asset('storage/' . $item['tenant']->logo) }}" alt="{{ $item['tenant']->name }}" class="h-10 w-10 rounded-full">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-gray-600 font-semibold">{{ strtoupper(substr($item['tenant']->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['tenant']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $item['tenant']->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <i class="fas fa-shopping-bag text-gray-400"></i> {{ $item['orders_count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ${{ number_format($item['total_sales'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $item['commission_rate'] }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    ${{ number_format($item['commission_amount'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($item['tenant_earnings'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-chart-bar text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500">No hay datos de comisiones disponibles</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($tenants->count() > 0)
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900" colspan="2">TOTAL</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($totalSales, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                    ${{ number_format($totalCommissions, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($totalSales - $totalCommissions, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Información sobre Comisiones</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Las comisiones se calculan únicamente sobre pedidos con estado "Pagado"</li>
                            <li>El porcentaje de comisión se configura individualmente para cada tienda</li>
                            <li>La ganancia de la tienda es el total de ventas menos la comisión</li>
                            <li>Los datos se actualizan en tiempo real</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
