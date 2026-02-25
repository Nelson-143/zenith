<?php

namespace app\Http\Controllers;

use app\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the branches.
     */
    public function index()
    {
        $branches = Branch::where('account_id', auth()->user()->account_id)->get();

        return view('branches.index', compact('branches')); // Load the UI
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,disabled',
        ]);
    
        // Add the authenticated user as the creator
       
    
        // Create the branch
        $branch = Branch::create($validated);
    
        // Redirect with a success message
        return redirect()->route('branches.index')->with('success', 'Branch created successfully!');
    }

    /**
     * Get the specified branch (AJAX request).
     */
    public function show($id)
    {
        $branch = Branch::findOrFail($id);

        return response()->json([
            'success' => true,
            'branch' => $branch
        ]);
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,disabled',
        ]);

        $branch->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Branch updated successfully!',
            'branch' => $branch
        ]);
    }

    /**
     * Disable the specified branch.
     */
    public function destroy(Branch $branch)
    {
        $branch->update(['status' => 'disabled']);

        return response()->json([
            'success' => true,
            'message' => 'Branch disabled successfully!'
        ]);
    }
}
