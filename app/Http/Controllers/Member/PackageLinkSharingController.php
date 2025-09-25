<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\PackageLinkSharingService;
use App\Models\Product;
use App\Models\PackageLinkSharingSetting;
use App\Models\Plan;
use App\Models\AffiliateLinkShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PackageLinkSharingController extends Controller
{
    protected $linkSharingService;

    public function __construct(PackageLinkSharingService $linkSharingService)
    {
        $this->linkSharingService = $linkSharingService;
    }

    /**
     * Show package link sharing dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $dashboardData = $this->linkSharingService->getUserDashboard($user->id);
        
        if (!$dashboardData['package_settings']) {
            return view('member.link-sharing.no-package');
        }

        return view('member.link-sharing.dashboard', compact('dashboardData'));
    }

    /**
     * Generate and share product link with enhanced response
     */
    public function shareProduct(Request $request)
    {
        $request->validate([
            'product_slug' => 'required|string',
            'platform' => 'string|in:facebook,twitter,whatsapp,telegram,email,copy,manual,instant_share'
        ]);

        $user = Auth::user();
        $result = $this->linkSharingService->shareProductLink(
            $user->id,
            $request->product_slug,
            $request->platform ?? 'manual'
        );

        if ($request->ajax()) {
            // Add additional stats for real-time updates
            if ($result['success']) {
                $dashboardData = $this->linkSharingService->getUserDashboard($user->id);
                
                $result['stats'] = [
                    'shares_remaining' => ($dashboardData['package_settings']->daily_share_limit ?? 0) - ($dashboardData['today_stats']->shares_count ?? 0),
                    'earnings_today' => $dashboardData['today_stats']->earnings_amount ?? 0,
                    'earnings_limit' => $dashboardData['package_settings']->daily_earning_limit ?? 0,
                    'shares_used' => $dashboardData['today_stats']->shares_count ?? 0,
                    'share_limit' => $dashboardData['package_settings']->daily_share_limit ?? 0,
                    'reward_per_click' => $dashboardData['package_settings']->click_reward_amount ?? 0
                ];
                
                $result['shares_remaining'] = $result['stats']['shares_remaining'];
                $result['earnings_today'] = $result['stats']['earnings_today'];
                $result['earnings_limit'] = $result['stats']['earnings_limit'];
            }
            
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message'])
                        ->with('affiliate_link', $result['affiliate_link']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Get available products for sharing with enhanced search and popular products
     */
    public function getProducts(Request $request)
    {
        try {
            // Start with basic query
            $query = Product::query();

            // Handle popular products request
            if ($request->filled('popular') && $request->boolean('popular')) {
                // Get featured or recent products for popular section
                $products = Product::where('status', 'active')
                                 ->orderBy('created_at', 'desc')
                                 ->select('id', 'name', 'slug', 'sale_price', 'price', 'images')
                                 ->limit($request->get('limit', 12))
                                 ->get();
            } else {
                // Regular search
                $searchTerm = $request->get('search', '');
                
                $query->where('status', 'active');
                
                if (!empty($searchTerm)) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                }

                $products = $query->orderBy('created_at', 'desc')
                                 ->select('id', 'name', 'slug', 'sale_price', 'price', 'images')
                                 ->limit($request->get('limit', 12))
                                 ->get();
            }

            // Format data for response
            $formattedProducts = [];
            foreach ($products as $product) {
                $image = null;
                
                if ($product->images) {
                    $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                    if (is_array($images) && count($images) > 0) {
                        $firstImage = $images[0];
                        
                        // Handle complex nested structure first
                        if (is_array($firstImage)) {
                            // Try different size variants in order of preference
                            // Check if files actually exist before using storage_url
                            if (isset($firstImage['sizes']['medium']['storage_url'])) {
                                $storagePath = str_replace([url('storage/'), asset('storage/')], '', $firstImage['sizes']['medium']['storage_url']);
                                $storagePath = ltrim($storagePath, '/');
                                if (file_exists(storage_path('app/public/' . $storagePath))) {
                                    $image = $firstImage['sizes']['medium']['storage_url'];
                                }
                            }
                            
                            if (!$image && isset($firstImage['sizes']['original']['storage_url'])) {
                                $storagePath = str_replace([url('storage/'), asset('storage/')], '', $firstImage['sizes']['original']['storage_url']);
                                $storagePath = ltrim($storagePath, '/');
                                if (file_exists(storage_path('app/public/' . $storagePath))) {
                                    $image = $firstImage['sizes']['original']['storage_url'];
                                }
                            }
                            
                            if (!$image && isset($firstImage['sizes']['large']['storage_url'])) {
                                $storagePath = str_replace([url('storage/'), asset('storage/')], '', $firstImage['sizes']['large']['storage_url']);
                                $storagePath = ltrim($storagePath, '/');
                                if (file_exists(storage_path('app/public/' . $storagePath))) {
                                    $image = $firstImage['sizes']['large']['storage_url'];
                                }
                            }
                            
                            // If no storage_url files exist, try the 'url' field (direct-storage)
                            if (!$image && isset($firstImage['sizes']['medium']['url'])) {
                                $image = $firstImage['sizes']['medium']['url'];
                            } elseif (!$image && isset($firstImage['sizes']['original']['url'])) {
                                $image = $firstImage['sizes']['original']['url'];
                            } elseif (!$image && isset($firstImage['sizes']['large']['url'])) {
                                $image = $firstImage['sizes']['large']['url'];
                            } elseif (!$image && isset($firstImage['urls']['medium'])) {
                                $image = $firstImage['urls']['medium'];
                            } elseif (!$image && isset($firstImage['urls']['original'])) {
                                $image = $firstImage['urls']['original'];
                            } elseif (!$image && isset($firstImage['url']) && is_string($firstImage['url'])) {
                                $image = $firstImage['url'];
                            } elseif (!$image && isset($firstImage['path']) && is_string($firstImage['path'])) {
                                // For path, check if file exists in storage first
                                if (file_exists(storage_path('app/public/' . $firstImage['path']))) {
                                    $image = asset('storage/' . $firstImage['path']);
                                } else {
                                    // Fallback to direct-storage route
                                    $image = url('direct-storage/' . $firstImage['path']);
                                }
                            }
                        } elseif (is_string($firstImage)) {
                            // Simple string path
                            if (str_starts_with($firstImage, 'http')) {
                                $image = $firstImage;
                            } else {
                                // Check if file exists in storage first
                                if (file_exists(storage_path('app/public/' . $firstImage))) {
                                    $image = asset('storage/' . $firstImage);
                                } else {
                                    // Fallback to direct-storage route
                                    $image = url('direct-storage/' . $firstImage);
                                }
                            }
                        }
                    }
                }
                
                // Fallback to single image field if no images array worked
                if (!$image && $product->image) {
                    $productImage = $product->image;
                    if ($productImage && $productImage !== 'products/product1.jpg') {
                        if (str_starts_with($productImage, 'http')) {
                            $image = $productImage;
                        } else {
                            // Check if file exists in storage first
                            if (file_exists(storage_path('app/public/' . $productImage))) {
                                $image = asset('storage/' . $productImage);
                            } else {
                                // Fallback to direct-storage route
                                $image = url('direct-storage/' . $productImage);
                            }
                        }
                    }
                }
                
                // Final fallback to default image
                if (!$image) {
                    // Use a random product image from assets as fallback instead of default
                    $availableImages = [
                        'assets/img/product/1.png',
                        'assets/img/product/2.png', 
                        'assets/img/product/3.png',
                        'assets/img/product/4.png',
                        'assets/img/product/5.png',
                        'assets/img/product/6.png',
                        'assets/img/product/7.png',
                        'assets/img/product/8.png',
                        'assets/img/product/9.png',
                        'assets/img/product/10.png'
                    ];
                    
                    // Use product ID to consistently assign same image to same product
                    $imageIndex = $product->id % count($availableImages);
                    $image = asset($availableImages[$imageIndex]);
                }
                
                $formattedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sale_price' => $product->sale_price ?? $product->price ?? 0,
                    'price' => $product->price ?? 0,
                    'image' => $image,
                    'stock_quantity' => 100, // Default stock
                    'category' => 'General'
                ];
            }

            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
                'count' => count($formattedProducts),
                'search_term' => $request->get('search', ''),
                'is_popular' => $request->boolean('popular', false)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Product fetch error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load products. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get user's sharing history
     */
    public function sharingHistory(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);
        
        $query = \App\Models\AffiliateLinkShare::where('user_id', $user->id)
            ->with(['product']);

        // Apply date range filter
        if ($request->filled('date_range')) {
            $dateRange = $request->get('date_range');
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('share_date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('share_date', \Carbon\Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('share_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'last_week':
                    $query->whereBetween('share_date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereBetween('share_date', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
                case 'last_month':
                    $query->whereBetween('share_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]);
                    break;
            }
        }

        // Apply platform filter
        if ($request->filled('platform')) {
            $query->where('shared_platform', $request->get('platform'));
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('product_slug', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $shares = $query->orderBy('share_date', 'desc')->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'shares' => $shares
            ]);
        }

        return view('member.link-sharing.history', compact('shares'));
    }

    /**
     * Get sharing statistics
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        
        // Get basic dashboard data
        $dashboardData = $this->linkSharingService->getUserDashboard($user->id);
        $todayStats = $dashboardData['today_stats'];
        
        // Weekly stats
        $weeklyStats = \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
            ->whereBetween('stat_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->selectRaw('SUM(shares_count) as total_shares, SUM(earnings_amount) as total_earnings')
            ->first();
            
        // Monthly stats
        $monthlyStats = \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
            ->whereBetween('stat_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('SUM(shares_count) as total_shares, SUM(earnings_amount) as total_earnings')
            ->first();
            
        // All time stats
        $allTimeStats = \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
            ->selectRaw('SUM(shares_count) as total_shares, SUM(earnings_amount) as total_earnings')
            ->first();
            
        // Top performing products
        $topProducts = \App\Models\AffiliateLinkShare::where('user_id', $user->id)
            ->selectRaw('product_slug, COUNT(*) as share_count, SUM(clicks_count) as total_clicks, SUM(earnings_amount) as total_earnings')
            ->groupBy('product_slug')
            ->orderBy('total_earnings', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                $product = \App\Models\Product::where('slug', $item->product_slug)->first();
                $item->product_name = $product ? $product->name : 'Unknown Product';
                return $item;
            });
            
        // Performance metrics
        $totalShares = \App\Models\AffiliateLinkShare::where('user_id', $user->id)->count();
        $totalClicks = \App\Models\AffiliateLinkShare::where('user_id', $user->id)->sum('clicks_count');
        $totalEarnings = \App\Models\AffiliateLinkShare::where('user_id', $user->id)->sum('earnings_amount');
        
        $performanceMetrics = [
            'avg_clicks_per_share' => $totalShares > 0 ? $totalClicks / $totalShares : 0,
            'avg_earnings_per_share' => $totalShares > 0 ? $totalEarnings / $totalShares : 0,
            'click_through_rate' => $totalShares > 0 ? ($totalClicks / $totalShares) * 100 : 0,
            'conversion_rate' => $totalClicks > 0 ? ($totalEarnings / $totalClicks) * 100 : 0,
            'best_day' => \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
                ->orderBy('earnings_amount', 'desc')
                ->value('stat_date')
        ];
        
        // Daily chart data (last 7 days)
        $dailyData = \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
            ->whereBetween('stat_date', [now()->subDays(6), now()])
            ->orderBy('stat_date', 'asc')
            ->get();
            
        $dailyChartData = [
            'labels' => $dailyData->map(fn($item) => $item->stat_date->format('M d'))->toArray(),
            'shares' => $dailyData->pluck('shares_count')->toArray(),
            'clicks' => $dailyData->pluck('clicks_count')->toArray(),
            'earnings' => $dailyData->pluck('earnings_amount')->toArray(),
        ];
        
        // Platform distribution
        $platformData = \App\Models\AffiliateLinkShare::where('user_id', $user->id)
            ->selectRaw('shared_platform, COUNT(*) as count')
            ->groupBy('shared_platform')
            ->get();
            
        $platformChartData = [
            'labels' => $platformData->pluck('shared_platform')->map(fn($p) => ucfirst($p))->toArray(),
            'data' => $platformData->pluck('count')->toArray(),
        ];
        
        // Monthly breakdown
        $monthlyBreakdown = \App\Models\DailyLinkSharingStat::where('user_id', $user->id)
            ->selectRaw('
                YEAR(stat_date) as year, 
                MONTH(stat_date) as month, 
                SUM(shares_count) as total_shares,
                SUM(clicks_count) as total_clicks,
                SUM(unique_clicks_count) as unique_clicks,
                SUM(earnings_amount) as total_earnings,
                AVG(shares_count) as avg_daily_shares
            ')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->map(function($item) {
                $item->month_name = Carbon::create($item->year, $item->month, 1)->format('M Y');
                $item->click_rate = $item->total_shares > 0 ? ($item->total_clicks / $item->total_shares) * 100 : 0;
                return $item;
            });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'todayStats' => $todayStats,
                'weeklyStats' => $weeklyStats,
                'monthlyStats' => $monthlyStats,
                'allTimeStats' => $allTimeStats,
                'topProducts' => $topProducts,
                'performanceMetrics' => $performanceMetrics,
                'dailyChartData' => $dailyChartData,
                'platformChartData' => $platformChartData,
                'monthlyBreakdown' => $monthlyBreakdown
            ]);
        }

        return view('member.link-sharing.stats', compact(
            'todayStats', 'weeklyStats', 'monthlyStats', 'allTimeStats',
            'topProducts', 'performanceMetrics', 'dailyChartData', 
            'platformChartData', 'monthlyBreakdown'
        ));
    }

    /**
     * Show package upgrade options
     */
    public function packageUpgrade()
    {
        $user = Auth::user();
        
        // Get all available plans
        $plans = Plan::where('is_active', true)
                    ->orderBy('fixed_amount', 'asc')
                    ->get();

        // Get package settings for all plans
        $packageSettings = PackageLinkSharingSetting::with('plan')
            ->where('is_active', true)
            ->get();

        // Get current package settings if user has active plan
        $currentSettings = null;
        if ($user->activePlan) {
            $currentSettings = $packageSettings->where('plan_id', $user->activePlan->id)->first();
        }

        // Calculate total earnings for current user
        $totalEarnings = AffiliateLinkShare::where('user_id', $user->id)
                                         ->sum('earnings_amount');

        return view('member.link-sharing.upgrade', compact(
            'plans', 
            'packageSettings', 
            'currentSettings', 
            'totalEarnings'
        ));
    }

    /**
     * Track affiliate click (public endpoint)
     */
    public function trackClick(Request $request)
    {
        $affiliateId = $request->get('aff');
        $productSlug = $request->get('product', $request->route('product'));
        
        if (!$affiliateId || !$productSlug) {
            return response()->json(['success' => false]);
        }

        $clickerInfo = [
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'shared_url' => $request->fullUrl()
        ];

        $result = $this->linkSharingService->processAffiliateClick($affiliateId, $productSlug, $clickerInfo);

        return response()->json($result);
    }

    /**
     * Generate social sharing URLs
     */
    public function getSocialSharingUrls(Request $request)
    {
        $request->validate([
            'affiliate_link' => 'required|url',
            'product_name' => 'required|string'
        ]);

        $affiliateLink = $request->affiliate_link;
        $productName = $request->product_name;
        $message = "Check out this amazing product: {$productName}";

        $socialUrls = [
            'facebook' => [
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($affiliateLink),
                'name' => 'Facebook',
                'icon' => 'fab fa-facebook-f',
                'color' => '#3b5998'
            ],
            'twitter' => [
                'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($affiliateLink) . '&text=' . urlencode($message),
                'name' => 'Twitter',
                'icon' => 'fab fa-twitter',
                'color' => '#1da1f2'
            ],
            'whatsapp' => [
                'url' => 'https://wa.me/?text=' . urlencode($message . ' ' . $affiliateLink),
                'name' => 'WhatsApp',
                'icon' => 'fab fa-whatsapp',
                'color' => '#25d366'
            ],
            'telegram' => [
                'url' => 'https://t.me/share/url?url=' . urlencode($affiliateLink) . '&text=' . urlencode($message),
                'name' => 'Telegram',
                'icon' => 'fab fa-telegram',
                'color' => '#0088cc'
            ],
            'linkedin' => [
                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($affiliateLink),
                'name' => 'LinkedIn',
                'icon' => 'fab fa-linkedin-in',
                'color' => '#0077b5'
            ]
        ];

        return response()->json([
            'success' => true,
            'social_urls' => $socialUrls
        ]);
    }
}
