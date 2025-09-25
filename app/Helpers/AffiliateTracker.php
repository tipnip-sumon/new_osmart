<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class AffiliateTracker
{
    /**
     * Track affiliate click and store info
     */
    public static function trackClick($affiliateId, $referralCode, $productId)
    {
        try {
            // Store in session for immediate use
            session([
                'affiliate_info' => [
                    'affiliate_id' => $affiliateId,
                    'referral_code' => $referralCode,
                    'product_id' => $productId,
                    'tracked_at' => now()
                ]
            ]);
            
            // Store in persistent cookie for extended attribution
            $attributionDays = (int) config('affiliate.attribution_days', 30);
            $cookieData = [
                'affiliate_id' => $affiliateId,
                'referral_code' => $referralCode,
                'product_id' => $productId,
                'tracked_at' => now()->timestamp,
                'expires_at' => now()->addDays($attributionDays)->timestamp,
                'attribution_days' => $attributionDays
            ];
            
            $cookieName = config('affiliate.cookie_name', 'affiliate_tracking');
            Cookie::queue($cookieName, encrypt(json_encode($cookieData)), $attributionDays * 24 * 60);
            
            Log::info('Affiliate tracking stored', [
                'affiliate_id' => $affiliateId,
                'product_id' => $productId,
                'attribution_days' => $attributionDays,
                'storage' => 'session + cookie'
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to track affiliate click: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get current affiliate info (session first, then cookie)
     */
    public static function getAffiliateInfo()
    {
        // Check session first (most recent)
        $sessionInfo = session('affiliate_info');
        if ($sessionInfo && isset($sessionInfo['affiliate_id'])) {
            return array_merge($sessionInfo, ['source' => 'session']);
        }
        
        // Fall back to cookie (persistent)
        $cookieInfo = static::getAffiliateInfoFromCookie();
        if ($cookieInfo) {
            return array_merge($cookieInfo, ['source' => 'cookie']);
        }
        
        return null;
    }
    
    /**
     * Get affiliate info from cookie
     */
    public static function getAffiliateInfoFromCookie()
    {
        try {
            $cookieName = config('affiliate.cookie_name', 'affiliate_tracking');
            $cookieValue = request()->cookie($cookieName);
            
            if (!$cookieValue) {
                return null;
            }
            
            $cookieData = json_decode(decrypt($cookieValue), true);
            
            if (!$cookieData || !is_array($cookieData)) {
                return null;
            }
            
            // Check expiration
            if (isset($cookieData['expires_at']) && time() > $cookieData['expires_at']) {
                return null;
            }
            
            // Validate required fields
            if (!isset($cookieData['affiliate_id'])) {
                return null;
            }
            
            return $cookieData;
            
        } catch (\Exception $e) {
            Log::warning('Failed to read affiliate cookie: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Clear affiliate tracking data
     */
    public static function clearTracking()
    {
        // Clear session
        session()->forget('affiliate_info');
        
        // Clear cookie
        $cookieName = config('affiliate.cookie_name', 'affiliate_tracking');
        Cookie::queue(Cookie::forget($cookieName));
        
        Log::info('Affiliate tracking cleared');
    }
    
    /**
     * Get attribution window info
     */
    public static function getAttributionInfo($affiliateInfo = null)
    {
        if (!$affiliateInfo) {
            $affiliateInfo = static::getAffiliateInfo();
        }
        
        if (!$affiliateInfo) {
            return null;
        }
        
        $trackedAt = isset($affiliateInfo['tracked_at']) 
            ? (is_numeric($affiliateInfo['tracked_at']) ? $affiliateInfo['tracked_at'] : strtotime($affiliateInfo['tracked_at']))
            : time();
            
        $attributionDays = $affiliateInfo['attribution_days'] ?? config('affiliate.attribution_days', 30);
        $expiresAt = $trackedAt + ($attributionDays * 24 * 60 * 60);
        $daysRemaining = max(0, ($expiresAt - time()) / (24 * 60 * 60));
        $daysSinceClick = (time() - $trackedAt) / (24 * 60 * 60);
        
        return [
            'tracked_at' => $trackedAt,
            'expires_at' => $expiresAt,
            'attribution_days' => $attributionDays,
            'days_remaining' => round($daysRemaining, 1),
            'days_since_click' => round($daysSinceClick, 1),
            'is_valid' => $daysRemaining > 0,
            'source' => $affiliateInfo['source'] ?? 'unknown'
        ];
    }
    
    /**
     * Check if affiliate user is valid
     */
    public static function isValidAffiliate($affiliateId)
    {
        try {
            $user = User::find($affiliateId);
            return $user && $user->status === 'active';
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Generate affiliate link for product
     */
    public static function generateAffiliateLink($productSlug, $affiliateId, $referralCode = null)
    {
        $user = User::find($affiliateId);
        if (!$user) {
            return null;
        }
        
        $referralCode = $referralCode ?: $user->username;
        $utmParams = config('affiliate.utm_parameters', []);
        
        $params = array_merge([
            'aff' => $affiliateId,
            'ref' => $referralCode,
        ], $utmParams);
        
        return route('products.show', $productSlug) . '?' . http_build_query($params);
    }
    
    /**
     * Get social sharing URLs
     */
    public static function getSocialSharingUrls($productUrl, $productName, $affiliateId)
    {
        $platforms = config('affiliate.social_platforms', []);
        $message = "Check out this amazing product: {$productName}";
        $sharingUrls = [];
        
        foreach ($platforms as $platform => $config) {
            $url = str_replace(
                ['{url}', '{message}'],
                [urlencode($productUrl), urlencode($message)],
                $config['url_template']
            );
            
            $sharingUrls[$platform] = [
                'url' => $url,
                'name' => $config['name'],
                'icon' => $config['icon'],
                'color' => $config['color']
            ];
        }
        
        return $sharingUrls;
    }
}
