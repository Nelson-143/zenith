<?php
namespace app\Http\Controllers;

use app\Models\Budget;
use app\Models\BudgetCategory;
use app\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;



class BudgetController extends Controller
{
    public function index()
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // Fetch data for the budget dashboard
        $growthData = $this->getGrowthData($accountId);
        $expenses = Expense::with('category')
            ->where('account_id', $accountId)
            ->where('user_id', auth()->id())
            ->get();
        $budgets = Budget::with('category')
            ->where('account_id', $accountId)
            ->where('user_id', auth()->id())
            ->get();
        $budgetCategories = BudgetCategory::all();

        return view('budgets.index', compact('budgets', 'budgetCategories', 'expenses', 'growthData'));
    }
    
    private function getGrowthData($accountId)
    {
        // Retrieve budgets for the authenticated user and account
        $budgets = Budget::where('account_id', $accountId)
            ->where('user_id', auth()->id())
            ->orderBy('start_date')
            ->get(['start_date', 'amount']); 

        // Prepare data for the growth chart
        $growthData = [
            'dates' => [],
            'values' => []
        ];

        foreach ($budgets as $budget) {
            $growthData['dates'][] = Carbon::parse($budget->start_date)->format('Y-m-d');
            $growthData['values'][] = $budget->amount;
        }

        return $growthData;
    }

    public function create()
    {
        $budgetCategories = BudgetCategory::all();
        return view('budgets.create', compact('budgetCategories'));
    }
    public function store(Request $request)
    {
        // Add debug statement to check the input data
        \Log::info('Request Data: ', $request->all());
    
        $request->validate([
            'budget_category_id' => 'required|exists:budget_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
           
        ]);
    
        $data = [
            'user_id' => auth()->id(),
            'account_id' => auth()->user()->account_id, // Set the account_id
            'category_id' => $request->budget_category_id,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            
        ];
    
        // Add debug statement to check the prepared data
        \Log::info('Prepared Data: ', $data);
    
        try {
            Budget::create($data);
            return redirect()->route('budgets.index')->with('success', 'Budget added successfully.');
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error saving budget: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to add budget. Please try again.');
        }
    }
    
    
}
