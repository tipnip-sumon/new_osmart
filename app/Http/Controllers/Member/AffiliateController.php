<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class AffiliateController extends Controller
{
    /**
     * Show affiliate dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get affiliate statistics
        $statsArray = $this->getAffiliateStats($user->id);
        
        // Create comprehensive userStats object with all required fields
        $userStats = (object) array_merge([
            'current_rank' => 'Bronze',
            'next_rank' => 'Silver', 
            'total_earnings' => $statsArray['total_commission'] ?? 0,
            'this_month_earnings' => 0, // Could be calculated from monthly commissions
            'team_size' => 0,
            'direct_referrals' => 0,
            'rank_progress' => 25,
            'team_volume' => 0,
            'next_rank_requirement' => 1000,
            'personal_volume' => 0,
        ], $statsArray);
        
        // Get recent commissions (empty for now, can be implemented later)
        $recentCommissions = collect();
        
        return view('user.affiliate-dashboard', compact('user', 'userStats', 'recentCommissions'));
    }
    
    /**
     * Get affiliate statistics
     */
    private function getAffiliateStats($affiliateId)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        try {
            // Total clicks
            $totalClicks = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->count();
            
            // Today's clicks
            $todayClicks = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->whereDate('clicked_at', $today)
                ->count();
            
            // This month's clicks
            $monthClicks = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->where('clicked_at', '>=', $thisMonth)
                ->count();
            
            // Unique products clicked
            $uniqueProducts = DB::table('affiliate_clicks')
                ->where('affiliate_id', $affiliateId)
                ->distinct('product_id')
                ->count();
            
            // Most popular products
            $topProducts = DB::table('affiliate_clicks')
                ->select('product_id', DB::raw('COUNT(*) as click_count'))
                ->join('products', 'affiliate_clicks.product_id', '=', 'products.id')
                ->where('affiliate_id', $affiliateId)
                ->groupBy('product_id')
                ->orderBy('click_count', 'desc')
                ->limit(5)
                ->get();
            
            // Recent clicks with product info
            $recentClicks = DB::table('affiliate_clicks')
                ->select('affiliate_clicks.*', 'products.name as product_name', 'products.slug')
                ->join('products', 'affiliate_clicks.product_id', '=', 'products.id')
                ->where('affiliate_id', $affiliateId)
                ->orderBy('clicked_at', 'desc')
                ->limit(10)
                ->get();
            
            // Calculate actual conversions and commissions
            $commissionsData = DB::table('commissions')
                ->where('user_id', $affiliateId)
                ->where('commission_type', 'affiliate')
                ->whereNotNull('product_id');

            $totalCommission = $commissionsData->sum('commission_amount') ?? 0;
            $totalConversions = $commissionsData->count() ?? 0;
            
            return [
                'total_clicks' => $totalClicks,
                'today_clicks' => $todayClicks,
                'month_clicks' => $monthClicks,
                'unique_products' => $uniqueProducts,
                'top_products' => $topProducts,
                'recent_clicks' => $recentClicks,
                'conversion_rate' => $totalClicks > 0 ? round(($totalConversions / $totalClicks) * 100, 2) : 0,
                'total_commission' => $totalCommission,
            ];
            
        } catch (\Exception $e) {
            return [
                'total_clicks' => 0,
                'today_clicks' => 0,
                'month_clicks' => 0,
                'unique_products' => 0,
                'top_products' => collect(),
                'recent_clicks' => collect(),
                'conversion_rate' => 0,
                'total_commission' => 0,
            ];
        }
    }
    
    /**
     * Get click analytics data for charts
     */
    public function getAnalytics(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);
        $startDate = Carbon::now()->subDays($days);
        
        try {
            // Daily clicks for the chart
            $dailyClicks = DB::table('affiliate_clicks')
                ->select(DB::raw('DATE(clicked_at) as date'), DB::raw('COUNT(*) as clicks'))
                ->where('affiliate_id', $user->id)
                ->where('clicked_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Top referring sources
            $topSources = DB::table('affiliate_clicks')
                ->select('referral_url', DB::raw('COUNT(*) as clicks'))
                ->where('affiliate_id', $user->id)
                ->where('clicked_at', '>=', $startDate)
                ->whereNotNull('referral_url')
                ->groupBy('referral_url')
                ->orderBy('clicks', 'desc')
                ->limit(10)
                ->get();
            
            return response()->json([
                'success' => true,
                'daily_clicks' => $dailyClicks,
                'top_sources' => $topSources
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load analytics data'
            ], 500);
        }
    }
    
    /**
     * Generate a shareable affiliate link
     */
    public function generateShareableLink(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'platform' => 'sometimes|string|in:facebook,twitter,whatsapp,email,copy'
        ]);
        
        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);
        
        // Generate the affiliate link
        $affiliateLink = $this->generateAffiliateLink($product, $user);
        
        // Platform-specific sharing URLs
        $platform = $request->get('platform', 'copy');
        $shareUrl = $this->generatePlatformSpecificUrl($affiliateLink, $product, $platform);
        
        return response()->json([
            'success' => true,
            'affiliate_link' => $affiliateLink,
            'share_url' => $shareUrl,
            'product_name' => $product->name
        ]);
    }
    
    /**
     * Generate platform-specific sharing URLs
     */
    private function generatePlatformSpecificUrl($affiliateLink, $product, $platform)
    {
        $encodedLink = urlencode($affiliateLink);
        $productName = urlencode($product->name);
        
        switch ($platform) {
            case 'facebook':
                return "https://www.facebook.com/sharer/sharer.php?u={$encodedLink}&quote=Check out this amazing product: {$productName}";
                
            case 'twitter':
                return "https://twitter.com/intent/tweet?url={$encodedLink}&text=Check out this amazing product: {$productName}";
                
            case 'whatsapp':
                return "https://wa.me/?text=Check out this amazing product: {$productName} {$encodedLink}";
                
            case 'email':
                $subject = urlencode("Check out: {$product->name}");
                $body = urlencode("I found this amazing product and thought you'd like it:\n\n{$product->name}\n{$affiliateLink}");
                return "mailto:?subject={$subject}&body={$body}";
                
            default:
                return $affiliateLink;
        }
    }
    
    /**
     * Helper method to generate affiliate link (same as ProductController)
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
}
