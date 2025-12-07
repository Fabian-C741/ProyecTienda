<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No hay categorías. Ejecuta CategorySeeder primero.');
            return;
        }

        $products = [
            ['name' => 'Laptop HP 15"', 'category' => 'Electrónica', 'price' => 899.99, 'stock' => 15, 'description' => 'Laptop HP 15 pulgadas, Intel Core i5, 8GB RAM, 256GB SSD'],
            ['name' => 'iPhone 14 Pro', 'category' => 'Electrónica', 'price' => 1299.99, 'stock' => 8, 'description' => 'iPhone 14 Pro 128GB, cámara triple, pantalla ProMotion'],
            ['name' => 'Smart TV Samsung 55"', 'category' => 'Electrónica', 'price' => 699.99, 'stock' => 12, 'description' => 'Smart TV Samsung 4K UHD 55 pulgadas con HDR'],
            ['name' => 'Auriculares Sony WH-1000XM5', 'category' => 'Electrónica', 'price' => 349.99, 'stock' => 25, 'description' => 'Auriculares inalámbricos con cancelación de ruido premium'],
            
            ['name' => 'Camiseta Nike Deportiva', 'category' => 'Ropa y Moda', 'price' => 29.99, 'stock' => 50, 'description' => 'Camiseta deportiva Nike Dri-FIT, disponible en varios colores'],
            ['name' => 'Jeans Levi\'s 501', 'category' => 'Ropa y Moda', 'price' => 79.99, 'stock' => 30, 'description' => 'Jeans clásicos Levi\'s 501 Original Fit'],
            ['name' => 'Zapatillas Adidas Ultraboost', 'category' => 'Ropa y Moda', 'price' => 189.99, 'stock' => 20, 'description' => 'Zapatillas running Adidas Ultraboost con tecnología Boost'],
            
            ['name' => 'Cafetera Nespresso', 'category' => 'Hogar y Cocina', 'price' => 149.99, 'stock' => 18, 'description' => 'Cafetera de cápsulas Nespresso con espumador de leche'],
            ['name' => 'Juego de Sartenes Tefal', 'category' => 'Hogar y Cocina', 'price' => 89.99, 'stock' => 22, 'description' => 'Set de 3 sartenes antiadherentes Tefal'],
            ['name' => 'Aspiradora Robot Roomba', 'category' => 'Hogar y Cocina', 'price' => 399.99, 'stock' => 10, 'description' => 'Aspiradora robot iRobot Roomba con mapeo inteligente'],
            
            ['name' => 'Pelota de Fútbol Adidas', 'category' => 'Deportes', 'price' => 24.99, 'stock' => 40, 'description' => 'Pelota oficial Adidas tamaño 5'],
            ['name' => 'Pesas Ajustables 20kg', 'category' => 'Deportes', 'price' => 129.99, 'stock' => 15, 'description' => 'Set de pesas ajustables de 5 a 20kg'],
            ['name' => 'Bicicleta de Montaña Trek', 'category' => 'Deportes', 'price' => 599.99, 'stock' => 7, 'description' => 'Bicicleta de montaña Trek Marlin 5, rodado 29'],
            
            ['name' => 'Harry Potter Colección Completa', 'category' => 'Libros', 'price' => 89.99, 'stock' => 25, 'description' => 'Box set completo de Harry Potter, 7 libros'],
            ['name' => 'Cien Años de Soledad', 'category' => 'Libros', 'price' => 15.99, 'stock' => 35, 'description' => 'Novela clásica de Gabriel García Márquez'],
            
            ['name' => 'LEGO Star Wars Millennium Falcon', 'category' => 'Juguetes', 'price' => 159.99, 'stock' => 12, 'description' => 'Set LEGO Halcón Milenario, 1351 piezas'],
            ['name' => 'Muñeca Barbie Fashionista', 'category' => 'Juguetes', 'price' => 24.99, 'stock' => 30, 'description' => 'Muñeca Barbie con accesorios de moda'],
            
            ['name' => 'Set Maquillaje MAC', 'category' => 'Belleza y Cuidado Personal', 'price' => 79.99, 'stock' => 20, 'description' => 'Kit de maquillaje profesional MAC'],
            ['name' => 'Perfume Chanel N°5', 'category' => 'Belleza y Cuidado Personal', 'price' => 129.99, 'stock' => 15, 'description' => 'Perfume clásico Chanel N°5, 100ml'],
            
            ['name' => 'Café Colombiano Premium 1kg', 'category' => 'Alimentos y Bebidas', 'price' => 19.99, 'stock' => 50, 'description' => 'Café colombiano 100% arábica, molido medio']
        ];

        foreach ($products as $productData) {
            $category = $categories->where('name', $productData['category'])->first();
            
            if ($category) {
                Product::create([
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'category_id' => $category->id,
                    'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    'is_active' => true,
                    'featured_image' => 'https://via.placeholder.com/400x400?text=' . urlencode($productData['name'])
                ]);
            }
        }
    }
}
