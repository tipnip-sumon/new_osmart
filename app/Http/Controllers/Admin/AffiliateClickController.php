<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateClickController extends Controller
{
    /**
     * Display a listing of affiliate clicks
     */
    public function index(Request $request)
    {
        $query = AffiliateClick::with(['user', 'product'])
                              ->latest();

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orWhereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('ip_address', 'like', "%{$search}%");
        }

        // Filter by affiliate user
        if ($request->filled('affiliate_id')) {
            $query->where('user_id', $request->affiliate_id);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('clicked_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('clicked_at', '<=', $request->date_to);
        }

        $clicks = $query->paginate(20);

        // Get summary statistics
        $stats = [
            'total_clicks' => AffiliateClick::count(),
            'unique_visitors' => AffiliateClick::distinct('ip_address')->count(),
            'today_clicks' => AffiliateClick::whereDate('clicked_at', today())->count(),
            'this_month_clicks' => AffiliateClick::whereBetween('clicked_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count(),
        ];

        // Get top affiliates by clicks
        $topAffiliates = User::withCount(['affiliateClicks as clicks_count'])
                            ->having('clicks_count', '>', 0)
                            ->orderBy('clicks_count', 'desc')
                            ->limit(10)
                            ->get();

        // Get top products by clicks
        $topProducts = Product::withCount(['affiliateClicks as clicks_count'])
                             ->having('clicks_count', '>', 0)
                             ->orderBy('clicks_count', 'desc')
                             ->limit(10)
                             ->get();

        // Get affiliate users for filter
        $affiliates = User::whereHas('affiliateClicks')
                         ->select('id', 'name', 'username')
                         ->get();

        // Get products for filter
        $products = Product::whereHas('affiliateClicks')
                          ->select('id', 'name')
                          ->get();

        return view('admin.affiliate-clicks.index', compact(
            'clicks', 'stats', 'topAffiliates', 'topProducts', 'affiliates', 'products'
        ));
    }

    /**
     * Display the specified affiliate click
     */
    public function show(AffiliateClick $affiliateClick)
    {
        $affiliateClick->load(['user', 'product']);

        // Get related clicks from same IP or user
        $relatedClicks = AffiliateClick::where('id', '!=', $affiliateClick->id)
                                     ->where(function($q) use ($affiliateClick) {
                                         $q->where('ip_address', $affiliateClick->ip_address)
                                           ->orWhere('user_id', $affiliateClick->user_id);
                                     })
                                     ->with(['user', 'product'])
                                     ->latest()
                                     ->limit(10)
                                     ->get();

        return view('admin.affiliate-clicks.show', compact('affiliateClick', 'relatedClicks'));
    }

    /**
     * Remove the specified affiliate click
     */
    public function destroy(AffiliateClick $affiliateClick)
    {
        $affiliateClick->delete();

        return redirect()->route('admin.affiliate-clicks.index')
                        ->with('success', 'Affiliate click record deleted successfully.');
    }

    /**
     * Bulk delete affiliate clicks
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'click_ids' => 'required|array',
            'click_ids.*' => 'exists:affiliate_clicks,id'
        ]);

        AffiliateClick::whereIn('id', $request->click_ids)->delete();

        return redirect()->route('admin.affiliate-clicks.index')
                        ->with('success', count($request->click_ids) . ' affiliate click records deleted successfully.');
    }

    /**
     * Get click analytics data
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // Clicks over time
        $clicksOverTime = AffiliateClick::selectRaw('DATE(clicked_at) as date, COUNT(*) as clicks')
                                      ->where('clicked_at', '>=', now()->subDays($period))
                                      ->groupBy('date')
                                      ->orderBy('date')
                                      ->get();

        // Top referrers
        $topReferrers = AffiliateClick::selectRaw('referrer, COUNT(*) as clicks')
                                    ->where('clicked_at', '>=', now()->subDays($period))
                                    ->whereNotNull('referrer')
                                    ->groupBy('referrer')
                                    ->orderBy('clicks', 'desc')
                                    ->limit(10)
                                    ->get();

        // Geographic distribution (by IP - simplified)
        $geoDistribution = AffiliateClick::selectRaw('SUBSTRING_INDEX(ip_address, ".", 2) as region, COUNT(*) as clicks')
                                       ->where('clicked_at', '>=', now()->subDays($period))
                                       ->groupBy('region')
                                       ->orderBy('clicks', 'desc')
                                       ->limit(10)
                                       ->get();

        return response()->json([
            'clicks_over_time' => $clicksOverTime,
            'top_referrers' => $topReferrers,
            'geo_distribution' => $geoDistribution,
        ]);
    }
}
