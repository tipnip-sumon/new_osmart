<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\PackageLinkSharingSetting;
use App\Models\AffiliateLinkShare;
use App\Models\DailyLinkSharingStat;
use App\Models\AffiliateClick;
use App\Models\UserActivePackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PackageLinkSharingService
{
    /**
     * Share a product link for a user
     */
    public function shareProductLink($userId, $productSlug, $platform = 'manual')
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            // Get user's active package settings
            $packageSettings = $this->getUserPackageSettings($user);
            if (!$packageSettings) {
                return ['success' => false, 'message' => 'No active package found or package link sharing not available'];
            }

            // Check daily share limit
            $todayStats = DailyLinkSharingStat::getTodayStats($userId, $packageSettings->package_name);
            if ($todayStats->checkShareLimit($packageSettings)) {
                return ['success' => false, 'message' => "Daily share limit reached ({$packageSettings->daily_share_limit} shares)"];
            }

            // Generate affiliate link
            $affiliateUrl = $this->generateAffiliateLink($productSlug, $user);
            if (!$affiliateUrl) {
                return ['success' => false, 'message' => 'Failed to generate affiliate link'];
            }

            // Record the share
            $linkShare = AffiliateLinkShare::create([
                'user_id' => $userId,
                'product_slug' => $productSlug,
                'shared_url' => $affiliateUrl,
                'shared_platform' => $platform,
                'share_date' => Carbon::today(),
                'clicks_count' => 0,
                'unique_clicks_count' => 0,
                'earnings_amount' => 0,
                'is_active' => true
            ]);

            // Update daily stats
            $todayStats->incrementShare();

            return [
                'success' => true,
                'message' => 'Link shared successfully',
                'affiliate_link' => $affiliateUrl,
                'shares_remaining' => $packageSettings->daily_share_limit - $todayStats->shares_count,
                'earnings_today' => $todayStats->earnings_amount,
                'earnings_limit' => $packageSettings->daily_earning_limit
            ];

        } catch (\Exception $e) {
            Log::error('Package Link Sharing Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to share link'];
        }
    }

    /**
     * Process affiliate click with atomic transaction to prevent limit exceeded (FIXED)
     */
    public function processAffiliateClick($affiliateId, $productSlug, $clickerInfo)
    {
        try {
            Log::info('Processing affiliate click with atomic transaction', [
                'affiliate_id' => $affiliateId,
                'product_slug' => $productSlug,
                'clicker_ip' => $clickerInfo['ip_address'] ?? 'unknown',
                'clicker_user_id' => $clickerInfo['user_id'] ?? null
            ]);

            // CRITICAL FIX: Use database transaction for atomic operations
            return DB::transaction(function () use ($affiliateId, $productSlug, $clickerInfo) {
                
                // STEP 1: Lock the affiliate user record to prevent race conditions
                $affiliate = User::where('id', $affiliateId)
                    ->where('is_active', true)
                    ->lockForUpdate() // 🔒 PREVENTS CONCURRENT ACCESS
                    ->first();

                if (!$affiliate) {
                    Log::warning('Affiliate not found or inactive', ['affiliate_id' => $affiliateId]);
                    return ['success' => false, 'message' => 'Affiliate not found or inactive'];
                }

                // Get package settings
                $packageSettings = $this->getUserPackageSettings($affiliate);
                if (!$packageSettings) {
                    Log::warning('Package settings not found for affiliate', [
                        'affiliate_id' => $affiliateId,
                        'affiliate_is_active' => $affiliate->is_active,
                        'affiliate_active_points' => $affiliate->active_points
                    ]);
                    return ['success' => false, 'message' => 'Package settings not found'];
                }

                Log::info('Package settings found', [
                    'affiliate_id' => $affiliateId,
                    'package_name' => $packageSettings->package_name,
                    'click_reward_amount' => $packageSettings->click_reward_amount,
                    'daily_earning_limit' => $packageSettings->daily_earning_limit
                ]);

                // STEP 2: Get today's stats with row-level locking (PREVENTS RACE CONDITIONS)
                $todayStats = DailyLinkSharingStat::where('user_id', $affiliateId)
                    ->where('stat_date', Carbon::today())
                    ->lockForUpdate() // 🔒 ATOMIC STAT UPDATES
                    ->first();

                if (!$todayStats) {
                    $todayStats = DailyLinkSharingStat::create([
                        'user_id' => $affiliateId,
                        'stat_date' => Carbon::today(),
                        'shares_count' => 0,
                        'clicks_count' => 0,
                        'unique_clicks_count' => 0,
                        'earnings_amount' => 0,
                        'package_name' => $packageSettings->package_name,
                        'daily_limit_used' => false,
                        'earning_limit_reached' => false,
                    ]);
                }

                // STEP 3: REAL-TIME LIMIT CHECK (no stale data possible)
                if ($todayStats->earnings_amount >= $packageSettings->daily_earning_limit) {
                    Log::info('Daily earning limit already reached - BLOCKED', [
                        'affiliate_id' => $affiliateId,
                        'current_earnings' => $todayStats->earnings_amount,
                        'daily_limit' => $packageSettings->daily_earning_limit
                    ]);
                    return ['success' => false, 'message' => 'Daily earning limit reached'];
                }

                // STEP 4: Check for unique click (IP + User Agent + Date)
                $isUniqueClick = $this->isUniqueClick($affiliateId, $productSlug, $clickerInfo);
                $earningAmount = 0;

                Log::info('Click uniqueness check', [
                    'affiliate_id' => $affiliateId,
                    'product_slug' => $productSlug,
                    'is_unique' => $isUniqueClick,
                    'ip_address' => $clickerInfo['ip_address']
                ]);

                if ($isUniqueClick) {
                    $tentativeEarning = $packageSettings->click_reward_amount;
                    
                    // STEP 5: ATOMIC EARNING CALCULATION (prevents exceed)
                    $potentialTotal = $todayStats->earnings_amount + $tentativeEarning;
                    
                    if ($potentialTotal > $packageSettings->daily_earning_limit) {
                        // CAP THE EARNING to not exceed daily limit
                        $earningAmount = $packageSettings->daily_earning_limit - $todayStats->earnings_amount;
                        
                        if ($earningAmount <= 0) {
                            Log::info('Zero earning calculated - limit exactly reached', [
                                'affiliate_id' => $affiliateId,
                                'current_earnings' => $todayStats->earnings_amount,
                                'daily_limit' => $packageSettings->daily_earning_limit
                            ]);
                            return ['success' => false, 'message' => 'Daily earning limit reached'];
                        }
                        
                        Log::info('Earning amount CAPPED to prevent exceeding limit', [
                            'affiliate_id' => $affiliateId,
                            'original_amount' => $tentativeEarning,
                            'capped_amount' => $earningAmount,
                            'daily_limit' => $packageSettings->daily_earning_limit,
                            'current_earnings' => $todayStats->earnings_amount,
                            'would_be_total' => $potentialTotal
                        ]);
                    } else {
                        $earningAmount = $tentativeEarning;
                    }

                    // STEP 6: Record earning ATOMICALLY within transaction
                    if ($earningAmount > 0) {
                        Log::info('Recording earning atomically', [
                            'affiliate_id' => $affiliateId,
                            'earning_amount' => $earningAmount,
                            'product_slug' => $productSlug
                        ]);
                        
                        $this->recordEarning($affiliate, $earningAmount, $productSlug);
                        
                        // Update stats ATOMICALLY - prevents race conditions
                        $todayStats->increment('unique_clicks_count');
                        $todayStats->increment('earnings_amount', $earningAmount);
                        
                        // Mark limit reached if we've hit it exactly
                        $newTotal = $todayStats->fresh()->earnings_amount;
                        if ($newTotal >= $packageSettings->daily_earning_limit) {
                            $todayStats->update(['earning_limit_reached' => true]);
                            Log::info('Daily limit reached - marked as reached', [
                                'affiliate_id' => $affiliateId,
                                'final_earnings' => $newTotal,
                                'daily_limit' => $packageSettings->daily_earning_limit
                            ]);
                        }
                    }
                } else {
                    Log::info('Click is not unique, no earning recorded', [
                        'affiliate_id' => $affiliateId,
                        'product_slug' => $productSlug,
                        'ip_address' => $clickerInfo['ip_address']
                    ]);
                }

                // STEP 7: Record click in affiliate_clicks table
                $clickRecord = AffiliateClick::create([
                    'affiliate_id' => $affiliateId,
                    'user_id' => $clickerInfo['user_id'] ?? null,
                    'product_id' => $this->getProductId($productSlug),
                    'ip_address' => $clickerInfo['ip_address'],
                    'cookie_id' => $clickerInfo['cookie_id'] ?? null,
                    'user_agent' => $clickerInfo['user_agent'],
                    'referrer' => $clickerInfo['referrer'] ?? null,
                    'clicked_at' => now(),
                ]);

                // STEP 8: Update total clicks count atomically
                $todayStats->increment('clicks_count');

                // STEP 9: Update link share record
                $this->updateLinkShareStats($affiliate->id, $clickerInfo['shared_url'] ?? null, $isUniqueClick, $earningAmount);

                // STEP 10: Get final stats for response
                $finalStats = $todayStats->fresh();

                return [
                    'success' => true,
                    'is_unique' => $isUniqueClick,
                    'earning_amount' => $earningAmount,
                    'total_earnings_today' => $finalStats->earnings_amount,
                    'daily_limit' => $packageSettings->daily_earning_limit,
                    'limit_reached' => $finalStats->earning_limit_reached,
                    'remaining_limit' => max(0, $packageSettings->daily_earning_limit - $finalStats->earnings_amount),
                    'message' => $isUniqueClick ? "Earned ৳{$earningAmount}" : 'Click recorded (not unique)'
                ];
                
            }); // End of DB::transaction

        } catch (\Exception $e) {
            Log::error('Atomic Affiliate Click Processing Error: ' . $e->getMessage(), [
                'affiliate_id' => $affiliateId,
                'product_slug' => $productSlug,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'message' => 'Failed to process click'];
        }
    }

    /**
     * Get user's package settings
     */
    private function getUserPackageSettings($user)
    {
        // Get user's active package
        $activePackage = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('plan')
            ->orderBy('amount_invested', 'desc') // Get highest package first
            ->first();

        $packageName = null;

        if ($activePackage) {
            // User has an active package - use tier mapping
            $packageName = $this->getPackageNameFromTier($activePackage);
        } elseif ($user->is_active && $user->active_points >= 100) {
            // User is activated with 100+ active points but no UserActivePackage record
            // Give them starter package as default
            $packageName = 'starter';
            
            Log::info("Assigning default starter package to activated user", [
                'user_id' => $user->id,
                'active_points' => $user->active_points,
                'is_active' => $user->is_active
            ]);
        }

        if (!$packageName) {
            return null;
        }

        // Get package link sharing settings
        return PackageLinkSharingSetting::where('package_name', $packageName)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Map package tier to package name
     */
    private function getPackageNameFromTier($activePackage)
    {
        $amount = $activePackage->amount_invested;

        // Map based on investment amount
        if ($amount >= 5000) {
            return 'diamond';
        } elseif ($amount >= 1000) {
            return 'gold';
        } elseif ($amount >= 500) {
            return 'silver';
        } else {
            return 'starter';
        }
    }

    /**
     * Generate affiliate link
     */
    private function generateAffiliateLink($productSlug, $user)
    {
        $product = Product::where('slug', $productSlug)->first();
        if (!$product) {
            return null;
        }

        $params = [
            'ref' => $user->username,
            'aff' => $user->id,
            'utm_source' => 'package_sharing',
            'utm_medium' => 'affiliate_link',
            'utm_campaign' => 'product_share',
            'utm_content' => $product->slug
        ];

        // Use the correct route name and add query parameters
        return route('products.show', $product->slug) . '?' . http_build_query($params);
    }

    /**
     * Check if click is unique
     */
    private function isUniqueClick($affiliateId, $productSlug, $clickerInfo)
    {
        $productId = $this->getProductId($productSlug);
        
        Log::info('Checking click uniqueness', [
            'affiliate_id' => $affiliateId,
            'product_slug' => $productSlug,
            'product_id' => $productId,
            'clicker_ip' => $clickerInfo['ip_address'],
            'clicker_cookie' => $clickerInfo['cookie_id'] ?? 'not_set',
            'clicker_user_agent' => substr($clickerInfo['user_agent'] ?? '', 0, 100), // Truncate for readability
            'checking_date' => Carbon::today()->toDateString()
        ]);

        if (!$productId) {
            Log::warning('Product not found for uniqueness check', [
                'product_slug' => $productSlug,
                'affiliate_id' => $affiliateId
            ]);
            return false; // Don't reward if product doesn't exist
        }
        
        // Check for duplicate click today using multiple criteria
        // Primary check: cookie_id (if available)
        $query = AffiliateClick::where('affiliate_id', $affiliateId)
            ->where('product_id', $productId)
            ->whereDate('clicked_at', Carbon::today());
        
        // If cookie is available, use it as primary unique identifier
        if (!empty($clickerInfo['cookie_id'])) {
            $query->where('cookie_id', $clickerInfo['cookie_id']);
        } else {
            // Fallback to IP address if no cookie
            $query->where('ip_address', $clickerInfo['ip_address']);
        }
        
        $existingClick = $query->first();

        if ($existingClick) {
            Log::info('Duplicate click detected - not unique', [
                'affiliate_id' => $affiliateId,
                'product_id' => $productId,
                'clicker_ip' => $clickerInfo['ip_address'],
                'clicker_cookie' => $clickerInfo['cookie_id'] ?? 'not_set',
                'existing_click_id' => $existingClick->id,
                'existing_click_time' => $existingClick->clicked_at,
                'detection_method' => !empty($clickerInfo['cookie_id']) ? 'cookie' : 'ip',
                'hours_since_last_click' => Carbon::parse($existingClick->clicked_at)->diffInHours(now())
            ]);
            return false;
        }

        Log::info('Click is unique - will be rewarded', [
            'affiliate_id' => $affiliateId,
            'product_id' => $productId,
            'clicker_ip' => $clickerInfo['ip_address'],
            'clicker_cookie' => $clickerInfo['cookie_id'] ?? 'not_set',
            'detection_method' => !empty($clickerInfo['cookie_id']) ? 'cookie' : 'ip'
        ]);

        return true;
    }

    /**
     * Record earning for affiliate
     */
    private function recordEarning($user, $amount, $productSlug)
    {
        DB::transaction(function () use ($user, $amount, $productSlug) {
            // Add to user's wallet
            $user->increment('interest_wallet', $amount);
            $user->increment('total_earnings', $amount);

            // Record transaction
            DB::table('transactions')->insert([
                'user_id' => $user->id,
                'transaction_id' => 'LINK_' . uniqid() . '_' . $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => "Link sharing reward for product: {$productSlug}",
                'status' => 'completed',
                'reference_type' => 'affiliate_click',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info("Link sharing reward processed", [
                'user_id' => $user->id,
                'amount' => $amount,
                'product' => $productSlug
            ]);
        });
    }

    /**
     * Get product ID from slug
     */
    private function getProductId($productSlug)
    {
        $product = Product::where('slug', $productSlug)->first();
        return $product ? $product->id : null;
    }

    /**
     * Update link share statistics
     */
    private function updateLinkShareStats($userId, $sharedUrl, $isUnique, $earningAmount)
    {
        // Try to find the link share record - be flexible with URL matching
        $linkShare = null;
        
        // First try exact URL match
        if ($sharedUrl) {
            $linkShare = AffiliateLinkShare::where('user_id', $userId)
                ->where('shared_url', $sharedUrl)
                ->whereDate('share_date', Carbon::today())
                ->first();
        }
        
        // If no exact match, try to find by user and date (most recent share)
        if (!$linkShare) {
            $linkShare = AffiliateLinkShare::where('user_id', $userId)
                ->whereDate('share_date', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // If still no match, create a new record (this shouldn't happen normally)
        if (!$linkShare) {
            Log::warning('No affiliate_link_shares record found, creating new one', [
                'user_id' => $userId,
                'shared_url' => $sharedUrl,
                'date' => Carbon::today()
            ]);
            
            // Create a basic record so the stats can be tracked
            $linkShare = AffiliateLinkShare::create([
                'user_id' => $userId,
                'product_slug' => 'unknown', // We'll try to determine from URL
                'shared_url' => $sharedUrl ?: 'unknown',
                'shared_platform' => 'auto-created',
                'share_date' => Carbon::today(),
                'clicks_count' => 0,
                'unique_clicks_count' => 0,
                'earnings_amount' => 0,
                'is_active' => true
            ]);
        }

        if ($linkShare) {
            $linkShare->increment('clicks_count');
            if ($isUnique) {
                $linkShare->increment('unique_clicks_count');
                $linkShare->increment('earnings_amount', $earningAmount);
            }
            
            Log::info('Link share stats updated', [
                'link_share_id' => $linkShare->id,
                'user_id' => $userId,
                'clicks_count' => $linkShare->clicks_count,
                'earnings_amount' => $linkShare->earnings_amount
            ]);
        }
    }

    /**
     * Get user's sharing dashboard data
     */
    public function getUserDashboard($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        $packageSettings = $this->getUserPackageSettings($user);
        $packageName = $packageSettings ? $packageSettings->package_name : null;
        $todayStats = DailyLinkSharingStat::getTodayStats($userId, $packageName);
        $monthlyStats = DailyLinkSharingStat::getMonthlyStats($userId);

        return [
            'package_settings' => $packageSettings,
            'today_stats' => $todayStats,
            'monthly_stats' => $monthlyStats,
            'shares_remaining' => $packageSettings ? ($packageSettings->daily_share_limit - $todayStats->shares_count) : 0,
            'earnings_remaining' => $packageSettings ? ($packageSettings->daily_earning_limit - $todayStats->earnings_amount) : 0,
        ];
    }
}
