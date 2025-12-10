<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} - Detalles</title>
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('super-admin.tenants') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver a Tiendas
            </a>
        </div>

        <!-- Header with Actions -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $tenant->name }}</h1>
                <p class="text-gray-500 mt-1">Detalles de la tienda</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('super-admin.tenants.edit', $tenant) }}" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('super-admin.tenants.toggle-status', $tenant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-power-off mr-2"></i>
                        {{ $tenant->status === 'active' ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- URL del Subdominio (DESTACADO) -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium opacity-90 mb-1">URL de la Tienda</p>
                    <div class="flex items-center space-x-3 flex-wrap">
                        <a href="https://ingreso-tienda.kcrsf.com/tienda/{{ $tenant->slug }}" 
                            target="_blank"
                            class="text-xl md:text-2xl font-bold hover:underline flex items-center break-all">
                            ingreso-tienda.kcrsf.com/tienda/{{ $tenant->slug }}
                            <i class="fas fa-external-link-alt ml-3 text-lg flex-shrink-0"></i>
                        </a>
                    </div>
                    <p class="text-sm opacity-75 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Los clientes acceden a través de esta URL
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="text-xs bg-white bg-opacity-20 px-3 py-1 rounded-full">
                            ✅ Compatible con SSL estándar
                        </span>
                        <span class="text-xs bg-white bg-opacity-20 px-3 py-1 rounded-full">
                            🔒 HTTPS Seguro
                        </span>
                        <span class="text-xs bg-white bg-opacity-20 px-3 py-1 rounded-full">
                            🚀 Sin configuración adicional
                        </span>
                    </div>
                </div>
                <button onclick="copyURL()" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition flex-shrink-0 whitespace-nowrap">
                    <i class="fas fa-copy mr-2"></i>Copiar URL
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-box text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Productos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Pedidos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Usuarios</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-dollar-sign text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ingresos</p>
                        <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información General -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Información General
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nombre</label>
                        <p class="text-gray-800 font-semibold">{{ $tenant->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Slug (Subdominio)</label>
                        <p class="text-gray-800 font-mono bg-gray-100 px-3 py-1 rounded">{{ $tenant->slug }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-800">{{ $tenant->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Teléfono</label>
                        <p class="text-gray-800">{{ $tenant->phone ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Descripción</label>
                        <p class="text-gray-800">{{ $tenant->description ?? 'Sin descripción' }}</p>
                    </div>
                </div>
            </div>

            <!-- Credenciales de Acceso -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-key text-yellow-600 mr-2"></i>
                    Credenciales de Acceso
                </h2>
                @php
                    $admin = \App\Models\User::where('tenant_id', $tenant->id)
                                             ->where('role', 'admin')
                                             ->first();
                @endphp
                
                @if($admin)
                <div class="space-y-4">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <p class="text-sm text-yellow-800 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Estas son las credenciales del administrador de la tienda
                        </p>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-600">Nombre</label>
                                <p class="text-gray-900 font-semibold">{{ $admin->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-600">Email de acceso</label>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-900 font-mono bg-white px-3 py-1 rounded border">{{ $admin->email }}</p>
                                    <button onclick="copyText('{{ $admin->email }}')" class="text-indigo-600 hover:text-indigo-800" title="Copiar email">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded p-3">
                                <label class="text-xs font-medium text-red-800 flex items-center gap-1">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Contraseña
                                </label>
                                <p class="text-red-900 text-sm mt-1">
                                    Por seguridad, las contraseñas están encriptadas y no se pueden visualizar.
                                </p>
                                <p class="text-xs text-red-700 mt-2">
                                    💡 Si el usuario olvidó su contraseña, usa la opción "Resetear Contraseña" en la gestión de usuarios.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-3 border-t">
                        <a href="{{ route('super-admin.users.edit', $admin->id) }}" 
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-user-edit mr-2"></i>
                            Editar Usuario Administrador
                        </a>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded p-4 text-center">
                    <i class="fas fa-user-slash text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-600">No se encontró usuario administrador para esta tienda</p>
                </div>
                @endif
            </div>

            <!-- Configuración -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cog text-indigo-600 mr-2"></i>
                    Configuración
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Estado</label>
                        <p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($tenant->status === 'active') bg-green-100 text-green-800
                                @elseif($tenant->status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tasa de Comisión</label>
                        <p class="text-2xl font-bold text-gray-800">{{ $tenant->commission_rate }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fecha de Registro</label>
                        <p class="text-gray-800">{{ $tenant->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Última Actualización</label>
                        <p class="text-gray-800">{{ $tenant->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        @if($tenant->products->count() > 0)
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-box text-indigo-600 mr-2"></i>
                Productos Recientes
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tenant->products->take(5) as $product)
                        <tr>
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            <td class="px-6 py-4">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Orders -->
        @if($tenant->orders->count() > 0)
        <div class="bg-white rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-shopping-cart text-indigo-600 mr-2"></i>
                Pedidos Recientes
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tenant->orders->take(5) as $order)
                        <tr>
                            <td class="px-6 py-4">#{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </main>

    <script>
        function copyURL() {
            const url = "https://ingreso-tienda.kcrsf.com/tienda/{{ $tenant->slug }}";
            navigator.clipboard.writeText(url).then(() => {
                alert('URL copiada: ' + url);
            });
        }

        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('📋 Copiado: ' + text);
            });
        }
    </script>
</body>
</html>
