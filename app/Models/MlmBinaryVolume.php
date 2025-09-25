<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmBinaryVolume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'left_leg_volume',
        'right_leg_volume',
        'personal_volume',
        'binary_volume',
        'carry_forward_left',
        'carry_forward_right',
        'total_commissions_paid',
        'max_payout_reached',
        'volume_source',
        'calculation_date',
        'is_finalized',
        'notes'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'left_leg_volume' => 'decimal:2',
        'right_leg_volume' => 'decimal:2',
        'personal_volume' => 'decimal:2',
        'binary_volume' => 'decimal:2',
        'carry_forward_left' => 'decimal:2',
        'carry_forward_right' => 'decimal:2',
        'total_commissions_paid' => 'decimal:2',
        'max_payout_reached' => 'boolean',
        'calculation_date' => 'datetime',
        'is_finalized' => 'boolean',
        'volume_source' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function binaryTree()
    {
        return $this->belongsTo(MlmBinaryTree::class, 'user_id', 'user_id');
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->where('period_start', '>=', $startDate)
                    ->where('period_end', '<=', $endDate);
    }

    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_finalized', false);
    }

    // Methods
    public function calculateBinaryVolume()
    {
        $this->binary_volume = min($this->left_leg_volume, $this->right_leg_volume);
        $this->save();

        return $this->binary_volume;
    }

    public function calculateCarryForward()
    {
        $smallerLeg = min($this->left_leg_volume, $this->right_leg_volume);
        
        $this->carry_forward_left = max(0, $this->left_leg_volume - $smallerLeg);
        $this->carry_forward_right = max(0, $this->right_leg_volume - $smallerLeg);
        
        $this->save();

        return [
            'left' => $this->carry_forward_left,
            'right' => $this->carry_forward_right
        ];
    }

    public function finalize()
    {
        $this->is_finalized = true;
        $this->calculation_date = now();
        $this->save();

        return $this;
    }

    public function getSmallerLeg()
    {
        return $this->left_leg_volume <= $this->right_leg_volume ? 'left' : 'right';
    }

    public function getLargerLeg()
    {
        return $this->left_leg_volume > $this->right_leg_volume ? 'left' : 'right';
    }
}
