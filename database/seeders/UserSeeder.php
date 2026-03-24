<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Log::info('Admin user seeded: ' . $admin->email);
        for($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->first_name = 'FirstName' . ($i + 1);
            $user->last_name = 'LastName' . ($i + 1);
            $user->password = bcrypt('password');
            $user->email = 'user' . ($i + 1) . '@gmail.com';
            $user->role = 'customer';
            $user->status = 'active';
            $user->email_verified_at = now();
            $user->created_at = now();
            $user->updated_at = now();
            $user->save();
            Log::info('User created: ' . $user->email);
        }

        
    }
}
