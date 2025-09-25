<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\UserFavorite;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display the main products page
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            // Get products with filters
            $query = Product::where('status', 'active')
                ->with(['category', 'brand', 'vendor']);

            // Apply category filter
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            // Apply brand filter
            if ($request->filled('brand')) {
                $query->where('brand_id', $request->brand);
            }

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply price range filter
            if ($request->filled('min_price')) {
                $query->where('sale_price', '>=', $request->min_price);
            }
            
            if ($request->filled('max_price')) {
                $query->where('sale_price', '<=', $request->max_price);
            }

            // Apply sorting
            $sortBy = $request->get('sort', 'created_at');
            $sortOrder = $request->get('order', 'desc');
            
            switch ($sortBy) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_low':
                    $query->orderBy('sale_price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('sale_price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'popularity':
                    $query->withCount('orderItems')
                          ->orderBy('order_items_count', 'desc');
                    break;
                default:
                    $query->orderBy($sortBy, $sortOrder);
            }

            $products = $query->paginate(12);

            // Get additional data
            $categories = Category::where('status', 'active')
                ->withCount('products')
                ->orderBy('name')
                ->get();

            $brands = Brand::where('status', 'active')
                ->withCount('products')
                ->orderBy('name')
                ->get();
            // Get user statistics (with null safety for non-authenticated users)
            $favoriteProducts = $user ? UserFavorite::where('user_id', $user->id)->count() : 0;
            $memberCommission = $user ? $this->calculateMemberCommissions($user) : 0;
            $sharedProducts = $user ? $this->getSharedProductsCount($user) : 0;
            $favoriteProductIds = [];
            
            // Mark favorite products
            if ($user) {
                $favoriteProductIds = UserFavorite::where('user_id', $user->id)
                    ->pluck('product_id')
                    ->toArray();
                
                foreach ($products as $product) {
                    $product->is_favorite = in_array($product->id, $favoriteProductIds);
                }
            }
            // If AJAX request, return filtered products
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'products' => view('member.partials.product-grid', compact('products', 'favoriteProductIds'))->render(),
                    'pagination' => $products->appends($request->all())->links('pagination::bootstrap-4')->render(),
                    'total' => $products->total(),
                    'count' => $products->count()
                ]);
            }
            return view('member.products.index', compact(
                'products', 
                'categories', 
                'brands', 
                'favoriteProducts', 
                'memberCommission', 
                'sharedProducts',
                'favoriteProductIds'
            ));

        } catch (\Exception $e) {
            Log::error('Member Products Index Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load products'
                ], 500);
            }
            
            return back()->with('error', 'Failed to load products. Please try again.');
        }
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        try {
            $user = Auth::user();
            
            // Check if product is active
            if ($product->status !== 'active') {
                abort(404, 'Product not found');
            }

            // Load relationships
            $product->load(['category', 'brand', 'vendor', 'reviews.user']);

            // Check if user has favorited this product
            $product->is_favorite = $user ? 
                UserFavorite::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists() : false;

            // Get related products
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->with(['category', 'brand'])
                ->limit(8)
                ->get();

            // Generate affiliate link
            $affiliateLink = $this->generateAffiliateLink($product, $user);

            return view('member.products.show', compact(
                'product', 
                'relatedProducts', 
                'affiliateLink'
            ));

        } catch (\Exception $e) {
            Log::error('Member Product Show Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load product details.');
        }
    }

    /**
     * Add product to favorites
     */
    public function addToFavorites(Request $request)
    {
        try {
            $user = Auth::user();
            $productId = $request->input('product_id');

            $product = Product::findOrFail($productId);

            $favorite = UserFavorite::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);

            $isFavorite = $favorite->wasRecentlyCreated;

            if (!$isFavorite) {
                // Remove from favorites if already exists
                $favorite->delete();
                $message = 'Product removed from favorites';
                $isFavorite = false;
            } else {
                $message = 'Product added to favorites';
                $isFavorite = true;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => $isFavorite
            ]);

        } catch (\Exception $e) {
            Log::error('Add to Favorites Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update favorites'
            ], 500);
        }
    }

    /**
     * Remove product from favorites
     */
    public function removeFromFavorites(Request $request)
    {
        try {
            $user = Auth::user();
            $productId = $request->input('product_id');

            UserFavorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from favorites'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove from Favorites Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove from favorites'
            ], 500);
        }
    }

    /**
     * Bulk remove favorites
     */
    public function bulkRemoveFavorites(Request $request)
    {
        try {
            $user = Auth::user();
            $productIds = $request->input('product_ids', []);

            if (empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products selected'
                ], 400);
            }

            $removed = UserFavorite::where('user_id', $user->id)
                ->whereIn('product_id', $productIds)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Removed {$removed} products from favorites"
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk Remove Favorites Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove favorites'
            ], 500);
        }
    }

    /**
     * Toggle favorite status for a product
     */
    public function toggleFavorite(Request $request)
    {
        try {
            $user = Auth::user();
            $productId = $request->input('product_id');

            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }

            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Check if already favorited
            $existingFavorite = UserFavorite::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingFavorite) {
                // Remove from favorites
                $existingFavorite->delete();
                $isFavorite = false;
                $message = 'Product removed from favorites';
            } else {
                // Add to favorites
                UserFavorite::create([
                    'user_id' => $user->id,
                    'product_id' => $productId
                ]);
                $isFavorite = true;
                $message = 'Product added to favorites';
            }

            return response()->json([
                'success' => true,
                'favorited' => $isFavorite,
                'is_favorite' => $isFavorite, // Keep both for compatibility
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle Favorite Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update favorites'
            ], 500);
        }
    }

    /**
     * Bulk generate affiliate links
     */
    public function bulkGenerateAffiliateLinks(Request $request)
    {
        try {
            $user = Auth::user();
            $productIds = $request->input('product_ids', []);

            if (empty($productIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products selected'
                ], 400);
            }

            $products = Product::whereIn('id', $productIds)
                ->where('status', 'active')
                ->get();

            $affiliateLinks = [];
            foreach ($products as $product) {
                $affiliateLinks[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'link' => $this->generateAffiliateLink($product, $user)
                ];
            }

            return response()->json([
                'success' => true,
                'affiliate_links' => $affiliateLinks,
                'message' => 'Affiliate links generated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk Generate Affiliate Links Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate affiliate links'
            ], 500);
        }
    }

    /**
     * Show user's favorite products
     */
    public function favorites(Request $request)
    {
        try {
            $user = Auth::user();

            // Get favorites with filters
            $query = UserFavorite::where('user_id', $user->id)
                ->with(['product' => function($q) {
                    $q->where('status', 'active')
                      ->with(['category', 'brand', 'vendor']);
                }]);

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->whereHas('product', function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply category filter
            if ($request->filled('category')) {
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('category_id', $request->category);
                });
            }

            // Apply sorting
            $sortBy = $request->get('sort', 'newest');
            switch ($sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_low':
                    $query->join('products', 'user_favorites.product_id', '=', 'products.id')
                          ->orderBy('products.sale_price', 'asc')
                          ->select('user_favorites.*');
                    break;
                case 'price_high':
                    $query->join('products', 'user_favorites.product_id', '=', 'products.id')
                          ->orderBy('products.sale_price', 'desc')
                          ->select('user_favorites.*');
                    break;
                case 'name':
                    $query->join('products', 'user_favorites.product_id', '=', 'products.id')
                          ->orderBy('products.name', 'asc')
                          ->select('user_favorites.*');
                    break;
                default: // newest
                    $query->orderBy('created_at', 'desc');
            }

            $perPage = $request->get('per_page', 12);
            $favorites = $query->paginate($perPage);

            // Filter out favorites with inactive products
            $favorites->getCollection()->transform(function ($favorite) {
                if (!$favorite->product || $favorite->product->status !== 'active') {
                    return null;
                }
                $favorite->product->is_favorite = true;
                return $favorite;
            })->filter();

            // Get all favorites for statistics (without pagination)
            $allFavorites = UserFavorite::where('user_id', $user->id)
                ->with(['product' => function($q) {
                    $q->where('status', 'active')
                      ->with(['category', 'brand', 'vendor']);
                }])
                ->get()
                ->filter(function($favorite) {
                    return $favorite->product && $favorite->product->status === 'active';
                });

            // Get categories for filter
            $categories = Category::where('status', 'active')
                ->whereHas('products.userFavorites', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->orderBy('name')
                ->get();

            // Calculate statistics from all favorites
            $totalValue = $allFavorites->sum(function($favorite) {
                return $favorite->product ? ($favorite->product->sale_price ?: $favorite->product->price ?: 0) : 0;
            });

            $avgPrice = $allFavorites->count() > 0 ? ($totalValue / $allFavorites->count()) : 0;

            $stats = [
                'total_favorites' => $allFavorites->count(),
                'categories' => $allFavorites->pluck('product.category_id')->filter()->unique()->count(),
                'total_value' => $totalValue,
                'avg_price' => $avgPrice
            ];

            return view('member.products.favorites', compact('favorites', 'categories', 'stats'));

        } catch (\Exception $e) {
            Log::error('Member Favorites Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load favorite products.');
        }
    }

    /**
     * Generate affiliate link for product
     */
    public function getAffiliateLink(Request $request)
    {
        try {
            $user = Auth::user();
            $productId = $request->input('product_id');

            $product = Product::findOrFail($productId);
            $affiliateLink = $this->generateAffiliateLink($product, $user);

            return response()->json([
                'success' => true,
                'affiliate_link' => $affiliateLink,
                'message' => 'Affiliate link generated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Generate Affiliate Link Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate affiliate link'
            ], 500);
        }
    }

    /**
     * Quick view product details (AJAX)
     */
    public function quickView(Product $product)
    {
        try {
            $user = Auth::user();
            
            $product->load(['category', 'brand', 'vendor']);
            
            $product->is_favorite = $user ? 
                UserFavorite::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists() : false;

            return response()->json([
                'success' => true,
                'product' => $product,
                'affiliate_link' => $this->generateAffiliateLink($product, $user)
            ]);

        } catch (\Exception $e) {
            Log::error('Quick View Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load product details'
            ], 500);
        }
    }

    /**
     * Search products (AJAX)
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $limit = $request->input('limit', 10);

            $products = Product::where('status', 'active')
                ->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
                })
                ->with(['category', 'brand'])
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('Product Search Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }

    /**
     * Helper method to calculate member commissions
     */
    private function calculateMemberCommissions(User $user)
    {
        try {
            // Check if commissions table exists and has data
            if (!Schema::hasTable('commissions')) {
                return 0;
            }
            
            return DB::table('commissions')
                ->where('user_id', $user->id)
                ->whereIn('commission_type', ['affiliate', 'product', 'referral'])
                ->sum('commission_amount') ?? 0;
        } catch (\Exception $e) {
            // Log the error and return 0 if table doesn't exist or query fails
            Log::warning('Commission calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Helper method to get shared products count
     */
    private function getSharedProductsCount(User $user)
    {
        try {
            // Check if affiliate_clicks table exists
            if (!Schema::hasTable('affiliate_clicks')) {
                return 0;
            }
            
            return DB::table('affiliate_clicks')
                ->where('affiliate_id', $user->id)
                ->distinct('product_id')
                ->count() ?? 0;
        } catch (\Exception $e) {
            // Log the error and return 0 if table doesn't exist or query fails
            Log::warning('Shared products count calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Helper method to generate affiliate link
     */
    private function generateAffiliateLink(Product $product, User $user)
    {
        if (!$user) {
            return url("/products/{$product->slug}");
        }

        // Generate a more sophisticated affiliate link with tracking
        $baseUrl = url("/products/{$product->slug}");
        
        // Add affiliate parameters
        $params = [
            'ref' => $user->username,
            'aff' => $user->id,
            'utm_source' => 'affiliate',
            'utm_medium' => 'link',
            'utm_campaign' => 'product_share',
            'utm_content' => $product->slug
        ];
        
        // Create the query string
        $queryString = http_build_query($params);
        
        return $baseUrl . '?' . $queryString;
    }

    /**
     * Show shared products page
     */
    public function sharedProducts(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->get('per_page', 12);
            
            // Get products that have been shared via affiliate links
            $sharedProducts = collect();
            
            try {
                // Check if affiliate_clicks table exists
                if (Schema::hasTable('affiliate_clicks')) {
                    // Get product IDs with their click counts
                    $productClickCounts = DB::table('affiliate_clicks')
                        ->where('affiliate_id', $user->id)
                        ->select('product_id', DB::raw('COUNT(*) as click_count'))
                        ->groupBy('product_id')
                        ->pluck('click_count', 'product_id');
                    
                    $productIds = $productClickCounts->keys();
                    
                    if ($productIds->isNotEmpty()) {
                        $sharedProducts = Product::whereIn('id', $productIds)
                            ->with(['category', 'brand'])
                            ->active()
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
                        
                        // Add click counts to each product
                        $sharedProducts->getCollection()->transform(function ($product) use ($productClickCounts) {
                            $product->click_count = $productClickCounts->get($product->id, 0);
                            return $product;
                        });
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Shared products query failed: ' . $e->getMessage());
                $sharedProducts = collect();
            }
            
            // Get sharing statistics
            $stats = [
                'total_shared' => $sharedProducts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $sharedProducts->total() : 0,
                'total_clicks' => $this->getAffiliateClicks($user),
                'this_month_clicks' => $this->getAffiliateClicks($user, 'month'),
                'total_commission' => $this->calculateMemberCommissions($user)
            ];

            return view('member.products.shared', compact('sharedProducts', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Shared Products Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load shared products.');
        }
    }

    /**
     * Show product commissions page
     */
    public function productCommissions(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->get('per_page', 15);
            
            // Get commission data
            $commissions = collect();
            
            try {
                // Check if commissions table exists
                if (Schema::hasTable('commissions')) {
                    $commissions = DB::table('commissions')
                        ->leftJoin('products', 'commissions.product_id', '=', 'products.id')
                        ->leftJoin('orders', 'commissions.order_id', '=', 'orders.id')
                        ->where('commissions.user_id', $user->id)
                        ->whereIn('commissions.commission_type', ['affiliate', 'product', 'referral'])
                        ->select([
                            'commissions.*',
                            'products.name as product_name',
                            'products.slug as product_slug',
                            'products.image as product_image',
                            'orders.order_number'
                        ])
                        ->orderBy('commissions.created_at', 'desc')
                        ->paginate($perPage);
                }
            } catch (\Exception $e) {
                Log::warning('Commission query failed: ' . $e->getMessage());
                $commissions = collect();
            }
            
            // Get commission statistics
            $stats = [
                'total_commission' => $this->calculateMemberCommissions($user),
                'this_month_commission' => $this->getMonthlyCommissions($user),
                'pending_commission' => $this->getPendingCommissions($user),
                'paid_commission' => $this->getPaidCommissions($user)
            ];

            return view('member.products.commissions', compact('commissions', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Product Commissions Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load commission data.');
        }
    }

    /**
     * Get affiliate clicks count
     */
    private function getAffiliateClicks(User $user, $period = null)
    {
        try {
            if (!Schema::hasTable('affiliate_clicks')) {
                return 0;
            }
            
            $query = DB::table('affiliate_clicks')->where('affiliate_id', $user->id);
            
            if ($period === 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }
            
            return $query->count() ?? 0;
        } catch (\Exception $e) {
            Log::warning('Affiliate clicks calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get monthly commissions
     */
    private function getMonthlyCommissions(User $user)
    {
        try {
            if (!Schema::hasTable('commissions')) {
                return 0;
            }
            
            return DB::table('commissions')
                ->where('user_id', $user->id)
                ->whereIn('commission_type', ['affiliate', 'product', 'referral'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('commission_amount') ?? 0;
        } catch (\Exception $e) {
            Log::warning('Monthly commissions calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get pending commissions
     */
    private function getPendingCommissions(User $user)
    {
        try {
            if (!Schema::hasTable('commissions')) {
                return 0;
            }
            
            return DB::table('commissions')
                ->where('user_id', $user->id)
                ->whereIn('commission_type', ['affiliate', 'product', 'referral'])
                ->where('status', 'pending')
                ->sum('commission_amount') ?? 0;
        } catch (\Exception $e) {
            Log::warning('Pending commissions calculation failed: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get paid commissions
     */
    private function getPaidCommissions(User $user)
    {
        try {
            if (!Schema::hasTable('commissions')) {
                return 0;
            }
            
            return DB::table('commissions')
                ->where('user_id', $user->id)
                ->whereIn('commission_type', ['affiliate', 'product', 'referral'])
                ->where('status', 'paid')
                ->sum('commission_amount') ?? 0;
        } catch (\Exception $e) {
            Log::warning('Paid commissions calculation failed: ' . $e->getMessage());
            return 0;
        }
    }
}
