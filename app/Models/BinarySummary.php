<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BinarySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'left_carry_balance',
        'right_carry_balance',
        'lifetime_left_volume',
        'lifetime_right_volume',
        'lifetime_matching_bonus',
        'lifetime_slot_bonus',
        'lifetime_capped_amount',
        'current_period_left',
        'current_period_right',
        'current_period_bonus',
        'monthly_left_volume',
        'monthly_right_volume',
        'monthly_matching_bonus',
        'monthly_capped_amount',
        'weekly_left_volume',
        'weekly_right_volume',
        'weekly_matching_bonus',
        'weekly_capped_amount',
        'daily_left_volume',
        'daily_right_volume',
        'daily_matching_bonus',
        'daily_capped_amount',
        'total_matching_records',
        'total_slot_matches',
        'last_daily_reset',
        'last_weekly_reset',
        'last_monthly_reset',
        'is_active',
        'last_calculated_at',
        // Point-based fields
        'summary_type',
        'left_total_points',
        'right_total_points',
        'matched_points',
    ];

    protected $casts = [
        'left_carry_balance' => 'decimal:2',
        'right_carry_balance' => 'decimal:2',
        'lifetime_left_volume' => 'decimal:2',
        'lifetime_right_volume' => 'decimal:2',
        'lifetime_matching_bonus' => 'decimal:2',
        'lifetime_slot_bonus' => 'decimal:2',
        'lifetime_capped_amount' => 'decimal:2',
        'current_period_left' => 'decimal:2',
        'current_period_right' => 'decimal:2',
        'current_period_bonus' => 'decimal:2',
        'monthly_left_volume' => 'decimal:2',
        'monthly_right_volume' => 'decimal:2',
        'monthly_matching_bonus' => 'decimal:2',
        'monthly_capped_amount' => 'decimal:2',
        'weekly_left_volume' => 'decimal:2',
        'weekly_right_volume' => 'decimal:2',
        'weekly_matching_bonus' => 'decimal:2',
        'weekly_capped_amount' => 'decimal:2',
        'daily_left_volume' => 'decimal:2',
        'daily_right_volume' => 'decimal:2',
        'daily_matching_bonus' => 'decimal:2',
        'daily_capped_amount' => 'decimal:2',
        'last_daily_reset' => 'date',
        'last_weekly_reset' => 'date',
        'last_monthly_reset' => 'date',
        'is_active' => 'boolean',
        'last_calculated_at' => 'datetime',
        // Point-based fields
        'left_total_points' => 'decimal:2',
        'right_total_points' => 'decimal:2',
        'matched_points' => 'decimal:2',
    ];

    /**
     * Get the user that owns this binary summary
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total carry balance
     */
    public function getTotalCarryBalanceAttribute()
    {
        return $this->left_carry_balance + $this->right_carry_balance;
    }

    /**
     * Get lifetime total volume
     */
    public function getLifetimeTotalVolumeAttribute()
    {
        return $this->lifetime_left_volume + $this->lifetime_right_volume;
    }

    /**
     * Get current period total volume
     */
    public function getCurrentPeriodTotalAttribute()
    {
        return $this->current_period_left + $this->current_period_right;
    }

    /**
     * Get monthly total volume
     */
    public function getMonthlyTotalVolumeAttribute()
    {
        return $this->monthly_left_volume + $this->monthly_right_volume;
    }

    /**
     * Get weekly total volume
     */
    public function getWeeklyTotalVolumeAttribute()
    {
        return $this->weekly_left_volume + $this->weekly_right_volume;
    }

    /**
     * Get daily total volume
     */
    public function getDailyTotalVolumeAttribute()
    {
        return $this->daily_left_volume + $this->daily_right_volume;
    }

    /**
     * Get net lifetime bonus (after capping)
     */
    public function getNetLifetimeBonusAttribute()
    {
        return $this->lifetime_matching_bonus - $this->lifetime_capped_amount;
    }

    /**
     * Scope for active summaries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Reset daily totals
     */
    public function resetDaily()
    {
        $this->update([
            'daily_left_volume' => 0,
            'daily_right_volume' => 0,
            'daily_matching_bonus' => 0,
            'daily_capped_amount' => 0,
            'last_daily_reset' => now()->toDateString(),
        ]);
    }

    /**
     * Reset weekly totals
     */
    public function resetWeekly()
    {
        $this->update([
            'weekly_left_volume' => 0,
            'weekly_right_volume' => 0,
            'weekly_matching_bonus' => 0,
            'weekly_capped_amount' => 0,
            'last_weekly_reset' => now()->toDateString(),
        ]);
    }

    /**
     * Reset monthly totals
     */
    public function resetMonthly()
    {
        $this->update([
            'monthly_left_volume' => 0,
            'monthly_right_volume' => 0,
            'monthly_matching_bonus' => 0,
            'monthly_capped_amount' => 0,
            'last_monthly_reset' => now()->toDateString(),
        ]);
    }
}
