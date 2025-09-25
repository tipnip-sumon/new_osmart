<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'title',
        'description',
        'content',
        'type',
        'trigger_type',
        'trigger_value',
        'position',
        'size',
        'modal_size',
        'animation',
        'frequency',
        'priority',
        'status',
        'is_active',
        'show_once',
        'start_date',
        'end_date',
        'target_pages',
        'exclude_pages',
        'target_devices',
        'target_users',
        'target_audience',
        'device_targeting',
        'frequency_limit',
        'delay_seconds',
        'show_count',
        'click_count',
        'conversion_count',
        'close_count',
        'max_displays',
        'conversion_goal',
        'auto_close',
        'displays',
        'conversions',
        'clicks',
        'image',
        'image_data',
        'background_color',
        'text_color',
        'button_color',
        'button_text',
        'button_url',
        'close_button',
        'overlay',
        'border_color',
        'overlay_color',
        'overlay_opacity',
        'animation_in',
        'animation_out',
        'show_close_button',
        'close_delay',
        'sound_enabled',
        'cookie_lifetime',
        'metadata'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_pages' => 'array',
        'exclude_pages' => 'array',
        'target_devices' => 'array',
        'target_users' => 'array',
        'target_audience' => 'array',
        'device_targeting' => 'array',
        'image_data' => 'array',
        'is_active' => 'boolean',
        'show_once' => 'boolean',
        'close_button' => 'boolean',
        'overlay' => 'boolean',
        'show_close_button' => 'boolean',
        'auto_close' => 'boolean',
        'sound_enabled' => 'boolean',
        'show_count' => 'integer',
        'click_count' => 'integer',
        'conversion_count' => 'integer',
        'close_count' => 'integer',
        'displays' => 'integer',
        'conversions' => 'integer',
        'clicks' => 'integer',
        'max_displays' => 'integer',
        'priority' => 'integer',
        'trigger_value' => 'integer',
        'frequency_limit' => 'integer',
        'delay_seconds' => 'integer',
        'close_delay' => 'integer',
        'cookie_lifetime' => 'integer',
        'overlay_opacity' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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

    public function getConversionRateAttribute()
    {
        if ($this->show_count == 0) {
            return 0;
        }
        return round(($this->conversion_count / $this->show_count) * 100, 2);
    }

    public function getClickThroughRateAttribute()
    {
        if ($this->show_count == 0) {
            return 0;
        }
        return round(($this->click_count / $this->show_count) * 100, 2);
    }

    public function getCloseRateAttribute()
    {
        if ($this->show_count == 0) {
            return 0;
        }
        return round(($this->close_count / $this->show_count) * 100, 2);
    }

    // Methods
    public static function getTypes()
    {
        return [
            'newsletter' => 'Newsletter Signup',
            'promotional' => 'Promotional',
            'announcement' => 'Announcement',
            'age_verification' => 'Age Verification',
            'cookie_consent' => 'Cookie Consent',
            'exit_intent' => 'Exit Intent',
            'discount' => 'Discount Offer',
            'survey' => 'Survey',
            'social_proof' => 'Social Proof'
        ];
    }

    public static function getTriggerTypes()
    {
        return [
            'immediate' => 'Immediate',
            'time_delay' => 'Time Delay',
            'scroll_percentage' => 'Scroll Percentage',
            'exit_intent' => 'Exit Intent',
            'page_views' => 'Page Views',
            'return_visitor' => 'Return Visitor',
            'inactivity' => 'Inactivity'
        ];
    }

    public static function getPositions()
    {
        return [
            'center' => 'Center',
            'top_left' => 'Top Left',
            'top_right' => 'Top Right',
            'bottom_left' => 'Bottom Left',
            'bottom_right' => 'Bottom Right',
            'top_center' => 'Top Center',
            'bottom_center' => 'Bottom Center',
            'fullscreen' => 'Fullscreen'
        ];
    }

    public static function getSizes()
    {
        return [
            'small' => 'Small (400x300)',
            'medium' => 'Medium (600x400)',
            'large' => 'Large (800x600)',
            'extra_large' => 'Extra Large (1000x700)',
            'custom' => 'Custom'
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

    public function incrementShows()
    {
        $this->increment('show_count');
    }

    public function incrementClicks()
    {
        $this->increment('click_count');
    }

    public function incrementConversions()
    {
        $this->increment('conversion_count');
    }

    public function incrementCloses()
    {
        $this->increment('close_count');
    }

    public function shouldShowOnPage($page)
    {
        if (empty($this->target_pages)) {
            return true;
        }
        
        return in_array($page, $this->target_pages) || in_array('*', $this->target_pages);
    }

    public function shouldShowForDevice($device)
    {
        if (empty($this->device_targeting)) {
            return true;
        }
        
        return in_array($device, $this->device_targeting);
    }
}
