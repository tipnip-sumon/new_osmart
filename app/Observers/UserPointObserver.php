<?php

namespace App\Observers;

use App\Models\User;
use App\Services\DailyPointDistributionService;
use Illuminate\Support\Facades\Log;

class UserPointObserver
{
    protected $distributionService;

    public function __construct(DailyPointDistributionService $distributionService)
    {
        $this->distributionService = $distributionService;
    }

    /**
     * Handle the User "updated" event.
     * This triggers when any user field is updated, including reserve_points
     */
    public function updated(User $user)
    {
        // Check if reserve_points was changed and user now has 100+ points
        if ($user->wasChanged('reserve_points')) {
            $oldPoints = $user->getOriginal('reserve_points') ?? 0;
            $newPoints = $user->reserve_points ?? 0;
            $pointsAdded = $newPoints - $oldPoints;

            // Skip if this is likely a product purchase (check for recent transactions and current transaction context)
            if ($this->isRecentProductPurchase($user, $pointsAdded)) {
                Log::info('Skipping commission distribution - detected recent product purchase', [
                    'user_id' => $user->id,
                    'points_added' => $pointsAdded,
                    'total_points' => $newPoints
                ]);
                return;
            }

            // Only trigger if points were added (not removed) and user now has 100+ points
            if ($pointsAdded > 0 && $newPoints >= 100 && $oldPoints < 100) {
                $this->triggerCommissionDistribution($user, $pointsAdded, 'external_point_addition');
            }
            // Also trigger if significant points were added (even if already above 100)
            elseif ($pointsAdded >= 100) {
                $this->triggerCommissionDistribution($user, $pointsAdded, 'external_point_addition');
            }
        }
    }

    /**
     * Check if this point change is due to a recent product purchase
     * Enhanced to handle race conditions in database transactions
     */
    private function isRecentProductPurchase(User $user, $pointsAdded = null)
    {
        // Method 1: Check if we're in a product purchase request context
        $request = request();
        if ($request && $request->route()) {
            $routeName = $request->route()->getName();
            $actionName = $request->route()->getActionName();
            
            // Check if this request is coming from product purchase endpoints
            if (str_contains($routeName, 'direct-point-purchase') || 
                str_contains($actionName, 'purchaseProduct') ||
                str_contains($actionName, 'DirectPointPurchase')) {
                
                Log::info('Detected product purchase context via request route', [
                    'user_id' => $user->id,
                    'route_name' => $routeName,
                    'action_name' => $actionName,
                    'points_added' => $pointsAdded
                ]);
                return true;
            }
        }
        
        // Method 2: Check if there was a product_purchase transaction in the last 10 minutes
        $recentTransaction = \App\Models\PointTransaction::where('user_id', $user->id)
            ->where('reference_type', 'product_purchase')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();
        
        if ($recentTransaction) {
            return true;
        }
         
        // Method 3: Check if there's a matching transaction for the exact points added (race condition protection)
        if ($pointsAdded !== null && $pointsAdded > 0) {
            $matchingTransaction = \App\Models\PointTransaction::where('user_id', $user->id)
                ->where('reference_type', 'product_purchase')
                ->where('amount', $pointsAdded)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->exists();
            
            if ($matchingTransaction) {
                return true;
            }
        }
        
        // Method 4: Check for recent order activity (product purchases create orders)
        $recentOrder = \App\Models\Order::where('customer_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->where('payment_method', 'app_balance') // Product purchases use app_balance
            ->exists();
        
        if ($recentOrder) {
            Log::info('Detected recent product purchase via order history', [
                'user_id' => $user->id,
                'points_added' => $pointsAdded
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Trigger commission distribution for points earned outside the direct purchase system
     */
    private function triggerCommissionDistribution(User $user, $pointsAdded, $source)
    {
        try {
            Log::info('Auto-triggering commission distribution for external point addition', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'points_added' => $pointsAdded,
                'total_points' => $user->reserve_points,
                'source' => $source
            ]);

            // Check if user already has distribution today to avoid duplicates
            $hasDistributionToday = \App\Models\DailyPointDistribution::hasDistributionToday($user->id);
            
            if (!$hasDistributionToday) {
                // Trigger commission distribution
                $this->distributionService->processPointAcquisition(
                    $user,
                    $pointsAdded,
                    'external_addition', // Custom acquisition type
                    null, // No purchase amount
                    $source . ' - Auto-triggered commission distribution'
                );

                Log::info('Commission distribution completed for external point addition', [
                    'user_id' => $user->id,
                    'points_processed' => $pointsAdded
                ]);
            } else {
                Log::info('Skipped commission distribution - user already has distribution today', [
                    'user_id' => $user->id,
                    'points_added' => $pointsAdded
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to trigger commission distribution for external point addition', [
                'user_id' => $user->id,
                'points_added' => $pointsAdded,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
