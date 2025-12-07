@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    
    <!-- Total Products -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Productos</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_products'] ?? 0 }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-blue-100">
            <i class="fas fa-arrow-up mr-1"></i> {{ $stats['active_products'] ?? 0 }} activos
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Total Órdenes</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_orders'] ?? 0 }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-green-100">
            <i class="fas fa-clock mr-1"></i> {{ $stats['pending_orders'] ?? 0 }} pendientes
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Ingresos Totales</p>
                <h3 class="text-3xl font-bold mt-1">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-purple-100">
            <i class="fas fa-calendar mr-1"></i> Este mes
        </div>
    </div>
    
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Total Usuarios</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_users'] ?? 0 }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-orange-100">
            <i class="fas fa-user-plus mr-1"></i> {{ $stats['new_users'] ?? 0 }} nuevos
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4">Órdenes Recientes</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Pedido</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Cliente</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Total</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders ?? [] as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">#{{ $order->order_number }}</td>
                        <td class="py-3 px-4 text-sm">{{ $order->customer_name }}</td>
                        <td class="py-3 px-4 text-sm font-semibold">${{ number_format($order->total, 2) }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">No hay órdenes recientes</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="/admin/orders" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Ver todas las órdenes <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4">Productos Más Vendidos</h3>
        <div class="space-y-4">
            @forelse($top_products ?? [] as $product)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                        @if($product->featured_image)
                            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <i class="fas fa-box text-gray-400"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->sales_count ?? 0 }} ventas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm">${{ number_format($product->price, 2) }}</p>
                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                </div>
            </div>
            @empty
            <p class="text-center py-8 text-gray-500">No hay datos de ventas</p>
            @endforelse
        </div>
        <div class="mt-4">
            <a href="/admin/products" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                Ver todos los productos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="/admin/products/create" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition flex items-center">
        <div class="bg-indigo-100 rounded-full p-4 mr-4">
            <i class="fas fa-plus text-indigo-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-800">Nuevo Producto</h4>
            <p class="text-sm text-gray-600">Agregar producto al catálogo</p>
        </div>
    </a>
    
    <a href="/admin/categories/create" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition flex items-center">
        <div class="bg-purple-100 rounded-full p-4 mr-4">
            <i class="fas fa-tags text-purple-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-800">Nueva Categoría</h4>
            <p class="text-sm text-gray-600">Organizar productos</p>
        </div>
    </a>
    
    <a href="/admin/users/create" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition flex items-center">
        <div class="bg-green-100 rounded-full p-4 mr-4">
            <i class="fas fa-user-plus text-green-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-gray-800">Nuevo Usuario</h4>
            <p class="text-sm text-gray-600">Agregar administrador</p>
        </div>
    </a>
</div>
@endsection
