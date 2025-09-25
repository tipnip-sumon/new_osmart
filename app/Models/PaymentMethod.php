<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'account_number',
        'account_name',
        'bank_name',
        'branch_name',
        'routing_number',
        'swift_code',
        'logo',
        'logo_data',
        'is_active',
        'requires_verification',
        'min_amount',
        'max_amount',
        'fee_percentage',
        'fee_fixed',
        'sort_order',
        'extra_fields',
        'instructions'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_verification' => 'boolean',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'extra_fields' => 'array',
        'logo_data' => 'array'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_method', 'code');
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('assets/images/payment-default.png');
    }

    public function calculateFee($amount)
    {
        $fixedFee = $this->fee_fixed ?? 0;
        $percentageFee = ($amount * ($this->fee_percentage ?? 0)) / 100;
        return $fixedFee + $percentageFee;
    }

    public function isAmountValid($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
