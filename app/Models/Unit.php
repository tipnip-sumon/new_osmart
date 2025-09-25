<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'type',
        'description',
        'base_factor',
        'base_unit_id',
        'is_active',
        'is_default',
        'sort_order',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'base_factor' => 'decimal:6',
        'metadata' => 'array',
    ];

    /**
     * The unit types available
     */
    const TYPES = [
        'weight' => 'Weight',
        'length' => 'Length',
        'volume' => 'Volume',
        'area' => 'Area',
        'time' => 'Time',
        'quantity' => 'Quantity',
        'temperature' => 'Temperature',
        'other' => 'Other',
    ];

    /**
     * Relationship: Base unit
     */
    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    /**
     * Relationship: Units that use this as base
     */
    public function derivedUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    /**
     * Relationship: Products that use this unit
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for units by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for default units
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get type name for display
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'badge-success' : 'badge-secondary';
    }

    /**
     * Get status name for display
     */
    public function getStatusNameAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get full name with symbol
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->symbol . ')';
    }

    /**
     * Convert value from this unit to base unit
     */
    public function convertToBase($value)
    {
        return $value * $this->base_factor;
    }

    /**
     * Convert value from base unit to this unit
     */
    public function convertFromBase($value)
    {
        return $value / $this->base_factor;
    }

    /**
     * Convert value to another unit of the same type
     */
    public function convertTo($value, Unit $targetUnit)
    {
        if ($this->type !== $targetUnit->type) {
            throw new \InvalidArgumentException('Cannot convert between different unit types');
        }

        $baseValue = $this->convertToBase($value);
        return $targetUnit->convertFromBase($baseValue);
    }
}
