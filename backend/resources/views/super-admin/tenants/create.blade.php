<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Tienda - Super Admin</title>
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
                <div class="flex items-center">
                    <a href="{{ route('super-admin.tenants') }}" class="px-4 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-arrow-left mr-2"></i>Volver a Tiendas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-store mr-2 text-indigo-600"></i>Crear Nueva Tienda
            </h1>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-bold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Errores:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('super-admin.tenants.store') }}" method="POST">
                @csrf

                <!-- Información de la Tienda -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>Información de la Tienda
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de la Tienda <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Ej: Tienda de Ropa">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Identificador de la Tienda (URL) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="mi-tienda-ejemplo">
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Solo letras minúsculas, números y guiones. Debe ser único.
                            </p>
                            <p class="text-xs text-blue-600 mt-1 font-medium">
                                <i class="fas fa-link mr-1"></i>
                                URL: <span id="url-preview" class="font-mono">https://ingreso-tienda.kcrsf.com/tienda/mi-tienda-ejemplo</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="contacto@tienda.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="+1 234 567 890">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Descripción breve de la tienda...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tasa de Comisión (%)
                            </label>
                            <input type="number" name="commission_rate" value="{{ old('commission_rate', 10) }}" 
                                min="0" max="100" step="0.01"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">Por defecto 10%</p>
                        </div>
                    </div>
                </div>

                <!-- Información del Administrador -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-user-shield mr-2 text-green-600"></i>Administrador de la Tienda
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Admin <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Juan Pérez">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email del Admin <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="admin@tienda.com">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="admin_password" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Mínimo 8 caracteres">
                            <p class="text-xs text-gray-500 mt-1">Esta será la contraseña del vendedor para acceder a su panel</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('super-admin.tenants') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow">
                        <i class="fas fa-check mr-2"></i>Crear Tienda
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto-generar slug desde el nombre
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.getElementById('slug');
        const urlPreview = document.getElementById('url-preview');

        nameInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.manual !== 'true') {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Quitar tildes
                    .replace(/[^a-z0-9\s-]/g, '') // Solo letras, números, espacios y guiones
                    .replace(/\s+/g, '-') // Espacios a guiones
                    .replace(/-+/g, '-') // Múltiples guiones a uno solo
                    .replace(/^-|-$/g, ''); // Quitar guiones al inicio/final
                
                slugInput.value = slug;
                updateUrlPreview(slug);
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
            updateUrlPreview(this.value);
        });

        function updateUrlPreview(slug) {
            const baseUrl = 'https://ingreso-tienda.kcrsf.com/tienda/';
            urlPreview.textContent = baseUrl + (slug || 'tu-tienda');
        }

        // Actualizar preview inicial
        if (slugInput.value) {
            updateUrlPreview(slugInput.value);
        }
    </script>
</body>
</html>
