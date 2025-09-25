<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'transaction_id',
        'plan_name',
        'plan_price',
        'points_received',
        'point_value_rate',
        'payment_method',
        'wallet_balance_before',
        'wallet_balance_after',
        'sponsor_bonus_given',
        'generation_bonus_given',
        'commission_breakdown',
        'plan_features',
        'plan_description',
        'plan_category',
        'status',
        'purchased_at',
        'processed_at',
        'processing_notes',
        'is_validated',
        'validation_hash',
        'purchase_ip',
        'user_agent',
    ];

    protected $casts = [
        'commission_breakdown' => 'array',
        'plan_features' => 'array',
        'purchased_at' => 'datetime',
        'processed_at' => 'datetime',
        'is_validated' => 'boolean',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId()
    {
        do {
            $transactionId = 'PP_' . date('Ymd') . '_' . strtoupper(uniqid());
        } while (self::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }

    /**
     * Get total earnings from this purchase (sponsor + generation bonus)
     */
    public function getTotalBonusAttribute()
    {
        return $this->sponsor_bonus_given + $this->generation_bonus_given;
    }

    /**
     * Check if purchase is recent (within last 24 hours)
     */
    public function isRecent()
    {
        return $this->purchased_at >= now()->subDay();
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'fas fa-clock',
            'completed' => 'fas fa-check-circle',
            'failed' => 'fas fa-times-circle',
            'refunded' => 'fas fa-undo',
            default => 'fas fa-question-circle',
        };
    }

    /**
     * Scope for completed purchases
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for recent purchases
     */
    public function scopeRecent($query)
    {
        return $query->where('purchased_at', '>=', now()->subDay());
    }

    /**
     * Scope for user purchases
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
