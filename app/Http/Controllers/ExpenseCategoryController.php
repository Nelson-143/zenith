<?php
namespace app\Http\Controllers;

use app\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function store(Request $request)
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;
        
        $request->validate([
            'name' => 'required|unique:expense_categories,name',
        ]);

        ExpenseCategory::create([
            'account_id' => $accountId, 
            'name' => $request->name,

        ]);

        return back()->with('success', 'Expense category added successfully.');
    }
}

