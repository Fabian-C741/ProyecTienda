<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónica',
                'description' => 'Dispositivos electrónicos y accesorios tecnológicos',
                'is_active' => true
            ],
            [
                'name' => 'Ropa y Moda',
                'description' => 'Prendas de vestir, calzado y accesorios',
                'is_active' => true
            ],
            [
                'name' => 'Hogar y Cocina',
                'description' => 'Artículos para el hogar, decoración y utensilios de cocina',
                'is_active' => true
            ],
            [
                'name' => 'Deportes',
                'description' => 'Equipamiento deportivo y ropa atlética',
                'is_active' => true
            ],
            [
                'name' => 'Libros',
                'description' => 'Libros físicos y digitales de todas las categorías',
                'is_active' => true
            ],
            [
                'name' => 'Juguetes',
                'description' => 'Juguetes y juegos para todas las edades',
                'is_active' => true
            ],
            [
                'name' => 'Belleza y Cuidado Personal',
                'description' => 'Productos de belleza, cosmética y cuidado personal',
                'is_active' => true
            ],
            [
                'name' => 'Alimentos y Bebidas',
                'description' => 'Productos alimenticios y bebidas',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => $category['is_active']
            ]);
        }
    }
}
