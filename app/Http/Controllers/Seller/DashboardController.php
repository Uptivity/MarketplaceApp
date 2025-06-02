<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show seller dashboard with summarized data
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get inventory stats
        $inventoryStats = [
            'total_products' => $user->products()->count(),
            'low_stock_products' => $user->products()->whereRaw('quantity > 0 AND quantity <= low_stock_threshold')->count(),
            'out_of_stock_products' => $user->products()->where('quantity', 0)->count(),
            'expiring_soon_products' => $user->products()->whereNotNull('expiry_date')
                ->where('expiry_date', '>', now())
                ->where('expiry_date', '<=', now()->addDays(7))
                ->count(),
        ];
        
        // Get sales stats - assuming we have order items that reference products by title
        $productTitles = $user->products()->pluck('title')->toArray();
        
        $salesStats = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('product_title', $productTitles)
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->select(DB::raw('SUM(quantity * price) as monthly_revenue'), DB::raw('COUNT(DISTINCT order_id) as monthly_orders'))
            ->first();
        
        $salesStats = [
            'monthly_revenue' => $salesStats->monthly_revenue ?? 0,
            'monthly_orders' => $salesStats->monthly_orders ?? 0,
        ];
        
        // Get unread notifications
        $notifications = $user->notifications()->latest()->limit(5)->get();
        
        // Get low stock products
        $lowStockProducts = $user->products()
            ->whereRaw('quantity > 0 AND quantity <= low_stock_threshold')
            ->get();
        
        // Get recent orders with seller's products
        $recentOrders = Order::whereHas('items', function($query) use ($productTitles) {
            $query->whereIn('product_title', $productTitles);
        })
        ->with('items')
        ->latest()
        ->limit(5)
        ->get();
        
        return view('seller.dashboard', compact(
            'inventoryStats', 
            'salesStats', 
            'notifications', 
            'lowStockProducts', 
            'recentOrders'
        ));
    }
}
