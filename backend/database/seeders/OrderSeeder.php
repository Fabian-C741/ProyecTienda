<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Necesitas tener usuarios y productos primero.');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];

        for ($i = 1; $i <= 15; $i++) {
            $customer = $customers->random();
            $orderDate = Carbon::now()->subDays(rand(1, 30));
            $status = $statuses[array_rand($statuses)];
            
            // Crear orden
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'status' => $status,
                'total' => 0, // Se calculará después
                'subtotal' => 0,
                'tax' => 0,
                'shipping_address' => 'Calle Principal ' . rand(100, 999),
                'shipping_city' => 'Ciudad ' . rand(1, 10),
                'shipping_state' => 'Estado ' . rand(1, 5),
                'shipping_postal_code' => '10' . rand(100, 999),
                'shipping_country' => 'País',
                'payment_method' => ['Tarjeta de Crédito', 'PayPal', 'Transferencia'][rand(0, 2)],
                'payment_status' => $status === 'completed' ? 'paid' : ($status === 'cancelled' ? 'refunded' : 'pending'),
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            // Agregar items aleatorios (2-5 productos)
            $numItems = rand(2, 5);
            $subtotal = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity
                ]);

                $subtotal += $price * $quantity;
            }

            // Calcular totales
            $tax = $subtotal * 0.16; // 16% IVA
            $total = $subtotal + $tax;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ]);
        }
    }
}
