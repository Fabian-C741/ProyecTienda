@extends('shop.layout')

@section('title', $product->name . ' - Mi Tienda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Inicio</a>
        <span class="mx-2">/</span>
        <a href="{{ route('shop.category', $product->category->slug) }}" class="text-blue-600 hover:underline">{{ $product->category->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Image -->
        <div>
            @if($product->featured_image)
            <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" 
                 class="w-full rounded-lg shadow-lg">
            @else
            <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-image text-6xl text-gray-400"></i>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-4xl font-bold mb-4">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= ($product->reviews_avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                    <span class="ml-2 text-gray-600">({{ $product->reviews_count ?? 0 }} reseñas)</span>
                </div>
            </div>

            <div class="text-4xl font-bold text-blue-600 mb-6">
                ${{ number_format($product->price, 2) }}
            </div>

            <div class="prose mb-6">
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            @if($product->stock > 0)
            <div class="mb-6">
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    <i class="fas fa-check mr-1"></i>En Stock ({{ $product->stock }} disponibles)
                </span>
            </div>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex gap-4">
                @csrf
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-bold text-lg">
                    <i class="fas fa-shopping-cart mr-2"></i>Agregar al Carrito
                </button>
            </form>
            @else
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg">
                <i class="fas fa-times-circle mr-2"></i>Producto agotado
            </div>
            @endif

            <div class="mt-8 border-t pt-6 space-y-3 text-sm text-gray-600">
                <p><i class="fas fa-tag mr-2"></i><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><i class="fas fa-folder mr-2"></i><strong>Categoría:</strong> {{ $product->category->name }}</p>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-3xl font-bold mb-8">Productos Relacionados</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                <a href="{{ route('shop.product', $related->slug) }}">
                    @if($related->featured_image)
                    <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->name }}" 
                         class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                    @endif
                </a>
                <div class="p-4">
                    <a href="{{ route('shop.product', $related->slug) }}" class="font-bold text-gray-800 hover:text-blue-600">
                        {{ $related->name }}
                    </a>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xl font-bold text-blue-600">${{ number_format($related->price, 2) }}</span>
                        <form action="{{ route('cart.add', $related->id) }}" method="POST">
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
    </div>
    @endif
</div>
@endsection
