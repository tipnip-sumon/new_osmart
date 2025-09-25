<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Services\VolumeTrackingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyMatchingProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matching:daily-process {--dry-run : Show what would be processed without executing} {--user= : Process specific user by username}';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Run daily matching process: refresh qualifications and process bonuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificUser = $this->option('user');

        $this->info('=== Daily Matching Process ===');
        $this->info('Date: ' . Carbon::now()->format('Y-m-d H:i:s'));
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No actual processing will occur');
        }

        // Step 1: Refresh user qualifications
        $this->info('Step 1: Refreshing user qualifications...');
        $refreshResult = $this->refreshUserQualifications($specificUser, $isDryRun);
        
        // Step 2: Process matching bonuses
        $this->info('Step 2: Processing matching bonuses...');
        $processResult = $this->processMatchingBonuses($specificUser, $isDryRun);

        // Step 3: Update binary summaries
        $this->info('Step 3: Updating binary summaries...');
        $updateResult = $this->updateBinarySummaries($specificUser, $isDryRun);

        // Summary
        $this->info('=== Daily Process Summary ===');
        $this->info("Qualifications refreshed: {$refreshResult['users_processed']}");
        $this->info("Bonuses processed: {$processResult['users_processed']}");
        $this->info("Total bonus amount: ৳" . number_format($processResult['total_bonus'], 2));
        $this->info("Binary summaries updated: {$updateResult['summaries_updated']}");
        
        if ($refreshResult['errors'] > 0 || $processResult['errors'] > 0) {
            $this->warn("Total errors: " . ($refreshResult['errors'] + $processResult['errors']));
        }

        if ($isDryRun) {
            $this->warn('This was a dry run - no actual processing occurred');
        } else {
            $this->info('Daily matching process completed successfully');
        }

        return Command::SUCCESS;
    }

    /**
     * Refresh user qualifications based on sales data
     */
    private function refreshUserQualifications($specificUser = null, $isDryRun = false)
    {
        // Get users to process
        if ($specificUser) {
            $users = User::where('username', $specificUser)->where('is_active', true)->get();
        } else {
            $users = User::where('is_active', true)->get();
        }

        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                if (!$isDryRun) {
                    $this->refreshSingleUserQualification($user);
                }
                $processed++;
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error refreshing qualification for user {$user->username}: " . $e->getMessage());
            }
        }

        return ['users_processed' => $processed, 'errors' => $errors];
    }

    /**
     * Refresh qualification data for a single user (Pure Point-based system)
     */
    private function refreshSingleUserQualification($user)
    {
        // For point-based system, ensure binary summary exists for users with 100+ points
        if (($user->active_points ?? 0) >= 100) {
            $binarySummary = $user->getOrCreateBinarySummary();
            
            // Calculate point-based volumes for left and right legs (pure points)
            $leftLegPoints = $this->calculateLegPoints($user, 'left');
            $rightLegPoints = $this->calculateLegPoints($user, 'right');
            
            // Update binary summary with pure point tracking
            $binarySummary->update([
                'left_total_points' => $leftLegPoints,          // Store actual points
                'right_total_points' => $rightLegPoints,        // Store actual points
                'lifetime_left_volume' => $leftLegPoints,       // Store points (not Taka conversion)
                'lifetime_right_volume' => $rightLegPoints,     // Store points (not Taka conversion)
                'monthly_left_volume' => $leftLegPoints,        // For monthly tracking (in points)
                'monthly_right_volume' => $rightLegPoints,      // For monthly tracking (in points)
                'last_calculated_at' => now(),
            ]);
            
            // Ensure point fields are set (direct DB update as backup)
            DB::table('binary_summaries')
                ->where('user_id', $user->id)
                ->update([
                    'left_total_points' => $leftLegPoints,
                    'right_total_points' => $rightLegPoints,
                ]);
            
            $this->info("Binary summary updated for user {$user->username} - Left: {$leftLegPoints} points, Right: {$rightLegPoints} points");
        }
    }
    
    /**
     * Calculate total points for a leg (left or right)
     */
    private function calculateLegPoints($user, $position)
    {
        // Get all users in this leg position under the user
        $legUsers = \App\Models\User::where('upline_id', $user->id)
            ->where('position', $position)
            ->get();
        
        $totalPoints = 0;
        
        foreach ($legUsers as $legUser) {
            // Add user's active points
            $totalPoints += $legUser->active_points ?? 0;
            
            // Recursively add points from their downlines
            $totalPoints += $this->calculateLegPointsRecursive($legUser);
        }
        
        return $totalPoints;
    }
    
    /**
     * Recursively calculate points from all downlines
     */
    private function calculateLegPointsRecursive($user, $depth = 0, $maxDepth = 10)
    {
        // Prevent infinite recursion
        if ($depth >= $maxDepth) {
            return 0;
        }
        
        $totalPoints = 0;
        
        // Get all direct downlines (both left and right)
        $downlines = \App\Models\User::where('upline_id', $user->id)->get();
        
        foreach ($downlines as $downline) {
            // Add downline's active points
            $totalPoints += $downline->active_points ?? 0;
            
            // Recursively add points from their downlines
            $totalPoints += $this->calculateLegPointsRecursive($downline, $depth + 1, $maxDepth);
        }
        
        return $totalPoints;
    }

    /**
     * Process matching bonuses for eligible users
     */
    private function processMatchingBonuses($specificUser = null, $isDryRun = false)
    {
        // Get matching settings
        $matchingSettings = CommissionSetting::where('type', 'matching')
            ->where('is_active', true)
            ->orderBy('min_qualification', 'asc')
            ->get();

        if ($matchingSettings->isEmpty()) {
            return ['users_processed' => 0, 'total_bonus' => 0, 'errors' => 1];
        }

        // Get users to process
        if ($specificUser) {
            $users = User::where('username', $specificUser)->where('is_active', true)->get();
        } else {
            $users = User::where('is_active', true)
                ->where('active_points', '>=', 100)
                ->get();
        }

        $processed = 0;
        $totalBonus = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                $result = $this->processSingleUserMatching($user, $matchingSettings, $isDryRun);
                
                if ($result['processed']) {
                    $processed++;
                    $totalBonus += $result['bonus_amount'];
                    
                    if ($isDryRun) {
                        $this->line("  - {$user->username}: ৳" . number_format($result['bonus_amount'], 2));
                    }
                }
                
            } catch (\Exception $e) {
                $errors++;
                Log::error("Error processing matching for user {$user->username}: " . $e->getMessage());
            }
        }

        return ['users_processed' => $processed, 'total_bonus' => $totalBonus, 'errors' => $errors];
    }

    /**
     * Process matching bonus for a single user
     */
    private function processSingleUserMatching($user, $matchingSettings, $isDryRun = false)
    {
        // Get latest binary summary
        $binarySummary = BinarySummary::where('user_id', $user->id)->latest()->first();
        
        if (!$binarySummary) {
            return ['processed' => false, 'bonus_amount' => 0, 'reason' => 'No binary data'];
        }

        // Determine user's highest qualification level
        $userActivePoints = $user->active_points ?? 0;
        $qualifiedLevel = null;
        
        foreach ($matchingSettings as $setting) {
            if ($userActivePoints >= ($setting->min_qualification ?? 0)) {
                $qualifiedLevel = $setting;
            }
        }

        if (!$qualifiedLevel) {
            return ['processed' => false, 'bonus_amount' => 0, 'reason' => 'Not qualified'];
        }

        // Calculate matchable points (use point columns directly)
        $leftPoints = $binarySummary->left_total_points ?? 0;
        $rightPoints = $binarySummary->right_total_points ?? 0;
        $carryForwardPoints = $binarySummary->left_carry_balance ?? 0; // Carry stored as pure points
        
        // Add carry forward points to weaker leg
        if ($carryForwardPoints > 0) {
            if ($leftPoints < $rightPoints) {
                $leftPoints += $carryForwardPoints;
            } else {
                $rightPoints += $carryForwardPoints;
            }
        }
        
        $matchablePoints = min($leftPoints, $rightPoints);
        
        if ($matchablePoints < 100) {
            return ['processed' => false, 'bonus_amount' => 0, 'reason' => "Minimum 100 points required in both legs. Current: Left={$leftPoints}, Right={$rightPoints}"];
        }

        // Pure point calculation: Get commission percentage as points
        $commissionRate = ($qualifiedLevel->value ?? 10) / 100; // Convert percentage to decimal
        $bonusPoints = $matchablePoints * $commissionRate; // This gives us points (e.g., 100 points × 10% = 10 points)
        
        // Convert to Taka only for wallet: bonus_points × 6
        $bonusAmount = $bonusPoints * 6; // Final conversion: 10 points × 6 = 60 Tk
        $maxPayout = $qualifiedLevel->max_payout ?? null;
        
        // Check if there are NEW matchable points since last processing
        $today = Carbon::today();
        $lastMatching = BinaryMatching::where('user_id', $user->id)
            ->whereDate('match_date', $today)
            ->latest()
            ->first();
        
        // If there was already matching today, check if user has accumulated new points
        if ($lastMatching) {
            $alreadyMatched = $lastMatching->matching_volume ?? 0;
            
            // If current matchable points are same or less than already matched, skip
            if ($matchablePoints <= $alreadyMatched) {
                return ['processed' => false, 'bonus_amount' => 0, 'reason' => 'No new matchable points since last processing'];
            }
            
            // Only match the NEW points
            $newMatchablePoints = $matchablePoints - $alreadyMatched;
            $bonusPoints = $newMatchablePoints * $commissionRate;
            $bonusAmount = $bonusPoints * 6;
            $matchablePoints = $newMatchablePoints; // Update to only new points
        }
        
        // Apply daily/weekly caps if configured  
        $todayEarnings = BinaryMatching::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->sum('matching_bonus');
            
        if ($maxPayout && ($todayEarnings + $bonusAmount) > $maxPayout) {
            $bonusAmount = max(0, $maxPayout - $todayEarnings);
        }

        if ($bonusAmount <= 0) {
            return ['processed' => false, 'bonus_amount' => 0, 'reason' => 'Daily cap reached or no new points'];
        }

        if ($isDryRun) {
            return ['processed' => true, 'bonus_amount' => $bonusAmount];
        }

        // Process the matching bonus
        DB::transaction(function () use ($user, $qualifiedLevel, $bonusAmount, $bonusPoints, $matchablePoints, $binarySummary, $leftPoints, $rightPoints) {
            // Create matching record (store PURE POINTS - no Taka conversion)
            BinaryMatching::create([
                'user_id' => $user->id,
                'match_date' => Carbon::today(),
                'period' => 'daily',
                'left_current_volume' => $leftPoints, // Store pure points
                'right_current_volume' => $rightPoints, // Store pure points  
                'matching_volume' => $matchablePoints, // Store pure points
                'matching_percentage' => $qualifiedLevel->value ?? 10,
                'matching_bonus' => $bonusAmount, // This is in Taka (bonusPoints × 6) for wallet
                'left_carry_next' => max(0, $leftPoints - $matchablePoints), // Store carry as points
                'right_carry_next' => max(0, $rightPoints - $matchablePoints), // Store carry as points
                'status' => 'processed',
                'is_processed' => true,
                'processed_at' => Carbon::now(),
                'notes' => 'Pure point matching: ' . $matchablePoints . ' points matched, earned ' . $bonusPoints . ' bonus points (৳' . $bonusAmount . ')'
            ]);

            // Update user balance - Binary matching income goes to interest_wallet (in Taka)
            $user->increment('interest_wallet', $bonusAmount);
            $user->increment('total_earnings', $bonusAmount);

            // Update binary summary - store remaining points after matching
            $remainingLeftPoints = max(0, $leftPoints - $matchablePoints);
            $remainingRightPoints = max(0, $rightPoints - $matchablePoints);
            $newCarryForwardPoints = abs($remainingLeftPoints - $remainingRightPoints);
            
            // Update carry balance as points (not Taka)
            $leftCarryPoints = $remainingLeftPoints > $remainingRightPoints ? $newCarryForwardPoints : 0;
            $rightCarryPoints = $remainingRightPoints > $remainingLeftPoints ? $newCarryForwardPoints : 0;
            
            $binarySummary->update([
                'left_carry_balance' => $leftCarryPoints,     // Store as points
                'right_carry_balance' => $rightCarryPoints,   // Store as points
                'left_total_points' => $remainingLeftPoints,  // Update remaining points
                'right_total_points' => $remainingRightPoints, // Update remaining points
                'last_calculated_at' => Carbon::now(),
            ]);
        });

        return ['processed' => true, 'bonus_amount' => $bonusAmount];
    }

    /**
     * Update binary summaries
     */
    private function updateBinarySummaries($specificUser = null, $isDryRun = false)
    {
        // Get users to update
        if ($specificUser) {
            $users = User::where('username', $specificUser)->where('is_active', true)->get();
        } else {
            $users = User::where('is_active', true)->get();
        }

        $updated = 0;

        foreach ($users as $user) {
            try {
                if (!$isDryRun) {
                    $this->updateUserBinarySummary($user);
                }
                $updated++;
            } catch (\Exception $e) {
                Log::error("Error updating binary summary for user {$user->username}: " . $e->getMessage());
            }
        }

        return ['summaries_updated' => $updated];
    }

    /**
     * Update binary summary for a single user
     */
    private function updateUserBinarySummary($user)
    {
        // This would typically involve calculating volumes from downline
        // For now, we'll just ensure the summary exists
        
        $binarySummary = BinarySummary::firstOrCreate(
            ['user_id' => $user->id],
            [
                'lifetime_left_volume' => 0,
                'lifetime_right_volume' => 0,
                'left_carry_balance' => 0,
                'right_carry_balance' => 0,
                'current_period_left' => 0,
                'current_period_right' => 0,
                'last_calculated_at' => Carbon::now(),
            ]
        );

        // Update timestamp
        $binarySummary->update(['last_calculated_at' => Carbon::now()]);
    }
}
