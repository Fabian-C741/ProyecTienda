<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Category;
use App\Models\Product;

class DemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener tenant demo
        $tenant = Tenant::where('slug', 'tienda-demo')->first();
        
        if (!$tenant) {
            $this->command->error('Tenant tienda-demo no encontrado. Ejecuta primero TenantSeeder.');
            return;
        }

        $this->command->info('Creando categorías y productos para tienda-demo...');

        // Crear categorías
        $categorias = [
            ['name' => 'Electrónica', 'slug' => 'electronica', 'description' => 'Productos electrónicos y tecnología'],
            ['name' => 'Ropa', 'slug' => 'ropa', 'description' => 'Ropa y accesorios de moda'],
            ['name' => 'Hogar', 'slug' => 'hogar', 'description' => 'Productos para el hogar'],
        ];

        $categoriesCreated = [];
        foreach ($categorias as $cat) {
            $category = Category::firstOrCreate(
                ['slug' => $cat['slug'], 'tenant_id' => $tenant->id],
                array_merge($cat, ['tenant_id' => $tenant->id, 'is_active' => true])
            );
            $categoriesCreated[$cat['slug']] = $category;
            $this->command->info("✓ Categoría: {$cat['name']}");
        }

        // Crear productos
        $productos = [
            // Electrónica
            [
                'name' => 'Laptop HP 15.6"',
                'slug' => 'laptop-hp-156',
                'category' => 'electronica',
                'description' => 'Laptop HP con procesador Intel Core i5, 8GB RAM, 256GB SSD',
                'price' => 599.99,
                'stock' => 10,
                'is_featured' => true,
            ],
            [
                'name' => 'Mouse Inalámbrico Logitech',
                'slug' => 'mouse-inalambrico-logitech',
                'category' => 'electronica',
                'description' => 'Mouse inalámbrico ergonómico con batería de larga duración',
                'price' => 29.99,
                'stock' => 50,
                'is_featured' => false,
            ],
            
            // Ropa
            [
                'name' => 'Camiseta Básica Algodón',
                'slug' => 'camiseta-basica-algodon',
                'category' => 'ropa',
                'description' => 'Camiseta 100% algodón, disponible en varios colores',
                'price' => 19.99,
                'stock' => 100,
                'is_featured' => true,
            ],
            [
                'name' => 'Jeans Clásicos',
                'slug' => 'jeans-clasicos',
                'category' => 'ropa',
                'description' => 'Jeans de mezclilla clásicos, corte regular',
                'price' => 49.99,
                'stock' => 30,
                'is_featured' => false,
            ],
            
            // Hogar
            [
                'name' => 'Juego de Toallas Premium',
                'slug' => 'juego-toallas-premium',
                'category' => 'hogar',
                'description' => 'Set de 6 toallas de algodón egipcio premium',
                'price' => 79.99,
                'stock' => 20,
                'is_featured' => true,
            ],
        ];

        foreach ($productos as $prod) {
            $category = $categoriesCreated[$prod['category']];
            
            Product::firstOrCreate(
                ['slug' => $prod['slug'], 'tenant_id' => $tenant->id],
                [
                    'tenant_id' => $tenant->id,
                    'category_id' => $category->id,
                    'name' => $prod['name'],
                    'description' => $prod['description'],
                    'price' => $prod['price'],
                    'stock' => $prod['stock'],
                    'is_active' => true,
                    'is_featured' => $prod['is_featured'],
                ]
            );
            
            $this->command->info("✓ Producto: {$prod['name']} - \${$prod['price']}");
        }

        $this->command->info('');
        $this->command->info('✅ Demo completado:');
        $this->command->info('   - 3 categorías creadas');
        $this->command->info('   - 5 productos creados');
        $this->command->info('');
        $this->command->info('🔗 Ver tienda: https://ingreso-tienda.kcrsf.com/tienda/tienda-demo');
    }
}
