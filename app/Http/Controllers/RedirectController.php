<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class RedirectController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        
        if (!$user) {
            // This would only happen if the auth middleware isn't working correctly
            return redirect()->route('login')->with('error', 'You must be logged in to access the dashboard');
        }
        
        // Get user-specific data for dashboard
        $data = $this->getDashboardData($user);
        
        // Return dashboard view with consistent data regardless of role
        return view('dashboard', $data);
    }
    
    private function getDashboardData($user)
    {
        $data = [
            'user' => $user
        ];
        
        // Recent orders for this user
        $data['recentOrders'] = Order::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
            
        // Get recommended products based on categories of previous orders
        $purchasedProductIds = OrderItem::whereHas('order', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->pluck('product_title');
        
        // Get categories from purchased products for recommendations
        $purchasedCategories = Product::whereIn('title', $purchasedProductIds)
            ->pluck('category_id')
            ->unique()
            ->toArray();
            
        // Get recommended products (same categories but not purchased yet)
        $data['recommendedProducts'] = Product::whereIn('category_id', $purchasedCategories)
            ->whereNotIn('title', $purchasedProductIds)
            ->where('quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
            
        // If no recommendations based on purchases, get popular products
        if ($data['recommendedProducts']->count() === 0) {
            $data['recommendedProducts'] = Product::where('quantity', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        }
        
        // Get popular categories
        $data['popularCategories'] = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(6)
            ->get();
            
        // Get user's pending reviews (orders without reviews)
        $data['pendingReviews'] = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('reviews')
            ->with('items.product')
            ->latest()
            ->limit(3)
            ->get();
            
        // Get user's recent notifications
        $data['recentNotifications'] = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
            
        // Calculate profile completion percentage
        $data['profileCompletion'] = $this->calculateProfileCompletion($user);
        
        // Account age
        $data['accountAge'] = Carbon::parse($user->created_at)->diffForHumans();
            
        return $data;
    }
    
    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => !empty($user->name),
            'email_verified' => !empty($user->email_verified_at),
            'profile_photo' => !empty($user->profile_photo_path),
            'shipping_address' => !empty($user->profile) && !empty($user->profile->address),
            'phone' => !empty($user->profile) && !empty($user->profile->phone)
        ];
        
        $completedFields = array_filter($fields, function($value) {
            return $value === true;
        });
        
        return (count($completedFields) / count($fields)) * 100;
    }
}
