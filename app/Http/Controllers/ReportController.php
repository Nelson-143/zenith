<?php

namespace app\Http\Controllers;

use app\Models\Report;
use app\Models\Recommendation;
use app\Models\IncomeStatement;
use app\Models\BalanceSheet;
use app\Models\CashFlow;
use app\Models\Purchase;
use app\Models\TaxReport;
use app\Models\Product;
use app\Models\Order;
use app\Models\OrderDetails;
use app\Models\Budget;
use app\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use illuminate\Support\Facades\log;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{


    public function index(Request $request)
    {
       
        $userId = auth()->id();
        $accountId = auth()->user()->account_id;
    
        // Fetch total sales and expenses
        $totalSales = Order::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->sum('total');
    
        $totalExpenses = Expense::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->sum('amount');
    
        // Calculate KPIs
        $grossMargin = $totalSales > 0 ? (($totalSales - $totalExpenses) / $totalSales) * 100 : 0;
        $expenseRatio = $totalSales > 0 ? ($totalExpenses / $totalSales) * 100 : 0;
        $ytdPerformance = $totalSales - $totalExpenses;
    
        // Fetch reports filtered by date (if provided)
        $reports = Report::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('created_at', $date); // Filter by selected date
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Fetch recommendations without account binding and in random order
        $recommendations = Recommendation::where('user_id', $userId)
            ->where('is_read', false)
            ->inRandomOrder() // Fetch recommendations in random order
            ->take(3) // Limit to 3 recommendations
            ->get();
    
        // Fetch financial statements
        $incomeStatement = $this->getIncomeStatement($userId, $accountId);
        $balanceSheet = $this->getBalanceSheet($userId, $accountId, $request);
        $cashFlow = $this->getCashFlow($userId, $accountId);
    
        $taxReport = TaxReport::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->first();
    
        // Fetch data for Business Overview
        $carts = Order::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->where('order_status', '1 - Complete')
            ->sum('total');
    
        $profit = $carts - $totalExpenses;
    
        $reportCount = Report::where('user_id', $userId)
            ->where('account_id', $accountId)
            ->count();
            


            
       // Get the selected date from the request
       $date = $request->input('date', now()->format('Y-m-d'));

       // Fetch the last five days' reports
       $reports = Report::where('created_at', '>=', Carbon::now()->subDays(5))
           ->orderBy('created_at', 'desc')
           ->get();

       // Log the fetched reports for debugging
       Log::info('Fetched Reports:', ['reports' => $reports]);

       
        // Fetch actionable insights
        $actionableInsights = $this->generateActionableInsights($userId, $accountId);
    




        // Calculate stock values
        $totalAvailableStock = Product::where('account_id', $accountId)->sum('quantity');
        $lowStockItems = Product::where('account_id', $accountId)
            ->where('quantity', '<=', 'quantity_alert')
            ->count();
        $outOfStockItems = Product::where('account_id', $accountId)
            ->where('quantity', 0)
            ->count();
        $totalStockValue = Product::where('account_id', $accountId)
            ->sum(DB::raw('quantity * selling_price'));
    
        // Default report type (monthly)
        $reportType = $request->get('report_type', 'monthly');
    
        // Fetch chart data based on the report type
        $chartData = $this->getChartData($reportType, $userId, $accountId);
    
        // Pass data to the view
        return view('reports.index', [
            'chartLabels' => $chartData['labels'],
            'chartData' => $chartData['data'],
            'reportType' => $reportType,
            'reportCount' => $reportCount,
            'carts' => $carts,
            'profit'=> $profit,
            'totalExpenses' => $totalExpenses,
            'reports' => $reports,
            'recommendations' => $recommendations, // Recommendations fetched without account binding
            'incomeStatement' => $incomeStatement,
            'balanceSheet' => $balanceSheet,
            'cashFlow' => $cashFlow,
            'taxReport' => $taxReport,
            'actionableInsights' => $actionableInsights,
            'grossMargin' => $grossMargin,
            'expenseRatio' => $expenseRatio,
            'ytdPerformance' => $ytdPerformance,
            'totalAvailableStock' => $totalAvailableStock,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'totalStockValue' => $totalStockValue,
            'date' => $date,
        ]);
    }

public function calculateBalanceSheet(Request $request)
{
    $userId = auth()->id();
    $accountId = auth()->user()->account_id;

    // Fetch balance sheet data based on user input
    $balanceSheet = $this->getBalanceSheet($userId, $accountId,$request);

    // Pass data back to the view
    return view('reports.index', compact('balanceSheet'));
}

private function getBalanceSheet($userId, $accountId, Request $request)
{
    // Initialize empty balance sheet
    $balanceSheet = [
        'assets' => [],
        'liabilities' => [],
    ];

    // Get user input from the request (if any)
    $assets = $request->input('assets', []);
    $liabilities = $request->input('liabilities', []);

    // Populate assets from user input
    foreach ($assets as $asset) {
        if (!empty($asset['name']) && !empty($asset['amount'])) {
            $balanceSheet['assets'][$asset['name']] = (float) $asset['amount'];
        }
    }

    // Populate liabilities from user input
    foreach ($liabilities as $liability) {
        if (!empty($liability['name']) && !empty($liability['amount'])) {
            $balanceSheet['liabilities'][$liability['name']] = (float) $liability['amount'];
        }
    }

    // Calculate totals
    $totalAssets = array_sum($balanceSheet['assets']);
    $totalLiabilities = array_sum($balanceSheet['liabilities']);
    $equity = $totalAssets - $totalLiabilities;

    $balanceSheet['totalAssets'] = $totalAssets;
    $balanceSheet['totalLiabilities'] = $totalLiabilities;
    $balanceSheet['equity'] = $equity;

    return $balanceSheet;
}
private function getIncomeStatement($userId, $accountId)
{
    $revenue = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('total');

    // Calculate COGS using order details
    $orderDetails = OrderDetails::whereHas('order', function ($query) use ($userId, $accountId) {
            $query->where('user_id', $userId)
                ->where('account_id', $accountId);
        })
        ->get();

    $cogs = 0;
    foreach ($orderDetails as $detail) {
        $product = Product::find($detail->product_id);
        if ($product) {
            $cogs += $detail->quantity * $product->buying_price;
        } else {
            Log::error("Product not found for order detail " . $detail->id);
        }
    }

    $operatingExpenses = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('amount');

    $grossProfit = $revenue - $cogs;
    $netIncome = $grossProfit - $operatingExpenses;

    return [
        'revenue' => $revenue,
        'cogs' => $cogs,
        'grossProfit' => $grossProfit,
        'expenses' => $operatingExpenses,
        'netIncome' => $netIncome,
    ];
}



private function getCashFlow($userId, $accountId)
{
    $revenue = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('total');

    $operatingExpenses = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('amount');

    $cogs = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
       // ->where('category', 'COGS')
        ->sum('amount');

    return [
        'inflows' => [
            'sales' => $revenue,
        ],
        'outflows' => [
            'expenses' => $operatingExpenses + $cogs,
        ],
    ];
}


    private function generateActionableInsights($userId, $accountId)
{
    $insights = [];

    // Fetch sales and expenses
    $totalSales = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('total');

    $totalExpenses = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->sum('amount');

    $profit = $totalSales - $totalExpenses;
    $profitMargin = $totalSales > 0 ? ($profit / $totalSales) * 100 : 0;

    // Insight 1: High expenses
    if ($totalExpenses > ($totalSales * 0.8)) {
        $insights[] = [
            'message' => "Your expenses are high compared to your sales.",
            'details' => "Total expenses: Tsh " . number_format($totalExpenses, 2) . 
                         " | Total sales: Tsh " . number_format($totalSales, 2) .
                         " | Consider reviewing your spending in high-cost areas.",
            'status' => 'danger' // Red for high expenses
        ];
    }

    // Insight 2: Low sales
    if ($totalSales < 1000) {
        $insights[] = [
            'message' => "Sales are below the expected threshold.",
            'details' => "Total sales: Tsh " . number_format($totalSales, 2) .
                         " | Consider increasing marketing efforts or launching promotions.",
            'status' => 'danger' // Red for low sales
        ];
    }

    // Insight 3: Low profit margin
    if ($profitMargin < 10) {
        $insights[] = [
            'message' => "Your profit margin is low.",
            'details' => "Profit margin: " . number_format($profitMargin, 2) . "%" .
                         " | Review your pricing strategy or reduce operational costs.",
            'status' => 'danger' // Red for low profit margin
        ];
    }

    // Insight 4: High profit margin
    if ($profitMargin > 30) {
        $insights[] = [
            'message' => "Your profit margin is high.",
            'details' => "Profit margin: " . number_format($profitMargin, 2) . "%" .
                         " | Consider reinvesting in your business or expanding operations.",
            'status' => 'success' // Blue for normal performance
        ];
    }

    // Insight 5: No recent sales
    $lastSaleDate = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->orderBy('created_at', 'desc')
        ->value('created_at');

    if ($lastSaleDate && now()->diffInDays($lastSaleDate) > 7) {
        $insights[] = [
            'message' => "No recent sales.",
            'details' => "Last sale was on " . $lastSaleDate->format('Y-m-d') .
                         " | Consider launching a promotion or discount to boost sales.",
            'status' => 'danger' // Red for no recent sales
        ];
    }

    // Insight 6: High expenses in a specific category
    $expenseCategories = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
        //->groupBy('category')
        //->selectRaw('category, sum(amount) as total')
        ->get();

    foreach ($expenseCategories as $category) {
        if ($category->total > ($totalExpenses * 0.5)) {
            $insights[] = [
                'message' => "High expenses in the '{$category->category}' category.",
                'details' => "Total expenses in this category: Tsh " . number_format($category->total, 2) .
                             " | Review and optimize spending in this area.",
                'status' => 'danger' // Red for high category expenses
            ];
        }
    }

    // Insight 7: Budget vs. Actual
    $budgetVsActual = Budget::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->get();

    foreach ($budgetVsActual as $budget) {
        $actual = Expense::where('user_id', $userId)
            ->where('account_id', $accountId)
            //->where('category', $budget->category)
            ->sum('amount');

        if ($actual > $budget->amount) {
         
        }
    }

    // Insight 8: Increasing trend in expenses
    $previousMonthExpenses = Expense::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->sum('amount');

    if ($previousMonthExpenses < $totalExpenses) {
        $insights[] = [
            'message' => "Your expenses have increased compared to last month.",
            'details' => "Current month expenses: Tsh " . number_format($totalExpenses, 2) .
                         " | Last month expenses: Tsh " . number_format($previousMonthExpenses, 2) .
                         " | Consider identifying areas of unnecessary spending.",
            'status' => 'danger' // Red for increasing expenses
        ];
    }

    // Insight 9: Sales growth trend
    $previousMonthSales = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->sum('total');

    if ($previousMonthSales < $totalSales) {
        $insights[] = [
            'message' => "Your sales have increased compared to last month.",
            'details' => "Current month sales: Tsh " . number_format($totalSales, 2) .
                         " | Last month sales: Tsh " . number_format($previousMonthSales, 2) .
                         " | Keep up the good work and consider further marketing efforts.",
            'status' => 'success' // Blue for normal performance
        ];
    }

    // Insight 10: Seasonal sales patterns
    $seasonalSales = Order::where('user_id', $userId)
        ->where('account_id', $accountId)
        ->whereMonth('created_at', now()->month)
        ->sum('total');

    if ($seasonalSales < 500) {
        $insights[] = [
            'message' => "Sales are lower than expected for this season.",
            'details' => "Current seasonal sales: Tsh " . number_format($seasonalSales, 2) .
                         " | Consider seasonal promotions or discounts to boost sales.",
            'status' => 'danger' // Red for low seasonal sales
        ];
    }

    return $insights;
}
    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:daily,weekly,monthly,yearly',
        ]);

        $userId = auth()->id();
        $accountId = auth()->user()->account_id;

        // Calculate the report data based on the report type
        $reportData = $this->calculateReportData($request->type, $userId, $accountId);

        // Create the report
        $report = Report::create([
            'user_id' => $userId,
            'account_id' => $accountId,
            'type' => $request->type,
            'data' => $reportData,
        ]);

        return redirect()->route('reports.index')->with('success', 'Report generated successfully.');
    }

    private function calculateReportData($type, $userId, $accountId)
    {
        $sales = 0;
        $expenses = 0;
        $now = now();

        switch ($type) {
            case 'daily':
                $sales = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereDate('created_at', $now->toDateString())
                    ->sum('amount');

                $expenses = Expense::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereDate('created_at', $now->toDateString())
                    ->sum('amount');
                break;

            case 'weekly':
                $sales = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()])
                    ->sum('amount');

                $expenses = Expense::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()])
                    ->sum('amount');
                break;

            case 'monthly':
                $sales = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->sum('amount');

                $expenses = Expense::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->sum('amount');
                break;

            case 'yearly':
                $sales = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereYear('created_at', $now->year)
                    ->sum('amount');

                $expenses = Expense::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereYear('created_at', $now->year)
                    ->sum('amount');
                break;

            default:
                return [
                    'sales' => 0,
                    'expenses' => 0,
                    'profit' => 0,
                ];
        }

        $profit = $sales - $expenses;

        return [
            'sales' => $sales,
            'expenses' => $expenses,
            'profit' => $profit,
        ];
    }

    public function markRecommendationRead($id)
    {
        $recommendation = Recommendation::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        $recommendation->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Recommendation marked as read.');
    }



private function getChartData($reportType, $userId, $accountId)
{
    $now = now();

    switch ($reportType) {
        case 'daily':
            $labels = [];
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i)->format('Y-m-d');
                $labels[] = $date;
                $data[] = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereDate('created_at', $date)
                    ->sum('total');
            }
            break;

        case 'weekly':
            $labels = [];
            $data = [];
            for ($i = 3; $i >= 0; $i--) {
                $startOfWeek = $now->copy()->subWeeks($i)->startOfWeek()->format('Y-m-d');
                $endOfWeek = $now->copy()->subWeeks($i)->endOfWeek()->format('Y-m-d');
                $labels[] = "Week " . ($i + 1);
                $data[] = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                    ->sum('total');
            }
            break;

        case 'monthly':
            $labels = [];
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i)->format('M Y');
                $labels[] = $month;
                $data[] = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereYear('created_at', $now->copy()->subMonths($i)->year)
                    ->whereMonth('created_at', $now->copy()->subMonths($i)->month)
                    ->sum('total');
            }
            break;

        case 'yearly':
            $labels = [];
            $data = [];
            for ($i = 4; $i >= 0; $i--) {
                $year = $now->copy()->subYears($i)->format('Y');
                $labels[] = $year;
                $data[] = Order::where('user_id', $userId)
                    ->where('account_id', $accountId)
                    ->whereYear('created_at', $year)
                    ->sum('total');
            }
            break;

        default:
            $labels = [];
            $data = [];
            break;
    }

    return [
        'labels' => $labels,
        'data' => $data,
    ];
}

public function generateDailyReport()
{
    $date = now()->format('Y-m-d');

    // Fetch data from the orders table
    $orders = Order::whereDate('order_date', $date)->get();
    $totalSales = $orders->sum('total');
    $totalProductsSold = $orders->sum('total_products');

    // Fetch data from the expenses table
    $expenses = Expense::whereDate('expense_date', $date)->get();
    $totalExpenses = $expenses->sum('amount');

    // Calculate profit
    $profit = $totalSales - $totalExpenses;

    // Prepare the report data
    $reportData = [
        'sales' => $totalSales,
        'expenses' => $totalExpenses,
        'profit' => $profit,
        'products_sold' => $totalProductsSold,
    ];

    // Save the report
    Report::create([
        'user_id' => auth()->id(),
        'type' => 'daily',
        'data' => $reportData,
        'account_id' => auth()->user()->account_id,
    ]);

    return redirect()->route('reports.index')->with('success', 'Daily report generated successfully.');
}


}