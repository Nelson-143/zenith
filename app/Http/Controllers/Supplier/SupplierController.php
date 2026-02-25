<?php

namespace app\Http\Controllers\Supplier;

use app\Enums\SupplierType;
use app\Http\Controllers\Controller;
use app\Http\Requests\Supplier\StoreSupplierRequest;
use app\Http\Requests\Supplier\UpdateSupplierRequest;
use app\Models\Supplier;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure authentication
    }

    public function index()
    {
        // Get the logged-in user's account_id
        $accountId = auth()->user()->account_id;

        // Fetch suppliers for the logged-in user's account
        $suppliers = Supplier::where('account_id', $accountId)
            ->where('user_id', auth()->id())
            ->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();

        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id();
        $data['account_id'] = auth()->user()->account_id; // Set the account_id
        $data['type'] = SupplierType::from(strtolower($request->input('type')));

        // Handle file upload if present
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Define the custom path where you want to store the file
            $destinationPath = public_path('assets/img/suppliers/');

            // Ensure the directory exists or create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
            }

            // Define the filename (optional: you can rename or use the original name)
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Move the file to the specified folder
            $file->move($destinationPath, $fileName);

            // Save the path to the database (relative to the public folder)
            $data['photo'] = 'assets/img/suppliers/' . $fileName;
        }

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($uuid)
    {
        // Ensure the supplier belongs to the logged-in user's account
        $supplier = Supplier::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        return view('suppliers.show', compact('supplier'));
    }

    public function edit($uuid)
    {
        // Ensure the supplier belongs to the logged-in user's account
        $supplier = Supplier::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, $uuid)
    {
        // Ensure the supplier belongs to the logged-in user's account
        $supplier = Supplier::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        $data = $request->validated();

        // Update user ID and type
        $data['user_id'] = auth()->id();
        $data['type'] = SupplierType::from(strtolower($request->input('type')));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Get the uploaded file
            $file = $request->file('photo');

            // Define the filename with a timestamp
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Define the path where the image will be stored
            $destinationPath = public_path('assets/img/suppliers');

            // Ensure the directory exists or create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
            }

            // Move the file to the specified folder
            $file->move($destinationPath, $fileName);

            // Store the relative path in the database
            $data['photo'] = 'assets/img/suppliers/' . $fileName;
        } else {
            // Retain the existing photo if no new file is uploaded
            $data['photo'] = $supplier->photo; // Keep the existing photo
        }

        // Update the supplier with the new data
        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($uuid)
    {
        // Ensure the supplier belongs to the logged-in user's account
        $supplier = Supplier::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function getSupplierDetails($id)
    {
        // Ensure the supplier belongs to the logged-in user's account
        $supplier = Supplier::where('id', $id)
            ->where('account_id', auth()->user()->account_id)
            ->first();

        if ($supplier) {
            return response()->json([
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'address' => $supplier->address,
                'id' => $supplier->id,
            ]);
        } else {
            return response()->json([], 404);  // Return 404 if no supplier is found
        }
    }
}