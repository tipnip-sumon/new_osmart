<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Controller
{
    public function index(Request $request)
    {
        // Get flash sale products (products on sale)
        $query = Product::where('status', 'active')
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', DB::raw('price'))
            ->with(['category', 'vendor']);
            
        // Try to add review aggregations, fallback if Review model doesn't exist
        try {
            $query = $query->withAvg('reviews', 'rating')->withCount('reviews');
        } catch (\Exception $e) {
            // Reviews table might not exist yet, continue without ratings
        }
        
        $flashSaleProducts = $query->paginate(12);

        $flashSaleProducts->getCollection()->transform(function ($product) {
            $discount = round(((($product->price - $product->sale_price) / $product->price) * 100), 0);
            
            // Handle complex image structure
            $imageUrl = null;
            if (is_array($product->images) && !empty($product->images)) {
                $firstImage = $product->images[0];
                if (is_array($firstImage) && isset($firstImage['urls'])) {
                    $imageUrl = $firstImage['urls']['medium'] ?? $firstImage['urls']['original'] ?? null;
                } else {
                    $imageUrl = $firstImage;
                }
            }
            
            // Get dynamic rating or use actual database values
            $avgRating = $product->reviews_avg_rating ?? $product->average_rating ?? 0;
            $reviewCount = $product->reviews_count ?? $product->review_count ?? 0;
            
            return (object) [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'vendor' => $product->vendor ? $product->vendor->name : 'Store',
                'images' => $product->images,
                'image' => $imageUrl,
                'price' => $product->sale_price,
                'old_price' => $product->price,
                'discount' => $discount,
                'rating' => round($avgRating, 1),
                'average_rating' => round($avgRating, 1),
                'reviews_count' => $reviewCount,
                'total_reviews' => $reviewCount
            ];
        });

        return view('flash-sale', compact('flashSaleProducts'));
    }
}
