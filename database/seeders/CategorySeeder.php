<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i = 0; $i < 20; $i++)
            {
                $category = new Category();
                $category->name = $faker->unique()->word() . '-' . $faker->unique()->numberBetween(1, 1000);
                $category->description = $faker->paragraph();
                $category->created_at = now();
                $category->updated_at = now();
                $category->save();
                Log::info('Category created: ' . $category->name);
            }
    }
}
