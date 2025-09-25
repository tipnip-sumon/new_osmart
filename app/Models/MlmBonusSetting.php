<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmBonusSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_name',
        'description',
        'setting_type',
        'value',
        'min_value',
        'max_value',
        'category',
        'subcategory',
        'level',
        'threshold_amount',
        'threshold_count',
        'calculation_method',
        'is_active',
        'is_editable',
        'requires_kyc',
        'requires_rank',
        'rank_required',
        'conditions',
        'additional_settings',
        'formula',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'threshold_amount' => 'decimal:2',
        'threshold_count' => 'integer',
        'level' => 'integer',
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'requires_kyc' => 'boolean',
        'requires_rank' => 'boolean',
        'conditions' => 'array',
        'additional_settings' => 'array',
    ];

    // Constants for categories
    const CATEGORIES = [
        'sponsor_commission' => 'Sponsor Commission',
        'binary_matching' => 'Binary Matching',
        'unilevel' => 'Unilevel Commission',
        'generation' => 'Generation Commission',
        'rank' => 'Rank Bonus',
        'club' => 'Club Bonus',
        'daily_cashback' => 'Daily Cashback',
        'leadership' => 'Leadership Bonus',
        'performance' => 'Performance Bonus',
        'loyalty' => 'Loyalty Bonus',
    ];

    const SETTING_TYPES = [
        'percentage' => 'Percentage',
        'fixed' => 'Fixed Amount',
        'boolean' => 'Boolean',
        'array' => 'Multiple Values',
    ];

    const CALCULATION_METHODS = [
        'percentage' => 'Percentage Based',
        'fixed' => 'Fixed Amount',
        'sliding_scale' => 'Sliding Scale',
        'tier_based' => 'Tier Based',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('setting_type', $type);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeRequiresKyc($query)
    {
        return $query->where('requires_kyc', true);
    }

    public function scopeRequiresRank($query)
    {
        return $query->where('requires_rank', true);
    }

    // Accessors
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getSettingTypeNameAttribute()
    {
        return self::SETTING_TYPES[$this->setting_type] ?? ucfirst($this->setting_type);
    }

    public function getCalculationMethodNameAttribute()
    {
        return self::CALCULATION_METHODS[$this->calculation_method] ?? ucfirst($this->calculation_method);
    }

    public function getFormattedValueAttribute()
    {
        if ($this->setting_type === 'percentage') {
            return $this->value . '%';
        } elseif ($this->setting_type === 'fixed') {
            return formatCurrency($this->value);
        } elseif ($this->setting_type === 'boolean') {
            return $this->value ? 'Yes' : 'No';
        }
        return $this->value;
    }
    // Methods
    public function calculateBonus($criteria = [])
    {
        if (!$this->is_active) {
            return 0;
        }

        // Check qualification criteria
        if (!$this->checkQualification($criteria)) {
            return 0;
        }

        // Calculate bonus based on type and method
        switch ($this->calculation_method) {
            case 'percentage':
                if (isset($criteria['amount'])) {
                    return ($criteria['amount'] * $this->value) / 100;
                }
                break;
            case 'fixed':
                return $this->value;
            case 'sliding_scale':
                return $this->calculateSlidingScale($criteria);
            case 'tier_based':
                return $this->calculateTierBased($criteria);
        }

        return 0;
    }

    protected function checkQualification($criteria)
    {
        // Check KYC requirement
        if ($this->requires_kyc && !($criteria['user_kyc_verified'] ?? false)) {
            return false;
        }

        // Check rank requirement
        if ($this->requires_rank && isset($criteria['user_rank'])) {
            if ($this->rank_required && $criteria['user_rank'] !== $this->rank_required) {
                return false;
            }
        }

        // Check threshold amount
        if ($this->threshold_amount && isset($criteria['amount'])) {
            if ($criteria['amount'] < $this->threshold_amount) {
                return false;
            }
        }

        // Check threshold count
        if ($this->threshold_count && isset($criteria['count'])) {
            if ($criteria['count'] < $this->threshold_count) {
                return false;
            }
        }

        // Check additional conditions
        if ($this->conditions) {
            foreach ($this->conditions as $condition => $value) {
                if (!isset($criteria[$condition]) || $criteria[$condition] !== $value) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function calculateSlidingScale($criteria)
    {
        // Implement sliding scale calculation
        $amount = $criteria['amount'] ?? 0;
        $scales = $this->additional_settings['scales'] ?? [];
        
        foreach ($scales as $scale) {
            if ($amount >= $scale['min'] && ($scale['max'] === null || $amount <= $scale['max'])) {
                if ($scale['type'] === 'percentage') {
                    return ($amount * $scale['value']) / 100;
                } else {
                    return $scale['value'];
                }
            }
        }
        
        return 0;
    }

    protected function calculateTierBased($criteria)
    {
        // Implement tier-based calculation
        $tier = $criteria['tier'] ?? 1;
        $tiers = $this->additional_settings['tiers'] ?? [];
        
        if (isset($tiers[$tier])) {
            $tierData = $tiers[$tier];
            if ($tierData['type'] === 'percentage') {
                return (($criteria['amount'] ?? 0) * $tierData['value']) / 100;
            } else {
                return $tierData['value'];
            }
        }
        
        return 0;
    }

    // Static methods for common operations
    public static function getByKey($key)
    {
        return static::where('setting_key', $key)->first();
    }

    public static function getValue($key, $default = null)
    {
        $setting = static::getByKey($key);
        return $setting ? $setting->value : $default;
    }

    public static function getByCategoryAndLevel($category, $level = null)
    {
        $query = static::where('category', $category)->active();
        
        if ($level !== null) {
            $query->where('level', $level);
        }
        
        return $query->get();
    }
}
