<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referred_user_id',
        'order_id',
        'product_id',
        'commission_type',
        'level',
        'order_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'notes',
        'earned_at',
        'approved_at',
        'paid_at',
        'approved_by'
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_rate' => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'earned_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // Commission types
    const TYPES = [
        'sponsor' => 'Sponsor Commission',
        'generation' => 'Generation Commission',
        'binary' => 'Binary Commission',
        'matching' => 'Matching Bonus',
        'referral' => 'Referral Commission',
        'affiliate' => 'Affiliate Commission',
        'sales' => 'Sales Commission',
        'bonus' => 'Bonus Commission',
        'tier_bonus' => 'Tier Bonus',
        'performance' => 'Performance Bonus',
        'monthly_bonus' => 'Monthly Bonus'
    ];

    // Commission statuses
    const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getCommissionTypeNameAttribute()
    {
        return self::TYPES[$this->commission_type] ?? $this->commission_type;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getCommissionRatePercentAttribute()
    {
        return ($this->commission_rate * 100) . '%';
    }

    public function getFormattedCommissionAmountAttribute()
    {
        return number_format($this->commission_amount, 2);
    }

    public function getFormattedOrderAmountAttribute()
    {
        return number_format($this->order_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'info',
            'paid' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'referral' => 'ti-users',
            'sales' => 'ti-shopping-cart',
            'bonus' => 'ti-gift',
            'tier_bonus' => 'ti-award',
            'performance' => 'ti-trending-up',
            'monthly_bonus' => 'ti-calendar'
        ];

        return $icons[$this->commission_type] ?? 'ti-dollar-sign';
    }

    public function getDescriptionAttribute()
    {
        if ($this->notes) {
            return $this->notes;
        }
        
        // Generate default description based on commission type
        switch ($this->commission_type) {
            case 'sponsor':
                return 'Direct referral commission';
            case 'generation':
                return 'Level commission bonus';
            case 'bonus':
                return 'Club bonus commission';
            case 'monthly_bonus':
                return 'Daily pool bonus';
            case 'tier_bonus':
                return 'Rank achievement bonus';
            default:
                return ucfirst(str_replace('_', ' ', $this->commission_type)) . ' commission';
        }
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('commission_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('earned_at', now()->month)
                    ->whereYear('earned_at', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('earned_at', now()->subMonth()->month)
                    ->whereYear('earned_at', now()->subMonth()->year);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('earned_at', '>=', now()->subDays($days));
    }

    public function scopeReferralCommissions($query)
    {
        return $query->where('commission_type', 'referral');
    }

    public function scopeSalesCommissions($query)
    {
        return $query->where('commission_type', 'sales');
    }

    // Methods
    public function approve($adminId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $adminId;
        $this->approved_at = now();
        $this->save();

        // Add to user's pending balance
        $this->user->addEarnings($this->commission_amount, 'commission', 
            "Commission approved for {$this->commission_type_name}");

        return $this;
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();

        // Move from pending to available balance
        $this->user->confirmEarnings($this->commission_amount);

        // Create transaction record
        Transaction::create([
            'user_id' => $this->user_id,
            'type' => 'commission',
            'amount' => $this->commission_amount,
            'status' => 'completed',
            'description' => "Commission payment for {$this->commission_type_name}",
            'reference_type' => 'commission',
            'reference_id' => $this->id,
            'processed_at' => now()
        ]);

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        $this->notes = $this->notes ? $this->notes . "\nCancelled: " . $reason : "Cancelled: " . $reason;
        $this->save();

        return $this;
    }

    public static function calculateCommission($orderAmount, $commissionRate, $level = 1)
    {
        // Apply level-based reduction
        $levelMultiplier = match($level) {
            1 => 1.0,      // 100% of rate
            2 => 0.5,      // 50% of rate
            3 => 0.25,     // 25% of rate
            default => 0.1  // 10% of rate for levels 4+
        };

        $adjustedRate = $commissionRate * $levelMultiplier;
        return $orderAmount * $adjustedRate;
    }

    public static function createReferralCommissions($order)
    {
        $customer = $order->customer;
        $currentLevel = 1;
        $currentSponsor = $customer->sponsor;
        $maxLevels = 5; // Maximum referral levels

        while ($currentSponsor && $currentLevel <= $maxLevels) {
            $commissionAmount = self::calculateCommission(
                $order->total_amount,
                $currentSponsor->commission_rate,
                $currentLevel
            );

            if ($commissionAmount > 0) {
                self::create([
                    'user_id' => $currentSponsor->id,
                    'referred_user_id' => $customer->id,
                    'order_id' => $order->id,
                    'commission_type' => 'referral',
                    'level' => $currentLevel,
                    'order_amount' => $order->total_amount,
                    'commission_rate' => $currentSponsor->commission_rate,
                    'commission_amount' => $commissionAmount,
                    'status' => 'pending',
                    'earned_at' => now()
                ]);
            }

            $currentSponsor = $currentSponsor->sponsor;
            $currentLevel++;
        }
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commission) {
            $commission->earned_at = $commission->earned_at ?? now();
        });
    }
}
