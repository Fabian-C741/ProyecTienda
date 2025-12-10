@extends('storefront.layout')

@section('title', 'Productos - ' . $tenant->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Productos</h1>

    <!-- Filtros -->
    <div class="mb-8 bg-white rounded-lg shadow p-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            @if($categories->count() > 0)
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @endif

            <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Buscar
            </button>
        </form>
    </div>

    <!-- Productos -->
    @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow hover:shadow-xl transition group">
            <a href="{{ storefront_route('product', $product->slug) }}">
                <div class="w-full h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-t-lg flex items-center justify-center overflow-hidden">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                    <i class="fas fa-box text-gray-400 text-5xl"></i>
                    @endif
                </div>
            </a>
            
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2 group-hover:text-blue-600 transition line-clamp-2">
                    <a href="{{ storefront_route('product', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                
                @if($product->category)
                <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                @endif
                
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                
                <div class="flex items-center justify-between">
                    <div>
                        @if($product->compare_price && $product->compare_price > $product->price)
                        <span class="text-gray-400 line-through text-sm block">${{ number_format($product->compare_price, 2) }}</span>
                        @endif
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    </div>
                    
                    <form action="{{ storefront_route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="flex justify-center">
        {{ $products->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <i class="fas fa-box-open text-gray-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay productos disponibles</h3>
        <p class="text-gray-500">Vuelve pronto para ver nuevos productos</p>
    </div>
    @endif
</div>
@endsection
