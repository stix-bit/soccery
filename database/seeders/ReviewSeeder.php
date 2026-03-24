<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'customer')->take(10)->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            Log::warning('ReviewSeeder skipped: users or products are missing.');
            return;
        }

        foreach ($users as $index => $user) {
            $product = $products[$index % $products->count()];

            $review = Review::create([
                'product_id' => $product->id,
                'users_id' => $user->id,
                'rating' => rand(4, 5),
                'comment' => 'Great quality product from ' . $product->name . '.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Review created: #' . $review->id);
        }
    }
}
