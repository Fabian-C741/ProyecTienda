<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso No Autorizado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes shake-head {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-15deg); }
            75% { transform: rotate(15deg); }
        }
        @keyframes blink {
            0%, 90%, 100% { opacity: 1; }
            95% { opacity: 0; }
        }
        .person-float {
            animation: float 3s ease-in-out infinite;
        }
        .head-shake {
            animation: shake-head 2s ease-in-out infinite;
        }
        .eye-blink {
            animation: blink 4s ease-in-out infinite;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full">
        <!-- Card principal -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center">
            
            <!-- Persona animada confundida (SVG) -->
            <div class="mb-8 person-float">
                <svg class="w-64 h-64 mx-auto" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <!-- Cuerpo -->
                    <ellipse cx="100" cy="140" rx="35" ry="50" fill="#667eea" opacity="0.8"/>
                    
                    <!-- Brazos (levantados en confusión) -->
                    <path d="M 65 120 Q 40 100, 30 85" stroke="#667eea" stroke-width="8" fill="none" stroke-linecap="round"/>
                    <path d="M 135 120 Q 160 100, 170 85" stroke="#667eea" stroke-width="8" fill="none" stroke-linecap="round"/>
                    
                    <!-- Manos -->
                    <circle cx="30" cy="85" r="8" fill="#FDB5B5"/>
                    <circle cx="170" cy="85" r="8" fill="#FDB5B5"/>
                    
                    <!-- Cabeza (con animación de sacudida) -->
                    <g class="head-shake" transform-origin="100 70">
                        <circle cx="100" cy="70" r="35" fill="#FDB5B5"/>
                        
                        <!-- Ojos confundidos -->
                        <g class="eye-blink">
                            <circle cx="88" cy="65" r="4" fill="#333"/>
                            <circle cx="112" cy="65" r="4" fill="#333"/>
                        </g>
                        
                        <!-- Cejas levantadas (confusión) -->
                        <path d="M 80 55 Q 88 50, 95 55" stroke="#333" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        <path d="M 105 55 Q 112 50, 120 55" stroke="#333" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        
                        <!-- Boca confundida (línea ondulada) -->
                        <path d="M 85 85 Q 100 82, 115 85" stroke="#333" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                        
                        <!-- Cabello -->
                        <path d="M 70 50 Q 75 35, 85 40 Q 90 30, 100 35 Q 110 30, 115 40 Q 125 35, 130 50" fill="#4A4A4A" stroke="#4A4A4A" stroke-width="2"/>
                    </g>
                    
                    <!-- Signos de interrogación flotantes -->
                    <text x="140" y="40" font-size="30" fill="#667eea" opacity="0.6">?</text>
                    <text x="50" y="50" font-size="25" fill="#764ba2" opacity="0.5">?</text>
                    <text x="155" y="80" font-size="20" fill="#667eea" opacity="0.4">?</text>
                </svg>
            </div>

            <!-- Emoji alternativo para móviles que no soporten SVG bien -->
            <div class="mb-6 md:hidden">
                <span class="text-8xl">🤔</span>
            </div>

            <!-- Título -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                ¡Oops! 🔒
            </h1>

            <!-- Mensaje principal -->
            <p class="text-xl md:text-2xl text-gray-600 mb-6">
                Parece que no has iniciado sesión
            </p>

            <!-- Descripción -->
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Para acceder a esta página necesitas estar autenticado. 
                Por favor, inicia sesión para continuar.
            </p>

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/login" 
                   class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Iniciar Sesión
                </a>
                
                <a href="/" 
                   class="px-8 py-3 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Volver al Inicio
                </a>
            </div>

            <!-- Información adicional -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-400">
                    Error 401 - No Autenticado
                </p>
            </div>
        </div>

        <!-- Mensaje de ayuda debajo de la card -->
        <div class="mt-6 text-center">
            <p class="text-white text-sm opacity-80">
                ¿Necesitas ayuda? Contacta al administrador del sistema
            </p>
        </div>
    </div>
</body>
</html>
