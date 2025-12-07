@extends('admin.layout')

@section('title', 'Órdenes')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Gestión de Órdenes</h1>
</div>

<!-- Filtros -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID o cliente..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div>
            <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Estado de pago</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

<!-- Tabla de órdenes -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Orden</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                    <div class="text-xs text-gray-500">{{ $order->items->count() }} productos</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    ${{ number_format($order->total, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'processing' => 'Procesando',
                            'shipped' => 'Enviado',
                            'delivered' => 'Entregado',
                            'cancelled' => 'Cancelado',
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $paymentColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'failed' => 'bg-red-100 text-red-800',
                        ];
                        $paymentLabels = [
                            'pending' => 'Pendiente',
                            'paid' => 'Pagado',
                            'failed' => 'Fallido',
                        ];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                    No se encontraron órdenes
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
