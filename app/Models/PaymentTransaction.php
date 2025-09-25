<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'transaction_id',
        'payment_method',
        'gateway',
        'amount',
        'fee',
        'net_amount',
        'status',
        'type',
        'currency',
        'gateway_response',
        'gateway_transaction_id',
        'notes',
        'processed_at',
        'failed_at',
        'refunded_at',
        'refunded_by',
        'failure_reason',
        'refund_reason',
        'rejection_reason',
        'processed_by',
        'sender_number',
        'receipt_path',
        'description',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refundedBy()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return '৳' . number_format($this->amount, 2);
    }

    public function getFormattedFeeAttribute()
    {
        return '৳' . number_format($this->fee, 2);
    }

    public function getFormattedNetAmountAttribute()
    {
        return '৳' . number_format($this->net_amount, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'refunded' => 'dark'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsFailedAttribute()
    {
        return $this->status === 'failed';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsRefundedAttribute()
    {
        return $this->status === 'refunded';
    }

    // Methods
    public function markAsCompleted($gatewayTransactionId = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
            'gateway_transaction_id' => $gatewayTransactionId,
            'gateway_response' => $gatewayResponse
        ]);

        return $this;
    }

    public function markAsFailed($reason = null, $gatewayResponse = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
            'gateway_response' => $gatewayResponse
        ]);

        return $this;
    }

    public function refund($amount = null, $reason = null, $refundedBy = null)
    {
        $refundAmount = $amount ?? $this->amount;
        
        if ($refundAmount > $this->amount) {
            throw new \Exception('Refund amount cannot be greater than original amount');
        }

        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_reason' => $reason,
            'refunded_by' => $refundedBy
        ]);

        return $this;
    }

    public function generateTransactionId()
    {
        $prefix = 'TXN';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return $prefix . $timestamp . $random;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_id) {
                $transaction->transaction_id = $transaction->generateTransactionId();
            }

            if (!$transaction->net_amount) {
                $transaction->net_amount = $transaction->amount - ($transaction->fee ?? 0);
            }
        });
    }
}
