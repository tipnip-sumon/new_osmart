<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'mobile_image',
        'link_url',
        'link_text',
        'position',
        'type',
        'status',
        'sort_order',
        'start_date',
        'end_date',
        'target_audience',
        'device_targeting',
        'click_count',
        'impression_count',
        'conversion_count',
        'background_color',
        'text_color',
        'button_color',
        'button_text_color',
        'overlay_opacity',
        'animation_type',
        'display_duration',
        'auto_close',
        'show_close_button',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'auto_close' => 'boolean',
        'show_close_button' => 'boolean',
        'click_count' => 'integer',
        'impression_count' => 'integer',
        'conversion_count' => 'integer',
        'overlay_opacity' => 'decimal:2',
        'display_duration' => 'integer',
        'target_audience' => 'array',
        'device_targeting' => 'array',
        'metadata' => 'array'
    ];

    // Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // If the image path already contains the full path structure, use it as is
        if (strpos($this->image, '/') !== false) {
            return asset('storage/' . $this->image);
        }

        // For legacy data with just filename, try to find the image in the expected structure
        $year = date('Y');
        $month = date('m');
        $possiblePaths = [
            "banners/{$year}/{$month}/desktop/{$this->image}",
            "banners/{$this->image}",
            $this->image
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }

        return null;
    }

    public function getMobileImageUrlAttribute()
    {
        if (!$this->mobile_image) {
            return null;
        }

        // If the mobile_image path already contains the full path structure, use it as is
        if (strpos($this->mobile_image, '/') !== false) {
            return asset('storage/' . $this->mobile_image);
        }

        // For legacy data with just filename, try to find the image in the expected structure
        $year = date('Y');
        $month = date('m');
        $possiblePaths = [
            "banners/{$year}/{$month}/mobile/{$this->mobile_image}",
            "banners/mobile/{$this->mobile_image}",
            $this->mobile_image
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }

        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeScheduled($query)
    {
        return $query->where(function($q) {
            $q->where('start_date', '<=', now())
              ->where(function($q2) {
                  $q2->whereNull('end_date')
                     ->orWhere('end_date', '>=', now());
              });
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-danger',
            'scheduled' => 'badge-warning',
            'expired' => 'badge-secondary'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    public function getClickThroughRateAttribute()
    {
        if ($this->impression_count == 0) {
            return 0;
        }
        return round(($this->click_count / $this->impression_count) * 100, 2);
    }

    public function getConversionRateAttribute()
    {
        if ($this->click_count == 0) {
            return 0;
        }
        return round(($this->conversion_count / $this->click_count) * 100, 2);
    }

    // Methods
    public static function getPositions()
    {
        return [
            'header' => 'Header',
            'hero' => 'Hero Section',
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
            'popup' => 'Popup',
            'category_top' => 'Category Top',
            'category_bottom' => 'Category Bottom',
            'product_detail' => 'Product Detail',
            'checkout' => 'Checkout',
            'floating' => 'Floating'
        ];
    }

    public static function getTypes()
    {
        return [
            'promotional' => 'Promotional',
            'informational' => 'Informational',
            'seasonal' => 'Seasonal',
            'product_showcase' => 'Product Showcase',
            'newsletter' => 'Newsletter Signup',
            'social_media' => 'Social Media',
            'announcement' => 'Announcement'
        ];
    }

    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'scheduled' => 'Scheduled',
            'expired' => 'Expired'
        ];
    }

    public static function getAnimationTypes()
    {
        return [
            'none' => 'None',
            'fade' => 'Fade',
            'slide_up' => 'Slide Up',
            'slide_down' => 'Slide Down',
            'slide_left' => 'Slide Left',
            'slide_right' => 'Slide Right',
            'zoom' => 'Zoom',
            'bounce' => 'Bounce'
        ];
    }

    public function isActive()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }
        
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }
        
        return true;
    }

    public function incrementImpressions()
    {
        $this->increment('impression_count');
    }

    public function incrementClicks()
    {
        $this->increment('click_count');
    }

    public function incrementConversions()
    {
        $this->increment('conversion_count');
    }

    public function shouldShowForDevice($device)
    {
        if (empty($this->device_targeting)) {
            return true;
        }
        
        return in_array($device, $this->device_targeting);
    }

    public function shouldShowForUser($user = null)
    {
        if (empty($this->target_audience)) {
            return true;
        }
        
        // Add logic for user targeting based on your requirements
        return true;
    }
}
