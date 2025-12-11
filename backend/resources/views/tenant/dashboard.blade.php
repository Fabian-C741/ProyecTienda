@extends('tenant.layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600">Bienvenido a tu panel de vendedor</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Ventas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Ventas Totales</p>
                <h3 class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_sales'], 2) }}</h3>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
            </div>
        </div>
    </div>

    <!-- Órdenes -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Órdenes</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</h3>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Productos</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</h3>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-box text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>

    <!-- Comisión -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm mb-1">Comisión ({{ auth()->user()->tenant->commission_rate }}%)</p>
                <h3 class="text-2xl font-bold text-gray-900">${{ number_format($stats['commission'], 2) }}</h3>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-percentage text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="p-6 border-b">
        <h2 class="text-xl font-bold">Órdenes Recientes</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recent_orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-medium">#{{ $order->order_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('vendedor.pedidos.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                            Ver detalles
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No hay órdenes recientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('vendedor.productos.create') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-plus text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Nuevo Producto</h3>
                <p class="text-gray-600 text-sm">Agregar producto a tu tienda</p>
            </div>
        </div>
    </a>

    <a href="{{ route('vendedor.pedidos.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Ver Órdenes</h3>
                <p class="text-gray-600 text-sm">Gestionar todas las órdenes</p>
            </div>
        </div>
    </a>

    <a href="{{ route('tenant.settings') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center">
            <div class="bg-purple-100 p-3 rounded-full mr-4">
                <i class="fas fa-cog text-purple-600 text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Configuración</h3>
                <p class="text-gray-600 text-sm">Personalizar tu tienda</p>
            </div>
        </div>
    </a>
</div>
@endsection
