<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BannerCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_url',
        'image',
        'image_data',
        'images_data',
        'show_countdown',
        'countdown_end_date',
        'background_color',
        'text_color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'countdown_end_date' => 'datetime',
        'show_countdown' => 'boolean',
        'is_active' => 'boolean',
        'image_data' => 'array',
        'images_data' => 'array'
    ];

    /**
     * Scope to get only active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/ecomus/images/collections/banner-collection-3.png');
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    /**
     * Check if countdown is still active
     */
    public function getIsCountdownActiveAttribute()
    {
        if (!$this->show_countdown || !$this->countdown_end_date) {
            return false;
        }

        return $this->countdown_end_date->isFuture();
    }

    /**
     * Get countdown timer in milliseconds
     */
    public function getCountdownTimerAttribute()
    {
        if (!$this->is_countdown_active) {
            return 0;
        }

        return $this->countdown_end_date->diffInMilliseconds(now());
    }

    /**
     * Get time remaining in human readable format
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->is_countdown_active) {
            return null;
        }

        $diff = $this->countdown_end_date->diff(now());
        
        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s
        ];
    }

    /**
     * Get all image URLs from multiple images data
     */
    public function getImageUrlsAttribute()
    {
        $urls = [];
        
        // Add main image
        if ($this->image) {
            $urls[] = $this->image_url;
        }
        
        // Add multiple images
        if ($this->images_data) {
            foreach ($this->images_data as $imageData) {
                if (isset($imageData['sizes']['large']['url'])) {
                    $urls[] = $imageData['sizes']['large']['url'];
                }
            }
        }
        
        return $urls;
    }
    
    /**
     * Get thumbnail URLs from multiple images
     */
    public function getThumbnailUrlsAttribute()
    {
        $urls = [];
        
        // Add main image thumbnail (use medium size if available)
        if ($this->image && $this->image_data) {
            if (isset($this->image_data['sizes']['thumbnail']['url'])) {
                $urls[] = $this->image_data['sizes']['thumbnail']['url'];
            } elseif (isset($this->image_data['sizes']['medium']['url'])) {
                $urls[] = $this->image_data['sizes']['medium']['url'];
            }
        }
        
        // Add multiple image thumbnails
        if ($this->images_data) {
            foreach ($this->images_data as $imageData) {
                if (isset($imageData['sizes']['thumbnail']['url'])) {
                    $urls[] = $imageData['sizes']['thumbnail']['url'];
                } elseif (isset($imageData['sizes']['medium']['url'])) {
                    $urls[] = $imageData['sizes']['medium']['url'];
                }
            }
        }
        
        return $urls;
    }
    
    /**
     * Get the best available image URL
     */
    public function getBestImageUrlAttribute()
    {
        // Check if we have processed image data
        if ($this->image_data && isset($this->image_data['sizes']['large']['url'])) {
            return $this->image_data['sizes']['large']['url'];
        }
        
        // Check multiple images
        if ($this->images_data && !empty($this->images_data)) {
            $firstImage = $this->images_data[0];
            if (isset($firstImage['sizes']['large']['url'])) {
                return $firstImage['sizes']['large']['url'];
            }
        }
        
        // Fallback to original image
        return $this->image_url;
    }
}