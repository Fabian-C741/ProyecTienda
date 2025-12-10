<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Acceso Denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-slow { animation: pulse-slow 2s ease-in-out infinite; }
        .gradient-bg {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12">
            <div class="text-center mb-8">
                <div class="inline-block pulse-slow">
                    <svg class="w-32 h-32 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-pink-600 mt-6 mb-2">
                    403
                </h1>
                <p class="text-2xl text-gray-700 font-semibold">
                    Acceso Denegado
                </p>
            </div>

            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <span class="text-4xl mr-4">🚫</span>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 mb-2">
                            No tienes permiso para acceder aquí
                        </h3>
                        <p class="text-red-700">
                            Esta área está restringida. Si crees que deberías tener acceso, contacta al administrador del sistema.
                        </p>
                    </div>
                </div>
            </div>

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
                        No has iniciado sesión
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Tu cuenta no tiene los permisos necesarios
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Esta sección requiere un rol específico (Admin, Super Admin, etc.)
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Tu sesión expiró
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                @guest
                <a href="/login" 
                    class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-red-600 hover:to-pink-700 transition shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Iniciar Sesión
                </a>
                @else
                <button onclick="window.history.back()" 
                    class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-lg hover:from-red-600 hover:to-pink-700 transition shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver Atrás
                </button>
                @endguest
                <a href="/" 
                    class="flex-1 bg-white border-2 border-red-500 text-red-600 px-6 py-3 rounded-lg hover:bg-red-50 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir al Inicio
                </a>
            </div>

            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    ¿Necesitas acceso? Contacta a 
                    <a href="mailto:soporte@ingreso-tienda.kcrsf.com" class="text-red-600 hover:text-red-800 font-semibold">
                        soporte@ingreso-tienda.kcrsf.com
                    </a>
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Error 403 | {{ now()->format('Y-m-d H:i:s') }}
                </p>
            </div>
        </div>

        <div class="text-center mt-6">
            <p class="text-white text-sm opacity-75">
                🔐 Esta puerta está cerrada con llave... y tú no tienes la llave 🗝️
            </p>
        </div>
    </div>
</body>
</html>
