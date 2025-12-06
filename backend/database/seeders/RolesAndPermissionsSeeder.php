<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Productos
            'view products',
            'create products',
            'edit products',
            'delete products',
            'publish products',
            
            // Categorías
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Órdenes
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'manage order status',
            
            // Usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Tenants
            'view tenants',
            'create tenants',
            'edit tenants',
            'delete tenants',
            'manage tenants',
            
            // Configuración
            'view settings',
            'edit settings',
            'manage payment gateways',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // Super Admin - Acceso total
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Tenant Admin - Gestiona su tienda
        $tenantAdmin = Role::create(['name' => 'tenant_admin']);
        $tenantAdmin->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products', 'publish products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view orders', 'edit orders', 'manage order status',
            'view settings', 'edit settings',
            'manage payment gateways',
        ]);

        // Customer - Cliente registrado
        $customer = Role::create(['name' => 'customer']);
        $customer->givePermissionTo([
            'view products',
            'view categories',
            'create orders',
            'view orders',
        ]);

        // Crear usuario super admin por defecto
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        $this->command->info('Roles y permisos creados exitosamente');
        $this->command->info('Super Admin creado: admin@tienda.com / password');
    }
}
