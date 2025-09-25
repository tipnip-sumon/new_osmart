<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class MiniVendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'affiliate_id', 
        'district',
        'status',
        'commission_rate',
        'total_earned_commission',
        'assigned_at',
        'last_transfer_at',
        'notes'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earned_commission' => 'decimal:2',
        'assigned_at' => 'datetime',
        'last_transfer_at' => 'datetime'
    ];

    /**
     * Get the main vendor who assigned this mini vendor
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the affiliate user who is the mini vendor
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    /**
     * Check if mini vendor is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Calculate commission for a given amount
     */
    public function calculateCommission(float $amount): float
    {
        if ($amount >= 100) {
            return $amount * ($this->commission_rate / 100);
        }
        return 0;
    }

    /**
     * Calculate total transfer amount with commission
     */
    public function calculateTotalWithCommission(float $amount): float
    {
        return $amount + $this->calculateCommission($amount);
    }

    /**
     * Add earned commission to total
     */
    public function addEarnedCommission(float $commission): void
    {
        $this->increment('total_earned_commission', $commission);
        $this->update(['last_transfer_at' => now()]);
    }

    /**
     * Check if vendor and affiliate are from same district
     */
    public function validateSameDistrict(): bool
    {
        return $this->vendor->district === $this->affiliate->district;
    }

    /**
     * Scope for active mini vendors
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for specific vendor
     */
    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope for specific district
     */
    public function scopeInDistrict($query, $district)
    {
        return $query->where('district', $district);
    }
}
