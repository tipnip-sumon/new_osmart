<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'type',
        'quantity',
        'reason',
        'user_id',
        'previous_quantity',
        'new_quantity',
        'cost_impact',
        'reference_number',
        'notes',
        'approved_by',
        'approved_at',
        'adjustment_date'
    ];

    protected $casts = [
        'adjustment_date' => 'datetime',
        'approved_at' => 'datetime',
        'quantity' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'cost_impact' => 'decimal:2'
    ];

    // Adjustment types: increase, decrease
    const TYPES = [
        'increase' => 'Stock Increase',
        'decrease' => 'Stock Decrease'
    ];

    // Adjustment reasons
    const REASONS = [
        'damaged' => 'Damaged Goods',
        'expired' => 'Expired Products',
        'lost' => 'Lost/Stolen',
        'found' => 'Found/Recovered',
        'recount' => 'Physical Recount',
        'supplier_return' => 'Return to Supplier',
        'customer_return' => 'Customer Return',
        'quality_issue' => 'Quality Issue',
        'system_error' => 'System Error Correction',
        'other' => 'Other'
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getReasonNameAttribute()
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }

    public function getQuantityChangeAttribute()
    {
        return $this->new_quantity - $this->previous_quantity;
    }

    public function getIsApprovedAttribute()
    {
        return !is_null($this->approved_at);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('approved_at');
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('adjustment_date', [$from, $to]);
    }

    public function scopeSignificant($query, $threshold = 100)
    {
        return $query->where('quantity', '>=', $threshold);
    }

    // Methods
    public function approve($userId)
    {
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();

        return $this;
    }

    public function calculateCostImpact()
    {
        if ($this->inventory) {
            $quantityChange = $this->type === 'increase' ? $this->quantity : -$this->quantity;
            $this->cost_impact = $quantityChange * $this->inventory->cost_price;
            $this->save();
        }

        return $this;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adjustment) {
            if (empty($adjustment->adjustment_date)) {
                $adjustment->adjustment_date = now();
            }
            
            if (empty($adjustment->reference_number)) {
                $adjustment->reference_number = 'ADJ-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($adjustment) {
            $adjustment->calculateCostImpact();
        });
    }
}
