<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VolumeTrackingService
{
    /**
     * Update user volumes when new purchase is made
     * This counts ALL paid purchases towards volumes initially
     */
    public function updateUserVolumesOnPurchase(User $user, $orderAmount)
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->format('Y-m');
        
        DB::transaction(function () use ($user, $orderAmount, $today, $currentMonth) {
            // Reset daily volume if it's a new day
            if ($user->last_daily_reset_date != $today->toDateString()) {
                $user->update([
                    'daily_sales_volume' => $orderAmount,
                    'processed_daily_volume' => 0,
                    'last_daily_reset_date' => $today->toDateString()
                ]);
            } else {
                // Add to existing daily volume
                $user->increment('daily_sales_volume', $orderAmount);
            }
            
            // Reset monthly volume if it's a new month
            if ($user->last_monthly_reset_period != $currentMonth) {
                $user->update([
                    'monthly_sales_volume' => $orderAmount,
                    'processed_monthly_volume' => 0,
                    'last_monthly_reset_period' => $currentMonth
                ]);
            } else {
                // Add to existing monthly volume
                $user->increment('monthly_sales_volume', $orderAmount);
            }
            
            // Always add to total volume
            $user->increment('total_sales_volume', $orderAmount);
        });
        
        Log::info("Volume updated for user {$user->id}: +{$orderAmount}");
    }
    
    /**
     * Get unprocessed volumes for payout calculations
     * Returns only the volume that hasn't been used for payouts yet
     */
    public function getUnprocessedVolumes(User $user)
    {
        // Refresh user data to get latest volumes
        $user->refresh();
        
        return [
            'daily_unprocessed' => max(0, $user->daily_sales_volume - $user->processed_daily_volume),
            'monthly_unprocessed' => max(0, $user->monthly_sales_volume - $user->processed_monthly_volume),
            'total_unprocessed' => max(0, $user->total_sales_volume - $user->processed_total_volume),
        ];
    }
    
    /**
     * Mark volumes as processed after payout calculations
     * This prevents double-counting in future payouts
     */
    public function markVolumesAsProcessed(User $user, $dailyAmount = null, $monthlyAmount = null, $totalAmount = null)
    {
        $updates = ['last_payout_processed_at' => now()];
        
        if ($dailyAmount !== null) {
            $updates['processed_daily_volume'] = DB::raw("processed_daily_volume + {$dailyAmount}");
        }
        
        if ($monthlyAmount !== null) {
            $updates['processed_monthly_volume'] = DB::raw("processed_monthly_volume + {$monthlyAmount}");
        }
        
        if ($totalAmount !== null) {
            $updates['processed_total_volume'] = DB::raw("processed_total_volume + {$totalAmount}");
        }
        
        $user->update($updates);
        
        Log::info("Marked volumes as processed for user {$user->id}", [
            'daily' => $dailyAmount,
            'monthly' => $monthlyAmount,
            'total' => $totalAmount
        ]);
    }
    
    /**
     * Recalculate all user volumes from orders (for maintenance/correction)
     * Only counts orders with payment_status = 'paid'
     */
    public function recalculateUserVolumes(User $user)
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Calculate daily volume (today only)
        $dailyVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('total_amount');
            
        // Calculate monthly volume (current month)
        $monthlyVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
            ->sum('total_amount');
            
        // Calculate total volume (all time)
        $totalVolume = Order::where('customer_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        
        // Update user volumes
        $user->update([
            'daily_sales_volume' => $dailyVolume,
            'monthly_sales_volume' => $monthlyVolume,
            'total_sales_volume' => $totalVolume,
            'last_daily_reset_date' => $today->toDateString(),
            'last_monthly_reset_period' => $currentMonth
        ]);
        
        Log::info("Recalculated volumes for user {$user->id}", [
            'daily' => $dailyVolume,
            'monthly' => $monthlyVolume,
            'total' => $totalVolume
        ]);
        
        return [
            'daily' => $dailyVolume,
            'monthly' => $monthlyVolume,
            'total' => $totalVolume
        ];
    }
    
    /**
     * Reset processed volumes for a new period
     * This should be called at the start of each new day/month
     */
    public function resetProcessedVolumes($resetType = 'daily')
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->format('Y-m');
        
        switch ($resetType) {
            case 'daily':
                // Reset daily processed volumes for all users
                User::whereDate('last_daily_reset_date', '<', $today)
                    ->update([
                        'processed_daily_volume' => 0,
                        'last_daily_reset_date' => $today->toDateString()
                    ]);
                break;
                
            case 'monthly':
                // Reset monthly processed volumes for all users
                User::where(function($query) use ($currentMonth) {
                    $query->whereNull('last_monthly_reset_period')
                          ->orWhere('last_monthly_reset_period', '!=', $currentMonth);
                })->update([
                    'processed_monthly_volume' => 0,
                    'last_monthly_reset_period' => $currentMonth
                ]);
                break;
                
            case 'both':
                $this->resetProcessedVolumes('daily');
                $this->resetProcessedVolumes('monthly');
                break;
        }
        
        Log::info("Reset processed volumes: {$resetType}");
    }
    
    /**
     * Get volume summary for a user
     */
    public function getVolumeSummary(User $user)
    {
        $unprocessed = $this->getUnprocessedVolumes($user);
        
        return [
            'daily' => [
                'total' => $user->daily_sales_volume,
                'processed' => $user->processed_daily_volume,
                'unprocessed' => $unprocessed['daily_unprocessed'],
                'available_for_payout' => $unprocessed['daily_unprocessed']
            ],
            'monthly' => [
                'total' => $user->monthly_sales_volume,
                'processed' => $user->processed_monthly_volume,
                'unprocessed' => $unprocessed['monthly_unprocessed'],
                'available_for_payout' => $unprocessed['monthly_unprocessed']
            ],
            'total' => [
                'total' => $user->total_sales_volume,
                'processed' => $user->processed_total_volume,
                'unprocessed' => $unprocessed['total_unprocessed'],
                'available_for_payout' => $unprocessed['total_unprocessed']
            ],
            'last_processed_at' => $user->last_payout_processed_at
        ];
    }
}
