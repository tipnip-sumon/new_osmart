<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BinaryRankService;
use App\Models\BinaryRankAchievement;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProcessRankQualifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rank:process-qualifications 
                          {--user-id= : Process qualifications for specific user}
                          {--distribute-salary : Distribute eligible salaries}
                          {--dry-run : Show what would be processed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process rank qualifications, update 30-day periods, and distribute eligible salaries';

    protected $binaryRankService;

    public function __construct(BinaryRankService $binaryRankService)
    {
        parent::__construct();
        $this->binaryRankService = $binaryRankService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Processing Rank Qualifications...');
        
        $userIds = $this->getUserIds();
        $dryRun = $this->option('dry-run');
        $distributeSalary = $this->option('distribute-salary');
        
        $processedUsers = 0;
        $qualificationUpdates = 0;
        $salariesDistributed = 0;
        $totalSalaryAmount = 0;
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
        }
        
        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if (!$user) {
                    $this->error("User not found: {$userId}");
                    continue;
                }
                
                $this->info("Processing user: {$user->name} (ID: {$userId})");
                
                // Update qualification progress for all active qualification periods
                if (!$dryRun) {
                    $this->binaryRankService->updateAllQualificationProgress($userId);
                }
                
                // Get active qualification periods
                $activeQualifications = BinaryRankAchievement::where('user_id', $userId)
                                                           ->where('qualification_period_active', true)
                                                           ->get();
                
                foreach ($activeQualifications as $qualification) {
                    $this->info("  - {$qualification->rank_name}: {$qualification->qualification_days_remaining} days remaining");
                    $qualificationUpdates++;
                }
                
                // Distribute eligible salaries if requested
                if ($distributeSalary) {
                    if (!$dryRun) {
                        $distributedSalaries = $this->binaryRankService->processSalaryDistribution($userId);
                        foreach ($distributedSalaries as $salaryInfo) {
                            $this->info("  💰 Distributed salary: ৳{$salaryInfo['salary_amount']} for {$salaryInfo['rank_name']} (added to interest_wallet)");
                            $totalSalaryAmount += $salaryInfo['salary_amount'];
                            $salariesDistributed++;
                        }
                    } else {
                        // Show what would be distributed
                        $eligibleRanks = BinaryRankAchievement::where('user_id', $userId)
                                                            ->where('salary_eligible', true)
                                                            ->where('is_current_rank', true)
                                                            ->get();
                        
                        foreach ($eligibleRanks as $rank) {
                            if ($rank->isEligibleForSalary()) {
                                $monthlyLeftNew = $this->binaryRankService->calculateMonthlyNewPoints($userId, 'left');
                                $monthlyRightNew = $this->binaryRankService->calculateMonthlyNewPoints($userId, 'right');
                                
                                if ($monthlyLeftNew >= $rank->monthly_left_points && 
                                    $monthlyRightNew >= $rank->monthly_right_points) {
                                    
                                    $this->info("  💰 Would distribute salary: ৳{$rank->salary_amount} for {$rank->rank_name} (to interest_wallet)");
                                    $totalSalaryAmount += $rank->salary_amount;
                                    $salariesDistributed++;
                                }
                            }
                        }
                    }
                }
                
                $processedUsers++;
                
            } catch (\Exception $e) {
                $this->error("Error processing user {$userId}: " . $e->getMessage());
                Log::error("ProcessRankQualifications error for user {$userId}: " . $e->getMessage());
            }
        }
        
        // Summary
        $this->info("\n📊 Processing Summary:");
        $this->table(['Metric', 'Count'], [
            ['Users Processed', $processedUsers],
            ['Qualification Updates', $qualificationUpdates],
            ['Salaries Distributed', $salariesDistributed],
            ['Total Salary Amount', "৳" . number_format($totalSalaryAmount)]
        ]);
        
        if ($dryRun) {
            $this->warn('This was a dry run. Use --distribute-salary flag to actually distribute salaries.');
        } else {
            $this->info('✅ Rank qualification processing completed!');
        }
        
        return 0;
    }
    
    private function getUserIds()
    {
        $userId = $this->option('user-id');
        
        if ($userId) {
            return [$userId];
        }
        
        // Get all users with active qualification periods
        return BinaryRankAchievement::where('qualification_period_active', true)
                                   ->distinct('user_id')
                                   ->pluck('user_id')
                                   ->toArray();
    }
}
