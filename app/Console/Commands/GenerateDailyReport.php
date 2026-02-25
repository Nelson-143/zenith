<?php
namespace app\Console\Commands;

use Illuminate\Console\Command;
use app\Models\Order;
use app\Models\Expense;
use app\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateDailyReport extends Command
{
    protected $signature = 'reports:generate-daily';
    protected $description = 'Generate a daily report for sales, expenses, and profit.';

    public function handle()
    {
        $date = Carbon::yesterday()->format('Y-m-d'); // Generate report for yesterday

        // Fetch data from the orders table
        $orders = Order::whereDate('order_date', $date)->get();
        $totalSales = $orders->sum('total');
        $totalProductsSold = $orders->sum('total_products');

        // Log orders data
        Log::info('Orders Data:', ['orders' => $orders, 'totalSales' => $totalSales, 'totalProductsSold' => $totalProductsSold]);

        // Fetch data from the expenses table
        $expenses = Expense::whereDate('expense_date', $date)->get();
        $totalExpenses = $expenses->sum('amount');

        // Log expenses data
        Log::info('Expenses Data:', ['expenses' => $expenses, 'totalExpenses' => $totalExpenses]);

        // Calculate profit
        $profit = $totalSales - $totalExpenses;

        // Prepare the report data
        $reportData = [
            'sales' => $totalSales,
            'expenses' => $totalExpenses,
            'profit' => $profit,
            'products_sold' => $totalProductsSold,
        ];

        // Log report data
        Log::info('Report Data:', ['reportData' => $reportData]);

        // Save the report
        $report = Report::create([
            'user_id' => 1, // Replace with dynamic user ID if needed
            'type' => 'daily',
            'data' => $reportData,
            'account_id' => 1, // Replace with dynamic account ID if needed
        ]);

        // Log the created report
        Log::info('Report Created:', ['report' => $report]);

        $this->info("Daily report for {$date} generated successfully.");
    }
}
