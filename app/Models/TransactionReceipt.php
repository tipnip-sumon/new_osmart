<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TransactionReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_type',
        'vendor_id',
        'customer_id',
        'order_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'reference_number',
        'gateway_transaction_id',
        'gateway_response',
        'description',
        'receipt_attachment',
        'invoice_attachment',
        'status',
        'transaction_date',
        'due_date',
        'processed_by',
        'updated_by',
        'notes',
        'metadata',
        'verification_status',
        'verification_notes',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'due_date' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
        'amount' => 'decimal:2'
    ];

    protected $dates = [
        'transaction_date',
        'due_date',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function histories()
    {
        return $this->hasMany(TransactionHistory::class, 'receipt_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeVerificationPending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'confirmed' => 'badge-success',
            'failed' => 'badge-danger',
            'cancelled' => 'badge-secondary',
            'refunded' => 'badge-info'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    public function getVerificationStatusBadgeAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'verified' => 'badge-success',
            'rejected' => 'badge-danger'
        ];

        return $classes[$this->verification_status] ?? 'badge-secondary';
    }

    // Mutators
    public function setTransactionIdAttribute($value)
    {
        $this->attributes['transaction_id'] = strtoupper($value);
    }

    public function setCurrencyAttribute($value)
    {
        $this->attributes['currency'] = strtoupper($value);
    }

    // Methods
    public function canBeEdited()
    {
        return !in_array($this->status, ['confirmed', 'refunded']);
    }

    public function canBeDeleted()
    {
        return !in_array($this->status, ['confirmed', 'refunded']);
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status === 'pending';
    }

    public function generateReferenceNumber()
    {
        return 'REF_' . date('Y') . '_' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public static function getTransactionTypes()
    {
        return [
            'payment' => 'Payment',
            'refund' => 'Refund',
            'payout' => 'Vendor Payout',
            'commission' => 'Commission',
            'withdrawal' => 'Withdrawal',
            'deposit' => 'Deposit',
            'transfer' => 'Transfer'
        ];
    }

    public static function getStatuses()
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];
    }

    public static function getVerificationStatuses()
    {
        return [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'rejected' => 'Rejected'
        ];
    }
}
