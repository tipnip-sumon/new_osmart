<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserDailyCashback;
use App\Models\Invest;
use App\Models\Transaction;
use App\Models\UserNotification;
use Carbon\Carbon;

class ProcessDailyCashbacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashback:process-daily 
                            {--date= : Process cashbacks for specific date (Y-m-d format)}
                            {--dry-run : Preview what would be processed without actually processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily cashback payments for eligible users with referral validation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();
        $isDryRun = $this->option('dry-run');

        $this->info('🎯 Processing Daily Cashbacks for: ' . $date->format('Y-m-d'));
        
        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No actual payments will be processed');
        }

        // Get all cashback-enabled plans
        $cashbackPlans = Plan::cashbackEnabled()->get();
        
        if ($cashbackPlans->isEmpty()) {
            $this->error('❌ No cashback-enabled plans found.');
            return 1;
        }

        $this->info('📦 Found ' . $cashbackPlans->count() . ' cashback-enabled plans');

        $totalProcessed = 0;
        $totalAmount = 0;
        $totalSkipped = 0;

        foreach ($cashbackPlans as $plan) {
            $this->line('');
            $this->info("🔄 Processing Plan: {$plan->name} (ID: {$plan->id})");
            
            // Get users who have activated this plan
            $activeUsers = $this->getEligibleUsersForPlan($plan, $date);
            
            $this->info("👥 Found {$activeUsers->count()} users with active {$plan->name} packages");

            foreach ($activeUsers as $user) {
                $result = $this->processCashbackForUser($user, $plan, $date, $isDryRun);
                
                if ($result['processed']) {
                    $totalProcessed++;
                    $totalAmount += $result['amount'];
                    $this->line("  ✅ {$user->name}: {$result['amount']} TK");
                } else {
                    $totalSkipped++;
                    $this->line("  ⏭️  {$user->name}: {$result['reason']}");
                }
            }
        }

        $this->line('');
        $this->info('📊 Summary:');
        $this->table(['Metric', 'Value'], [
            ['Date Processed', $date->format('Y-m-d')],
            ['Users Processed', $totalProcessed],
            ['Users Skipped', $totalSkipped],
            ['Total Amount', number_format($totalAmount, 2) . ' TK'],
            ['Mode', $isDryRun ? 'DRY RUN' : 'LIVE'],
        ]);

        return 0;
    }

        /**
     * Get users eligible for cashback for a specific plan
     */
    private function getEligibleUsersForPlan($plan, $date)
    {
        return User::whereHas('activePackages', function ($query) use ($plan, $date) {
            $query->where('plan_id', $plan->id)
                  ->where('is_active', true);
        })->with('activePackages')->get();
    }

    /**
     * Process cashback for a single user
     */
    private function processCashbackForUser($user, $plan, $date, $isDryRun = false)
    {
        // Check if cashback already exists for this user/plan/date
        $existingCashback = UserDailyCashback::where('user_id', $user->id)
                                           ->where('plan_id', $plan->id)
                                           ->where('cashback_date', $date)
                                           ->first();

        if ($existingCashback) {
            return [
                'processed' => false,
                'reason' => 'Already processed',
                'amount' => 0
            ];
        }

        // Check if user meets referral conditions (but don't skip - create pending if not met)
        $userMeetsConditions = $plan->userMeetsReferralConditions($user->id);

        // Get user's active investment for this plan
        $activeInvestment = $user->activePackages()
                                ->where('plan_id', $plan->id)
                                ->where('is_active', true)
                                ->first();

        if (!$activeInvestment) {
            return [
                'processed' => false,
                'reason' => 'No active investment',
                'amount' => 0
            ];
        }

        // Check if still within cashback duration
        if (!$plan->isEligibleForCashback($activeInvestment->created_at)) {
            return [
                'processed' => false,
                'reason' => 'Duration expired',
                'amount' => 0
            ];
        }

        // Calculate cashback amount
        $cashbackAmount = $plan->calculateDailyCashback();

        if ($cashbackAmount <= 0) {
            return [
                'processed' => false,
                'reason' => 'Zero cashback amount',
                'amount' => 0
            ];
        }

        // Create cashback record if not dry run
        if (!$isDryRun) {
            // Determine cashback status based on referral conditions
            $userMeetsConditions = $plan->userMeetsReferralConditions($user->id);
            $cashbackStatus = $userMeetsConditions ? 'paid' : 'pending';
            
            // Create cashback record
            $cashback = UserDailyCashback::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'cashback_amount' => $cashbackAmount,
                'cashback_date' => $date,
                'status' => $cashbackStatus,
                'remarks' => $userMeetsConditions ? 'Auto-generated daily cashback - paid immediately' : 'Auto-generated daily cashback - pending referral conditions',
                'paid_at' => $userMeetsConditions ? now() : null,
            ]);

            // Only credit wallet and create transaction if conditions are met
            if ($userMeetsConditions) {
                // Credit user's interest wallet
                $user->increment('interest_wallet', $cashbackAmount);
                
                // Create transaction record
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'transaction_id' => 'CB' . $date->format('Ymd') . '-' . $user->id . '-' . $plan->id,
                    'type' => 'daily_cashback',
                    'amount' => $cashbackAmount,
                    'fee' => 0.00,
                    'status' => 'completed',
                    'wallet_type' => 'interest_wallet',
                    'description' => "Daily cashback from {$plan->name}",
                    'note' => "Automatic daily cashback payment for {$date->format('Y-m-d')}",
                    'reference_type' => 'user_daily_cashback',
                    'reference_id' => $cashback->id,
                    'processed_at' => now(),
                ]);
                
                // Create user notification for immediate payment
                UserNotification::create([
                    'user_id' => $user->id,
                    'title' => '💰 Daily Cashback Received',
                    'message' => "You've received ৳{$cashbackAmount} daily cashback from {$plan->name} package!",
                    'type' => 'cashback',
                    'data' => [
                        'amount' => $cashbackAmount,
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'date' => $date->format('Y-m-d'),
                        'transaction_id' => $transaction->transaction_id,
                        'wallet_type' => 'interest_wallet',
                        'status' => 'paid'
                    ],
                    'is_read' => false
                ]);
            } else {
                // Create notification for pending cashback
                UserNotification::create([
                    'user_id' => $user->id,
                    'title' => '⏳ Daily Cashback Pending',
                    'message' => "৳{$cashbackAmount} cashback is pending from {$plan->name}. Complete referral requirements to unlock all pending cashbacks!",
                    'type' => 'cashback_pending',
                    'data' => [
                        'amount' => $cashbackAmount,
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => 'pending',
                        'requirements' => $plan->getReferralConditionsDescription()
                    ],
                    'is_read' => false
                ]);
            }
        }

        return [
            'processed' => true,
            'reason' => 'Success',
            'amount' => $cashbackAmount
        ];
    }
}
