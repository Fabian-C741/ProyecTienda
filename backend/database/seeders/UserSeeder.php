<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@tienda.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ],
            [
                'name' => 'María González',
                'email' => 'maria@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer'
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
