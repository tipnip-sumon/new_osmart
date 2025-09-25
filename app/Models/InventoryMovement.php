<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'warehouse_id',
        'type',
        'quantity',
        'remaining_quantity',
        'unit_cost',
        'reason',
        'user_id',
        'order_id',
        'supplier_id',
        'previous_quantity',
        'new_quantity',
        'reference_type',
        'reference_id',
        'reference_number',
        'batch_number',
        'serial_number',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
        'is_approved',
        'metadata'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'remaining_quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
        'metadata' => 'array'
    ];

    // Movement types: in, out, reserved, released, adjusted, damaged, expired, returned
    const TYPES = [
        'in' => 'Stock In',
        'out' => 'Stock Out',
        'reserved' => 'Reserved',
        'released' => 'Released',
        'adjusted' => 'Adjusted',
        'damaged' => 'Damaged',
        'expired' => 'Expired',
        'returned' => 'Returned',
        'transferred' => 'Transferred'
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getQuantityChangeAttribute()
    {
        return $this->new_quantity - $this->previous_quantity;
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeIncoming($query)
    {
        return $query->whereIn('type', ['in', 'returned', 'released']);
    }

    public function scopeOutgoing($query)
    {
        return $query->whereIn('type', ['out', 'reserved', 'damaged', 'expired']);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('movement_date', [$from, $to]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('movement_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('movement_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('movement_date', now()->month)
                    ->whereYear('movement_date', now()->year);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            if (empty($movement->movement_date)) {
                $movement->movement_date = now();
            }
            
            if (empty($movement->reference_number)) {
                $movement->reference_number = 'MOV-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
