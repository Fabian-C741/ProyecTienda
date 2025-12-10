<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-30px); }
        }
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        .bounce-slow { animation: bounce-slow 2s ease-in-out infinite; }
        .wiggle { animation: wiggle 1s ease-in-out infinite; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12">
            <!-- Ilustración 404 -->
            <div class="text-center mb-8">
                <div class="inline-block bounce-slow">
                    <svg class="w-40 h-40 mx-auto text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-6xl md:text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 mt-6 mb-2">
                    404
                </h1>
                <p class="text-2xl text-gray-700 font-semibold">
                    ¡Página no encontrada!
                </p>
            </div>

            <!-- Mensaje divertido -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <span class="text-4xl mr-4 wiggle">🔍</span>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                            ¡Parece que te perdiste!
                        </h3>
                        <p class="text-yellow-700">
                            La página que buscas ha decidido tomarse unas vacaciones... o tal vez nunca existió. 
                            ¡Pero no te preocupes! Tenemos muchas otras páginas increíbles para ti.
                        </p>
                    </div>
                </div>
            </div>

            <!-- URL solicitada (si está disponible) -->
            @if(isset($exception) && method_exists($exception, 'getMessage'))
            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                <p class="text-sm text-gray-600">
                    <strong class="text-gray-800">URL solicitada:</strong>
                    <code class="text-purple-600 bg-purple-50 px-2 py-1 rounded ml-2">{{ request()->url() }}</code>
                </p>
            </div>
            @endif

            <!-- Sugerencias -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Posibles razones:
                </h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        La URL fue escrita incorrectamente
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        La página fue movida o eliminada
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        El enlace que seguiste está roto
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        No tienes permisos para acceder a esta página
                    </li>
                </ul>
            </div>

            <!-- Enlaces útiles -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Enlaces útiles que podrían interesarte:</h4>
                <div class="grid grid-cols-2 gap-3">
                    <a href="/" class="flex items-center p-3 bg-white rounded-lg hover:shadow-md transition">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Inicio</span>
                    </a>
                    <a href="/products" class="flex items-center p-3 bg-white rounded-lg hover:shadow-md transition">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Productos</span>
                    </a>
                    @auth
                    <a href="/dashboard" class="flex items-center p-3 bg-white rounded-lg hover:shadow-md transition">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Dashboard</span>
                    </a>
                    @endauth
                    <a href="/contact" class="flex items-center p-3 bg-white rounded-lg hover:shadow-md transition">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Contacto</span>
                    </a>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="window.history.back()" 
                    class="flex-1 bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-indigo-700 transition shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver Atrás
                </button>
                <a href="/" 
                    class="flex-1 bg-white border-2 border-purple-500 text-purple-600 px-6 py-3 rounded-lg hover:bg-purple-50 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir al Inicio
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    ¿Crees que esto es un error? 
                    <a href="mailto:soporte@ingreso-tienda.kcrsf.com" class="text-purple-600 hover:text-purple-800 font-semibold">
                        Repórtalo aquí
                    </a>
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Error 404 | {{ now()->format('Y-m-d H:i:s') }}
                </p>
            </div>
        </div>

        <!-- Mensaje divertido abajo -->
        <div class="text-center mt-6">
            <p class="text-white text-sm opacity-75">
                🗺️ Parece que necesitas un mapa... o tal vez GPS 😅
            </p>
        </div>
    </div>
</body>
</html>
