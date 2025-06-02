<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Generate inventory report for the seller
     */
    public function inventory()
    {
        $user = Auth::user();
        
        // Get aggregate statistics
        $stats = [
            'total_products' => $user->products()->count(),
            'total_value' => $user->products()->sum(DB::raw('price * quantity')),
            'avg_price' => $user->products()->avg('price'),
            'low_stock' => $user->products()->whereRaw('quantity > 0 AND quantity <= low_stock_threshold')->count(),
            'out_of_stock' => $user->products()->where('quantity', 0)->count(),
        ];
        
        // Get products grouped by category
        $categoryData = DB::table('products')
            ->select('categories.name as category', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(price * quantity) as total_value'))
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.user_id', $user->id)
            ->groupBy('categories.name')
            ->orderByDesc('total_value')
            ->get();
            
        // Get expiring products within the next 30 days
        $expiryData = $user->products()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->select(
                DB::raw('DATE(expiry_date) as date'), 
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(price * quantity) as value')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get products with low stock ordered by how much below threshold they are
        $lowStockProducts = $user->products()
            ->whereRaw('quantity > 0 AND quantity <= low_stock_threshold')
            ->select('*', DB::raw('low_stock_threshold - quantity as deficit'))
            ->orderByDesc('deficit')
            ->get();
    
        return view('seller.reports.inventory', compact(
            'stats',
            'categoryData',
            'expiryData',
            'lowStockProducts'
        ));
    }
}
