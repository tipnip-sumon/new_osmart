<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'district',
        'upazila',
        'ward',
        'charge',
        'estimated_delivery_time',
        'is_active',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'charge' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user who created this delivery charge
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this delivery charge
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope to get active delivery charges
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find delivery charge for specific location
     * Priority: Ward > Upazila > District
     */
    public static function findChargeForLocation($district, $upazila = null, $ward = null)
    {
        // First try to find exact match with ward
        if ($ward && $upazila) {
            $charge = static::active()
                ->where('district', $district)
                ->where('upazila', $upazila)
                ->where('ward', $ward)
                ->first();
            if ($charge) return $charge;
        }

        // Then try upazila level
        if ($upazila) {
            $charge = static::active()
                ->where('district', $district)
                ->where('upazila', $upazila)
                ->whereNull('ward')
                ->first();
            if ($charge) return $charge;
        }

        // Finally try district level
        $charge = static::active()
            ->where('district', $district)
            ->whereNull('upazila')
            ->whereNull('ward')
            ->first();
        if ($charge) return $charge;

        // Return default charge if nothing found
        return (object) [
            'charge' => 100.00,
            'estimated_delivery_time' => '3-5 days'
        ];
    }

    /**
     * Get formatted charge with currency
     */
    public function getFormattedChargeAttribute()
    {
        return '৳' . number_format($this->charge, 2);
    }

    /**
     * Get location display string
     */
    public function getLocationDisplayAttribute()
    {
        $location = $this->district;
        if ($this->upazila) {
            $location .= ' > ' . $this->upazila;
        }
        if ($this->ward) {
            $location .= ' > ' . $this->ward;
        }
        return $location;
    }
}
