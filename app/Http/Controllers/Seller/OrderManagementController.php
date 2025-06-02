<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index()
    {
        // For now, show ALL orders (later we'll restrict to only their products)
        // Eager load the user relationship to avoid N+1 query problem
        $orders = Order::with('user')->latest()->get();

        return view('seller.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['items', 'user', 'statusHistory.creator']);
        
        return view('seller.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'comment' => 'nullable|string|max:500'
        ]);
        
        $newStatus = $request->status;
        
        // Check if the status transition is valid
        if (!$order->canUpdateStatus($newStatus)) {
            return back()->with('error', "Cannot update order status from '{$order->status}' to '{$newStatus}'.");
        }
        
        // Update the order status
        $order->addStatusUpdate($newStatus, $request->comment);
        
        return back()->with('success', 'Order status updated successfully.');
    }
}
