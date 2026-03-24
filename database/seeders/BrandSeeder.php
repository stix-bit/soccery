<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Facades\Log;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Manchester United',
                'description' => 'Manchester United Football Club',
            ],
            [
                'name' => 'Everton',
                'description' => 'Everton Football Club',
            ],
            [
                'name' => 'Liverpool',
                'description' => 'Liverpool Football Club',
            ],
            [
                'name' => 'Arsenal',
                'description' => 'Arsenal Football Club',
            ],
            [
                'name' => 'Chelsea',
                'description' => 'Chelsea Football Club',
            ],
        ];

        foreach ($brands as $data) {
            $brand = new Brand();
            $brand->name = $data['name'];
            $brand->description = $data['description'];
            $brand->created_at = now();
            $brand->updated_at = now();
            $brand->save();

            Log::info('Brand created: ' . $brand->name);
        }
    }
}
