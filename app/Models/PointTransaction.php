<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Carbon\Carbon;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // credit or debit
        'amount',
        'description',
        'reference_id',
        'reference_type', // package_activation, product_purchase, transfer_in, transfer_out, etc.
        'status' // pending, completed, failed
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === 'credit' ? '+' : '-';
        return $prefix . number_format($this->amount, 0);
    }

    /**
     * Get the transaction color class
     */
    public function getColorClassAttribute()
    {
        return match($this->type) {
            'credit' => 'text-success',
            'debit' => 'text-danger',
            default => 'text-muted'
        };
    }

    /**
     * Get the transaction icon
     */
    public function getIconAttribute()
    {
        return match($this->reference_type) {
            'package_activation' => 'bx-rocket',
            'product_purchase' => 'bx-shopping-bag',
            'transfer_in' => 'bx-transfer',
            'transfer_out' => 'bx-export',
            'commission' => 'bx-money',
            'bonus' => 'bx-gift',
            default => 'bx-coin'
        };
    }

    /**
     * Create a credit transaction
     */
    public static function createCredit($userId, $amount, $description, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'status' => 'completed'
        ]);
    }

    /**
     * Create a debit transaction
     */
    public static function createDebit($userId, $amount, $description, $referenceType = null, $referenceId = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'status' => 'completed'
        ]);
    }

    // Boot method to handle notifications
    protected static function boot()
    {
        parent::boot();

        static::created(function ($pointTransaction) {
            try {
                $pointTransaction->sendPointNotification();
            } catch (\Exception $e) {
                Log::error("Failed to send point transaction notification: " . $e->getMessage());
            }
        });
    }

    /**
     * Send point transaction notification
     */
    public function sendPointNotification()
    {
        if (!$this->user_id || $this->status !== 'completed') return;

        $notificationService = app(NotificationService::class);
        
        $notificationService->sendPointTransaction(
            $this->user_id,
            $this->amount,
            $this->type,
            $this->description,
            [
                'reference_type' => $this->reference_type,
                'reference_id' => $this->reference_id,
                'data' => [
                    'point_transaction_id' => $this->id,
                    'transaction_type' => $this->type,
                    'reference_type' => $this->reference_type
                ]
            ]
        );
    }
}
