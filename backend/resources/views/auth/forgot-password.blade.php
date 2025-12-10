<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¿Olvidaste tu contraseña?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo/Icono -->
            <div class="text-center mb-6">
                <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
                    <i class="fas fa-key text-4xl text-purple-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">¿Olvidaste tu contraseña?</h1>
                <p class="text-gray-600 mt-2">No te preocupes, te ayudaremos a recuperarla</p>
            </div>

            <!-- Mensajes -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Formulario -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-500"></i>Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 @error('email') border-red-500 @enderror"
                           placeholder="tu@email.com"
                           required>
                    <p class="text-sm text-gray-500 mt-2">
                        Ingresa el correo con el que te registraste y te enviaremos un enlace para restablecer tu contraseña.
                    </p>
                </div>

                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Enviar Enlace de Recuperación
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center space-y-2">
                <a href="{{ route('login') }}" class="block text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al inicio de sesión
                </a>
                <div class="text-sm text-gray-500">
                    ¿No tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-800 font-semibold">
                        Regístrate aquí
                    </a>
                </div>
            </div>

            <!-- Info adicional -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>¿Cómo funciona?
                    </h3>
                    <ol class="text-sm text-blue-800 space-y-1 ml-6 list-decimal">
                        <li>Ingresa tu correo electrónico</li>
                        <li>Recibirás un email con un enlace seguro</li>
                        <li>Haz clic en el enlace y crea una nueva contraseña</li>
                        <li>¡Listo! Podrás iniciar sesión nuevamente</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-white text-sm opacity-80">
                <i class="fas fa-shield-alt mr-1"></i>
                Tu información está segura y protegida
            </p>
        </div>
    </div>
</body>
</html>
