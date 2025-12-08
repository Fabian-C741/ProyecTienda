@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Bienvenido al panel de administración</p>
</div>

<!-- Estadísticas principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Products -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Productos</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_products'] ?? 0 }}</h3>
                <p class="text-blue-100 text-xs mt-2">{{ $stats['active_products'] ?? 0 }} activos</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Total Órdenes</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_orders'] ?? 0 }}</h3>
                <p class="text-green-100 text-xs mt-2">{{ $stats['pending_orders'] ?? 0 }} pendientes</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Ingresos Totales</p>
                <h3 class="text-3xl font-bold mt-1">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                <p class="text-purple-100 text-xs mt-2">Completados</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm">Total Usuarios</p>
                <h3 class="text-3xl font-bold mt-1">{{ $stats['total_users'] ?? 0 }}</h3>
                <p class="text-orange-100 text-xs mt-2">{{ $stats['new_users'] ?? 0 }} nuevos (30d)</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-4">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-receipt text-purple-500 mr-2"></i>
            Órdenes Recientes
        </h3>
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
                        <td class="py-3 px-4 text-sm font-mono">#{{ $order->order_number ?? $order->id }}</td>
                        <td class="py-3 px-4 text-sm">{{ $order->customer_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm font-semibold">${{ number_format($order->total, 2) }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay órdenes recientes</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center">
                Ver todas las órdenes <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Top Products List -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-star text-yellow-500 mr-2"></i>
            Top 5 Productos
        </h3>
        <div class="space-y-4">
            @forelse($top_products ?? [] as $index => $product)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        {{ $index + 1 }}
                    </div>
                    <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center overflow-hidden">
                        @if($product->featured_image)
                            <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-box text-gray-400"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-sm">{{ Str::limit($product->name, 30) }}</p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-shopping-bag mr-1"></i>{{ $product->sales_count ?? 0 }} ventas
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm text-green-600">${{ number_format($product->price, 2) }}</p>
                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-2"></i>
                <p>No hay datos de ventas</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center">
                Ver todos los productos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection
