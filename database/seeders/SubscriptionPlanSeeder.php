<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;
use App\Models\Account;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        // Ensure there is at least one account to associate with subscriptions
        $account = Account::first(); // Adjust logic if multiple accounts are needed
        
        if (!$account) {
            $this->command->info('No accounts found. Please create at least one account before running the seeder.');
            return;
        }

        $plans = [
            [
                'id' => Str::uuid(),
                'name' => 'Simple Plan',
                'price' => 15000,
                'max_branches' => 1,
                'max_users' => 1,
                'features' => json_encode([
                    'Basic inventory tracking',
                    'Basic invoicing',
                    'Limited FinAssist usage',
                    'No data export',
                    'Maximum 5 customers'
                ]),
                'account_id' => $account->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Starter Champion',
                'price' => 25000,
                'max_branches' => 2,
                'max_users' => 3,
                'features' => json_encode([
                    'Increased branches & users',
                    'Advanced inventory features',
                    'CSV & Excel export',
                    'Unlimited customers'
                ]),
                'account_id' => $account->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Business Champion',
                'price' => 35000,
                'max_branches' => 10,
                'max_users' => 20,
                'features' => json_encode([
                    'More branches & users',
                    'AI-powered insights',
                    'More FinAssist capabilities',
                    'API access',
                    'Advanced analytics'
                ]),
                'account_id' => $account->id,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Digital Dominator',
                'price' => 45000,
                'max_branches' => null, // Unlimited
                'max_users' => null, // Unlimited
                'features' => json_encode([
                    'Full access to all features',
                    'Sell to five customers at once',
                    'Highest AI & automation levels',
                    'WhatsApp/SMS invoices',
                ]),
                'account_id' => $account->id,
            ],
        ];

        foreach ($plans as $plan) {
            Subscription::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
