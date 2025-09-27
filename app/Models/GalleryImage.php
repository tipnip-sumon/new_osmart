<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'type',
        'rank',
        'achiever_name',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rank' => 'integer',
        'sort_order' => 'integer',
        'image_path' => 'array'
    ];

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute()
    {
        // Handle new JSON format
        if (is_array($this->image_path)) {
            // Return medium size if available, otherwise original
            if (isset($this->image_path['sizes']['medium']['path'])) {
                return url('direct-storage/' . $this->image_path['sizes']['medium']['path']);
            }
            if (isset($this->image_path['sizes']['original']['path'])) {
                return url('direct-storage/' . $this->image_path['sizes']['original']['path']);
            }
        }
        
        // Handle legacy string format
        if (is_string($this->image_path)) {
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return Storage::url($this->image_path);
        }
        
        return null;
    }

    /**
     * Get image URL by size
     */
    public function getImageUrl($size = 'medium')
    {
        if (is_array($this->image_path) && isset($this->image_path['sizes'][$size]['path'])) {
            return url('direct-storage/' . $this->image_path['sizes'][$size]['path']);
        }
        
        // Fallback to default image URL
        return $this->getImageUrlAttribute();
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->getImageUrl('small');
    }

    /**
     * Scope for active images
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for achievement type images
     */
    public function scopeAchievements($query)
    {
        return $query->where('type', 'achievement');
    }

    /**
     * Scope for ordering by sort order and rank
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('rank', 'asc');
    }
}
