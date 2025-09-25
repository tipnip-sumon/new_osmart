<?php

namespace App\Services;

use App\Models\User;
use App\Models\BinaryRankAchievement;
use App\Models\BinaryRankStructure;
use App\Models\MonthlyRankQualification;
use App\Models\BinarySummary;
use App\Models\Transaction;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BinaryRankService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService = null)
    {
        $this->notificationService = $notificationService ?? app(NotificationService::class);
    }
    /**
     * Process user rank achievements based on binary points
     */
    public function processUserRankAchievements($userId)
    {
        try {
            DB::beginTransaction();

            $user = User::find($userId);
            if (!$user) {
                throw new \Exception("User not found: {$userId}");
            }

            // Get user's current binary points
            $binarySummary = BinarySummary::where('user_id', $userId)->latest()->first();
            
            if (!$binarySummary) {
                Log::info("No binary summary found for user: {$userId}");
                return [];
            }

            $leftPoints = $binarySummary->lifetime_left_volume ?? 0;
            $rightPoints = $binarySummary->lifetime_right_volume ?? 0;

            // Initialize user ranks if not exists
            $this->initializeUserRanks($userId);

            // Check for new achievements
            $achievements = $this->checkRankAchievements($userId, $leftPoints, $rightPoints);

            // Process monthly qualifications for achieved ranks
            $this->processMonthlyQualifications($userId, $leftPoints, $rightPoints);

            // Update qualification progress for all active qualification periods
            $this->updateAllQualificationProgress($userId);

            // Process salary distribution for eligible ranks
            $salariesDistributed = $this->processSalaryDistribution($userId);
            
            if (!empty($salariesDistributed)) {
                Log::info("Salaries distributed for user {$userId}: " . json_encode($salariesDistributed));
            }

            DB::commit();
            return $achievements;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error processing rank achievements for user {$userId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get detailed rank conditions report for a user
     */
    public function getRankConditionsReport($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        // Get user's current points
        $binarySummary = BinarySummary::where('user_id', $userId)->latest()->first();
        $leftPoints = $binarySummary->lifetime_left_volume ?? 0;
        $rightPoints = $binarySummary->lifetime_right_volume ?? 0;

        // Get monthly NEW points (last 30 days)
        $monthlyLeftNew = $this->calculateMonthlyNewPoints($userId, 'left');
        $monthlyRightNew = $this->calculateMonthlyNewPoints($userId, 'right');

        // Get current rank
        $currentRank = $this->getCurrentRank($userId);
        
        // Get all rank structures with user data
        $rankStructures = BinaryRankStructure::orderBy('left_points')->get();
        $userRanks = BinaryRankAchievement::where('user_id', $userId)->get()->keyBy('rank_name');

        $rankConditions = [];
        
        foreach ($rankStructures as $rank) {
            $userRank = $userRanks->get($rank->rank_name);
            $isAchieved = $userRank && $userRank->is_achieved;
            $isCurrent = $currentRank && $currentRank->rank_name === $rank->rank_name;
            
            // Calculate achievement progress
            $leftProgress = min(100, ($leftPoints / max(1, $rank->left_points)) * 100);
            $rightProgress = min(100, ($rightPoints / max(1, $rank->right_points)) * 100);
            $achievementProgress = min($leftProgress, $rightProgress);
            
            // Calculate monthly qualification progress
            $monthlyLeftProgress = min(100, ($monthlyLeftNew / max(1, $rank->monthly_left_points)) * 100);
            $monthlyRightProgress = min(100, ($monthlyRightNew / max(1, $rank->monthly_right_points)) * 100);
            $monthlyProgress = min($monthlyLeftProgress, $monthlyRightProgress);
            
            // Check if can qualify monthly
            $canQualifyMonthly = $isAchieved && 
                                $monthlyLeftNew >= $rank->monthly_left_points && 
                                $monthlyRightNew >= $rank->monthly_right_points;

            $rank->user_achieved = $isAchieved;
            $rank->is_current = $isCurrent;
            $rank->achievement_progress = $achievementProgress;
            $rank->monthly_progress = $monthlyProgress;
            $rank->can_qualify_monthly = $canQualifyMonthly;
            
            $rankConditions[] = $rank;
        }

        return [
            'user_data' => [
                'current_rank' => $currentRank,
                'left_points' => $leftPoints,
                'right_points' => $rightPoints,
                'monthly_left_new' => $monthlyLeftNew,
                'monthly_right_new' => $monthlyRightNew,
                'monthly_salary' => $currentRank ? $currentRank->salary_amount ?? 0 : 0,
                'matching_bonus' => $this->calculateCurrentMatchingBonus($userId),
                'monthly_qualified' => $this->isMonthlyQualified($userId),
            ],
            'rank_conditions' => $rankConditions,
            'earnings_projection' => $this->calculateEarningsProjection($userId, $leftPoints, $rightPoints)
        ];
    }

    /**
     * Calculate monthly NEW points for a user
     */
    public function calculateMonthlyNewPoints($userId, $side = 'both')
    {
        $startDate = Carbon::now()->subDays(30);
        
        // This should be implemented based on your sales/transaction model
        // For now, returning a placeholder calculation
        $leftNew = 0;
        $rightNew = 0;
        
        // You would implement actual logic here to calculate NEW points from recent sales
        // Example logic would involve checking sales/transactions from the last 30 days
        // and calculating points generated from left and right teams
        
        if ($side === 'left') {
            return $leftNew;
        } elseif ($side === 'right') {
            return $rightNew;
        }
        
        return ['left' => $leftNew, 'right' => $rightNew];
    }

    /**
     * Get current rank for a user
     */
    public function getCurrentRank($userId)
    {
        return BinaryRankAchievement::where('user_id', $userId)
            ->where('is_current_rank', true)
            ->where('is_achieved', true)
            ->with('rankStructure')
            ->first();
    }

    /**
     * Calculate current matching bonus based on monthly qualification
     */
    public function calculateCurrentMatchingBonus($userId)
    {
        $monthlyLeftNew = $this->calculateMonthlyNewPoints($userId, 'left');
        $monthlyRightNew = $this->calculateMonthlyNewPoints($userId, 'right');
        
        // Find the highest level user can qualify for this month
        $rankStructures = BinaryRankStructure::orderBy('monthly_left_points', 'desc')->get();
        
        foreach ($rankStructures as $rank) {
            if ($monthlyLeftNew >= $rank->monthly_left_points && 
                $monthlyRightNew >= $rank->monthly_right_points) {
                return $rank->matching_tk;
            }
        }
        
        return 0;
    }

    /**
     * Check if user is monthly qualified
     */
    public function isMonthlyQualified($userId)
    {
        $currentRank = $this->getCurrentRank($userId);
        if (!$currentRank) {
            return false;
        }

        $monthlyLeftNew = $this->calculateMonthlyNewPoints($userId, 'left');
        $monthlyRightNew = $this->calculateMonthlyNewPoints($userId, 'right');
        
        $rankStructure = $currentRank->rankStructure ?? BinaryRankStructure::where('rank_name', $currentRank->rank_name)->first();
        
        if (!$rankStructure) {
            return false;
        }

        return $monthlyLeftNew >= $rankStructure->monthly_left_points && 
               $monthlyRightNew >= $rankStructure->monthly_right_points;
    }

    /**
     * Calculate earnings projection for all levels
     */
    public function calculateEarningsProjection($userId, $leftPoints, $rightPoints)
    {
        $rankStructures = BinaryRankStructure::orderBy('left_points')->get();
        $projection = [];
        
        foreach ($rankStructures as $index => $rank) {
            $level = $index + 1;
            
            // Check if user can achieve this rank
            $achievable = $leftPoints >= $rank->left_points && 
                         $rightPoints >= $rank->right_points;
            
            // Calculate progress
            $leftProgress = min(100, ($leftPoints / max(1, $rank->left_points)) * 100);
            $rightProgress = min(100, ($rightPoints / max(1, $rank->right_points)) * 100);
            $progress = min($leftProgress, $rightProgress);
            
            $projection[$level] = [
                'rank_name' => $rank->rank_name,
                'salary' => $rank->salary,
                'matching_bonus' => $rank->matching_tk,
                'total' => $rank->salary + $rank->matching_tk,
                'monthly_left' => $rank->monthly_left_points,
                'monthly_right' => $rank->monthly_right_points,
                'achievable' => $achievable,
                'progress' => $progress
            ];
        }
        
        return $projection;
    }

    /**
     * Get rank status with enhanced conditions data
     */
    public function getRankStatusWithConditions($userId)
    {
        $basicStatus = $this->getUserRankStatus($userId);
        $conditionsReport = $this->getRankConditionsReport($userId);
        
        return array_merge($basicStatus, [
            'detailed_conditions' => $conditionsReport,
            'monthly_matching_available' => $this->calculateCurrentMatchingBonus($userId),
            'qualification_history' => $this->getQualificationHistory($userId, 6) // Last 6 months
        ]);
    }

    /**
     * Get qualification history for a user
     */
    public function getQualificationHistory($userId, $months = 6)
    {
        return MonthlyRankQualification::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subMonths($months))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($qualification) {
                return [
                    'month' => $qualification->created_at->format('M Y'),
                    'rank_name' => $qualification->rank_name,
                    'left_points' => $qualification->left_points_achieved,
                    'right_points' => $qualification->right_points_achieved,
                    'qualified' => $qualification->is_qualified,
                    'salary_paid' => $qualification->salary_amount_paid,
                    'matching_bonus' => $qualification->matching_bonus_paid
                ];
            });
    }

    /**
     * Initialize rank structure for a user
     */
    public function initializeUserRanks($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // Check if ranks already initialized
        $existingRanks = BinaryRankAchievement::where('user_id', $userId)->count();
        if ($existingRanks > 0) {
            return true;
        }

        // Initialize from rank structure
        BinaryRankAchievement::initializeUserRanks($userId);
        
        Log::info("Initialized rank structure for user: {$userId}");
        return true;
    }

    /**
     * Check and process rank achievements with qualification period start
     */
    private function checkRankAchievements($userId, $leftPoints, $rightPoints)
    {
        $achievements = [];
        $userRanks = BinaryRankAchievement::where('user_id', $userId)
                                         ->where('is_achieved', false)
                                         ->orderBy('rank_level')
                                         ->get();

        foreach ($userRanks as $rank) {
            if ($rank->checkAchievement($leftPoints, $rightPoints)) {
                // Start 30-day qualification period when rank is achieved
                $rank->startSalaryQualificationPeriod();
                
                $achievements[] = [
                    'rank' => $rank,
                    'achieved_at' => $rank->achieved_at,
                    'bonus' => $this->calculateAchievementBonus($rank),
                    'rewards' => $this->getAchievementRewards($rank),
                    'qualification_period_started' => true,
                    'qualification_days_remaining' => 30
                ];

                // Send real-time notification for rank achievement
                try {
                    $this->notificationService->sendRankAchievement(
                        $userId, 
                        $rank->rank_name,
                        [
                            'reference_type' => 'rank_achievement',
                            'reference_id' => $rank->id,
                            'data' => [
                                'rank_level' => $rank->rank_level,
                                'left_points' => $leftPoints,
                                'right_points' => $rightPoints,
                                'qualification_start' => now()->toDateString(),
                                'qualification_end' => now()->addDays(30)->toDateString()
                            ]
                        ]
                    );
                    
                    Log::info("✅ Rank achievement notification sent for user {$userId}, rank: {$rank->rank_name}");
                } catch (\Exception $e) {
                    Log::error("❌ Failed to send rank achievement notification: " . $e->getMessage());
                }

                Log::info("User {$userId} achieved rank: {$rank->rank_name} - Starting 30-day qualification period");
            }
        }

        return $achievements;
    }

    /**
     * Process monthly qualifications for all achieved ranks
     */
    private function processMonthlyQualifications($userId, $leftPoints, $rightPoints, $month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m-01');
        
        $achievedRanks = BinaryRankAchievement::where('user_id', $userId)
                                            ->where('is_achieved', true)
                                            ->get();

        foreach ($achievedRanks as $achievement) {
            // Check monthly qualification
            $qualified = $achievement->checkMonthlyQualification($leftPoints, $rightPoints, $month);
            
            if ($qualified) {
                $this->createMonthlyQualificationRecord($achievement, $leftPoints, $rightPoints, $month);
            }
        }
    }

    /**
     * Create monthly qualification record
     */
    private function createMonthlyQualificationRecord($achievement, $leftPoints, $rightPoints, $month)
    {
        $rankStructure = $achievement->rankStructure;
        if (!$rankStructure) {
            return false;
        }

        $qualification = MonthlyRankQualification::firstOrCreate([
            'user_id' => $achievement->user_id,
            'rank_id' => $rankStructure->id,
            'qualification_month' => $month
        ]);

        $qualification->processQualification($leftPoints, $rightPoints);
        
        return $qualification;
    }

    /**
     * Calculate achievement bonus
     */
    private function calculateAchievementBonus($rank)
    {
        // One-time achievement bonus
        return [
            'matching_bonus' => $rank->matching_tk,
            'point_bonus' => $rank->point_10_percent,
            'total_value' => $rank->matching_tk + $rank->point_10_percent
        ];
    }

    /**
     * Get achievement rewards
     */
    private function getAchievementRewards($rank)
    {
        return [
            'tour' => $rank->tour_reward,
            'gift' => $rank->gift_reward,
            'monthly_salary' => $rank->salary_amount,
            'duration_months' => $rank->duration_months
        ];
    }

    /**
     * Calculate monthly matching bonus for qualified ranks
     */
    public function calculateMonthlyMatchingBonus($userId, $leftPoints, $rightPoints)
    {
        $totalBonus = 0;
        $currentRank = BinaryRankAchievement::where('user_id', $userId)
                                          ->where('is_current_rank', true)
                                          ->first();

        if ($currentRank && $currentRank->monthly_qualified) {
            $totalBonus = $currentRank->calculateMatchingBonus($leftPoints, $rightPoints);
        }

        return $totalBonus;
    }

    /**
     * Get user's rank progress and status
     */
    public function getUserRankStatus($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        // Calculate left and right team sales from all levels (no level condition)
        $leftPoints = $this->calculateLeftTeamSales($userId);
        $rightPoints = $this->calculateRightTeamSales($userId);

        // Also get binary summary for compatibility
        $binarySummary = BinarySummary::where('user_id', $userId)->latest()->first();
        if ($binarySummary) {
            // Use the higher value between calculated and stored
            $leftPoints = max($leftPoints, $binarySummary->lifetime_left_volume ?? 0);
            $rightPoints = max($rightPoints, $binarySummary->lifetime_right_volume ?? 0);
        }

        // Get current rank
        $currentRank = BinaryRankAchievement::where('user_id', $userId)
                                          ->where('is_current_rank', true)
                                          ->first();

        // Get next rank
        $nextRank = null;
        if ($currentRank) {
            // If user has a current rank, find the next unachieved rank
            $nextRank = BinaryRankAchievement::where('user_id', $userId)
                                           ->where('rank_level', '>', $currentRank->rank_level)
                                           ->where('is_achieved', false)
                                           ->orderBy('rank_level')
                                           ->first();
        } else {
            // If user has no current rank, get the first rank (Elite - Level 1)
            $nextRank = BinaryRankAchievement::where('user_id', $userId)
                                           ->where('is_achieved', false)
                                           ->orderBy('rank_level')
                                           ->first();
                                           
            // If no achievements found, create from rank structure
            if (!$nextRank) {
                $firstRankStructure = BinaryRankStructure::where('is_active', true)
                                                        ->orderBy('sl_no')
                                                        ->first();
                if ($firstRankStructure) {
                    // Initialize user ranks first
                    $this->initializeUserRanks($userId);
                    // Try to get the next rank again
                    $nextRank = BinaryRankAchievement::where('user_id', $userId)
                                                   ->where('is_achieved', false)
                                                   ->orderBy('rank_level')
                                                   ->first();
                }
            }
        }

        // Calculate progress to next rank
        $progressToNext = 0;
        $remainingLeftPoints = 0;
        $remainingRightPoints = 0;
        
        if ($nextRank) {
            $leftProgress = ($leftPoints / $nextRank->required_left_points) * 100;
            $rightProgress = ($rightPoints / $nextRank->required_right_points) * 100;
            $progressToNext = min(100, min($leftProgress, $rightProgress));
            
            // Calculate remaining points needed
            $remainingLeftPoints = max(0, $nextRank->required_left_points - $leftPoints);
            $remainingRightPoints = max(0, $nextRank->required_right_points - $rightPoints);
        }

        return [
            'current_rank' => $currentRank,
            'next_rank' => $nextRank,
            'left_points' => $leftPoints,
            'right_points' => $rightPoints,
            'remaining_left_points' => $remainingLeftPoints,
            'remaining_right_points' => $remainingRightPoints,
            'progress_to_next' => $progressToNext,
            'monthly_qualified' => $currentRank ? $currentRank->monthly_qualified : false,
            'consecutive_months' => $currentRank ? $currentRank->consecutive_qualified_months : 0
        ];
    }

    /**
     * Get all rank structures for display
     */
    public function getAllRankStructures()
    {
        return BinaryRankStructure::active()->orderedByLevel()->get();
    }

    /**
     * Process monthly salary payments for all qualified users
     */
    public function processMonthlyRankSalaries($month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m-01');
        
        try {
            DB::beginTransaction();

            $totalPaid = 0;
            $usersPaid = 0;

            // Get all qualified users for the month
            $qualifiedUsers = MonthlyRankQualification::where('qualification_month', $month)
                                                    ->where('qualified', true)
                                                    ->where('salary_paid', false)
                                                    ->with(['user', 'rank'])
                                                    ->get();

            foreach ($qualifiedUsers as $qualification) {
                if ($qualification->paySalary()) {
                    $totalPaid += $qualification->salary_amount;
                    $usersPaid++;
                    
                    Log::info("Paid rank salary: ৳{$qualification->salary_amount} to User {$qualification->user_id} for rank {$qualification->rank->rank_name}");
                }
            }

            DB::commit();

            return [
                'total_paid' => $totalPaid,
                'users_paid' => $usersPaid,
                'month' => $month
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error processing monthly rank salaries: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get rank achievement statistics
     */
    public function getRankStatistics()
    {
        $stats = [];

        // Get total achievements by rank
        $rankStructures = BinaryRankStructure::active()->orderedByLevel()->get();
        
        foreach ($rankStructures as $rank) {
            $achievedCount = BinaryRankAchievement::where('rank_name', $rank->rank_name)
                                                 ->where('is_achieved', true)
                                                 ->count();
            
            $monthlyQualified = MonthlyRankQualification::whereHas('rank', function($q) use ($rank) {
                                    $q->where('rank_name', $rank->rank_name);
                                })
                                ->where('qualification_month', Carbon::now()->format('Y-m-01'))
                                ->where('qualified', true)
                                ->count();

            $stats[] = [
                'rank' => $rank,
                'achieved_count' => $achievedCount,
                'monthly_qualified' => $monthlyQualified
            ];
        }

        return $stats;
    }

    /**
     * Bulk process all users for rank achievements and qualifications
     */
    public function bulkProcessAllUsers()
    {
        $users = User::whereHas('binarySummary')->get();
        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                $this->processUserRankAchievements($user->id);
                $processed++;
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error processing user {$user->id}: " . $e->getMessage());
            }
        }

        return [
            'processed' => $processed,
            'errors' => $errors,
            'total' => $users->count()
        ];
    }

    /**
     * Calculate total left team sales from all levels
     */
    private function calculateLeftTeamSales($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }

        // Get all left team members recursively (no level limit)
        $leftTeamSales = 0;
        
        // Method 1: Use direct left downline and recursively calculate
        $leftTeamUsers = $this->getAllLeftTeamUsers($userId);
        
        foreach ($leftTeamUsers as $teamUser) {
            // Calculate total purchase amount for each team member
            $userSales = $this->calculateUserTotalSales($teamUser->id);
            $leftTeamSales += $userSales;
        }

        return $leftTeamSales;
    }

    /**
     * Calculate total right team sales from all levels
     */
    private function calculateRightTeamSales($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return 0;
        }

        // Get all right team members recursively (no level limit)
        $rightTeamSales = 0;
        
        // Method 1: Use direct right downline and recursively calculate
        $rightTeamUsers = $this->getAllRightTeamUsers($userId);
        
        foreach ($rightTeamUsers as $teamUser) {
            // Calculate total purchase amount for each team member
            $userSales = $this->calculateUserTotalSales($teamUser->id);
            $rightTeamSales += $userSales;
        }

        return $rightTeamSales;
    }

    /**
     * Get all left team users recursively (all levels)
     */
    private function getAllLeftTeamUsers($userId, &$processed = [])
    {
        if (in_array($userId, $processed)) {
            return collect();
        }
        
        $processed[] = $userId;
        $allUsers = collect();

        // Get direct left children
        $leftUsers = User::where('upline_id', $userId)
                        ->where('position', 'left')
                        ->get();

        foreach ($leftUsers as $user) {
            $allUsers->push($user);
            // Recursively get all downlines of left user (both left and right)
            $subUsers = $this->getAllDownlineUsers($user->id, $processed);
            $allUsers = $allUsers->merge($subUsers);
        }

        return $allUsers;
    }

    /**
     * Get all right team users recursively (all levels)
     */
    private function getAllRightTeamUsers($userId, &$processed = [])
    {
        if (in_array($userId, $processed)) {
            return collect();
        }
        
        $processed[] = $userId;
        $allUsers = collect();

        // Get direct right children
        $rightUsers = User::where('upline_id', $userId)
                         ->where('position', 'right')
                         ->get();

        foreach ($rightUsers as $user) {
            $allUsers->push($user);
            // Recursively get all downlines of right user (both left and right)
            $subUsers = $this->getAllDownlineUsers($user->id, $processed);
            $allUsers = $allUsers->merge($subUsers);
        }

        return $allUsers;
    }

    /**
     * Get all downline users recursively from a given user
     */
    private function getAllDownlineUsers($userId, &$processed = [])
    {
        if (in_array($userId, $processed)) {
            return collect();
        }
        
        $processed[] = $userId;
        $allUsers = collect();

        // Get all direct children
        $children = User::where('upline_id', $userId)->get();

        foreach ($children as $child) {
            $allUsers->push($child);
            // Recursively get children of this child
            $subUsers = $this->getAllDownlineUsers($child->id, $processed);
            $allUsers = $allUsers->merge($subUsers);
        }

        return $allUsers;
    }

    /**
     * Calculate total sales/purchases for a specific user
     */
    private function calculateUserTotalSales($userId)
    {
        // Calculate from orders/purchases
        $totalSales = 0;

        // Method 1: Check if there's an Order model and calculate sales
        if (class_exists('\App\Models\Order')) {
            $totalSales += \App\Models\Order::where('customer_id', $userId)
                                          ->where('status', 'completed')
                                          ->sum('total_amount');
        }

        // Method 2: Check for transactions that represent sales
        if (class_exists('\App\Models\Transaction')) {
            $totalSales += \App\Models\Transaction::where('user_id', $userId)
                                                ->where('type', 'purchase')
                                                ->where('status', 'completed')
                                                ->sum('amount');
        }

        // Fallback: Use a fixed value for testing or if no sales models exist
        // This can be removed once proper sales tracking is implemented
        if ($totalSales == 0) {
            // For testing purposes, assign some points based on user ID
            // This ensures the rank system can be tested even without actual sales
            $totalSales = ($userId * 10); // Simple calculation for demo
        }

        return $totalSales;
    }

    /**
     * Enhanced rank conditions report with 30-day qualification tracking
     */
    public function getRankConditionsReportEnhanced($userId)
    {
        $basicReport = $this->getRankConditionsReport($userId);
        
        if (!$basicReport) {
            return null;
        }

        // Add qualification period information for all achieved ranks
        $achievedRanks = BinaryRankAchievement::where('user_id', $userId)
                                            ->where('is_achieved', true)
                                            ->get();

        $qualificationDetails = [];
        foreach ($achievedRanks as $rank) {
            $qualificationDetails[$rank->rank_name] = [
                'qualification_status' => $rank->qualification_status,
                'qualification_period_active' => $rank->qualification_period_active,
                'qualification_days_remaining' => $rank->qualification_days_remaining,
                'salary_eligible' => $rank->salary_eligible,
                'salary_eligible_date' => $rank->salary_eligible_date,
                'qualification_start_date' => $rank->salary_qualification_start_date,
                'qualification_tracking' => $rank->qualification_monthly_tracking ?? []
            ];
        }

        $basicReport['qualification_details'] = $qualificationDetails;
        
        return $basicReport;
    }

    /**
     * Update qualification progress for all active qualification periods
     */
    public function updateAllQualificationProgress($userId)
    {
        $monthlyLeftNew = $this->calculateMonthlyNewPoints($userId, 'left');
        $monthlyRightNew = $this->calculateMonthlyNewPoints($userId, 'right');

        $activeQualifications = BinaryRankAchievement::where('user_id', $userId)
                                                   ->where('qualification_period_active', true)
                                                   ->get();

        foreach ($activeQualifications as $rank) {
            $rank->updateQualificationProgress($monthlyLeftNew, $monthlyRightNew);
            
            // Send qualification reminder notifications
            $this->sendQualificationReminders($rank);
        }
    }

    /**
     * Send qualification reminder notifications based on remaining days
     */
    private function sendQualificationReminders($rankAchievement)
    {
        try {
            $daysRemaining = $rankAchievement->getDaysRemainingInQualificationPeriod();
            
            // Send reminders at 7 days, 3 days, and 1 day remaining
            if (in_array($daysRemaining, [7, 3, 1])) {
                $this->notificationService->sendQualificationReminder(
                    $rankAchievement->user_id,
                    $rankAchievement->rank_name,
                    $daysRemaining,
                    [
                        'reference_type' => 'qualification_reminder',
                        'reference_id' => $rankAchievement->id,
                        'data' => [
                            'rank_level' => $rankAchievement->rank_level,
                            'days_remaining' => $daysRemaining,
                            'monthly_left_required' => $rankAchievement->monthly_left_points,
                            'monthly_right_required' => $rankAchievement->monthly_right_points,
                            'current_monthly_left' => $rankAchievement->current_monthly_left ?? 0,
                            'current_monthly_right' => $rankAchievement->current_monthly_right ?? 0,
                            'qualification_end_date' => $rankAchievement->qualification_end_date
                        ]
                    ]
                );
                
                Log::info("✅ Qualification reminder sent for user {$rankAchievement->user_id}, rank: {$rankAchievement->rank_name}, {$daysRemaining} days remaining");
            }
        } catch (\Exception $e) {
            Log::error("❌ Failed to send qualification reminder: " . $e->getMessage());
        }
    }

    /**
     * Check and distribute eligible salaries
     */
    public function processSalaryDistribution($userId)
    {
        $eligibleRanks = BinaryRankAchievement::where('user_id', $userId)
                                            ->where('salary_eligible', true)
                                            ->where('is_current_rank', true)
                                            ->get();

        $salariesDistributed = [];

        foreach ($eligibleRanks as $rank) {
            if ($rank->isEligibleForSalary()) {
                // Check if user maintains monthly conditions for current month
                $monthlyLeftNew = $this->calculateMonthlyNewPoints($userId, 'left');
                $monthlyRightNew = $this->calculateMonthlyNewPoints($userId, 'right');

                if ($monthlyLeftNew >= $rank->monthly_left_points && 
                    $monthlyRightNew >= $rank->monthly_right_points) {
                    
                    $salaryAmount = $this->distributeSalary($rank);
                    $salariesDistributed[] = [
                        'rank_name' => $rank->rank_name,
                        'salary_amount' => $salaryAmount,
                        'distributed_at' => now()
                    ];
                }
            }
        }

        return $salariesDistributed;
    }

    /**
     * Distribute salary for a specific rank
     */
    private function distributeSalary($rankAchievement)
    {
        $salaryAmount = $rankAchievement->salary_amount;
        
        try {
            DB::beginTransaction();
            
            // Get the user
            $user = User::find($rankAchievement->user_id);
            if (!$user) {
                throw new \Exception("User not found for salary distribution: {$rankAchievement->user_id}");
            }
            
            // Add salary to user's interest_wallet
            $user->increment('interest_wallet', $salaryAmount);
            
            // Create transaction record
            $transactionId = 'RANK_SALARY_' . $rankAchievement->id . '_' . time();
            
            Transaction::create([
                'user_id' => $rankAchievement->user_id,
                'transaction_id' => $transactionId,
                'type' => 'rank_salary',
                'amount' => $salaryAmount,
                'fee' => 0,
                'status' => 'completed',
                'payment_method' => 'internal',
                'wallet_type' => 'interest_wallet',
                'description' => "Monthly salary for {$rankAchievement->rank_name} rank - 30 days qualification completed",
                'note' => "Rank: {$rankAchievement->rank_name}, Qualification Period: 30 days",
                'reference_type' => 'binary_rank_achievement',
                'reference_id' => $rankAchievement->id,
                'processed_by' => 'system',
                'processed_at' => now()
            ]);

            // Update rank achievement record
            $rankAchievement->increment('total_salary_paid', $salaryAmount);
            $rankAchievement->increment('salary_months_paid');
            $rankAchievement->update(['last_qualified_month' => now()]);
            
            // Send real-time salary payment notification
            try {
                $this->notificationService->sendSalaryPayment(
                    $rankAchievement->user_id,
                    $salaryAmount,
                    $rankAchievement->rank_name,
                    [
                        'reference_type' => 'salary_payment',
                        'reference_id' => $transactionId,
                        'data' => [
                            'rank_level' => $rankAchievement->rank_level,
                            'transaction_id' => $transactionId,
                            'month' => now()->format('F Y'),
                            'wallet_type' => 'interest_wallet',
                            'qualification_period' => '30 days'
                        ]
                    ]
                );
                
                Log::info("✅ Salary payment notification sent for user {$rankAchievement->user_id}, amount: ৳{$salaryAmount}");
            } catch (\Exception $e) {
                Log::error("❌ Failed to send salary payment notification: " . $e->getMessage());
            }
            
            DB::commit();
            
            Log::info("Distributed salary of ৳{$salaryAmount} to user {$rankAchievement->user_id} (interest_wallet) for {$rankAchievement->rank_name} rank");

            return $salaryAmount;
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error distributing salary for user {$rankAchievement->user_id}: " . $e->getMessage());
            throw $e;
        }
    }
}