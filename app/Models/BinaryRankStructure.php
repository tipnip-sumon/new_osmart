<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinaryRankStructure extends Model
{
    use HasFactory;

    protected $table = 'binary_rank_structure';

    protected $fillable = [
        'sl_no',
        'rank_name',
        'left_points',
        'right_points',
        'matching_tk',
        'point_10_percent',
        'tour',
        'gift',
        'salary',
        'duration_months',
        'monthly_left_points',
        'monthly_right_points',
        'monthly_matching_tk',
        'is_active'
    ];

    protected $casts = [
        'left_points' => 'decimal:2',
        'right_points' => 'decimal:2',
        'matching_tk' => 'decimal:2',
        'point_10_percent' => 'decimal:2',
        'salary' => 'decimal:2',
        'monthly_left_points' => 'decimal:2',
        'monthly_right_points' => 'decimal:2',
        'monthly_matching_tk' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function userAchievements()
    {
        return $this->hasMany(BinaryRankAchievement::class, 'rank_name', 'rank_name');
    }

    public function monthlyQualifications()
    {
        return $this->hasMany(MonthlyRankQualification::class, 'rank_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderedByLevel($query)
    {
        return $query->orderBy('sl_no');
    }

    // Methods
    public function getQualificationPercentage($leftPoints, $rightPoints)
    {
        $leftPercent = $this->left_points > 0 ? min(100, ($leftPoints / $this->left_points) * 100) : 100;
        $rightPercent = $this->right_points > 0 ? min(100, ($rightPoints / $this->right_points) * 100) : 100;
        
        return min($leftPercent, $rightPercent);
    }

    public function getMonthlyQualificationPercentage($leftPoints, $rightPoints)
    {
        $leftPercent = $this->monthly_left_points > 0 ? min(100, ($leftPoints / $this->monthly_left_points) * 100) : 100;
        $rightPercent = $this->monthly_right_points > 0 ? min(100, ($rightPoints / $this->monthly_right_points) * 100) : 100;
        
        return min($leftPercent, $rightPercent);
    }

    public function isQualifiedForAchievement($leftPoints, $rightPoints)
    {
        return $leftPoints >= $this->left_points && $rightPoints >= $this->right_points;
    }

    public function isQualifiedForMonthlySalary($leftPoints, $rightPoints)
    {
        return $leftPoints >= $this->monthly_left_points && $rightPoints >= $this->monthly_right_points;
    }

    public function calculateMatchingBonus($leftPoints, $rightPoints)
    {
        $matchedPoints = min($leftPoints, $rightPoints);
        return $matchedPoints * 0.10 * 6; // 10% of matched points * 6 Tk per point
    }

    public function getFormattedRewardsAttribute()
    {
        $rewards = [];
        
        if ($this->tour && $this->tour !== 'N/A') {
            $rewards[] = $this->tour;
        }
        
        if ($this->gift && $this->gift !== 'N/A') {
            $rewards[] = $this->gift;
        }
        
        if ($this->salary > 0) {
            $rewards[] = '৳' . number_format($this->salary) . ' salary';
        }
        
        return implode(', ', $rewards);
    }

    public static function seedDefaultRanks()
    {
        $ranks = [
            [
                'sl_no' => 1,
                'rank_name' => 'Elite',
                'left_points' => 1000,
                'right_points' => 1000,
                'matching_tk' => 600,
                'point_10_percent' => 100,
                'tour' => 'N/A',
                'gift' => 'T - Shirt',
                'salary' => 2000,
                'duration_months' => 2,
                'monthly_left_points' => 500,
                'monthly_right_points' => 500,
                'monthly_matching_tk' => 300
            ],
            [
                'sl_no' => 2,
                'rank_name' => 'General Executive',
                'left_points' => 10000,
                'right_points' => 10000,
                'matching_tk' => 6000,
                'point_10_percent' => 1000,
                'tour' => 'Cox\'s bazar',
                'gift' => 'T - Shirt',
                'salary' => 5000,
                'duration_months' => 6,
                'monthly_left_points' => 1000,
                'monthly_right_points' => 1000,
                'monthly_matching_tk' => 600
            ],
            [
                'sl_no' => 3,
                'rank_name' => 'Marketing Executive',
                'left_points' => 25000,
                'right_points' => 25000,
                'matching_tk' => 15000,
                'point_10_percent' => 2500,
                'tour' => 'Sajek',
                'gift' => 'T - Shirt',
                'salary' => 10000,
                'duration_months' => 6,
                'monthly_left_points' => 3000,
                'monthly_right_points' => 3000,
                'monthly_matching_tk' => 1800
            ],
            [
                'sl_no' => 4,
                'rank_name' => 'Assistant Marketing Manager',
                'left_points' => 50000,
                'right_points' => 50000,
                'matching_tk' => 30000,
                'point_10_percent' => 5000,
                'tour' => 'Nepal',
                'gift' => 'T - Shirt',
                'salary' => 20000,
                'duration_months' => 6,
                'monthly_left_points' => 6000,
                'monthly_right_points' => 6000,
                'monthly_matching_tk' => 3600
            ],
            [
                'sl_no' => 5,
                'rank_name' => 'Marketing Manager',
                'left_points' => 100000,
                'right_points' => 100000,
                'matching_tk' => 60000,
                'point_10_percent' => 10000,
                'tour' => 'Couple Tour',
                'gift' => 'T - Shirt',
                'salary' => 30000,
                'duration_months' => 6,
                'monthly_left_points' => 9000,
                'monthly_right_points' => 9000,
                'monthly_matching_tk' => 5400
            ],
            [
                'sl_no' => 6,
                'rank_name' => 'Sr. Marketing Manager',
                'left_points' => 200000,
                'right_points' => 200000,
                'matching_tk' => 120000,
                'point_10_percent' => 20000,
                'tour' => 'N/A',
                'gift' => 'Motor Bike',
                'salary' => 50000,
                'duration_months' => 6,
                'monthly_left_points' => 20000,
                'monthly_right_points' => 20000,
                'monthly_matching_tk' => 12000
            ],
            [
                'sl_no' => 7,
                'rank_name' => 'Assistant Zonal Manager',
                'left_points' => 500000,
                'right_points' => 500000,
                'matching_tk' => 300000,
                'point_10_percent' => 50000,
                'tour' => 'N/A',
                'gift' => 'Car Fund',
                'salary' => 100000,
                'duration_months' => 6,
                'monthly_left_points' => 50000,
                'monthly_right_points' => 50000,
                'monthly_matching_tk' => 30000
            ],
            [
                'sl_no' => 8,
                'rank_name' => 'Zonal Manager',
                'left_points' => 1000000,
                'right_points' => 1000000,
                'matching_tk' => 600000,
                'point_10_percent' => 100000,
                'tour' => 'N/A',
                'gift' => 'House Fund',
                'salary' => 150000,
                'duration_months' => 6,
                'monthly_left_points' => 100000,
                'monthly_right_points' => 100000,
                'monthly_matching_tk' => 60000
            ],
            [
                'sl_no' => 9,
                'rank_name' => 'Sr. Zonal Manager',
                'left_points' => 2500000,
                'right_points' => 2500000,
                'matching_tk' => 1500000,
                'point_10_percent' => 250000,
                'tour' => 'N/A',
                'gift' => 'Fixed Deposit',
                'salary' => 200000,
                'duration_months' => 6,
                'monthly_left_points' => 150000,
                'monthly_right_points' => 150000,
                'monthly_matching_tk' => 90000
            ],
            [
                'sl_no' => 10,
                'rank_name' => 'Director',
                'left_points' => 5000000,
                'right_points' => 5000000,
                'matching_tk' => 3000000,
                'point_10_percent' => 500000,
                'tour' => 'N/A',
                'gift' => 'Company Share',
                'salary' => 250000,
                'duration_months' => 12,
                'monthly_left_points' => 200000,
                'monthly_right_points' => 200000,
                'monthly_matching_tk' => 120000
            ]
        ];

        foreach ($ranks as $rank) {
            static::updateOrCreate(
                ['rank_name' => $rank['rank_name']],
                $rank
            );
        }
    }
}