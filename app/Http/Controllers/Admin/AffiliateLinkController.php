<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\Commission;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateLinkController extends Controller
{
    /**
     * Display a listing of shared affiliate links
     */
    public function index(Request $request)
    {
        // Get products that have been shared (have affiliate clicks)
        $query = Product::withCount(['affiliateClicks as total_clicks'])
                       ->withCount(['commissions as total_commissions' => function($q) {
                           $q->where('commission_type', 'affiliate');
                       }])
                       ->withSum(['commissions as total_earnings' => function($q) {
                           $q->where('commission_type', 'affiliate')
                             ->where('status', 'approved');
                       }], 'commission_amount')
                       ->having('total_clicks', '>', 0);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $sharedProducts = $query->orderBy('total_clicks', 'desc')->paginate(20);

        // Get summary statistics
        $stats = [
            'total_shared_products' => Product::whereHas('affiliateClicks')->count(),
            'total_clicks' => AffiliateClick::count(),
            'total_commissions' => Commission::where('commission_type', 'affiliate')->count(),
            'total_earnings' => Commission::where('commission_type', 'affiliate')
                                        ->where('status', 'approved')
                                        ->sum('commission_amount'),
        ];

        // Get most active affiliates
        $topAffiliates = User::withCount(['affiliateClicks as clicks_count'])
                            ->withSum(['commissions as earnings' => function($q) {
                                $q->where('commission_type', 'affiliate')
                                  ->where('status', 'approved');
                            }], 'commission_amount')
                            ->having('clicks_count', '>', 0)
                            ->orderBy('clicks_count', 'desc')
                            ->limit(10)
                            ->get();

        return view('admin.affiliate-links.index', compact('sharedProducts', 'stats', 'topAffiliates'));
    }

    /**
     * Show detailed analytics for a specific product's affiliate performance
     */
    public function show(Product $product)
    {
        $product->load(['category', 'brand']);

        // Get click analytics
        $clickStats = [
            'total_clicks' => $product->affiliateClicks()->count(),
            'unique_users' => $product->affiliateClicks()->distinct('user_id')->count(),
            'unique_ips' => $product->affiliateClicks()->distinct('ip_address')->count(),
            'today_clicks' => $product->affiliateClicks()->whereDate('clicked_at', today())->count(),
            'this_month_clicks' => $product->affiliateClicks()
                                         ->whereBetween('clicked_at', [
                                             now()->startOfMonth(),
                                             now()->endOfMonth()
                                         ])
                                         ->count(),
        ];

        // Get commission analytics
        $totalCommissions = $product->commissions()
                                  ->where('commission_type', 'affiliate')
                                  ->count();
        
        $commissionStats = [
            'total_commissions' => $totalCommissions,
            'total_earnings' => $product->commissions()
                                       ->where('commission_type', 'affiliate')
                                       ->where('status', 'approved')
                                       ->sum('commission_amount'),
            'pending_earnings' => $product->commissions()
                                         ->where('commission_type', 'affiliate')
                                         ->where('status', 'pending')
                                         ->sum('commission_amount'),
            'conversion_rate' => $clickStats['total_clicks'] > 0 
                               ? round(($totalCommissions / $clickStats['total_clicks']) * 100, 2)
                               : 0,
        ];

        // Get recent clicks
        $recentClicks = $product->affiliateClicks()
                              ->with(['user'])
                              ->latest('clicked_at')
                              ->limit(20)
                              ->get();

        // Get recent commissions
        $recentCommissions = $product->commissions()
                                   ->where('commission_type', 'affiliate')
                                   ->with(['user', 'order'])
                                   ->latest('earned_at')
                                   ->limit(10)
                                   ->get();

        // Get top affiliates for this product
        $topAffiliates = User::withCount(['affiliateClicks as product_clicks' => function($q) use ($product) {
                                 $q->where('product_id', $product->id);
                             }])
                            ->withSum(['commissions as product_earnings' => function($q) use ($product) {
                                $q->where('commission_type', 'affiliate')
                                  ->where('product_id', $product->id)
                                  ->where('status', 'approved');
                            }], 'commission_amount')
                            ->having('product_clicks', '>', 0)
                            ->orderBy('product_clicks', 'desc')
                            ->limit(10)
                            ->get();

        return view('admin.affiliate-links.show', compact(
            'product', 'clickStats', 'commissionStats', 'recentClicks', 'recentCommissions', 'topAffiliates'
        ));
    }

    /**
     * Get link performance analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $productId = $request->get('product_id');

        $query = AffiliateClick::query();
        
        if ($productId) {
            $query->where('product_id', $productId);
        }

        // Clicks over time
        $clicksOverTime = $query->selectRaw('DATE(clicked_at) as date, COUNT(*) as clicks')
                               ->where('clicked_at', '>=', now()->subDays($period))
                               ->groupBy('date')
                               ->orderBy('date')
                               ->get();

        // If no data, create sample data points to prevent empty charts
        if ($clicksOverTime->isEmpty()) {
            $clicksOverTime = collect();
            for ($i = min($period, 7) - 1; $i >= 0; $i--) {
                $clicksOverTime->push((object)[
                    'date' => now()->subDays($i)->format('Y-m-d'),
                    'clicks' => 0
                ]);
            }
        }

        // Top products by clicks
        $topProducts = Product::withCount(['affiliateClicks as clicks_count' => function($q) use ($period) {
                                   $q->where('clicked_at', '>=', now()->subDays($period));
                               }])
                              ->having('clicks_count', '>', 0)
                              ->orderBy('clicks_count', 'desc')
                              ->limit(10)
                              ->get();

        // Browser distribution
        $browserStats = AffiliateClick::selectRaw('
                            CASE 
                                WHEN user_agent LIKE "%Chrome%" THEN "Chrome"
                                WHEN user_agent LIKE "%Firefox%" THEN "Firefox" 
                                WHEN user_agent LIKE "%Safari%" THEN "Safari"
                                WHEN user_agent LIKE "%Edge%" THEN "Edge"
                                ELSE "Other"
                            END as browser,
                            COUNT(*) as count
                        ')
                        ->where('clicked_at', '>=', now()->subDays($period))
                        ->groupBy('browser')
                        ->orderBy('count', 'desc')
                        ->get();

        // If no browser data, create sample data
        if ($browserStats->isEmpty()) {
            $browserStats = collect([
                (object)['browser' => 'No Data', 'count' => 1]
            ]);
        }

        // Get additional stats for the view
        $totalClicks = AffiliateClick::where('clicked_at', '>=', now()->subDays($period))->count();
        $totalProducts = Product::whereHas('affiliateClicks', function($q) use ($period) {
            $q->where('clicked_at', '>=', now()->subDays($period));
        })->count();

        $data = [
            'clicks_over_time' => $clicksOverTime,
            'top_products' => $topProducts,
            'browser_stats' => $browserStats,
            'total_clicks' => $totalClicks,
            'total_products' => $totalProducts,
            'period' => $period,
            'product_id' => $productId,
        ];

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($data);
        }

        // Return view for regular requests
        return view('admin.affiliate-links.analytics', $data);
    }
}
