<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invest;
use App\Models\Plan;
use App\Models\User;
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

        return view('user.investments.index', compact('investments'));
    }

    /**
     * Show the form for creating a new investment.
     */
    public function create()
    {
        $plans = Plan::where('status', true)->get();
        return view('user.investments.create', compact('plans'));
    }

    /**
     * Store a newly created investment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0.01',
            'wallet_type' => 'required|string|in:main,interest,bonus',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $user = Auth::user();

        // Validate investment amount
        if ($request->amount < $plan->minimum || $request->amount > $plan->maximum) {
            return back()->withErrors(['amount' => 'Investment amount must be between ' . $plan->minimum . ' and ' . $plan->maximum]);
        }

        // Check user balance (you'll need to implement this based on your wallet system)
        // if ($user->getWalletBalance($request->wallet_type) < $request->amount) {
        //     return back()->withErrors(['amount' => 'Insufficient balance in ' . $request->wallet_type . ' wallet']);
        // }

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
                'period' => $plan->time,
                'hours' => $plan->time,
                'time_name' => $plan->time_name,
                'return_rec_time' => 0,
                'next_time' => now()->addHours($plan->time),
                'status' => true,
                'capital_status' => false,
                'trx' => Str::random(20),
                'wallet_type' => $request->wallet_type,
            ]);

            // Deduct amount from user wallet (implement based on your wallet system)
            // $user->deductFromWallet($request->wallet_type, $amount);
        });

        return redirect()->route('user.investments.index')->with('success', 'Investment created successfully!');
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

        return view('user.investments.show', compact('investment'));
    }

    /**
     * Get investment statistics
     */
    public function statistics()
    {
        $userId = Auth::id();

        $stats = [
            'total_invested' => Invest::where('user_id', $userId)->sum('amount'),
            'total_returned' => Invest::where('user_id', $userId)->sum('paid'),
            'active_investments' => Invest::where('user_id', $userId)->where('status', true)->count(),
            'completed_investments' => Invest::where('user_id', $userId)->where('capital_status', true)->count(),
            'total_profit' => Invest::where('user_id', $userId)->sum('interest'),
        ];

        return response()->json($stats);
    }

    /**
     * Process investment returns (this would typically be called by a cron job)
     */
    public function processReturns()
    {
        $dueInvestments = Invest::where('status', true)
            ->where('next_time', '<=', now())
            ->where('capital_status', false)
            ->get();

        foreach ($dueInvestments as $investment) {
            $this->processInvestmentReturn($investment);
        }

        return response()->json(['message' => 'Investment returns processed successfully']);
    }

    /**
     * Process individual investment return
     */
    private function processInvestmentReturn(Invest $investment)
    {
        $plan = $investment->plan;
        $returnAmount = $investment->interest / $investment->period;

        // Add return to user wallet (implement based on your wallet system)
        // $investment->user->addToWallet('interest', $returnAmount);

        // Update investment
        $investment->increment('paid', $returnAmount);
        $investment->increment('return_rec_time');
        
        // Check if investment is completed
        if ($investment->return_rec_time >= $investment->period) {
            $investment->update([
                'capital_status' => true,
                'status' => false,
            ]);

            // Return capital if plan allows
            if ($plan->capital_back) {
                // $investment->user->addToWallet('main', $investment->amount);
            }
        } else {
            // Set next return time
            $investment->update([
                'next_time' => now()->addHours($plan->time),
                'last_time' => now(),
            ]);
        }
    }
}
