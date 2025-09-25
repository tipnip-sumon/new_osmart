<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'manager_id',
        'capacity',
        'used_capacity',
        'coordinates',
        'is_active',
        'operating_hours',
        'description'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'operating_hours' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'decimal:2',
        'used_capacity' => 'decimal:2'
    ];

    protected $appends = [
        'capacity_utilization',
        'full_address'
    ];

    // Relationships
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function movements()
    {
        return $this->hasManyThrough(InventoryMovement::class, Inventory::class);
    }

    // Accessors
    public function getCapacityUtilizationAttribute()
    {
        if ($this->capacity > 0) {
            return ($this->used_capacity / $this->capacity) * 100;
        }
        return 0;
    }

    public function getFullAddressAttribute()
    {
        return trim("{$this->address}, {$this->city}, {$this->state}, {$this->country} {$this->postal_code}");
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNearCapacity($query, $threshold = 80)
    {
        return $query->whereRaw('(used_capacity / capacity) * 100 >= ?', [$threshold]);
    }

    // Methods
    public function updateCapacity()
    {
        $this->used_capacity = $this->inventories()->sum('quantity');
        $this->save();
        return $this;
    }

    public function getTotalValue()
    {
        return $this->inventories()->get()->sum('stock_value');
    }

    public function getProductCount()
    {
        return $this->inventories()->count();
    }

    public function getLowStockCount()
    {
        return $this->inventories()->lowStock()->count();
    }
}
