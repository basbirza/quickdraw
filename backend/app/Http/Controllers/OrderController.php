<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function printLabel(Order $order)
    {
        // Load order items for the label
        $order->load('items');

        return view('orders.print-label', compact('order'));
    }

    public function printLabelsBulk(Request $request)
    {
        // Get order IDs from query parameter
        $orderIds = explode(',', $request->input('ids'));

        // Fetch orders with items
        $orders = Order::with('items')
            ->whereIn('id', $orderIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.print-labels-bulk', compact('orders'));
    }
}
