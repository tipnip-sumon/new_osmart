<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VolumeTrackingService;

class DailyMaintenanceProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:daily-maintenance {--force : Force run even if already run today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run complete daily maintenance: reset volumes, update qualifications, process bonuses';

    protected VolumeTrackingService $volumeService;

    public function __construct(VolumeTrackingService $volumeService)
    {
        parent::__construct();
        $this->volumeService = $volumeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Daily Maintenance Process...');
        $this->info('Date: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        $startTime = microtime(true);
        $errors = [];

        // Step 1: Reset Daily Processed Volumes
        $this->info('📊 Step 1: Resetting daily processed volumes...');
        try {
            $this->volumeService->resetProcessedVolumes('daily');
            $this->info('✅ Daily processed volumes reset successfully');
        } catch (\Exception $e) {
            $error = "❌ Failed to reset daily volumes: " . $e->getMessage();
            $this->error($error);
            $errors[] = $error;
        }

        // Step 2: Update Sales Tracking
        $this->info('📈 Step 2: Updating sales tracking...');
        try {
            $this->call('sales:update-tracking', ['--frequency' => 'day']);
            $this->info('✅ Sales tracking updated successfully');
        } catch (\Exception $e) {
            $error = "❌ Failed to update sales tracking: " . $e->getMessage();
            $this->error($error);
            $errors[] = $error;
        }

        // Step 3: Run Daily Matching Process
        $this->info('💰 Step 3: Running daily matching process...');
        try {
            $this->call('matching:daily-process');
            $this->info('✅ Daily matching process completed successfully');
        } catch (\Exception $e) {
            $error = "❌ Failed to run matching process: " . $e->getMessage();
            $this->error($error);
            $errors[] = $error;
        }

        // Step 4: Check if it's the start of a new month
        if (now()->day === 1 || $this->option('force')) {
            $this->info('📅 Step 4: New month detected - Running monthly maintenance...');
            try {
                $this->volumeService->resetProcessedVolumes('monthly');
                $this->call('matching:reset-monthly');
                $this->info('✅ Monthly maintenance completed successfully');
            } catch (\Exception $e) {
                $error = "❌ Failed to run monthly maintenance: " . $e->getMessage();
                $this->error($error);
                $errors[] = $error;
            }
        } else {
            $this->info('📅 Step 4: Skipping monthly maintenance (not start of month)');
        }

        // Summary
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $this->newLine();
        $this->info('🏁 Daily Maintenance Process Complete');
        $this->info("⏱️  Duration: {$duration} seconds");
        
        if (empty($errors)) {
            $this->info('✅ All processes completed successfully!');
            return Command::SUCCESS;
        } else {
            $this->error('❌ Some processes failed:');
            foreach ($errors as $error) {
                $this->error("   {$error}");
            }
            return Command::FAILURE;
        }
    }
}
