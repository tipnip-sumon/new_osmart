<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'user_id',
        'from_user_id',
        'commission_type',
        'amount',
        'percentage',
        'volume',
        'product_id',
        'order_id',
        'source_type',
        'source_id',
        'status',
        'earned_at',
        'approved_at',
        'paid_at',
        'calculated_by',
        'approved_by',
        'paid_by',
        'payment_method',
        'payment_reference',
        'payment_notes',
        'generation_level',
        'binary_leg',
        'left_volume',
        'right_volume',
        'carry_forward',
        'calculation_details',
        'notes',
        'is_holdback',
        'holdback_release_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'volume' => 'decimal:2',
        'left_volume' => 'decimal:2',
        'right_volume' => 'decimal:2',
        'carry_forward' => 'decimal:2',
        'earned_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_holdback' => 'boolean',
        'holdback_release_date' => 'date',
        'calculation_details' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculatedBy()
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // Scopes
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

    public function scopeByType($query, $type)
    {
        return $query->where('commission_type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function approve($approvedBy = null)
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->approved_by = $approvedBy;
        $this->save();

        return $this;
    }

    public function markAsPaid($paidBy = null, $paymentMethod = null, $paymentReference = null)
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->paid_by = $paidBy;
        $this->payment_method = $paymentMethod;
        $this->payment_reference = $paymentReference;
        $this->save();

        return $this;
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->save();

        return $this;
    }
}
