@extends('admin.layout')

@section('title', 'Detalle de Orden')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i>Volver a órdenes
    </a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">Orden #{{ $order->id }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Productos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Productos</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center border-b border-gray-200 pb-4 last:border-0">
                    <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/80' }}" alt="{{ $item->product->name }}" class="w-20 h-20 rounded-lg object-cover mr-4">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                        <p class="text-sm text-gray-500">Precio unitario: ${{ number_format($item->price, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Impuestos:</span>
                    <span class="font-semibold">${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600">Envío:</span>
                    <span class="font-semibold">${{ number_format($order->shipping, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-gray-200">
                    <span>Total:</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Información de pago -->
        @if($order->payment)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Información de Pago</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Método de pago</p>
                    <p class="font-semibold">{{ $order->payment->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estado</p>
                    <p class="font-semibold">
                        @if($order->payment->status == 'completed')
                            <span class="text-green-600">Completado</span>
                        @elseif($order->payment->status == 'pending')
                            <span class="text-yellow-600">Pendiente</span>
                        @else
                            <span class="text-red-600">Fallido</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ID de Transacción</p>
                    <p class="font-mono text-sm">{{ $order->payment->transaction_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha de pago</p>
                    <p class="font-semibold">{{ $order->payment->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Información del cliente -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Cliente</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Nombre</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold">{{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Teléfono</p>
                    <p class="font-semibold">{{ $order->user->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Dirección de envío -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Dirección de Envío</h2>
            <div class="text-gray-700">
                <p>{{ $order->shipping_address }}</p>
            </div>
        </div>
        
        <!-- Estado de la orden -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Estado de la Orden</h2>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Procesando</option>
                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-save mr-2"></i>Actualizar Estado
                </button>
            </form>
        </div>
        
        <!-- Información adicional -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Información</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Fecha de creación</p>
                    <p class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Última actualización</p>
                    <p class="font-semibold">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
