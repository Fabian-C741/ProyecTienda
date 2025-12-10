<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Tienda - Conviértete en Vendedor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-purple-600">
                        <i class="fas fa-store mr-2"></i>Ingreso Tienda
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-sign-in-alt mr-1"></i>Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                    <i class="fas fa-store text-5xl text-purple-600"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    ¡Abre Tu Tienda Online!
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Únete a nuestra plataforma y comienza a vender tus productos con tu propia tienda personalizada
                </p>
            </div>

            <!-- Beneficios -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="inline-block p-3 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-globe text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Tu Propio Subdominio</h3>
                    <p class="text-gray-600 text-sm">tutienda.ingreso-tienda.kcrsf.com</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="inline-block p-3 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-palette text-3xl text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Personalización Total</h3>
                    <p class="text-gray-600 text-sm">Diseña tu tienda a tu gusto</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="inline-block p-3 bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-headset text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Soporte Dedicado</h3>
                    <p class="text-gray-600 text-sm">Te ayudamos en todo momento</p>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario de Solicitud -->
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                    Solicita Tu Tienda
                </h2>
                
                <form action="{{ route('vendor.request.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nombre del Negocio -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-store mr-2 text-purple-600"></i>Nombre de tu Tienda *
                            </label>
                            <input type="text" 
                                   name="store_name" 
                                   value="{{ old('store_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="Mi Tienda Online" 
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Este será el nombre visible de tu tienda</p>
                        </div>

                        <!-- Slug (subdominio) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-link mr-2 text-purple-600"></i>URL de tu Tienda (Subdominio) *
                            </label>
                            <div class="flex items-center">
                                <span class="text-gray-500 text-sm mr-2">https://</span>
                                <input type="text" 
                                       name="slug" 
                                       value="{{ old('slug') }}"
                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       placeholder="mitienda"
                                       pattern="[a-z0-9-]+"
                                       required>
                                <span class="px-4 py-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-sm text-gray-600">
                                    .ingreso-tienda.kcrsf.com
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Solo letras minúsculas, números y guiones. Ejemplo: mi-tienda-online</p>
                        </div>

                        <!-- Nombre del Propietario -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-purple-600"></i>Tu Nombre Completo *
                            </label>
                            <input type="text" 
                                   name="owner_name" 
                                   value="{{ old('owner_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="Juan Pérez" 
                                   required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-purple-600"></i>Email *
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="tu@email.com" 
                                   required>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-purple-600"></i>Teléfono *
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                   placeholder="+1234567890" 
                                   required>
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-purple-600"></i>Categoría de Productos *
                            </label>
                            <select name="category" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                    required>
                                <option value="">Selecciona una categoría</option>
                                <option value="Ropa y Moda">Ropa y Moda</option>
                                <option value="Electrónica">Electrónica</option>
                                <option value="Hogar y Decoración">Hogar y Decoración</option>
                                <option value="Deportes">Deportes</option>
                                <option value="Belleza y Salud">Belleza y Salud</option>
                                <option value="Alimentos y Bebidas">Alimentos y Bebidas</option>
                                <option value="Libros y Medios">Libros y Medios</option>
                                <option value="Juguetes">Juguetes</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-purple-600"></i>Descripción de tu Negocio *
                            </label>
                            <textarea name="description" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                      placeholder="Cuéntanos sobre tu negocio, qué productos venderás, tu experiencia, etc."
                                      required>{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 50 caracteres</p>
                        </div>
                    </div>

                    <!-- Términos -->
                    <div class="flex items-start">
                        <input type="checkbox" 
                               name="terms" 
                               id="terms"
                               class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                               required>
                        <label for="terms" class="ml-3 text-sm text-gray-600">
                            Acepto los <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold">términos y condiciones</a> 
                            y la <a href="#" class="text-purple-600 hover:text-purple-800 font-semibold">política de privacidad</a>
                        </label>
                    </div>

                    <!-- Botón de envío -->
                    <button type="submit" 
                            class="w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-bold text-lg hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>Enviar Solicitud
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        Revisaremos tu solicitud en 24-48 horas y te contactaremos por email
                    </p>
                </form>
            </div>

            <!-- FAQ -->
            <div class="mt-12 bg-white rounded-lg shadow-md p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Preguntas Frecuentes</h3>
                
                <div class="space-y-4">
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                            ¿Cuánto tiempo tarda la aprobación?
                        </h4>
                        <p class="text-gray-600 text-sm ml-7">
                            Normalmente revisamos las solicitudes en 24-48 horas hábiles.
                        </p>
                    </div>
                    
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                            ¿Tiene algún costo?
                        </h4>
                        <p class="text-gray-600 text-sm ml-7">
                            Cobramos una pequeña comisión por cada venta realizada. Te informaremos los detalles al aprobar tu tienda.
                        </p>
                    </div>
                    
                    <div class="pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">
                            <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                            ¿Puedo cambiar el nombre de mi tienda después?
                        </h4>
                        <p class="text-gray-600 text-sm ml-7">
                            Sí, podrás modificar el nombre y la configuración de tu tienda desde el panel de administración.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Ingreso Tienda. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
