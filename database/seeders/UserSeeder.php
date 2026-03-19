<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->first_name = 'FirstName' . ($i + 1);
            $user->last_name = 'LastName' . ($i + 1);
            $user->password = bcrypt('password');
            $user->email = 'user' . ($i + 1) . '@example.com';
            $user->role = 'customer';
            $user->status = 'active';
            $user->created_at = now();
            $user->updated_at = now();
            $user->save();
            Log::info('User created: ' . $user->email);
        }
    }
}
