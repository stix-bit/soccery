<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all();

        $shirtsCategory = Category::where('name', 'Shirts')->first();
        $shortsCategory = Category::where('name', 'Shorts')->first();
        $socksCategory = Category::where('name', 'Socks')->first();
        $ballCategory = Category::where('name', 'Ball')->first();
        $shoesCategory = Category::where('name', 'Shoes')->first();

        foreach ($brands as $brand) {
            if ($shirtsCategory) {
                $shirtVariants = ['Home', 'Away'];

                foreach ($shirtVariants as $index => $variant) {
                    $product = Product::create([
                        'name' => $brand->name . ' Shirt ' . $variant,
                        'description' => $brand->name . ' Shirt ' . $variant,
                        'price' => 300.00 + ($index * 5),
                        'stock' => 100,
                        'category_id' => $shirtsCategory->id,
                        'brand_id' => $brand->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Product created: ' . $product->name);
                }
            }

            if ($shortsCategory) {
                $shortVariants = ['Home', 'Away'];

                foreach ($shortVariants as $index => $variant) {
                    $product = Product::create([
                        'name' => $brand->name . ' Short ' . $variant,
                        'description' => $brand->name . ' Short ' . $variant,
                        'price' => 250.00 + ($index * 5),
                        'stock' => 100,
                        'category_id' => $shortsCategory->id,
                        'brand_id' => $brand->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('Product created: ' . $product->name);
                }
            }

            if ($socksCategory) {
                $product = Product::create([
                    'name' => $brand->name . ' Sock',
                    'description' => $brand->name . ' Sock',
                    'price' => 100.00,
                    'stock' => 100,
                    'category_id' => $socksCategory->id,
                    'brand_id' => $brand->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Product created: ' . $product->name);
            }

            if ($ballCategory) {
                $product = Product::create([
                    'name' => $brand->name . ' Ball',
                    'description' => $brand->name . ' Ball',
                    'price' => 400.00,
                    'stock' => 100,
                    'category_id' => $ballCategory->id,
                    'brand_id' => $brand->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Product created: ' . $product->name);
            }

            if ($shoesCategory) {
                $product = Product::create([
                    'name' => $brand->name . ' Shoes',
                    'description' => $brand->name . ' Shoes',
                    'price' => 500.00,
                    'stock' => 100,
                    'category_id' => $shoesCategory->id,
                    'brand_id' => $brand->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Product created: ' . $product->name);
            }
        }
    }
}
