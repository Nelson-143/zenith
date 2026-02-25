<?php
namespace app\Http\Controllers;

use app\Models\Liability;
use app\Models\LiabilityPayment;
use app\Models\Order;
use app\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LiabilityController extends Controller
{
    protected function getAccountId()
    {
        return auth()->user()->account_id;
    }

    public function index()
    {
        $liabilities = Liability::where('account_id', $this->getAccountId())->get();
        
        $metrics = $this->calculateFinancialMetrics();
        $riskAnalysis = $this->analyzeFinancialRisk();
    
        // Calculate repayment progress
        $totalDebt = $liabilities->sum('remaining_balance');
        $totalPaid = LiabilityPayment::where('account_id', $this->getAccountId())->sum('amount_paid');
        $progress = $totalDebt > 0 ? ($totalPaid / $totalDebt) * 100 : 0;
    
        return view('liabilities.index', compact('liabilities', 'metrics', 'riskAnalysis', 'progress'));
    }

    private function calculateFinancialMetrics()
    {
        $totalLiabilities = Liability::where('account_id', $this->getAccountId())
            ->sum('remaining_balance');
        
        $monthlyRevenue = Order::where('account_id', $this->getAccountId())
            ->whereMonth('created_at', now()->month)
            ->sum('total');
            
        $monthlyExpenses = Expense::where('account_id', $this->getAccountId())
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
            
        $cashFlow = $monthlyRevenue - $monthlyExpenses;
        $debtToIncome = $monthlyRevenue > 0 ? ($totalLiabilities / $monthlyRevenue) * 100 : 0;

        return [
            'total_liabilities' => $totalLiabilities,
            'monthly_revenue' => $monthlyRevenue,
            'monthly_expenses' => $monthlyExpenses,
            'cash_flow' => $cashFlow,
            'debt_to_income' => $debtToIncome
        ];
    }

    private function analyzeFinancialRisk()
    {
        $metrics = $this->calculateFinancialMetrics();
        
        $riskLevel = 'Low';
        if ($metrics['debt_to_income'] > 50) {
            $riskLevel = 'High';
        } elseif ($metrics['debt_to_income'] > 30) {
            $riskLevel = 'Moderate';
        }
    
        $recommendations = [];
        if ($metrics['cash_flow'] < 0) {
            $recommendations[] = 'Consider reducing operational costs or increasing sales.';
        }
    
        // Historical sales data analysis
        $historicalSales = Order::where('account_id', $this->getAccountId())
            ->where('order_date', '>=', now()->subYear())
            ->sum('total');
    
        if ($historicalSales < 10000) { // Example threshold
            $recommendations[] = 'Your historical sales are low. Consider improving sales strategies.';
        }
    
        return [
            'risk_level' => $riskLevel,
            'recommendations' => $recommendations,
            'historical_sales' => $historicalSales
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:high,medium,low',
            'type' => 'required|in:formal,informal'
        ]);

        Liability::create([
            ...$validated,
            'remaining_balance' => $validated['amount'],
            'account_id' => $this->getAccountId()
        ]);

        return redirect()->route('liabilities.index')->with('success', 'Liability added successfully');
    }

  public function makePayment(Request $request, Liability $liability)
{
    Log::info('Making payment for liability ' . $liability->id);

    $validated = $request->validate([
        'amount' => 'required|numeric|min:0.01|max:' . $liability->remaining_balance
    ]);

    Log::info('Validation successful. Amount: ' . $validated['amount']);

    // Check if the liability is already fully paid
    if ($liability->remaining_balance <= 0) {
        Log::warning('Liability ' . $liability->id . ' is already fully paid.');
        return redirect()->back()->with('error', 'This liability has already been fully paid.');
    }

    Log::info('Recording payment for liability ' . $liability->id);

    // Record the payment
    DB::transaction(function () use ($liability, $validated) {
        Log::info('Creating new liability payment');
        LiabilityPayment::create([
            'amount_paid' => $validated['amount'], // Ensure this matches your model's field
            'liability_id' => $liability->id,
            'paid_at' => now(),
            'account_id' => $this->getAccountId()
        ]);

        Log::info('Updating liability ' . $liability->id);
        // Update liability
        $liability->remaining_balance -= $validated['amount'];
        if ($liability->remaining_balance <= 0) {
            $liability->status = 'paid'; // Mark as paid if fully paid
        }
        $liability->save();
    });

    Log::info('Payment recorded successfully for liability ' . $liability->id);
    return redirect()->back()->with('success', 'Payment recorded successfully.');
}

    public function destroy($id)
{
    // Find the liability by ID and ensure it belongs to the authenticated user's account
    $liability = Liability::where('id', $id)
        ->where('account_id', $this->getAccountId())
        ->firstOrFail();

    // Delete the liability
    $liability->delete();

    return redirect()->route('liabilities.index')->with('success', 'Liability deleted successfully.');
}
    public function paymentHistory(Liability $liability)
    {
        $payments = LiabilityPayment::where('liability_id', $liability->id)
            ->where('account_id', $this->getAccountId())
            ->get();

        return view('liabilities.history', compact('liability', 'payments'));
    }

    public function loanCalculator()
    {
        return view('liabilities.calculator');
    }
    public function calculateLoan(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'term' => 'required|integer|min:1' // Term in years
        ]);

        $monthlyRate = $validated['interest_rate'] / 100 / 12; // Convert annual interest rate to monthly
        $termMonths = $validated['term'] * 12; // Convert years to months

        // Calculate monthly payment using the formula for an amortizing loan
        $monthlyPayment = ($validated['amount'] * $monthlyRate) / (1 - pow(1 + $monthlyRate, -$termMonths));

        // Affordability check
        $metrics = $this->calculateFinancialMetrics();
        $affordable = $monthlyPayment <= ($metrics['cash_flow'] * 0.3); // Check if payment is less than 30% of cash flow

        return view('liabilities.calculator-result', [
            'monthly_payment' => $monthlyPayment,
            'total_interest' => ($monthlyPayment * $termMonths) - $validated['amount'],
            'affordable' => $affordable,
            'request' => $validated
        ]);
    }

    public function consolidateDebts(Request $request)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:1',
        'interest_rate' => 'required|numeric|min:0',
        'term' => 'required|integer|min:1' // Term in years
    ]);

    // Fetch all liabilities
    $liabilities = Liability::where('account_id', $this->getAccountId())->get();

    // Calculate total remaining balance
    $totalRemaining = $liabilities->sum('remaining_balance');

    // Create a new consolidated liability
    Liability::create([
        'name' => 'Consolidated Debt',
        'amount' => $totalRemaining,
        'remaining_balance' => $totalRemaining,
        'interest_rate' => $validated['interest_rate'],
        'due_date' => now()->addYears($validated['term']),
        'priority' => 'high',
        'type' => 'formal',
        'account_id' => $this->getAccountId()
    ]);

    // Optionally, delete old liabilities or mark them as consolidated
    foreach ($liabilities as $liability) {
        $liability->delete(); // Or set a flag to mark as consolidated
    }

    return redirect()->route('liabilities.index')->with('success', 'Debts consolidated successfully.');
}
}