<?php

namespace App\Services;

use App\Models\User;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use App\Models\CommissionSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchingService
{
    /**
     * Calculate and process point-based matching bonus for a user
     * Min qualification: 100 points left + 100 points right
     * Matching rate: 10% of matched points
     * Point value: 1 point = 6 Tk
     */
    public function calculateMatchingBonus($userId, $leftPoints = null, $rightPoints = null)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception("User not found");
        }

        // Get point-based matching settings
        $matchingSettings = CommissionSetting::where('type', 'point_matching')
            ->where('is_active', true)
            ->first();

        if (!$matchingSettings) {
            // Create default point matching settings if not exists
            $matchingSettings = $this->createDefaultPointMatchingSettings();
        }

        // Get or calculate leg point volumes
        if ($leftPoints === null || $rightPoints === null) {
            $points = $this->calculateLegPoints($user);
            $leftPoints = $leftPoints ?? $points['left'];
            $rightPoints = $rightPoints ?? $points['right'];
        }

        // Check minimum qualification: 100 points each leg
        if ($leftPoints < 100 || $rightPoints < 100) {
            return null;
        }

        // Calculate matchable points
        $matchablePoints = min($leftPoints, $rightPoints);
        
        if ($matchablePoints <= 0) {
            return null;
        }

        // Calculate bonus: 10% of matched points * 6 Tk per point
        $pointValue = 6; // 1 point = 6 Tk
        $matchingPercentage = 10; // 10% matching
        
        $bonusAmount = $matchablePoints * ($matchingPercentage / 100) * $pointValue;
        // This gives us: matched_points * 0.10 * 6 = 0.6 Tk per matched point

        // Apply daily/weekly caps if configured
        $bonusAmount = $this->applyPayoutCaps($user, $bonusAmount, $matchingSettings);

        if ($bonusAmount <= 0) {
            return null;
        }

        // Create point-based matching bonus record
        $matching = $this->createPointMatchingBonus($user, $bonusAmount, $leftPoints, $rightPoints, $matchablePoints);

        // Update binary point summary
        $this->updateBinaryPointSummary($user, $leftPoints, $rightPoints, $matchablePoints);

        return $matching;
    }

    /**
     * Calculate potential point-based bonus without creating records
     */
    public function calculatePotentialBonus($user, $leftPoints, $rightPoints)
    {
        $matchablePoints = min($leftPoints, $rightPoints);
        
        $result = [
            'left_points' => $leftPoints,
            'right_points' => $rightPoints,
            'matchable_points' => $matchablePoints,
            'qualified' => ($leftPoints >= 100 && $rightPoints >= 100),
            'bonus_amount' => 0,
            'commission_rate' => 10, // Fixed 10% for point system
            'point_value' => 6,
            'min_qualification' => 100
        ];

        if ($result['qualified'] && $matchablePoints > 0) {
            $pointValue = 6; // 1 point = 6 Tk
            $matchingPercentage = 10; // 10% matching
            
            $bonusAmount = $matchablePoints * ($matchingPercentage / 100) * $pointValue;
            $result['bonus_amount'] = $bonusAmount;
        }

        return $result;
    }

    /**
     * Calculate leg points for a user (point-based system)
     * Should be consistent with DailyMatchingProcess logic
     */
    public function calculateLegPoints($user)
    {
        $leftPoints = $this->calculateLegPointsForPosition($user, 'left');
        $rightPoints = $this->calculateLegPointsForPosition($user, 'right');

        return [
            'left' => $leftPoints,
            'right' => $rightPoints
        ];
    }

    /**
     * Calculate total points for a specific leg position
     */
    private function calculateLegPointsForPosition($user, $position)
    {
        // Get all users in this leg position under the user
        $legUsers = User::where('upline_id', $user->id)
            ->where('position', $position)
            ->get();
        
        $totalPoints = 0;
        
        foreach ($legUsers as $legUser) {
            // Add user's active points (not reserve_points)
            $totalPoints += $legUser->active_points ?? 0;
            
            // Recursively add points from their downlines (unlimited generations)
            $totalPoints += $this->calculateTotalLegPoints($legUser);
        }
        
        return $totalPoints;
    }

    /**
     * Calculate total points for a leg (recursive for unlimited generations)
     */
    private function calculateTotalLegPoints($user)
    {
        $totalPoints = 0;
        
        // Get all direct downlines (both left and right)
        $downlines = User::where('upline_id', $user->id)->get();
        
        foreach ($downlines as $downline) {
            // Add downline's active points
            $totalPoints += $downline->active_points ?? 0;
            
            // Recursively add points from their downlines (unlimited depth)
            $totalPoints += $this->calculateTotalLegPoints($downline);
        }
        
        return $totalPoints;
    }

    /**
     * Check if user qualifies for point-based matching bonus
     */
    private function checkPointMatchingQualification($user, $matchingSettings)
    {
        $conditions = $matchingSettings->conditions ?? [];

        // Check minimum points in both legs requirement
        if (isset($conditions['both_legs_required']) && $conditions['both_legs_required']) {
            $minLegPoints = $conditions['min_leg_points'] ?? 100;
            $points = $this->calculateLegPoints($user);
            
            if ($points['left'] < $minLegPoints || $points['right'] < $minLegPoints) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get user's qualification level
     */
    private function getUserQualificationLevel($user, $matchingSettings)
    {
        $levels = $matchingSettings->levels ?? [];
        $userQualification = $user->monthly_sales_volume ?? 0;

        // Find the highest level the user qualifies for
        $qualifiedLevel = null;
        
        foreach ($levels as $level) {
            $minQualification = $level['min_qualification'] ?? 0;
            
            if ($userQualification >= $minQualification) {
                $qualifiedLevel = $level;
            }
        }

        return $qualifiedLevel;
    }

    /**
     * Apply daily and weekly payout caps
     */
    private function applyPayoutCaps($user, $bonusAmount, $matchingSettings)
    {
        $conditions = $matchingSettings->conditions ?? [];

        // Apply daily cap
        if (isset($conditions['daily_cap_enabled']) && $conditions['daily_cap_enabled']) {
            $dailyCapAmount = $conditions['daily_cap_amount'] ?? 0;
            $todayEarnings = BinaryMatching::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->sum('matching_bonus');

            $remainingDaily = max(0, $dailyCapAmount - $todayEarnings);
            $bonusAmount = min($bonusAmount, $remainingDaily);
        }

        // Apply weekly cap
        if (isset($conditions['weekly_cap_enabled']) && $conditions['weekly_cap_enabled']) {
            $weeklyCapAmount = $conditions['weekly_cap_amount'] ?? 0;
            $weekStart = now()->startOfWeek();
            $weeklyEarnings = BinaryMatching::where('user_id', $user->id)
                ->where('created_at', '>=', $weekStart)
                ->sum('matching_bonus');

            $remainingWeekly = max(0, $weeklyCapAmount - $weeklyEarnings);
            $bonusAmount = min($bonusAmount, $remainingWeekly);
        }

        return max(0, $bonusAmount);
    }

    /**
     * Create point-based matching bonus record
     */
    private function createPointMatchingBonus($user, $amount, $leftPoints, $rightPoints, $matchablePoints)
    {
        $leftUser = User::where('sponsor_id', $user->id)->where('position', 'left')->first();
        $rightUser = User::where('sponsor_id', $user->id)->where('position', 'right')->first();

        return BinaryMatching::create([
            'user_id' => $user->id,
            'match_date' => today(),
            'left_current_volume' => $leftPoints, // Store points instead of volume
            'right_current_volume' => $rightPoints,
            'matching_volume' => $matchablePoints,
            'matching_percentage' => 10, // Fixed 10% for point-based system
            'matching_bonus' => $amount,
            'status' => 'processed',
            'is_processed' => true,
            'processed_at' => now(),
            'bonus_type' => 'point_matching' // Distinguish from regular matching
        ]);
    }

    /**
     * Update binary point summary
     */
    private function updateBinaryPointSummary($user, $leftPoints, $rightPoints, $matchedPoints)
    {
        $carryForward = $this->calculatePointCarryForward($leftPoints, $rightPoints, $matchedPoints);

        BinarySummary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'left_total_volume' => $leftPoints, // Store as points
                'right_total_volume' => $rightPoints,
                'matched_volume' => $matchedPoints,
                'carry_forward' => $carryForward['total'],
                'left_carry' => $carryForward['left'],
                'right_carry' => $carryForward['right'],
                'updated_at' => now(),
                'summary_type' => 'points' // Mark as point-based summary
            ]
        );
    }

    /**
     * Calculate point carry forward
     */
    private function calculatePointCarryForward($leftPoints, $rightPoints, $matchedPoints)
    {
        $leftCarry = $leftPoints - $matchedPoints;
        $rightCarry = $rightPoints - $matchedPoints;
        $totalCarry = $leftCarry + $rightCarry;

        return [
            'left' => max(0, $leftCarry),
            'right' => max(0, $rightCarry),
            'total' => max(0, $totalCarry)
        ];
    }

    /**
     * Create default point matching settings
     */
    private function createDefaultPointMatchingSettings()
    {
        return CommissionSetting::create([
            'type' => 'point_matching',
            'name' => 'Point-Based Binary Matching',
            'is_active' => true,
            'settings' => [
                'min_left_points' => 100,
                'min_right_points' => 100,
                'matching_percentage' => 10,
                'point_value' => 6,
                'min_threshold' => 100
            ],
            'conditions' => [
                'both_legs_required' => true,
                'min_leg_points' => 100,
                'daily_cap_enabled' => false,
                'weekly_cap_enabled' => false
            ]
        ]);
    }

    /**
     * Update binary summary
     */
    private function updateBinarySummary($user, $leftVolume, $rightVolume, $matchedVolume)
    {
        $carryForward = $this->calculateCarryForward($leftVolume, $rightVolume, $matchedVolume);

        BinarySummary::updateOrCreate(
            ['user_id' => $user->id],
            [
                'left_total_volume' => $leftVolume,
                'right_total_volume' => $rightVolume,
                'matched_volume' => $matchedVolume,
                'carry_forward' => $carryForward['total'],
                'left_carry' => $carryForward['left'],
                'right_carry' => $carryForward['right'],
                'updated_at' => now()
            ]
        );
    }

    /**
     * Calculate carry forward volumes
     */
    private function calculateCarryForward($leftVolume, $rightVolume, $matchedVolume)
    {
        $leftCarry = $leftVolume - $matchedVolume;
        $rightCarry = $rightVolume - $matchedVolume;
        $totalCarry = $leftCarry + $rightCarry;

        return [
            'left' => max(0, $leftCarry),
            'right' => max(0, $rightCarry),
            'total' => max(0, $totalCarry)
        ];
    }

    /**
     * Process matching bonuses for all qualified users
     */
    public function processAllMatchingBonuses()
    {
        $users = User::where('status', 'active')
            ->whereHas('sponsor') // Users with sponsors (in the binary tree)
            ->get();

        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                $matching = $this->calculateMatchingBonus($user->id);
                if ($matching) {
                    $processed++;
                    Log::info("Matching bonus processed for user {$user->id}: {$matching->amount}");
                }
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error processing matching bonus for user {$user->id}: " . $e->getMessage());
            }
        }

        return [
            'processed' => $processed,
            'errors' => $errors,
            'total_users' => $users->count()
        ];
    }
}
