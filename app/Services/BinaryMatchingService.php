<?php

namespace App\Services;

use App\Models\User;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BinaryMatchingService
{
    /**
     * Calculate binary matching for a user for a specific date and period
     */
    public function calculateBinaryMatching(User $user, $date = null, $period = 'daily')
    {
        $date = $date ?: Carbon::now()->toDateString();
        
        try {
            DB::beginTransaction();
            
            // Get or create binary summary
            $summary = $user->getOrCreateBinarySummary();
            
            // Check if matching already exists for this date and period
            $existingMatching = BinaryMatching::where('user_id', $user->id)
                ->where('match_date', $date)
                ->where('period', $period)
                ->first();
            
            if ($existingMatching && $existingMatching->is_processed) {
                return $existingMatching; // Already processed
            }
            
            // Calculate left and right leg volumes
            $leftVolume = $this->calculateLegVolume($user, 'left', $date, $period);
            $rightVolume = $this->calculateLegVolume($user, 'right', $date, $period);
            
            // Get previous carry forward
            $previousCarry = $this->getPreviousCarryForward($user, $date, $period);
            
            // Calculate current volumes including carry forward
            $leftCurrentVolume = $leftVolume + $previousCarry['left'];
            $rightCurrentVolume = $rightVolume + $previousCarry['right'];
            
            // Calculate matching
            $matchingVolume = min($leftCurrentVolume, $rightCurrentVolume);
            $matchingPercentage = $this->getMatchingPercentage($user);
            $matchingBonus = $matchingVolume * ($matchingPercentage / 100);
            
            // Calculate slot matching if applicable
            $slotMatchData = $this->calculateSlotMatching($user, $leftCurrentVolume, $rightCurrentVolume);
            
            // Calculate carry forward for next period
            $leftCarryNext = max(0, $leftCurrentVolume - $matchingVolume);
            $rightCarryNext = max(0, $rightCurrentVolume - $matchingVolume);
            
            // Apply capping if necessary
            $cappingData = $this->applyCapping($user, $matchingBonus, $period);
            
            // Create or update binary matching record
            $binaryMatching = BinaryMatching::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'match_date' => $date,
                    'period' => $period,
                ],
                [
                    'left_total_sale' => $leftVolume,
                    'right_total_sale' => $rightVolume,
                    'total_sale' => $leftVolume + $rightVolume,
                    'left_carry_forward' => $previousCarry['left'],
                    'right_carry_forward' => $previousCarry['right'],
                    'left_current_volume' => $leftCurrentVolume,
                    'right_current_volume' => $rightCurrentVolume,
                    'matching_volume' => $matchingVolume,
                    'matching_percentage' => $matchingPercentage,
                    'matching_bonus' => $matchingBonus,
                    'slot_match_count' => $slotMatchData['count'],
                    'slot_match_bonus' => $slotMatchData['bonus'],
                    'left_carry_next' => $leftCarryNext,
                    'right_carry_next' => $rightCarryNext,
                    'daily_cap_limit' => $cappingData['daily_limit'],
                    'weekly_cap_limit' => $cappingData['weekly_limit'],
                    'monthly_cap_limit' => $cappingData['monthly_limit'],
                    'capped_amount' => $cappingData['capped'],
                    'status' => 'pending',
                    'is_processed' => false,
                    'transaction_ref' => $this->generateTransactionRef($user, $date, $period),
                    'carry_from_id' => $previousCarry['carry_from_id'],
                    'calculation_details' => $this->buildCalculationDetails($user, $leftVolume, $rightVolume, $matchingVolume, $matchingBonus, $slotMatchData, $cappingData),
                ]
            );
            
            // Update binary summary
            $this->updateBinarySummary($summary, $binaryMatching, $period);
            
            DB::commit();
            
            Log::info("Binary matching calculated for user {$user->id} on {$date} for {$period}");
            
            return $binaryMatching;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error calculating binary matching for user {$user->id}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Calculate leg volume for a specific side, date and period
     */
    private function calculateLegVolume(User $user, $side, $date, $period)
    {
        $volume = 0;
        
        // Get downline users for the specified side
        $downlineUsers = $side === 'left' ? $user->leftDownlines : $user->rightDownlines;
        
        foreach ($downlineUsers as $downlineUser) {
            // Calculate volume from this user's sales/investments
            $userVolume = $this->getUserVolume($downlineUser, $date, $period);
            $volume += $userVolume;
            
            // Recursively add volume from their downlines
            $volume += $this->calculateLegVolume($downlineUser, 'left', $date, $period);
            $volume += $this->calculateLegVolume($downlineUser, 'right', $date, $period);
        }
        
        return $volume;
    }
    
    /**
     * Get user volume from sales/investments for a specific date and period
     */
    private function getUserVolume(User $user, $date, $period)
    {
        $startDate = $this->getPeriodStartDate($date, $period);
        $endDate = $this->getPeriodEndDate($date, $period);
        
        // Calculate from investments/orders/sales
        $volume = 0;
        
        // Add investment volume if Invest model exists
        if (class_exists('App\Models\Invest')) {
            $volume += $user->invests()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        }
        
        // Add order volume if Order model exists
        if (class_exists('App\Models\Order')) {
            $volume += $user->orders()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_amount');
        }
        
        return $volume;
    }
    
    /**
     * Get previous carry forward amounts
     */
    private function getPreviousCarryForward(User $user, $date, $period)
    {
        $previousDate = $this->getPreviousPeriodDate($date, $period);
        
        $previousMatching = BinaryMatching::where('user_id', $user->id)
            ->where('match_date', $previousDate)
            ->where('period', $period)
            ->first();
        
        return [
            'left' => $previousMatching ? $previousMatching->left_carry_next : 0,
            'right' => $previousMatching ? $previousMatching->right_carry_next : 0,
            'carry_from_id' => $previousMatching ? $previousMatching->id : null,
        ];
    }
    
    /**
     * Get matching percentage based on user rank/plan
     */
    private function getMatchingPercentage(User $user)
    {
        // Default percentage (can be customized based on user rank/plan)
        $defaultPercentage = 10; // 10%
        
        // You can implement rank-based percentages here
        switch ($user->rank ?? 'bronze') {
            case 'diamond':
                return 20;
            case 'gold':
                return 15;
            case 'silver':
                return 12;
            default:
                return $defaultPercentage;
        }
    }
    
    /**
     * Calculate slot matching if applicable
     */
    private function calculateSlotMatching(User $user, $leftVolume, $rightVolume)
    {
        // Example slot matching logic (customize as needed)
        $slotSize = 1000; // $1000 per slot
        $slotBonus = 50;  // $50 per slot match
        
        $leftSlots = floor($leftVolume / $slotSize);
        $rightSlots = floor($rightVolume / $slotSize);
        $matchedSlots = min($leftSlots, $rightSlots);
        
        return [
            'count' => $matchedSlots,
            'bonus' => $matchedSlots * $slotBonus,
        ];
    }
    
    /**
     * Apply capping based on user limits
     */
    private function applyCapping(User $user, $matchingBonus, $period)
    {
        // Default limits (can be customized based on user rank/plan)
        $dailyLimit = 500;
        $weeklyLimit = 3000;
        $monthlyLimit = 10000;
        
        // Calculate current period totals for capping
        $currentTotal = $this->getCurrentPeriodTotal($user, $period);
        $availableLimit = $this->getAvailableLimit($user, $period, $dailyLimit, $weeklyLimit, $monthlyLimit);
        
        $cappedAmount = 0;
        if ($matchingBonus > $availableLimit) {
            $cappedAmount = $matchingBonus - $availableLimit;
            $matchingBonus = $availableLimit;
        }
        
        return [
            'daily_limit' => $dailyLimit,
            'weekly_limit' => $weeklyLimit,
            'monthly_limit' => $monthlyLimit,
            'capped' => $cappedAmount,
            'available' => $availableLimit,
        ];
    }
    
    /**
     * Update binary summary with new matching data
     */
    private function updateBinarySummary(BinarySummary $summary, BinaryMatching $matching, $period)
    {
        $summary->increment('lifetime_left_volume', $matching->left_total_sale);
        $summary->increment('lifetime_right_volume', $matching->right_total_sale);
        $summary->increment('lifetime_matching_bonus', $matching->matching_bonus);
        $summary->increment('lifetime_slot_bonus', $matching->slot_match_bonus);
        $summary->increment('lifetime_capped_amount', $matching->capped_amount);
        
        // Update carry balances
        $summary->update([
            'left_carry_balance' => $matching->left_carry_next,
            'right_carry_balance' => $matching->right_carry_next,
            'last_calculated_at' => now(),
        ]);
        
        // Update period-specific totals
        switch ($period) {
            case 'daily':
                $summary->increment('daily_left_volume', $matching->left_total_sale);
                $summary->increment('daily_right_volume', $matching->right_total_sale);
                $summary->increment('daily_matching_bonus', $matching->matching_bonus);
                $summary->increment('daily_capped_amount', $matching->capped_amount);
                break;
            case 'weekly':
                $summary->increment('weekly_left_volume', $matching->left_total_sale);
                $summary->increment('weekly_right_volume', $matching->right_total_sale);
                $summary->increment('weekly_matching_bonus', $matching->matching_bonus);
                $summary->increment('weekly_capped_amount', $matching->capped_amount);
                break;
            case 'monthly':
                $summary->increment('monthly_left_volume', $matching->left_total_sale);
                $summary->increment('monthly_right_volume', $matching->right_total_sale);
                $summary->increment('monthly_matching_bonus', $matching->matching_bonus);
                $summary->increment('monthly_capped_amount', $matching->capped_amount);
                break;
        }
        
        $summary->increment('total_matching_records');
        $summary->increment('total_slot_matches', $matching->slot_match_count);
    }
    
    /**
     * Generate transaction reference
     */
    private function generateTransactionRef(User $user, $date, $period)
    {
        return 'BIN-' . strtoupper($period) . '-' . $user->id . '-' . str_replace('-', '', $date) . '-' . time();
    }
    
    /**
     * Build detailed calculation information
     */
    private function buildCalculationDetails($user, $leftVolume, $rightVolume, $matchingVolume, $matchingBonus, $slotData, $cappingData)
    {
        return [
            'user_id' => $user->id,
            'username' => $user->username,
            'left_volume' => $leftVolume,
            'right_volume' => $rightVolume,
            'matching_volume' => $matchingVolume,
            'matching_bonus' => $matchingBonus,
            'slot_matches' => $slotData,
            'capping_applied' => $cappingData,
            'calculated_at' => now()->toISOString(),
        ];
    }
    
    /**
     * Helper methods for date calculations
     */
    private function getPeriodStartDate($date, $period)
    {
        $carbon = Carbon::parse($date);
        
        switch ($period) {
            case 'weekly':
                return $carbon->startOfWeek()->toDateString();
            case 'monthly':
                return $carbon->startOfMonth()->toDateString();
            default: // daily
                return $carbon->toDateString();
        }
    }
    
    private function getPeriodEndDate($date, $period)
    {
        $carbon = Carbon::parse($date);
        
        switch ($period) {
            case 'weekly':
                return $carbon->endOfWeek()->toDateString();
            case 'monthly':
                return $carbon->endOfMonth()->toDateString();
            default: // daily
                return $carbon->toDateString();
        }
    }
    
    private function getPreviousPeriodDate($date, $period)
    {
        $carbon = Carbon::parse($date);
        
        switch ($period) {
            case 'weekly':
                return $carbon->subWeek()->toDateString();
            case 'monthly':
                return $carbon->subMonth()->toDateString();
            default: // daily
                return $carbon->subDay()->toDateString();
        }
    }
    
    private function getCurrentPeriodTotal(User $user, $period)
    {
        // Implementation for getting current period totals for capping
        $summary = $user->binarySummary;
        if (!$summary) return 0;
        
        switch ($period) {
            case 'weekly':
                return $summary->weekly_matching_bonus;
            case 'monthly':
                return $summary->monthly_matching_bonus;
            default: // daily
                return $summary->daily_matching_bonus;
        }
    }
    
    private function getAvailableLimit(User $user, $period, $dailyLimit, $weeklyLimit, $monthlyLimit)
    {
        $currentTotal = $this->getCurrentPeriodTotal($user, $period);
        
        switch ($period) {
            case 'weekly':
                return max(0, $weeklyLimit - $currentTotal);
            case 'monthly':
                return max(0, $monthlyLimit - $currentTotal);
            default: // daily
                return max(0, $dailyLimit - $currentTotal);
        }
    }
}
