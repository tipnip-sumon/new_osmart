<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_number',
        'user_id',
        'amount',
        'fee',
        'net_amount',
        'method',
        'payment_details',
        'status',
        'notes',
        'admin_notes',
        'transaction_reference',
        'requested_at',
        'processed_at',
        'completed_at',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'payment_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // Withdrawal methods
    const METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'crypto' => 'Cryptocurrency',
        'check' => 'Check',
        'wise' => 'Wise (TransferWise)',
        'skrill' => 'Skrill',
        'payoneer' => 'Payoneer'
    ];

    // Withdrawal statuses
    const STATUSES = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'failed' => 'Failed'
    ];

    // Withdrawal fees by method (percentage)
    const FEES = [
        'bank_transfer' => 0.02, // 2%
        'paypal' => 0.029,       // 2.9%
        'stripe' => 0.029,       // 2.9%
        'crypto' => 0.01,        // 1%
        'check' => 0.05,         // 5%
        'wise' => 0.015,         // 1.5%
        'skrill' => 0.035,       // 3.5%
        'payoneer' => 0.02       // 2%
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Accessors
    public function getMethodNameAttribute()
    {
        return self::METHODS[$this->method] ?? $this->method;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getFormattedFeeAttribute()
    {
        return number_format($this->fee, 2);
    }

    public function getFormattedNetAmountAttribute()
    {
        return number_format($this->net_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'secondary',
            'failed' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getMethodIconAttribute()
    {
        $icons = [
            'bank_transfer' => 'ti-building-bank',
            'paypal' => 'ti-brand-paypal',
            'stripe' => 'ti-credit-card',
            'crypto' => 'ti-currency-bitcoin',
            'check' => 'ti-file-text',
            'wise' => 'ti-send',
            'skrill' => 'ti-wallet',
            'payoneer' => 'ti-credit-card'
        ];

        return $icons[$this->method] ?? 'ti-wallet';
    }

    public function getProcessingTimeAttribute()
    {
        if (!$this->requested_at) return null;
        
        if ($this->completed_at) {
            return $this->requested_at->diffForHumans($this->completed_at);
        }
        
        return $this->requested_at->diffForHumans();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('requested_at', '>=', now()->subDays($days));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('requested_at', now()->month)
                    ->whereYear('requested_at', now()->year);
    }

    // Methods
    public function calculateFee()
    {
        $feeRate = self::FEES[$this->method] ?? 0.02; // Default 2%
        $this->fee = $this->amount * $feeRate;
        $this->net_amount = $this->amount - $this->fee;
        
        return $this;
    }

    public function approve($adminId = null)
    {
        $this->status = 'processing';
        $this->processed_by = $adminId;
        $this->processed_at = now();
        $this->save();

        // Create transaction record
        Transaction::create([
            'user_id' => $this->user_id,
            'type' => 'withdrawal',
            'amount' => -$this->amount,
            'status' => 'completed',
            'payment_method' => $this->method,
            'description' => "Withdrawal via {$this->method_name}",
            'reference_type' => 'withdrawal',
            'reference_id' => $this->id,
            'processed_by' => $adminId,
            'processed_at' => now()
        ]);

        return $this;
    }

    public function complete($transactionReference = null)
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->transaction_reference = $transactionReference;
        $this->save();

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        $this->admin_notes = $reason;
        $this->save();

        // Restore user balance
        $this->user->available_balance += $this->amount;
        $this->user->save();

        return $this;
    }

    public function fail($reason = null)
    {
        $this->status = 'failed';
        $this->admin_notes = $reason;
        $this->save();

        // Restore user balance
        $this->user->available_balance += $this->amount;
        $this->user->save();

        return $this;
    }

    public static function generateWithdrawalNumber()
    {
        do {
            $number = 'WD' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('withdrawal_number', $number)->exists());

        return $number;
    }

    public static function getMinimumAmount($method = null)
    {
        $minimums = [
            'bank_transfer' => 50.00,
            'paypal' => 10.00,
            'stripe' => 10.00,
            'crypto' => 25.00,
            'check' => 100.00,
            'wise' => 20.00,
            'skrill' => 15.00,
            'payoneer' => 20.00
        ];

        return $method ? ($minimums[$method] ?? 10.00) : min($minimums);
    }

    public static function getProcessingTime($method)
    {
        $times = [
            'bank_transfer' => '3-5 business days',
            'paypal' => 'Instant',
            'stripe' => '1-2 business days',
            'crypto' => '30 minutes - 2 hours',
            'check' => '7-14 business days',
            'wise' => '1-2 business days',
            'skrill' => 'Instant',
            'payoneer' => '1-3 business days'
        ];

        return $times[$method] ?? '1-3 business days';
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($withdrawal) {
            // Generate withdrawal number
            if (!$withdrawal->withdrawal_number) {
                $withdrawal->withdrawal_number = self::generateWithdrawalNumber();
            }

            // Set requested_at
            $withdrawal->requested_at = $withdrawal->requested_at ?? now();

            // Calculate fee and net amount
            $withdrawal->calculateFee();
        });
    }
}
