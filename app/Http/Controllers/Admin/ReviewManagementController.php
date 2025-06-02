<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewManagementController extends Controller
{
    /**
     * Display a listing of all reviews.
     */
    public function index(Request $request)
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $query = Review::with(['user', 'product']);
        
        // Filter by approval status
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }
        
        $reviews = $query->latest()->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    /**
     * Approve a review.
     */
    public function approve(Review $review)
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $review->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'Review approved successfully.');
    }
    
    /**
     * Reject and delete a review.
     */
    public function reject(Review $review)
    {
        // Only allow admin access
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'Review rejected and removed successfully.');
    }
}
