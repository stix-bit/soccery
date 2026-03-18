<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i = 0; $i < 50; $i++)
        {
            $product = new Product();
            $product->name = $faker->unique()->word();
            $product->description = $faker->paragraph();
            $product->price = $faker->randomFloat(2, 10, 1000);
            $product->stock = $faker->numberBetween(0, 100);
            $product->category_id = Category::inRandomOrder()->first()->id;
            $product->brand_id = Brand::inRandomOrder()->first()->id;
            $product->created_at = now();
            $product->updated_at = now();
            $product->save();
            Log::info('Product created: ' . $product->name);
        }
    }
}
