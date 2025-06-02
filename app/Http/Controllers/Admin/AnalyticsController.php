<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    
    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }
    
    /**
     * Display analytics overview dashboard.
     */
    public function index()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $totalRevenue = Order::where('status', 'delivered')->sum('total_price') ?? 0;
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
        $activeProducts = Product::where('quantity', '>', 0)->count();
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
        
        // Get sales data for chart
        $salesData = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $dates = [];
        $dailySales = [];
        
        foreach ($salesData as $data) {
            $dates[] = Carbon::parse($data->date)->format('M d');
            $dailySales[] = round($data->total, 2);
        }
        
        // Get category data for chart
        $categories = Category::withCount('products')->get();
        $categoryLabels = $categories->pluck('name')->toArray();
        $categoryCounts = $categories->pluck('products_count')->toArray();
        
        // Recent activity
        $recentActivity = DB::table('activity_log')
            ->join('users', 'activity_log.user_id', '=', 'users.id')
            ->select('activity_log.*', 'users.name', 'users.email')
            ->orderBy('activity_log.created_at', 'desc')
            ->limit(10)
            ->get();
            
        if (!$recentActivity) {
            // If activity_log table doesn't exist, create a fallback
            $recentActivity = collect([]);
            
            // Add recent orders
            $recentOrders = Order::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($order) {
                    return (object)[
                        'id' => $order->id,
                        'type' => 'order',
                        'description' => "Order #{$order->id} for \${$order->total_price}",
                        'user' => $order->user,
                        'created_at' => $order->created_at,
                    ];
                });
                
            // Add recent products
            $recentProducts = Product::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function($product) {
                    return (object)[
                        'id' => $product->id,
                        'type' => 'product',
                        'description' => "Product \"{$product->title}\" was created",
                        'user' => $product->user,
                        'created_at' => $product->created_at,
                    ];
                });
                
            $recentActivity = $recentOrders->merge($recentProducts)
                ->sortByDesc('created_at')
                ->take(10);
        }
        
        return view('admin.analytics.index', compact(
            'totalRevenue', 
            'ordersToday', 
            'activeProducts', 
            'activeUsers',
            'dates',
            'dailySales',
            'categoryLabels',
            'categoryCounts',
            'recentActivity'
        ));
    }
    
    /**
     * Show the store performance analytics.
     */
    public function store()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $data = [
            'dailySales' => $this->analyticsService->getDailySalesForLastDays(30),
            'monthlySales' => $this->analyticsService->getMonthlySalesForLastMonths(12),
            'topSellingProducts' => $this->analyticsService->getTopSellingProducts(10),
            'topSellingCategories' => $this->analyticsService->getTopSellingCategories(5),
            'salesBySellerChart' => $this->analyticsService->getSalesBySeller(10),
            'averageOrderValue' => $this->analyticsService->getAverageOrderValue(),
            'salesGrowth' => $this->analyticsService->getSalesGrowth(),
        ];
        
        return view('admin.analytics.store', $data);
    }
    
    /**
     * Show the user analytics.
     */
    public function users()
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $data = [
            'newUsers' => $this->analyticsService->getNewUsersForLastDays(30),
            'activeUsers' => $this->analyticsService->getActiveUsers(),
            'usersByRole' => $this->analyticsService->getUsersByRole(),
            'topBuyers' => $this->analyticsService->getTopBuyersBySpent(10),
            'topSellers' => $this->analyticsService->getTopSellersByRevenue(10),
            'userGrowth' => $this->analyticsService->getUserGrowth(),
        ];
        
        return view('admin.analytics.users', $data);
    }
}
