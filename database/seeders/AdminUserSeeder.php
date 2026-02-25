<?php

namespace Database\Seeders; // Ensure this namespace is included

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Don't forget to import Str

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'eggplant@txt.com'], // Prevent duplicates
            [
                'name' => 'EggsRsm',
                'email' => 'eggplant@txt.com',
                'password' => Hash::make('Xml@2023*'), // Change this later
                'role' => 'super_admin', // Ensure your User model supports roles
                'email_verified_at' => now(),
                'created_at' => now(),
                'uuid' => Str::uuid(),
            ]
        );
    }
}
