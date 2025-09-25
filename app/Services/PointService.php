<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointService
{
    /**
     * Allocate points to user when purchasing a product
     * Points are added to reserve_points and activate when >= 100
     */
    public function allocatePointsForPurchase(User $user, Product $product, $quantity = 1)
    {
        try {
            DB::beginTransaction();
            
            // Calculate points based on product PV points or price
            $pointsPerUnit = $this->calculateProductPoints($product);
            $totalPoints = $pointsPerUnit * $quantity;
            
            // Add to user's reserve points
            $user->increment('reserve_points', $totalPoints);
            $user->increment('total_points_earned', $totalPoints);
            
            // Log point allocation IMMEDIATELY after increment (for every point amount)
            $this->logPointTransaction($user, $totalPoints, 'purchase', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'points_per_unit' => $pointsPerUnit
            ]);
            
            // NOTE: Do NOT auto-activate points for product purchases
            // Points should remain in reserve until user buys a starter kit or manually activates
            // Only activation packages should trigger point activation
            
            DB::commit();
            
            Log::info("Points allocated to user {$user->id}: {$totalPoints} points for product {$product->name}");
            
            return [
                'success' => true,
                'points_allocated' => $totalPoints,
                'total_reserve_points' => $user->fresh()->reserve_points,
                'total_active_points' => $user->fresh()->active_points
            ];
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error allocating points to user {$user->id}: " . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Calculate points for a product
     * Priority: PV Points > Price-based points
     */
    private function calculateProductPoints(Product $product)
    {
        // If product has PV points defined, use those
        if (!empty($product->pv_points) && $product->pv_points > 0) {
            return $product->pv_points;
        }
        
        // Otherwise, calculate points based on price (1 point = 6 Tk)
        $price = $product->sale_price ?? $product->price;
        return floor($price / 6); // 1 point for every 6 Tk
    }
    
    /**
     * Activate reserve points when they reach 100 or more
     */
    private function activateReservePoints(User $user)
    {
        if ($user->reserve_points >= 100) {
            // Move points from reserve to active
            $pointsToActivate = floor($user->reserve_points / 100) * 100; // Activate in multiples of 100
            
            $user->increment('active_points', $pointsToActivate);
            $user->decrement('reserve_points', $pointsToActivate);
            
            // Log the activation transaction
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $pointsToActivate,
                'description' => "Point activation: {$pointsToActivate} points moved from reserve to active",
                'reference_type' => 'point_activation',
                'reference_id' => null,
                'status' => 'completed'
            ]);
            
            Log::info("Activated {$pointsToActivate} points for user {$user->id}");
            
            // Ensure BinarySummary entry exists for users with 100+ points
            $this->ensureBinarySummaryForActivatedUser($user);
            
            return $pointsToActivate;
        }
        
        return 0;
    }
    
    /**
     * Get user's point balance summary
     */
    public function getUserPointBalance(User $user)
    {
        // Refresh user data to ensure we have the latest values
        $user->refresh();
        
        $reservePoints = $user->reserve_points ?? 0;
        $activePoints = $user->active_points ?? 0;
        $totalEarned = $user->total_points_earned ?? 0;
        $totalUsed = $user->total_points_used ?? 0;
        
        // Validation: Active points should not exceed total earned
        if ($activePoints > $totalEarned) {
            Log::warning("Point balance validation error", [
                'user_id' => $user->id,
                'username' => $user->username,
                'active_points' => $activePoints,
                'total_earned' => $totalEarned,
                'reserve_points' => $reservePoints,
                'total_used' => $totalUsed
            ]);
        }
        
        // Validation: Total earned should equal reserve + active + used
        $calculatedTotal = $reservePoints + $activePoints + $totalUsed;
        if (abs($totalEarned - $calculatedTotal) > 1) { // Allow small rounding difference
            Log::warning("Point total mismatch", [
                'user_id' => $user->id,
                'username' => $user->username,
                'total_earned_db' => $totalEarned,
                'calculated_total' => $calculatedTotal,
                'reserve' => $reservePoints,
                'active' => $activePoints,
                'used' => $totalUsed
            ]);
        }
        
        return [
            'reserve_points' => $reservePoints,
            'active_points' => $activePoints,
            'total_points_earned' => $totalEarned,
            'total_points_used' => $totalUsed,
            'points_ready_for_activation' => floor($reservePoints / 100) * 100,
            'points_until_activation' => 100 - ($reservePoints % 100)
        ];
    }
    
    /**
     * Process points usage in binary matching
     */
    public function usePointsForMatching(User $user, $pointsUsed)
    {
        try {
            DB::beginTransaction();
            
            if ($user->active_points < $pointsUsed) {
                throw new \Exception("Insufficient active points for matching");
            }
            
            // Deduct from active points and add to used points
            $user->decrement('active_points', $pointsUsed);
            $user->increment('total_points_used', $pointsUsed);
            
            // Log points usage as debit transaction
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $pointsUsed,
                'description' => "Point deduction: {$pointsUsed} points used for binary matching",
                'reference_type' => 'binary_matching',
                'reference_id' => null,
                'status' => 'completed'
            ]);
            
            DB::commit();
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error using points for user {$user->id}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ensure BinarySummary entry exists for users with 100+ active points
     */
    protected function ensureBinarySummaryForActivatedUser(User $user)
    {
        // Only ensure binary summary for users with 100+ active points
        if (($user->active_points ?? 0) >= 100) {
            $user->getOrCreateBinarySummary();
            
            Log::info("Binary summary ensured for activated user", [
                'user_id' => $user->id,
                'active_points' => $user->active_points,
                'threshold_met' => true
            ]);
        }
    }
    
    /**
     * Log point transactions for audit trail
     */
    private function logPointTransaction(User $user, $points, $type, $metadata = [])
    {
        // Determine transaction type based on operation
        $transactionType = 'credit'; // Most point allocations are credits
        $referenceType = null;
        $referenceId = null;
        
        switch ($type) {
            case 'purchase':
                $referenceType = 'product_purchase';
                $referenceId = $metadata['product_id'] ?? null;
                break;
            case 'binary_matching':
                $transactionType = 'debit'; // Using points is a debit
                $referenceType = 'binary_matching';
                break;
            case 'package_activation':
                $referenceType = 'package_activation';
                $referenceId = $metadata['package_id'] ?? null;
                break;
            default:
                $referenceType = $type;
        }

        PointTransaction::create([
            'user_id' => $user->id,
            'type' => $transactionType,
            'amount' => $points,
            'description' => "Point {$type}: {$points} points" . (isset($metadata['product_name']) ? " for {$metadata['product_name']}" : ''),
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'status' => 'completed'
        ]);
    }
    
    /**
     * Check if user has minimum points for binary matching
     */
    public function hasMinimumPointsForMatching(User $user)
    {
        $leftPoints = 0;
        $rightPoints = 0;
        
        // Get direct downline users
        $leftUser = User::where('sponsor_id', $user->id)->where('position', 'left')->first();
        $rightUser = User::where('sponsor_id', $user->id)->where('position', 'right')->first();
        
        if ($leftUser) {
            $leftPoints = $this->calculateUserTotalPoints($leftUser);
        }
        
        if ($rightUser) {
            $rightPoints = $this->calculateUserTotalPoints($rightUser);
        }
        
        return [
            'left_points' => $leftPoints,
            'right_points' => $rightPoints,
            'qualified' => ($leftPoints >= 100 && $rightPoints >= 100)
        ];
    }
    
    /**
     * Calculate total points for a user and their downline
     */
    private function calculateUserTotalPoints(User $user)
    {
        // Only count active points (reserve points must be >= 100 to count)
        $userPoints = $user->active_points ?? 0;
        
        // Add points from immediate downline
        $downline = User::where('sponsor_id', $user->id)->get();
        $downlinePoints = 0;
        
        foreach ($downline as $downlineUser) {
            $downlinePoints += $this->calculateUserTotalPoints($downlineUser);
        }
        
        return $userPoints + $downlinePoints;
    }
}
