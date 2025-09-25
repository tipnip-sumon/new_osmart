<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'parent_id',
        'image',
        'image_data',
        'banner_image',
        'icon',
        'color_code',
        'sort_order',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'commission_rate',
        'commission_type',
        'show_in_menu',
        'show_in_footer',
        'attributes_layout',
        'metadata'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_in_footer' => 'boolean',
        'commission_rate' => 'decimal:2',
        'metadata' => 'array',
        'image_data' => 'array'
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function subcategories()
    {
        return $this->children();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function subcategoryProducts()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeInMenu($query)
    {
        return $query->where('show_in_menu', true);
    }

    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-danger',
            'draft' => 'badge-warning'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    public function getBreadcrumbAttribute()
    {
        $breadcrumb = [];
        $category = $this;
        
        while ($category) {
            array_unshift($breadcrumb, $category->name);
            $category = $category->parent;
        }
        
        return implode(' > ', $breadcrumb);
    }

    public function getImageUrlAttribute()
    {
        // Use new image data structure if available
        if ($this->image_data && is_array($this->image_data)) {
            if (isset($this->image_data['sizes']['medium']['url'])) {
                return $this->image_data['sizes']['medium']['url'];
            }
            if (isset($this->image_data['sizes']['original']['url'])) {
                return $this->image_data['sizes']['original']['url'];
            }
        }
        
        // Fallback to legacy image field
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }

    /**
     * Get image URL by size
     */
    public function getImageUrl(string $size = 'medium'): ?string
    {
        if ($this->image_data && is_array($this->image_data)) {
            if (isset($this->image_data['sizes'][$size]['url'])) {
                return $this->image_data['sizes'][$size]['url'];
            }
            // Fallback to original if requested size not found
            if (isset($this->image_data['sizes']['original']['url'])) {
                return $this->image_data['sizes']['original']['url'];
            }
        }
        
        // Fallback to legacy image
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    // Methods
    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft'
        ];
    }

    public static function getCommissionTypes()
    {
        return [
            'percentage' => 'Percentage',
            'fixed' => 'Fixed Amount'
        ];
    }

    public function isParent()
    {
        return is_null($this->parent_id);
    }

    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function getLevel()
    {
        $level = 0;
        $category = $this->parent;
        
        while ($category) {
            $level++;
            $category = $category->parent;
        }
        
        return $level;
    }

    public function getAllChildren()
    {
        $children = collect();
        
        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }
        
        return $children;
    }

    public function getFormattedCommissionAttribute()
    {
        if ($this->commission_type === 'percentage') {
            return $this->commission_rate . '%';
        }
        return '$' . number_format($this->commission_rate, 2);
    }
}
