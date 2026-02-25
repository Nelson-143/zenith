<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an account
        Account::create([
            'id' => \Illuminate\Support\Str::uuid(), // Assuming you are using UUIDs
            'name' => 'Main Account',
            // Add any other fields that are required
        ]);
    }
}