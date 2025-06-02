<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display reviews for a product.
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->paginate(10);
            
        return view('reviews.index', compact('product', 'reviews'));
    }    /**
     * Show the form to create a review for a product.
     */
    public function create(Request $request, Product $product = null)
    {
        // If product is not provided directly but we have IDs in request
        if (!$product && $request->has('product')) {
            $product = Product::findOrFail($request->product);
        }
        
        $order = null;
        if ($request->has('order')) {
            $order = Order::findOrFail($request->order);
        }
        
        // Make sure we have a product
        if (!$product) {
            return redirect()->route('dashboard')->with('error', 'Product not found.');
        }
          // Check if user has purchased the product
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_title', $product->title);
            })
            ->exists();
            
        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }
        
        return view('reviews.create', compact('product', 'order'));
    }    /**
     * Store a new review.
     */
    public function store(Request $request, Product $product)
    {        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
        ]);
        
        // Check if user has already reviewed this product for this order
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('order_id', $request->order_id)
            ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product for this order.');
        }
          // Check if buyer has purchased the product
        $hasPurchased = Auth::user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_title', $product->title);
            })
            ->exists();
            
        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }        // Auto-approve if admin, otherwise set to pending
        $isApproved = Auth::user()->role === 'admin';
          Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $request->order_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => $isApproved,
        ]);
          $message = $isApproved 
            ? 'Review submitted successfully!' 
            : 'Review submitted and pending approval. Thank you!';
            
        return redirect()->route('public.products.show', $product)->with('success', $message);
    }

    /**
     * Admin method to approve a review.
     */
    public function approve(Review $review)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $review->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    /**
     * Admin method to reject a review.
     */
    public function reject(Review $review)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'Review rejected and removed successfully.');
    }
}
