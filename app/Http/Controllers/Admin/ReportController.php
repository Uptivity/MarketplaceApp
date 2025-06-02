<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }        // Get weekly sales data
        try {
            $weeklyData = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_price) as daily_sales')
                )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching weekly sales data: ' . $e->getMessage());
            $weeklyData = collect([]);
        }        // Get top selling products - try/catch for database error handling
        try {
            // First check if the order_items table exists
            if (Schema::hasTable('order_items')) {
                $topProducts = DB::table('order_items')
                    ->select('product_title', DB::raw('SUM(quantity) as total_sold'))
                    ->groupBy('product_title')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get();
            } else {
                $topProducts = collect([]);
            }
        } catch (\Exception $e) {
            // If there's an error, provide an empty collection
            \Log::error('Error fetching top products: ' . $e->getMessage());
            $topProducts = collect([]);
        }// Get sales by status
        try {
            $salesByStatus = Order::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_price) as total'))
                ->groupBy('status')
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching sales by status: ' . $e->getMessage());
            $salesByStatus = collect([]);
        }

        return view('admin.reports.sales', compact('weeklyData', 'topProducts', 'salesByStatus'));
    }    public function inventory()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get low stock products
        $lowStockProducts = Product::where('quantity', '<', 5)
            ->orderBy('quantity')
            ->with('user') // Eager load the user relationship
            ->get();

        // Get expired products
        $expiredProducts = Product::whereDate('expiry_date', '<', Carbon::now())
            ->orderBy('expiry_date')
            ->with('user') // Eager load the user relationship
            ->get();

        // Get products expiring soon
        $expiringProducts = Product::whereDate('expiry_date', '>', Carbon::now())
            ->whereDate('expiry_date', '<', Carbon::now()->addDays(30))
            ->orderBy('expiry_date')
            ->with('user') // Eager load the user relationship
            ->get();

        return view('admin.reports.inventory', compact('lowStockProducts', 'expiredProducts', 'expiringProducts'));
    }
}
