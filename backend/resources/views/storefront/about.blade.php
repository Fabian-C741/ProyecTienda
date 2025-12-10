@extends('storefront.layout')

@section('title', 'Acerca de ' . $tenant->name)

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Acerca de Nosotros</h1>
            <div class="w-24 h-1 bg-blue-600 mx-auto"></div>
        </div>

        <!-- Logo y Nombre -->
        <div class="text-center mb-12">
            @if($tenant->logo_url)
            <img src="{{ $tenant->logo_url }}" alt="{{ $tenant->name }}" class="w-32 h-32 mx-auto mb-6 object-contain">
            @endif
            <h2 class="text-3xl font-semibold text-gray-800">{{ $tenant->name }}</h2>
        </div>

        <!-- Descripción -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Nuestra Historia</h3>
            @if($tenant->description)
            <p class="text-gray-600 leading-relaxed">{{ $tenant->description }}</p>
            @else
            <p class="text-gray-600 leading-relaxed">
                Bienvenido a {{ $tenant->name }}, tu tienda de confianza. Nos dedicamos a ofrecerte 
                los mejores productos con la mejor calidad y servicio. Cada producto que vendemos 
                ha sido cuidadosamente seleccionado para satisfacer tus necesidades.
            </p>
            @endif
        </div>

        <!-- Información de Contacto -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Contáctanos</h3>
            <div class="space-y-4">
                @if($tenant->contact_email)
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $tenant->contact_email }}</span>
                </div>
                @endif

                @if($tenant->contact_phone)
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $tenant->contact_phone }}</span>
                </div>
                @endif

                @if($tenant->address)
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $tenant->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Llamado a la Acción -->
        <div class="text-center bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-8 text-white">
            <h3 class="text-2xl font-semibold mb-4">¿Listo para comprar?</h3>
            <p class="mb-6">Explora nuestro catálogo de productos y encuentra lo que necesitas</p>
            <a href="{{ route('tienda.home', ['slug' => $tenant->slug]) }}" 
               class="inline-block bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition">
                Ver Productos
            </a>
        </div>
    </div>
</div>
@endsection
