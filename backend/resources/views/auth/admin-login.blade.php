<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-blue-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Panel de Vendedor</h1>
            <p class="text-gray-600 mt-2">Ingresa a tu tienda</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="vendedor@tienda.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>
                    Contraseña
                </label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Iniciar Sesión
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>¿No tienes una tienda? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Regístrate aquí
                </a>
            </p>
        </div>

        <!-- Demo Credentials (solo para desarrollo) -->
        @if(config('app.env') !== 'production')
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-xs text-yellow-800 font-bold mb-2">🔑 Credenciales Demo:</p>
            <p class="text-xs text-yellow-700">Email: vendedor@tienda.com</p>
            <p class="text-xs text-yellow-700">Password: vendedor123</p>
        </div>
        @endif
    </div>
</body>
</html>
