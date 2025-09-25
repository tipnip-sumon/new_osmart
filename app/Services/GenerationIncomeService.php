<?php

namespace App\Services;

use App\Models\User;
use App\Models\GenerationIncome;
use App\Models\MlmRank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerationIncomeService
{
    /**
     * Process generation income when a user upgrades account or gets new points
     */
    public function processGenerationIncome(User $user, int $pointsUpgraded, string $source = 'upgrade'): array
    {
        $processed = [];
        $currentUser = $user;
        $level = 1;
        
        Log::info("Processing generation income for user {$user->id} with points upgraded {$pointsUpgraded}");
        
        try {
            DB::beginTransaction();
            
            // Traverse up the sponsorship tree for 20 levels
            while ($currentUser && $level <= 20) {
                $sponsor = $currentUser->sponsor_id ? User::find($currentUser->sponsor_id) : null;
                
                if (!$sponsor) {
                    Log::info("No sponsor found at level {$level}, stopping traversal");
                    break;
                }
                
                Log::info("Processing level {$level}: sponsor {$sponsor->id} ({$sponsor->username})");
                
                // Calculate generation income for this level (percentage-based)
                $percentage = $this->getPercentageForLevel($level);
                $pointValue = $pointsUpgraded * 6; // 1 point = 6 TK
                $amount = ($pointValue * $percentage) / 100;
                $status = $this->determineIncomeStatus($sponsor);
                
                // Create generation income record
                $generationIncome = GenerationIncome::create([
                    'user_id' => $sponsor->id,
                    'from_user_id' => $user->id,
                    'generation_level' => $level,
                    'points' => $percentage, // Store percentage instead of fixed points
                    'amount' => $amount,
                    'business_volume' => $pointValue, // Store point value (points * 6)
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? now() : null,
                    'payment_reason' => $status === 'paid' ? 'first_rank_achieved' : null,
                    'remarks' => $status === 'paid' ? 'Generation income paid to first rank achiever' : null,
                    'meta_data' => [
                        'source' => $source,
                        'original_user' => $user->id,
                        'points_upgraded' => $pointsUpgraded,
                        'percentage' => $percentage,
                        'point_value' => $pointValue,
                        'sponsor_rank' => $sponsor->rank ?? 'member',
                        'sponsor_role' => $sponsor->role,
                        'processed_at' => now()->toISOString()
                    ]
                ]);
                
                // If status is paid (first rank achiever), add to interest wallet immediately
                if ($status === 'paid') {
                    $balanceBefore = $sponsor->interest_wallet;
                    $sponsor->increment('interest_wallet', $amount);
                    $balanceAfter = $sponsor->interest_wallet;
                    
                    // Create transaction record for immediate payment
                    \App\Models\Transaction::create([
                        'user_id' => $sponsor->id,
                        'type' => 'credit',
                        'amount' => $amount,
                        'wallet_type' => 'interest_wallet',
                        'reference_id' => $generationIncome->id,
                        'reference_type' => 'generation_income',
                        'description' => "Generation income from Level {$level} - First rank achiever",
                        'metadata' => [
                            'generation_level' => $level,
                            'from_user_id' => $user->id,
                            'points_upgraded' => $pointsUpgraded,
                            'percentage' => $percentage,
                            'point_value' => $pointValue,
                            'source' => $source,
                            'sponsor_rank' => $sponsor->rank ?? 'member',
                            'sponsor_role' => $sponsor->role,
                            'processed_at' => now()->toISOString()
                        ],
                        'status' => 'completed'
                    ]);
                    
                    Log::info("Paid generation income {$amount} TK directly to interest_wallet for user {$sponsor->id} at level {$level}");
                } elseif ($status === 'pending') {
                    Log::info("Generation income {$amount} TK for user {$sponsor->id} at level {$level} marked as pending");
                } else {
                    Log::info("Generation income {$amount} TK for user {$sponsor->id} at level {$level} marked as invalid (free account)");
                }
                
                $processed[] = [
                    'level' => $level,
                    'sponsor_id' => $sponsor->id,
                    'sponsor_username' => $sponsor->username,
                    'percentage' => $percentage,
                    'points_upgraded' => $pointsUpgraded,
                    'amount' => $amount,
                    'status' => $generationIncome->status,
                    'income_id' => $generationIncome->id
                ];
                
                // Move to next level
                $currentUser = $sponsor;
                $level++;
            }
            
            DB::commit();
            Log::info("Successfully processed generation income for " . ($level - 1) . " levels");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error processing generation income: " . $e->getMessage());
            throw $e;
        }
        
        return $processed;
    }
    
    /**
     * Determine income status based on user eligibility
     */
    private function determineIncomeStatus(User $user): string
    {
        // Free account users don't get generation income
        if ($this->isFreeAccount($user)) {
            return 'invalid';
        }
        
        // Check if user is eligible for any generation income
        if (!$this->isEligibleForGenerationIncome($user)) {
            return 'invalid';
        }
        
        // Check if user qualifies for immediate payment (first rank achiever)
        if ($this->qualifiesForPaidIncome($user)) {
            return 'paid'; // First rank achievers get immediate payment
        }
        
        // Users with minimum active package get pending income
        return 'pending';
    }
    
    /**
     * Check if user is eligible for generation income (first rank achiever)
     * Two-tier system:
     * 1. Minimum 100 points active package → Gets income but PENDING
     * 2. First rank achiever (binary matching) → PENDING income becomes PAID
     */
    public function isEligibleForGenerationIncome(User $user): bool
    {
        // Base requirement: Must not be a free account
        if ($this->isFreeAccount($user)) {
            return false;
        }
        
        // Criteria 1: Must have minimum self active package of 100 points
        $hasMinimumActivePackage = $this->hasMinimumActivePackage($user);
        
        // Criteria 2: Must be first rank achiever (binary matching qualification)
        $isFirstRankAchiever = $this->isFirstRankAchiever($user);
        
        // Return true if user has minimum active package (will get PENDING income)
        // OR if user is first rank achiever (will get PAID income)
        return $hasMinimumActivePackage || $isFirstRankAchiever;
    }
    
    /**
     * Check if user qualifies for PAID generation income (first rank achiever)
     */
    public function qualifiesForPaidIncome(User $user): bool
    {
        // Base requirement: Must not be a free account
        if ($this->isFreeAccount($user)) {
            return false;
        }
        
        // Must have BOTH criteria for PAID status:
        // - Minimum 100 points self active package
        // - First rank achiever status (binary matching)
        $hasMinimumActivePackage = $this->hasMinimumActivePackage($user);
        $isFirstRankAchiever = $this->isFirstRankAchiever($user);
        
        return $hasMinimumActivePackage && $isFirstRankAchiever;
    }
    
    /**
     * Check if user account is free (no generation income eligibility)
     */
    private function isFreeAccount(User $user): bool
    {
        // Free accounts are customer role with no minimum active package and no first rank achiever status
        return $user->role === 'customer' && 
               ($user->rank === 'member' || !$user->rank) && 
               !$this->hasMinimumActivePackage($user) &&
               !$this->isFirstRankAchiever($user);
    }
    
    /**
     * Check if user has minimum active package of 100 points
     */
    private function hasMinimumActivePackage(User $user): bool
    {
        // Check if user has active packages worth at least 100 points total
        $totalActivePoints = DB::table('user_active_packages')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->sum('points_allocated');
            
        // If no active packages with points_allocated, fall back to user's active_points check
        if ($totalActivePoints === null || $totalActivePoints == 0) {
            // Alternative: Check if user has active packages and minimum 100 active points
            $hasActivePackages = DB::table('user_active_packages')
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->exists();
                
            return $hasActivePackages && ($user->active_points ?? 0) >= 100;
        }
        
        return $totalActivePoints >= 100; // Minimum 100 points in active packages
    }
    
    /**
     * Check if user is first rank achiever
     * Criteria: Left side 1000 points + Right side 1000 points = 600 tk binary matching
     */
    private function isFirstRankAchiever(User $user): bool
    {
        // Check if user has achieved binary matching qualification
        // First rank = Left 1000 points + Right 1000 points = 10% matching (600 tk)
        return $this->hasBinaryMatchingQualification($user);
    }
    
    /**
     * Check if user has achieved binary matching qualification for first rank
     */
    private function hasBinaryMatchingQualification(User $user): bool
    {
        // Get user's binary matching data from binary_matchings table
        $binaryMatching = DB::table('binary_matchings')
            ->where('user_id', $user->id)
            ->where('is_processed', true)
            ->orderBy('match_date', 'desc')
            ->first();
        
        if (!$binaryMatching) {
            return false; // No binary matching activity yet
        }
        
        // Check if user has at least 1000 points on both left and right sides
        $leftVolume = $binaryMatching->left_current_volume ?? 0;
        $rightVolume = $binaryMatching->right_current_volume ?? 0;
        
        // First rank qualification: Both sides must have at least 1000 points
        return $leftVolume >= 1000 && $rightVolume >= 1000;
    }
    
    /**
     * Get percentage for generation level
     */
    private function getPercentageForLevel(int $level): float
    {
        if ($level <= 2) {
            return 2.0; // 2% for Level 1-2
        } elseif ($level <= 6) {
            return 1.0; // 1% for Level 3-6
        } else {
            return 0.5; // 0.5% for Level 7-20
        }
    }
    
    /**
     * Process pending income when user achieves first rank
     * This should be called when user achieves binary matching qualification
     */
    public function processPendingIncome(User $user, string $reason = 'first_rank_achieved'): int
    {
        // Double check if user now qualifies for paid income
        if (!$this->qualifiesForPaidIncome($user)) {
            Log::info("User {$user->id} does not qualify for paid income, skipping pending income processing");
            return 0;
        }
        
        $pendingIncomes = GenerationIncome::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
        
        $processedCount = 0;
        
        try {
            DB::beginTransaction();
            
            foreach ($pendingIncomes as $income) {
                // Mark income as paid
                $income->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_reason' => $reason,
                    'remarks' => "Generation income paid - {$reason}"
                ]);
                
                // Add to interest wallet
                $balanceBefore = $user->interest_wallet;
                $user->increment('interest_wallet', $income->amount);
                $balanceAfter = $user->interest_wallet;
                
                // Create transaction record
                \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $income->amount,
                    'wallet_type' => 'interest_wallet',
                    'reference_id' => $income->id,
                    'reference_type' => 'generation_income',
                    'description' => "Generation income from Level {$income->generation_level} - {$reason}",
                    'metadata' => [
                        'generation_level' => $income->generation_level,
                        'from_user_id' => $income->from_user_id,
                        'points' => $income->points,
                        'business_volume' => $income->business_volume,
                        'reason' => $reason,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'processed_at' => now()->toISOString()
                    ],
                    'status' => 'completed'
                ]);
                
                $processedCount++;
                
                Log::info("Processed pending generation income {$income->amount} TK for user {$user->id}, generation level {$income->generation_level}");
            }
            
            DB::commit();
            Log::info("Successfully processed {$processedCount} pending generation incomes for user {$user->id}");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error processing pending generation income: " . $e->getMessage());
            throw $e;
        }
        
        return $processedCount;
    }
    
    /**
     * Check if user has newly qualified for first rank and process pending income
     * This should be called after binary matching calculations
     */
    public function checkAndProcessFirstRankQualification(User $user): bool
    {
        // Check if user now qualifies for paid income
        if (!$this->qualifiesForPaidIncome($user)) {
            return false;
        }
        
        // Check if user has any pending generation income
        $hasPendingIncome = GenerationIncome::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
            
        if ($hasPendingIncome) {
            Log::info("User {$user->id} has achieved first rank qualification, processing pending income");
            $processedCount = $this->processPendingIncome($user, 'first_rank_achieved');
            
            if ($processedCount > 0) {
                Log::info("Processed {$processedCount} pending generation incomes for newly qualified user {$user->id}");
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get generation income summary for a user
     */
    public function getGenerationIncomeSummary(User $user): array
    {
        $totalPaid = GenerationIncome::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');
        
        $totalPending = GenerationIncome::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
        
        $totalInvalid = GenerationIncome::where('user_id', $user->id)
            ->where('status', 'invalid')
            ->sum('amount');
        
        $levelWise = GenerationIncome::where('user_id', $user->id)
            ->select('generation_level', 'status', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('generation_level', 'status')
            ->get()
            ->groupBy('generation_level');
        
        return [
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'total_invalid' => $totalInvalid,
            'grand_total' => $totalPaid + $totalPending,
            'level_wise' => $levelWise,
            'is_eligible' => $this->isEligibleForGenerationIncome($user),
            'is_free_account' => $this->isFreeAccount($user)
        ];
    }
    
    /**
     * Get generation income details with pagination
     */
    public function getGenerationIncomeDetails(User $user, int $perPage = 20)
    {
        return GenerationIncome::where('user_id', $user->id)
            ->with(['fromUser'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}