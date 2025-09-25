<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        // Filter by rating
        if ($request->has('rating') && $request->rating !== '') {
            $query->where('rating', $request->rating);
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $reviews = $query->paginate(15);
        
        // Statistics
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
            'average_rating' => Review::where('is_approved', true)->avg('rating') ?? 0,
        ];
        
        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display pending reviews.
     */
    public function pending(Request $request)
    {
        $query = Review::with(['user', 'product'])->where('is_approved', false);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.reviews.pending', compact('reviews'));
    }

    /**
     * Display featured reviews.
     */
    public function featured(Request $request)
    {
        // For now, just show top-rated approved reviews until is_featured column is added
        $query = Review::with(['user', 'product'])
                      ->where('is_approved', true)
                      ->where('rating', '>=', 4);
        
        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.reviews.featured', compact('reviews'));
    }

    /**
     * Display review analytics.
     */
    public function analytics()
    {
        $analytics = [
            'total_reviews' => Review::count(),
            'reviews_this_month' => Review::whereMonth('created_at', now()->month)->count(),
            'reviews_this_week' => Review::where('created_at', '>=', now()->subWeek())->count(),
            'reviews_today' => Review::whereDate('created_at', today())->count(),
            
            'average_rating' => Review::where('is_approved', true)->avg('rating') ?? 0,
            'rating_distribution' => Review::where('is_approved', true)
                                          ->select('rating', DB::raw('count(*) as count'))
                                          ->groupBy('rating')
                                          ->orderBy('rating')
                                          ->pluck('count', 'rating'),
            
            'approval_distribution' => [
                'approved' => Review::where('is_approved', true)->count(),
                'pending' => Review::where('is_approved', false)->count(),
            ],
            
            'top_rated_products' => Product::withAvg('reviews', 'rating')
                                          ->having('reviews_avg_rating', '>', 4)
                                          ->orderBy('reviews_avg_rating', 'desc')
                                          ->take(10)
                                          ->get(),
            
            'most_reviewed_products' => Product::withCount('reviews')
                                              ->orderBy('reviews_count', 'desc')
                                              ->take(10)
                                              ->get(),
        ];
        
        return view('admin.reviews.analytics', compact('analytics'));
    }

    /**
     * Update review status.
     */
    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved'
        ]);
        
        $isApproved = $request->status === 'approved';
        $review->update(['is_approved' => $isApproved]);
        
        return response()->json([
            'success' => true,
            'message' => 'Review status updated successfully'
        ]);
    }

    /**
     * Delete review.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }
}
