<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Services\PackageLinkSharingService;
use Illuminate\Support\Facades\Log;

// Debug route to test link sharing manually
Route::get('/debug/test-link-sharing/{userId}', function ($userId) {
    try {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $service = new PackageLinkSharingService();
        
        // Check user package settings first
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getUserPackageSettings');
        $method->setAccessible(true);
        $packageSettings = $method->invoke($service, $user);

        $debugInfo = [
            'user_id' => $user->id,
            'user_is_active' => $user->is_active,
            'user_active_points' => $user->active_points,
            'package_settings' => $packageSettings ? [
                'package_name' => $packageSettings->package_name,
                'daily_share_limit' => $packageSettings->daily_share_limit,
                'click_reward_amount' => $packageSettings->click_reward_amount,
                'daily_earning_limit' => $packageSettings->daily_earning_limit,
            ] : null,
        ];

        // Try to get the first product to test sharing
        $product = Product::first();
        if (!$product) {
            $debugInfo['error'] = 'No products found to test sharing';
            return response()->json($debugInfo);
        }

        // Test the sharing process
        $shareResult = $service->shareProductLink($user->id, $product->slug);
        $debugInfo['share_result'] = $shareResult;

        return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debug route to test affiliate click processing
Route::get('/debug/test-affiliate-click/{affiliateId}/{productSlug}', function ($affiliateId, $productSlug) {
    try {
        $service = new PackageLinkSharingService();
        
        // Get IP address with same logic as main route
        $ipAddress = request()->ip();
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            $ipAddress = request()->header('X-Forwarded-For') 
                ?: request()->header('X-Real-IP') 
                ?: request()->header('HTTP_CLIENT_IP')
                ?: request()->header('HTTP_X_FORWARDED_FOR')
                ?: request()->ip();
                
            if (strpos($ipAddress, ',') !== false) {
                $ipAddress = trim(explode(',', $ipAddress)[0]);
            }
        }
        
        $clickerInfo = [
            'user_id' => null, // Simulate guest click
            'ip_address' => $ipAddress,
            'user_agent' => request()->userAgent(),
            'referrer' => 'https://example.com',
            'shared_url' => request()->fullUrl()
        ];

        // Also check existing clicks for this affiliate/product
        $existingClicks = \App\Models\AffiliateClick::where('affiliate_id', $affiliateId)
            ->whereDate('clicked_at', \Carbon\Carbon::today())
            ->get(['id', 'ip_address', 'clicked_at', 'product_id']);

        $result = $service->processAffiliateClick($affiliateId, $productSlug, $clickerInfo);

        return response()->json([
            'affiliate_id' => $affiliateId,
            'product_slug' => $productSlug,
            'detected_ip' => $ipAddress,
            'original_request_ip' => request()->ip(),
            'clicker_info' => $clickerInfo,
            'existing_clicks_today' => $existingClicks,
            'processing_result' => $result,
            'ip_headers' => [
                'X-Forwarded-For' => request()->header('X-Forwarded-For'),
                'X-Real-IP' => request()->header('X-Real-IP'),
                'HTTP_CLIENT_IP' => request()->header('HTTP_CLIENT_IP'),
                'HTTP_X_FORWARDED_FOR' => request()->header('HTTP_X_FORWARDED_FOR'),
            ]
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Debug route to check database tables
Route::get('/debug/check-link-sharing-data/{userId}', function ($userId) {
    try {
        $data = [
            'user' => User::find($userId),
            'package_settings' => \App\Models\PackageLinkSharingSetting::all(),
            'affiliate_link_shares' => \App\Models\AffiliateLinkShare::where('user_id', $userId)->get(),
            'daily_stats' => \App\Models\DailyLinkSharingStat::where('user_id', $userId)->get(),
            'affiliate_clicks' => \App\Models\AffiliateClick::where('affiliate_id', $userId)->get(),
            'transactions' => \Illuminate\Support\Facades\DB::table('transactions')
                ->where('user_id', $userId)
                ->where('description', 'like', '%Link sharing%')
                ->get(),
        ];

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
