<?php

namespace App\Services;

use App\Models\User;
use App\Models\Plan;
use App\Models\UserActivePackage;
use App\Models\UserPackageHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PackageManagementService
{
    /**
     * Purchase a new package for the user using accumulated points
     */
    public function purchasePackage(User $user, Plan $plan, $source = 'direct', $productId = null, $orderId = null)
    {
        DB::beginTransaction();

        try {
            // Calculate values
            $packageTier = $this->getPackageTier($plan);
            $pointsRequired = $this->calculatePointsForPackage($plan);
            $nextPayoutDate = $this->calculateNextPayoutDate($plan);

            // Validate upgrade path
            $this->validateUpgradePath($user, $packageTier);

            // Check if user has sufficient reserve points for activation
            if ($user->reserve_points < $pointsRequired) {
                throw new \Exception("Insufficient points for package activation. Required: {$pointsRequired}, Available: {$user->reserve_points}. Please purchase products to accumulate more points.");
            }

            // Create active package record
            $activePackage = UserActivePackage::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'package_tier' => $packageTier,
                'points_allocated' => $pointsRequired,
                'points_remaining' => $pointsRequired,
                'amount_invested' => 0, // No direct payment - point-based activation
                'activated_at' => now(),
                'next_payout_eligible_at' => $nextPayoutDate,
                'is_active' => true,
                'product_id' => $productId,
                'order_id' => $orderId
            ]);

            // Create history record
            UserPackageHistory::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'active_package_id' => $activePackage->id,
                'action_type' => 'purchase',
                'points_before' => $user->reserve_points,
                'points_changed' => -$pointsRequired, // Negative because points are deducted
                'points_after' => $user->reserve_points - $pointsRequired,
                'package_tier' => $packageTier,
                'amount_involved' => 0, // No direct payment involved
                'source' => $source,
                'product_id' => $productId,
                'order_id' => $orderId,
                'metadata' => json_encode([
                    'plan_name' => $plan->name,
                    'next_payout_date' => $nextPayoutDate,
                    'activation_method' => 'point_based',
                    'points_used_for_activation' => $pointsRequired
                ])
            ]);

            // Deduct points from reserve_points for package activation
            $user->decrement('reserve_points', $pointsRequired);
            
            // Add points to active_points (now available for binary matching)
            $user->increment('active_points', $pointsRequired);

            // Update user summary fields
            $this->updateUserSummaryFields($user);

            // Distribute commissions based on activated points
            $this->distributePackageCommissions($user, $plan, $pointsRequired);

            DB::commit();

            return $activePackage;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Package activation failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'user_reserve_points' => $user->reserve_points ?? 0,
                'points_required' => $pointsRequired ?? 0,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Process payouts for eligible packages
     */
    public function processPayouts(User $user)
    {
        DB::beginTransaction();

        try {
            $eligiblePackages = $user->eligiblePackages()->get();
            $totalPayoutProcessed = 0;

            foreach ($eligiblePackages as $package) {
                $pointsRemaining = $package->points_remaining;
                
                if ($pointsRemaining > 0) {
                    // Create history record for payout
                    UserPackageHistory::create([
                        'user_id' => $user->id,
                        'plan_id' => $package->plan_id,
                        'active_package_id' => $package->id,
                        'action_type' => 'payout',
                        'points_before' => $package->points_remaining,
                        'points_changed' => -$pointsRemaining,
                        'points_after' => 0,
                        'package_tier' => $package->package_tier,
                        'amount_involved' => 0,
                        'source' => 'payout_processing',
                        'metadata' => json_encode([
                            'payout_date' => now(),
                            'points_paid_out' => $pointsRemaining
                        ])
                    ]);

                    // Update package
                    $package->update([
                        'points_remaining' => 0,
                        'total_payout_received' => $package->total_payout_received + $pointsRemaining,
                        'last_payout_at' => now(),
                        'next_payout_eligible_at' => null,
                        'payout_count' => $package->payout_count + 1,
                        'is_active' => false
                    ]);

                    $totalPayoutProcessed += $pointsRemaining;
                }
            }

            // Update user summary
            if ($totalPayoutProcessed > 0) {
                $this->updateUserSummaryFields($user);
            }

            DB::commit();
            return $totalPayoutProcessed;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payout processing failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get package tier from plan
     */
    protected function getPackageTier(Plan $plan)
    {
        return $plan->minimum_points ?? $plan->points ?? 100;
    }

    /**
     * Validate upgrade path - users can only purchase higher tier packages
     */
    protected function validateUpgradePath(User $user, $newPackageTier)
    {
        $activePackages = $user->activePackages()->get();
        
        if ($activePackages->isEmpty()) {
            return; // First package, no validation needed
        }

        $highestTier = $activePackages->max('package_tier');
        
        if ($newPackageTier <= $highestTier) {
            throw new \Exception("You can only purchase packages higher than your current highest tier ({$highestTier}). Selected tier: {$newPackageTier}");
        }
    }

    /**
     * Update user's summary fields based on all active packages
     */
    protected function updateUserSummaryFields(User $user)
    {
        $user->refresh();
        $activePackages = $user->activePackages()->get();
        
        if ($activePackages->isNotEmpty()) {
            $highestTierPackage = $activePackages->sortByDesc('package_tier')->first();
            
            $user->update([
                'current_package_id' => $highestTierPackage->plan_id,
                'current_package_tier' => $highestTierPackage->package_tier,
                'accumulated_points' => $activePackages->sum('points_remaining'),
                'package_activated_at' => $activePackages->min('activated_at'),
                'total_package_investment' => $activePackages->sum('amount_invested'),
                'next_payout_eligible_at' => $activePackages->whereNotNull('next_payout_eligible_at')->min('next_payout_eligible_at'),
                'is_active' => true
            ]);
        }
    }

    /**
     * Calculate package amount based on plan
     */
    protected function calculatePackageAmount(Plan $plan)
    {
        if ($plan->fixed_amount && $plan->fixed_amount > 0) {
            return $plan->fixed_amount;
        }
        return $plan->minimum ?? 100;
    }

    /**
     * Calculate points to award for package (public method)
     */
    public function calculatePointsForPackage(Plan $plan)
    {
        if ($plan->points_reward && $plan->points_reward > 0) {
            return $plan->points_reward;
        }
        
        if ($plan->minimum_points && $plan->minimum_points > 0) {
            return $plan->minimum_points;
        }
        
        // Fallback: calculate based on amount
        $amount = $this->calculatePackageAmount($plan);
        return intval($amount);
    }

    /**
     * Calculate next payout eligible date
     */
    protected function calculateNextPayoutDate(Plan $plan)
    {
        $days = $plan->time ?? 30;
        return now()->addDays($days);
    }

    /**
     * Update user points - no longer used for package activation
     * Points are now managed directly in purchasePackage method
     */
    protected function updateUserPoints(User $user, $points)
    {
        // This method is kept for backward compatibility but not used
        // in point-based package activation system
        $user->increment('active_points', $points);
        $user->increment('reserve_points', $points);
        $user->increment('total_points_earned', $points);
    }

    /**
     * Distribute commissions for package purchase
     */
    protected function distributePackageCommissions(User $user, Plan $plan, $points)
    {
        if (class_exists('App\Services\DailyPointDistributionService')) {
            $distributionService = new \App\Services\DailyPointDistributionService();
            
            if (method_exists($distributionService, 'processPointAcquisition')) {
                try {
                    $distributionService->processPointAcquisition($user, $points, 'package_purchase');
                } catch (\Exception $e) {
                    Log::error('Package commission distribution failed', [
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'points' => $points,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Get available packages for user (only higher tiers than current highest and affordable with reserve points)
     */
    public function getAvailablePackages(User $user = null)
    {
        $query = Plan::where('status', 1)
            ->where('point_based', 1)
            ->orderBy('minimum', 'asc');

        // If user exists, apply filters
        if ($user) {
            $activePackages = $user->activePackages()->get();
            
            // Only show packages higher than their current highest tier
            if ($activePackages->isNotEmpty()) {
                $highestTier = $activePackages->max('package_tier');
                
                $query->where(function($q) use ($highestTier) {
                    $q->where('minimum_points', '>', $highestTier)
                      ->orWhere('points_reward', '>', $highestTier)
                      ->orWhere('minimum', '>', $highestTier);
                });
            }

            // Filter packages based on user's reserve points
            $userReservePoints = $user->reserve_points ?? 0;
            $packages = $query->get();
            
            // Filter packages user can afford
            $availablePackages = $packages->filter(function($plan) use ($userReservePoints) {
                $pointsRequired = $this->calculatePointsForPackage($plan);
                return $userReservePoints >= $pointsRequired;
            });

            return $availablePackages;
        }

        return $query->get();
    }

    /**
     * Get user's package history
     */
    public function getUserPackageHistory(User $user, $limit = 20)
    {
        return UserPackageHistory::where('user_id', $user->id)
            ->with(['plan:id,name'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's package summary
     */
    public function getUserPackageSummary(User $user)
    {
        $activePackages = $user->activePackages()->get();
        
        return [
            'total_packages' => $activePackages->count(),
            'total_invested' => $activePackages->sum('amount_invested'),
            'total_points_remaining' => $activePackages->sum('points_remaining'),
            'total_payout_received' => $activePackages->sum('total_payout_received'),
            'packages_eligible_for_payout' => $user->eligiblePackages()->count(),
            'highest_tier' => $activePackages->max('package_tier') ?? 0,
            'latest_package_date' => $activePackages->max('activated_at')
        ];
    }

    /**
     * Check if user has any packages eligible for payout
     */
    public function isEligibleForPayout(User $user)
    {
        return $user->eligiblePackages()->exists();
    }

    /**
     * Check if user has sufficient points to activate a package
     */
    public function canActivatePackage(User $user, Plan $plan)
    {
        $pointsRequired = $this->calculatePointsForPackage($plan);
        return $user->reserve_points >= $pointsRequired;
    }

    /**
     * Get user's point status for package activation
     */
    public function getUserPointStatus(User $user)
    {
        return [
            'reserve_points' => $user->reserve_points ?? 0,
            'active_points' => $user->active_points ?? 0,
            'total_points_earned' => $user->total_points_earned ?? 0,
            'points_used_for_packages' => $user->activePackages()->sum('points_allocated') ?? 0
        ];
    }
}
