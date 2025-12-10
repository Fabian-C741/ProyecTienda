<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-crown text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold">Super Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('super-admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-chart-line mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('super-admin.tenants') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-store mr-2"></i>Tiendas
                    </a>
                    <a href="{{ route('super-admin.users') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-users mr-2"></i>Usuarios
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded hover:bg-indigo-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Detalles
            </a>
        </div>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Tienda</h1>
            <p class="text-gray-500 mt-1">Modifica la información de {{ $tenant->name }}</p>
        </div>

        <!-- URL Actual (Solo lectura) -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-link text-blue-500 mr-3"></i>
<div>
                    <p class="text-sm font-medium text-blue-800">URL de la tienda</p>
                    <p class="text-blue-900 font-mono text-sm md:text-base break-all">
                        https://ingreso-tienda.kcrsf.com/tienda/{{ $tenant->slug }}
                    </p>
                    <p class="text-xs text-blue-700 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>
                        El slug no se puede modificar después de crear la tienda
                    </p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                            🔒 HTTPS Seguro
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                            ✅ SSL Activo
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <p class="font-bold mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>Hay errores en el formulario:
            </p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('super-admin.tenants.update', $tenant) }}" method="POST" class="bg-white rounded-lg shadow-lg p-8">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Información General
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Tienda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $tenant->name) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $tenant->email) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', $tenant->phone) }}"
                            placeholder="+57 300 123 4567"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            placeholder="Describe la tienda..."
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $tenant->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <i class="fas fa-cog text-indigo-600 mr-2"></i>
                    Configuración
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Comisión -->
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Tasa de Comisión (%) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                id="commission_rate" 
                                name="commission_rate" 
                                value="{{ old('commission_rate', $tenant->commission_rate) }}"
                                min="0" 
                                max="100" 
                                step="0.01"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('commission_rate') border-red-500 @enderror"
                                required>
                            <span class="absolute right-4 top-3 text-gray-500">%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Porcentaje que cobra la plataforma por cada venta</p>
                        @error('commission_rate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="status" 
                            name="status"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror"
                            required>
                            <option value="active" {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>
                                ✅ Activo
                            </option>
                            <option value="inactive" {{ old('status', $tenant->status) === 'inactive' ? 'selected' : '' }}>
                                ⏸️ Inactivo
                            </option>
                            <option value="suspended" {{ old('status', $tenant->status) === 'suspended' ? 'selected' : '' }}>
                                🚫 Suspendido
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-semibold">Activo:</span> La tienda está operativa<br>
                            <span class="font-semibold">Inactivo:</span> Pausada temporalmente<br>
                            <span class="font-semibold">Suspendido:</span> Bloqueada por el administrador
                        </p>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Metadata (Read-only) -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Información del Sistema</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Slug (Subdominio)</p>
                        <p class="font-mono bg-white px-3 py-1 rounded border">{{ $tenant->slug }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha de Creación</p>
                        <p class="text-gray-800">{{ $tenant->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="text-gray-800">{{ $tenant->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('super-admin.tenants.show', $tenant) }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mt-8">
            <h2 class="text-xl font-bold text-red-800 mb-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>Zona Peligrosa
            </h2>
            <p class="text-red-700 mb-4">
                Las siguientes acciones son permanentes y no se pueden deshacer.
            </p>
            <form action="{{ route('super-admin.tenants.destroy', $tenant) }}" method="POST" 
                onsubmit="return confirm('⚠️ ¿ESTÁS SEGURO?\n\nEsta acción eliminará:\n- La tienda {{ $tenant->name }}\n- Todos sus productos\n- Todos sus pedidos\n- Todos sus usuarios\n\nEsta acción NO se puede deshacer.\n\n¿Deseas continuar?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow">
                    <i class="fas fa-trash-alt mr-2"></i>Eliminar Tienda Permanentemente
                </button>
            </form>
        </div>
    </main>
</body>
</html>
