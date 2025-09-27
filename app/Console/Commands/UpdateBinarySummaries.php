<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\BinarySummary;
use App\Services\MatchingService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateBinarySummaries extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'matching:update-summaries 
                           {--comprehensive : Update all users instead of just active ones}
                           {--user-id=* : Update specific user IDs only}
                           {--force : Force update even if recently updated}';

    /**
     * The console command description.
     */
    protected $description = 'Update binary summaries with current point calculations';

    protected $matchingService;

    public function __construct()
    {
        parent::__construct();
        $this->matchingService = new MatchingService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $this->info("=== Binary Summaries Update Process ===");
        $this->info("Started at: " . now()->format('Y-m-d H:i:s'));

        try {
            $users = $this->getUsersToUpdate();
            
            if ($users->isEmpty()) {
                $this->info("No users need updating at this time.");
                return 0;
            }

            $this->info("Updating binary summaries for {$users->count()} users...");
            
            $updated = 0;
            $errors = 0;
            
            foreach ($users as $user) {
                try {
                    $this->updateUserBinarySummary($user);
                    $updated++;
                    
                    if ($updated % 10 == 0) {
                        $this->info("Processed {$updated} users...");
                    }
                    
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("Error updating user {$user->id}: " . $e->getMessage());
                    Log::error("Binary summary update error", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            $duration = round(microtime(true) - $startTime, 2);
            
            $this->info("\n=== Update Complete ===");
            $this->info("Users processed: {$updated}");
            $this->info("Errors: {$errors}");
            $this->info("Duration: {$duration} seconds");
            
            Log::info("Binary summaries update completed", [
                'users_processed' => $updated,
                'errors' => $errors,
                'duration' => $duration
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Fatal error in binary summaries update: " . $e->getMessage());
            Log::error("Fatal binary summaries update error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get users that need binary summary updates
     */
    private function getUsersToUpdate()
    {
        // If specific user IDs provided
        if ($this->option('user-id')) {
            return User::whereIn('id', $this->option('user-id'))
                      ->whereNotNull('upline_id')
                      ->get();
        }

        $query = User::whereNotNull('upline_id');

        if ($this->option('comprehensive')) {
            // Comprehensive mode: update all users with binary positions
            $this->info("Comprehensive mode: updating all users with binary positions");
            return $query->get();
        }

        // Default mode: only update users who need it
        $query->where(function($q) {
            // Users with recent point changes (last 2 hours)
            $q->where('updated_at', '>=', now()->subHours(2))
              // OR users whose binary summary is outdated (last 6 hours)
              ->orWhereDoesntHave('binarySummary')
              ->orWhereHas('binarySummary', function($subq) {
                  if ($this->option('force')) {
                      // Force mode: update all
                      $subq->where('id', '>', 0);
                  } else {
                      // Only update if summary is older than 6 hours
                      $subq->where('last_calculated_at', '<', now()->subHours(6));
                  }
              });
        });

        return $query->limit(100)->get(); // Limit to prevent overload
    }

    /**
     * Update binary summary for a specific user
     */
    private function updateUserBinarySummary(User $user)
    {
        // Calculate current leg points
        $legPoints = $this->matchingService->calculateLegPoints($user);
        
        // Get or create binary summary
        $binarySummary = BinarySummary::firstOrCreate(
            ['user_id' => $user->id],
            [
                'left_carry_balance' => 0,
                'right_carry_balance' => 0,
                'lifetime_left_volume' => 0,
                'lifetime_right_volume' => 0,
                'lifetime_matching_bonus' => 0,
                'is_active' => true,
            ]
        );
        
        // Update with current point calculations
        $binarySummary->update([
            'summary_type' => 'points',
            'left_total_points' => $legPoints['left'],
            'right_total_points' => $legPoints['right'],
            'matched_points' => min($legPoints['left'], $legPoints['right']),
            'last_calculated_at' => now(),
            // Also update volume fields for backward compatibility
            'lifetime_left_volume' => $legPoints['left'],
            'lifetime_right_volume' => $legPoints['right'],
        ]);

        // Log if there were significant changes
        $previousLeft = $binarySummary->getOriginal('left_total_points') ?? 0;
        $previousRight = $binarySummary->getOriginal('right_total_points') ?? 0;
        
        if (abs($legPoints['left'] - $previousLeft) > 10 || abs($legPoints['right'] - $previousRight) > 10) {
            Log::info("Significant binary summary update", [
                'user_id' => $user->id,
                'username' => $user->username,
                'left_change' => $legPoints['left'] - $previousLeft,
                'right_change' => $legPoints['right'] - $previousRight,
                'new_left' => $legPoints['left'],
                'new_right' => $legPoints['right']
            ]);
        }
    }
}