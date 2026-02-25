<?php

namespace app\Http\Controllers\Dashboards;

use app\Http\Controllers\Controller;
use app\Http\Requests\Category\StoreCategoryRequest;
use app\Http\Requests\Category\UpdateCategoryRequest;
use app\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure authentication
    }

    /**
     * Display a listing of categories for the logged-in user's account.
     */
    public function index()
    {
        // Fetch categories for the logged-in user's account (filtered by account_id via global scope)
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        $data = $request->validated();
        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id(); // Keep track of the creator
        $data['account_id'] = $accountId; // Set the account_id
        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        // Ensure the category belongs to the logged-in user's account (via global scope)
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Ensure the category belongs to the logged-in user's account (via global scope)
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, $slug)
    {
        // Find the category by slug (filtered by account_id via global scope)
        $category = Category::where('slug', $slug)->firstOrFail();

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Ensure the category belongs to the logged-in user's account (via global scope)
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}