<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Models\BinaryMatching;
use App\Models\User;
use App\Services\MatchingBonusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchingBonusController extends Controller
{
    protected $matchingBonusService;

    public function __construct(MatchingBonusService $matchingBonusService)
    {
        $this->matchingBonusService = $matchingBonusService;
    }

    /**
     * Display matching bonus overview
     */
    public function index(Request $request)
    {
        $query = Commission::with(['user', 'commissionSetting'])
                          ->whereIn('commission_type', ['matching_bonus', 'tier_bonus']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('commission_type')) {
            $query->where('commission_type', $request->commission_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->user_search . '%')
                  ->orWhere('email', 'like', '%' . $request->user_search . '%')
                  ->orWhere('firstname', 'like', '%' . $request->user_search . '%')
                  ->orWhere('lastname', 'like', '%' . $request->user_search . '%');
            });
        }

        $matchingBonuses = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics
        $stats = $this->getMatchingStatistics($request);

        // Get commission settings
        $commissionSettings = CommissionSetting::where('type', 'matching')
                                              ->where('is_active', true)
                                              ->get();

        return view('admin.matching-bonuses.index', compact(
            'matchingBonuses', 
            'stats', 
            'commissionSettings'
        ));
    }

    /**
     * Show detailed matching bonus view
     */
    public function show($id)
    {
        $commission = Commission::with(['user', 'commissionSetting'])
                               ->findOrFail($id);

        // Get related binary matching if exists
        $binaryMatching = null;
        if ($commission->reference_type === 'binary_matching' && $commission->reference_id) {
            $binaryMatching = BinaryMatching::find($commission->reference_id);
        }

        // Get user's binary tree information
        $binaryTree = $commission->user->binaryTree;

        return view('admin.matching-bonuses.show', compact(
            'commission', 
            'binaryMatching', 
            'binaryTree'
        ));
    }

    /**
     * Process matching bonuses manually
     */
    public function process(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $date = $request->date;
            $userId = $request->user_id;

            $result = $this->matchingBonusService->processMatchingBonuses($date, $userId);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Matching bonuses processed successfully',
                    'data' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process matching bonuses'
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing matching bonuses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve pending matching bonuses
     */
    public function approve(Request $request)
    {
        $request->validate([
            'commission_ids' => 'required|array',
            'commission_ids.*' => 'exists:commissions,id'
        ]);

        try {
            DB::beginTransaction();

            $commissions = Commission::whereIn('id', $request->commission_ids)
                                   ->where('status', 'pending')
                                   ->whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                   ->get();

            $approvedCount = 0;
            $totalAmount = 0;

            foreach ($commissions as $commission) {
                $commission->update([
                    'status' => 'approved',
                    'approved_at' => now()
                ]);

                $approvedCount++;
                $totalAmount += $commission->commission_amount;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Approved {$approvedCount} matching bonuses totaling $" . number_format($totalAmount, 2),
                'approved_count' => $approvedCount,
                'total_amount' => $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error approving matching bonuses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pay approved matching bonuses
     */
    public function pay(Request $request)
    {
        $request->validate([
            'commission_ids' => 'required|array',
            'commission_ids.*' => 'exists:commissions,id'
        ]);

        try {
            DB::beginTransaction();

            $commissions = Commission::whereIn('id', $request->commission_ids)
                                   ->where('status', 'approved')
                                   ->whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                   ->get();

            $paidCount = 0;
            $totalAmount = 0;

            foreach ($commissions as $commission) {
                // Update user's balance (assuming you have a wallet system)
                $user = $commission->user;
                if ($user && method_exists($user, 'addBalance')) {
                    $user->addBalance($commission->commission_amount, 'matching_bonus', $commission->id);
                }

                $commission->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

                $paidCount++;
                $totalAmount += $commission->commission_amount;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Paid {$paidCount} matching bonuses totaling $" . number_format($totalAmount, 2),
                'paid_count' => $paidCount,
                'total_amount' => $totalAmount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error paying matching bonuses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's matching bonus history
     */
    public function userHistory($userId)
    {
        $user = User::findOrFail($userId);

        $matchingBonuses = Commission::with('commissionSetting')
                                   ->where('user_id', $userId)
                                   ->whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(20);

        // Get user's binary summary
        $binarySummary = $user->binarySummary;

        // Get user's binary tree
        $binaryTree = $user->binaryTree;

        return view('admin.matching-bonuses.user-history', compact(
            'user', 
            'matchingBonuses', 
            'binarySummary', 
            'binaryTree'
        ));
    }

    /**
     * Export matching bonuses
     */
    public function export(Request $request)
    {
        $query = Commission::with(['user', 'commissionSetting'])
                          ->whereIn('commission_type', ['matching_bonus', 'tier_bonus']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('commission_type')) {
            $query->where('commission_type', $request->commission_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $matchingBonuses = $query->orderBy('created_at', 'desc')->get();

        $filename = 'matching_bonuses_' . Carbon::now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($matchingBonuses) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'User',
                'Commission Type',
                'Setting',
                'Amount',
                'Status',
                'Level',
                'Percentage',
                'Created At',
                'Paid At'
            ]);

            // Add data rows
            foreach ($matchingBonuses as $bonus) {
                fputcsv($file, [
                    $bonus->id,
                    $bonus->user->username ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $bonus->commission_type)),
                    $bonus->commissionSetting->display_name ?? 'N/A',
                    number_format($bonus->commission_amount, 2),
                    ucfirst($bonus->status),
                    $bonus->level ?? 'N/A',
                    $bonus->percentage ?? 'N/A',
                    $bonus->created_at->format('Y-m-d H:i:s'),
                    $bonus->paid_at ? $bonus->paid_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get matching statistics
     */
    protected function getMatchingStatistics($request)
    {
        $startDate = $request->filled('date_from') ? $request->date_from : Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->filled('date_to') ? $request->date_to : Carbon::now()->toDateString();

        $baseQuery = Commission::whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                              ->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_bonuses' => (clone $baseQuery)->sum('commission_amount'),
            'pending_bonuses' => (clone $baseQuery)->where('status', 'pending')->sum('commission_amount'),
            'approved_bonuses' => (clone $baseQuery)->where('status', 'approved')->sum('commission_amount'),
            'paid_bonuses' => (clone $baseQuery)->where('status', 'paid')->sum('commission_amount'),
            'total_records' => (clone $baseQuery)->count(),
            'active_users' => (clone $baseQuery)->distinct('user_id')->count(),
            'matching_bonus_total' => (clone $baseQuery)->where('commission_type', 'matching_bonus')->sum('commission_amount'),
            'tier_bonus_total' => (clone $baseQuery)->where('commission_type', 'tier_bonus')->sum('commission_amount'),
        ];
    }

    /**
     * Dashboard widget data
     */
    public function dashboardWidget()
    {
        $today = Carbon::now()->toDateString();
        $thisMonth = Carbon::now()->startOfMonth()->toDateString();

        return response()->json([
            'today_bonuses' => Commission::whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                       ->whereDate('created_at', $today)
                                       ->sum('commission_amount'),
            
            'month_bonuses' => Commission::whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                       ->where('created_at', '>=', $thisMonth)
                                       ->sum('commission_amount'),
            
            'pending_count' => Commission::whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                       ->where('status', 'pending')
                                       ->count(),
            
            'active_users_today' => Commission::whereIn('commission_type', ['matching_bonus', 'tier_bonus'])
                                            ->whereDate('created_at', $today)
                                            ->distinct('user_id')
                                            ->count(),
        ]);
    }
}
