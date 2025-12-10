<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">Super Admin Panel</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('super-admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('super-admin.tenants') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-store"></i> Tenants
                    </a>
                    <a href="{{ route('super-admin.users') }}" class="text-blue-600 font-semibold">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                    <a href="{{ route('super-admin.commissions') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-dollar-sign"></i> Comisiones
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h2>
            <p class="text-gray-600">Administra todos los usuarios del sistema</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Usuarios</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-crown text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Super Admins</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role', 'super_admin')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-store text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Admins Tienda</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role', 'tenant_admin')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                        <i class="fas fa-shopping-cart text-white text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Clientes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->where('role', 'customer')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('super-admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nombre o email..." 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                    <select name="role" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="tenant_admin" {{ request('role') == 'tenant_admin' ? 'selected' : '' }}>Admin Tienda</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tenant</label>
                    <select name="tenant_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tienda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role == 'super_admin')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-crown mr-1"></i> Super Admin
                                    </span>
                                @elseif($user->role == 'tenant_admin')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-store mr-1"></i> Admin Tienda
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-user mr-1"></i> Cliente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($user->tenant)
                                    {{ $user->tenant->name }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->status == 'active')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Ver detalles -->
                                    <button onclick="viewUser({{ $user->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors" 
                                            title="Ver detalles">
                                        <i class="far fa-eye text-lg"></i>
                                    </button>
                                    
                                    <!-- Editar -->
                                    <a href="{{ route('super-admin.users.edit', $user->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors" 
                                       title="Editar usuario">
                                        <i class="far fa-edit text-lg"></i>
                                    </a>
                                    
                                    <!-- Restablecer contraseña -->
                                    <button onclick="resetPassword({{ $user->id }})" 
                                            class="text-yellow-600 hover:text-yellow-900 transition-colors" 
                                            title="Restablecer contraseña">
                                        <i class="fas fa-key text-lg"></i>
                                    </button>
                                    
                                    <!-- Eliminar -->
                                    @if($user->role != 'super_admin')
                                    <button onclick="deleteUser({{ $user->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                            title="Eliminar usuario">
                                        <i class="far fa-trash-alt text-lg"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">No se encontraron usuarios</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ver Usuario -->
    <div id="viewUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Detalles del Usuario</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="userDetails" class="space-y-3">
                <!-- Se llenará dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modal Restablecer Contraseña -->
    <div id="resetPasswordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Restablecer Contraseña</h3>
                <button onclick="closeResetModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="resetPasswordForm">
                <input type="hidden" id="resetUserId" name="user_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                    <input type="password" id="newPassword" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Ingresa nueva contraseña" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" id="confirmPassword" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                           placeholder="Confirma la contraseña" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeResetModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        <i class="fas fa-key mr-2"></i>Restablecer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Ver detalles del usuario
        function viewUser(userId) {
            fetch(`/super-admin/users/${userId}/details`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('userDetails').innerHTML = `
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Nombre</p>
                            <p class="font-semibold">${user.name}</p>
                        </div>
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold">${user.email}</p>
                        </div>
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Rol</p>
                            <p class="font-semibold">${user.role_display}</p>
                        </div>
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Tienda</p>
                            <p class="font-semibold">${user.tenant_name || '-'}</p>
                        </div>
                        <div class="border-b pb-3">
                            <p class="text-sm text-gray-500">Estado</p>
                            <p class="font-semibold ${user.status == 'active' ? 'text-green-600' : 'text-red-600'}">
                                ${user.status == 'active' ? 'Activo' : 'Inactivo'}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha de registro</p>
                            <p class="font-semibold">${user.created_at}</p>
                        </div>
                    `;
                    document.getElementById('viewUserModal').classList.remove('hidden');
                })
                .catch(error => {
                    alert('Error al cargar los detalles del usuario');
                    console.error(error);
                });
        }

        function closeViewModal() {
            document.getElementById('viewUserModal').classList.add('hidden');
        }

        // Restablecer contraseña
        function resetPassword(userId) {
            document.getElementById('resetUserId').value = userId;
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
            document.getElementById('resetPasswordModal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = document.getElementById('resetUserId').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }

            if (newPassword.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres');
                return;
            }

            fetch(`/super-admin/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: newPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contraseña restablecida exitosamente');
                    closeResetModal();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo restablecer la contraseña'));
                }
            })
            .catch(error => {
                alert('Error al restablecer la contraseña');
                console.error(error);
            });
        });

        // Eliminar usuario
        function deleteUser(userId) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                fetch(`/super-admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuario eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo eliminar el usuario'));
                    }
                })
                .catch(error => {
                    alert('Error al eliminar el usuario');
                    console.error(error);
                });
            }
        }
    </script>
</body>
</html>
