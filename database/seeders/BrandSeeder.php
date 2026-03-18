<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i = 0; $i < 20; $i++)
            {
                $brand = new Brand();
                $brand->name = $faker->company();
                $brand->description = $faker->paragraph();
                $brand->created_at = now();
                $brand->updated_at = now();
                $brand->save();
                Log::info('Brand created: ' . $brand->name);
            }
    }
}
