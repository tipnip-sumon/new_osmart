<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BinaryRankAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_name',
        'rank_level',
        'required_left_points',
        'required_right_points',
        'matching_tk',
        'point_10_percent',
        'tour_reward',
        'gift_reward',
        'salary_amount',
        'duration_months',
        'monthly_left_points',
        'monthly_right_points',
        'monthly_matching_tk',
        'is_achieved',
        'achieved_at',
        'is_current_rank',
        'monthly_qualified',
        'last_qualified_month',
        'consecutive_qualified_months',
        'total_matching_bonus',
        'total_salary_paid',
        'salary_months_paid',
        'salary_qualification_start_date',
        'salary_eligible',
        'salary_eligible_date',
        'qualification_days_remaining',
        'qualification_monthly_tracking',
        'qualification_period_active'
    ];

    protected $casts = [
        'required_left_points' => 'decimal:2',
        'required_right_points' => 'decimal:2',
        'matching_tk' => 'decimal:2',
        'point_10_percent' => 'decimal:2',
        'salary_amount' => 'decimal:2',
        'monthly_left_points' => 'decimal:2',
        'monthly_right_points' => 'decimal:2',
        'monthly_matching_tk' => 'decimal:2',
        'total_matching_bonus' => 'decimal:2',
        'total_salary_paid' => 'decimal:2',
        'is_achieved' => 'boolean',
        'is_current_rank' => 'boolean',
        'monthly_qualified' => 'boolean',
        'achieved_at' => 'datetime',
        'last_qualified_month' => 'date',
        'salary_qualification_start_date' => 'datetime',
        'salary_eligible' => 'boolean',
        'salary_eligible_date' => 'datetime',
        'qualification_monthly_tracking' => 'array',
        'qualification_period_active' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rankStructure()
    {
        return $this->hasOne(BinaryRankStructure::class, 'rank_name', 'rank_name');
    }

    public function monthlyQualifications()
    {
        return $this->hasMany(MonthlyRankQualification::class, 'rank_id', 'id');
    }

    // Scopes
    public function scopeAchieved($query)
    {
        return $query->where('is_achieved', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current_rank', true);
    }

    public function scopeMonthlyQualified($query)
    {
        return $query->where('monthly_qualified', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRankLevel($query, $level)
    {
        return $query->where('rank_level', $level);
    }

    // Methods
    public function checkAchievement($leftPoints, $rightPoints)
    {
        if ($leftPoints >= $this->required_left_points && 
            $rightPoints >= $this->required_right_points && 
            !$this->is_achieved) {
            
            $this->is_achieved = true;
            $this->achieved_at = now();
            $this->save();
            
            // Set as current rank if it's the highest achieved
            $this->setAsCurrentRank();
            
            return true;
        }
        return false;
    }

    public function checkMonthlyQualification($leftPoints, $rightPoints, $month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m');
        
        $qualified = $leftPoints >= $this->monthly_left_points && 
                    $rightPoints >= $this->monthly_right_points;
        
        if ($qualified) {
            $this->monthly_qualified = true;
            $this->last_qualified_month = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            
            // Update consecutive months
            $lastMonth = Carbon::createFromFormat('Y-m', $month)->subMonth()->format('Y-m');
            if ($this->last_qualified_month && 
                $this->last_qualified_month->format('Y-m') == $lastMonth) {
                $this->consecutive_qualified_months++;
            } else {
                $this->consecutive_qualified_months = 1;
            }
            
            $this->save();
        } else {
            $this->monthly_qualified = false;
            $this->consecutive_qualified_months = 0;
            $this->save();
        }
        
        return $qualified;
    }

    public function calculateMatchingBonus($leftPoints, $rightPoints)
    {
        if (!$this->is_achieved) {
            return 0;
        }
        
        $matchedPoints = min($leftPoints, $rightPoints);
        
        // For monthly qualification, use monthly requirements
        if ($this->checkMonthlyQualification($leftPoints, $rightPoints)) {
            $matchedMonthlyPoints = min(
                max(0, $leftPoints - $this->monthly_left_points),
                max(0, $rightPoints - $this->monthly_right_points)
            );
            
            // 10% of matched points * 6 Tk per point
            $bonus = $matchedMonthlyPoints * 0.10 * 6;
            
            $this->total_matching_bonus += $bonus;
            $this->save();
            
            return $bonus;
        }
        
        return 0;
    }

    public function setAsCurrentRank()
    {
        if (!$this->is_achieved) {
            return false;
        }
        
        // Remove current rank from other ranks for this user
        static::where('user_id', $this->user_id)
              ->where('id', '!=', $this->id)
              ->update(['is_current_rank' => false]);
        
        $this->is_current_rank = true;
        $this->save();
        
        return true;
    }

    public function processMonthlySalary($month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m-01');
        
        if (!$this->is_achieved || !$this->monthly_qualified) {
            return 0;
        }
        
        // Check if salary already paid for this month
        $existingQualification = MonthlyRankQualification::where('user_id', $this->user_id)
            ->where('rank_id', $this->id)
            ->where('qualification_month', $month)
            ->first();
        
        if ($existingQualification && $existingQualification->salary_paid) {
            return 0;
        }
        
        // Create or update monthly qualification record
        $qualification = MonthlyRankQualification::updateOrCreate([
            'user_id' => $this->user_id,
            'rank_id' => $this->id,
            'qualification_month' => $month
        ], [
            'qualified' => true,
            'salary_amount' => $this->salary_amount,
            'salary_paid' => true,
            'salary_paid_at' => now(),
            'is_processed' => true,
            'processed_at' => now()
        ]);
        
        // Update total salary paid
        $this->total_salary_paid += $this->salary_amount;
        $this->salary_months_paid++;
        $this->save();
        
        return $this->salary_amount;
    }

    public function getRemainingDurationAttribute()
    {
        if (!$this->achieved_at) {
            return $this->duration_months;
        }
        
        $monthsPassed = Carbon::now()->diffInMonths($this->achieved_at);
        return max(0, $this->duration_months - $monthsPassed);
    }

    public function getIsActiveAttribute()
    {
        if (!$this->is_achieved) {
            return false;
        }
        
        return $this->remaining_duration > 0;
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->is_achieved || $this->duration_months == 0) {
            return 0;
        }
        
        $monthsPassed = Carbon::now()->diffInMonths($this->achieved_at);
        return min(100, ($monthsPassed / $this->duration_months) * 100);
    }

    // Static methods for rank processing
    public static function initializeUserRanks($userId)
    {
        $rankStructures = BinaryRankStructure::orderBy('sl_no')->get();
        
        foreach ($rankStructures as $rank) {
            static::firstOrCreate([
                'user_id' => $userId,
                'rank_name' => $rank->rank_name
            ], [
                'rank_level' => $rank->sl_no,
                'required_left_points' => $rank->left_points,
                'required_right_points' => $rank->right_points,
                'matching_tk' => $rank->matching_tk,
                'point_10_percent' => $rank->point_10_percent,
                'tour_reward' => $rank->tour,
                'gift_reward' => $rank->gift,
                'salary_amount' => $rank->salary,
                'duration_months' => $rank->duration_months,
                'monthly_left_points' => $rank->monthly_left_points,
                'monthly_right_points' => $rank->monthly_right_points,
                'monthly_matching_tk' => $rank->monthly_matching_tk
            ]);
        }
    }

    public static function processUserRankAchievements($userId, $leftPoints, $rightPoints)
    {
        $userRanks = static::forUser($userId)->orderBy('rank_level')->get();
        $achievements = [];
        
        foreach ($userRanks as $rank) {
            if ($rank->checkAchievement($leftPoints, $rightPoints)) {
                $achievements[] = $rank;
            }
        }
        
        return $achievements;
    }

    /**
     * Start salary qualification period when rank is achieved
     */
    public function startSalaryQualificationPeriod()
    {
        $this->update([
            'salary_qualification_start_date' => now(),
            'qualification_period_active' => true,
            'qualification_days_remaining' => 30,
            'salary_eligible' => false,
            'qualification_monthly_tracking' => []
        ]);
    }

    /**
     * Update qualification progress and check if user maintains monthly conditions
     */
    public function updateQualificationProgress($monthlyLeftNew, $monthlyRightNew)
    {
        if (!$this->qualification_period_active || !$this->salary_qualification_start_date) {
            return;
        }

        $daysElapsed = Carbon::parse($this->salary_qualification_start_date)->diffInDays(now());
        $daysRemaining = max(0, 30 - $daysElapsed);

        // Track monthly condition fulfillment
        $tracking = $this->qualification_monthly_tracking ?? [];
        $tracking[now()->format('Y-m-d')] = [
            'left_new' => $monthlyLeftNew,
            'right_new' => $monthlyRightNew,
            'left_required' => $this->monthly_left_points,
            'right_required' => $this->monthly_right_points,
            'conditions_met' => $monthlyLeftNew >= $this->monthly_left_points && 
                             $monthlyRightNew >= $this->monthly_right_points
        ];

        // Check if qualification period is complete
        if ($daysRemaining <= 0) {
            $this->completeQualificationPeriod();
        } else {
            $this->update([
                'qualification_days_remaining' => $daysRemaining,
                'qualification_monthly_tracking' => $tracking
            ]);
        }
    }

    /**
     * Complete qualification period and determine salary eligibility
     */
    public function completeQualificationPeriod()
    {
        $tracking = $this->qualification_monthly_tracking ?? [];
        
        // Check if user maintained monthly conditions throughout the period
        $qualificationDays = collect($tracking)->filter(function($day) {
            return $day['conditions_met'] ?? false;
        })->count();

        // User needs to maintain conditions for at least 25 out of 30 days to be eligible
        $salaryEligible = $qualificationDays >= 25;

        $this->update([
            'qualification_period_active' => false,
            'qualification_days_remaining' => 0,
            'salary_eligible' => $salaryEligible,
            'salary_eligible_date' => $salaryEligible ? now() : null
        ]);
    }

    /**
     * Check if user is eligible for salary this month
     */
    public function isEligibleForSalary()
    {
        // Must have completed qualification period and be eligible
        if (!$this->salary_eligible) {
            return false;
        }

        // Must be current rank
        if (!$this->is_current_rank) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining days in qualification period
     */
    public function getQualificationDaysRemainingAttribute()
    {
        if (!$this->qualification_period_active || !$this->salary_qualification_start_date) {
            return 0;
        }

        $daysElapsed = Carbon::parse($this->salary_qualification_start_date)->diffInDays(now());
        return max(0, 30 - $daysElapsed);
    }

    /**
     * Get qualification status message
     */
    public function getQualificationStatusAttribute()
    {
        if (!$this->is_achieved) {
            return 'Not Achieved';
        }

        if ($this->qualification_period_active) {
            return "Qualifying ({$this->qualification_days_remaining} days remaining)";
        }

        if ($this->salary_eligible) {
            return 'Salary Eligible';
        }

        return 'Qualification Failed';
    }
}