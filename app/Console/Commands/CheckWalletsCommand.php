<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Commission;

class CheckWalletsCommand extends Command
{
    protected $signature = 'check:wallets {user_id?}';
    protected $description = 'Check wallet balances and commission status';

    public function handle()
    {
        $userId = $this->argument('user_id');

        $this->info('=== WALLET BALANCE CHECK ===');
        $this->newLine();

        if ($userId) {
            $this->checkUserWallet($userId);
        } else {
            // Check multiple users
            $this->checkUserWallet(1);
            $this->checkUserWallet(4);
        }

        $this->newLine();
        $this->checkCommissionTotals();

        return 0;
    }

    private function checkUserWallet($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User {$userId} not found");
            return;
        }

        $this->info("User {$userId} ({$user->name}) Balances:");
        $this->line("- Interest Wallet: ৳" . ($user->interest_wallet ?? 0));
        $this->line("- Deposit Wallet: ৳" . ($user->deposit_wallet ?? 0));
        $this->line("- Reserve Points: " . ($user->reserve_points ?? 0) . " points");
        $this->line("- Total Points Earned: " . ($user->total_points_earned ?? 0) . " points");
        $this->newLine();

        // Check commissions received by this user
        $totalReceived = Commission::where('recipient_id', $userId)->sum('commission_amount');
        $sponsorReceived = Commission::where('recipient_id', $userId)
            ->where('commission_type', 'sponsor')
            ->sum('commission_amount');
        $generationReceived = Commission::where('recipient_id', $userId)
            ->where('commission_type', 'generation')
            ->sum('commission_amount');

        $this->info("Commissions Received by User {$userId}:");
        $this->line("- Total: ৳{$totalReceived}");
        $this->line("- Sponsor: ৳{$sponsorReceived}");
        $this->line("- Generation: ৳{$generationReceived}");
        $this->newLine();
    }

    private function checkCommissionTotals()
    {
        $totalCommissions = Commission::sum('commission_amount');
        $totalCount = Commission::count();
        
        $this->info('=== COMMISSION TOTALS ===');
        $this->line("Total Commissions Paid: ৳{$totalCommissions} ({$totalCount} records)");

        $sponsorTotal = Commission::where('commission_type', 'sponsor')->sum('commission_amount');
        $sponsorCount = Commission::where('commission_type', 'sponsor')->count();
        
        $generationTotal = Commission::where('commission_type', 'generation')->sum('commission_amount');
        $generationCount = Commission::where('commission_type', 'generation')->count();

        $this->line("- Sponsor Commissions: ৳{$sponsorTotal} ({$sponsorCount} records)");
        $this->line("- Generation Commissions: ৳{$generationTotal} ({$generationCount} records)");
        $this->newLine();

        $this->info('=== RECENT TEST CALCULATION ===');
        $this->line('Latest test: 100 points × 6 taka = ৳600 value');
        $this->line('Sponsor commission: ৳600 × 20% = ৳120');
        $this->line('Generation commission: ৳600 × 20% = ৳120');
        $this->line('Expected total for this test: ৳240');
    }
}
