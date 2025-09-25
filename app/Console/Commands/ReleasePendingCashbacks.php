<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserDailyCashback;
use App\Models\Transaction;
use App\Models\UserNotification;
use Carbon\Carbon;

class ReleasePendingCashbacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashback:release-pending 
                            {--user-id= : Process specific user ID}
                            {--plan-id= : Process specific plan ID}
                            {--dry-run : Preview what would be processed without actually processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release pending cashbacks for users who now meet referral conditions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $planId = $this->option('plan-id');
        $isDryRun = $this->option('dry-run');

        $this->info('🔓 Processing Pending Cashback Releases');
        
        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No actual payments will be processed');
        }

        // Get users with pending cashbacks
        $query = User::whereHas('dailyCashbacks', function ($q) use ($planId) {
            $q->where('status', 'pending');
            if ($planId) {
                $q->where('plan_id', $planId);
            }
        })->with(['dailyCashbacks' => function ($q) use ($planId) {
            $q->where('status', 'pending')
              ->with('plan');
            if ($planId) {
                $q->where('plan_id', $planId);
            }
        }]);

        if ($userId) {
            $query->where('id', $userId);
        }

        $usersWithPending = $query->get();

        if ($usersWithPending->isEmpty()) {
            $this->info('✅ No users with pending cashbacks found.');
            return 0;
        }

        $this->info("👥 Found {$usersWithPending->count()} users with pending cashbacks");

        $totalReleased = 0;
        $totalAmount = 0;
        $totalSkipped = 0;

        foreach ($usersWithPending as $user) {
            $this->line('');
            $this->info("🔄 Processing User: {$user->name} (ID: {$user->id})");
            
            // Group pending cashbacks by plan
            $pendingByPlan = $user->dailyCashbacks->groupBy('plan_id');
            
            foreach ($pendingByPlan as $planId => $pendingCashbacks) {
                $plan = $pendingCashbacks->first()->plan;
                
                $this->line("  📦 Plan: {$plan->name}");
                
                // Check if user now meets referral conditions
                if ($plan->userMeetsReferralConditions($user->id)) {
                    $result = $this->releasePendingCashbacks($user, $plan, $pendingCashbacks, $isDryRun);
                    
                    if ($result['released']) {
                        $totalReleased += $result['count'];
                        $totalAmount += $result['amount'];
                        $this->line("    ✅ Released {$result['count']} cashbacks totaling {$result['amount']} TK");
                    } else {
                        $this->line("    ❌ Failed: {$result['reason']}");
                    }
                } else {
                    $totalSkipped += $pendingCashbacks->count();
                    $this->line("    ⏭️  Still pending: Referral conditions not met ({$pendingCashbacks->count()} cashbacks)");
                }
            }
        }

        $this->line('');
        $this->info('📊 Summary:');
        $this->table(['Metric', 'Value'], [
            ['Users Processed', $usersWithPending->count()],
            ['Cashbacks Released', $totalReleased],
            ['Cashbacks Still Pending', $totalSkipped],
            ['Total Amount Released', number_format($totalAmount, 2) . ' TK'],
            ['Mode', $isDryRun ? 'DRY RUN' : 'LIVE'],
        ]);

        return 0;
    }

    /**
     * Release pending cashbacks for a user and plan
     */
    private function releasePendingCashbacks($user, $plan, $pendingCashbacks, $isDryRun = false)
    {
        $totalAmount = $pendingCashbacks->sum('cashback_amount');
        $count = $pendingCashbacks->count();

        if ($totalAmount <= 0) {
            return [
                'released' => false,
                'reason' => 'Zero total amount',
                'count' => 0,
                'amount' => 0
            ];
        }

        if (!$isDryRun) {
            // Update all pending cashbacks to paid
            UserDailyCashback::whereIn('id', $pendingCashbacks->pluck('id'))
                           ->update([
                               'status' => 'paid',
                               'paid_at' => now(),
                               'remarks' => 'Released after referral conditions met - batch payment'
                           ]);

            // Credit user's interest wallet with total amount
            $user->increment('interest_wallet', $totalAmount);

            // Create single transaction for all released cashbacks
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_id' => 'CBR' . now()->format('Ymd-His') . '-' . $user->id . '-' . $plan->id,
                'type' => 'daily_cashback',
                'amount' => $totalAmount,
                'fee' => 0.00,
                'status' => 'completed',
                'wallet_type' => 'interest_wallet',
                'description' => "Pending cashback release from {$plan->name} ({$count} days)",
                'note' => "Released {$count} pending cashbacks after referral conditions were met",
                'reference_type' => 'cashback_batch_release',
                'reference_id' => $plan->id,
                'processed_at' => now(),
            ]);

            // Create success notification
            UserNotification::create([
                'user_id' => $user->id,
                'title' => '🎉 Pending Cashbacks Released!',
                'message' => "Congratulations! ৳{$totalAmount} from {$count} pending cashbacks has been added to your wallet after meeting referral requirements!",
                'type' => 'cashback_released',
                'data' => [
                    'total_amount' => $totalAmount,
                    'count' => $count,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'transaction_id' => $transaction->transaction_id,
                    'wallet_type' => 'interest_wallet',
                    'requirements_met' => $plan->getReferralConditionsDescription()
                ],
                'is_read' => false
            ]);
        }

        return [
            'released' => true,
            'reason' => 'Success',
            'count' => $count,
            'amount' => $totalAmount
        ];
    }
}
