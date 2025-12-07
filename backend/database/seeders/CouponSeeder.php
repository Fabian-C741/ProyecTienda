<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'BIENVENIDA2024',
                'type' => 'percentage',
                'value' => 15.00,
                'min_purchase' => 50.00,
                'max_uses' => 100,
                'expires_at' => Carbon::now()->addMonths(3),
                'description' => 'Descuento de bienvenida del 15% para nuevos clientes',
                'is_active' => true
            ],
            [
                'code' => 'VERANO25',
                'type' => 'fixed',
                'value' => 25.00,
                'min_purchase' => 100.00,
                'max_uses' => 50,
                'expires_at' => Carbon::now()->addMonths(2),
                'description' => '$25 de descuento en compras mayores a $100',
                'is_active' => true
            ],
            [
                'code' => 'BLACKFRIDAY',
                'type' => 'percentage',
                'value' => 30.00,
                'min_purchase' => null,
                'max_uses' => 200,
                'expires_at' => Carbon::now()->addDays(7),
                'description' => '30% de descuento Black Friday',
                'is_active' => true
            ],
            [
                'code' => 'ENVIOGRATIS',
                'type' => 'fixed',
                'value' => 10.00,
                'min_purchase' => 30.00,
                'max_uses' => null,
                'expires_at' => null,
                'description' => 'Cupón permanente de envío gratis',
                'is_active' => true
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::firstOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }
    }
}
