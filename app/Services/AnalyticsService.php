<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get daily sales for the last specified days.
     */
    public function getDailySalesForLastDays(int $days = 30)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        
        return Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'total' => (float) $item->total,
                    'formatted_date' => Carbon::parse($item->date)->format('M d'),
                ];
            });
    }
    
    /**
     * Get monthly sales for the last specified months.
     */
    public function getMonthlySalesForLastMonths(int $months = 12)
    {
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
        
        return Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'year' => $item->year,
                    'month' => $item->month,
                    'total' => (float) $item->total,
                    'formatted_month' => $date->format('M Y'),
                ];
            });
    }
    
    /**
     * Get top selling products.
     */
    public function getTopSellingProducts(int $limit = 10)
    {
        return DB::table('order_items')
            ->select('product_title', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(product_price * quantity) as total_revenue'))
            ->groupBy('product_title')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get top selling categories.
     */
    public function getTopSellingCategories(int $limit = 5)
    {
        return Category::select('categories.name', DB::raw('COUNT(order_items.id) as total_sold'))
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', function($join) {
                $join->where('order_items.product_title', '=', DB::raw('products.title'));
            })
            ->groupBy('categories.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get sales by seller.
     */
    public function getSalesBySeller(int $limit = 10)
    {
        return User::where('role', 'seller')
            ->select('users.name', DB::raw('SUM(order_items.product_price * order_items.quantity) as total_revenue'))
            ->join('products', 'users.id', '=', 'products.user_id')
            ->leftJoin('order_items', function($join) {
                $join->where('order_items.product_title', '=', DB::raw('products.title'));
            })
            ->groupBy('users.name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get average order value.
     */
    public function getAverageOrderValue()
    {
        return Order::avg('total_price') ?? 0;
    }
    
    /**
     * Get sales growth percentage compared to previous period.
     */
    public function getSalesGrowth()
    {
        $currentPeriodStart = Carbon::now()->startOfMonth();
        $previousPeriodStart = Carbon::now()->subMonth()->startOfMonth();
        $previousPeriodEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $currentPeriodSales = Order::where('created_at', '>=', $currentPeriodStart)
            ->sum('total_price');
            
        $previousPeriodSales = Order::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->sum('total_price');
            
        if ($previousPeriodSales == 0) {
            return $currentPeriodSales > 0 ? 100 : 0;
        }
        
        return (($currentPeriodSales - $previousPeriodSales) / $previousPeriodSales) * 100;
    }
    
    /**
     * Get new users for the last specified days.
     */
    public function getNewUsersForLastDays(int $days = 30)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        
        return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                    'formatted_date' => Carbon::parse($item->date)->format('M d'),
                ];
            });
    }
    
    /**
     * Get active users (users who placed orders in the last 30 days).
     */
    public function getActiveUsers()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        return User::select('users.id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.created_at', '>=', $thirtyDaysAgo)
            ->groupBy('users.id')
            ->get()
            ->count();
    }
    
    /**
     * Get users by role.
     */
    public function getUsersByRole()
    {
        return User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();
    }
    
    /**
     * Get top buyers by amount spent.
     */
    public function getTopBuyersBySpent(int $limit = 10)
    {
        return User::where('role', 'buyer')
            ->select('users.name', DB::raw('SUM(orders.total_price) as total_spent'), DB::raw('COUNT(orders.id) as order_count'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.name')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get top sellers by revenue.
     */
    public function getTopSellersByRevenue(int $limit = 10)
    {
        return User::where('role', 'seller')
            ->select(
                'users.name', 
                DB::raw('SUM(order_items.product_price * order_items.quantity) as total_revenue'), 
                DB::raw('COUNT(DISTINCT order_items.id) as orders_count')
            )
            ->join('products', 'users.id', '=', 'products.user_id')
            ->leftJoin('order_items', function($join) {
                $join->whereRaw('order_items.product_title = products.title');
            })
            ->groupBy('users.name')
            ->orderByDesc('total_revenue')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get user growth percentage compared to previous period.
     */
    public function getUserGrowth()
    {
        $currentPeriodStart = Carbon::now()->startOfMonth();
        $previousPeriodStart = Carbon::now()->subMonth()->startOfMonth();
        $previousPeriodEnd = Carbon::now()->subMonth()->endOfMonth();
        
        $currentPeriodUsers = User::where('created_at', '>=', $currentPeriodStart)->count();
        $previousPeriodUsers = User::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])->count();
        
        if ($previousPeriodUsers == 0) {
            return $currentPeriodUsers > 0 ? 100 : 0;
        }
        
        return (($currentPeriodUsers - $previousPeriodUsers) / $previousPeriodUsers) * 100;
    }
}
