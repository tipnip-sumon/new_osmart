<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'gateway_name',
        'gateway_config',
        'processing_time',
        'min_amount',
        'max_amount',
        'fixed_charge',
        'percentage_charge',
        'currency',
        'supported_currencies',
        'is_active',
        'is_instant',
        'requires_verification',
        'auto_approval',
        'instructions',
        'required_fields',
        'test_mode',
        'credentials',
        'webhook_url',
        'logo',
        'sort_order',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_instant' => 'boolean',
        'requires_verification' => 'boolean',
        'auto_approval' => 'boolean',
        'test_mode' => 'boolean',
        'gateway_config' => 'array',
        'supported_currencies' => 'array',
        'required_fields' => 'array',
        'credentials' => 'array',
        'metadata' => 'array',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fixed_charge' => 'decimal:2',
        'percentage_charge' => 'decimal:2'
    ];

    // Relationships
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'method_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInstant($query)
    {
        return $query->where('is_instant', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency)
                    ->orWhereJsonContains('supported_currencies', $currency);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getFormattedMinAmountAttribute()
    {
        return '$' . number_format($this->min_amount, 2);
    }

    public function getFormattedMaxAmountAttribute()
    {
        return $this->max_amount ? '$' . number_format($this->max_amount, 2) : 'Unlimited';
    }

    public function getFormattedChargesAttribute()
    {
        $charges = [];
        
        if ($this->fixed_charge > 0) {
            $charges[] = '$' . number_format($this->fixed_charge, 2);
        }
        
        if ($this->percentage_charge > 0) {
            $charges[] = $this->percentage_charge . '%';
        }
        
        return !empty($charges) ? implode(' + ', $charges) : 'Free';
    }

    // Methods
    public static function getTypes()
    {
        return [
            'bank_transfer' => 'Bank Transfer',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'wise' => 'Wise (TransferWise)',
            'skrill' => 'Skrill',
            'payoneer' => 'Payoneer',
            'cryptocurrency' => 'Cryptocurrency',
            'mobile_money' => 'Mobile Money',
            'check' => 'Check/Cheque',
            'cash_pickup' => 'Cash Pickup'
        ];
    }

    public function calculateCharges($amount)
    {
        $fixedCharge = $this->fixed_charge ?? 0;
        $percentageCharge = ($amount * ($this->percentage_charge ?? 0)) / 100;
        
        return $fixedCharge + $percentageCharge;
    }

    public function calculateNetAmount($amount)
    {
        return $amount - $this->calculateCharges($amount);
    }

    public function isWithinLimits($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        
        return true;
    }

    public function supportsCurrency($currency)
    {
        if ($this->currency === $currency) {
            return true;
        }
        
        return in_array($currency, $this->supported_currencies ?? []);
    }

    public function getProcessingTimeInHours()
    {
        // Parse processing time string (e.g., "1-3 days", "24 hours", "instant")
        $time = strtolower($this->processing_time ?? '');
        
        if (strpos($time, 'instant') !== false) {
            return 0;
        }
        
        if (strpos($time, 'hour') !== false) {
            preg_match('/(\d+)/', $time, $matches);
            return isset($matches[0]) ? (int)$matches[0] : 24;
        }
        
        if (strpos($time, 'day') !== false) {
            preg_match('/(\d+)/', $time, $matches);
            return isset($matches[0]) ? (int)$matches[0] * 24 : 24;
        }
        
        return 24; // Default to 24 hours
    }

    public function testConnection()
    {
        // This would implement actual gateway testing logic
        // For now, return a mock response
        
        if (!$this->is_active) {
            return [
                'success' => false,
                'message' => 'Withdrawal method is inactive'
            ];
        }
        
        if ($this->test_mode) {
            return [
                'success' => true,
                'message' => 'Test mode connection successful'
            ];
        }
        
        // Implement actual gateway API testing here
        return [
            'success' => true,
            'message' => 'Connection test successful'
        ];
    }

    public function getRequiredFieldsForUser()
    {
        return $this->required_fields ?? [];
    }

    public function validateUserData($userData)
    {
        $requiredFields = $this->getRequiredFieldsForUser();
        $errors = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($userData[$field['name']]) || empty($userData[$field['name']])) {
                $errors[] = $field['label'] . ' is required';
            }
        }
        
        return $errors;
    }
}
