@extends('shop.layout')

@section('title', 'Finalizar Compra')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Finalizar Compra</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario de Checkout -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <!-- Información del Cliente -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4">Información de Envío</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" value="{{ $user->name }}" disabled
                                   class="w-full px-4 py-2 border rounded-lg bg-gray-50">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-4 py-2 border rounded-lg bg-gray-50">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                            <input type="tel" name="shipping_phone" required
                                   value="{{ old('shipping_phone', $user->phone) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('shipping_phone') border-red-500 @enderror">
                            @error('shipping_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección de Envío *</label>
                            <input type="text" name="shipping_address" required
                                   value="{{ old('shipping_address', $user->address) }}"
                                   placeholder="Calle, número, depto, etc."
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('shipping_address') border-red-500 @enderror">
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                            <input type="text" name="shipping_city" required
                                   value="{{ old('shipping_city', $user->city) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('shipping_city') border-red-500 @enderror">
                            @error('shipping_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4">Método de Pago</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="mercadopago" required
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium">Mercado Pago</span>
                                        <p class="text-sm text-gray-600">Paga con tarjeta de crédito o débito</p>
                                    </div>
                                    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.18.9/mercadopago/logo__large.png" alt="Mercado Pago" class="h-8">
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <div class="ml-3">
                                <span class="font-medium">Transferencia Bancaria</span>
                                <p class="text-sm text-gray-600">Recibirás los datos bancarios por email</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <div class="ml-3">
                                <span class="font-medium">Pago en Efectivo</span>
                                <p class="text-sm text-gray-600">Paga al recibir tu pedido</p>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notas Adicionales -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas del Pedido (Opcional)</label>
                    <textarea name="notes" rows="3" 
                              placeholder="Ej: Timbre roto, tocar la puerta"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>

                <!-- Botón de Compra -->
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    <i class="fas fa-lock mr-2"></i>
                    Confirmar Pedido
                </button>

                <p class="text-xs text-gray-500 text-center mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Tus datos están protegidos y seguros
                </p>
            </form>
        </div>

        <!-- Resumen del Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4">Resumen del Pedido</h2>

                <!-- Productos -->
                <div class="space-y-3 mb-4">
                    @foreach($cart as $id => $item)
                    <div class="flex gap-3">
                        <img src="{{ $item['image'] ?? '/images/placeholder.png' }}" alt="{{ $item['name'] }}" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-medium text-sm">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-600">Cant: {{ $item['quantity'] }}</p>
                            <p class="font-bold text-blue-600">${{ number_format($item['price'], 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <!-- Totales -->
                <div class="space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Descuento:</span>
                        <span>-${{ number_format($discount, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between text-gray-600">
                        <span>Envío:</span>
                        <span>{{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Gratis' }}</span>
                    </div>

                    <hr class="my-2">

                    <div class="flex justify-between text-xl font-bold">
                        <span>Total:</span>
                        <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center text-green-700">
                        <i class="fas fa-shipping-fast text-2xl mr-3"></i>
                        <div>
                            <p class="font-medium">Envío Gratis</p>
                            <p class="text-sm">En compras superiores a $500</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
