<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Plan;
use App\Models\PointTransaction;
use Carbon\Carbon;

class PointsController extends Controller
{
    /**
     * Points dashboard showing overview of user's points
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get point statistics
        $pointStats = [
            'reserve_points' => $user->reserve_points ?? 0,
            'active_points' => $user->active_points ?? 0,
            'total_points_earned' => $user->total_points_earned ?? 0,
            'points_spent' => $user->points_spent ?? 0,
        ];
        
        // Recent transactions
        $recentTransactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Monthly points chart data
        $monthlyData = PointTransaction::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as earned'),
                DB::raw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as spent')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        // Available packages for activation
        $availablePackages = Plan::where('status', 'active')
            ->where('package_price', '<=', $pointStats['reserve_points'])
            ->orderBy('package_price', 'asc')
            ->get();
        
        return view('member.points.dashboard', compact(
            'pointStats',
            'recentTransactions',
            'monthlyData',
            'availablePackages'
        ));
    }
    
    /**
     * Show points transaction history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = PointTransaction::where('user_id', $user->id);
        
        // Filter by transaction type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Summary statistics
        $summary = [
            'total_earned' => PointTransaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->sum('amount'),
            'total_spent' => PointTransaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->sum('amount'),
            'current_balance' => ($user->reserve_points ?? 0) + ($user->active_points ?? 0)
        ];
        
        return view('member.points.history', compact('transactions', 'summary'));
    }
    
    /**
     * Show points transfer form
     */
    public function transfer()
    {
        $user = Auth::user();
        $availablePoints = $user->reserve_points ?? 0;
        
        return view('member.points.transfer', compact('availablePoints'));
    }
    
    /**
     * Process points transfer
     */
    public function processTransfer(Request $request)
    {
        $request->validate([
            'recipient_username' => 'required|exists:users,username',
            'amount' => 'required|numeric|min:1',
            'password' => 'required'
        ]);
        
        $user = User::find(Auth::id());
        
        // Verify password
        if (!password_verify($request->password, $user->password)) {
            return back()->with('error', 'Invalid password provided.');
        }
        
        // Check if user has sufficient points
        if ($user->reserve_points < $request->amount) {
            return back()->with('error', 'Insufficient reserve points for transfer.');
        }
        
        // Find recipient
        $recipient = User::where('username', $request->recipient_username)->first();
        
        if ($recipient->id === $user->id) {
            return back()->with('error', 'You cannot transfer points to yourself.');
        }
        
        try {
            DB::transaction(function () use ($user, $recipient, $request) {
                // Deduct from sender
                $user->reserve_points -= $request->amount;
                $user->save();
                
                // Add to recipient
                $recipient->reserve_points += $request->amount;
                $recipient->save();
                
                // Record transactions
                PointTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $request->amount,
                    'description' => "Points transferred to {$recipient->username}",
                    'reference_id' => $recipient->id,
                    'reference_type' => 'transfer_out'
                ]);
                
                PointTransaction::create([
                    'user_id' => $recipient->id,
                    'type' => 'credit',
                    'amount' => $request->amount,
                    'description' => "Points received from {$user->username}",
                    'reference_id' => $user->id,
                    'reference_type' => 'transfer_in'
                ]);
            });
            
            return redirect()->route('member.points.dashboard')
                ->with('success', "Successfully transferred {$request->amount} points to {$recipient->username}");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Transfer failed. Please try again.');
        }
    }
    
    /**
     * Get current points balance (AJAX)
     */
    public function balance()
    {
        $user = Auth::user();
        
        return response()->json([
            'reserve_points' => $user->reserve_points ?? 0,
            'active_points' => $user->active_points ?? 0,
            'total_points_earned' => $user->total_points_earned ?? 0
        ]);
    }
    
    /**
     * Get recent transactions (AJAX)
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'date' => $transaction->created_at->format('M d, Y H:i'),
                    'status' => $transaction->status ?? 'completed'
                ];
            });
        
        return response()->json($transactions);
    }
}
