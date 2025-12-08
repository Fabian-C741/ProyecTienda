@extends('shop.layout')

@section('title', isset($category) ? $category->name : 'Productos - Mi Tienda')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">{{ isset($category) ? $category->name : 'Todos los Productos' }}</h1>
        <div class="flex gap-4">
            <select onchange="window.location.href='?sort='+this.value" class="px-4 py-2 border rounded-lg">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
            </select>
        </div>
    </div>

    <div class="flex gap-8">
        <!-- Sidebar -->
        <aside class="hidden md:block w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-lg mb-4">Categorías</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('shop.index') }}" class="text-gray-700 hover:text-blue-600 {{ !isset($category) ? 'font-bold text-blue-600' : '' }}">
                            Todas las categorías
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('shop.category', $cat->slug) }}" 
                           class="text-gray-700 hover:text-blue-600 {{ isset($category) && $category->id == $cat->id ? 'font-bold text-blue-600' : '' }}">
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Products Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
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
                        <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
                        <a href="{{ route('shop.product', $product->slug) }}" class="font-bold text-gray-800 hover:text-blue-600">
                            {{ $product->name }}
                        </a>
                        <p class="text-sm text-gray-500 mt-2">{{ Str::limit($product->description, 80) }}</p>
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

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">No se encontraron productos</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
