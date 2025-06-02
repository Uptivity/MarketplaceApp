<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                ->with('statusHistory')
                ->latest()
                ->get();

        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Ensure the user can only view their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->load(['items', 'statusHistory.creator']);
        
        return view('orders.show', compact('order'));
    }
}
