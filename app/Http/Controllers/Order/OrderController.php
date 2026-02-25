<?php

namespace app\Http\Controllers\Order;

use app\Enums\OrderStatus;
use app\Http\Controllers\Controller;
use app\Http\Requests\Order\OrderStoreRequest;
use app\Models\Customer;
use app\Models\Order;
use app\Models\OrderDetails;
use app\Models\Product;
use app\Models\User;
use app\Models\Debt;
use app\Mail\StockAlert;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; 

class OrderController extends Controller
{
    public function index()
    {
        
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        $orders = Order::query()
            ->where('account_id', $accountId) // Filter by account_id
            ->count();

        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $userId = auth()->id();
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        $products = Product::query()
            ->where('account_id', $accountId) // Filter by account_id
            ->with(['category', 'unit'])
            ->get();

        $customers = Customer::where('user_id', $userId)
            ->where('account_id', $accountId) // Filter by account_id
            ->get(['id', 'name']);

        $carts = Cart::content();

        return view('orders.create', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    public function store(Request $request)
    {
        Log::info('OrderController@store started', ['request' => $request->all()]);

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_type' => 'required|in:HandCash,Cheque,Due',
            'pay' => 'required|numeric|min:0',
            'active_tab' => 'required|integer|between:1,5',
        ]);

        $pendingOrder = session('pending_order');
        Log::info('Pending order from session', ['pending_order' => $pendingOrder]);

        if (!$pendingOrder || empty($pendingOrder['cart'])) {
            Log::warning('No pending order data found in session');
            return redirect()->route('pos.index')->with('error', 'No order data found. Please start over.');
        }

        $cartInstance = 'customer' . $pendingOrder['active_tab'];
        Cart::instance($cartInstance)->destroy();
        foreach ($pendingOrder['cart'] as $item) {
            Cart::instance($cartInstance)->add($item);
        }
        Log::info('Cart re-populated', ['cart_content' => Cart::instance($cartInstance)->content()->toArray()]);

        foreach (Cart::instance($cartInstance)->content() as $item) {
            $product = Product::find($item->id);
            if ($product && $item->qty > $product->quantity) {
                Cart::instance($cartInstance)->destroy();
                Log::warning('Stock check failed', ['product_id' => $item->id, 'name' => $product->name]);
                return redirect()->route('pos.index')->with('error', 'Product "' . $product->name . '" is out of stock!');
            }
        }

        DB::beginTransaction();
        try {
            $userId = auth()->id();
            $accountId = auth()->user()->account_id;

            // Use VAT from session (calculated in CreateOrder)
            $subTotal = $pendingOrder['sub_total'];
            $vat = $pendingOrder['vat']; // 2,880, not Cart::tax()
            $total = $pendingOrder['total'];

            Log::info('Order totals', [
                'sub_total' => $subTotal,
                'vat' => $vat,
                'total' => $total,
            ]);

            $order = Order::create([
                'customer_id' => $request->customer_id ?: null,
                'payment_type' => $request->payment_type,
                'pay' => $request->pay,
                'order_date' => Carbon::now()->format('Y-m-d'),
                'order_status' => $request->payment_type === 'Due' ? OrderStatus::PENDING->value : OrderStatus::COMPLETE->value,
                'total_products' => Cart::instance($cartInstance)->count(),
                'sub_total' => $subTotal,
                'vat' => $vat,
                'total' => $total,
                'invoice_no' => IdGenerator::generate([
                    'table' => 'orders',
                    'field' => 'invoice_no',
                    'length' => 10,
                    'prefix' => 'INV-'
                ]),
                'due' => $total - $request->pay,
                'user_id' => $userId,
                'account_id' => $accountId,
                'uuid' => Str::uuid(),
            ]);

            Log::info('Order created', ['order_id' => $order->id]);

            foreach (Cart::instance($cartInstance)->content() as $item) {
                $product = Product::find($item->id);
                if ($product) {
                    $product->quantity -= $item->qty;
                    $product->save();
                }

                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $product ? $product->id : null,
                    'name' => $item->name,
                    'quantity' => $item->qty,
                    'unitcost' => $item->price,
                    'total' => $item->subtotal,
                    'account_id' => $accountId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            Cart::instance($cartInstance)->destroy();
            session()->forget('pending_order');
            Log::info('Cart and session cleared');

            DB::commit();
            Log::info('Transaction committed');

            return redirect()
                ->route('orders.index')
                ->with('success', 'Order has been created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('pos.index')->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }
    // Update storeDebt similarly
    public function storeDebt(Request $request)
    {
        Log::info('OrderController@storeDebt started', ['request' => $request->all()]);

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'active_tab' => 'required|integer|between:1,5',
            'payment_type' => 'required|in:Due',
            'customer_set' => 'required|string',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        $pendingOrder = session('pending_order');
        if (!$pendingOrder || empty($pendingOrder['cart'])) {
            Log::warning('No pending order data found in session');
            return redirect()->route('pos.index')->with('error', 'No order data found.');
        }

        $cartInstance = 'customer' . $pendingOrder['active_tab'];
        Cart::instance($cartInstance)->destroy();
        foreach ($pendingOrder['cart'] as $item) {
            Cart::instance($cartInstance)->add($item);
        }

        DB::beginTransaction();
        try {
            $userId = auth()->id();
            $accountId = auth()->user()->account_id;

            $order = Order::create([
                'customer_id' => $request->customer_id ?: null,
                'payment_type' => $request->payment_type,
                'pay' => 0,
                'order_date' => Carbon::now()->format('Y-m-d'),
                'order_status' => OrderStatus::PENDING->value,
                'total_products' => Cart::instance($cartInstance)->count(),
                'sub_total' => Cart::instance($cartInstance)->subtotalFloat(),
                'vat' => Cart::instance($cartInstance)->taxFloat(),
                'total' => Cart::instance($cartInstance)->totalFloat(),
                'invoice_no' => IdGenerator::generate([
                    'table' => 'orders',
                    'field' => 'invoice_no',
                    'length' => 10,
                    'prefix' => 'INV-'
                ]),
                'due' => $request->amount,
                'user_id' => $userId,
                'account_id' => $accountId,
                'uuid' => Str::uuid(),
            ]);

            foreach (Cart::instance($cartInstance)->content() as $item) {
                $product = Product::find($item->id);
                if ($product) {
                    $product->quantity -= $item->qty;
                    $product->save();
                }

                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $product ? $product->id : null,
                    'name' => $item->name,
                    'quantity' => $item->qty,
                    'unitcost' => $item->price,
                    'total' => $item->subtotal,
                    'account_id' => $accountId, // Add account_id here
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

                Debt::create([
                'customer_id' => $request->customer_id,
                'order_id' => $order->id,
                'customer_set' => $request->customer_set,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'account_id' => $accountId,
            ]);

            Cart::instance($cartInstance)->destroy();
            session()->forget('pending_order');

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Debt created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Cart::instance($cartInstance)->destroy();
            Log::error('Error creating debt', ['message' => $e->getMessage()]);
            return redirect()->route('pos.index')->with('error', 'Error creating debt: ' . $e->getMessage());
        }
    }
    public function show($uuid)
    {
       
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        $order = Order::where('uuid', $uuid)
           
            ->where('account_id', $accountId) // Filter by account_id
            ->firstOrFail();

        $order->loadMissing(['customer', 'details']);

        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function update($uuid, Request $request)
    {
        $accountId = auth()->user()->account_id;
    
        $order = Order::where('uuid', $uuid)
            ->where('account_id', $accountId)
            ->firstOrFail();
    
        $request->validate([
            'payment_type' => 'required|in:HandCash,Cheque,Due',
            'pay' => 'required|numeric|min:0',
        ]);
    
        // Update order with payment details
        $order->update([
            'order_status' => $request->payment_type === 'Due' ? OrderStatus::PENDING->value : OrderStatus::COMPLETE->value,
            'payment_type' => $request->payment_type,
            'pay' => $request->pay,
            'due' => $order->total - $request->pay,
        ]);
    
        if ($request->payment_type !== 'Due') {
            // Reduce stock only on payment completion (if not already done in createOrder)
            foreach ($order->details as $detail) {
                $product = Product::find($detail->product_id);
                if ($product) {
                    $newQty = $product->quantity - $detail->quantity;
                    $product->update(['quantity' => $newQty]);
                }
            }
        }
    
        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been updated successfully!');
    }

    public function destroy($uuid)
    {
     
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        $order = Order::where('uuid', $uuid)
           
            ->where('account_id', $accountId) // Filter by account_id
            ->firstOrFail();

        $order->delete();
    }

    public function downloadInvoice($uuid)
    {
        
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        $order = Order::with(['customer', 'details'])
            ->where('uuid', $uuid)
            
            ->where('account_id', $accountId) // Filter by account_id
            ->firstOrFail();

        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'order_status' => 2
        ]);
        $orders = Order::where('user_id',auth()->id())->count();

        return redirect()
            ->route('orders.index', [
                'orders' => $orders
            ])
            ->with('success', 'Order has been canceled!');
    }


    public function setActiveCustomer(Request $request)
    {
        $request->validate([
            'active_customer' => 'required|integer|min:0|max:4'
        ]);

        session(['active_customer' => $request->active_customer]);

        return response()->json(['success' => true]);
    }

    public function updateCartItem(Request $request, $rowId)
    {
        $qty = $request->input('qty');
        // Update cart logic
        return response()->json(['cartContent' => view('partials.cart-content', compact('cart'))->render()]);
    }

    public function deleteCartItem(Request $request)
    {
        $rowId = $request->input('rowId');
        // Delete cart item logic
        return response()->json(['success' => true, 'cartContent' => view('partials.cart-content', compact('cart'))->render()]);
    }

    public function showOrder($orderId)
    {
       
        $accountId = auth()->user()->account_id; // Get the account_id of the logged-in user

        // Fetch the order with its related customer and items
        $order = Order::with(['customer', 'details'])
            ->where('uuid', $orderId)
          
            ->where('account_id', $accountId) // Filter by account_id
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
 


public function approve($uuid)
{
    // Find the order by UUID
    $order = Order::where('uuid', $uuid)->firstOrFail();

    // Update the order status to COMPLETE
    $order->update([
        'order_status' => OrderStatus::COMPLETE,
    ]);

    // Optionally, reduce the product stock here (if not already handled elsewhere)
    foreach ($order->details as $detail) {
        $product = Product::find($detail->product_id);
        $product->update([
            'quantity' => $product->quantity - $detail->quantity,
        ]);
    }

    return redirect()
        ->route('orders.index')
        ->with('success', 'Order approved successfully!');
}



}



