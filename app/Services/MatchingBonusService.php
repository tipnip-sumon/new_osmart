<?php

namespace App\Services;

use App\Models\User;
use App\Models\CommissionSetting;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use App\Models\Commission;
use App\Models\MlmBinaryTree;
use App\Services\VolumeTrackingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchingBonusService
{
    protected $binaryMatchingService;

    public function __construct(BinaryMatchingService $binaryMatchingService)
    {
        $this->binaryMatchingService = $binaryMatchingService;
    }

    /**
     * Process matching bonuses based on commission settings
     */
    public function processMatchingBonuses($date = null, $userId = null)
    {
        $date = $date ?: Carbon::now()->toDateString();
        
        try {
            DB::beginTransaction();
            
            // Get active matching commission settings
            $matchingSettings = CommissionSetting::where('type', 'matching')
                ->where('is_active', true)
                ->orderBy('priority', 'desc')
                ->get();

            if ($matchingSettings->isEmpty()) {
                Log::info('No active matching commission settings found');
                return;
            }

            // Get users to process
            $usersQuery = User::where('status', 'active')
                ->whereNotNull('sponsor_id');

            if ($userId) {
                $usersQuery->where('id', $userId);
            }

            $users = $usersQuery->get();
            $processedCount = 0;
            $totalBonus = 0;

            foreach ($users as $user) {
                foreach ($matchingSettings as $setting) {
                    $result = $this->processUserMatchingBonus($user, $setting, $date);
                    if ($result) {
                        $processedCount++;
                        $totalBonus += $result['bonus_amount'] ?? 0;
                    }
                }
            }

            DB::commit();
            
            Log::info("Matching bonuses processed", [
                'date' => $date,
                'users_processed' => $processedCount,
                'total_bonus' => $totalBonus
            ]);

            return [
                'success' => true,
                'processed_count' => $processedCount,
                'total_bonus' => $totalBonus
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing matching bonuses: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process matching bonus for a specific user with specific commission setting
     */
    public function processUserMatchingBonus(User $user, CommissionSetting $setting, $date)
    {
        try {
            // Check if user is qualified for this matching setting
            if (!$this->isUserQualified($user, $setting)) {
                return null;
            }

            // Get or calculate binary matching data
            $binaryMatching = $this->binaryMatchingService->calculateBinaryMatching(
                $user, 
                $date, 
                $setting->matching_frequency ?? 'daily'
            );

            if (!$binaryMatching || $binaryMatching->matching_bonus <= 0) {
                return null;
            }

            // Calculate matching bonus based on setting configuration
            $matchingBonus = $this->calculateMatchingBonus($user, $setting, $binaryMatching);

            if ($matchingBonus <= 0) {
                return null;
            }

            // Create commission record
            $commission = $this->createCommissionRecord($user, $setting, $binaryMatching, $matchingBonus, $date);

            // Update user's matching history
            $this->updateMatchingHistory($user, $commission);

            // Mark volumes as processed to prevent double-counting
            $this->markVolumesAsProcessed($user, $binaryMatching);

            return [
                'user_id' => $user->id,
                'commission_id' => $commission->id,
                'bonus_amount' => $matchingBonus,
                'setting_id' => $setting->id
            ];

        } catch (\Exception $e) {
            Log::error("Error processing matching bonus for user {$user->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user is qualified for matching bonus
     */
    protected function isUserQualified(User $user, CommissionSetting $setting)
    {
        // Check personal volume requirement
        if ($setting->personal_volume_required && $setting->min_personal_volume > 0) {
            $personalVolume = $this->getUserPersonalVolume($user);
            if ($personalVolume < $setting->min_personal_volume) {
                return false;
            }
        }

        // Check both legs requirement
        if ($setting->both_legs_required) {
            $binaryTree = $user->binaryTree ?? MlmBinaryTree::where('user_id', $user->id)->first();
            
            if (!$binaryTree) {
                return false;
            }

            $leftVolume = $binaryTree->left_leg_volume ?? 0;
            $rightVolume = $binaryTree->right_leg_volume ?? 0;

            if ($setting->min_left_volume > 0 && $leftVolume < $setting->min_left_volume) {
                return false;
            }

            if ($setting->min_right_volume > 0 && $rightVolume < $setting->min_right_volume) {
                return false;
            }
        }

        // Check minimum qualification
        if ($setting->min_qualification > 0) {
            $qualification = $this->getUserQualification($user);
            if ($qualification < $setting->min_qualification) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate matching bonus based on commission setting
     */
    protected function calculateMatchingBonus(User $user, CommissionSetting $setting, BinaryMatching $binaryMatching)
    {
        $baseBonus = $binaryMatching->matching_bonus;
        
        // Apply setting-specific calculations
        if ($setting->enable_multi_level && is_array($setting->levels)) {
            // Multi-level calculation
            $bonus = $this->calculateMultiLevelMatchingBonus($user, $setting, $binaryMatching);
        } else {
            // Single level calculation
            if ($setting->calculation_type === 'percentage') {
                $bonus = $baseBonus * ($setting->value / 100);
            } else {
                $bonus = $setting->value; // Fixed amount
            }
        }

        // Apply maximum payout limit
        if ($setting->max_payout > 0) {
            $totalEarned = $this->getUserTotalEarnings($user, $setting);
            $remainingLimit = max(0, $setting->max_payout - $totalEarned);
            $bonus = min($bonus, $remainingLimit);
        }

        // Apply daily/weekly caps if enabled
        $bonus = $this->applyCapping($user, $setting, $bonus);

        return max(0, $bonus);
    }

    /**
     * Calculate multi-level matching bonus
     */
    protected function calculateMultiLevelMatchingBonus(User $user, CommissionSetting $setting, BinaryMatching $binaryMatching)
    {
        $levels = $setting->levels;
        $userLevel = $this->getUserLevel($user);
        
        // Find applicable level configuration
        $levelConfig = null;
        foreach ($levels as $level) {
            if (isset($level['level']) && $level['level'] == $userLevel) {
                $levelConfig = $level;
                break;
            }
        }

        if (!$levelConfig) {
            // Use first level as default
            $levelConfig = $levels[0] ?? null;
        }

        if (!$levelConfig || !isset($levelConfig['value'])) {
            return 0;
        }

        // Check level-specific qualification
        if (isset($levelConfig['min_qualification']) && $levelConfig['min_qualification'] > 0) {
            $qualification = $this->getUserQualification($user);
            if ($qualification < $levelConfig['min_qualification']) {
                return 0;
            }
        }

        // Calculate bonus
        $baseBonus = $binaryMatching->matching_bonus;
        $bonus = $baseBonus * ($levelConfig['value'] / 100);

        // Apply level-specific max payout
        if (isset($levelConfig['max_payout']) && $levelConfig['max_payout'] > 0) {
            $bonus = min($bonus, $levelConfig['max_payout']);
        }

        return $bonus;
    }

    /**
     * Apply capping based on commission setting
     */
    protected function applyCapping(User $user, CommissionSetting $setting, $bonus)
    {
        $today = Carbon::now()->toDateString();
        $thisWeek = Carbon::now()->startOfWeek()->toDateString();

        // Apply daily cap
        if ($setting->daily_cap_enabled && $setting->daily_cap_amount > 0) {
            $dailyEarned = Commission::where('user_id', $user->id)
                ->where('commission_setting_id', $setting->id)
                ->whereDate('created_at', $today)
                ->sum('commission_amount');

            $dailyRemaining = max(0, $setting->daily_cap_amount - $dailyEarned);
            $bonus = min($bonus, $dailyRemaining);
        }

        // Apply weekly cap
        if ($setting->weekly_cap_enabled && $setting->weekly_cap_amount > 0) {
            $weeklyEarned = Commission::where('user_id', $user->id)
                ->where('commission_setting_id', $setting->id)
                ->where('created_at', '>=', $thisWeek)
                ->sum('commission_amount');

            $weeklyRemaining = max(0, $setting->weekly_cap_amount - $weeklyEarned);
            $bonus = min($bonus, $weeklyRemaining);
        }

        return $bonus;
    }

    /**
     * Create commission record
     */
    protected function createCommissionRecord(User $user, CommissionSetting $setting, BinaryMatching $binaryMatching, $matchingBonus, $date)
    {
        return Commission::create([
            'user_id' => $user->id,
            'commission_setting_id' => $setting->id,
            'commission_type' => 'matching_bonus',
            'reference_type' => 'binary_matching',
            'reference_id' => $binaryMatching->id,
            'commission_amount' => $matchingBonus,
            'calculation_amount' => $binaryMatching->matching_bonus,
            'percentage' => $setting->value ?? 0,
            'level' => $this->getUserLevel($user),
            'status' => 'pending',
            'processed_at' => $date,
            'description' => "Matching bonus for {$setting->display_name}",
            'calculation_details' => [
                'setting_id' => $setting->id,
                'setting_name' => $setting->name,
                'binary_matching_id' => $binaryMatching->id,
                'base_bonus' => $binaryMatching->matching_bonus,
                'left_volume' => $binaryMatching->left_current_volume,
                'right_volume' => $binaryMatching->right_current_volume,
                'matching_volume' => $binaryMatching->matching_volume,
                'user_level' => $this->getUserLevel($user),
                'calculated_at' => now()
            ]
        ]);
    }

    /**
     * Update user's matching history
     */
    protected function updateMatchingHistory(User $user, Commission $commission)
    {
        // Update binary summary if exists
        $binarySummary = $user->binarySummary;
        if ($binarySummary) {
            $binarySummary->increment('total_matching_bonus', $commission->commission_amount);
            $binarySummary->update(['last_calculated_at' => now()]);
        }

        // You can add more history tracking here
    }

    /**
     * Get user's personal volume
     */
    protected function getUserPersonalVolume(User $user)
    {
        // Calculate from orders, investments, or other sources
        $volume = 0;

        // Add investment volume if Investment model exists
        if (class_exists('App\Models\Investment')) {
            $volume += $user->investments()
                ->where('status', 'completed')
                ->sum('amount');
        }

        // Add order volume if Order model exists - only count paid orders
        if (class_exists('App\Models\Order')) {
            $volume += $user->orders()
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        return $volume;
    }

    /**
     * Get user's qualification amount
     */
    protected function getUserQualification(User $user)
    {
        // This could be based on personal volume, rank, or other criteria
        return $this->getUserPersonalVolume($user);
    }

    /**
     * Get user's current level
     */
    protected function getUserLevel(User $user)
    {
        // You can implement this based on your business logic
        // This could be based on rank, total volume, downline count, etc.
        
        $personalVolume = $this->getUserPersonalVolume($user);
        
        if ($personalVolume >= 50000) return 5;
        if ($personalVolume >= 25000) return 4;
        if ($personalVolume >= 10000) return 3;
        if ($personalVolume >= 5000) return 2;
        return 1;
    }

    /**
     * Get user's total earnings for a specific commission setting
     */
    protected function getUserTotalEarnings(User $user, CommissionSetting $setting)
    {
        return Commission::where('user_id', $user->id)
            ->where('commission_setting_id', $setting->id)
            ->sum('commission_amount');
    }

    /**
     * Calculate matching bonus for downline teams (tier bonus)
     */
    public function calculateTierMatchingBonus(User $user, $date = null)
    {
        $date = $date ?: Carbon::now()->toDateString();
        
        try {
            // Get active matching commission settings for tier bonus
            $tierSettings = CommissionSetting::where('type', 'matching')
                ->where('is_active', true)
                ->where('enable_multi_level', true)
                ->get();

            $totalTierBonus = 0;

            foreach ($tierSettings as $setting) {
                // Get direct downlines who earned binary bonuses
                $downlines = $user->directDownlines()
                    ->whereHas('commissions', function($query) use ($date) {
                        $query->where('commission_type', 'matching_bonus')
                            ->whereDate('created_at', $date);
                    })
                    ->get();

                foreach ($downlines as $downline) {
                    // Calculate tier percentage based on user level and setting
                    $tierPercentage = $this->getTierPercentage($user, $setting);
                    
                    if ($tierPercentage > 0) {
                        // Get downline's binary bonus for the date
                        $downlineBinaryBonus = Commission::where('user_id', $downline->id)
                            ->where('commission_type', 'matching_bonus')
                            ->whereDate('created_at', $date)
                            ->sum('commission_amount');

                        if ($downlineBinaryBonus > 0) {
                            $tierBonus = $downlineBinaryBonus * ($tierPercentage / 100);
                            
                            // Create tier commission record
                            Commission::create([
                                'user_id' => $user->id,
                                'commission_setting_id' => $setting->id,
                                'commission_type' => 'tier_bonus',
                                'reference_type' => 'downline_binary',
                                'reference_id' => $downline->id,
                                'commission_amount' => $tierBonus,
                                'calculation_amount' => $downlineBinaryBonus,
                                'percentage' => $tierPercentage,
                                'level' => $this->getUserLevel($user),
                                'status' => 'pending',
                                'processed_at' => $date,
                                'description' => "Tier matching bonus from {$downline->username}",
                                'calculation_details' => [
                                    'downline_id' => $downline->id,
                                    'downline_binary_bonus' => $downlineBinaryBonus,
                                    'tier_percentage' => $tierPercentage,
                                    'user_level' => $this->getUserLevel($user)
                                ]
                            ]);

                            $totalTierBonus += $tierBonus;
                        }
                    }
                }
            }

            return $totalTierBonus;

        } catch (\Exception $e) {
            Log::error("Error calculating tier matching bonus for user {$user->id}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get tier percentage based on user level
     */
    protected function getTierPercentage(User $user, CommissionSetting $setting)
    {
        $userLevel = $this->getUserLevel($user);
        
        // Default tier percentages by level
        $tierPercentages = [
            1 => 5,   // 5% for level 1
            2 => 7,   // 7% for level 2
            3 => 10,  // 10% for level 3
            4 => 12,  // 12% for level 4
            5 => 15   // 15% for level 5
        ];

        return $tierPercentages[$userLevel] ?? 0;
    }

    /**
     * Process all users' matching bonuses for a specific date
     */
    public function processDailyMatchingBonuses($date = null)
    {
        $date = $date ?: Carbon::now()->toDateString();
        
        return $this->processMatchingBonuses($date);
    }

    /**
     * Get matching statistics for admin dashboard
     */
    public function getMatchingStatistics($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?: Carbon::now()->startOfMonth()->toDateString();
        $endDate = $endDate ?: Carbon::now()->toDateString();

        return [
            'total_matching_bonuses' => Commission::where('commission_type', 'matching_bonus')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('commission_amount'),
            
            'total_tier_bonuses' => Commission::where('commission_type', 'tier_bonus')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('commission_amount'),
            
            'active_users_count' => Commission::where('commission_type', 'matching_bonus')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count(),
            
            'average_matching_bonus' => Commission::where('commission_type', 'matching_bonus')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->avg('commission_amount'),
            
            'total_binary_matchings' => BinaryMatching::whereBetween('match_date', [$startDate, $endDate])
                ->count(),
            
            'total_matched_volume' => BinaryMatching::whereBetween('match_date', [$startDate, $endDate])
                ->sum('matching_volume')
        ];
    }

    /**
     * Mark volumes as processed after payout to prevent double-counting
     */
    protected function markVolumesAsProcessed(User $user, BinaryMatching $binaryMatching)
    {
        $volumeService = app(VolumeTrackingService::class);
        
        // Mark the matching volume as processed
        $matchingVolume = $binaryMatching->matching_volume ?? 0;
        
        if ($matchingVolume > 0) {
            // Mark monthly volume as processed (most commonly used for matching)
            $volumeService->markVolumesAsProcessed($user, null, $matchingVolume, null);
            
            Log::info("Marked {$matchingVolume} as processed for user {$user->id} after matching bonus");
        }
    }
}
