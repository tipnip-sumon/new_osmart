<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VolumeTrackingService;

class ResetProcessedVolumes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'volume:reset-processed {--type=daily : Reset type: daily, monthly, or both}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset processed volumes for new periods to allow fresh payout calculations';

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
        $type = $this->option('type');

        if (!in_array($type, ['daily', 'monthly', 'both'])) {
            $this->error('Invalid type. Use: daily, monthly, or both');
            return Command::FAILURE;
        }

        $this->info("Resetting processed volumes: {$type}");
        
        $this->volumeService->resetProcessedVolumes($type);
        
        $this->info("✅ Processed volumes reset successfully for: {$type}");
        $this->info("Users can now earn payouts on their unprocessed volumes again.");

        return Command::SUCCESS;
    }
}
