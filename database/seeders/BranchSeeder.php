<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a branch
        Branch::create([
            'name' => 'Main Branch',
            // Add any other fields that are required
        ]);
    }
}