<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DailyPointDistributionService;
use App\Models\User;
use App\Models\Commission;
use App\Models\CommissionSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessCommissionDistribution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:distribute 
                           {--user-id= : Specific user ID to process}
                           {--points= : Points amount to distribute (default: 100)}
                           {--test : Test mode - only show what would happen}
                           {--check : Check current commission data}
                           {--force : Force distribution even if already processed today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually trigger commission distribution for users with 100+ points';

    protected $distributionService;

    public function __construct(DailyPointDistributionService $distributionService)
    {
        parent::__construct();
        $this->distributionService = $distributionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('check')) {
            return $this->checkCommissionData();
        }

        $userId = $this->option('user-id');
        $points = $this->option('points') ?? 100;
        $testMode = $this->option('test');

        if ($userId) {
            return $this->processSpecificUser($userId, $points, $testMode);
        }

        return $this->processEligibleUsers($points, $testMode);
    }

    /**
     * Check current commission data
     */
    private function checkCommissionData()
    {
        $this->info('=== COMMISSION DATA CHECK ===');

        // Check commission settings
        $this->info("\n--- Commission Settings ---");
        $settings = CommissionSetting::where('is_active', true)
            ->whereIn('type', ['sponsor', 'generation'])
            ->get();

        if ($settings->isEmpty()) {
            $this->error('No active commission settings found!');
            return 1;
        }

        foreach ($settings as $setting) {
            $this->line("ID: {$setting->id} | Type: {$setting->type} | Value: {$setting->value}% | Max Levels: {$setting->max_levels}");
        }

        // Check existing commissions
        $this->info("\n--- Commission Records ---");
        $totalCommissions = Commission::count();
        $this->line("Total Commissions: {$totalCommissions}");

        if ($totalCommissions > 0) {
            $recentCommissions = Commission::latest()
                ->take(5)
                ->with(['user:id,name', 'referredUser:id,name'])
                ->get();

            $this->table(
                ['ID', 'Type', 'Receiver', 'From User', 'Level', 'Amount', 'Status', 'Created'],
                $recentCommissions->map(function ($c) {
                    return [
                        $c->id,
                        $c->commission_type,
                        $c->user->name ?? "ID: {$c->user_id}",
                        $c->referredUser->name ?? "ID: {$c->referred_user_id}",
                        $c->level,
                        "৳{$c->commission_amount}",
                        $c->status,
                        $c->created_at->format('Y-m-d H:i')
                    ];
                })->toArray()
            );
        }

        // Check users with 100+ points
        $this->info("\n--- Users with 100+ Points ---");
        $eligibleUsers = User::where('reserve_points', '>=', 100)
            ->orWhere('total_points_earned', '>=', 100)
            ->select('id', 'name', 'reserve_points', 'total_points_earned', 'is_active')
            ->get();

        if ($eligibleUsers->isEmpty()) {
            $this->warn('No users with 100+ points found');
        } else {
            $this->table(
                ['ID', 'Name', 'Reserve Points', 'Total Points', 'Active'],
                $eligibleUsers->map(function ($u) {
                    return [
                        $u->id,
                        $u->name,
                        $u->reserve_points,
                        $u->total_points_earned,
                        $u->is_active ? 'Yes' : 'No'
                    ];
                })->toArray()
            );
        }

        return 0;
    }

    /**
     * Process specific user
     */
    private function processSpecificUser($userId, $points, $testMode = false)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }

        $this->info("Processing user: {$user->name} (ID: {$userId})");
        $this->line("Points to distribute: {$points}");

        if ($testMode) {
            $this->warn("TEST MODE - No actual processing will occur");
            
            // Show what would happen
            $sponsor = $user->sponsor;
            if ($sponsor) {
                $this->line("Sponsor found: {$sponsor->name} (ID: {$sponsor->id})");
                
                $sponsorSetting = CommissionSetting::where('type', 'sponsor')
                    ->where('is_active', true)
                    ->first();
                
                if ($sponsorSetting) {
                    $sponsorBonus = ($points * $sponsorSetting->value) / 100;
                    $this->line("Sponsor would get: {$sponsorBonus} points ({$sponsorSetting->value}%)");
                }
            } else {
                $this->warn("No sponsor found for this user");
            }

            // Check generation chain
            $this->info("Generation chain would be processed...");
            $currentUser = $user->sponsor;
            $level = 1;
            
            while ($currentUser && $level <= 5) { // Show first 5 levels
                $this->line("Level {$level}: {$currentUser->name} (ID: {$currentUser->id})");
                $currentUser = $currentUser->sponsor;
                $level++;
            }

            return 0;
        }

        // Actual processing
        try {
            $forceMode = $this->option('force');
            
            if ($forceMode) {
                // Delete existing distribution for today to allow re-processing
                \App\Models\DailyPointDistribution::where('user_id', $user->id)
                    ->whereDate('distribution_date', today())
                    ->delete();
                
                $this->warn("⚠️ Force mode: Deleted existing distribution for today");
            }

            $this->distributionService->processPointAcquisition(
                $user,
                $points,
                'direct_purchase', // Use valid enum value
                null,
                'Manual command distribution'
            );

            $this->info("✅ Commission distribution completed successfully!");
            
            // Show results
            $recentCommissions = Commission::where('referred_user_id', $userId)
                ->latest()
                ->take(10)
                ->with('user:id,name')
                ->get();

            if ($recentCommissions->isNotEmpty()) {
                $this->table(
                    ['Type', 'Receiver', 'Level', 'Amount', 'Status'],
                    $recentCommissions->map(function ($c) {
                        return [
                            $c->commission_type,
                            $c->user->name ?? "ID: {$c->user_id}",
                            $c->level,
                            "৳{$c->commission_amount}",
                            $c->status
                        ];
                    })->toArray()
                );
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Process all eligible users
     */
    private function processEligibleUsers($points, $testMode = false)
    {
        $eligibleUsers = User::where('reserve_points', '>=', 100)
            ->where('is_active', true)
            ->get();

        if ($eligibleUsers->isEmpty()) {
            $this->info('No eligible users found with 100+ points');
            return 0;
        }

        $this->info("Found {$eligibleUsers->count()} eligible users");

        if ($testMode) {
            $this->warn("TEST MODE - No actual processing will occur");
            
            foreach ($eligibleUsers as $user) {
                $this->line("Would process: {$user->name} (ID: {$user->id}) - {$user->reserve_points} points");
            }
            
            return 0;
        }

        $processed = 0;
        $errors = 0;

        foreach ($eligibleUsers as $user) {
            try {
                $this->line("Processing: {$user->name} (ID: {$user->id})...");
                
                $this->distributionService->processPointAcquisition(
                    $user,
                    $points,
                    'direct_purchase', // Use valid enum value
                    null,
                    'Bulk command distribution'
                );

                $processed++;
                $this->info("✅ Completed");

            } catch (\Exception $e) {
                $errors++;
                $this->error("❌ Error for user {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("\n=== SUMMARY ===");
        $this->line("Processed: {$processed}");
        $this->line("Errors: {$errors}");

        return $errors > 0 ? 1 : 0;
    }
}
