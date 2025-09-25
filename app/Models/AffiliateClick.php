<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'user_id',
        'product_id',
        'ip_address',
        'cookie_id',
        'user_agent',
        'referrer',
        'clicked_at',
        'session_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content'
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    /**
     * Get the user that owns the affiliate click
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was clicked
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get clicks from today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('clicked_at', today());
    }

    /**
     * Scope to get clicks from this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('clicked_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    /**
     * Scope to get clicks by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('clicked_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted clicked at date
     */
    public function getFormattedClickedAtAttribute()
    {
        return $this->clicked_at->format('M d, Y H:i:s');
    }

    /**
     * Get browser from user agent
     */
    public function getBrowserAttribute()
    {
        if (strpos($this->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($this->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($this->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($this->user_agent, 'Edge') !== false) {
            return 'Edge';
        } else {
            return 'Other';
        }
    }

    /**
     * Get platform from user agent
     */
    public function getPlatformAttribute()
    {
        if (strpos($this->user_agent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($this->user_agent, 'Mac') !== false) {
            return 'Mac';
        } elseif (strpos($this->user_agent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($this->user_agent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($this->user_agent, 'iOS') !== false) {
            return 'iOS';
        } else {
            return 'Other';
        }
    }
}
