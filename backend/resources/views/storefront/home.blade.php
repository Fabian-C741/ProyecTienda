@extends('storefront.layout')

@section('content')
<!-- Hero Banner -->
@if($tenant->banner || $tenant->hero_text)
<section class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white">
    @if($tenant->banner)
    <div class="absolute inset-0">
        <img src="{{ $tenant->banner }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover opacity-50">
    </div>
    @endif
    
    <div class="relative container mx-auto px-4 py-24">
        <div class="max-w-2xl">
            <h2 class="text-5xl font-bold mb-6">
                {{ $tenant->hero_text ?? 'Bienvenido a ' . $tenant->name }}
            </h2>
            @if($tenant->description)
            <p class="text-xl mb-8 text-gray-200">{{ $tenant->description }}</p>
            @endif
            @if($tenant->hero_button_text && $tenant->hero_button_link)
            <a href="{{ $tenant->hero_button_link }}" class="btn-primary text-white px-8 py-4 rounded-lg text-lg font-bold inline-block hover:shadow-lg transition">
                {{ $tenant->hero_button_text }}
            </a>
            @else
            <a href="{{ storefront_route('products') }}" class="btn-primary text-white px-8 py-4 rounded-lg text-lg font-bold inline-block hover:shadow-lg transition">
                Ver Productos
            </a>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="container mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Productos Destacados</h2>
        <a href="{{ storefront_route('products') }}" class="text-primary hover:underline font-medium">
            Ver todos <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
        <div class="bg-white rounded-lg shadow hover:shadow-xl transition group">
            <a href="{{ storefront_route('product', $product->slug) }}">
                <div class="w-full h-64 bg-gradient-to-br from-primary/10 to-accent/10 rounded-t-lg flex items-center justify-center">
                    <i class="fas fa-box text-primary text-5xl"></i>
                </div>
            </a>
            
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2 group-hover:text-primary transition">
                    <a href="{{ storefront_route('product', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                
                <div class="flex items-center justify-between">
                    <div>
                        @if($product->compare_price)
                        <span class="text-gray-400 line-through text-sm">${{ number_format($product->compare_price, 2) }}</span>
                        @endif
                        <span class="text-2xl font-bold text-accent">${{ number_format($product->price, 2) }}</span>
                    </div>
                    
                    <button class="btn-primary text-white px-4 py-2 rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Categories -->
@if($categories->count() > 0)
<section class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Categorías</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="{{ storefront_route('products', ['category' => $category->slug]) }}" class="bg-white rounded-lg p-6 text-center hover:shadow-lg transition group">
                @if($category->icon)
                <i class="{{ $category->icon }} text-4xl text-primary mb-3 group-hover:scale-110 transition"></i>
                @else
                <i class="fas fa-tag text-4xl text-primary mb-3 group-hover:scale-110 transition"></i>
                @endif
                <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500">{{ $category->products_count }} productos</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Recent Products -->
@if($recentProducts->count() > 0)
<section class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Últimos Productos</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($recentProducts as $product)
        <div class="bg-white rounded-lg shadow hover:shadow-xl transition group">
            <a href="{{ storefront_route('product', $product->slug) }}">
                <div class="w-full h-64 bg-gradient-to-br from-accent/10 to-secondary/10 rounded-t-lg flex items-center justify-center">
                    <i class="fas fa-box text-accent text-5xl"></i>
                </div>
            </a>
            
            <div class="p-4">
                <span class="text-xs bg-accent text-white px-2 py-1 rounded">Nuevo</span>
                <h3 class="font-bold text-lg mb-2 mt-2 group-hover:text-primary transition">
                    <a href="{{ storefront_route('product', $product->slug) }}">{{ $product->name }}</a>
                </h3>
                
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-accent">${{ number_format($product->price, 2) }}</span>
                    
                    <button class="btn-primary text-white px-4 py-2 rounded-lg hover:shadow-lg transition">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- WhatsApp Float Button -->
@if($tenant->whatsapp_number)
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_number) }}?text=Hola! Estoy interesado en sus productos" 
   target="_blank" 
   class="fixed bottom-6 right-6 bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 hover:scale-110 transition z-50">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>
@endif
@endsection
