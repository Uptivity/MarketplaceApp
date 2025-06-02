<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    // Show all products publicly with filtering and sorting
    public function index()
    {
        // Start with only published products
        $query = Product::published();
        
        // Filter by category if provided
        if (request()->has('category') && request('category') != '') {
            $query->where('category_id', request('category'));
        }
        
        // Filter by price range if provided
        if (request()->has('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request()->has('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }
        
        // Sort products
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12); // Show 12 products per page
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        
        return view('public.products.index', compact('products', 'categories'));
    }

    // Show single product detail
    public function show(Product $product)
    {
        // Check if the product is published
        if (!$product->is_published) {
            abort(404, 'Product not found');
        }
        
        // Load reviews for the product
        $reviews = Review::where('product_id', $product->id)
                        ->where('is_approved', true)
                        ->with('user')
                        ->latest()
                        ->get();
        
        // Get average rating
        $avgRating = $reviews->avg('rating') ?: 0;
        
        // Get similar products in the same category (only published ones)
        $similarProducts = Product::published()
                                ->where('category_id', $product->category_id)
                                ->where('id', '!=', $product->id)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();
        
        return view('public.products.show', compact('product', 'reviews', 'avgRating', 'similarProducts'));
    }
    
    /**
     * Show products by category
     */
    public function byCategory(Category $category)
    {
        $query = Product::published()->where('category_id', $category->id);
        
        // Sort products
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('public.products.category', compact('products', 'categories', 'category'));
    }
    
    /**
     * Show featured/recommended products
     */
    public function featured()
    {
        // Get top-rated products with at least 2 reviews
        $topRatedProducts = Product::withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', 4)
            ->having('reviews_count', '>=', 2)
            ->orderByDesc('reviews_avg_rating')
            ->limit(6)
            ->get();
            
        // Get newest products
        $newestProducts = Product::latest()->limit(6)->get();
        
        // Get most popular categories
        $popularCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->where('is_active', true)
            ->limit(6)
            ->get();
            
        return view('public.products.featured', compact('topRatedProducts', 'newestProducts', 'popularCategories'));
    }
}
