<?php

namespace app\Http\Controllers;

use app\Models\Product;
use app\Models\Customer;
use app\Models\Debt;
use app\Models\Order;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'unit'])->get();

        $customers = Customer::all()->sortBy('name');

        $carts = Cart::content();

        return view('pos.index', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    public function addCartItem(Request $request)
    {
        // Check if the product is a custom product
        if ($request->has('is_custom_product') && $request->input('is_custom_product') == 1) {
            // Validate the request for custom products
            $request->validate([
                'name' => 'required|string',
                'quantity' => 'required|numeric|min:1',
                'selling_price' => 'required|numeric|min:0',
            ]);
    
            // Add the custom product to the cart
            Cart::add([
                'id' => uniqid(), // Generate a unique ID for the product
                'name' => $request->input('name'),
                'qty' => $request->input('quantity'),
                'price' => $request->input('selling_price'),
                'weight' => 1, // Default weight
                'options' => [] // Additional options (if needed)
            ]);
        } else {
            // Validate the request for inventory products
            $request->validate([
                'id' => 'required|numeric',
                'name' => 'required|string',
                'selling_price' => 'required|numeric',
            ]);
    
            // Add the inventory product to the cart
            Cart::add(
                $request->input('id'),
                $request->input('name'),
                1, // Default quantity
                $request->input('selling_price'),
                1, // Default weight
                (array) $options = null // Additional options (if needed)
            );
        }
    
        return redirect()
            ->back()
            ->with('success', 'Product has been added to cart!');
    }
    public function updateCartItem(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
            'product_id' => 'numeric'
        ];
        
        $validatedData = $request->validate($rules);
        if ($validatedData['qty'] > Product::where('id', intval($validatedData['product_id']))->value('quantity')) {
            return redirect()
            ->back()
            ->with('error', 'The requested quantity is not available in stock.');
        }
        

        Cart::update($rowId, $validatedData['qty']);

        return redirect()
            ->back()
            ->with('success', 'Product has been updated from cart!');
    }

    public function deleteCartItem(String $rowId)
    {
        Cart::remove($rowId);

        return redirect()
            ->back()
            ->with('success', 'Product has been deleted from cart!');
    }

    public function storeDebt(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_set' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);
    
        // Fetch cart items
        $cartItems = Cart::content();
    
        // Check if cart is empty
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Cart is empty. Cannot create debt.');
        }
    
        // Start a database transaction
        \DB::beginTransaction();
    
        try {
            // Reduce product quantities
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);
    
                if ($product) {
                    // Check if there is enough stock
                    if ($product->quantity < $item->qty) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }
    
                    // Reduce the product quantity
                    $product->quantity -= $item->qty;
                    $product->save();
                } else {
                    throw new \Exception("Product not found: {$item->id}");
                }
            }
    
            // Save the debt
            $debt = new Debt();
            $debt->customer_id = $request->input('customer_id');
            $debt->customer_set = $validated['customer_set'];
            $debt->amount = $validated['amount'];
            $debt->amount_paid = 0;
            $debt->due_date = $validated['due_date'];
            $debt->account_id = auth()->user()->account_id;
            $debt->save();
    
            // Clear the cart
            Cart::destroy();
    
            // Commit the transaction
            \DB::commit();
    
            return redirect()->route('debts.index')->with('success', 'Debt added successfully and product quantities updated.');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            \DB::rollBack();
    
            // Log the error
            \Log::error('Error creating debt:', ['error' => $e->getMessage()]);
    
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

