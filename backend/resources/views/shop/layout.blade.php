<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tienda Online')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-store mr-2"></i>Mi Tienda
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-xl mx-8">
                    <form action="{{ route('shop.search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Buscar productos..." 
                                   class="w-full px-4 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-blue-600">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Menu -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('shop.index') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-th-large mr-1"></i>Productos
                    </a>
                    
                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-user mr-1"></i>{{ Auth::user()->name }}
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fas fa-box mr-2"></i>Mis Pedidos
                            </a>
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i>Mi Cuenta
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-sign-in-alt mr-1"></i>Iniciar Sesión
                    </a>
                    @endauth

                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            {{ count(session('cart')) }}
                        </span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden pb-4">
                <form action="{{ route('shop.search') }}" method="GET">
                    <input type="text" name="q" placeholder="Buscar productos..." 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Mi Tienda</h3>
                    <p class="text-gray-400">Tu tienda online de confianza para todas tus necesidades.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-white">Productos</a></li>
                        <li><a href="#" class="hover:text-white">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-white">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Ayuda</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Preguntas Frecuentes</a></li>
                        <li><a href="#" class="hover:text-white">Envíos</a></li>
                        <li><a href="#" class="hover:text-white">Devoluciones</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Síguenos</h4>
                    <div class="flex gap-4 text-2xl">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Mi Tienda. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
