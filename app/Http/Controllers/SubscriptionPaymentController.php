<?php

namespace app\Http\Controllers;

use app\Models\SubscriptionPayment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionPaymentController extends Controller
{
    //
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'subscription_id' => 'required|exists:subscriptions,id',
        'amount_paid' => 'required|numeric',
        'payment_method' => 'required|string',
        'transaction_id' => 'required|string|unique:subscription_payments',
    ]);

    $payment = SubscriptionPayment::create([
        'id' => Str::uuid(),
        'user_id' => $request->user_id,
        'subscription_id' => $request->subscription_id,
        'amount_paid' => $request->amount_paid,
        'payment_method' => $request->payment_method,
        'transaction_id' => $request->transaction_id,
        'status' => 'pending',
    ]);

    return response()->json(['message' => 'Payment recorded successfully', 'payment' => $payment]);
}

}
