<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tenant de prueba
        $tenant = Tenant::create([
            'name' => 'Tienda Demo',
            'slug' => 'tienda-demo',
            'email' => 'demo@tienda.com',
            'phone' => '+54 11 1234-5678',
            'description' => 'Tienda de demostración para pruebas',
            'status' => 'active',
        ]);

        // Crear usuario tenant_admin para la tienda
        User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Vendedor Demo',
            'email' => 'vendedor@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'tenant_admin',
            'is_active' => true,
        ]);

        $this->command->info('✓ Tenant y usuario tenant_admin creados:');
        $this->command->info('  Email: vendedor@demo.com');
        $this->command->info('  Password: password123');
    }
}
