<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Shoes',
                'description' => 'Soccer Shoes',
            ],
            [
                'name' => 'Shirts',
                'description' => 'Soccer Jerseys',
            ],
            [
                'name' => 'Shorts',
                'description' => 'Soccer Shorts',
            ],
            [
                'name' => 'Socks',
                'description' => 'Soccer Socks',
            ],
            [
                'name' => 'Ball',
                'description' => 'Soccer Ball',
            ],
        ];

        foreach ($categories as $data) {
            $category = new Category();
            $category->name = $data['name'];
            $category->description = $data['description'];
            $category->created_at = now();
            $category->updated_at = now();
            $category->save();

            Log::info('Category created: ' . $category->name);
        }
    }
}
