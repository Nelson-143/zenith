<?php

namespace app\Http\Controllers;

use app\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create()
    {
        $pendingOrder = session('pending_order');
        if (!$pendingOrder || empty($pendingOrder['cart'])) {
            return redirect()->route('pos.index')->with('error', 'No order data found. Please start over.');
        }

        $customer = $pendingOrder['customer_id'] && $pendingOrder['customer_id'] !== 'pass_by' 
            ? Customer::findOrFail($pendingOrder['customer_id']) 
            : null;

        return view('invoices.create', [
            'cart' => $pendingOrder['cart'],
            'customer' => $customer,
            'sub_total' => $pendingOrder['sub_total'],
            'vat' => $pendingOrder['vat'],
            'total' => $pendingOrder['total'],
            'active_tab' => $pendingOrder['active_tab'],
        ]);
    }
}