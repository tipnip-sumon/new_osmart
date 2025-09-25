<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'attribute_id',
        'value',
        'display_name',
        'color_code',
        'image',
        'icon',
        'description',
        'extra_price',
        'is_default',
        'sort_order',
        'status',
        'seo_title',
        'seo_description',
        'metadata'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'extra_price' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Relationships
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForAttribute($query, $attributeId)
    {
        return $query->where('attribute_id', $attributeId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-danger'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    public function getDisplayValueAttribute()
    {
        return $this->display_name ?: $this->value;
    }

    public function getFormattedExtraPriceAttribute()
    {
        if ($this->extra_price > 0) {
            return '+$' . number_format($this->extra_price, 2);
        } elseif ($this->extra_price < 0) {
            return '-$' . number_format(abs($this->extra_price), 2);
        }
        return null;
    }

    // Methods
    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ];
    }

    public function hasImage()
    {
        return !empty($this->image);
    }

    public function hasIcon()
    {
        return !empty($this->icon);
    }

    public function hasColorCode()
    {
        return !empty($this->color_code);
    }

    public function getImageUrl()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getIconUrl()
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }
}
