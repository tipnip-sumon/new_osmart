<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invest;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvestController extends Controller
{
    /**
     * Display a listing of investments.
     */
    public function index()
    {
        $investments = Invest::with(['plan', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('member.invest.index', compact('investments'));
    }

    /**
     * Show the form for creating a new investment.
     */
    public function create()
    {
        $plans = Plan::where('status', true)->get();
        return view('member.invest.create', compact('plans'));
    }

    /**
     * Store a newly created investment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0.01',
            'wallet_type' => 'required|string|in:deposit_wallet,main,interest,bonus',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Validate investment amount
        if ($request->amount < $plan->minimum || $request->amount > $plan->maximum) {
            return back()->withErrors(['amount' => 'Investment amount must be between ' . formatCurrency($plan->minimum) . ' and ' . formatCurrency($plan->maximum)]);
        }

        // Check user balance
        if (!$user->hasSufficientBalance($request->wallet_type, $request->amount)) {
            return back()->withErrors([
                'amount' => 'Insufficient balance in ' . $user->getWalletDisplayName($request->wallet_type) . 
                           '. Current balance: ' . formatCurrency($user->getWalletBalance($request->wallet_type)) . 
                           ', Required: ' . formatCurrency($request->amount)
            ]);
        }

        try {
            DB::transaction(function () use ($request, $plan, $user) {
                // Calculate investment details
                $amount = $request->amount;
                $interest = ($amount * $plan->interest) / 100;
                $shouldPay = $amount + $interest;

                // Create investment
                $invest = Invest::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'amount' => $amount,
                    'actual_paid' => $amount,
                    'token_discount' => 0,
                    'interest' => $interest,
                    'should_pay' => $shouldPay,
                    'paid' => 0,
                    'period' => (int) $plan->time,
                    'hours' => (int) $plan->time,
                    'time_name' => $plan->time_name,
                    'return_rec_time' => 0,
                    'next_time' => now()->addHours((int) $plan->time),
                    'status' => true,
                    'capital_status' => false,
                    'trx' => 'INV' . strtoupper(Str::random(15)),
                    'wallet_type' => $request->wallet_type,
                ]);

                // Deduct amount from user wallet
                $user->deductFromWallet($request->wallet_type, $amount);
            });

            return redirect()->route('member.invest.index')->with('success', 'Investment created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create investment. Please try again.']);
        }
    }

    /**
     * Display the specified investment.
     */
    public function show(Invest $investment)
    {
        // Ensure user can only view their own investments
        if ($investment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('member.invest.show', compact('investment'));
    }

    /**
     * Get investment statistics for dashboard
     */
    public function statistics()
    {
        $userId = Auth::id();

        $stats = [
            'total_invested' => Invest::where('user_id', $userId)->sum('amount'),
            'total_returned' => Invest::where('user_id', $userId)->sum('paid'),
            'active_investments' => Invest::where('user_id', $userId)->where('status', true)->where('capital_status', false)->count(),
            'completed_investments' => Invest::where('user_id', $userId)->where('capital_status', true)->count(),
            'total_profit' => Invest::where('user_id', $userId)->sum('interest'),
            'pending_returns' => Invest::where('user_id', $userId)->sum(DB::raw('should_pay - paid')),
        ];

        return response()->json($stats);
    }

    /**
     * Show investment dashboard
     */
    public function dashboard()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_invested' => Invest::where('user_id', $userId)->sum('amount'),
            'total_returned' => Invest::where('user_id', $userId)->sum('paid'),
            'active_investments' => Invest::where('user_id', $userId)->where('status', true)->where('capital_status', false)->count(),
            'completed_investments' => Invest::where('user_id', $userId)->where('capital_status', true)->count(),
        ];

        $recentInvestments = Invest::with('plan')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $activePlans = Plan::where('status', true)->limit(6)->get();

        return view('member.invest.dashboard', compact('stats', 'recentInvestments', 'activePlans'));
    }
}
