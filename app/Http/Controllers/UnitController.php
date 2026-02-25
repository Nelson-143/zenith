<?php

namespace app\Http\Controllers;

use app\Models\Unit;
use app\Http\Requests\Unit\StoreUnitRequest;
use app\Http\Requests\Unit\UpdateUnitRequest;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure authentication
    }

    /**
     * Display a listing of units for the logged-in user's account.
     */
    public function index()
    {
        // Fetch units for the logged-in user's account (filtered by account_id via global scope)
        $units = Unit::select(['id', 'name', 'slug', 'short_code'])->get();

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        Unit::create([
            'account_id' => $accountId, // Set the account_id
            'user_id' => auth()->id(), // Keep track of the creator
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'short_code' => $request->short_code,
        ]);

        return redirect()->route('units.index')->with('success', 'The unit has been added successfully!');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        // Ensure the unit belongs to the logged-in user's account (via global scope)
        $unit->loadMissing('products');

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        // Ensure the unit belongs to the logged-in user's account (via global scope)
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(UpdateUnitRequest $request, $slug)
    {
        // Find the unit by slug (filtered by account_id via global scope)
        $unit = Unit::where('slug', $slug)->firstOrFail();

        // Update the unit
        $unit->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'short_code' => $request->short_code,
        ]);

        return redirect()->route('units.index')->with('success', 'The unit has been updated successfully!');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        // Ensure the unit belongs to the logged-in user's account (via global scope)
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'The unit has been deleted successfully!');
    }
}