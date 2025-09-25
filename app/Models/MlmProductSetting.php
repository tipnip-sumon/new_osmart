<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmProductSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'pv_points',
        'bv_points', 
        'cv_points',
        'qv_points',
        'point_calculation_method',
        'point_percentage',
        'point_tiers',
        'is_starter_kit',
        'is_autoship_eligible',
        'generates_commission',
        'requires_qualification',
        'minimum_rank_required',
        'minimum_volume_required',
        'counts_towards_qualification',
        'max_purchase_per_month',
        'max_purchase_per_order',
        'first_order_only',
        'days_between_purchases',
        'placement_type',
        'affects_binary_tree',
        'left_leg_points',
        'right_leg_points',
        'recognition_points',
        'achievement_rewards',
        'contributes_to_rank_advancement',
        'mlm_launch_date',
        'mlm_end_date',
        'grandfathered_commissions'
    ];

    protected $casts = [
        'is_starter_kit' => 'boolean',
        'is_autoship_eligible' => 'boolean',
        'generates_commission' => 'boolean',
        'requires_qualification' => 'boolean',
        'counts_towards_qualification' => 'boolean',
        'first_order_only' => 'boolean',
        'affects_binary_tree' => 'boolean',
        'contributes_to_rank_advancement' => 'boolean',
        'grandfathered_commissions' => 'boolean',
        'pv_points' => 'decimal:2',
        'bv_points' => 'decimal:2',
        'cv_points' => 'decimal:2',
        'qv_points' => 'decimal:2',
        'point_percentage' => 'decimal:2',
        'minimum_volume_required' => 'decimal:2',
        'left_leg_points' => 'decimal:2',
        'right_leg_points' => 'decimal:2',
        'point_tiers' => 'array',
        'achievement_rewards' => 'array',
        'mlm_launch_date' => 'date',
        'mlm_end_date' => 'date'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function commissions()
    {
        return $this->hasMany(MlmCommission::class, 'product_id', 'product_id');
    }
}
