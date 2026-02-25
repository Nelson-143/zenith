<?php

namespace app\Http\Controllers\Order;

use app\Enums\OrderStatus;
use app\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderCompleteController extends Controller
{
    public function __invoke(Request $request)
    {
        $orders = Order::where('order_status', OrderStatus::COMPLETE)
            ->latest()
            ->with('customer')
            ->get();

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }
}