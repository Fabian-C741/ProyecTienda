@extends('shop.layout')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Crear una cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                O
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    inicia sesión si ya tienes cuenta
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo *
                    </label>
                    <input id="name" name="name" type="text" required 
                           value="{{ old('name') }}"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico *
                    </label>
                    <input id="email" name="email" type="email" required 
                           value="{{ old('email') }}"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono
                    </label>
                    <input id="phone" name="phone" type="tel" 
                           value="{{ old('phone') }}"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Dirección
                    </label>
                    <input id="address" name="address" type="text" 
                           value="{{ old('address') }}"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña *
                    </label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar contraseña *
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Crear cuenta
                </button>
            </div>

            <div class="text-center">
                <p class="text-xs text-gray-600">
                    Al crear una cuenta, aceptas nuestros 
                    <a href="#" class="text-blue-600 hover:text-blue-500">Términos y Condiciones</a> 
                    y 
                    <a href="#" class="text-blue-600 hover:text-blue-500">Política de Privacidad</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
