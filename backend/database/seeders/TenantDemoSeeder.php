<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\TenantSetting;
use Illuminate\Support\Facades\Hash;

class TenantDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear tenant demo
        $tenant = Tenant::create([
            'name' => 'Tienda Demo',
            'slug' => 'tienda-demo',
            'email' => 'demo@tienda.com',
            'phone' => '+1234567890',
            'address' => 'Av. Principal 123',
            'status' => 'active',
            'commission_rate' => 10.00,
        ]);

        // Crear configuración del tenant
        TenantSetting::create([
            'tenant_id' => $tenant->id,
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'font_family' => 'Inter',
            'show_categories' => true,
            'show_search' => true,
            'show_reviews' => true,
        ]);

        // Crear usuario vendedor/admin del tenant
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Vendedor Demo',
            'email' => 'vendedor@tienda.com',
            'password' => Hash::make('vendedor123'),
            'role' => 'tenant_admin',
            'is_active' => true,
        ]);

        $this->command->info('✅ Tenant creado exitosamente!');
        $this->command->info('📧 Email: vendedor@tienda.com');
        $this->command->info('🔑 Password: vendedor123');
        $this->command->info('🏪 Tenant: ' . $tenant->name . ' (ID: ' . $tenant->id . ')');
    }
}
