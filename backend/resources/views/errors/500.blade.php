<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 - Algo salió mal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(-5deg); }
            20%, 40%, 60%, 80% { transform: rotate(5deg); }
        }
        .float { animation: float 3s ease-in-out infinite; }
        .shake { animation: shake 0.5s ease-in-out; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Card Principal -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12">
            <!-- Icono Animado -->
            <div class="text-center mb-8">
                <div class="inline-block float">
                    <svg class="w-32 h-32 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mt-6 mb-2">
                    Error 500
                </h1>
                <p class="text-xl text-gray-600">
                    ¡Ups! Algo no salió como esperábamos
                </p>
            </div>

            <!-- Mensaje Principal -->
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800 mb-2">
                            Error Interno del Servidor
                        </h3>
                        <p class="text-red-700">
                            Nuestro servidor encontró un problema inesperado. No te preocupes, ya lo estamos revisando.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detalles del Error (solo en desarrollo) -->
            @if(config('app.debug') && isset($exception))
            <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200">
                <div class="flex items-center mb-3">
                    <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <h4 class="text-sm font-semibold text-gray-700">Detalles Técnicos (Modo Desarrollo)</h4>
                </div>
                <div class="bg-white rounded p-4 border border-gray-200 overflow-x-auto">
                    <p class="text-sm text-red-600 font-mono mb-2">
                        <strong>Tipo:</strong> {{ get_class($exception) }}
                    </p>
                    <p class="text-sm text-gray-700 font-mono mb-2">
                        <strong>Mensaje:</strong> {{ $exception->getMessage() }}
                    </p>
                    <p class="text-sm text-gray-600 font-mono">
                        <strong>Archivo:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Posibles Soluciones -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    ¿Qué puedes hacer?
                </h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Intenta recargar la página (a veces es temporal)
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Vuelve a la página anterior y prueba de nuevo
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Si el problema persiste, contacta al soporte técnico
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-2">•</span>
                        Limpia el caché de tu navegador
                    </li>
                </ul>
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
                <button onclick="location.reload()" 
                    class="flex-1 bg-white border-2 border-purple-500 text-purple-600 px-6 py-3 rounded-lg hover:bg-purple-50 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reintentar
                </button>
                <a href="/" 
                    class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ir al Inicio
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Si necesitas ayuda, contáctanos en 
                    <a href="mailto:soporte@ingreso-tienda.kcrsf.com" class="text-purple-600 hover:text-purple-800 font-semibold">
                        soporte@ingreso-tienda.kcrsf.com
                    </a>
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Código de error: 500 | {{ now()->format('Y-m-d H:i:s') }}
                </p>
            </div>
        </div>

        <!-- Animación decorativa -->
        <div class="text-center mt-6">
            <p class="text-white text-sm opacity-75">
                😵 Nuestros desarrolladores ya están trabajando en esto...
            </p>
        </div>
    </div>
</body>
</html>
