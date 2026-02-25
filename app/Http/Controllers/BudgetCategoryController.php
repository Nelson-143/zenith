<?php

namespace app\Http\Controllers;

use app\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    
    public function store(Request $request)
    {
        
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        $request->validate([
            'name' => 'required|unique:budget_categories,name',
        ]);

        BudgetCategory::create([
            'account_id' => $accountId, 
            'name' => $request->name,
        ]);

        return back()->with('success', 'Budget category added successfully.');
    }
}
