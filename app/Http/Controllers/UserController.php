<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Commission;
use App\Models\Withdrawal;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['sponsor', 'subscriptionPlan']);

            // Search functionality
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Filter by role
            if ($request->filled('role')) {
                $query->byRole($request->role);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->byStatus($request->status);
            }

            // Filter by KYC status
            if ($request->filled('kyc_status')) {
                $query->where('kyc_status', $request->kyc_status);
            }

            // Filter by verification status
            if ($request->filled('verification')) {
                switch ($request->verification) {
                    case 'email_verified':
                        $query->verifiedEmail();
                        break;
                    case 'phone_verified':
                        $query->verifiedPhone();
                        break;
                    case 'kyc_verified':
                        $query->kycVerified();
                        break;
                    case 'has_sponsor':
                        $query->hasSponsor();
                        break;
                }
            }

            // Sort functionality
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSorts = ['name', 'email', 'role', 'status', 'kyc_status', 'total_earnings', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $users = $query->paginate(20);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $users,
                    'html' => view('users.partials.users-table', compact('users'))->render()
                ]);
            }

            return view('users.index', compact('users'));

        } catch (\Exception $e) {
            Log::error('Users index error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading users data'
                ], 500);
            }

            return back()->with('error', 'Error loading users data');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        try {
            $sponsors = User::affiliates()->active()->get();
            $subscriptionPlans = SubscriptionPlan::active()->ordered()->get();

            return view('users.create', compact('sponsors', 'subscriptionPlans'));

        } catch (\Exception $e) {
            Log::error('User create form error: ' . $e->getMessage());
            return back()->with('error', 'Error loading create form');
        }
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20|unique:users',
                'role' => 'required|in:customer,vendor,affiliate,admin',
                'sponsor_id' => 'nullable|exists:users,id',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'commission_rate' => 'nullable|numeric|min:0|max:1',
                'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::beginTransaction();

            $userData = $request->except(['password', 'avatar']);
            $userData['password'] = Hash::make($request->password);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $userData['avatar'] = $avatarPath;
            }

            $user = User::create($userData);

            // Set subscription if provided
            if ($request->subscription_plan_id) {
                $plan = SubscriptionPlan::find($request->subscription_plan_id);
                $plan->createSubscription($user);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'data' => $user->load(['sponsor', 'subscriptionPlan'])
                ]);
            }

            return redirect()->route('users.index')
                           ->with('success', 'User created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User store error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating user'
                ], 500);
            }

            return back()->with('error', 'Error creating user')->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        try {
            $user->load([
                'sponsor',
                'referrals',
                'subscriptionPlan',
                'transactions' => function ($query) {
                    $query->latest()->take(10);
                },
                'commissions' => function ($query) {
                    $query->latest()->take(10);
                },
                'withdrawals' => function ($query) {
                    $query->latest()->take(5);
                }
            ]);

            // Calculate statistics
            $stats = [
                'total_referrals' => $user->referrals()->count(),
                'active_referrals' => $user->referrals()->active()->count(),
                'total_commissions' => $user->commissions()->sum('commission_amount'),
                'pending_commissions' => $user->commissions()->pending()->sum('commission_amount'),
                'total_withdrawals' => $user->withdrawals()->completed()->sum('amount'),
                'login_streak' => $this->calculateLoginStreak($user)
            ];

            return view('users.show', compact('user', 'stats'));

        } catch (\Exception $e) {
            Log::error('User show error: ' . $e->getMessage());
            return back()->with('error', 'Error loading user details');
        }
    }

    /**
     * Update user KYC status.
     */
    public function updateKyc(Request $request, User $user)
    {
        try {
            $request->validate([
                'action' => 'required|in:approve,reject,request_resubmission',
                'rejection_reason' => 'required_if:action,reject,request_resubmission|string|max:1000'
            ]);

            DB::beginTransaction();

            switch ($request->action) {
                case 'approve':
                    $user->approveKyc(auth()->id());
                    $message = 'KYC approved successfully';
                    break;
                    
                case 'reject':
                    $user->rejectKyc($request->rejection_reason, auth()->id());
                    $message = 'KYC rejected';
                    break;
                    
                case 'request_resubmission':
                    $user->kyc_status = 'resubmission_required';
                    $user->kyc_rejection_reason = $request->rejection_reason;
                    $user->save();
                    $message = 'Resubmission requested';
                    break;
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'kyc_status' => $user->kyc_status
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KYC update error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating KYC status'
                ], 500);
            }

            return back()->with('error', 'Error updating KYC status');
        }
    }

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, User $user)
    {
        try {
            $request->validate([
                'status' => 'required|in:active,inactive,suspended,banned',
                'reason' => 'nullable|string|max:500'
            ]);

            $oldStatus = $user->status;
            
            switch ($request->status) {
                case 'active':
                    $user->activate();
                    break;
                case 'inactive':
                    $user->deactivate();
                    break;
                case 'suspended':
                    $user->suspend($request->reason);
                    break;
                case 'banned':
                    $user->ban($request->reason);
                    break;
            }

            // Log status change
            Log::info("User status changed", [
                'user_id' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $user->status,
                'reason' => $request->reason,
                'changed_by' => auth()->id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "User status updated to {$user->status_name}",
                    'status' => $user->status
                ]);
            }

            return back()->with('success', "User status updated to {$user->status_name}");

        } catch (\Exception $e) {
            Log::error('User status update error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user status'
                ], 500);
            }

            return back()->with('error', 'Error updating user status');
        }
    }

    /**
     * Get user analytics.
     */
    public function analytics(Request $request, User $user)
    {
        try {
            $period = $request->get('period', '30'); // days

            $analytics = [
                'referral_stats' => [
                    'total' => $user->referrals()->count(),
                    'this_month' => $user->referrals()->whereMonth('created_at', now()->month)->count(),
                    'active' => $user->referrals()->active()->count()
                ],
                'commission_stats' => [
                    'total_earned' => $user->commissions()->sum('commission_amount'),
                    'this_month' => $user->commissions()->thisMonth()->sum('commission_amount'),
                    'pending' => $user->commissions()->pending()->sum('commission_amount'),
                    'paid' => $user->commissions()->paid()->sum('commission_amount')
                ],
                'transaction_stats' => [
                    'total_transactions' => $user->transactions()->count(),
                    'total_income' => $user->transactions()->income()->sum('amount'),
                    'total_expenses' => abs($user->transactions()->expense()->sum('amount')),
                    'this_month_income' => $user->transactions()->income()->thisMonth()->sum('amount')
                ],
                'withdrawal_stats' => [
                    'total_withdrawals' => $user->withdrawals()->count(),
                    'total_amount' => $user->withdrawals()->completed()->sum('amount'),
                    'pending' => $user->withdrawals()->pending()->sum('amount'),
                    'average_amount' => $user->withdrawals()->completed()->avg('amount') ?? 0
                ]
            ];

            // Get chart data for commissions over time
            $commissionChart = $user->commissions()
                ->selectRaw('DATE(earned_at) as date, SUM(commission_amount) as total')
                ->where('earned_at', '>=', now()->subDays($period))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Get referral tree
            $referralTree = $this->buildReferralTree($user, 3); // 3 levels deep

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'analytics' => $analytics,
                        'commission_chart' => $commissionChart,
                        'referral_tree' => $referralTree
                    ]
                ]);
            }

            return view('users.analytics', compact('user', 'analytics', 'commissionChart', 'referralTree'));

        } catch (\Exception $e) {
            Log::error('User analytics error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading analytics data'
                ], 500);
            }

            return back()->with('error', 'Error loading analytics data');
        }
    }

    /**
     * Get referral tree.
     */
    public function referralTree(User $user, $levels = 3)
    {
        try {
            $tree = $this->buildReferralTree($user, $levels);

            return response()->json([
                'success' => true,
                'data' => $tree
            ]);

        } catch (\Exception $e) {
            Log::error('Referral tree error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading referral tree'
            ], 500);
        }
    }

    /**
     * Build referral tree recursively.
     */
    private function buildReferralTree(User $user, $levels, $currentLevel = 1)
    {
        if ($currentLevel > $levels) {
            return [];
        }

        $referrals = $user->referrals()->with(['referrals' => function ($query) {
            $query->take(10); // Limit to prevent excessive loading
        }])->get();

        $tree = [];
        foreach ($referrals as $referral) {
            $node = [
                'id' => $referral->id,
                'name' => $referral->name,
                'email' => $referral->email,
                'avatar' => $referral->avatar_url,
                'role' => $referral->role,
                'status' => $referral->status,
                'joined_at' => $referral->created_at->format('M d, Y'),
                'total_earnings' => $referral->total_earnings,
                'level' => $currentLevel,
                'children' => $this->buildReferralTree($referral, $levels, $currentLevel + 1)
            ];
            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Calculate login streak.
     */
    private function calculateLoginStreak(User $user)
    {
        // This is a simplified version - in a real app, you'd track daily logins
        if (!$user->last_login_at) {
            return 0;
        }

        $daysSinceLastLogin = $user->last_login_at->diffInDays(now());
        
        if ($daysSinceLastLogin <= 1) {
            return $user->login_count >= 7 ? 7 : $user->login_count; // Max 7 day streak for demo
        }

        return 0;
    }

    /**
     * Export users data.
     */
    public function export(Request $request)
    {
        try {
            // Implementation would depend on your export requirements
            // This is a placeholder for the export functionality
            
            return response()->json([
                'success' => true,
                'message' => 'Export feature will be implemented based on requirements'
            ]);

        } catch (\Exception $e) {
            Log::error('User export error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error exporting users data'
            ], 500);
        }
    }

    /**
     * Bulk actions on users.
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|in:activate,deactivate,suspend,ban,delete,export',
                'users' => 'required|array',
                'users.*' => 'exists:users,id',
                'reason' => 'nullable|string|max:500'
            ]);

            $userIds = $request->users;
            $users = User::whereIn('id', $userIds)->get();
            $successCount = 0;

            DB::beginTransaction();

            foreach ($users as $user) {
                try {
                    switch ($request->action) {
                        case 'activate':
                            $user->activate();
                            $successCount++;
                            break;
                        case 'deactivate':
                            $user->deactivate();
                            $successCount++;
                            break;
                        case 'suspend':
                            $user->suspend($request->reason);
                            $successCount++;
                            break;
                        case 'ban':
                            $user->ban($request->reason);
                            $successCount++;
                            break;
                        case 'delete':
                            if ($user->id !== auth()->id()) { // Don't delete current user
                                $user->delete();
                                $successCount++;
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    Log::error("Bulk action failed for user {$user->id}: " . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk action completed. {$successCount} users processed."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action'
            ], 500);
        }
    }
}
