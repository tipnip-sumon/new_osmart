<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'icon',
        'route',
        'url',
        'parent_id',
        'sort_order',
        'is_active',
        'permission',
        'badge_text',
        'badge_color',
        'description',
        'target',
        'is_external',
        'menu_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_external' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(AdminMenu::class, 'parent_id');
    }

    /**
     * Get the children menu items
     */
    public function children()
    {
        return $this->hasMany(AdminMenu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get active children menu items
     */
    public function activeChildren()
    {
        return $this->hasMany(AdminMenu::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }

    /**
     * Scope for active menu items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parent menu items
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for children menu items
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get menu items ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the full URL for the menu item
     */
    public function getFullUrlAttribute()
    {
        if ($this->is_external) {
            return $this->url;
        }

        if ($this->route) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                return $this->url ?: '#';
            }
        }

        return $this->url ?: '#';
    }

    /**
     * Check if menu item is active based on current route
     */
    public function isActiveRoute()
    {
        if (!$this->route) {
            return false;
        }

        $currentRoute = request()->route()->getName();
        
        // Exact match
        if ($currentRoute === $this->route) {
            return true;
        }

        // Pattern match for wildcard routes
        if (str_contains($this->route, '*')) {
            $pattern = str_replace('*', '', $this->route);
            return str_starts_with($currentRoute, $pattern);
        }

        return false;
    }

    /**
     * Check if menu has active children
     */
    public function hasActiveChildren()
    {
        return $this->activeChildren()->exists() && 
               $this->activeChildren()->filter(function ($child) {
                   return $child->isActiveRoute() || $child->hasActiveChildren();
               })->isNotEmpty();
    }

    /**
     * Get breadcrumb trail for this menu item
     */
    public function getBreadcrumb()
    {
        $breadcrumb = collect([$this]);
        
        $parent = $this->parent;
        while ($parent) {
            $breadcrumb->prepend($parent);
            $parent = $parent->parent;
        }

        return $breadcrumb;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menu) {
            if (is_null($menu->sort_order)) {
                $menu->sort_order = static::where('parent_id', $menu->parent_id)->max('sort_order') + 1;
            }
        });
    }
}
