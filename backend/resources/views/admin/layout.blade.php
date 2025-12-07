<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Tienda Online</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js para interactividad -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-black bg-opacity-20">
                <h1 class="text-2xl font-bold">🛒 TiendaAdmin</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-4">
                <a href="/admin/dashboard" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="/admin/products" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/products*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-box mr-3"></i>
                    <span>Productos</span>
                </a>
                
                <a href="/admin/categories" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/categories*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-tags mr-3"></i>
                    <span>Categorías</span>
                </a>
                
                <a href="/admin/orders" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/orders*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    <span>Órdenes</span>
                </a>
                
                <a href="/admin/users" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/users*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    <span>Usuarios</span>
                </a>
                
                <a href="/admin/reviews" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/reviews*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-star mr-3"></i>
                    <span>Reseñas</span>
                </a>
                
                <a href="/admin/payment-gateways" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/payment-gateways*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-credit-card mr-3"></i>
                    <span>Pasarelas de Pago</span>
                </a>
                
                <a href="/admin/settings" class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition {{ request()->is('admin/settings*') ? 'bg-white bg-opacity-20' : '' }}">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Configuración</span>
                </a>
            </nav>
            
            <!-- User Info -->
            <div class="absolute bottom-0 w-64 p-4 bg-black bg-opacity-20">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold">Admin User</p>
                        <p class="text-xs text-gray-300">admin@tienda.com</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex-1 flex justify-between items-center ml-6">
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative text-gray-600 hover:text-gray-800">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Logout -->
                            <form method="POST" action="/admin/logout">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Salir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
