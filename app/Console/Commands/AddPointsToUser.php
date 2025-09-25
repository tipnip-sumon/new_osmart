<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AddPointsToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-points {user_id} {points} {--source=manual : Source of points}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add points to a user (will auto-trigger commission distribution)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $points = $this->argument('points');
        $source = $this->option('source');

        // Validate inputs
        if (!is_numeric($points) || $points <= 0) {
            $this->error('Points must be a positive number');
            return 1;
        }

        // Find user
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        // Show current status
        $this->info("Adding {$points} points to user: {$user->name} (ID: {$userId})");
        $this->line("Current reserve points: {$user->reserve_points}");
        
        // Add points (this will trigger the UserPointObserver)
        $oldPoints = $user->reserve_points;
        $user->increment('reserve_points', $points);
        $user->increment('total_points_earned', $points);
        
        // Log the manual addition
        Log::info('Manual points addition', [
            'user_id' => $userId,
            'user_name' => $user->name,
            'points_added' => $points,
            'old_points' => $oldPoints,
            'new_points' => $user->fresh()->reserve_points,
            'source' => $source,
            'added_by' => 'console_command'
        ]);

        $newPoints = $user->fresh()->reserve_points;
        
        $this->info("✅ Points added successfully!");
        $this->line("New reserve points: {$newPoints}");
        
        // Check if this will trigger commission distribution
        if ($oldPoints < 100 && $newPoints >= 100) {
            $this->warn("🎉 User reached 100+ points threshold! Commission distribution will be triggered automatically.");
        } elseif ($points >= 100) {
            $this->warn("🎉 Significant points added (100+)! Commission distribution will be triggered automatically.");
        } else {
            $this->line("ℹ️  Commission distribution will trigger when user reaches 100+ points total.");
        }

        return 0;
    }
}
