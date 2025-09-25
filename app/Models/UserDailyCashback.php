<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyCashback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'cashback_amount',
        'cashback_date',
        'status',
        'remarks',
        'paid_at',
    ];

    protected $casts = [
        'cashback_amount' => 'decimal:2',
        'cashback_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the user that owns the cashback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan that the cashback is based on
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope for pending cashbacks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid cashbacks
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Mark cashback as paid
     */
    public function markAsPaid($remarks = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'remarks' => $remarks,
        ]);
    }
}
