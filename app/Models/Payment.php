<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'gateway_name',
        'gateway_config',
        'is_active',
        'is_default',
        'sort_order',
        'logo',
        'description',
        'instructions',
        'processing_fee',
        'fee_type',
        'min_amount',
        'max_amount',
        'supported_currencies',
        'test_mode',
        'credentials',
        'webhook_url',
        'success_url',
        'cancel_url',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'test_mode' => 'boolean',
        'gateway_config' => 'array',
        'credentials' => 'array',
        'supported_currencies' => 'array',
        'metadata' => 'array',
        'processing_fee' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2'
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(TransactionReceipt::class, 'payment_method', 'name');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    public function getFormattedProcessingFeeAttribute()
    {
        if ($this->fee_type === 'percentage') {
            return $this->processing_fee . '%';
        }
        return '$' . number_format($this->processing_fee, 2);
    }

    // Methods
    public static function getTypes()
    {
        return [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'digital_wallet' => 'Digital Wallet',
            'cryptocurrency' => 'Cryptocurrency',
            'cash_on_delivery' => 'Cash on Delivery'
        ];
    }

    public static function getFeeTypes()
    {
        return [
            'fixed' => 'Fixed Amount',
            'percentage' => 'Percentage'
        ];
    }

    public function calculateFee($amount)
    {
        if ($this->fee_type === 'percentage') {
            return ($amount * $this->processing_fee) / 100;
        }
        return $this->processing_fee;
    }

    public function isSupported($currency)
    {
        return in_array($currency, $this->supported_currencies ?? []);
    }

    public function withinLimits($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        return true;
    }
}
