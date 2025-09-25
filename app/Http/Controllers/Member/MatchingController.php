<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use App\Models\CommissionSetting;
use App\Models\Transaction;
use App\Services\MatchingService;
use App\Services\PointService;
use App\Services\BinaryRankService;
use App\Services\NotificationService;
use Carbon\Carbon;

class MatchingController extends Controller
{
    protected $matchingService;
    protected $pointService;
    protected $binaryRankService;
    protected $notificationService;

    public function __construct(MatchingService $matchingService, PointService $pointService, BinaryRankService $binaryRankService, NotificationService $notificationService)
    {
        $this->matchingService = $matchingService;
        $this->pointService = $pointService;
        $this->binaryRankService = $binaryRankService;
        $this->notificationService = $notificationService;
        $this->binaryRankService = $binaryRankService;
    }

    /**
     * Display matching bonus dashboard
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $targetUser = $user; // Default to current user
        $isViewingOtherUser = false;
        
        // Check if viewing another user's tree
        if ($request->has('tree_user') && !empty($request->tree_user)) {
            $searchUser = User::where('username', $request->tree_user)
                            ->orWhere('referral_code', $request->tree_user)
                            ->first();
            
            if ($searchUser) {
                // Check if the searched user is in current user's network
                if ($this->isUserInNetwork($user, $searchUser)) {
                    $targetUser = $searchUser;
                    $isViewingOtherUser = true;
                } else {
                    // If not in network, show error and fallback to current user
                    session()->flash('error', 'User not found in your binary tree network.');
                }
            } else {
                session()->flash('error', 'User not found.');
            }
        }
        
        // Get matching settings (now multiple records)
        $matchingSettings = CommissionSetting::where('type', 'matching')
            ->where('is_active', true)
            ->orderBy('min_qualification', 'asc')
            ->get();

        // Determine user's highest qualified level
        $userQualifiedLevel = $this->getUserHighestQualifiedLevel($targetUser, $matchingSettings);

        // Get latest binary summary
        $binarySummary = BinarySummary::where('user_id', $targetUser->id)
            ->latest()
            ->first();

        // Get today's matching bonuses
        $todayMatching = BinaryMatching::where('user_id', $targetUser->id)
            ->whereDate('created_at', today())
            ->sum('matching_bonus');

        // Get this month's matching bonuses
        $monthlyMatching = BinaryMatching::where('user_id', $targetUser->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('matching_bonus');

        // Get total matching bonuses
        $totalMatching = BinaryMatching::where('user_id', $targetUser->id)
            ->sum('matching_bonus');

        // Get recent matching history
        $recentMatching = BinaryMatching::where('user_id', $targetUser->id)
            ->latest()
            ->take(5)
            ->get();

        // Get qualification status
        $qualificationStatus = $this->getQualificationStatus($targetUser, $matchingSettings);

        // Get leg volumes for chart
        $legVolumes = $this->getLegVolumesData($targetUser);

        // Get point information for the new point-based system
        $pointBalance = $this->pointService->getUserPointBalance($targetUser);
        $legPoints = $this->getLegPointsData($targetUser);

        return view('member.matching.dashboard', compact(
            'user',
            'targetUser',
            'isViewingOtherUser',
            'matchingSettings',
            'binarySummary',
            'todayMatching',
            'monthlyMatching',
            'totalMatching',
            'recentMatching',
            'qualificationStatus',
            'legVolumes',
            'pointBalance',
            'legPoints'
        ));
    }

    /**
     * Display matching bonus history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = BinaryMatching::where('user_id', $user->id);

        // Apply filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Get matching history with pagination
        $matchings = $query->latest()->paginate(20);

        // Get summary statistics
        $totalAmount = BinaryMatching::where('user_id', $user->id)->sum('matching_bonus');
        $totalCount = BinaryMatching::where('user_id', $user->id)->count();
        $avgAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;

        // Get point statistics
        $totalPointsUsed = BinaryMatching::where('user_id', $user->id)->sum('matching_volume') / 6; // Convert value back to points
        $pointBalance = $this->pointService->getUserPointBalance($user);
        $legPoints = $this->getLegPointsData($user);

        return view('member.matching.history', compact(
            'matchings',
            'totalAmount',
            'totalCount',
            'avgAmount',
            'totalPointsUsed',
            'pointBalance',
            'legPoints',
            'user'
        ));
    }

    /**
     * Get user's highest qualified level
     */
    private function getUserHighestQualifiedLevel($user, $matchingSettings)
    {
        $userSalesVolume = $user->monthly_sales_volume ?? 0;
        $highestQualifiedLevel = null;
        
        foreach ($matchingSettings as $setting) {
            if ($userSalesVolume >= ($setting->min_qualification ?? 0)) {
                $highestQualifiedLevel = $setting;
            }
        }
        
        return $highestQualifiedLevel;
    }

    /**
     * Display qualification requirements and status
     */
    public function qualifications()
    {
        $user = Auth::user();
        
        // Get all matching settings (now we have one record per level)
        $matchingSettings = CommissionSetting::where('type', 'matching')
            ->where('is_active', true)
            ->orderBy('min_qualification', 'asc')
            ->get();

        if ($matchingSettings->isEmpty()) {
            return view('member.matching.qualifications', [
                'message' => 'No matching settings configured.',
                'qualifications' => [],
                'user' => $user
            ]);
        }

        // Get qualification status for each level
        $qualifications = [];

        foreach ($matchingSettings as $index => $setting) {
            $levelNumber = $index + 1;
            $qualifications[$levelNumber] = $this->getLevelQualificationStatusNew($user, $setting, $levelNumber);
        }

        // Get current binary summary
        $binarySummary = BinarySummary::where('user_id', $user->id)
            ->latest()
            ->first();

        // Get point information for point-based system
        $pointBalance = $this->pointService->getUserPointBalance($user);
        $legPoints = $this->getLegPointsData($user);

        return view('member.matching.qualifications', compact(
            'matchingSettings',
            'qualifications',
            'binarySummary',
            'user',
            'pointBalance',
            'legPoints'
        ));
    }

    /**
     * Display matching bonus calculator
     */
    public function calculator()
    {
        $user = Auth::user();
        
        // Get matching settings
        $matchingSettings = CommissionSetting::where('type', 'matching')
            ->where('is_active', true)
            ->first();

        // Get current binary summary
        $binarySummary = BinarySummary::where('user_id', $user->id)
            ->latest()
            ->first();

        // Get point information for point-based system
        $pointBalance = $this->pointService->getUserPointBalance($user);
        $legPoints = $this->getLegPointsData($user);

        return view('member.matching.calculator', compact(
            'matchingSettings',
            'binarySummary',
            'user',
            'pointBalance',
            'legPoints'
        ));
    }

    /**
     * Display rank-wise salary conditions report
     */
    public function rankSalaryReport()
    {
        $user = Auth::user();
        
        try {
            // Get enhanced rank conditions report with qualification period tracking
            $rankReport = $this->binaryRankService->getRankConditionsReportEnhanced($user->id);
            
            // Get current rank information
            $currentRank = $this->binaryRankService->getCurrentRank($user->id);
            
            // Get current matching bonus for the month
            $currentMatchingBonus = $this->binaryRankService->calculateCurrentMatchingBonus($user->id);
            
            // Get monthly qualification status
            $monthlyQualified = $this->binaryRankService->isMonthlyQualified($user->id);
            
            // Get earnings projection for all ranks
            $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();
            $leftPoints = $binarySummary->lifetime_left_volume ?? 0;
            $rightPoints = $binarySummary->lifetime_right_volume ?? 0;
            $earningsProjection = $this->binaryRankService->calculateEarningsProjection($user->id, $leftPoints, $rightPoints);
            
            // Get qualification history
            $qualificationHistory = $this->binaryRankService->getQualificationHistory($user->id, 6);
            
            // Get recent rank salary transactions
            $recentSalaryTransactions = Transaction::where('user_id', $user->id)
                ->where('type', 'rank_salary')
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            // Create userRankData structure for the view
            $userRankData = [
                'current_rank' => $rankReport['user_data']['current_rank'] ?? null,
                'monthly_salary' => $rankReport['user_data']['monthly_salary'] ?? 0,
                'matching_bonus' => $currentMatchingBonus,
                'monthly_qualified' => $monthlyQualified,
                'left_points' => $rankReport['user_data']['left_points'] ?? 0,
                'right_points' => $rankReport['user_data']['right_points'] ?? 0,
                'monthly_left_new' => $rankReport['user_data']['monthly_left_new'] ?? 0,
                'monthly_right_new' => $rankReport['user_data']['monthly_right_new'] ?? 0,
            ];
            
            // Extract rank conditions from report
            $rankConditions = $rankReport['rank_conditions'] ?? [];
            
            // Add qualification details to each rank condition
            $qualificationDetails = $rankReport['qualification_details'] ?? [];
            foreach ($rankConditions as &$condition) {
                if (isset($qualificationDetails[$condition->rank_name])) {
                    $condition->qualification_details = $qualificationDetails[$condition->rank_name];
                }
            }
            
            // Get earnings projection for the view
            $earningsProjectionFormatted = $rankReport['earnings_projection'] ?? [];
            
            return view('member.matching.rank-salary-report', compact(
                'user',
                'userRankData',
                'rankConditions',
                'earningsProjection',
                'qualificationHistory',
                'recentSalaryTransactions'
            ));
            
        } catch (\Exception $e) {
            Log::error('Rank salary report error: ' . $e->getMessage());
            
            // Provide default data structure for error cases
            $userRankData = [
                'current_rank' => null,
                'monthly_salary' => 0,
                'matching_bonus' => 0,
                'monthly_qualified' => false,
                'left_points' => 0,
                'right_points' => 0,
                'monthly_left_new' => 0,
                'monthly_right_new' => 0,
            ];
            
            $rankConditions = [];
            $earningsProjectionFormatted = [];
            
            return view('member.matching.rank-salary-report', compact(
                'user',
                'userRankData', 
                'rankConditions',
                'earningsProjectionFormatted'
            ))->with([
                'error' => 'Unable to load rank salary report. Please try again later.'
            ]);
        }
    }

    /**
     * Calculate potential matching bonus
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'left_volume' => 'required|numeric|min:0',
            'right_volume' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $leftVolume = $request->left_volume;
        $rightVolume = $request->right_volume;

        // Get matching settings
        $matchingSettings = CommissionSetting::where('type', 'matching')
            ->where('is_active', true)
            ->first();

        if (!$matchingSettings) {
            return response()->json([
                'success' => false,
                'message' => 'No matching settings configured.'
            ]);
        }

        try {
            // Calculate potential bonus using matching service
            $calculation = $this->matchingService->calculatePotentialBonus($user, $leftVolume, $rightVolume);

            return response()->json([
                'success' => true,
                'calculation' => $calculation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating bonus: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get binary summary data for AJAX
     */
    public function getBinarySummary()
    {
        $user = Auth::user();
        
        $binarySummary = BinarySummary::where('user_id', $user->id)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'summary' => $binarySummary
        ]);
    }

    /**
     * Get leg volumes data for charts
     */
    public function getLegVolumes()
    {
        $user = Auth::user();
        $volumes = $this->getLegVolumesData($user);

        return response()->json([
            'success' => true,
            'volumes' => $volumes
        ]);
    }

    /**
     * Get qualification status for user
     */
    private function getQualificationStatus($user, $matchingSettings)
    {
        if ($matchingSettings->isEmpty()) {
            return [
                'qualified' => false,
                'message' => 'No matching settings configured.',
                'current_volume' => 0,
                'levels' => [],
                'highest_qualified_level' => null,
                'missing' => ['No matching settings configured'],
                'requirements' => []
            ];
        }

        $userSalesVolume = $user->monthly_sales_volume ?? 0;
        $status = [
            'current_volume' => $userSalesVolume,
            'levels' => [],
            'highest_qualified_level' => null,
            'missing' => [],
            'requirements' => []
        ];

        // Check if any level is qualified
        $anyLevelQualified = false;
        $minimumRequiredVolume = PHP_INT_MAX;

        foreach ($matchingSettings as $index => $setting) {
            $levelNumber = $index + 1;
            $minRequired = $setting->min_qualification ?? 0;
            $isQualified = $userSalesVolume >= $minRequired;
            
            if ($minRequired < $minimumRequiredVolume) {
                $minimumRequiredVolume = $minRequired;
            }
            
            $levelStatus = [
                'level' => $levelNumber,
                'name' => $setting->display_name,
                'min_qualification' => $minRequired,
                'qualified' => $isQualified,
                'bonus_amount' => $setting->value,
                'max_payout' => $setting->max_payout
            ];
            
            $status['levels'][$levelNumber] = $levelStatus;
            
            if ($isQualified) {
                $status['highest_qualified_level'] = $levelNumber;
                $anyLevelQualified = true;
            }
        }

        // Add qualified flag for backward compatibility
        $status['qualified'] = $anyLevelQualified;
        
        // Add missing requirements if not qualified
        if (!$anyLevelQualified) {
            $status['missing'][] = "Minimum sales volume of ৳" . number_format($minimumRequiredVolume, 2) . " required";
        }
        
        // Add requirements details
        $status['requirements'] = [
            'monthly_sales' => [
                'value' => '৳' . number_format($userSalesVolume, 2),
                'met' => $userSalesVolume >= $minimumRequiredVolume
            ]
        ];

        return $status;
    }

    /**
     * Get level-specific qualification status for new structure
     */
    private function getLevelQualificationStatusNew($user, $setting, $levelNumber)
    {
        $minQualification = $setting->min_qualification ?? 0;
        $maxPayout = $setting->max_payout ?? null;
        $commissionRate = $setting->value ?? 0;

        // Get user's qualification amount (monthly sales volume)
        $userQualification = $user->monthly_sales_volume ?? 0;

        // Calculate remaining needed for qualification
        $remainingNeeded = max(0, $minQualification - $userQualification);

        // Get current matching volume
        $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();
        $leftVolume = $binarySummary->left_total_volume ?? 0;
        $rightVolume = $binarySummary->right_total_volume ?? 0;
        $matchableVolume = min($leftVolume, $rightVolume);

        return [
            'level' => $levelNumber,
            'name' => $setting->display_name,
            'commission_rate' => $commissionRate,
            'min_qualification' => $minQualification,
            'max_payout' => $maxPayout,
            'user_qualification' => $userQualification,
            'qualified' => $userQualification >= $minQualification,
            'remaining_needed' => $remainingNeeded,
            'left_volume' => $leftVolume,
            'right_volume' => $rightVolume,
            'matchable_volume' => $matchableVolume,
            'potential_earning' => $matchableVolume > 0 ? ($commissionRate * ($matchableVolume / 100)) : 0,
            'potential_earning_capped' => $maxPayout ? min($commissionRate * ($matchableVolume / 100), $maxPayout) : ($commissionRate * ($matchableVolume / 100)),
        ];
    }

    /**
     * Get level-specific qualification status
     */
    private function getLevelQualificationStatus($user, $level, $levelNumber)
    {
        $minQualification = $level['min_qualification'] ?? 0;
        $maxPayout = $level['max_payout'] ?? null;
        $commissionRate = $level['value'] ?? 0;

        // Get user's qualification amount (could be sales volume, investment, etc.)
        $userQualification = $user->monthly_sales_volume ?? 0;

        // Get current matching volume
        $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();
        $leftVolume = $binarySummary->left_total_volume ?? 0;
        $rightVolume = $binarySummary->right_total_volume ?? 0;
        $matchableVolume = min($leftVolume, $rightVolume);

        return [
            'level' => $levelNumber,
            'commission_rate' => $commissionRate,
            'min_qualification' => $minQualification,
            'max_payout' => $maxPayout,
            'user_qualification' => $userQualification,
            'qualified' => $userQualification >= $minQualification,
            'left_volume' => $leftVolume,
            'right_volume' => $rightVolume,
            'matchable_volume' => $matchableVolume,
            'potential_earning' => $matchableVolume * ($commissionRate / 100),
            'remaining_needed' => max(0, $minQualification - $userQualification)
        ];
    }

    /**
     * Get leg volumes data for visualization
     */
    private function getLegVolumesData($user)
    {
        $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();

        if (!$binarySummary) {
            return [
                'left_volume' => 0,
                'right_volume' => 0,
                'matched_volume' => 0,
                'carry_forward' => 0
            ];
        }

        return [
            'left_volume' => $binarySummary->lifetime_left_volume ?? 0,
            'right_volume' => $binarySummary->lifetime_right_volume ?? 0,
            'matched_volume' => min($binarySummary->lifetime_left_volume ?? 0, $binarySummary->lifetime_right_volume ?? 0),
            'carry_forward' => ($binarySummary->lifetime_left_volume + $binarySummary->lifetime_right_volume - min($binarySummary->lifetime_left_volume ?? 0, $binarySummary->lifetime_right_volume ?? 0) * 2),
            'left_carry' => max(0, ($binarySummary->lifetime_left_volume ?? 0) - min($binarySummary->lifetime_left_volume ?? 0, $binarySummary->lifetime_right_volume ?? 0)),
            'right_carry' => max(0, ($binarySummary->lifetime_right_volume ?? 0) - min($binarySummary->lifetime_left_volume ?? 0, $binarySummary->lifetime_right_volume ?? 0))
        ];
    }

    /**
     * Get leg points data for point-based matching visualization
     */
    public function getLegPointsData($user)
    {
        $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();

        if (!$binarySummary) {
            return [
                'left_points' => 0,
                'right_points' => 0,
                'matched_points' => 0,
                'points_carry_forward' => 0,
                'left_points_carry' => 0,
                'right_points_carry' => 0,
                'qualification_met' => false,
                'min_points_required' => 100
            ];
        }

        // Use the correct field names that match the DailyMatchingProcess
        $leftPoints = $binarySummary->lifetime_left_volume ?? 0;
        $rightPoints = $binarySummary->lifetime_right_volume ?? 0;
        $matchedPoints = min($leftPoints, $rightPoints);
        $qualificationMet = $leftPoints >= 100 && $rightPoints >= 100;

        return [
            'left_points' => $leftPoints,
            'right_points' => $rightPoints,
            'matched_points' => $matchedPoints,
            'points_carry_forward' => ($leftPoints + $rightPoints - $matchedPoints * 2),
            'left_points_carry' => max(0, $leftPoints - $matchedPoints),
            'right_points_carry' => max(0, $rightPoints - $matchedPoints),
            'qualification_met' => $qualificationMet,
            'min_points_required' => 100
        ];
    }

    /**
     * Check if a target user is in the current user's binary network
     */
    private function isUserInNetwork($currentUser, $targetUser)
    {
        // If target user is the current user
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Check if target user is in current user's downline (binary tree)
        $queue = [$currentUser->id];
        $visited = [];
        $maxDepth = 10; // Limit depth to prevent infinite loops
        $currentDepth = 0;

        while (!empty($queue) && $currentDepth < $maxDepth) {
            $levelSize = count($queue);
            
            for ($i = 0; $i < $levelSize; $i++) {
                $userId = array_shift($queue);
                
                if (in_array($userId, $visited)) {
                    continue;
                }
                
                $visited[] = $userId;
                
                // Check left and right binary positions
                $leftChild = User::where('binary_parent_id', $userId)
                               ->where('binary_position', 'left')
                               ->first();
                               
                $rightChild = User::where('binary_parent_id', $userId)
                                ->where('binary_position', 'right')
                                ->first();
                
                if ($leftChild) {
                    if ($leftChild->id === $targetUser->id) {
                        return true;
                    }
                    $queue[] = $leftChild->id;
                }
                
                if ($rightChild) {
                    if ($rightChild->id === $targetUser->id) {
                        return true;
                    }
                    $queue[] = $rightChild->id;
                }
            }
            
            $currentDepth++;
        }

        // Check if current user is in target user's upline
        $currentParent = $targetUser->binary_parent_id;
        $uplineDepth = 0;
        
        while ($currentParent && $uplineDepth < $maxDepth) {
            if ($currentParent === $currentUser->id) {
                return true;
            }
            
            $parent = User::find($currentParent);
            if (!$parent) {
                break;
            }
            
            $currentParent = $parent->binary_parent_id;
            $uplineDepth++;
        }

        return false;
    }

    /**
     * Get detailed information about a specific matching bonus transaction
     */
    public function details($id)
    {
        try {
            $user = Auth::user();
            
            // Find the matching record for the authenticated user
            $matching = BinaryMatching::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$matching) {
                return response()->json([
                    'success' => false,
                    'message' => 'Matching record not found or access denied.'
                ]);
            }

            // Since we now store pure points in volume columns (updated system)
            $leftPoints = $matching->left_current_volume; // Pure points
            $rightPoints = $matching->right_current_volume; // Pure points
            $matchedPoints = $matching->matching_volume; // Pure points
            $pointValue = $matchedPoints * 6; // Convert points to Taka value

            // Get binary summary at that time (if available)
            $binarySummary = $user->binarySummary;
            $currentLeftPoints = $binarySummary->left_total_points ?? 0; // Use pure point columns
            $currentRightPoints = $binarySummary->right_total_points ?? 0; // Use pure point columns

            $html = view('member.matching.details-modal', compact(
                'matching', 
                'leftPoints', 
                'rightPoints', 
                'matchedPoints', 
                'pointValue',
                'currentLeftPoints',
                'currentRightPoints'
            ))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            Log::error('Matching details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading matching details.'
            ]);
        }
    }
}
