<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmUserRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_id',
        'achieved_at',
        'qualification_volume',
        'qualification_details',
        'is_current',
        'maintained_until',
        'lost_at',
        'loss_reason',
        'qualification_period_start',
        'qualification_period_end',
        'maintenance_due_date',
        'rank_points_earned',
        'bonus_earned',
        'recognition_earned',
        'notes'
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
        'maintained_until' => 'datetime',
        'lost_at' => 'datetime',
        'qualification_period_start' => 'date',
        'qualification_period_end' => 'date',
        'maintenance_due_date' => 'date',
        'qualification_volume' => 'decimal:2',
        'qualification_details' => 'array',
        'rank_points_earned' => 'decimal:2',
        'bonus_earned' => 'decimal:2',
        'recognition_earned' => 'array',
        'is_current' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rank()
    {
        return $this->belongsTo(MlmRank::class);
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRank($query, $rankId)
    {
        return $query->where('rank_id', $rankId);
    }

    public function scopeAchievedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('achieved_at', [$startDate, $endDate]);
    }

    // Methods
    public function makeNotCurrent()
    {
        $this->is_current = false;
        $this->lost_at = now();
        $this->save();

        return $this;
    }

    public function makeCurrent()
    {
        // Make all other ranks for this user not current
        static::where('user_id', $this->user_id)
              ->where('id', '!=', $this->id)
              ->update(['is_current' => false]);

        $this->is_current = true;
        $this->lost_at = null;
        $this->save();

        return $this;
    }

    public function extendMaintenance($months = 1)
    {
        $this->maintenance_due_date = $this->maintenance_due_date->addMonths($months);
        $this->save();

        return $this;
    }
}
