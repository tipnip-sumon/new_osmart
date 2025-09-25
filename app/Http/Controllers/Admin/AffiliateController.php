<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commission;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    /**
     * Display a listing of affiliate users
     */
    public function index(Request $request)
    {
        $query = User::with(['commissions' => function($q) {
            $q->where('commission_type', 'affiliate');
        }])
        ->withCount(['commissions as affiliate_commissions_count' => function($q) {
            $q->where('commission_type', 'affiliate');
        }])
        ->withSum(['commissions as total_affiliate_earnings' => function($q) {
            $q->where('commission_type', 'affiliate')->where('status', 'approved');
        }], 'commission_amount');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $affiliates = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get summary statistics
        $stats = [
            'total_affiliates' => User::count(),
            'active_affiliates' => User::where('status', 'active')->count(),
            'total_affiliate_earnings' => Commission::where('commission_type', 'affiliate')
                                                  ->where('status', 'approved')
                                                  ->sum('commission_amount'),
            'pending_commissions' => Commission::where('commission_type', 'affiliate')
                                               ->where('status', 'pending')
                                               ->sum('commission_amount'),
        ];

        return view('admin.affiliates.index', compact('affiliates', 'stats'));
    }

    /**
     * Show the form for creating a new affiliate user
     */
    public function create()
    {
        return view('admin.affiliates.create');
    }

    /**
     * Store a newly created affiliate user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'commission_rate' => $request->commission_rate ? $request->commission_rate / 100 : null,
            'role' => 'customer',
            'status' => 'active',
            'ref_by' => 1, // Default referrer
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate user created successfully.');
    }

    /**
     * Display the specified affiliate user
     */
    public function show(User $affiliate)
    {
        $affiliate->load([
            'commissions' => function($q) {
                $q->where('commission_type', 'affiliate')->latest();
            }
        ]);

        // Get commission statistics
        $commissionStats = [
            'total_earned' => $affiliate->commissions
                                      ->where('commission_type', 'affiliate')
                                      ->where('status', 'approved')
                                      ->sum('commission_amount'),
            'pending_amount' => $affiliate->commissions
                                        ->where('commission_type', 'affiliate')
                                        ->where('status', 'pending')
                                        ->sum('commission_amount'),
            'total_commissions' => $affiliate->commissions
                                           ->where('commission_type', 'affiliate')
                                           ->count(),
            'this_month_earned' => $affiliate->commissions
                                           ->where('commission_type', 'affiliate')
                                           ->where('status', 'approved')
                                           ->whereBetween('created_at', [
                                               now()->startOfMonth(),
                                               now()->endOfMonth()
                                           ])
                                           ->sum('commission_amount'),
        ];

        // Get recent commissions
        $recentCommissions = $affiliate->commissions()
                                     ->where('commission_type', 'affiliate')
                                     ->with(['order', 'product'])
                                     ->latest()
                                     ->limit(10)
                                     ->get();

        return view('admin.affiliates.show', compact('affiliate', 'commissionStats', 'recentCommissions'));
    }

    /**
     * Show the form for editing the specified affiliate user
     */
    public function edit(User $affiliate)
    {
        return view('admin.affiliates.edit', compact('affiliate'));
    }

    /**
     * Update the specified affiliate user
     */
    public function update(Request $request, User $affiliate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $affiliate->id,
            'email' => 'required|email|unique:users,email,' . $affiliate->id,
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $affiliate->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'commission_rate' => $request->commission_rate ? $request->commission_rate / 100 : null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate user updated successfully.');
    }

    /**
     * Remove the specified affiliate user
     */
    public function destroy(User $affiliate)
    {
        // Check if affiliate has commissions
        $hasCommissions = $affiliate->commissions()->where('commission_type', 'affiliate')->exists();
        
        if ($hasCommissions) {
            return redirect()->route('admin.affiliates.index')
                            ->with('error', 'Cannot delete affiliate user with existing commissions.');
        }

        $affiliate->delete();

        return redirect()->route('admin.affiliates.index')
                        ->with('success', 'Affiliate user deleted successfully.');
    }

    /**
     * Toggle affiliate status
     */
    public function toggleStatus(User $affiliate)
    {
        $affiliate->update([
            'status' => $affiliate->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $affiliate->status === 'active' ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Affiliate user {$status} successfully.",
            'status' => $affiliate->status
        ]);
    }
}
