<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserActivePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'package_tier',
        'amount_invested',
        'points_allocated',
        'points_remaining',
        'points_used_for_payout',
        'total_payout_received',
        'is_active',
        'activated_at',
        'last_payout_at',
        'next_payout_eligible_at',
        'package_details',
        'notes',
        'product_id',
        'order_id',
        'payout_count'
    ];

    protected $casts = [
        'amount_invested' => 'decimal:2',
        'total_payout_received' => 'decimal:2',
        'points_allocated' => 'integer',
        'points_remaining' => 'integer',
        'points_used_for_payout' => 'integer',
        'payout_count' => 'integer',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'last_payout_at' => 'datetime',
        'next_payout_eligible_at' => 'datetime',
        'package_details' => 'json',
    ];

    /**
     * Get the user that owns this package
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan associated with this package
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope for active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific package tier
     */
    public function scopeTier($query, $tier)
    {
        return $query->where('package_tier', $tier);
    }

    /**
     * Scope for packages eligible for payout
     */
    public function scopeEligibleForPayout($query)
    {
        return $query->where('is_active', true)
            ->where('points_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('next_payout_eligible_at')
                  ->orWhere('next_payout_eligible_at', '<=', now());
            });
    }

    /**
     * Get formatted amount invested
     */
    public function getFormattedAmountInvestedAttribute()
    {
        return '৳' . number_format($this->amount_invested, 2);
    }

    /**
     * Get formatted total payout received
     */
    public function getFormattedTotalPayoutAttribute()
    {
        return '৳' . number_format($this->total_payout_received, 2);
    }

    /**
     * Get package completion percentage
     */
    public function getCompletionPercentageAttribute()
    {
        if ($this->points_allocated == 0) return 0;
        return round((($this->points_allocated - $this->points_remaining) / $this->points_allocated) * 100, 2);
    }

    /**
     * Check if package is eligible for payout
     */
    public function isEligibleForPayout()
    {
        return $this->is_active 
            && $this->points_remaining > 0
            && ($this->next_payout_eligible_at === null || $this->next_payout_eligible_at <= now());
    }

    /**
     * Process payout for this package
     */
    public function processPayout($payoutAmount, $pointsUsed)
    {
        $this->update([
            'points_remaining' => max(0, $this->points_remaining - $pointsUsed),
            'points_used_for_payout' => $this->points_used_for_payout + $pointsUsed,
            'total_payout_received' => $this->total_payout_received + $payoutAmount,
            'last_payout_at' => now(),
            'next_payout_eligible_at' => $this->calculateNextPayoutDate(),
        ]);

        // Mark as inactive if no points remaining
        if ($this->points_remaining <= 0) {
            $this->update(['is_active' => false]);
        }
    }

    /**
     * Calculate next payout date based on plan
     */
    protected function calculateNextPayoutDate()
    {
        $days = $this->plan->time ?? 30;
        return now()->addDays($days);
    }
}
