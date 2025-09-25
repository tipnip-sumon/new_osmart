<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a review.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product.'
            ], 422);
        }

        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        // Create the review
        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'images' => $images,
            'is_approved' => true, // Auto-approve for now
            'is_verified_purchase' => $this->isVerifiedPurchase($product->id, Auth::id())
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $this->formatReviewForResponse($review)
        ]);
    }

    /**
     * Get reviews for a product
     */
    public function index(Product $product, Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $rating = $request->get('rating');
        
        $query = Review::where('product_id', $product->id)
            ->approved()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($rating) {
            $query->where('rating', $rating);
        }

        $reviews = $query->paginate($perPage);

        // Format reviews for response
        $formattedReviews = $reviews->map(function ($review) {
            return $this->formatReviewForResponse($review);
        });

        return response()->json([
            'success' => true,
            'reviews' => $formattedReviews,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'has_more' => $reviews->hasMorePages()
            ],
            'stats' => [
                'average_rating' => Review::averageRating($product->id),
                'total_reviews' => Review::totalCount($product->id),
                'rating_breakdown' => Review::ratingBreakdown($product->id)
            ]
        ]);
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');
        
        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }

    /**
     * Check if user made a verified purchase
     */
    private function isVerifiedPurchase($productId, $userId)
    {
        // Check if user has purchased this product
        // This would depend on your order/purchase system
        // For now, return false
        return false;
    }

    /**
     * Format review for API response
     */
    private function formatReviewForResponse($review)
    {
        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'title' => $review->title,
            'comment' => $review->comment,
            'images' => $review->images ? array_map(function($image) {
                return asset('storage/' . $image);
            }, $review->images) : [],
            'is_verified_purchase' => $review->is_verified_purchase,
            'helpful_count' => $review->helpful_count,
            'created_at' => $review->created_at->format('M d, Y'),
            'formatted_date' => $review->formatted_date,
            'stars_array' => $review->stars_array,
            'user' => [
                'name' => $review->user->name,
                'avatar' => $review->user->avatar ? asset('storage/' . $review->user->avatar) : null
            ]
        ];
    }
}
