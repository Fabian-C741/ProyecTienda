<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->meta_title ?? $tenant->name }} - {{ $tenant->description ?? 'Tienda Online' }}</title>
    <meta name="description" content="{{ $tenant->meta_description ?? $tenant->description }}">
    <meta name="keywords" content="{{ $tenant->meta_keywords ?? '' }}">
    
    <!-- Favicon -->
    @if($tenant->logo)
    <link rel="icon" href="{{ $tenant->logo }}" type="image/png">
    @endif
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family={{ $tenant->font_family ?? 'Inter' }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Personalización de colores del tenant -->
    <style>
        :root {
            --primary-color: {{ $tenant->primary_color ?? '#007bff' }};
            --secondary-color: {{ $tenant->secondary_color ?? '#6c757d' }};
            --accent-color: {{ $tenant->accent_color ?? '#28a745' }};
            --font-family: '{{ $tenant->font_family ?? 'Inter' }}', sans-serif;
        }
        
        body {
            font-family: var(--font-family);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            filter: brightness(0.9);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color);
        }
        
        .border-primary {
            border-color: var(--primary-color);
        }
        
        .text-accent {
            color: var(--accent-color);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <!-- Main Header -->
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ storefront_route('home') }}" class="flex items-center gap-3">
                    @if($tenant->logo)
                    <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-12">
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $tenant->name }}</h1>
                        @if($tenant->description)
                        <p class="text-sm text-gray-600">{{ $tenant->description }}</p>
                        @endif
                    </div>
                </a>
                
                <!-- Navigation -->
                <nav class="hidden md:flex gap-6">
                    <a href="{{ storefront_route('home') }}" class="text-gray-700 hover:text-primary font-medium">
                        Inicio
                    </a>
                    <a href="{{ storefront_route('products') }}" class="text-gray-700 hover:text-primary font-medium">
                        Productos
                    </a>
                    <a href="{{ storefront_route('about') }}" class="text-gray-700 hover:text-primary font-medium">
                        Nosotros
                    </a>
                    <a href="{{ storefront_route('contact') }}" class="text-gray-700 hover:text-primary font-medium">
                        Contacto
                    </a>
                </nav>
                
                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <a href="{{ storefront_route('cart.index') }}" class="relative">
                        <i class="fas fa-shopping-cart text-2xl text-gray-700 hover:text-primary"></i>
                        <span class="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            0
                        </span>
                    </a>
                    
                    @auth
                        @if(auth()->user()->role === 'customer')
                        <div class="relative group">
                            <button class="btn-primary text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                <i class="fas fa-user"></i>
                                {{ auth()->user()->name }}
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                                <a href="{{ storefront_route('orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-shopping-bag mr-2"></i>Mis Pedidos
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('dashboard.index') }}" class="btn-primary text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        @endif
                    @else
                    <a href="{{ storefront_route('login') }}" class="text-gray-700 hover:text-primary font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
                    </a>
                    <a href="{{ storefront_route('register') }}" class="btn-primary text-white px-4 py-2 rounded-lg">
                        Registrarse
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ $tenant->name }}</h3>
                    <p class="text-gray-400">{{ $tenant->description }}</p>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="font-bold mb-4">Enlaces</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ storefront_route('products') }}" class="text-gray-400 hover:text-white">Productos</a></li>
                        <li><a href="{{ storefront_route('about') }}" class="text-gray-400 hover:text-white">Nosotros</a></li>
                        <li><a href="{{ storefront_route('contact') }}" class="text-gray-400 hover:text-white">Contacto</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        @if($tenant->email)
                        <li><i class="fas fa-envelope mr-2"></i> {{ $tenant->email }}</li>
                        @endif
                        @if($tenant->phone)
                        <li><i class="fas fa-phone mr-2"></i> {{ $tenant->phone }}</li>
                        @endif
                        @if($tenant->address)
                        <li><i class="fas fa-map-marker-alt mr-2"></i> {{ $tenant->address }}</li>
                        @endif
                    </ul>
                </div>
                
                <!-- Social -->
                <div>
                    <h4 class="font-bold mb-4">Síguenos</h4>
                    <div class="flex gap-3">
                        @if($tenant->facebook_url)
                        <a href="{{ $tenant->facebook_url }}" target="_blank" class="bg-gray-800 hover:bg-primary w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-facebook"></i>
                        </a>
                        @endif
                        @if($tenant->instagram_url)
                        <a href="{{ $tenant->instagram_url }}" target="_blank" class="bg-gray-800 hover:bg-primary w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if($tenant->twitter_url)
                        <a href="{{ $tenant->twitter_url }}" target="_blank" class="bg-gray-800 hover:bg-primary w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $tenant->name }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
