<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tu tienda online de confianza">
    <meta name="theme-color" content="#2563eb">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icon-192.png">
    
    <title>@yield('title', 'Tienda Online')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <!-- App Install Prompt -->
    <div id="installPrompt" class="hidden fixed bottom-20 left-4 right-4 md:left-auto md:right-4 md:w-96 bg-white rounded-lg shadow-2xl z-50 border-2 border-blue-500">
        <div class="p-4">
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 rounded-full p-3 flex-shrink-0">
                    <i class="fas fa-mobile-alt text-2xl text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg mb-1">¡Instala nuestra App!</h3>
                    <p class="text-sm text-gray-600 mb-3">Obtén acceso rápido y una mejor experiencia de compra</p>
                    <div class="flex gap-2">
                        <button onclick="installApp()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                            <i class="fas fa-download mr-1"></i>Instalar
                        </button>
                        <button onclick="dismissInstallPrompt()" class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">
                            Ahora no
                        </button>
                    </div>
                </div>
                <button onclick="dismissInstallPrompt()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

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
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600">
                            <i class="fas fa-user mr-1"></i>
                            <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-box mr-2"></i>Mis Pedidos
                            </a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i>Mi Cuenta
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        <span class="hidden md:inline">Iniciar Sesión</span>
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 hidden md:block">
                        <i class="fas fa-user-plus mr-1"></i>Registro
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

    <style>
        @keyframes slide-up {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .animate-slide-up {
            animation: slide-up 0.3s ease-out;
        }
    </style>

    <script>
        let deferredPrompt;
        const installPromptEl = document.getElementById('installPrompt');

        // Detectar si ya se mostró el prompt o si ya está instalada
        const isInstalled = localStorage.getItem('appInstalled') === 'true';
        const promptDismissed = localStorage.getItem('installPromptDismissed');
        const dismissedTime = promptDismissed ? parseInt(promptDismissed) : 0;
        const oneDayAgo = Date.now() - (24 * 60 * 60 * 1000);

        // Capturar el evento beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Mostrar el prompt si no está instalado y no fue rechazado hace menos de 24h
            if (!isInstalled && dismissedTime < oneDayAgo) {
                setTimeout(() => {
                    installPromptEl.classList.remove('hidden');
                    installPromptEl.classList.add('animate-slide-up');
                }, 3000); // Mostrar después de 3 segundos
            }
        });

        // Detectar si la app fue instalada
        window.addEventListener('appinstalled', () => {
            localStorage.setItem('appInstalled', 'true');
            installPromptEl.classList.add('hidden');
        });

        function installApp() {
            if (!deferredPrompt) {
                alert('La instalación no está disponible en este momento');
                return;
            }

            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('Usuario aceptó instalar la app');
                    localStorage.setItem('appInstalled', 'true');
                } else {
                    console.log('Usuario rechazó instalar la app');
                    localStorage.setItem('installPromptDismissed', Date.now().toString());
                }
                installPromptEl.classList.add('hidden');
                deferredPrompt = null;
            });
        }

        function dismissInstallPrompt() {
            localStorage.setItem('installPromptDismissed', Date.now().toString());
            installPromptEl.classList.add('hidden');
        }
    </script>
</body>
</html>
