<?php

namespace app\Http\Controllers;

use app\Http\Requests\Customer\StoreCustomerRequest;
use app\Http\Requests\Customer\UpdateCustomerRequest;
use app\Models\Order;
use app\Models\{Customer, User};
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index()
    {
        // Fetch customers for the logged-in user's account
        $customers = Customer::where('account_id', auth()->user()->account_id)->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id();
        $data['account_id'] = auth()->user()->account_id; // Set the account_id
      

        // Handle image upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Define the custom path where you want to store the file
            $destinationPath = public_path('assets/img/customers/');

            // Ensure the directory exists or create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
            }

            // Define the filename (optional: you can rename or use the original name)
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Move the file to the specified folder
            $file->move($destinationPath, $fileName);

            // Save the path to the database (relative to the public folder)
            $data['photo'] = 'assets/img/customers/' . $fileName;
        }

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }
    public function show($uuid)
    {
        // Fetch the customer for the logged-in user's account
        $customer = Customer::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();
    
        // Fetch the number of orders and the total amount contributed by the customer
        $orderCount = Order::where('customer_id', $customer->id)
            ->where('account_id', auth()->user()->account_id) // Ensure to filter by account_id
            ->count();
    
        $totalContributed = Order::where('customer_id', $customer->id)
            ->where('account_id', auth()->user()->account_id) // Ensure to filter by account_id
            ->sum('total');
    
        return view('customers.show', compact('customer', 'orderCount', 'totalContributed'));
    }

    public function edit($uuid)
    {
        // Fetch the customer for the logged-in user's account
        $customer = Customer::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, $uuid)
    {
        // Fetch the customer for the logged-in user's account
        $customer = Customer::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        $data = $request->validated();
        $data['uuid'] = Str::uuid();
        $data['user_id'] = auth()->id();
        $data['account_id'] = auth()->user()->account_id; // Ensure account_id is set

        // Handle image upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Define the custom path where you want to store the file
            $destinationPath = public_path('assets/img/customers/');

            // Ensure the directory exists or create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Create the directory if it doesn't exist
            }

            // Define the filename (optional: you can rename or use the original name)
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Move the file to the specified folder
            $file->move($destinationPath, $fileName);

            // Save the path to the database (relative to the public folder)
            $data['photo'] = 'assets/img/customers/' . $fileName;
        } else {
            // Retain the existing photo if no new file is uploaded
            $data['photo'] = $customer->photo; // Keep the existing photo
        }

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($uuid)
    {
        // Fetch the customer for the logged-in user's account
        $customer = Customer::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();

        if ($customer->photo && file_exists(public_path($customer->photo))) {
            unlink(public_path($customer->photo));
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
