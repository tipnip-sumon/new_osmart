<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'type',
        'calculation_type',
        'value',
        'conditions',
        'levels',
        'min_qualification',
        'max_payout',
        'max_levels',
        'is_active',
        'priority',
        'enable_multi_level',
        // Enhanced Matching Features
        'carry_forward_enabled',
        'carry_side',
        'carry_percentage',
        'carry_max_days',
        'slot_matching_enabled',
        'slot_size',
        'slot_type',
        'min_slot_volume',
        'min_slot_count',
        'auto_balance_enabled',
        'balance_ratio',
        'spillover_enabled',
        'spillover_direction',
        'flush_enabled',
        'flush_percentage',
        'daily_cap_enabled',
        'daily_cap_amount',
        'weekly_cap_enabled',
        'weekly_cap_amount',
        'matching_frequency',
        'matching_time',
        'personal_volume_required',
        'min_personal_volume',
        'both_legs_required',
        'min_left_volume',
        'min_right_volume',
        // Calculation Basis Fields
        'qualification_basis',
        'pv_calculation_basis',
        'purchase_basis',
        'personal_volume_basis',
        'leg_calculation_basis',
        'slot_volume_basis'
    ];

    protected $casts = [
        'conditions' => 'array',
        'levels' => 'array',
        'value' => 'decimal:2',
        'min_qualification' => 'decimal:2',
        'max_payout' => 'decimal:2',
        'is_active' => 'boolean',
        'enable_multi_level' => 'boolean',
        // Enhanced Matching Features
        'carry_forward_enabled' => 'boolean',
        'carry_percentage' => 'decimal:2',
        'slot_matching_enabled' => 'boolean',
        'min_slot_volume' => 'decimal:2',
        'auto_balance_enabled' => 'boolean',
        'balance_ratio' => 'decimal:2',
        'spillover_enabled' => 'boolean',
        'flush_enabled' => 'boolean',
        'flush_percentage' => 'decimal:2',
        'daily_cap_enabled' => 'boolean',
        'daily_cap_amount' => 'decimal:2',
        'weekly_cap_enabled' => 'boolean',
        'weekly_cap_amount' => 'decimal:2',
        'personal_volume_required' => 'boolean',
        'min_personal_volume' => 'decimal:2',
        'both_legs_required' => 'boolean',
        'min_left_volume' => 'decimal:2',
        'min_right_volume' => 'decimal:2'
    ];

    // Commission Types
    const TYPE_SPONSOR = 'sponsor';
    const TYPE_MATCHING = 'matching';
    const TYPE_GENERATION = 'generation';
    const TYPE_RANK = 'rank';
    const TYPE_CLUB = 'club';
    const TYPE_BINARY = 'binary';
    const TYPE_LEADERSHIP = 'leadership';
    const TYPE_AFFILIATE = 'affiliate';

    // Calculation Types
    const CALC_FIXED = 'fixed';
    const CALC_PERCENTAGE = 'percentage';

    public static function getTypes()
    {
        return [
            self::TYPE_SPONSOR => 'Sponsor Commission',
            self::TYPE_MATCHING => 'Matching Bonus',
            self::TYPE_GENERATION => 'Generation Bonus',
            self::TYPE_RANK => 'Rank Achievement Bonus',
            self::TYPE_CLUB => 'Club Bonus',
            self::TYPE_BINARY => 'Binary Commission',
            self::TYPE_LEADERSHIP => 'Leadership Bonus',
            self::TYPE_AFFILIATE => 'Affiliate Commission'
        ];
    }

    public static function getCalculationTypes()
    {
        return [
            self::CALC_FIXED => 'Fixed Amount',
            self::CALC_PERCENTAGE => 'Percentage'
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getFormattedValueAttribute()
    {
        if ($this->calculation_type === self::CALC_PERCENTAGE) {
            return $this->value . '%';
        }
        return '৳' . number_format($this->value, 2);
    }

    public function getConditionsTextAttribute()
    {
        if (empty($this->conditions) || !is_array($this->conditions)) {
            return 'No conditions';
        }

        $text = [];
        foreach ($this->conditions as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $text[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
        }
        
        return implode(', ', $text);
    }

    public function getLevelsTextAttribute()
    {
        if (empty($this->levels) || !is_array($this->levels)) {
            return 'Single level';
        }

        return count($this->levels) . ' levels configured';
    }

    // Enhanced Matching Helper Methods
    public static function getCarrySides()
    {
        return [
            'strong' => 'Strong Side',
            'weak' => 'Weak Side', 
            'both' => 'Both Sides'
        ];
    }

    public static function getSlotTypes()
    {
        return [
            'volume' => 'Volume Based',
            'count' => 'Count Based',
            'mixed' => 'Mixed (Volume + Count)'
        ];
    }

    public static function getSpilloverDirections()
    {
        return [
            'weaker' => 'To Weaker Side',
            'stronger' => 'To Stronger Side',
            'alternate' => 'Alternate Sides'
        ];
    }

    public static function getMatchingFrequencies()
    {
        return [
            'real_time' => 'Real Time',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly'
        ];
    }

    public function isCarryForwardEnabled()
    {
        return $this->carry_forward_enabled;
    }

    public function isSlotMatchingEnabled()
    {
        return $this->slot_matching_enabled;
    }

    public function hasPersonalVolumeRequirement()
    {
        return $this->personal_volume_required;
    }

    public function getMatchingConfigSummary()
    {
        $summary = [];
        
        if ($this->carry_forward_enabled) {
            $summary[] = "Carry Forward: {$this->carry_side} side";
        }
        
        if ($this->slot_matching_enabled) {
            $summary[] = "Slot Matching: {$this->slot_size} {$this->slot_type}";
        }
        
        if ($this->flush_enabled) {
            $summary[] = "Flush: {$this->flush_percentage}%";
        }
        
        if ($this->daily_cap_enabled) {
            $summary[] = "Daily Cap: ৳" . number_format($this->daily_cap_amount, 2);
        }
        
        return empty($summary) ? 'Standard matching' : implode(', ', $summary);
    }
}
