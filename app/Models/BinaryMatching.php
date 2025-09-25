<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BinaryMatching extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_date',
        'period',
        'left_total_sale',
        'right_total_sale',
        'total_sale',
        'left_carry_forward',
        'right_carry_forward',
        'left_current_volume',
        'right_current_volume',
        'matching_volume',
        'matching_percentage',
        'matching_bonus',
        'slot_match_count',
        'slot_match_bonus',
        'left_carry_next',
        'right_carry_next',
        'daily_cap_limit',
        'weekly_cap_limit',
        'monthly_cap_limit',
        'capped_amount',
        'status',
        'is_processed',
        'processed_at',
        'transaction_ref',
        'carry_from_id',
        'calculation_details',
        'notes',
    ];

    protected $casts = [
        'match_date' => 'date',
        'left_total_sale' => 'decimal:2',
        'right_total_sale' => 'decimal:2',
        'total_sale' => 'decimal:2',
        'left_carry_forward' => 'decimal:2',
        'right_carry_forward' => 'decimal:2',
        'left_current_volume' => 'decimal:2',
        'right_current_volume' => 'decimal:2',
        'matching_volume' => 'decimal:2',
        'matching_percentage' => 'decimal:2',
        'matching_bonus' => 'decimal:2',
        'slot_match_bonus' => 'decimal:2',
        'left_carry_next' => 'decimal:2',
        'right_carry_next' => 'decimal:2',
        'daily_cap_limit' => 'decimal:2',
        'weekly_cap_limit' => 'decimal:2',
        'monthly_cap_limit' => 'decimal:2',
        'capped_amount' => 'decimal:2',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
        'calculation_details' => 'array',
    ];

    /**
     * Get the user that owns this binary matching record
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the carry forward record this is based on
     */
    public function carryFrom(): BelongsTo
    {
        return $this->belongsTo(BinaryMatching::class, 'carry_from_id');
    }

    /**
     * Get records that carry forward from this one
     */
    public function carryForwardTo()
    {
        return $this->hasMany(BinaryMatching::class, 'carry_from_id');
    }

    /**
     * Scope for getting records by period
     */
    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope for getting processed records
     */
    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    /**
     * Scope for getting pending records
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for getting records by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('match_date', [$startDate, $endDate]);
    }

    /**
     * Calculate the weaker leg volume
     */
    public function getWeakerLegVolumeAttribute()
    {
        return min($this->left_current_volume, $this->right_current_volume);
    }

    /**
     * Calculate the stronger leg volume
     */
    public function getStrongerLegVolumeAttribute()
    {
        return max($this->left_current_volume, $this->right_current_volume);
    }

    /**
     * Get the carry forward difference
     */
    public function getCarryDifferenceAttribute()
    {
        return abs($this->left_current_volume - $this->right_current_volume);
    }

    /**
     * Check if this record has been capped
     */
    public function getIsCappedAttribute()
    {
        return $this->capped_amount > 0;
    }

    /**
     * Calculate net matching bonus after capping
     */
    public function getNetMatchingBonusAttribute()
    {
        return $this->matching_bonus - $this->capped_amount;
    }
}
