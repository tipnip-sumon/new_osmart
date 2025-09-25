<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'discount_amount',
        'order_amount',
        'user_ip',
        'user_agent',
        'order_details',
        'used_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discount_amount' => 'decimal:2',
        'order_amount' => 'decimal:2',
        'order_details' => 'array',
        'used_at' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByCoupon($query, $couponId)
    {
        return $query->where('coupon_id', $couponId);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('used_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('used_at', now()->month)
                    ->whereYear('used_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('used_at', now()->year);
    }
}
