<?php

namespace app\Http\Controllers\Order;

use app\Enums\OrderStatus;
use app\Models\Order;
use Illuminate\Http\Request;
use app\Http\Controllers\Controller;

class OrderPendingController extends Controller
{
    public function __invoke(Request $request)
    {
        $orders = Order::where('order_status', OrderStatus::PENDING)
            ->latest()
            ->with('customer')
            ->get();

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }
}