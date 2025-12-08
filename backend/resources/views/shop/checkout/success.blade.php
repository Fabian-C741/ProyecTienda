@extends('shop.layout')

@section('title', 'Pedido Realizado')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pedido Realizado con Éxito!</h1>
            <p class="text-gray-600">Gracias por tu compra. Hemos recibido tu pedido correctamente.</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-bold mb-4">Detalles del Pedido</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Número de Pedido:</p>
                        <p class="font-bold text-lg">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Fecha:</p>
                        <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Estado:</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-600">Método de Pago:</p>
                        <p class="font-medium">
                            @if($order->payment_method === 'mercadopago')
                                <i class="fas fa-credit-card mr-1"></i>Mercado Pago
                            @elseif($order->payment_method === 'bank_transfer')
                                <i class="fas fa-university mr-1"></i>Transferencia
                            @else
                                <i class="fas fa-money-bill mr-1"></i>Efectivo
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="border-b pb-4 mb-4">
                <h3 class="font-bold mb-2">Dirección de Envío</h3>
                <p class="text-gray-700">{{ $order->customer_name }}</p>
                <p class="text-gray-600">{{ $order->shipping_address }}</p>
                <p class="text-gray-600">{{ $order->shipping_city }}</p>
                <p class="text-gray-600">{{ $order->customer_phone }}</p>
            </div>

            <!-- Order Items -->
            <div class="mb-4">
                <h3 class="font-bold mb-3">Productos</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <p class="font-medium">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <p class="font-bold">${{ number_format($item->subtotal, 2) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Total -->
            <div class="border-t pt-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Descuento:</span>
                        <span>-${{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    @if($order->shipping > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Envío:</span>
                        <span>${{ number_format($order->shipping, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold pt-2 border-t">
                        <span>Total:</span>
                        <span class="text-blue-600">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        @if($order->payment_method === 'bank_transfer')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="font-bold mb-3 text-blue-900">
                <i class="fas fa-info-circle mr-2"></i>
                Instrucciones de Pago
            </h3>
            <p class="text-blue-800 mb-3">Por favor realiza la transferencia a la siguiente cuenta:</p>
            <div class="bg-white rounded p-4 text-sm">
                <p><strong>Banco:</strong> Banco Ejemplo</p>
                <p><strong>Cuenta:</strong> 1234-5678-90</p>
                <p><strong>CLABE:</strong> 012345678901234567</p>
                <p><strong>Titular:</strong> Tienda Online SA de CV</p>
                <p class="mt-2 text-blue-600"><strong>Monto:</strong> ${{ number_format($order->total, 2) }}</p>
                <p class="mt-2 text-xs text-gray-600">
                    Referencia: {{ $order->order_number }}
                </p>
            </div>
            <p class="text-sm text-blue-700 mt-3">
                Una vez realizada la transferencia, envíanos el comprobante a 
                <a href="mailto:pagos@tienda.com" class="underline">pagos@tienda.com</a>
            </p>
        </div>
        @elseif($order->payment_method === 'cash')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <h3 class="font-bold mb-2 text-yellow-900">
                <i class="fas fa-money-bill-wave mr-2"></i>
                Pago en Efectivo
            </h3>
            <p class="text-yellow-800">
                Prepara el monto exacto de <strong>${{ number_format($order->total, 2) }}</strong> para pagar al recibir tu pedido.
            </p>
        </div>
        @endif

        <!-- Next Steps -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="font-bold mb-3">¿Qué sigue?</h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start">
                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                    <span>Recibirás un email de confirmación con los detalles de tu pedido</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-box text-blue-600 mr-2 mt-1"></i>
                    <span>Prepararemos tu pedido en las próximas 24-48 horas</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-shipping-fast text-purple-600 mr-2 mt-1"></i>
                    <span>Te notificaremos cuando tu pedido sea enviado con el número de rastreo</span>
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('home') }}" 
               class="flex-1 bg-blue-600 text-white text-center py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                <i class="fas fa-home mr-2"></i>
                Volver al Inicio
            </a>
            <a href="{{ route('shop.index') }}" 
               class="flex-1 bg-white text-blue-600 text-center py-3 rounded-lg font-bold border-2 border-blue-600 hover:bg-blue-50 transition">
                <i class="fas fa-shopping-bag mr-2"></i>
                Seguir Comprando
            </a>
        </div>

        <!-- Support -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">¿Necesitas ayuda con tu pedido?</p>
            <a href="#" class="text-blue-600 hover:underline font-medium">
                <i class="fas fa-headset mr-1"></i>
                Contacta a Soporte
            </a>
        </div>
    </div>
</div>
@endsection
