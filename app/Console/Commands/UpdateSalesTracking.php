<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\BinarySummary;
use App\Services\VolumeTrackingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateSalesTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:update-tracking 
                            {--frequency=minute : Update frequency (second/minute/hour/day)} 
                            {--user= : Update specific user only} 
                            {--real-time : Enable real-time mode}
                            {--binary-only : Update only binary volumes}
                            {--upline-cascade : Update upline binary volumes when user sales change}';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Update sales volume tracking for users at different frequencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $frequency = $this->option('frequency');
        $specificUser = $this->option('user');
        $realTime = $this->option('real-time');
        $binaryOnly = $this->option('binary-only');
        $uplineCascade = $this->option('upline-cascade');

        $this->info("=== Sales Tracking Update ({$frequency}) ===");
        $this->info('Time: ' . Carbon::now()->format('Y-m-d H:i:s'));

        // Handle binary-only updates
        if ($binaryOnly) {
            return $this->updateBinaryOnly($specificUser);
        }

        // Handle upline cascade updates
        if ($uplineCascade && $specificUser) {
            return $this->updateUplineCascade($specificUser);
        }

        // Determine what to update based on frequency
        switch ($frequency) {
            case 'second':
                return $this->updateSecondBySecond($specificUser);
            case 'minute':
                return $this->updateMinutely($specificUser);
            case 'hour':
                return $this->updateHourly($specificUser);
            case 'day':
                return $this->updateDaily($specificUser);
            default:
                $this->error('Invalid frequency. Use: second, minute, hour, or day');
                return Command::FAILURE;
        }
    }

    /**
     * Update every second (for real-time critical updates)
     */
    private function updateSecondBySecond($specificUser = null)
    {
        $this->info('Running second-by-second updates (real-time mode)...');
        
        // Only update users with recent order activity (last 5 minutes)
        $recentCutoff = Carbon::now()->subMinutes(5);
        
        $query = User::where('is_active', true)
            ->whereHas('orders', function($q) use ($recentCutoff) {
                $q->where('updated_at', '>=', $recentCutoff)
                  ->whereIn('status', ['completed', 'processing', 'pending']);
            });

        if ($specificUser) {
            $query->where('username', $specificUser);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No users with recent order activity found.');
            return Command::SUCCESS;
        }

        $this->info("Updating {$users->count()} users with recent order activity...");

        foreach ($users as $user) {
            $this->updateUserSalesRealTime($user);
        }

        $this->info('Real-time sales tracking updated successfully');
        return Command::SUCCESS;
    }

    /**
     * Update every minute (for frequent updates)
     */
    private function updateMinutely($specificUser = null)
    {
        $this->info('Running minutely updates...');
        
        // Update users with orders in the last hour
        $recentCutoff = Carbon::now()->subHour();
        
        $query = User::where('is_active', true)
            ->whereHas('orders', function($q) use ($recentCutoff) {
                $q->where('updated_at', '>=', $recentCutoff);
            });

        if ($specificUser) {
            $query->where('username', $specificUser);
        }

        $users = $query->get();
        
        $this->info("Updating {$users->count()} users with recent orders...");

        foreach ($users as $user) {
            $this->updateUserSalesVolumes($user);
        }

        // Update binary summaries for active users
        $this->updateBinarySummariesMinutely();

        $this->info('Minutely sales tracking updated successfully');
        return Command::SUCCESS;
    }

    /**
     * Update every hour (for regular updates)
     */
    private function updateHourly($specificUser = null)
    {
        $this->info('Running hourly updates...');
        
        $query = User::where('is_active', true);

        if ($specificUser) {
            $query->where('username', $specificUser);
        } else {
            // Only update users with orders in the last 24 hours for efficiency
            $query->whereHas('orders', function($q) {
                $q->where('created_at', '>=', Carbon::now()->subDay());
            });
        }

        $users = $query->get();
        
        $this->info("Updating {$users->count()} users...");

        $progressBar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $this->updateUserSalesVolumes($user);
            $this->updateUserBinaryData($user);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info('Hourly sales tracking updated successfully');
        return Command::SUCCESS;
    }

    /**
     * Update daily (comprehensive update)
     */
    private function updateDaily($specificUser = null)
    {
        $this->info('Running daily comprehensive updates...');
        
        $query = User::where('is_active', true);

        if ($specificUser) {
            $query->where('username', $specificUser);
        }

        $users = $query->get();
        
        $this->info("Updating {$users->count()} users comprehensively...");

        $progressBar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $this->updateUserSalesVolumes($user);
            $this->updateUserBinaryData($user);
            $this->updateUserDownlineData($user);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        // Clear old cache entries
        $this->clearOldCache();

        $this->info('Daily comprehensive update completed successfully');
        return Command::SUCCESS;
    }

    /**
     * Update user sales volumes in real-time
     */
    private function updateUserSalesRealTime($user)
    {
        $cacheKey = "user_sales_realtime_{$user->id}";
        
        // Check if we've updated this user in the last 30 seconds
        if (Cache::has($cacheKey)) {
            return;
        }

        // Define valid payment status for sales volume calculations - only paid orders count
        $validPaymentStatus = 'paid';
        
        // Quick update for real-time
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Count valid orders for sales volume
        $monthlyVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', $validPaymentStatus)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');

        $user->update(['monthly_sales_volume' => $monthlyVolume]);

        // Update binary volumes in real-time
        $this->updateUserBinaryRealTime($user);

        // Cache for 30 seconds to prevent excessive updates
        Cache::put($cacheKey, true, 30);
    }

    /**
     * Update user sales volumes (standard)
     */
    private function updateUserSalesVolumes($user)
    {
        // Define valid payment status for sales volume calculations - only paid orders count
        $validPaymentStatus = 'paid';
        
        // Calculate monthly sales volume (current month)
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', $validPaymentStatus)
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');

        // Calculate total sales volume (all time)
        $totalVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', $validPaymentStatus)
            ->sum('total_amount');

        // Calculate today's sales
        $todaySales = Order::where('customer_id', $user->id)
            ->where('payment_status', $validPaymentStatus)
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Update user data
        $user->update([
            'monthly_sales_volume' => $monthlyVolume,
            'total_sales_volume' => $totalVolume,
            'daily_sales_volume' => $todaySales,
        ]);
    }

    /**
     * Update user binary data
     */
    private function updateUserBinaryData($user)
    {
        // Get or create binary summary
        $binarySummary = BinarySummary::firstOrCreate(
            ['user_id' => $user->id],
            [
                'left_carry_balance' => 0,
                'right_carry_balance' => 0,
                'lifetime_left_volume' => 0,
                'lifetime_right_volume' => 0,
                'current_period_left' => 0,
                'current_period_right' => 0,
                'monthly_left_volume' => 0,
                'monthly_right_volume' => 0,
                'daily_left_volume' => 0,
                'daily_right_volume' => 0,
                'is_active' => true,
            ]
        );

        // Calculate real-time left and right volumes
        $leftVolume = $this->calculateDownlineVolume($user, 'left');
        $rightVolume = $this->calculateDownlineVolume($user, 'right');

        // Calculate daily volumes
        $dailyLeftVolume = $this->calculateDownlineVolume($user, 'left', 'today');
        $dailyRightVolume = $this->calculateDownlineVolume($user, 'right', 'today');

        // Calculate monthly volumes
        $monthlyLeftVolume = $this->calculateDownlineVolume($user, 'left', 'month');
        $monthlyRightVolume = $this->calculateDownlineVolume($user, 'right', 'month');

        // Update binary summary with real-time data
        $binarySummary->update([
            'lifetime_left_volume' => $leftVolume,
            'lifetime_right_volume' => $rightVolume,
            'daily_left_volume' => $dailyLeftVolume,
            'daily_right_volume' => $dailyRightVolume,
            'monthly_left_volume' => $monthlyLeftVolume,
            'monthly_right_volume' => $monthlyRightVolume,
            'current_period_left' => $monthlyLeftVolume,
            'current_period_right' => $monthlyRightVolume,
            'last_calculated_at' => Carbon::now(),
        ]);

        return $binarySummary;
    }

    /**
     * Update user downline data (comprehensive)
     */
    private function updateUserDownlineData($user)
    {
        // This would calculate volumes from entire downline
        // Implementation depends on your binary tree structure
        
        // For now, just ensure the data is current
        $this->updateUserBinaryData($user);
    }

    /**
     * Update binary summaries for minutely updates
     */
    private function updateBinarySummariesMinutely()
    {
        // Update only recently active binary summaries
        $recentSummaries = BinarySummary::where('last_calculated_at', '<', Carbon::now()->subMinutes(5))
            ->whereHas('user', function($q) {
                $q->where('is_active', true);
            })
            ->limit(100) // Limit for performance
            ->get();

        foreach ($recentSummaries as $summary) {
            $summary->update(['last_calculated_at' => Carbon::now()]);
        }
    }

    /**
     * Clear old cache entries
     */
    private function clearOldCache()
    {
        // Clear sales tracking cache
        Cache::forget('sales_tracking_last_run');
        
        // You can add more cache clearing logic here
        $this->info('Old cache entries cleared');
    }

    /**
     * Calculate downline volume for left or right leg
     */
    private function calculateDownlineVolume($user, $position, $period = 'all')
    {
        $cacheKey = "downline_volume_{$user->id}_{$position}_{$period}";
        
        // Use cache for better performance (cache for 1 minute)
        return Cache::remember($cacheKey, 60, function() use ($user, $position, $period) {
            return $this->getDownlineVolumeRecursive($user->id, $position, $period);
        });
    }

    /**
     * Recursively calculate downline volume
     */
    private function getDownlineVolumeRecursive($userId, $position, $period = 'all', $depth = 0, $maxDepth = 10)
    {
        // Prevent infinite recursion
        if ($depth > $maxDepth) {
            return 0;
        }

        // Get direct downlines in the specified position
        $downlines = User::where('upline_id', $userId)
            ->where('position', $position)
            ->where('is_active', true)
            ->get();

        $totalVolume = 0;

        foreach ($downlines as $downline) {
            // Calculate this user's personal sales volume
            $personalVolume = $this->getUserPersonalVolume($downline, $period);
            $totalVolume += $personalVolume;

            // Add volumes from their left and right downlines recursively
            $leftVolume = $this->getDownlineVolumeRecursive($downline->id, 'left', $period, $depth + 1, $maxDepth);
            $rightVolume = $this->getDownlineVolumeRecursive($downline->id, 'right', $period, $depth + 1, $maxDepth);
            
            $totalVolume += $leftVolume + $rightVolume;
        }

        return $totalVolume;
    }

    /**
     * Get user's personal sales volume for a specific period
     */
    private function getUserPersonalVolume($user, $period = 'all')
    {
        $query = Order::where('customer_id', $user->id)
            ->where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'all':
            default:
                // No additional filter for all-time
                break;
        }

        return $query->sum('total_amount') ?? 0;
    }

    /**
     * Update binary volumes in real-time for specific user
     */
    private function updateUserBinaryRealTime($user)
    {
        $cacheKey = "binary_realtime_{$user->id}";
        
        // Check if we've updated this user's binary data in the last 30 seconds
        if (Cache::has($cacheKey)) {
            return;
        }

        $binarySummary = BinarySummary::firstOrCreate(['user_id' => $user->id]);

        // Quick calculation for real-time (only direct downlines)
        $leftVolume = $this->calculateDirectDownlineVolume($user, 'left');
        $rightVolume = $this->calculateDirectDownlineVolume($user, 'right');

        $binarySummary->update([
            'current_period_left' => $leftVolume,
            'current_period_right' => $rightVolume,
            'last_calculated_at' => Carbon::now(),
        ]);

        // Cache for 30 seconds to prevent excessive updates
        Cache::put($cacheKey, true, 30);
    }

    /**
     * Calculate direct downline volume (non-recursive for real-time)
     */
    private function calculateDirectDownlineVolume($user, $position)
    {
        // Get direct children only for real-time updates
        $directDownlines = User::where('upline_id', $user->id)
            ->where('position', $position)
            ->where('is_active', true)
            ->pluck('id');

        if ($directDownlines->isEmpty()) {
            return 0;
        }

        // Sum their current month sales
        $currentMonth = Carbon::now()->format('Y-m');
        
        return Order::whereIn('customer_id', $directDownlines)
            ->where('status', 'completed')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount') ?? 0;
    }

    /**
     * Update only binary volumes (no personal sales)
     */
    private function updateBinaryOnly($specificUser = null)
    {
        $this->info('Running binary-only updates...');
        
        $query = User::where('is_active', true);

        if ($specificUser) {
            $query->where('username', $specificUser);
        }

        $users = $query->get();
        
        $this->info("Updating binary volumes for {$users->count()} users...");

        $progressBar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $this->updateUserBinaryData($user);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info('Binary volumes updated successfully');
        return Command::SUCCESS;
    }

    /**
     * Update upline binary volumes when a user's sales change (cascade effect)
     */
    private function updateUplineCascade($username)
    {
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            $this->error("User '{$username}' not found");
            return Command::FAILURE;
        }

        $this->info("Updating upline cascade for user: {$username}");

        // Update the user's own sales first
        $this->updateUserSalesVolumes($user);

        // Get all uplines that need to be updated
        $uplines = $this->getUplineChain($user);
        
        $this->info("Found {$uplines->count()} uplines to update");

        foreach ($uplines as $upline) {
            $this->updateUserBinaryData($upline);
            $this->info("Updated binary volumes for upline: {$upline->username}");
        }

        $this->info('Upline cascade update completed successfully');
        return Command::SUCCESS;
    }

    /**
     * Get the complete upline chain for a user
     */
    private function getUplineChain($user, $maxLevels = 20)
    {
        $uplines = collect();
        $currentUser = $user;
        $level = 0;

        while ($currentUser->upline_id && $level < $maxLevels) {
            $upline = User::find($currentUser->upline_id);
            
            if (!$upline || $uplines->contains('id', $upline->id)) {
                // Prevent infinite loops
                break;
            }

            $uplines->push($upline);
            $currentUser = $upline;
            $level++;
        }

        return $uplines;
    }

    /**
     * Real-time binary update for when orders are placed/completed
     * This should be called from Order model events
     */
    public static function triggerRealTimeBinaryUpdate($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        // Update the user and their immediate uplines
        $command = new self();
        
        // Update user's own data
        $command->updateUserSalesVolumes($user);
        $command->updateUserBinaryRealTime($user);

        // Update immediate uplines (up to 5 levels for real-time)
        $uplines = $command->getUplineChain($user, 5);
        
        foreach ($uplines as $upline) {
            $command->updateUserBinaryRealTime($upline);
        }
    }
}
