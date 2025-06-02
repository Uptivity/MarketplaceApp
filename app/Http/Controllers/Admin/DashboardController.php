<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\SiteSetting;

class DashboardController extends Controller
{
    public function index()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get summary statistics
        $stats = [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalSales' => Order::sum('total_price'),
            'buyerCount' => User::where('role', 'buyer')->count(),
            'sellerCount' => User::where('role', 'seller')->count(),
            'adminCount' => User::where('role', 'admin')->count(),
        ];

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
                          ->take(5)
                          ->get();

        // Get recent orders
        $recentOrders = Order::with('user')
                           ->orderBy('created_at', 'desc')
                           ->take(5)
                           ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentOrders'));
    }
}
