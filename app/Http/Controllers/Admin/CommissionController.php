<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commission;
use App\Models\BinaryMatching;
use App\Models\BinarySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CommissionController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Commission Overview
     */
    public function overview()
    {
        $data = [
            'total_commissions' => Commission::sum('commission_amount'),
            'total_paid' => Commission::where('status', 'paid')->sum('commission_amount'),
            'total_pending' => Commission::where('status', 'pending')->sum('commission_amount'),
            'total_cancelled' => Commission::where('status', 'cancelled')->sum('commission_amount'),
            'this_month_commissions' => Commission::whereMonth('created_at', now()->month)
                                                 ->whereYear('created_at', now()->year)
                                                 ->sum('commission_amount'),
            'last_month_commissions' => Commission::whereMonth('created_at', now()->subMonth()->month)
                                                 ->whereYear('created_at', now()->subMonth()->year)
                                                 ->sum('commission_amount'),
            'today_commissions' => Commission::whereDate('created_at', today())->sum('commission_amount'),
            'commission_types' => Commission::select('commission_type', DB::raw('count(*) as count'), DB::raw('sum(commission_amount) as total'))
                                           ->groupBy('commission_type')
                                           ->get(),
            'recent_commissions' => Commission::with('user')
                                             ->latest()
                                             ->limit(10)
                                             ->get(),
            'top_earners' => Commission::select('user_id', DB::raw('sum(commission_amount) as total_earned'))
                                      ->with('user')
                                      ->groupBy('user_id')
                                      ->orderBy('total_earned', 'desc')
                                      ->limit(10)
                                      ->get(),
            'monthly_stats' => Commission::select(
                                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                                    DB::raw('sum(commission_amount) as total'),
                                    DB::raw('count(*) as count')
                                )
                                ->where('created_at', '>=', now()->subMonths(12))
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get()
        ];

        return view('admin.commissions.overview', compact('data'));
    }

    /**
     * Direct Commission Management
     */
    public function direct(Request $request)
    {
        $query = Commission::with('user', 'referredUser')
                          ->where('commission_type', 'referral');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $commissions = $query->orderBy('created_at', 'desc')
                            ->paginate(20);

        $stats = [
            'total_direct' => Commission::where('commission_type', 'referral')->sum('commission_amount'),
            'paid_direct' => Commission::where('commission_type', 'referral')->where('status', 'paid')->sum('commission_amount'),
            'pending_direct' => Commission::where('commission_type', 'referral')->where('status', 'pending')->sum('commission_amount'),
            'this_month_direct' => Commission::where('commission_type', 'referral')
                                            ->whereMonth('created_at', now()->month)
                                            ->sum('commission_amount')
        ];

        return view('admin.commissions.direct', compact('commissions', 'stats'));
    }

    /**
     * Binary Commission Management
     */
    public function binary(Request $request)
    {
        $query = Commission::with('user')
                          ->where('commission_type', 'bonus');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->orderBy('created_at', 'desc')
                            ->paginate(20);

        $stats = [
            'total_binary' => Commission::where('commission_type', 'bonus')->sum('commission_amount'),
            'paid_binary' => Commission::where('commission_type', 'bonus')->where('status', 'paid')->sum('commission_amount'),
            'pending_binary' => Commission::where('commission_type', 'bonus')->where('status', 'pending')->sum('commission_amount'),
            'this_month_binary' => Commission::where('commission_type', 'bonus')
                                            ->whereMonth('created_at', now()->month)
                                            ->sum('commission_amount')
        ];

        $binary_summary = BinarySummary::with('user')
                                      ->orderBy('created_at', 'desc')
                                      ->limit(10)
                                      ->get();

        return view('admin.commissions.binary', compact('commissions', 'stats', 'binary_summary'));
    }

    /**
     * Matching Bonus Management
     */
    public function matching(Request $request)
    {
        $query = Commission::with('user')
                          ->where('commission_type', 'tier_bonus');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->orderBy('created_at', 'desc')
                            ->paginate(20);

        $stats = [
            'total_matching' => Commission::where('commission_type', 'tier_bonus')->sum('commission_amount'),
            'paid_matching' => Commission::where('commission_type', 'tier_bonus')->where('status', 'paid')->sum('commission_amount'),
            'pending_matching' => Commission::where('commission_type', 'tier_bonus')->where('status', 'pending')->sum('commission_amount'),
            'this_month_matching' => Commission::where('commission_type', 'tier_bonus')
                                              ->whereMonth('created_at', now()->month)
                                              ->sum('commission_amount')
        ];

        $matching_records = BinaryMatching::with('user')
                                          ->orderBy('created_at', 'desc')
                                          ->limit(10)
                                          ->get();

        return view('admin.commissions.matching', compact('commissions', 'stats', 'matching_records'));
    }

    /**
     * Leadership Bonus Management
     */
    public function leadership(Request $request)
    {
        $query = Commission::with('user')
                          ->where('commission_type', 'performance');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->orderBy('created_at', 'desc')
                            ->paginate(20);

        $stats = [
            'total_leadership' => Commission::where('commission_type', 'performance')->sum('commission_amount'),
            'paid_leadership' => Commission::where('commission_type', 'performance')->where('status', 'paid')->sum('commission_amount'),
            'pending_leadership' => Commission::where('commission_type', 'performance')->where('status', 'pending')->sum('commission_amount'),
            'this_month_leadership' => Commission::where('commission_type', 'performance')
                                                ->whereMonth('created_at', now()->month)
                                                ->sum('commission_amount')
        ];

        $leadership_levels = Commission::where('commission_type', 'performance')
                                      ->select('level', DB::raw('count(*) as count'), DB::raw('sum(commission_amount) as total'))
                                      ->groupBy('level')
                                      ->orderBy('level')
                                      ->get();

        return view('admin.commissions.leadership', compact('commissions', 'stats', 'leadership_levels'));
    }

    /**
     * Commission Payouts Management
     */
    public function payouts(Request $request)
    {
        // Debug: Force log to confirm method is called
        Log::info('PAYOUTS METHOD CALLED - Request URL: ' . $request->url());
        Log::info('PAYOUTS METHOD CALLED - Route name: ' . $request->route()->getName());
        
        // Get commissions with basic query
        $query = Commission::with('user');

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('commission_type', $request->type);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $commissions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate stats with proper fallbacks for empty database
        $stats = [
            'total_payouts' => Commission::sum('commission_amount') ?: 0,
            'paid_payouts' => Commission::where('status', 'paid')->sum('commission_amount') ?: 0,
            'pending_payouts' => Commission::where('status', 'pending')->sum('commission_amount') ?: 0,
            'cancelled_payouts' => Commission::where('status', 'cancelled')->sum('commission_amount') ?: 0,
            'this_month_payouts' => Commission::whereMonth('created_at', now()->month)->sum('commission_amount') ?: 0
        ];

        Log::info('PAYOUTS METHOD - Passing variables:', [
            'commissions_count' => $commissions->count(),
            'stats' => $stats
        ]);

        return view('admin.commissions.payouts', compact('commissions', 'stats'));
    }

    /**
     * Update Commission Status
     */
    public function updateStatus(Request $request, Commission $commission)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,paid,cancelled'
        ]);

        $commission->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        // If marking as paid, update user's wallet
        if ($request->status === 'paid' && $commission->status !== 'paid') {
            $user = $commission->user;
            if ($user && isset($user->wallet_balance)) {
                $user->increment('wallet_balance', $commission->commission_amount);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Commission status updated successfully'
        ]);
    }

    /**
     * Bulk Status Update
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'commission_ids' => 'required|array',
            'commission_ids.*' => 'exists:commissions,id',
            'status' => 'required|in:pending,approved,paid,cancelled'
        ]);

        $commissions = Commission::whereIn('id', $request->commission_ids)->get();
        
        foreach ($commissions as $commission) {
            if ($request->status === 'paid' && $commission->status !== 'paid') {
                $user = $commission->user;
                if ($user && isset($user->wallet_balance)) {
                    $user->increment('wallet_balance', $commission->commission_amount);
                }
            }
            
            $commission->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'message' => count($request->commission_ids) . ' commissions updated successfully'
        ]);
    }

    /**
     * Export Commissions
     */
    public function export(Request $request)
    {
        $query = Commission::with('user');

        // Apply same filters as the current view
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('commission_type', $request->type);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'commissions_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($commissions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Type', 'Commission Amount', 'Order Amount', 
                'Rate', 'Level', 'Status', 'Created At', 'Updated At'
            ]);

            // CSV data
            foreach ($commissions as $commission) {
                fputcsv($file, [
                    $commission->id,
                    $commission->user->name ?? 'N/A',
                    $commission->user->email ?? 'N/A',
                    ucfirst($commission->commission_type),
                    $commission->commission_amount,
                    $commission->order_amount,
                    $commission->commission_rate,
                    $commission->level ?? 'N/A',
                    ucfirst($commission->status),
                    $commission->created_at->format('Y-m-d H:i:s'),
                    $commission->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
