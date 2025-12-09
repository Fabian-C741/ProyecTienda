<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tiendas - Super Admin</title>
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
                    <a href="{{ route('super-admin.tenants') }}" class="px-3 py-2 rounded bg-indigo-700">
                        <i class="fas fa-store mr-2"></i>Tiendas
                    </a>
                    <a href="{{ route('super-admin.users') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-users mr-2"></i>Usuarios
                    </a>
                    <a href="{{ route('super-admin.commissions') }}" class="px-3 py-2 rounded hover:bg-indigo-700">
                        <i class="fas fa-dollar-sign mr-2"></i>Comisiones
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
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-store mr-2 text-indigo-600"></i>Gestión de Tiendas
            </h1>
            <a href="{{ route('super-admin.tenants.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 shadow">
                <i class="fas fa-plus mr-2"></i>Nueva Tienda
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('super-admin.tenants') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Nombre, slug o email..." 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendido</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-800">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <!-- Tenants Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tienda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estadísticas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comisión</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($tenant->logo)
                                <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-10 h-10 rounded-full mr-3">
                                @else
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-indigo-600"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $tenant->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $tenant->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-800">{{ $tenant->email }}</p>
                            <p class="text-sm text-gray-500">{{ $tenant->phone ?? 'Sin teléfono' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4 text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-box text-blue-500"></i> {{ $tenant->products_count }}
                                </span>
                                <span class="text-gray-600">
                                    <i class="fas fa-shopping-cart text-green-500"></i> {{ $tenant->orders_count }}
                                </span>
                                <span class="text-gray-600">
                                    <i class="fas fa-users text-purple-500"></i> {{ $tenant->users_count }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ $tenant->commission_rate }}%</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($tenant->status === 'active') bg-green-100 text-green-800
                                @elseif($tenant->status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" 
                                    class="text-blue-600 hover:text-blue-800" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('super-admin.tenants.edit', $tenant) }}" 
                                    class="text-indigo-600 hover:text-indigo-800" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('super-admin.tenants.toggle-status', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Activar/Desactivar">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <form action="{{ route('super-admin.tenants.destroy', $tenant) }}" method="POST" 
                                    class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta tienda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No hay tiendas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tenants->links() }}
        </div>
    </main>
</body>
</html>
