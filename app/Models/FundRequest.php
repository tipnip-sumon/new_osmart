<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FundRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'vendor_id',
        'amount',
        'request_type',
        'purpose',
        'notes',
        'status',
        'admin_notes',
        'processed_at',
        'processed_by',
        'transaction_id',
        'reference_type',
        'reference_id',
        'priority',
        'expires_at',
        'approved_amount',
        'rejection_reason',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    const STATUSES = [
        self::STATUS_PENDING => 'Pending Review',
        self::STATUS_APPROVED => 'Approved',
        self::STATUS_REJECTED => 'Rejected',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_EXPIRED => 'Expired'
    ];

    // Request types
    const TYPE_LOAN = 'loan';
    const TYPE_ADVANCE = 'advance';
    const TYPE_DISCOUNT = 'discount';
    const TYPE_BONUS = 'bonus';
    const TYPE_COMMISSION_ADVANCE = 'commission_advance';
    const TYPE_EMERGENCY = 'emergency';

    const REQUEST_TYPES = [
        self::TYPE_LOAN => 'Loan Request',
        self::TYPE_ADVANCE => 'Advance Payment',
        self::TYPE_DISCOUNT => 'Discount Request',
        self::TYPE_BONUS => 'Bonus Request',
        self::TYPE_COMMISSION_ADVANCE => 'Commission Advance',
        self::TYPE_EMERGENCY => 'Emergency Fund'
    ];

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    const PRIORITIES = [
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_NORMAL => 'Normal',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_URGENT => 'Urgent'
    ];

    /**
     * Relationships
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
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

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Accessors & Mutators
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getRequestTypeLabelAttribute()
    {
        return self::REQUEST_TYPES[$this->request_type] ?? $this->request_type;
    }

    public function getPriorityLabelAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            self::STATUS_EXPIRED => 'dark',
            default => 'secondary'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'secondary',
            self::PRIORITY_NORMAL => 'primary',
            self::PRIORITY_HIGH => 'warning',
            self::PRIORITY_URGENT => 'danger',
            default => 'primary'
        };
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getCanBeApprovedAttribute()
    {
        return $this->status === self::STATUS_PENDING && !$this->is_expired;
    }

    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    /**
     * Methods
     */
    public function approve($processedBy, $approvedAmount = null, $notes = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_amount = $approvedAmount ?? $this->amount;
        $this->processed_by = $processedBy;
        $this->processed_at = now();
        $this->admin_notes = $notes;
        $this->save();

        return $this;
    }

    public function reject($processedBy, $reason = null)
    {
        $this->status = self::STATUS_REJECTED;
        $this->processed_by = $processedBy;
        $this->processed_at = now();
        $this->rejection_reason = $reason;
        $this->save();

        return $this;
    }

    public function complete($transactionId = null)
    {
        $this->status = self::STATUS_COMPLETED;
        $this->transaction_id = $transactionId;
        $this->save();

        return $this;
    }

    public function cancel($reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        $this->rejection_reason = $reason;
        $this->save();

        return $this;
    }

    public function markExpired()
    {
        if ($this->is_expired && $this->status === self::STATUS_PENDING) {
            $this->status = self::STATUS_EXPIRED;
            $this->save();
        }

        return $this;
    }

    /**
     * Static methods
     */
    public static function generateRequestNumber()
    {
        do {
            $number = 'FR' . date('Ymd') . rand(1000, 9999);
        } while (self::where('transaction_id', $number)->exists());

        return $number;
    }

    public static function getPendingCountForVendor($vendorId)
    {
        return self::where('vendor_id', $vendorId)
            ->where('status', self::STATUS_PENDING)
            ->notExpired()
            ->count();
    }

    public static function getTotalAmountForVendor($vendorId, $status = null)
    {
        $query = self::where('vendor_id', $vendorId);
        
        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('approved_amount') ?: $query->sum('amount');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fundRequest) {
            if (!$fundRequest->transaction_id) {
                $fundRequest->transaction_id = self::generateRequestNumber();
            }
            
            if (!$fundRequest->priority) {
                $fundRequest->priority = self::PRIORITY_NORMAL;
            }
        });

        static::updating(function ($fundRequest) {
            // Auto-expire if past expiry date
            if ($fundRequest->is_expired && $fundRequest->status === self::STATUS_PENDING) {
                $fundRequest->status = self::STATUS_EXPIRED;
            }
        });
    }
}
