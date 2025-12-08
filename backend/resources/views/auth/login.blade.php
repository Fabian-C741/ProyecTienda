@extends('shop.layout')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                O
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    crea una cuenta nueva
                </a>
            </p>
        </div>

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                {{ session('info') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input id="email" name="email" type="email" required 
                           value="{{ old('email') }}"
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                           placeholder="tu@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Recordarme
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    Iniciar Sesión
                </button>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">¿Eres vendedor?</span>
                </div>
            </div>

            <div class="text-center">
                <a href="/admin/login" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    <i class="fas fa-store mr-1"></i>
                    Acceso para vendedores
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
