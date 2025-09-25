<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\VolumeTrackingService;

class EmergencyVolumeProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emergency:volume-process 
                            {action : Action to perform: recalculate, reset-processed, reset-all}
                            {--user= : Process specific user by username}
                            {--force : Force execution without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Emergency volume processing for system maintenance and corrections';

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
        $action = $this->argument('action');
        $username = $this->option('user');
        $force = $this->option('force');

        if (!in_array($action, ['recalculate', 'reset-processed', 'reset-all'])) {
            $this->error('Invalid action. Use: recalculate, reset-processed, or reset-all');
            return Command::FAILURE;
        }

        $this->warn('🚨 EMERGENCY VOLUME PROCESSING 🚨');
        $this->warn("Action: {$action}");
        $this->warn('This operation can affect user balances and payouts!');
        
        if (!$force && !$this->confirm('Are you sure you want to continue?')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        if ($username) {
            return $this->processSpecificUser($action, $username);
        } else {
            return $this->processAllUsers($action);
        }
    }

    private function processSpecificUser($action, $username)
    {
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            $this->error("User not found: {$username}");
            return Command::FAILURE;
        }

        $this->info("Processing user: {$username} (ID: {$user->id})");
        
        switch ($action) {
            case 'recalculate':
                $volumes = $this->volumeService->recalculateUserVolumes($user);
                $this->info("✅ Volumes recalculated:");
                $this->info("   Daily: ৳{$volumes['daily']}");
                $this->info("   Monthly: ৳{$volumes['monthly']}");
                $this->info("   Total: ৳{$volumes['total']}");
                break;
                
            case 'reset-processed':
                $user->update([
                    'processed_daily_volume' => 0,
                    'processed_monthly_volume' => 0,
                    'processed_total_volume' => 0,
                    'last_payout_processed_at' => null
                ]);
                $this->info("✅ Processed volumes reset for user {$username}");
                break;
                
            case 'reset-all':
                $user->update([
                    'daily_sales_volume' => 0,
                    'monthly_sales_volume' => 0,
                    'total_sales_volume' => 0,
                    'processed_daily_volume' => 0,
                    'processed_monthly_volume' => 0,
                    'processed_total_volume' => 0,
                    'last_payout_processed_at' => null,
                    'last_daily_reset_date' => null,
                    'last_monthly_reset_period' => null
                ]);
                $this->info("✅ All volumes reset for user {$username}");
                break;
        }

        return Command::SUCCESS;
    }

    private function processAllUsers($action)
    {
        $users = User::where('is_active', true)->get();
        $count = $users->count();
        
        $this->warn("This will process {$count} active users!");
        
        if (!$this->confirm('Continue with bulk processing?')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();
        
        $processed = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                switch ($action) {
                    case 'recalculate':
                        $this->volumeService->recalculateUserVolumes($user);
                        break;
                        
                    case 'reset-processed':
                        $user->update([
                            'processed_daily_volume' => 0,
                            'processed_monthly_volume' => 0,
                            'processed_total_volume' => 0,
                            'last_payout_processed_at' => null
                        ]);
                        break;
                        
                    case 'reset-all':
                        $user->update([
                            'daily_sales_volume' => 0,
                            'monthly_sales_volume' => 0,
                            'total_sales_volume' => 0,
                            'processed_daily_volume' => 0,
                            'processed_monthly_volume' => 0,
                            'processed_total_volume' => 0,
                            'last_payout_processed_at' => null,
                            'last_daily_reset_date' => null,
                            'last_monthly_reset_period' => null
                        ]);
                        break;
                }
                $processed++;
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error processing user {$user->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        
        $this->info("✅ Processing complete!");
        $this->info("   Processed: {$processed}");
        $this->info("   Errors: {$errors}");
        
        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
