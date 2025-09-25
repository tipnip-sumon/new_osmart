<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'reserved_quantity',
        'min_stock_level',
        'max_stock_level',
        'reorder_point',
        'reorder_quantity',
        'cost_per_unit',
        'location',
        'batch_number',
        'serial_number',
        'expiry_date',
        'manufacturing_date',
        'condition',
        'notes',
        'last_counted_at',
        'count_variance',
        'is_active',
        'last_updated_by'
    ];

    protected $casts = [
        'dimensions' => 'array',
        'expiry_date' => 'date',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'stock_status',
        'stock_value',
        'profit_margin',
        'days_until_expiry'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function adjustments()
    {
        return $this->hasMany(InventoryAdjustment::class);
    }

    public function alerts()
    {
        return $this->hasMany(InventoryAlert::class);
    }

    // Accessors
    public function getStockStatusAttribute()
    {
        if ($this->available_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->available_quantity <= $this->minimum_stock) {
            return 'low_stock';
        } elseif ($this->available_quantity >= $this->maximum_stock) {
            return 'overstock';
        }
        return 'in_stock';
    }

    public function getStockValueAttribute()
    {
        return $this->available_quantity * $this->cost_price;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->cost_price > 0) {
            return (($this->selling_price - $this->cost_price) / $this->cost_price) * 100;
        }
        return 0;
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->expiry_date) {
            return now()->diffInDays($this->expiry_date, false);
        }
        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('available_quantity <= minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('available_quantity', '<=', 0);
    }

    public function scopeOverstock($query)
    {
        return $query->whereRaw('available_quantity >= maximum_stock');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    // Methods
    public function updateQuantity($newQuantity, $reason = null, $userId = null)
    {
        $oldQuantity = $this->quantity;
        $this->quantity = $newQuantity;
        // Note: available_quantity is auto-calculated as (quantity - reserved_quantity)
        $this->last_updated_by = $userId;
        $this->save();

        // Log movement
        $this->movements()->create([
            'type' => $newQuantity > $oldQuantity ? 'in' : 'out',
            'quantity' => abs($newQuantity - $oldQuantity),
            'reason' => $reason ?? 'Manual adjustment',
            'user_id' => $userId,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity
        ]);

        $this->checkAndCreateAlerts();

        return $this;
    }

    public function reserveStock($quantity, $orderId = null)
    {
        if ($this->available_quantity >= $quantity) {
            $this->reserved_quantity += $quantity;
            // Note: available_quantity will be auto-calculated as (quantity - reserved_quantity)
            $this->save();

            // Log movement
            $this->movements()->create([
                'type' => 'reserved',
                'quantity' => $quantity,
                'reason' => 'Stock reserved for order: ' . $orderId,
                'order_id' => $orderId,
                'previous_quantity' => $this->quantity,
                'new_quantity' => $this->quantity
            ]);

            return true;
        }

        return false;
    }

    public function releaseReservedStock($quantity, $orderId = null)
    {
        $releaseAmount = min($quantity, $this->reserved_quantity);
        $this->reserved_quantity -= $releaseAmount;
        // Note: available_quantity will be auto-calculated as (quantity - reserved_quantity)
        $this->save();

        // Log movement
        $this->movements()->create([
            'type' => 'released',
            'quantity' => $releaseAmount,
            'reason' => 'Stock released from order: ' . $orderId,
            'order_id' => $orderId,
            'previous_quantity' => $this->quantity,
            'new_quantity' => $this->quantity
        ]);

        return $releaseAmount;
    }

    public function adjustStock($quantity, $type, $reason, $userId = null)
    {
        $oldQuantity = $this->quantity;
        
        if ($type === 'increase') {
            $this->quantity += $quantity;
        } else {
            $this->quantity = max(0, $this->quantity - $quantity);
        }
        
        // Note: available_quantity is auto-calculated as (quantity - reserved_quantity)
        $this->last_updated_by = $userId;
        $this->save();

        // Log adjustment
        $this->adjustments()->create([
            'type' => $type,
            'quantity' => $quantity,
            'reason' => $reason,
            'user_id' => $userId,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $this->quantity
        ]);

        // Log movement
        $this->movements()->create([
            'type' => $type === 'increase' ? 'in' : 'out',
            'quantity' => $quantity,
            'reason' => $reason,
            'user_id' => $userId,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $this->quantity
        ]);

        $this->checkAndCreateAlerts();

        return $this;
    }

    public function checkAndCreateAlerts()
    {
        $alerts = [];

        // Low stock alert
        if ($this->available_quantity <= $this->minimum_stock) {
            $alerts[] = [
                'type' => 'low_stock',
                'priority' => 'high',
                'message' => "Low stock alert: {$this->product->name} has only {$this->available_quantity} units left",
                'data' => json_encode(['current_stock' => $this->available_quantity, 'minimum_stock' => $this->minimum_stock])
            ];
        }

        // Out of stock alert
        if ($this->available_quantity <= 0) {
            $alerts[] = [
                'type' => 'out_of_stock',
                'priority' => 'critical',
                'message' => "Out of stock: {$this->product->name} is completely out of stock",
                'data' => json_encode(['current_stock' => $this->available_quantity])
            ];
        }

        // Expiry alert
        if ($this->expiry_date && $this->days_until_expiry <= 30 && $this->days_until_expiry > 0) {
            $alerts[] = [
                'type' => 'expiring_soon',
                'priority' => 'medium',
                'message' => "Expiry warning: {$this->product->name} expires in {$this->days_until_expiry} days",
                'data' => json_encode(['expiry_date' => $this->expiry_date, 'days_until_expiry' => $this->days_until_expiry])
            ];
        }

        // Expired alert
        if ($this->expiry_date && $this->days_until_expiry <= 0) {
            $alerts[] = [
                'type' => 'expired',
                'priority' => 'critical',
                'message' => "Expired: {$this->product->name} has expired",
                'data' => json_encode(['expiry_date' => $this->expiry_date])
            ];
        }

        // Create alerts
        foreach ($alerts as $alertData) {
            $this->alerts()->updateOrCreate(
                ['type' => $alertData['type']],
                array_merge($alertData, [
                    'is_resolved' => false,
                    'created_at' => now()
                ])
            );
        }
    }

    public function generateSKU()
    {
        $prefix = strtoupper(substr($this->product->name ?? 'PRD', 0, 3));
        $vendor = strtoupper(substr($this->vendor->name ?? 'VND', 0, 3));
        $timestamp = now()->format('ymd');
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$vendor}-{$timestamp}-{$random}";
    }

    public function getFormattedDimensions()
    {
        if ($this->dimensions) {
            return "{$this->dimensions['length']} x {$this->dimensions['width']} x {$this->dimensions['height']} cm";
        }
        return null;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($inventory) {
            $inventory->checkAndCreateAlerts();
        });

        static::updated(function ($inventory) {
            if ($inventory->wasChanged(['quantity', 'reserved_quantity', 'min_stock_level', 'expiry_date'])) {
                $inventory->checkAndCreateAlerts();
            }
        });
    }
}
