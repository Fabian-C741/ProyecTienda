@extends('shop.layout')

@section('title', 'Carrito de Compras - Mi Tienda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Carrito de Compras</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(count($cart) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @foreach($cart as $id => $item)
                <div class="flex gap-4 p-4 border-b">
                    @if($item['image'])
                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" 
                         class="w-24 h-24 object-cover rounded">
                    @else
                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fas fa-image text-gray-400"></i>
                    </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $item['name'] }}</h3>
                        <p class="text-gray-600">${{ number_format($item['price'], 2) }} c/u</p>
                        
                        <div class="flex items-center gap-4 mt-2">
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <label class="text-sm text-gray-600">Cantidad:</label>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                       class="w-20 px-2 py-1 border rounded" onchange="this.form.submit()">
                            </form>

                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-xl font-bold text-blue-600">
                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <form action="{{ route('cart.clear') }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash mr-2"></i>Vaciar Carrito
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-2xl font-bold mb-6">Resumen del Pedido</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Envío</span>
                        <span class="font-semibold">Gratis</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-xl font-bold">
                        <span>Total</span>
                        <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @auth
                <a href="{{ route('checkout.index') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-6 py-3 rounded-lg font-bold mb-3">
                    <i class="fas fa-credit-card mr-2"></i>Proceder al Pago
                </a>
                @else
                <a href="{{ route('login') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-6 py-3 rounded-lg font-bold mb-3">
                    <i class="fas fa-sign-in-alt mr-2"></i>Iniciar Sesión para Comprar
                </a>
                @endauth

                <a href="{{ route('shop.index') }}" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-center px-6 py-3 rounded-lg font-bold">
                    <i class="fas fa-arrow-left mr-2"></i>Seguir Comprando
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-16">
        <i class="fas fa-shopping-cart text-8xl text-gray-300 mb-6"></i>
        <h2 class="text-2xl font-bold text-gray-600 mb-4">Tu carrito está vacío</h2>
        <p class="text-gray-500 mb-8">¡Agrega algunos productos para empezar!</p>
        <a href="{{ route('shop.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold inline-block">
            <i class="fas fa-shopping-bag mr-2"></i>Ver Productos
        </a>
    </div>
    @endif
</div>
@endsection
