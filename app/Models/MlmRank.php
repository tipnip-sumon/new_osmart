<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'description',
        'requirements',
        'benefits',
        'personal_volume_required',
        'group_volume_required',
        'binary_volume_required',
        'team_size_required',
        'active_legs_required',
        'qualification_period_months',
        'maintenance_volume_required',
        'maintenance_period_months',
        'bonus_percentage',
        'leadership_bonus_percentage',
        'car_bonus_amount',
        'travel_bonus_amount',
        'recognition_rewards',
        'rank_advancement_bonus',
        'compression_eligible',
        'override_levels',
        'icon',
        'color',
        'is_active'
    ];

    protected $casts = [
        'requirements' => 'array',
        'benefits' => 'array',
        'personal_volume_required' => 'decimal:2',
        'group_volume_required' => 'decimal:2',
        'binary_volume_required' => 'decimal:2',
        'maintenance_volume_required' => 'decimal:2',
        'bonus_percentage' => 'decimal:2',
        'leadership_bonus_percentage' => 'decimal:2',
        'car_bonus_amount' => 'decimal:2',
        'travel_bonus_amount' => 'decimal:2',
        'recognition_rewards' => 'array',
        'rank_advancement_bonus' => 'decimal:2',
        'compression_eligible' => 'boolean',
        'override_levels' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function userRanks()
    {
        return $this->hasMany(MlmUserRank::class, 'rank_id');
    }

    public function currentUsers()
    {
        return $this->hasMany(MlmUserRank::class, 'rank_id')
                   ->where('is_current', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOrderedByLevel($query)
    {
        return $query->orderBy('level');
    }

    // Methods
    public function checkQualification($user)
    {
        // This would implement the logic to check if a user qualifies for this rank
        // Based on the requirements defined in the rank
        return true; // Placeholder
    }

    public function getNextRank()
    {
        return static::where('level', '>', $this->level)
                    ->where('is_active', true)
                    ->orderBy('level')
                    ->first();
    }

    public function getPreviousRank()
    {
        return static::where('level', '<', $this->level)
                    ->where('is_active', true)
                    ->orderByDesc('level')
                    ->first();
    }
}
