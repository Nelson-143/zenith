<?php
namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class UserSeeder extends Seeder
{
    public function run()
    {
        // Create an account
        $account = Account::create([
            'name' => 'Admin Account', // No need to manually set the ID
        ]);

        // Debugging: Log the created account
        Log::info('Account created:', $account->toArray());

        // Create a user associated with the account
        $user = User::create([
            'uuid' => Str::uuid(), // Optional: Keep UUID for external references
            'username' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'account_id' => $account->id, // Use the account's auto-incrementing ID
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Debugging: Log the created user
        Log::info('User created:', $user->toArray());
    }
}