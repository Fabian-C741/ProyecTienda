@extends('admin.layout')

@section('title', 'Reportes de Ventas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Reportes de Ventas</h1>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Fecha Inicio</label>
            <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" 
                   class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Fecha Fin</label>
            <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" 
                   class="w-full px-4 py-2 border rounded-lg">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.reports.export') }}?start_date={{ request('start_date', $startDate->format('Y-m-d')) }}&end_date={{ request('end_date', $endDate->format('Y-m-d')) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-file-excel mr-2"></i>Exportar CSV
            </a>
        </div>
    </form>
</div>

<!-- Resumen -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-blue-100 text-sm">Total Ventas</p>
                <h3 class="text-3xl font-bold mt-2">${{ number_format($totalSales, 2) }}</h3>
            </div>
            <i class="fas fa-dollar-sign text-4xl text-blue-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-green-100 text-sm">Total Órdenes</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalOrders }}</h3>
            </div>
            <i class="fas fa-shopping-cart text-4xl text-green-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-100 text-sm">Promedio por Orden</p>
                <h3 class="text-3xl font-bold mt-2">${{ number_format($averageOrder, 2) }}</h3>
            </div>
            <i class="fas fa-chart-line text-4xl text-purple-300"></i>
        </div>
    </div>
</div>

<!-- Ventas por Estado -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-bold mb-4">Ventas por Estado</h2>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($salesByStatus as $status => $data)
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 capitalize">{{ $status }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $data['count'] }}</p>
            <p class="text-sm text-gray-500">${{ number_format($data['total'], 2) }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Lista de Órdenes -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold">Detalle de Órdenes</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr>
                <td class="px-6 py-4 font-mono">{{ $order->order_number }}</td>
                <td class="px-6 py-4">{{ $order->customer_name }}</td>
                <td class="px-6 py-4 font-bold">${{ number_format($order->total, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs
                        @if($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                        @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No hay órdenes en este período
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
