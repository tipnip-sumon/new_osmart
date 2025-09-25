<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'minimum',
        'maximum',
        'fixed_amount',
        'points',
        'minimum_points',
        'point_value',
        'spot_commission_rate',
        'fixed_sponsor',
        'instant_activation',
        'point_based',
        'interest',
        'interest_type',
        'time',
        'time_name',
        'status',
        'featured',
        'capital_back',
        'lifetime',
        'repeat_time',
        'description',
        'image',
        'image_data',
        'binary_left',
        'binary_right',
        'direct_commission',
        'level_commission',
        'is_active',
        // Daily cashback fields
        'daily_cashback_enabled',
        'daily_cashback_min',
        'daily_cashback_max',
        'cashback_duration_days',
        'cashback_type',
        'is_special_package',
        'referral_conditions',
        'require_referral_for_cashback',
        // Additional point system fields
        'points_reward',
        'point_price',
        'maximum_points',
        'wallet_purchase',
        'point_purchase',
        'sponsor_commission',
        'generation_commission',
        'binary_matching',
        'category',
        'features',
        'purchase_type',
        'max_purchases_per_user',
        'point_to_taka_rate',
        'point_terms',
        'sort_order',
        'is_popular',
    ];

    protected $casts = [
        'id' => 'integer',
        'minimum' => 'decimal:2',
        'maximum' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'points' => 'integer',
        'minimum_points' => 'integer',
        'point_value' => 'decimal:2',
        'spot_commission_rate' => 'decimal:2',
        'fixed_sponsor' => 'decimal:2',
        'instant_activation' => 'boolean',
        'point_based' => 'boolean',
        'interest' => 'decimal:2',
        'time' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
        'capital_back' => 'boolean',
        'lifetime' => 'boolean',
        'repeat_time' => 'integer',
        'binary_left' => 'decimal:2',
        'binary_right' => 'decimal:2',
        'direct_commission' => 'decimal:2',
        'level_commission' => 'decimal:2',
        'is_active' => 'boolean',
        // Daily cashback casts
        'daily_cashback_enabled' => 'boolean',
        'daily_cashback_min' => 'decimal:2',
        'daily_cashback_max' => 'decimal:2',
        'cashback_duration_days' => 'integer',
        'is_special_package' => 'boolean',
        'referral_conditions' => 'array',
        'require_referral_for_cashback' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Get investments for this plan
     */
    public function invests()
    {
        return $this->hasMany(Invest::class);
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for featured plans
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }

    /**
     * Scope for lifetime plans
     */
    public function scopeLifetime($query)
    {
        return $query->where('lifetime', 1);
    }

    /**
     * Get formatted interest rate
     */
    public function getFormattedInterestAttribute()
    {
        if ($this->interest_type) {
            return number_format($this->interest, 2) . '%';
        } else {
            return $this->formatCurrency($this->interest);
        }
    }

    /**
     * Get formatted minimum amount
     */
    public function getFormattedMinimumAttribute()
    {
        return $this->formatCurrency($this->minimum);
    }

    /**
     * Get formatted maximum amount
     */
    public function getFormattedMaximumAttribute()
    {
        return $this->formatCurrency($this->maximum);
    }

    /**
     * Get formatted fixed amount
     */
    public function getFormattedFixedAmountAttribute()
    {
        return $this->formatCurrency($this->fixed_amount);
    }

    /**
     * Format currency using BD Taka
     */
    public function formatCurrency($amount)
    {
        $symbol = config('currency.currency_symbol', '৳');
        $position = config('currency.symbol_position', 'before');
        $decimals = config('currency.decimal_places', 2);
        $decimalSeparator = config('currency.decimal_separator', '.');
        $thousandsSeparator = config('currency.thousands_separator', ',');
        
        $formattedAmount = number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);
        
        if ($position === 'after') {
            return $formattedAmount . ' ' . $symbol;
        } else {
            return $symbol . ' ' . $formattedAmount;
        }
    }

    /**
     * Format currency in Bangladeshi style (Lakh/Crore)
     */
    public function formatCurrencyBangladeshi($amount)
    {
        $symbol = config('currency.currency_symbol', '৳');
        
        if ($amount >= 10000000) { // 1 Crore
            $crores = $amount / 10000000;
            return $symbol . ' ' . number_format($crores, 2) . ' Cr';
        } elseif ($amount >= 100000) { // 1 Lakh
            $lakhs = $amount / 100000;
            return $symbol . ' ' . number_format($lakhs, 2) . ' L';
        } elseif ($amount >= 1000) { // 1 Thousand
            $thousands = $amount / 1000;
            return $symbol . ' ' . number_format($thousands, 2) . 'K';
        } else {
            return $symbol . ' ' . number_format($amount, 2);
        }
    }

    /**
     * Get plan duration in readable format
     */
    public function getDurationAttribute()
    {
        if ($this->lifetime) {
            return 'Lifetime';
        }
        
        return $this->time . ' ' . $this->time_name;
    }

    /**
     * Check if plan uses fixed amount
     */
    public function isFixedAmount()
    {
        return $this->fixed_amount > 0;
    }

    /**
     * Check if plan is percentage based
     */
    public function isPercentageBased()
    {
        return $this->interest_type == 1;
    }

    /**
     * Calculate potential return for given amount
     */
    public function calculateReturn($amount = null)
    {
        $investAmount = $amount ?: $this->fixed_amount;
        
        if ($this->isPercentageBased()) {
            return ($investAmount * $this->interest) / 100;
        } else {
            return $this->interest;
        }
    }

    /**
     * Get total potential profit for given amount
     */
    public function getTotalPotentialProfit($amount = null)
    {
        if ($this->lifetime) {
            return 'Unlimited'; // Lifetime plans have unlimited potential
        }
        
        $dailyReturn = $this->calculateReturn($amount);
        $totalDays = $this->getTotalDays();
        
        return $dailyReturn * $totalDays;
    }

    /**
     * Get total days for the plan
     */
    public function getTotalDays()
    {
        if ($this->lifetime) {
            return 0; // Unlimited
        }
        
        switch ($this->time_name) {
            case 'days':
                return (int) $this->time;
            case 'weeks':
                return (int) $this->time * 7;
            case 'months':
                return (int) $this->time * 30;
            case 'years':
                return (int) $this->time * 365;
            default:
                return (int) $this->time;
        }
    }

    /**
     * Check if amount is within plan limits
     */
    public function isAmountValid($amount)
    {
        if ($this->isFixedAmount()) {
            return $amount == $this->fixed_amount;
        }
        
        return $amount >= $this->minimum && $amount <= $this->maximum;
    }

    /**
     * Check if user has enough points to activate this package
     */
    public function canActivateWithPoints($userPoints)
    {
        $requiredPoints = $this->minimum_points ?? $this->points ?? 0;
        return $userPoints >= $requiredPoints;
    }

    /**
     * Get required points for activation
     */
    public function getRequiredPointsAttribute()
    {
        return $this->minimum_points ?? $this->points_reward ?? 0;
    }

    /**
     * Check if this is a point-based package
     */
    public function isPointBased()
    {
        return $this->point_based || ($this->minimum_points ?? $this->points_reward ?? 0) > 0;
    }

    /**
     * Get formatted required points
     */
    public function getFormattedRequiredPointsAttribute()
    {
        return number_format($this->getRequiredPointsAttribute()) . ' Points';
    }

    /**
     * Scope for point-based plans
     */
    public function scopePointBased($query)
    {
        return $query->where('point_based', true)
                    ->orWhereNotNull('minimum_points')
                    ->orWhereNotNull('points_reward');
    }

    /**
     * Get commission rates array
     */
    public function getCommissionRatesAttribute()
    {
        return [
            'binary_left' => $this->binary_left ?? 0,
            'binary_right' => $this->binary_right ?? 0,
            'direct_commission' => $this->direct_commission ?? 0,
            'level_commission' => $this->level_commission ?? 0,
        ];
    }

    /**
     * Scope for cashback enabled plans
     */
    public function scopeCashbackEnabled($query)
    {
        return $query->where('daily_cashback_enabled', true);
    }

    /**
     * Scope for special packages
     */
    public function scopeSpecialPackages($query)
    {
        return $query->where('is_special_package', true);
    }

    /**
     * Calculate daily cashback amount for this plan
     */
    public function calculateDailyCashback()
    {
        if (!$this->daily_cashback_enabled) {
            return 0;
        }

        if ($this->cashback_type === 'random') {
            return rand(
                (int)($this->daily_cashback_min * 100),
                (int)($this->daily_cashback_max * 100)
            ) / 100;
        }

        return $this->daily_cashback_min;
    }

    /**
     * Check if plan is still eligible for cashback
     */
    public function isEligibleForCashback($activationDate)
    {
        if (!$this->daily_cashback_enabled) {
            return false;
        }

        // If duration is 0, it's unlimited
        if ($this->cashback_duration_days == 0) {
            return true;
        }

        $daysPassed = now()->diffInDays($activationDate);
        return $daysPassed < $this->cashback_duration_days;
    }

    /**
     * Check if user meets referral conditions for cashback
     */
    public function userMeetsReferralConditions($userId)
    {
        if (!$this->require_referral_for_cashback) {
            return true;
        }

        if (empty($this->referral_conditions)) {
            return true;
        }

        $conditions = $this->referral_conditions;
        $user = User::find($userId);
        
        if (!$user) {
            return false;
        }
        
        // Check direct referrals count
        if (isset($conditions['direct_referrals'])) {
            $directReferrals = User::where('sponsor_id', $userId)->count();
            if ($directReferrals < $conditions['direct_referrals']) {
                return false;
            }
        }
        
        // Check team size 
        if (isset($conditions['team_size'])) {
            $teamSize = $this->calculateTeamSize($userId);
            if ($teamSize < $conditions['team_size']) {
                return false;
            }
        }
        
        // Check minimum investment in team
        if (isset($conditions['min_investment'])) {
            $teamInvestment = $this->calculateTeamInvestment($userId);
            if ($teamInvestment < $conditions['min_investment']) {
                return false;
            }
        }
        
        // Check time limit (days since package activation)
        if (isset($conditions['time_limit'])) {
            $userPackage = $user->activePackages()
                               ->where('plan_id', $this->id)
                               ->first();
            
            if ($userPackage) {
                $daysSinceActivation = now()->diffInDays($userPackage->created_at);
                if ($daysSinceActivation > $conditions['time_limit']) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Calculate team size (all downline members)
     */
    private function calculateTeamSize($userId)
    {
        // Get direct referrals
        $directReferrals = User::where('sponsor_id', $userId)->pluck('id')->toArray();
        $teamSize = count($directReferrals);
        
        // Recursively get indirect referrals (simple 2-level check for performance)
        foreach ($directReferrals as $referralId) {
            $teamSize += User::where('sponsor_id', $referralId)->count();
        }
        
        return $teamSize;
    }

    /**
     * Calculate total team investment (in points for point-based system)
     */
    private function calculateTeamInvestment($userId)
    {
        // Get all team members
        $teamMembers = collect();
        
        // Direct referrals
        $directReferrals = User::where('sponsor_id', $userId)->get();
        $teamMembers = $teamMembers->merge($directReferrals);
        
        // Indirect referrals (2 levels)
        foreach ($directReferrals as $member) {
            $indirectReferrals = User::where('sponsor_id', $member->id)->get();
            $teamMembers = $teamMembers->merge($indirectReferrals);
        }
        
        // Calculate total point investments (activation points used by team)
        $totalPointInvestment = 0;
        foreach ($teamMembers as $member) {
            $memberInvestments = $member->activePackages()
                                      ->with('plan')
                                      ->get();
            
            foreach ($memberInvestments as $investment) {
                // For point-based system, use points_allocated (activation points used)
                // Fallback to plan's minimum_points if points_allocated is not available
                $pointsInvested = $investment->points_allocated ?? 
                                $investment->plan->minimum_points ?? 
                                $investment->plan->points_reward ?? 0;
                
                $totalPointInvestment += $pointsInvested;
            }
        }
        
        return $totalPointInvestment;
    }

    /**
     * Get referral conditions description for display
     */
    public function getReferralConditionsDescription()
    {
        if (!$this->require_referral_for_cashback || empty($this->referral_conditions)) {
            return 'No referral requirements';
        }

        $conditions = $this->referral_conditions;
        $requirements = [];
        
        if (isset($conditions['direct_referrals'])) {
            $requirements[] = $conditions['direct_referrals'] . ' direct referrals';
        }
        
        if (isset($conditions['team_size'])) {
            $requirements[] = $conditions['team_size'] . ' total team members';
        }
        
        if (isset($conditions['min_investment'])) {
            $requirements[] = number_format($conditions['min_investment']) . ' points team investment';
        }
        
        if (isset($conditions['time_limit'])) {
            $requirements[] = 'within ' . $conditions['time_limit'] . ' days';
        }
        
        return 'Requires: ' . implode(', ', $requirements);
    }

    /**
     * Set default referral conditions (example: 2 referrals with 100+ points OR 1 referral with 200+ points)
     */
    public function setDefaultReferralConditions()
    {
        $this->referral_conditions = [
            [
                'count' => 2,
                'points' => 100,
                'operator' => 'gte'
            ],
            [
                'count' => 1,
                'points' => 200,
                'operator' => 'gte'
            ]
        ];
        $this->require_referral_for_cashback = true;
        $this->save();
    }

    /**
     * Get daily cashbacks for this plan
     */
    public function dailyCashbacks()
    {
        return $this->hasMany(UserDailyCashback::class);
    }

    /**
     * Check if package is upgradable from given tier
     */
    public function isUpgradableFrom($currentTier)
    {
        $requiredPoints = $this->getRequiredPointsAttribute();
        return $requiredPoints > $currentTier;
    }
}
