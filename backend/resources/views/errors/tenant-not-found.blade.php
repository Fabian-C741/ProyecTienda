<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda No Encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center">
            
            <!-- Icono -->
            <div class="mb-8">
                <div class="inline-block p-6 bg-red-100 rounded-full">
                    <i class="fas fa-store-slash text-6xl text-red-600"></i>
                </div>
            </div>

            <!-- Título -->
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Tienda No Encontrada
            </h1>

            <!-- Mensaje -->
            <p class="text-xl text-gray-600 mb-6">
                La tienda <span class="font-bold text-purple-600">{{ $subdomain }}</span> no existe o está inactiva
            </p>

            <!-- Descripción -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8">
                <p class="text-gray-700 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Posibles razones:
                </p>
                <ul class="text-left text-gray-600 space-y-2 max-w-md mx-auto">
                    <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i>El nombre de la tienda está mal escrito</li>
                    <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i>La tienda ha sido desactivada temporalmente</li>
                    <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i>La tienda ya no existe en nuestra plataforma</li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="https://ingreso-tienda.kcrsf.com" 
                   class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-home mr-2"></i>Ir al Inicio
                </a>
                
                <a href="https://ingreso-tienda.kcrsf.com/registro-vendedor" 
                   class="px-8 py-3 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-store mr-2"></i>¿Quieres abrir tu tienda?
                </a>
            </div>

            <!-- Contacto -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    ¿Crees que esto es un error? 
                    <a href="mailto:soporte@ingreso-tienda.kcrsf.com" class="text-purple-600 hover:text-purple-800 font-semibold">
                        Contacta con soporte
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-white text-sm opacity-80">
                Error 404 - Tienda no encontrada
            </p>
        </div>
    </div>
</body>
</html>
