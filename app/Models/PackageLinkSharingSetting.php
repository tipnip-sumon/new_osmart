<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageLinkSharingSetting extends Model
{
    protected $fillable = [
        'plan_id',
        'package_name',
        'daily_share_limit',
        'click_reward_amount',
        'daily_earning_limit',
        'total_share_limit',
        'is_active',
        'conditions'
    ];

    protected $casts = [
        'conditions' => 'array',
        'daily_share_limit' => 'integer',
        'click_reward_amount' => 'decimal:2',
        'daily_earning_limit' => 'decimal:2',
        'total_share_limit' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the plan associated with this package setting
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the package display name (from plan or package_name)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->plan) {
            return $this->plan->name;
        }
        return ucfirst($this->package_name ?? 'Unknown Package');
    }

    /**
     * Get package price from associated plan
     */
    public function getPackagePriceAttribute()
    {
        return $this->plan ? $this->plan->price : null;
    }

    /**
     * Get settings for a specific package
     */
    public static function getForPackage($packageName)
    {
        return static::where('package_name', $packageName)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get all active package settings
     */
    public static function getAllActive()
    {
        return static::where('is_active', true)
            ->orderBy('package_name')
            ->get();
    }
}
