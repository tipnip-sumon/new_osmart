<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'logo_data',
        'banner_image',
        'banner_image_data',
        'website_url',
        'email',
        'phone',
        'address',
        'status',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'social_links',
        'commission_rate',
        'commission_type',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'commission_rate' => 'decimal:2',
        'social_links' => 'array',
        'logo_data' => 'array',
        'banner_image_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        // Use new image data structure if available
        if ($this->logo_data && is_array($this->logo_data)) {
            if (isset($this->logo_data['sizes']['medium']['url'])) {
                return $this->logo_data['sizes']['medium']['url'];
            }
            if (isset($this->logo_data['sizes']['original']['url'])) {
                return $this->logo_data['sizes']['original']['url'];
            }
        }
        
        // Fallback to legacy logo field
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        return asset('admin-assets/images/brand-placeholder.png');
    }

    public function getBannerImageUrlAttribute()
    {
        // Use new image data structure if available
        if ($this->banner_image_data && is_array($this->banner_image_data)) {
            if (isset($this->banner_image_data['sizes']['large']['url'])) {
                return $this->banner_image_data['sizes']['large']['url'];
            }
            if (isset($this->banner_image_data['sizes']['original']['url'])) {
                return $this->banner_image_data['sizes']['original']['url'];
            }
        }
        
        // Fallback to legacy banner_image field
        if ($this->banner_image) {
            return asset('storage/' . $this->banner_image);
        }
        
        return null;
    }

    /**
     * Get logo URL by size
     */
    public function getLogoUrl(string $size = 'medium'): ?string
    {
        if ($this->logo_data && is_array($this->logo_data)) {
            if (isset($this->logo_data['sizes'][$size]['url'])) {
                return $this->logo_data['sizes'][$size]['url'];
            }
            // Fallback to original if requested size not found
            if (isset($this->logo_data['sizes']['original']['url'])) {
                return $this->logo_data['sizes']['original']['url'];
            }
        }
        
        // Fallback to legacy logo
        if ($this->logo) {
            $logoPath = 'storage/' . $this->logo;
            // Check if file exists
            if (file_exists(public_path($logoPath))) {
                return asset($logoPath);
            }
        }
        
        // Return a data URL for a placeholder SVG
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="100" height="100" fill="#f8f9fa"/>
                <text x="50" y="55" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="24" font-weight="bold">' . 
                strtoupper(substr($this->name, 0, 2)) . 
                '</text>
            </svg>
        ');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }
}
