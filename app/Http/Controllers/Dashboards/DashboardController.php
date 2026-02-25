<?php

namespace app\Http\Controllers\Dashboards;

use app\Http\Controllers\Controller;
use app\Models\Category;
use app\Models\Order;
use app\Models\Product;
use app\Models\Purchase;
use app\Models\Quotation;
use app\Models\Customer;
use app\Models\Debt;
use app\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    
  public function index(Request $request) {
    $accountId = auth()->user()->account_id;

    // Existing logic for counts
    $ordersCount = Order::where('account_id', $accountId)->count();
    $productsCount = Product::where('account_id', $accountId)->count();
    $purchasesCount = Purchase::where('account_id', $accountId)->count();
    $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->where('account_id', $accountId)->count();
    $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->where('account_id', $accountId)->count();
    $todayQuotations = Quotation::whereDate('created_at', today()->format('Y-m-d'))->where('account_id', $accountId)->count();
    $todayOrders = Order::whereDate('created_at', today()->format('Y-m-d'))->where('account_id', $accountId)->count();
    $categoriesCount = Category::where('account_id', $accountId)->count();
    $quotationsCount = Quotation::where('account_id', $accountId)->count();

    // New logic for customers, debt, branch, carts, and growth rates
    $customersCount = Customer::where('account_id', $accountId)->count(); // Count customers

    $debts = Debt::where('account_id', $accountId)->get();
    $totalValueOfDebt = $debts->sum(function ($debt) {
        return $debt->amount - $debt->amount_paid; // Remaining balance for each debt
    });

    // Get the selected date if provided, otherwise use today
    $selectedDate = $request->input('selected_date') ? Carbon::parse($request->input('selected_date')) : Carbon::today();
    $today = Carbon::today();
    
    // Check if selected date is in the future
    $isFutureDate = $selectedDate->isAfter($today);
    
    // Flag for displaying messages
    $hasNoSales = false;
    $dailySalesMessage = null;

    // Calculate selected date sales
    if ($isFutureDate) {
        $dailySales = 0;
        $dailySalesMessage = "The future is good champion😎";
    } else {
        $dailySales = Order::where('account_id', $accountId)
            ->where('order_status', ' 1 - Complete')
            ->whereDate('created_at', $selectedDate->format('Y-m-d'))
            ->sum('total');
            
        if ($dailySales <= 0) {
            $hasNoSales = true;
            $dailySalesMessage = "No sales for this day";
        }
    }

    // Calculate total sales based on the selected period
    $period = $request->input('period', 'daily'); // Default to daily if not set

    switch ($period) {
        case 'daily':
            // Use the selected date for daily sales if provided
            $totalSales = $dailySales;
            break;
        case 'weekly':
            $totalSales = Order::where('account_id', $accountId)
                ->where('order_status', ' 1 - Complete')
                ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                ->sum('total');
            break;
        case 'monthly':
            $totalSales = Order::where('account_id', $accountId)
                ->where('order_status', ' 1 - Complete')
                ->whereBetween('created_at', [Carbon::now()->subMonths(1), Carbon::now()])
                ->sum('total');
            break;
        case 'yearly':
            $totalSales = Order::where('account_id', $accountId)
                ->where('order_status', ' 1 - Complete')
                ->whereBetween('created_at', [Carbon::now()->subYears(1), Carbon::now()])
                ->sum('total');
            break;
        default:
            $totalSales = 0; // Fallback
            break;
    }

    // Calculate weekly sales
    $weeklySales = Order::where('account_id', $accountId)
        ->where('order_status', ' 1 - Complete')
        ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
        ->sum('total');

    // Calculate monthly sales
    $monthlySales = Order::where('account_id', $accountId)
        ->where('order_status', ' 1 - Complete')
        ->whereBetween('created_at', [Carbon::now()->subMonths(1), Carbon::now()])
        ->sum('total');

    // Assuming you have a way to calculate branches
    $branchCount = Branch::where('account_id', $accountId)->count(); // Count all branches

    // Calculate growth rates
    $customerGrowth = $this->calculateGrowthRate(Customer::class, 'created_at', $accountId);
    $debtChange = $this->calculateGrowthRate(Debt::class, 'created_at', $accountId, 'amount'); // Adjust as needed
    $branchChange = 0; // Replace with actual logic for branch change
    $salesGrowth = $this->calculateGrowthRate(Order::class, 'created_at', $accountId, 'total');

    // Get daily sales data for the last 14 days for the line chart
    $dailySalesData = $this->getDailySalesData($accountId);
    $days = $dailySalesData['days'];
    $salesValues = $dailySalesData['salesValues'];
    $dailyGrowthRates = $dailySalesData['growthRates'];

    // Calculate out-of-stock products
    $outOfStockProducts = Product::where('quantity', '<=', 0)->where('account_id', $accountId)->count();
    $totalProducts = Product::where('account_id', $accountId)->count();
    $inStockProducts = $totalProducts - $outOfStockProducts;

    // Prepare data for the pie chart
    $pieChartData = [
        'labels' => ['In Stock', 'Out of Stock'],
        'data' => [$inStockProducts, $outOfStockProducts],
    ];
    $motivations = [
        'You got this! 💪',
        'Keep pushing! 🚀',
        'Stay focused! 🎯',
        'You are doing great! 🌟',
        'Keep going! 🏃‍♂️',
        'Believe in yourself! 🙌',
        'Stay motivated! 🔥',
        'You are strong! 💪',
        'Keep shining! ✨',
        'Never give up! 🚀',
        'You are a Champion! 😎',
        'Stay positive! 😊',
        'Dream big! 🌈',
        'You can do it! 🙌',
        'Stay awesome! 🦸‍♂️',
        'Go for it! 🚀',
        'Stay courageous! 🦁',
        'Reach for the stars! 🌟',
        'You are unstoppable! 🏆',
        'Stay determined! 💪',
        'Stay inspired! ✨',
        'Keep the faith! 🙏',
        'Stay strong! 💪',
        'You are a warrior! 🛡️',
        'Keep thriving! 🌱',
        'You are amazing! 🎉',
        'Stay resilient! 🧗‍♂️',
        'Keep dreaming! 🌟',
        'Stay fierce! 🐯',
        'You are capable! 💪'
    ];

    $motivation = $motivations[array_rand($motivations)];

    return view('dashboard', [
        'products' => $productsCount,
        'orders' => $ordersCount,
        'purchases' => $purchasesCount,
        'todayPurchases' => $todayPurchases,
        'todayProducts' => $todayProducts,
        'todayQuotations' => $todayQuotations,
        'todayOrders' => $todayOrders,
        'categories' => $categoriesCount,
        'quotations' => $quotationsCount,
        'customers' => $customersCount,
        'debt' => $totalValueOfDebt, // Pass the total value of debt to the view
        'branch' => $branchCount,
        'carts' => $totalSales, // Assuming carts represent total sales
        'total' => $totalSales,
        'daily' => $dailySales,
        'weekly' => $weeklySales,
        'monthly' => $monthlySales,
        'customerGrowth' => $customerGrowth,
        'debtChange' => $debtChange,
        'branchChange' => $branchChange,
        'salesGrowth' => $salesGrowth,
        'days' => $days, // Pass the days for the x-axis of the line chart
        'salesValues' => $salesValues, // Pass daily sales values
        'dailyGrowthRates' => $dailyGrowthRates, // Pass daily growth rates
        'pieChartData' => $pieChartData,
        'motivation' => $motivation,
        'selectedDate' => $selectedDate->format('Y-m-d'),
        'isFutureDate' => $isFutureDate,
        'hasNoSales' => $hasNoSales,
        'dailySalesMessage' => $dailySalesMessage,
    ]);
}

/**
 * Calculate growth rate between two periods
 */
private function calculateGrowthRate($model, $dateColumn, $accountId, $valueColumn = null)
{
    $now = Carbon::now();
    $previousPeriod = Carbon::now()->subDays(7); // Adjust the period as needed (e.g., weekly, monthly)

    // Calculate the current value for the specified period
    $currentValue = $model::where('account_id', $accountId)
        ->whereBetween($dateColumn, [$previousPeriod, $now])
        ->when($valueColumn, function ($query) use ($valueColumn) {
            return $query->sum($valueColumn); // Sum the monetary value (e.g., sales)
        }, function ($query) {
            return $query->count(); // Count the number of records
        });

    // Calculate the previous value for the previous period
    $previousValue = $model::where('account_id', $accountId)
        ->whereBetween($dateColumn, [$previousPeriod->copy()->subDays(7), $previousPeriod])
        ->when($valueColumn, function ($query) use ($valueColumn) {
            return $query->sum($valueColumn); // Sum the monetary value (e.g., sales)
        }, function ($query) {
            return $query->count(); // Count the number of records
        });

    // Avoid division by zero and handle edge cases
    if ($previousValue == 0) {
        return 0; // No growth if there's no previous value
    }

    // Calculate the growth rate as a percentage
    $growthRate = (($currentValue - $previousValue) / $previousValue) * 100;

    // Cap the growth rate at 100% to avoid unrealistic values
    $growthRate = min($growthRate, 100);

    return number_format($growthRate, 2); // Return the growth rate as a percentage (e.g., 12.34)
}

/**
 * Get daily sales data for the last 14 days
 */
public function getDailySalesData($accountId)
{
    $days = [];
    $salesValues = [];
    $growthRates = [];
    
    // Get data for the last 14 days
    for ($i = 13; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $previousDate = Carbon::now()->subDays($i + 1);
        
        // Get sales for current day
        $currentDaySales = Order::where('account_id', $accountId)
            ->where('order_status', ' 1 - Complete')
            ->whereDate('created_at', $date->format('Y-m-d'))
            ->sum('total');
        
        // Get sales for previous day to calculate growth
        $previousDaySales = Order::where('account_id', $accountId)
            ->where('order_status', ' 1 - Complete')
            ->whereDate('created_at', $previousDate->format('Y-m-d'))
            ->sum('total');
        
        // Calculate growth rate
        $growthRate = 0;
        if ($previousDaySales > 0) {
            $growthRate = (($currentDaySales - $previousDaySales) / $previousDaySales) * 100;
            // Cap growth rate to avoid extreme values
            $growthRate = min(max($growthRate, -100), 100);
        } elseif ($currentDaySales > 0) {
            // If previous day had no sales but current day does, show positive growth
            $growthRate = 100;
        }
        
        // Store formatted data
        $days[] = $date->format('M d');
        $salesValues[] = $currentDaySales;
        $growthRates[] = number_format($growthRate, 2);
    }
    
    return [
        'days' => $days,
        'salesValues' => $salesValues,
        'growthRates' => $growthRates
    ];
}

}