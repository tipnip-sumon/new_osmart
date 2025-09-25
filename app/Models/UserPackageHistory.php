<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPackageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'action_type',
        'amount_paid',
        'points_acquired',
        'points_before',
        'points_after',
        'active_points_before',
        'active_points_after',
        'reserve_points_before',
        'reserve_points_after',
        'payout_amount',
        'purchase_source',
        'product_id',
        'order_id',
        'package_tier',
        'package_details',
        'notes',
        'is_active',
        'activated_at',
        'expires_at',
        'payout_processed_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'points_acquired' => 'integer',
        'points_before' => 'integer',
        'points_after' => 'integer',
        'active_points_before' => 'integer',
        'active_points_after' => 'integer',
        'reserve_points_before' => 'integer',
        'reserve_points_after' => 'integer',
        'package_details' => 'json',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'payout_processed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this package history
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan associated with this history
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the product associated with this history (if applicable)
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    /**
     * Get the order associated with this history (if applicable)
     */
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

    /**
     * Scope for specific action types
     */
    public function scopeActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope for active histories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific package tier
     */
    public function scopePackageTier($query, $tier)
    {
        return $query->where('package_tier', $tier);
    }

    /**
     * Get formatted amount paid
     */
    public function getFormattedAmountPaidAttribute()
    {
        return '৳' . number_format($this->amount_paid, 2);
    }

    /**
     * Get formatted payout amount
     */
    public function getFormattedPayoutAmountAttribute()
    {
        return $this->payout_amount ? '৳' . number_format($this->payout_amount, 2) : null;
    }

    /**
     * Get action type display name
     */
    public function getActionTypeDisplayAttribute()
    {
        return match($this->action_type) {
            'purchase' => 'Package Purchase',
            'upgrade' => 'Package Upgrade',
            'payout' => 'Payout Processed',
            'point_invalidation' => 'Points Invalidated',
            default => ucfirst(str_replace('_', ' ', $this->action_type))
        };
    }
}
