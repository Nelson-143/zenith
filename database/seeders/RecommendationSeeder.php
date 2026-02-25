<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recommendation;
use App\Models\User;
use App\Models\Account;

class RecommendationSeeder extends Seeder
{
    public function run()
    {
        // Ensure at least one user and account exist
        $user = User::first();
     

        if (!$user) {
            $this->command->info('No users or accounts found. Please create at least one user and account before running the seeder.');
            return;
        }

        $recommendations = [
            ['Track your inventory to avoid wasting money on unsold items.', 'high'],
            ['Check your cash flow every week to avoid running out of money.', 'medium'],
            ['Change your prices based on what customers are buying and the time of year.', 'high'],
            ['Find out which products make the most profit and focus on selling them.', 'high'],
            ['Keep extra stock of popular items so you don’t run out.', 'medium'],
            ['Talk to your suppliers to get better prices for the things you buy.', 'medium'],
            ['Sell slow-moving items at a discount to clear them out.', 'low'],
            ['Review your financial reports every month to plan better.', 'high'],
            ['Offer discounts to customers who pay early to improve your cash flow.', 'medium'],
          
            ['Cut unnecessary costs to save money.', 'high'],
            ['Reward loyal customers with discounts or special offers.', 'medium'],
            ['Study what customers buy to decide what to stock.', 'high'],
            ['Let customers pay in different ways (e.g., mobile money, cards).', 'medium'],
            ['Stock more items that sell well during certain times of the year.', 'medium'],
            ['Check what your competitors are doing to stay ahead.', 'high'],
            ['Ask customers for feedback to improve your service.', 'medium'],
            ['Add new products to attract more customers.', 'high'],
            ['Train your staff to suggest additional items to customers.', 'medium'],
            ['Use social media to reach more people and grow your business.', 'high'],
            ['Send reminders to customers who haven’t paid their bills.', 'medium'],
            ['Understand your customers’ age, location, and preferences to market better.', 'high'],
            ['Use energy-saving equipment to reduce electricity bills.', 'medium'],
            ['Save some money for emergencies or unexpected costs.', 'high'],
            ['Update your business plan regularly to stay on track.', 'high'],
         
            ['Offer subscriptions for regular income (e.g., monthly deliveries).', 'medium'],
            ['Work with other businesses to promote each other’s products.', 'medium'],
            ['Give rewards to customers who keep coming back.', 'medium'],
            ['Make your supply chain faster and cheaper.', 'high'],
         
            ['Train your employees regularly to improve their skills.', 'medium'],
            ['Group customers by their preferences to market to them better.', 'high'],
            ['Stay updated on industry trends to find new opportunities.', 'high'],
            ['Use less packaging to save money and help the environment.', 'medium'],
            ['Offer free delivery for large orders to encourage bigger purchases.', 'medium'],
            ['Test different website designs to see which one works best.', 'high'],
            ['Check your expenses regularly to find ways to save money.', 'high'],
            ['Encourage customers to refer friends by giving them rewards.', 'medium'],
            ['Protect your business and customer data from hackers.', 'high'],
           
            ['Sell your products in new areas to grow your business.', 'high'],
            ['Run short-time offers to create urgency and boost sales.', 'medium'],
            ['Show customer reviews to build trust with new buyers.', 'medium'],
           
            ['Update your product list regularly to keep it fresh.', 'medium'],
           
            ['Let customers pay in installments to make it easier for them.', 'medium'],
            ['Analyze your business strengths, weaknesses, opportunities, and threats regularly.', 'high'],
        ];

        foreach ($recommendations as $recommendation) {
            Recommendation::create([
                'user_id' => $user->id,
                
                'recommendation' => $recommendation[0],
                'priority' => $recommendation[1],
                'is_read' => false,
            ]);
        }

        $this->command->info('Successfully seeded recommendations!');
    }
}