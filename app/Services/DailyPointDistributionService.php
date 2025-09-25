<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyPointDistribution;
use App\Models\CommissionSetting;
use App\Models\Commission;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyPointDistributionService
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }
    /**
     * Process daily point acquisition and distribution
     */
    public function processPointAcquisition(User $user, $pointsEarned, $acquisitionType, $purchaseAmount = null, $source = null)
    {
        // Check minimum points requirement
        if ($pointsEarned < 10) {
            throw new \Exception("Minimum 10 points required for distribution. Got: {$pointsEarned}");
        }

        DB::transaction(function () use ($user, $pointsEarned, $acquisitionType, $purchaseAmount, $source) {
            // Create daily distribution record (allow multiple per day)
            $distribution = DailyPointDistribution::create([
                'user_id' => $user->id,
                'distribution_date' => today(),
                'points_acquired' => $pointsEarned,
                'acquisition_type' => $acquisitionType,
                'purchase_amount' => $purchaseAmount,
                'source' => $source,
                'is_processed' => false
            ]);

            // Only update user points if NOT package_activation (points already handled in PackageController)
            if ($acquisitionType !== 'package_activation') {
                $this->updateUserPoints($user, $pointsEarned, $acquisitionType, $source);
            }

            // Process sponsor bonus
            $sponsorBonus = $this->processSponsorBonus($user, $pointsEarned);

            // Process generation bonus (20 levels)
            $generationData = $this->processGenerationBonus($user, $pointsEarned);

            // Update distribution record with bonuses
            $distribution->update([
                'sponsor_bonus' => $sponsorBonus,
                'generation_bonus' => $generationData['total_bonus'],
                'generation_details' => $generationData['details'],
                'is_processed' => true,
                'processed_at' => now(),
                'processing_notes' => "Processed successfully. Sponsor: {$sponsorBonus}, Generation: {$generationData['total_bonus']}"
            ]);

            Log::info("Daily point distribution processed", [
                'user_id' => $user->id,
                'points_earned' => $pointsEarned,
                'acquisition_type' => $acquisitionType,
                'sponsor_bonus' => $sponsorBonus,
                'generation_bonus' => $generationData['total_bonus'],
                'points_updated' => $acquisitionType !== 'package_activation' ? 'yes' : 'skipped'
            ]);
        });
    }

    /**
     * Update user points using PointService and check for activation
     */
    private function updateUserPoints(User $user, $pointsEarned, $acquisitionType = 'product_purchase', $source = null)
    {
        // Create point transaction record for the purchase
        \App\Models\PointTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $pointsEarned,
            'description' => "Point purchase: {$pointsEarned} points earned from {$acquisitionType}",
            'reference_type' => $acquisitionType,
            'reference_id' => null,
            'status' => 'completed'
        ]);
        
        // Update user points
        $user->increment('reserve_points', $pointsEarned);
        $user->increment('total_points_earned', $pointsEarned);
        
        // ONLY activate points for NON-product purchases (manual distribution, starter packages, etc)
        // Product purchases should keep points in reserve until manual activation
        if ($user->reserve_points >= 100 && !in_array($acquisitionType, ['product_purchase', 'external_addition'])) {
            // Get current points and activate in multiples of 100
            $pointsToActivate = floor($user->reserve_points / 100) * 100;
            if ($pointsToActivate > 0) {
                $user->increment('active_points', $pointsToActivate);
                $user->decrement('reserve_points', $pointsToActivate);
                
                // Create point transaction record for activation
                \App\Models\PointTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $pointsToActivate,
                    'description' => "Point activation: {$pointsToActivate} points moved from reserve to active (via {$acquisitionType})",
                    'reference_type' => 'point_activation',
                    'reference_id' => null,
                    'status' => 'completed'
                ]);
                
                Log::info("Activated {$pointsToActivate} points for user {$user->id} via {$acquisitionType}");
            }
        } else if ($user->reserve_points >= 100) {
            Log::info("Skipped point activation for product purchase - keeping points in reserve", [
                'user_id' => $user->id,
                'acquisition_type' => $acquisitionType,
                'reserve_points' => $user->reserve_points
            ]);
        }
        
        // ONLY activate user account for NON-product purchases
        // Product purchases should not automatically activate user accounts
        if (!in_array($acquisitionType, ['product_purchase', 'external_addition'])) {
            $this->checkUserActivation($user, $acquisitionType);
        } else {
            Log::info("Skipped user activation for product purchase - user should be manually activated", [
                'user_id' => $user->id,
                'acquisition_type' => $acquisitionType,
                'reserve_points' => $user->reserve_points
            ]);
        }
    }

    /**
     * Check if user should be activated based on point threshold
     * This should only be called for activation packages or manual activations
     * NOT for regular product purchases
     */
    private function checkUserActivation(User $user, $acquisitionType = null)
    {
        // Only activate for specific acquisition types, NOT product purchases
        if (in_array($acquisitionType, ['product_purchase', 'external_addition'])) {
            Log::info('Skipping user activation for product purchase - user should be manually activated', [
                'user_id' => $user->id,
                'acquisition_type' => $acquisitionType,
                'reserve_points' => $user->reserve_points
            ]);
            return;
        }
        
        // Reload to get updated points
        $user->refresh();
        
        $totalPoints = $user->reserve_points ?? 0;
        
        if ($totalPoints >= 100 && !$user->is_active) {
            $user->update([
                'is_active' => true,
                'activated_at' => now()
            ]);
            
            Log::info('User automatically activated for reaching 100+ points', [
                'user_id' => $user->id,
                'total_points' => $totalPoints,
                'activated_at' => now(),
                'acquisition_type' => $acquisitionType
            ]);
        }
    }
 
    /**
     * Process sponsor bonus (direct referrer)
     */
    public function processSponsorBonus(User $user, $pointsEarned)
    {
        Log::info('Processing sponsor bonus', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'points_earned' => $pointsEarned,
            'sponsor_id' => $user->sponsor_id ?? 'NULL'
        ]);

        // Check if user has sponsor_id
        if (!$user->sponsor_id) {
            Log::warning('No sponsor_id found for user', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
            return 0;
        }

        // Get sponsor user directly by ID
        $sponsor = User::find($user->sponsor_id);
        
        Log::info('Sponsor lookup result', [
            'user_id' => $user->id,
            'sponsor_id' => $user->sponsor_id,
            'sponsor_found' => $sponsor ? true : false,
            'sponsor_name' => $sponsor ? $sponsor->name : 'NULL'
        ]);
        
        if (!$sponsor) {
            Log::warning('Sponsor user not found in database', [
                'user_id' => $user->id,
                'sponsor_id' => $user->sponsor_id
            ]);
            return 0;
        }

        // Get sponsor commission setting
        $sponsorSetting = CommissionSetting::where('type', 'sponsor')
            ->where('is_active', true)
            ->first();

        if (!$sponsorSetting) {
            return 0;
        }

        // Calculate bonus in Taka (not points)
        $bonusAmountTaka = ($pointsEarned * 6 * $sponsorSetting->value) / 100; // Points to Taka, then percentage

        // Log commission creation attempt
        Log::info('Creating sponsor commission', [
            'sponsor_id' => $sponsor->id,
            'user_id' => $user->id,
            'points_earned' => $pointsEarned,
            'points_value_taka' => $pointsEarned * 6,
            'commission_percentage' => $sponsorSetting->value,
            'commission_amount_taka' => $bonusAmountTaka
        ]);

        // Create commission record
        try {
            $commission = Commission::create([
                'user_id' => $sponsor->id,
                'referred_user_id' => $user->id,
                'order_id' => null,
                'commission_type' => 'sponsor',
                'level' => 1,
                'order_amount' => $pointsEarned * 6, // Points converted to Taka value (1 point = 6 taka)
                'commission_rate' => $sponsorSetting->value / 100, // Store as decimal (0.20 for 20%)
                'commission_amount' => $bonusAmountTaka, // Commission in Taka
                'status' => 'approved',
                'notes' => "Sponsor bonus for {$pointsEarned} points (৳" . ($pointsEarned * 6) . ") at {$sponsorSetting->value}% = ৳{$bonusAmountTaka}",
                'earned_at' => now(),
                'approved_at' => now(),
                'approved_by' => 1 // System user ID
            ]);

            Log::info('Sponsor commission created successfully', [
                'commission_id' => $commission->id,
                'sponsor_id' => $sponsor->id,
                'amount_taka' => $commission->commission_amount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create sponsor commission', [
                'error' => $e->getMessage(),
                'sponsor_id' => $sponsor->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        // Pay commission to sponsor's interest_wallet (in Taka, not points)
        $sponsor->increment('interest_wallet', $bonusAmountTaka);
        
        Log::info('Sponsor commission paid to interest_wallet', [
            'sponsor_id' => $sponsor->id,
            'amount_taka' => $bonusAmountTaka,
            'new_interest_wallet_balance' => $sponsor->fresh()->interest_wallet
        ]);

        return $bonusAmountTaka; // Return Taka amount, not points
    }

    /**
     * Process generation bonus (20 levels)
     */
    private function processGenerationBonus(User $user, $pointsEarned)
    {
        $generationSetting = CommissionSetting::where('type', 'generation')
            ->where('is_active', true)
            ->first();

        if (!$generationSetting) {
            return ['total_bonus' => 0, 'details' => []];
        }

        $totalBonus = 0;
        $details = [];
        
        // Start with the user's direct sponsor using sponsor_id
        $currentUserId = $user->sponsor_id;
        $level = 1;

        // If levels array is empty, use the main value for all levels up to max_levels
        $levelPercentages = !empty($generationSetting->levels) 
            ? $generationSetting->levels 
            : array_fill(0, $generationSetting->max_levels, ['value' => $generationSetting->value]);

        while ($currentUserId && $level <= $generationSetting->max_levels && isset($levelPercentages[$level - 1])) {
            // Get current user by ID
            $currentUser = User::find($currentUserId);
            
            if (!$currentUser) {
                Log::warning('Generation user not found', [
                    'level' => $level,
                    'user_id' => $currentUserId
                ]);
                break;
            }
            
            $levelData = $levelPercentages[$level - 1];
            $percentage = is_array($levelData) ? $levelData['value'] : $levelData;
            
            // Calculate bonus in Taka (not points)
            $bonusAmountTaka = ($pointsEarned * 6 * $percentage) / 100; // Points to Taka, then percentage
            
            if ($bonusAmountTaka > 0) {
                // Log generation commission creation
                Log::info('Creating generation commission', [
                    'level' => $level,
                    'user_id' => $currentUser->id,
                    'referred_user_id' => $user->id,
                    'points_earned' => $pointsEarned,
                    'points_value_taka' => $pointsEarned * 6,
                    'percentage' => $percentage,
                    'commission_amount_taka' => $bonusAmountTaka
                ]);

                // Create commission record
                try {
                    $commission = Commission::create([
                        'user_id' => $currentUser->id,
                        'referred_user_id' => $user->id,
                        'order_id' => null,
                        'commission_type' => 'generation',
                        'level' => $level,
                        'order_amount' => $pointsEarned * 6, // Points converted to Taka value (1 point = 6 taka)
                        'commission_rate' => $percentage / 100, // Store as decimal (0.20 for 20%)
                        'commission_amount' => $bonusAmountTaka, // Commission in Taka
                        'status' => 'approved',
                        'notes' => "Generation Level {$level} bonus for {$pointsEarned} points (৳" . ($pointsEarned * 6) . ") at {$percentage}% = ৳{$bonusAmountTaka}",
                        'earned_at' => now(),
                        'approved_at' => now(),
                        'approved_by' => 1 // System user ID
                    ]);

                    Log::info('Generation commission created successfully', [
                        'commission_id' => $commission->id,
                        'level' => $level,
                        'user_id' => $currentUser->id,
                        'amount_taka' => $commission->commission_amount
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create generation commission', [
                        'error' => $e->getMessage(),
                        'level' => $level,
                        'user_id' => $currentUser->id,
                        'referred_user_id' => $user->id,
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Don't throw here, continue with other levels
                }

                // Pay commission to user's interest_wallet (in Taka, not points)
                $currentUser->increment('interest_wallet', $bonusAmountTaka);
                
                Log::info('Generation commission paid to interest_wallet', [
                    'level' => $level,
                    'user_id' => $currentUser->id,
                    'amount_taka' => $bonusAmountTaka,
                    'new_interest_wallet_balance' => $currentUser->fresh()->interest_wallet
                ]);

                // Store details
                $details["level_{$level}"] = [
                    'user_id' => $currentUser->id,
                    'user_name' => $currentUser->name,
                    'percentage' => $percentage,
                    'bonus_amount_taka' => $bonusAmountTaka
                ];

                $totalBonus += $bonusAmountTaka; // Total bonus in Taka
            }

            // Move to next level using sponsor_id
            $currentUserId = $currentUser->sponsor_id;
            $level++;
        }

        return [
            'total_bonus' => $totalBonus,
            'details' => $details
        ];
    }

    /**
     * Process direct point purchase
     */
    public function processDirectPointPurchase(User $user, $pointsToBuy, $amountFromWallet, $plan = null)
    {
        // Validate user has sufficient balance
        if ($user->deposit_wallet < $amountFromWallet) {
            throw new \Exception("Insufficient balance in deposit wallet.");
        }

        DB::transaction(function () use ($user, $pointsToBuy, $amountFromWallet, $plan) {
            // Deduct from deposit wallet
            $user->decrement('deposit_wallet', $amountFromWallet);

            // Determine acquisition type and source
            $acquisitionType = $plan ? 'plan_purchase' : 'direct_purchase';
            $source = $plan ? "Plan: {$plan->name}" : 'direct_purchase';

            // Process point acquisition
            $this->processPointAcquisition(
                $user,
                $pointsToBuy,
                $acquisitionType,
                $amountFromWallet,
                $source
            );
            
            // If plan purchase, also create a purchase record
            if ($plan) {
                $this->createPlanPurchaseRecord($user, $plan, $pointsToBuy, $amountFromWallet);
            }
        });
    }

    /**
     * Process product point purchase with account activation
     */
    public function processProductPointPurchase(User $user, $pointsToBuy, $amountFromWallet, $product = null, $shippingAddress = null)
    {
        // Validate user has sufficient balance
        if ($user->deposit_wallet < $amountFromWallet) {
            throw new \Exception("Insufficient balance in deposit wallet.");
        }

        return DB::transaction(function () use ($user, $pointsToBuy, $amountFromWallet, $product, $shippingAddress) {
            // Deduct from deposit wallet
            $user->decrement('deposit_wallet', $amountFromWallet);

            // Create completed order if product provided
            $order = null;
            if ($product) {
                $order = $this->createProductOrder($user, $product, $amountFromWallet, $shippingAddress);
            }

            // Determine acquisition type and source
            $acquisitionType = 'product_purchase';
            $source = $product ? "Product: {$product->name}" . ($order ? " (Order: {$order->order_number})" : '') : 'product_purchase';

            // Process point acquisition
            $this->processPointAcquisition(
                $user,
                $pointsToBuy,
                $acquisitionType,
                $amountFromWallet,
                $source
            );
            
            // Create product purchase record
            if ($product) {
                $this->createProductPurchaseRecord($user, $product, $pointsToBuy, $amountFromWallet, $order);
            }

            // Check for binary matching eligibility (100+ points)
            $this->checkBinaryMatchingEligibility($user);

            return $order;
        });
    }

    /**
     * Create product purchase record for history tracking
     */
    private function createProductPurchaseRecord(User $user, $product, $pointsReceived, $amountPaid, $order = null)
    {
        Log::info('Product Purchase Record', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'points_received' => $pointsReceived,
            'amount_paid' => $amountPaid,
            'is_activation_package' => $product->is_starter_kit,
            'order_id' => $order ? $order->id : null,
            'order_number' => $order ? $order->order_number : null,
            'purchase_date' => now()
        ]);
    }

    /**
     * Create a completed product order
     */
    private function createProductOrder(User $user, $product, $amountToPay, $shippingAddress = null)
    {
        // Generate unique order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

        // Create the order
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $user->id,
            'vendor_id' => $product->vendor_id,
            'status' => 'delivered', // Immediately delivered for point purchases
            'payment_status' => 'paid', // Already paid from wallet
            'shipping_status' => $product->is_digital ? 'delivered' : 'not_shipped',
            'total_amount' => $amountToPay,
            'tax_amount' => 0,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'subtotal' => $amountToPay,
            'currency' => 'BDT',
            'payment_method' => 'deposit_wallet',
            'shipping_method' => $product->is_digital ? 'digital_delivery' : 'standard',
            'shipping_address' => $shippingAddress,
            'billing_address' => $shippingAddress, // Use same address for billing
            'payment_details' => [
                'method' => 'deposit_wallet',
                'wallet_balance_before' => $user->deposit_wallet + $amountToPay,
                'wallet_balance_after' => $user->deposit_wallet,
                'transaction_type' => 'point_purchase'
            ],
            'notes' => 'Point purchase - Account activation product',
            'created_by' => $user->id,
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $amountToPay,
            'total' => $amountToPay,
        ]);

        // If digital product, mark as delivered
        if ($product->is_digital) {
            $order->update([
                'shipped_at' => now(),
                'delivered_at' => now(),
            ]);
        }

        return $order;
    }

    /**
     * Check if user is eligible for binary matching and activation (100+ points)
     */
    private function checkBinaryMatchingEligibility(User $user)
    {
        // Reload user to get updated points
        $user->refresh();
        
        // Check total points (reserve_points + any other point fields)
        $totalPoints = $user->reserve_points ?? 0;
        
        Log::info('Checking user activation eligibility', [
            'user_id' => $user->id,
            'current_points' => $totalPoints,
            'is_active' => $user->is_active,
            'binary_eligible' => $user->binary_eligible ?? false
        ]);
        
        if ($totalPoints >= 100) {
            $updates = [];
            
            // 1. Activate user account if not already active
            if (!$user->is_active) {
                $updates['is_active'] = true;
                $updates['activated_at'] = now();
                
                Log::info('User account activated', [
                    'user_id' => $user->id,
                    'total_points' => $totalPoints,
                    'activation_threshold' => 100
                ]);
            }
            
            // 2. Make eligible for binary matching if not already
            if (!$user->binary_eligible) {
                $updates['binary_eligible'] = true;
                $updates['binary_eligible_at'] = now();
                
                Log::info('User binary matching eligible', [
                    'user_id' => $user->id,
                    'total_points' => $totalPoints,
                    'eligibility_threshold' => 100
                ]);
            }
            
            // Apply updates if any
            if (!empty($updates)) {
                $user->update($updates);
                
                Log::info('User status updated for 100+ points', [
                    'user_id' => $user->id,
                    'updates' => $updates,
                    'total_points' => $totalPoints
                ]);
            }
        }
    }

    /**
     * Create plan purchase record for history tracking
     */
    private function createPlanPurchaseRecord(User $user, $plan, $pointsReceived, $amountPaid)
    {
        // You can create a separate purchase history table or use existing models
        // For now, we'll just log it in the DailyPointDistribution with additional fields
        
        // This could be expanded to create records in a separate plan_purchases table
        // if you want more detailed purchase history tracking
        
        Log::info('Plan Purchase Record', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'points_received' => $pointsReceived,
            'amount_paid' => $amountPaid,
            'purchase_date' => now()
        ]);
    }

    /**
     * Get today's distribution summary
     */
    public function getTodaysSummary()
    {
        $today = today();
        
        return [
            'total_distributions' => DailyPointDistribution::whereDate('distribution_date', $today)->count(),
            'total_points_distributed' => DailyPointDistribution::whereDate('distribution_date', $today)->sum('points_acquired'),
            'total_sponsor_bonus' => DailyPointDistribution::whereDate('distribution_date', $today)->sum('sponsor_bonus'),
            'total_generation_bonus' => DailyPointDistribution::whereDate('distribution_date', $today)->sum('generation_bonus'),
            'pending_distributions' => DailyPointDistribution::whereDate('distribution_date', $today)->where('is_processed', false)->count()
        ];
    }
}
