<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Seed de Super Admin y 2 Tenants de ejemplo
     * 
     * Crear:
     * - 1 Super Admin
     * - 2 Tenants con sus respectivos admins
     * - Datos de prueba para testing
     */
    public function run(): void
    {
        // 1. CREAR SUPER ADMIN
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@tienda.com'],
            [
                'name' => 'Super Administrador',
                'email' => 'superadmin@tienda.com',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'tenant_id' => null,
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Super Admin creado: superadmin@tienda.com / password123\n";

        // 2. CREAR TENANT 1: TIENDA DE TECNOLOGÍA
        $tenant1 = Tenant::updateOrCreate(
            ['slug' => 'tech-store'],
            [
                'name' => 'Tech Store',
                'slug' => 'tech-store',
                'email' => 'contacto@techstore.com',
                'phone' => '+1234567890',
                'description' => 'Tienda especializada en productos tecnológicos y gadgets',
                'commission_rate' => 10.00,
                'status' => 'active',
            ]
        );

        $tenantAdmin1 = User::updateOrCreate(
            ['email' => 'admin@techstore.com'],
            [
                'name' => 'Admin Tech Store',
                'email' => 'admin@techstore.com',
                'password' => Hash::make('password123'),
                'role' => 'tenant_admin',
                'tenant_id' => $tenant1->id,
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Tenant 1 creado: Tech Store (tech-store)\n";
        echo "   Admin: admin@techstore.com / password123\n";

        // 3. CREAR TENANT 2: TIENDA DE ROPA
        $tenant2 = Tenant::updateOrCreate(
            ['slug' => 'fashion-boutique'],
            [
                'name' => 'Fashion Boutique',
                'slug' => 'fashion-boutique',
                'email' => 'contacto@fashionboutique.com',
                'phone' => '+0987654321',
                'description' => 'Boutique de moda con las últimas tendencias',
                'commission_rate' => 15.00,
                'status' => 'active',
            ]
        );

        $tenantAdmin2 = User::updateOrCreate(
            ['email' => 'admin@fashionboutique.com'],
            [
                'name' => 'Admin Fashion Boutique',
                'email' => 'admin@fashionboutique.com',
                'password' => Hash::make('password123'),
                'role' => 'tenant_admin',
                'tenant_id' => $tenant2->id,
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Tenant 2 creado: Fashion Boutique (fashion-boutique)\n";
        echo "   Admin: admin@fashionboutique.com / password123\n";

        // 4. CREAR TENANT 3: TIENDA INACTIVA (para testing)
        $tenant3 = Tenant::updateOrCreate(
            ['slug' => 'inactive-store'],
            [
                'name' => 'Inactive Store',
                'slug' => 'inactive-store',
                'email' => 'contacto@inactivestore.com',
                'phone' => '+1122334455',
                'description' => 'Tienda desactivada para pruebas de middleware',
                'commission_rate' => 5.00,
                'status' => 'inactive',
            ]
        );

        $tenantAdmin3 = User::updateOrCreate(
            ['email' => 'admin@inactivestore.com'],
            [
                'name' => 'Admin Inactive Store',
                'email' => 'admin@inactivestore.com',
                'password' => Hash::make('password123'),
                'role' => 'tenant_admin',
                'tenant_id' => $tenant3->id,
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Tenant 3 creado: Inactive Store (inactive-store) - INACTIVO\n";
        echo "   Admin: admin@inactivestore.com / password123\n";

        // 5. CREAR CLIENTE DE PRUEBA
        $customer = User::updateOrCreate(
            ['email' => 'cliente@example.com'],
            [
                'name' => 'Cliente de Prueba',
                'email' => 'cliente@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'tenant_id' => null,
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Cliente creado: cliente@example.com / password123\n";

        echo "\n📊 RESUMEN:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Total Usuarios: " . User::count() . "\n";
        echo "Total Tenants: " . Tenant::count() . "\n";
        echo "Tenants Activos: " . Tenant::where('status', 'active')->count() . "\n";
        echo "Tenants Inactivos: " . Tenant::where('status', 'inactive')->count() . "\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n🔐 CREDENCIALES DE ACCESO:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🔵 SUPER ADMIN:\n";
        echo "   Email: superadmin@tienda.com\n";
        echo "   Password: password123\n";
        echo "   Ruta: /super-admin/dashboard\n\n";
        echo "🟢 TENANT 1 (Tech Store):\n";
        echo "   Email: admin@techstore.com\n";
        echo "   Password: password123\n";
        echo "   Ruta: /tenant/dashboard\n";
        echo "   Comisión: 10%\n\n";
        echo "🟢 TENANT 2 (Fashion Boutique):\n";
        echo "   Email: admin@fashionboutique.com\n";
        echo "   Password: password123\n";
        echo "   Ruta: /tenant/dashboard\n";
        echo "   Comisión: 15%\n\n";
        echo "🔴 TENANT 3 (Inactive Store - DESACTIVADO):\n";
        echo "   Email: admin@inactivestore.com\n";
        echo "   Password: password123\n";
        echo "   Estado: Inactivo (middleware bloqueará acceso)\n\n";
        echo "👤 CLIENTE:\n";
        echo "   Email: cliente@example.com\n";
        echo "   Password: password123\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n🧪 CASOS DE PRUEBA SUGERIDOS:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "1. Login como Super Admin → Acceder a /super-admin/dashboard\n";
        echo "   ✅ Debe mostrar estadísticas globales\n\n";
        echo "2. Login como Tenant 1 → Intentar acceder a /super-admin/dashboard\n";
        echo "   ❌ Debe retornar 403 Forbidden\n\n";
        echo "3. Login como Tenant 3 (Inactivo) → Acceder a /tenant/dashboard\n";
        echo "   ❌ Debe retornar 403 (TenantMiddleware bloquea)\n\n";
        echo "4. Super Admin → Crear nuevo producto global\n";
        echo "   ✅ Debe poder crear sin tenant_id\n\n";
        echo "5. Tenant 1 → Crear producto\n";
        echo "   ✅ Debe asignar automáticamente tenant_id = 1\n\n";
        echo "6. Tenant 1 → Intentar editar producto de Tenant 2\n";
        echo "   ❌ Debe retornar 403 + log de seguridad\n\n";
        echo "7. Super Admin → Desactivar Tenant 1\n";
        echo "   ✅ Debe cambiar status a 'inactive'\n\n";
        echo "8. Tenant 1 (ahora inactivo) → Intentar login\n";
        echo "   ❌ Middleware debe bloquear acceso\n\n";
        echo "9. Super Admin → Reactivar Tenant 1\n";
        echo "   ✅ Debe cambiar status a 'active'\n\n";
        echo "10. Tenant 1 → Volver a acceder\n";
        echo "    ✅ Debe funcionar normalmente\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }
}
