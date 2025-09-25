<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invest extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'actual_paid',
        'token_discount',
        'interest',
        'should_pay',
        'paid',
        'period',
        'hours',
        'time_name',
        'return_rec_time',
        'next_time',
        'last_time',
        'status',
        'capital_status',
        'trx',
        'wallet_type',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'actual_paid' => 'decimal:8',
        'token_discount' => 'decimal:8',
        'interest' => 'decimal:8',
        'should_pay' => 'decimal:8',
        'paid' => 'decimal:8',
        'period' => 'integer',
        'hours' => 'integer',
        'return_rec_time' => 'integer',
        'next_time' => 'datetime',
        'last_time' => 'datetime',
        'status' => 'boolean',
        'capital_status' => 'boolean',
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan associated with the investment.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope a query to only include active investments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include completed investments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('capital_status', true);
    }

    /**
     * Get the remaining amount to be paid.
     */
    public function getRemainingAmountAttribute()
    {
        return $this->should_pay - $this->paid;
    }

    /**
     * Get the profit percentage.
     */
    public function getProfitPercentageAttribute()
    {
        if ($this->amount > 0) {
            return ($this->interest / $this->amount) * 100;
        }
        return 0;
    }

    /**
     * Check if investment is completed.
     */
    public function isCompleted()
    {
        return $this->capital_status === true;
    }

    /**
     * Check if investment is active.
     */
    public function isActive()
    {
        return $this->status === true;
    }
}
