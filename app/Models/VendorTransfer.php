<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorTransfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'recipient_id',
        'amount',
        'fee',
        'net_amount',
        'transfer_type',
        'wallet_type',
        'recipient_wallet',
        'notes',
        'purpose',
        'status',
        'admin_approval_required',
        'approved_by',
        'approved_at',
        'processed_at',
        'completed_at',
        'transaction_id',
        'transfer_reference',
        'reference_type',
        'reference_id',
        'failure_reason',
        'retry_count',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'admin_approval_required' => 'boolean',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
        'retry_count' => 'integer'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    const STATUSES = [
        self::STATUS_PENDING => 'Pending Approval',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_FAILED => 'Failed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_REJECTED => 'Rejected'
    ];

    // Transfer types
    const TYPE_DIRECT = 'direct';
    const TYPE_COMMISSION = 'commission';
    const TYPE_BONUS = 'bonus';
    const TYPE_REFUND = 'refund';
    const TYPE_ADVANCE = 'advance';
    const TYPE_LOAN = 'loan';
    const TYPE_DISCOUNT = 'discount';

    const TRANSFER_TYPES = [
        self::TYPE_DIRECT => 'Direct Transfer',
        self::TYPE_COMMISSION => 'Commission Payment',
        self::TYPE_BONUS => 'Bonus Payment',
        self::TYPE_REFUND => 'Refund',
        self::TYPE_ADVANCE => 'Advance Payment',
        self::TYPE_LOAN => 'Loan',
        self::TYPE_DISCOUNT => 'Discount'
    ];

    // Wallet types
    const WALLET_VENDOR_BALANCE = 'vendor_balance';
    const WALLET_DEPOSIT = 'deposit_wallet';
    const WALLET_COMMISSION = 'commission_wallet';

    const WALLET_TYPES = [
        self::WALLET_VENDOR_BALANCE => 'Vendor Balance',
        self::WALLET_DEPOSIT => 'Deposit Wallet',
        self::WALLET_COMMISSION => 'Commission Wallet'
    ];

    // Recipient wallet types
    const RECIPIENT_DEPOSIT = 'deposit_wallet';
    const RECIPIENT_INTEREST = 'interest_wallet';
    const RECIPIENT_BALANCE = 'balance';

    const RECIPIENT_WALLETS = [
        self::RECIPIENT_DEPOSIT => 'Member Deposit Wallet',
        self::RECIPIENT_INTEREST => 'Member Interest Wallet',
        self::RECIPIENT_BALANCE => 'Member Main Balance'
    ];

    /**
     * Relationships
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeForRecipient($query, $recipientId)
    {
        return $query->where('recipient_id', $recipientId);
    }

    public function scopeRequiringApproval($query)
    {
        return $query->where('admin_approval_required', true)
                    ->where('status', self::STATUS_PENDING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getTransferTypeLabelAttribute()
    {
        return self::TRANSFER_TYPES[$this->transfer_type] ?? $this->transfer_type;
    }

    public function getWalletTypeLabelAttribute()
    {
        return self::WALLET_TYPES[$this->wallet_type] ?? $this->wallet_type;
    }

    public function getRecipientWalletLabelAttribute()
    {
        return self::RECIPIENT_WALLETS[$this->recipient_wallet] ?? $this->recipient_wallet;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            self::STATUS_REJECTED => 'danger',
            default => 'secondary'
        };
    }

    public function getCanBeApprovedAttribute()
    {
        return $this->status === self::STATUS_PENDING && $this->admin_approval_required;
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function getCanBeRetriedAttribute()
    {
        return $this->status === self::STATUS_FAILED && $this->retry_count < 3;
    }

    /**
     * Methods
     */
    public function approve($approvedBy, $notes = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        if ($notes) {
            $this->notes = $this->notes ? $this->notes . "\n\nApproval Notes: " . $notes : $notes;
        }
        $this->save();

        return $this;
    }

    public function reject($rejectedBy, $reason = null)
    {
        $this->status = self::STATUS_REJECTED;
        $this->approved_by = $rejectedBy;
        $this->approved_at = now();
        $this->failure_reason = $reason;
        $this->save();

        return $this;
    }

    public function process()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->processed_at = now();
        $this->save();

        return $this;
    }

    public function complete($transactionId = null)
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        $this->save();

        return $this;
    }

    public function fail($reason = null, $canRetry = true)
    {
        $this->status = self::STATUS_FAILED;
        $this->failure_reason = $reason;
        if ($canRetry) {
            $this->retry_count = ($this->retry_count ?? 0) + 1;
        }
        $this->save();

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        $this->failure_reason = $reason;
        $this->save();

        return $this;
    }

    public function retry()
    {
        if ($this->can_be_retried) {
            $this->status = self::STATUS_PENDING;
            $this->failure_reason = null;
            $this->processed_at = null;
            $this->retry_count = ($this->retry_count ?? 0) + 1;
            $this->save();
        }

        return $this;
    }

    /**
     * Static methods
     */
    public static function generateTransferNumber()
    {
        do {
            $number = 'VT' . date('Ymd') . rand(1000, 9999);
        } while (self::where('transfer_reference', $number)->exists());

        return $number;
    }

    public static function getTotalTransferredByVendor($vendorId, $period = null)
    {
        $query = self::where('vendor_id', $vendorId)
                    ->where('status', self::STATUS_COMPLETED);

        if ($period === 'today') {
            $query->whereDate('completed_at', today());
        } elseif ($period === 'month') {
            $query->whereMonth('completed_at', now()->month)
                  ->whereYear('completed_at', now()->year);
        }

        return $query->sum('amount');
    }

    public static function getTotalReceivedByMember($memberId, $period = null)
    {
        $query = self::where('recipient_id', $memberId)
                    ->where('status', self::STATUS_COMPLETED);

        if ($period === 'today') {
            $query->whereDate('completed_at', today());
        } elseif ($period === 'month') {
            $query->whereMonth('completed_at', now()->month)
                  ->whereYear('completed_at', now()->year);
        }

        return $query->sum('net_amount');
    }

    public static function getPendingApprovalCount()
    {
        return self::where('admin_approval_required', true)
                  ->where('status', self::STATUS_PENDING)
                  ->count();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transfer) {
            if (!$transfer->transfer_reference) {
                $transfer->transfer_reference = self::generateTransferNumber();
            }
            
            if (!$transfer->recipient_wallet) {
                $transfer->recipient_wallet = self::RECIPIENT_DEPOSIT;
            }

            // Calculate fee if not set
            if (!$transfer->fee) {
                $transfer->fee = self::calculateTransferFee($transfer->amount, $transfer->transfer_type);
            }

            // Calculate net amount
            $transfer->net_amount = $transfer->amount - $transfer->fee;

            // Set default status
            if (!$transfer->status) {
                $transfer->status = $transfer->admin_approval_required 
                    ? self::STATUS_PENDING 
                    : self::STATUS_APPROVED;
            }
        });
    }

    /**
     * Calculate transfer fee based on amount and type
     */
    public static function calculateTransferFee($amount, $transferType = self::TYPE_DIRECT)
    {
        // Basic fee calculation - can be made more sophisticated
        $feePercentage = match($transferType) {
            self::TYPE_DIRECT => 0.01, // 1%
            self::TYPE_COMMISSION => 0.005, // 0.5%
            self::TYPE_BONUS => 0, // No fee for bonuses
            self::TYPE_REFUND => 0, // No fee for refunds
            self::TYPE_ADVANCE => 0.02, // 2%
            self::TYPE_LOAN => 0.03, // 3%
            self::TYPE_DISCOUNT => 0, // No fee for discounts
            default => 0.01
        };

        $fee = $amount * $feePercentage;
        $minFee = 5.00; // Minimum fee of ৳5
        $maxFee = 100.00; // Maximum fee of ৳100

        return max($minFee, min($maxFee, $fee));
    }
}
