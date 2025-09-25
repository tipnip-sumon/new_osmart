<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\BinarySummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RealTimeBinaryService
{
    /**
     * Update binary volumes when an order status changes
     */
    public static function handleOrderStatusChange($order, $oldStatus = null)
    {
        // Only process when order becomes completed or gets cancelled from completed
        $triggerStatuses = ['completed', 'delivered'];
        $shouldProcess = in_array($order->status, $triggerStatuses) || 
                        ($oldStatus && in_array($oldStatus, $triggerStatuses));

        if (!$shouldProcess || !$order->customer_id) {
            return;
        }

        Log::info("Processing real-time binary update for order {$order->id}, customer {$order->customer_id}");

        try {
            self::updateUserAndUplines($order->customer_id);
        } catch (\Exception $e) {
            Log::error("Error in real-time binary update: " . $e->getMessage());
        }
    }

    /**
     * Update user and their upline binary volumes
     */
    public static function updateUserAndUplines($userId)
    {
        $user = User::find($userId);
        if (!$user) return;

        // Update the customer's own sales volumes
        self::updateUserSalesVolumes($user);

        // Get upline chain (limited for real-time performance)
        $uplines = self::getUplineChain($user, 10);

        // Update binary volumes for each upline
        foreach ($uplines as $upline) {
            self::updateUserBinaryVolumesRealTime($upline);
        }
    }

    /**
     * Update user's personal sales volumes
     */
    private static function updateUserSalesVolumes($user)
    {
        $cacheKey = "user_sales_update_{$user->id}";
        
        // Prevent duplicate updates within 30 seconds
        if (Cache::has($cacheKey)) {
            return;
        }

        $currentMonth = Carbon::now()->format('Y-m');
        
        // Calculate volumes
        $monthlyVolume = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');

        $todaySales = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $totalVolume = Order::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Update user
        $user->update([
            'monthly_sales_volume' => $monthlyVolume ?? 0,
            'daily_sales_volume' => $todaySales ?? 0,
            'total_sales_volume' => $totalVolume ?? 0,
        ]);

        // Cache for 30 seconds
        Cache::put($cacheKey, true, 30);
    }

    /**
     * Update binary volumes for a user in real-time
     */
    private static function updateUserBinaryVolumesRealTime($user)
    {
        $cacheKey = "binary_update_{$user->id}";
        
        // Prevent duplicate updates within 60 seconds
        if (Cache::has($cacheKey)) {
            return;
        }

        // Get or create binary summary
        $binarySummary = BinarySummary::firstOrCreate(['user_id' => $user->id]);

        // Calculate current month left and right volumes
        $leftVolume = self::calculateDownlineVolume($user, 'left', 'month');
        $rightVolume = self::calculateDownlineVolume($user, 'right', 'month');

        // Calculate today's volumes
        $dailyLeftVolume = self::calculateDownlineVolume($user, 'left', 'today');
        $dailyRightVolume = self::calculateDownlineVolume($user, 'right', 'today');

        // Update binary summary
        $binarySummary->update([
            'monthly_left_volume' => $leftVolume,
            'monthly_right_volume' => $rightVolume,
            'daily_left_volume' => $dailyLeftVolume,
            'daily_right_volume' => $dailyRightVolume,
            'current_period_left' => $leftVolume,
            'current_period_right' => $rightVolume,
            'last_calculated_at' => Carbon::now(),
        ]);

        // Cache for 60 seconds
        Cache::put($cacheKey, true, 60);
    }

    /**
     * Calculate downline volume for a specific position and period
     */
    private static function calculateDownlineVolume($user, $position, $period = 'month')
    {
        // Get direct children in the specified position
        $directChildren = User::where('upline_id', $user->id)
            ->where('position', $position)
            ->where('is_active', true)
            ->pluck('id');

        if ($directChildren->isEmpty()) {
            return 0;
        }

        // Build the query based on period
        $query = Order::whereIn('customer_id', $directChildren)
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
        }

        $directVolume = $query->sum('total_amount') ?? 0;

        // For real-time updates, also include volumes from their downlines (up to 3 levels deep)
        $downlineVolume = 0;
        foreach ($directChildren as $childId) {
            $child = User::find($childId);
            if ($child) {
                $downlineVolume += self::calculateDownlineVolumeRecursive($child, $period, 1, 3);
            }
        }

        return $directVolume + $downlineVolume;
    }

    /**
     * Recursively calculate downline volume (limited depth for performance)
     */
    private static function calculateDownlineVolumeRecursive($user, $period, $currentDepth, $maxDepth)
    {
        if ($currentDepth >= $maxDepth) {
            return 0;
        }

        $allChildren = User::where('upline_id', $user->id)
            ->where('is_active', true)
            ->pluck('id');

        if ($allChildren->isEmpty()) {
            return 0;
        }

        $query = Order::whereIn('customer_id', $allChildren)
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
        }

        $directVolume = $query->sum('total_amount') ?? 0;

        // Continue recursion
        $nestedVolume = 0;
        foreach ($allChildren as $childId) {
            $child = User::find($childId);
            if ($child) {
                $nestedVolume += self::calculateDownlineVolumeRecursive($child, $period, $currentDepth + 1, $maxDepth);
            }
        }

        return $directVolume + $nestedVolume;
    }

    /**
     * Get upline chain for a user
     */
    private static function getUplineChain($user, $maxLevels = 10)
    {
        $uplines = collect();
        $currentUser = $user;
        $level = 0;

        while ($currentUser->upline_id && $level < $maxLevels) {
            $upline = User::find($currentUser->upline_id);
            
            if (!$upline || $uplines->contains('id', $upline->id)) {
                break;
            }

            $uplines->push($upline);
            $currentUser = $upline;
            $level++;
        }

        return $uplines;
    }

    /**
     * Quick binary update for immediate UI feedback
     */
    public static function getQuickBinaryVolumes($userId)
    {
        $cacheKey = "quick_binary_{$userId}";
        
        return Cache::remember($cacheKey, 30, function() use ($userId) {
            $user = User::find($userId);
            if (!$user) return ['left' => 0, 'right' => 0];

            $leftVolume = self::calculateDownlineVolume($user, 'left', 'month');
            $rightVolume = self::calculateDownlineVolume($user, 'right', 'month');

            return [
                'left' => $leftVolume,
                'right' => $rightVolume,
                'updated_at' => Carbon::now()->toDateTimeString()
            ];
        });
    }
}
