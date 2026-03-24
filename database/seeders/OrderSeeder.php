<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            Log::warning('OrderSeeder skipped: users or products are missing.');
            return;
        }

        foreach ($users as $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_method' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $orderProducts = $products->random(min(2, $products->count()));

            foreach ($orderProducts as $product) {
                DB::table('order_product')->insert([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Order created for user: ' . $user->email);
        }
    }
}
