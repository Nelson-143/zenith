<?php
namespace app\Http\Controllers;

use app\Models\Expense;
use app\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure authentication
    }

    /**
     * Display a listing of expenses for the logged-in user's account.
     */
    public function index(Request $request)
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // Start building the query
        $query = Expense::with('category')
            ->where('account_id', $accountId) // Filter by account_id
            ->where('user_id', auth()->id()); // Filter by user_id (optional)

        // Apply filters if provided
        if ($request->has('expense_category_id') && $request->expense_category_id) {
            $query->where('category_id', $request->expense_category_id);
        }

        if ($request->has('date') && $request->date) {
            $query->whereDate('expense_date', $request->date);
        }

        // Paginate the results
        $expenses = $query->paginate(10);

        // Fetch expense categories for the logged-in user's account
        $expenseCategories = ExpenseCategory::where('account_id', $accountId)->get();

        // Calculate total expenses by category
        $expenseTotals = Expense::selectRaw('SUM(amount) as total, category_id')
            ->where('account_id', $accountId)
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $expenseCategoryNames = $expenseTotals->pluck('category.name');
        $expenseTotals = $expenseTotals->pluck('total');

        // Calculate expense trends data
        $expenseTrendsData = Expense::selectRaw('DATE(expense_date) as date, SUM(amount) as total')
            ->where('account_id', $accountId)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('expenses.index', compact(
            'expenses',
            'expenseCategories',
            'expenseCategoryNames',
            'expenseTotals',
            'expenseTrendsData',
            'accountId'
        ));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // Fetch expense categories for the logged-in user's account
        $expenseCategories = ExpenseCategory::where('account_id', $accountId)->get();

        return view('expenses.create', compact('expenseCategories', 'accountId'));
    }

    /**
     * Store a newly created expense in the database.
     */
    public function store(Request $request)
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // Validate the request
        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Handle file upload (if applicable)
        $attachment = $request->file('attachment')
            ? $request->file('attachment')->store('attachments')
            : null;

        // Create the expense
        Expense::create([
            'account_id' => $accountId, // Set the account_id
            'user_id' => auth()->id(), // Set the user_id
            'category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'description' => $request->description,
            'attachment' => $attachment,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }
}