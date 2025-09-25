<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class DailyLinkSharingStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stat_date',
        'shares_count',
        'clicks_count',
        'unique_clicks_count',
        'earnings_amount',
        'package_name',
        'daily_limit_used',
        'earning_limit_reached'
    ];

    protected $casts = [
        'stat_date' => 'date',
        'shares_count' => 'integer',
        'clicks_count' => 'integer',
        'unique_clicks_count' => 'integer',
        'earnings_amount' => 'decimal:2',
        'daily_limit_used' => 'boolean',
        'earning_limit_reached' => 'boolean',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create today's stats for user
     */
    public static function getTodayStats($userId, $packageName = null)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'stat_date' => Carbon::today(),
        ], [
            'shares_count' => 0,
            'clicks_count' => 0,
            'unique_clicks_count' => 0,
            'earnings_amount' => 0,
            'package_name' => $packageName, // Now allows null since column is nullable
            'daily_limit_used' => false,
            'earning_limit_reached' => false,
        ]);
    }

    /**
     * Update daily stats when link is shared
     */
    public function incrementShare()
    {
        $this->increment('shares_count');
    }

    /**
     * Update daily stats when link is clicked
     */
    public function incrementClick($isUnique = false, $earningAmount = 0)
    {
        $this->increment('clicks_count');
        
        if ($isUnique) {
            $this->increment('unique_clicks_count');
            $this->increment('earnings_amount', $earningAmount);
        }
    }

    /**
     * Check if daily earning limit is reached
     */
    public function checkEarningLimit($packageSettings)
    {
        if ($packageSettings && $packageSettings->daily_earning_limit > 0) {
            if ($this->earnings_amount >= $packageSettings->daily_earning_limit) {
                $this->update(['earning_limit_reached' => true]);
                return true;
            }
        }
        return false;
    }

    /**
     * Check if daily share limit is reached
     */
    public function checkShareLimit($packageSettings)
    {
        if ($packageSettings && $packageSettings->daily_share_limit > 0) {
            if ($this->shares_count >= $packageSettings->daily_share_limit) {
                $this->update(['daily_limit_used' => true]);
                return true;
            }
        }
        return false;
    }

    /**
     * Get monthly stats for user
     */
    public static function getMonthlyStats($userId, $month = null, $year = null)
    {
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        return self::where('user_id', $userId)
            ->whereMonth('stat_date', $month)
            ->whereYear('stat_date', $year)
            ->selectRaw('
                SUM(shares_count) as total_shares,
                SUM(clicks_count) as total_clicks,
                SUM(unique_clicks_count) as unique_clicks,
                SUM(earnings_amount) as total_earnings,
                COUNT(*) as active_days
            ')
            ->first();
    }
}
