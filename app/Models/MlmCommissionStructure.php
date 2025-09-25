<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmCommissionStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'percentage',
        'amount',
        'min_volume',
        'max_volume',
        'min_rank_level',
        'max_rank_level',
        'generation_limit',
        'qualification_requirements',
        'payout_frequency',
        'payout_day',
        'holdback_period_days',
        'cap_type',
        'cap_amount',
        'cap_period',
        'compression_rules',
        'matching_requirements',
        'binary_rules',
        'effective_from',
        'effective_until',
        'is_active'
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'min_volume' => 'decimal:2',
        'max_volume' => 'decimal:2',
        'cap_amount' => 'decimal:2',
        'qualification_requirements' => 'array',
        'compression_rules' => 'array',
        'matching_requirements' => 'array',
        'binary_rules' => 'array',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeEffective($query, $date = null)
    {
        $date = $date ?? now()->toDateString();
        
        return $query->where('effective_from', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('effective_until')
                          ->orWhere('effective_until', '>=', $date);
                    });
    }

    // Methods
    public function isEffective($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        if ($this->effective_from > $date) {
            return false;
        }
        
        if ($this->effective_until && $this->effective_until < $date) {
            return false;
        }
        
        return $this->is_active;
    }

    public function calculateCommission($volume, $rank = null)
    {
        if (!$this->isEffective()) {
            return 0;
        }

        // Check volume requirements
        if ($this->min_volume && $volume < $this->min_volume) {
            return 0;
        }

        if ($this->max_volume && $volume > $this->max_volume) {
            $volume = $this->max_volume;
        }

        // Calculate commission
        if ($this->percentage) {
            return ($volume * $this->percentage) / 100;
        }

        return $this->amount ?? 0;
    }
}
