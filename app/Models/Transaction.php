<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'commission_rate',
        'commission_amount',
        'base_amount',
        'fee',
        'status',
        'payment_method',
        'wallet_type',
        'account_number',
        'account_name',
        'description',
        'note',
        'metadata',
        'reference_type',
        'reference_id',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];

    // Transaction types
    const TYPES = [
        'commission' => 'Commission',
        'bonus' => 'Bonus',
        'daily_cashback' => 'Daily Cashback',
        'withdrawal' => 'Withdrawal',
        'transfer_out' => 'Transfer Out',
        'transfer_in' => 'Transfer In',
        'refund' => 'Refund',
        'penalty' => 'Penalty',
        'adjustment' => 'Adjustment',
        'subscription' => 'Subscription Payment',
        'deposit' => 'Deposit',
        'cod_deposit' => 'Cash on Delivery Deposit',
        'cod_refund' => 'Cash on Delivery Refund'
    ];

    // Transaction statuses
    const STATUSES = [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled'
    ];

    // Payment methods
    const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'crypto' => 'Cryptocurrency',
        'wallet' => 'Wallet Balance',
        'app_balance' => 'App Balance',
        'check' => 'Check',
        'manual' => 'Manual'
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

    public function reference()
    {
        return $this->morphTo();
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPaymentMethodNameAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'commission' => 'ti-trending-up',
            'bonus' => 'ti-gift',
            'withdrawal' => 'ti-arrow-down',
            'refund' => 'ti-rotate-ccw',
            'penalty' => 'ti-alert-triangle',
            'adjustment' => 'ti-edit',
            'subscription' => 'ti-credit-card',
            'deposit' => 'ti-arrow-up'
        ];

        return $icons[$this->type] ?? 'ti-dollar-sign';
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['commission', 'bonus', 'deposit', 'refund']);
    }

    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['withdrawal', 'penalty', 'subscription']);
    }

    // Methods
    public function approve($adminId = null)
    {
        $this->status = 'completed';
        $this->processed_by = $adminId;
        $this->processed_at = now();
        $this->save();

        return $this;
    }

    public function reject($reason = null)
    {
        $this->status = 'failed';
        $this->processed_at = now();
        
        if ($reason) {
            $metadata = $this->metadata ?? [];
            $metadata['rejection_reason'] = $reason;
            $this->metadata = $metadata;
        }
        
        $this->save();

        return $this;
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->processed_at = now();
        $this->save();

        return $this;
    }

    public static function generateTransactionId()
    {
        do {
            $id = 'TXN' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('transaction_id', $id)->exists());

        return $id;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_id) {
                $transaction->transaction_id = self::generateTransactionId();
            }
        });

        // Send notification when transaction is created
        static::created(function ($transaction) {
            try {
                $transaction->sendTransactionNotification();
            } catch (\Exception $e) {
                Log::error("Failed to send transaction notification: " . $e->getMessage());
            }
        });

        // Send notification when transaction status changes
        static::updated(function ($transaction) {
            try {
                if ($transaction->wasChanged('status')) {
                    $transaction->sendStatusChangeNotification();
                }
            } catch (\Exception $e) {
                Log::error("Failed to send transaction status notification: " . $e->getMessage());
            }
        });
    }

    /**
     * Send notification for transaction creation
     */
    public function sendTransactionNotification()
    {
        if (!$this->user_id) return;

        $notificationService = app(NotificationService::class);
        
        // Only send notifications for completed transactions or important status changes
        if ($this->status !== 'completed' && !in_array($this->type, ['withdrawal', 'deposit'])) {
            return;
        }

        switch ($this->type) {
            case 'commission':
                $commissionTypes = [
                    'direct_referral' => 'Direct Referral',
                    'matching_bonus' => 'Matching Bonus',
                    'binary_commission' => 'Binary Commission',
                    'rank_bonus' => 'Rank Bonus'
                ];
                
                $commissionType = $this->reference_type ?? 'commission';
                $fromUser = $this->metadata['from_user'] ?? '';
                
                $notificationService->sendCommission(
                    $this->user_id, 
                    abs($this->amount), 
                    $commissionTypes[$commissionType] ?? 'Commission',
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'bonus':
                $notificationService->sendBonus(
                    $this->user_id,
                    abs($this->amount),
                    $this->reference_type ?? 'bonus',
                    $this->description ?? 'You received a bonus',
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'deposit':
                $method = $this->payment_method ?? 'Bank Transfer';
                $notificationService->sendDeposit(
                    $this->user_id,
                    abs($this->amount),
                    $method,
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id,
                            'wallet_type' => $this->wallet_type
                        ])
                    ]
                );
                break;

            case 'transfer_in':
                $fromUser = $this->metadata['from_user'] ?? 'Another User';
                $notificationService->sendTransferReceived(
                    $this->user_id,
                    abs($this->amount),
                    $fromUser,
                    $this->wallet_type ?? 'balance',
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'transfer_out':
                $toUser = $this->metadata['to_user'] ?? 'Another User';
                $notificationService->sendTransferSent(
                    $this->user_id,
                    abs($this->amount),
                    $toUser,
                    $this->wallet_type ?? 'balance',
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'refund':
                $reason = $this->metadata['reason'] ?? 'Refund processed';
                $notificationService->sendRefund(
                    $this->user_id,
                    abs($this->amount),
                    $reason,
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'penalty':
                $reason = $this->metadata['reason'] ?? 'Penalty applied';
                $notificationService->sendPenalty(
                    $this->user_id,
                    abs($this->amount),
                    $reason,
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
                break;

            case 'rank_salary':
                // This is handled separately in BinaryRankService
                break;

            default:
                // Send generic transaction notification for other types
                $notificationService->sendTransaction(
                    $this->user_id,
                    $this->type,
                    abs($this->amount),
                    $this->description ?? ucfirst(str_replace('_', ' ', $this->type)),
                    [
                        'reference_type' => $this->reference_type,
                        'reference_id' => $this->reference_id,
                        'data' => array_merge($this->metadata ?? [], [
                            'transaction_id' => $this->transaction_id
                        ])
                    ]
                );
        }
    }

    /**
     * Send notification for transaction status change
     */
    public function sendStatusChangeNotification()
    {
        if (!$this->user_id) return;

        $notificationService = app(NotificationService::class);
        
        // Only send status notifications for certain transaction types
        if (!in_array($this->type, ['withdrawal', 'deposit', 'transfer_in', 'transfer_out'])) {
            return;
        }

        if ($this->type === 'withdrawal') {
            $notificationService->sendWithdrawal(
                $this->user_id,
                abs($this->amount),
                $this->status,
                [
                    'reference_type' => $this->reference_type,
                    'reference_id' => $this->reference_id,
                    'data' => array_merge($this->metadata ?? [], [
                        'transaction_id' => $this->transaction_id,
                        'previous_status' => $this->getOriginal('status')
                    ])
                ]
            );
        }
    }
}
