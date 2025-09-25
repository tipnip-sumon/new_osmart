<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\UserDailyCashback;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DailyCashbackController extends Controller
{
    /**
     * Display the daily cashback dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's cashback-enabled packages (with defensive coding)
        $cashbackPackages = collect();
        try {
            $cashbackPackages = $user->activePackages()
                ->whereHas('plan', function ($query) {
                    $query->where('daily_cashback_enabled', true);
                })
                ->with('plan')
                ->get();
        } catch (\Exception $e) {
            // Log error and continue with empty collection
            Log::warning('Error fetching active packages for user ' . $user->id . ': ' . $e->getMessage());
        }

        // Get all user's daily cashbacks
        $allCashbacks = UserDailyCashback::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('cashback_date', 'desc')
            ->paginate(20);

        // Calculate statistics
        $stats = $this->calculateCashbackStats($user->id);
        
        // Get recent cashback transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->where('type', 'daily_cashback')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Check referral progress for each package
        $referralProgress = [];
        foreach ($cashbackPackages as $package) {
            if (isset($package->plan) && $package->plan->require_referral_for_cashback) {
                $referralProgress[$package->plan_id] = $this->calculateReferralProgress($user->id, $package->plan);
            }
        }

        return view('member.daily-cashback.dashboard', compact(
            'cashbackPackages',
            'allCashbacks',
            'stats',
            'recentTransactions',
            'referralProgress'
        ));
    }

    /**
     * Display the daily cashback history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = UserDailyCashback::where('user_id', $user->id)
            ->with('plan');

        // Filter by plan if provided
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('cashback_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('cashback_date', '<=', $request->date_to);
        }

        $cashbacks = $query->orderBy('cashback_date', 'desc')
                          ->paginate(20);

        // Get user's plans that have cashbacks for the dropdown filter
        $userPlans = Plan::whereHas('dailyCashbacks', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get(['id', 'name']);

        return view('member.daily-cashback.history', compact('cashbacks', 'userPlans'));
    }

    /**
     * Display pending cashbacks
     */
    public function pending()
    {
        $user = Auth::user();
        
        // Get pending cashbacks with referral requirements
        $pendingCashbacks = UserDailyCashback::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['plan'])
            ->orderBy('cashback_date', 'desc')
            ->paginate(20);

        // Calculate total pending amount
        $totalPendingAmount = UserDailyCashback::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('cashback_amount');

        // Calculate referral progress for each pending cashback
        $referralProgress = [];
        foreach ($pendingCashbacks as $cashback) {
            if ($cashback->plan && $cashback->plan->require_referral_for_cashback) {
                $referralProgress[$cashback->plan_id] = $this->calculateReferralProgress($user->id, $cashback->plan);
            }
        }

        return view('member.daily-cashback.pending', compact(
            'pendingCashbacks', 
            'referralProgress',
            'totalPendingAmount'
        ));
    }

    /**
     * Calculate cashback statistics for a user
     */
    private function calculateCashbackStats($userId)
    {
        $totalEarned = UserDailyCashback::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('cashback_amount');

        $totalPending = UserDailyCashback::where('user_id', $userId)
            ->where('status', 'pending')
            ->sum('cashback_amount');

        $thisMonthEarned = UserDailyCashback::where('user_id', $userId)
            ->where('status', 'paid')
            ->whereMonth('cashback_date', now()->month)
            ->whereYear('cashback_date', now()->year)
            ->sum('cashback_amount');

        $todayEarned = UserDailyCashback::where('user_id', $userId)
            ->where('status', 'paid')
            ->whereDate('cashback_date', today())
            ->sum('cashback_amount');

        // Calculate total days with cashbacks
        $totalDays = UserDailyCashback::where('user_id', $userId)
            ->where('status', 'paid')
            ->distinct('cashback_date')
            ->count();

        // Calculate average daily cashback
        $averageDaily = $totalDays > 0 ? $totalEarned / $totalDays : 0;

        return [
            'total_earned' => $totalEarned,
            'total_pending' => $totalPending,
            'this_month_earned' => $thisMonthEarned,
            'today_earned' => $todayEarned,
            'average_daily' => $averageDaily,
            'total_days' => $totalDays,
            // Keep old key for backward compatibility
            'pending_amount' => $totalPending,
        ];
    }

    /**
     * Calculate referral progress for a specific plan
     */
    private function calculateReferralProgress($userId, $plan)
    {
        if (!$plan->require_referral_for_cashback) {
            return [
                'required' => 0,
                'current' => 0,
                'percentage' => 100,
                'met' => true
            ];
        }

        // Get direct referrals count
        $directReferrals = User::where('sponsor_id', $userId)
            ->whereHas('activePackages')
            ->count();

        // Calculate team referrals if needed
        $teamReferrals = $this->calculateTeamReferrals($userId);

        $currentReferrals = max($directReferrals, $teamReferrals);
        $requiredReferrals = $plan->required_referrals_for_cashback ?? 0;

        $percentage = $requiredReferrals > 0 ? min(100, ($currentReferrals / $requiredReferrals) * 100) : 100;

        return [
            'required' => $requiredReferrals,
            'current' => $currentReferrals,
            'percentage' => $percentage,
            'met' => $currentReferrals >= $requiredReferrals
        ];
    }

    /**
     * Calculate total team referrals (including sub-levels)
     */
    private function calculateTeamReferrals($userId, $depth = 0, $maxDepth = 5)
    {
        if ($depth >= $maxDepth) {
            return 0;
        }

        $directReferrals = User::where('sponsor_id', $userId)
            ->whereHas('activePackages')
            ->pluck('id');

        $totalTeam = $directReferrals->count();

        // Add sub-level referrals
        foreach ($directReferrals as $referralId) {
            $totalTeam += $this->calculateTeamReferrals($referralId, $depth + 1, $maxDepth);
        }

        return $totalTeam;
    }
}
