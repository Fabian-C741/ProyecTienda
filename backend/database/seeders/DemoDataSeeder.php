<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear tenant de demostración
        $tenant = Tenant::create([
            'name' => 'Tienda Demo',
            'domain' => 'demo.tienda.com',
            'database' => 'demo_db',
            'settings' => [
                'currency' => 'USD',
                'language' => 'es',
                'timezone' => 'America/Argentina/Buenos_Aires',
            ],
            'is_active' => true,
        ]);

        // Crear usuario super admin
        $superAdmin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Super Admin',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password123'),
            'phone' => '+54911234567',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super_admin');

        // Crear usuario admin de tienda
        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin Tienda',
            'email' => 'tienda@admin.com',
            'password' => Hash::make('password123'),
            'phone' => '+54911234568',
            'is_active' => true,
        ]);
        $admin->assignRole('tenant_admin');

        // Crear usuario vendedor
        $vendedor = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Vendedor Test',
            'email' => 'vendedor@tienda.com',
            'password' => Hash::make('password123'),
            'phone' => '+54911234569',
            'is_active' => true,
        ]);
        $vendedor->assignRole('vendedor');

        // Crear usuario cliente
        $cliente = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cliente Test',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password123'),
            'phone' => '+54911234570',
            'is_active' => true,
        ]);
        $cliente->assignRole('cliente');

        // Crear categorías
        $categorias = [
            ['name' => 'Electrónica', 'description' => 'Productos electrónicos', 'is_active' => true],
            ['name' => 'Ropa', 'description' => 'Indumentaria y accesorios', 'is_active' => true],
            ['name' => 'Hogar', 'description' => 'Artículos para el hogar', 'is_active' => true],
            ['name' => 'Deportes', 'description' => 'Equipamiento deportivo', 'is_active' => true],
            ['name' => 'Libros', 'description' => 'Libros y revistas', 'is_active' => true],
        ];

        $categoriasCreadas = [];
        foreach ($categorias as $cat) {
            $categoriasCreadas[] = Category::create([
                'tenant_id' => $tenant->id,
                ...$cat
            ]);
        }

        // Crear productos de ejemplo
        $productos = [
            ['category' => 0, 'name' => 'Smartphone Samsung Galaxy', 'price' => 599.99, 'stock' => 50],
            ['category' => 0, 'name' => 'Laptop Dell XPS 15', 'price' => 1299.99, 'stock' => 20],
            ['category' => 0, 'name' => 'Auriculares Sony WH-1000XM4', 'price' => 299.99, 'stock' => 100],
            ['category' => 1, 'name' => 'Remera Nike Deportiva', 'price' => 49.99, 'stock' => 200],
            ['category' => 1, 'name' => 'Zapatillas Adidas Running', 'price' => 89.99, 'stock' => 80],
            ['category' => 2, 'name' => 'Cafetera Nespresso', 'price' => 199.99, 'stock' => 30],
            ['category' => 2, 'name' => 'Aspiradora Roomba', 'price' => 499.99, 'stock' => 15],
            ['category' => 3, 'name' => 'Bicicleta Mountain Bike', 'price' => 799.99, 'stock' => 10],
            ['category' => 3, 'name' => 'Pelota de Fútbol Adidas', 'price' => 39.99, 'stock' => 150],
            ['category' => 4, 'name' => 'Libro "Clean Code"', 'price' => 29.99, 'stock' => 60],
        ];

        foreach ($productos as $prod) {
            Product::create([
                'tenant_id' => $tenant->id,
                'category_id' => $categoriasCreadas[$prod['category']]->id,
                'name' => $prod['name'],
                'description' => 'Descripción detallada de ' . $prod['name'],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'images' => [],
                'is_active' => true,
            ]);
        }

        $this->command->info('Datos de demostración creados exitosamente');
        $this->command->info('Usuarios creados:');
        $this->command->info('- Super Admin: admin@tienda.com / password123');
        $this->command->info('- Tenant Admin: tienda@admin.com / password123');
        $this->command->info('- Vendedor: vendedor@tienda.com / password123');
        $this->command->info('- Cliente: cliente@tienda.com / password123');
    }
}
