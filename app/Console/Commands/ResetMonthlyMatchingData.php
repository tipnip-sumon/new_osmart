<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\BinarySummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetMonthlyMatchingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matching:reset-monthly {--dry-run : Show what would be reset without executing} {--force : Force reset without confirmation}';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Reset monthly matching data for new matching period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('=== Monthly Matching Data Reset ===');
        $this->info('Date: ' . Carbon::now()->format('Y-m-d H:i:s'));
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No actual reset will occur');
        }

        if (!$force && !$isDryRun) {
            if (!$this->confirm('This will reset monthly sales volumes for all users. Are you sure?')) {
                $this->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }

        // Reset monthly sales volumes
        $usersToReset = User::where('is_active', true)
            ->where('monthly_sales_volume', '>', 0)
            ->count();

        $this->info("Users with monthly data to reset: {$usersToReset}");

        if (!$isDryRun) {
            DB::transaction(function () {
                // Archive current month data (optional - create a monthly summary table if needed)
                
                // Reset monthly sales volumes
                User::where('is_active', true)->update([
                    'monthly_sales_volume' => 0
                ]);

                // Reset binary summaries monthly data if needed
                BinarySummary::whereNotNull('id')->update([
                    'monthly_matched_volume' => 0,
                    'monthly_bonus_paid' => 0
                ]);

                Log::info('Monthly matching data reset completed', [
                    'date' => Carbon::now(),
                    'users_reset' => User::where('is_active', true)->count()
                ]);
            });
        }

        $this->info('=== Reset Summary ===');
        $this->info("Monthly sales volumes reset for {$usersToReset} users");
        $this->info("Binary summary monthly data reset");

        if ($isDryRun) {
            $this->warn('This was a dry run - no actual reset occurred');
        } else {
            $this->info('Monthly reset completed successfully');
        }

        return Command::SUCCESS;
    }
}
