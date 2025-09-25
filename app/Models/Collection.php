<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'image',
        'image_data',
        'banner_image',
        'color_code',
        'sort_order',
        'status',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'commission_rate',
        'commission_type',
        'show_in_menu',
        'show_in_footer',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_in_footer' => 'boolean',
        'image_data' => 'array',
        'metadata' => 'array',
        'commission_rate' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relationship: Products in this collection
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_products');
    }

    /**
     * Scope for active collections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('status', 'active');
    }

    /**
     * Scope for featured collections
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for collections that show in menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    /**
     * Scope for collections that show in footer
     */
    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true);
    }

    /**
     * Scope for collections with specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'draft' => 'badge-warning',
            default => 'badge-secondary'
        };
    }

    /**
     * Get status name for display
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft',
            default => 'Unknown'
        };
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get medium size image URL
     */
    public function getMediumImageUrlAttribute()
    {
        if ($this->image_data && isset($this->image_data['sizes']['medium']['url'])) {
            return $this->image_data['sizes']['medium']['url'];
        }
        return $this->image_url; // Fallback to original
    }

    /**
     * Get large size image URL
     */
    public function getLargeImageUrlAttribute()
    {
        if ($this->image_data && isset($this->image_data['sizes']['large']['url'])) {
            return $this->image_data['sizes']['large']['url'];
        }
        return $this->image_url; // Fallback to original
    }

    /**
     * Get small size image URL
     */
    public function getSmallImageUrlAttribute()
    {
        if ($this->image_data && isset($this->image_data['sizes']['small']['url'])) {
            return $this->image_data['sizes']['small']['url'];
        }
        return $this->image_url; // Fallback to original
    }

    /**
     * Get thumbnail size image URL
     */
    public function getThumbnailImageUrlAttribute()
    {
        if ($this->image_data && isset($this->image_data['sizes']['thumbnail']['url'])) {
            return $this->image_data['sizes']['thumbnail']['url'];
        }
        return $this->image_url; // Fallback to original
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }
}