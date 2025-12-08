@extends('shop.layout')

@section('title', 'Inicio - Mi Tienda')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Bienvenido a Mi Tienda</h1>
        <p class="text-xl mb-8">Encuentra los mejores productos al mejor precio</p>
        <a href="{{ route('shop.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 inline-block">
            <i class="fas fa-shopping-bag mr-2"></i>Comprar Ahora
        </a>
    </div>
</section>

<!-- Categories -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8">Categorías Destacadas</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('shop.category', $category->slug) }}" 
           class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition">
            <i class="fas fa-box text-4xl text-blue-600 mb-4"></i>
            <h3 class="font-bold">{{ $category->name }}</h3>
            <p class="text-sm text-gray-500">{{ $category->products_count }} productos</p>
        </a>
        @endforeach
    </div>
</section>

<!-- Featured Products -->
<section class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8">Productos Destacados</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <a href="{{ route('shop.product', $product->slug) }}">
                @if($product->featured_image)
                <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" 
                     class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                @endif
            </a>
            <div class="p-4">
                <a href="{{ route('shop.product', $product->slug) }}" class="font-bold text-gray-800 hover:text-blue-600">
                    {{ $product->name }}
                </a>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 60) }}</p>
                <div class="flex justify-between items-center mt-4">
                    <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Features -->
<section class="bg-gray-100 py-12 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <i class="fas fa-shipping-fast text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Envío Rápido</h3>
                <p class="text-gray-600">Entrega en 24-48 horas</p>
            </div>
            <div>
                <i class="fas fa-shield-alt text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Compra Segura</h3>
                <p class="text-gray-600">Pago 100% seguro</p>
            </div>
            <div>
                <i class="fas fa-undo text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Devoluciones Fáciles</h3>
                <p class="text-gray-600">30 días para devolver</p>
            </div>
        </div>
    </div>
</section>
@endsection
