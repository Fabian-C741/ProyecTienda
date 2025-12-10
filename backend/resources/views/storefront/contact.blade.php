@extends('storefront.layout')

@section('title', 'Contacto - ' . $tenant->name)

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Contáctanos</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Información de Contacto -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Información</h2>
                
                @if($tenant->contact_email || $tenant->email)
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-gray-800 font-medium">{{ $tenant->contact_email ?? $tenant->email }}</p>
                    </div>
                </div>
                @endif

                @if($tenant->contact_phone || $tenant->phone)
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-phone text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Teléfono</p>
                        <p class="text-gray-800 font-medium">{{ $tenant->contact_phone ?? $tenant->phone }}</p>
                    </div>
                </div>
                @endif

                @if($tenant->address)
                <div class="flex items-start mb-4">
                    <div class="bg-red-100 rounded-full p-3 mr-4">
                        <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dirección</p>
                        <p class="text-gray-800 font-medium">{{ $tenant->address }}</p>
                    </div>
                </div>
                @endif

                <!-- Redes Sociales -->
                @if($tenant->facebook_url || $tenant->instagram_url || $tenant->twitter_url)
                <div class="mt-8">
                    <p class="text-sm text-gray-500 mb-3">Síguenos</p>
                    <div class="flex gap-3">
                        @if($tenant->facebook_url)
                        <a href="{{ $tenant->facebook_url }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-facebook"></i>
                        </a>
                        @endif
                        @if($tenant->instagram_url)
                        <a href="{{ $tenant->instagram_url }}" target="_blank" class="bg-pink-600 hover:bg-pink-700 text-white w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($tenant->twitter_url)
                        <a href="{{ $tenant->twitter_url }}" target="_blank" class="bg-blue-400 hover:bg-blue-500 text-white w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Formulario de Contacto -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Envíanos un mensaje</h2>
                
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Teléfono</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Mensaje</label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
