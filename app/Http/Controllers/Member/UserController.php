<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\PaymentTransaction;
use App\Models\Commission;
use App\Models\BinaryMatching;
use App\Models\Product;
use App\Models\GeneralSetting;
use App\Models\VendorApplication;
use App\Models\AdminNotification;
use App\Helpers\FeeCalculator;
use App\Helpers\LocationMatcher;
use App\Helpers\ProfileVerificationHelper;
use App\Traits\HandlesImageUploads;

class UserController extends Controller
{
    use HandlesImageUploads;
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get real-time wallet balances from users table
        $userFresh = User::find($user->id); // Get fresh data from database
        
        // Real-time wallet statistics
        $walletStats = [
            'total_balance' => ($userFresh->balance ?? 0) + ($userFresh->deposit_wallet ?? 0) + ($userFresh->interest_wallet ?? 0),
            'deposit_wallet' => $userFresh->deposit_wallet ?? 0,
            'income_wallet' => $userFresh->interest_wallet ?? 0,
            'main_balance' => $userFresh->balance ?? 0,
            'available_balance' => ($userFresh->deposit_wallet ?? 0) + ($userFresh->interest_wallet ?? 0),
            'pending_balance' => $userFresh->pending_balance ?? 0,
            'reserve_points' => $userFresh->reserve_points ?? 0,
            'withdrawn_amount' => Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        // Get pending cashback amount sum from user_daily_cashbacks table
        $pendingCashbackAmount = DB::table('user_daily_cashbacks')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('cashback_amount');
        
        // Bonus statistics from commissions table
        $sponsorBonus = Commission::where('user_id', $user->id)
            ->where('commission_type', 'sponsor')
            ->where('status', 'approved')
            ->sum('commission_amount');
            
        $binaryBonus = BinaryMatching::where('user_id', $user->id)
            ->whereIn('status', ['processed', 'paid'])
            ->sum('matching_bonus');
            
        $teamBonus = Commission::where('user_id', $user->id)
            ->where('commission_type', 'team')
            ->where('status', 'approved')
            ->sum('commission_amount');
            
        $rankBonus = Commission::where('user_id', $user->id)
            ->where('commission_type', 'rank')
            ->where('status', 'approved')
            ->sum('commission_amount');

        // Link Share Bonus - Cumulative from daily link sharing stats (2 TK per link share)
        $linkShareBonus = DB::table('daily_link_sharing_stats')
            ->where('user_id', $user->id)
            ->sum('earnings_amount');
            
        // Rank Salary from transactions
        $rankSalary = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'rank_salary')
            ->where('status', 'completed')
            ->sum('amount');
            
        // Cash Back from transactions (if available)
        $cashBack = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'cashback')
            ->where('status', 'completed')
            ->sum('amount');
            
        // KYC Bonus from transactions (if available)
        $kycBonus = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'kyc_bonus')
            ->where('status', 'completed')
            ->sum('amount');
        
        // Bonus statistics
        $bonusStats = [
            'sponsor_bonus' => $sponsorBonus,
            'binary_bonus' => $binaryBonus,
            'team_bonus' => $teamBonus,
            'rank_bonus' => $rankBonus,
            'link_share_bonus' => $linkShareBonus,
            'rank_salary' => $rankSalary,
            'cash_back' => $cashBack,
            'kyc_bonus' => $kycBonus,
            'total_bonus' => $sponsorBonus + $binaryBonus + $teamBonus + $rankBonus + $linkShareBonus,
        ];
        
        // Affiliate-specific statistics
        $totalCommissions = Commission::where('user_id', $user->id)->sum('commission_amount');
        $thisMonthCommissions = Commission::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');
        
        // Team statistics
        $directReferrals = User::where('sponsor_id', $user->id)->count();
        $totalDownline = $this->getTotalDownlineCount($user);
        
        // Commission breakdown
        $pendingCommissions = Commission::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('commission_amount');
        $paidCommissions = Commission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('commission_amount');
        
        // Recent activities - Include both Commission and Binary Matching data
        $recentCommissions = Commission::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();
            
        // Get recent binary matching bonuses
        $recentBinaryMatching = BinaryMatching::where('user_id', $user->id)
            ->latest()
            ->take(2)
            ->get()
            ->map(function ($matching) {
                return (object) [
                    'id' => $matching->id,
                    'commission_type' => 'matching_bonus',
                    'commission_amount' => $matching->matching_bonus,
                    'status' => 'paid', // Binary matchings are automatically paid
                    'level' => 'Binary',
                    'created_at' => $matching->created_at,
                    'description' => 'Binary Matching Bonus',
                    'reference_type' => 'binary_matching',
                    'reference_id' => $matching->id,
                ];
            });
            
        // Merge and sort recent activities by date
        $allRecentActivities = $recentCommissions->concat($recentBinaryMatching)
            ->sortByDesc('created_at')
            ->take(5);
        
        $recentCommissions = $allRecentActivities;
        
        $recentReferrals = User::where('sponsor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Current rank and progress
        $currentRank = $user->rank ?? 'Bronze';
        $rankProgress = $this->calculateRankProgress($user);
        
        // Binary leg volumes (if using binary MLM)
        $leftVolume = $this->getBinaryVolume($user, 'left');
        $rightVolume = $this->getBinaryVolume($user, 'right');
        
        // Prepare affiliate stats for the view
        $affiliateStats = [
            'total_commissions' => $totalCommissions,
            'this_month_commissions' => $thisMonthCommissions,
            'direct_referrals' => $directReferrals,
            'total_downline' => $totalDownline,
            'pending_commissions' => $pendingCommissions,
            'paid_commissions' => $paidCommissions,
            'current_rank' => $currentRank,
            'rank_progress' => $rankProgress,
            'left_volume' => $leftVolume,
            'right_volume' => $rightVolume,
        ];

        return view('member.dashboard', compact(
            'user',
            'walletStats',
            'bonusStats',
            'affiliateStats',
            'recentCommissions',
            'recentReferrals',
            'pendingCashbackAmount'
        ));
    }
    
    public function profile()
    {
        $user = User::with('sponsor')->find(Auth::id());
        if (!$user) {
            $user = Auth::user();
        }
        
        // Get verification status
        $verificationStatus = ProfileVerificationHelper::getVerificationStatus($user);
        
        return view('member.profile', compact('user', 'verificationStatus'));
    }
    
    public function updateProfile(Request $request)
    {
        // Debug: Log the request to see if it's reaching the controller
        \Illuminate\Support\Facades\Log::info('Profile update request received', [
            'user_id' => Auth::id(),
            'request_data' => $request->except(['password', '_token'])
        ]);
        
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'country' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'upazila' => 'nullable|string|max:100',
            'union_ward' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000', // Keep validation but won't save to DB
        ]);

        // Define which fields exist in database for actual updating
        $databaseFields = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'country' => $request->country ?: 'Bangladesh',
            'district' => $request->district,
            'upazila' => $request->upazila,
            'union_ward' => $request->union_ward,
            'city' => $request->city,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
        ];

        // Handle preferences - these will be stored in the preferences JSON column
        $preferences = [
            'bio' => $request->bio,
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'marketing_emails' => $request->has('marketing_emails'),
            'newsletter' => $request->has('newsletter'),
        ];

        // Merge preferences with existing preferences data
        $existingPrefs = [];
        if ($user->preferences) {
            if (is_string($user->preferences)) {
                $existingPrefs = json_decode($user->preferences, true) ?: [];
            } elseif (is_array($user->preferences)) {
                $existingPrefs = $user->preferences;
            }
        }
        $updatedPrefs = array_merge($existingPrefs, $preferences);
        
        // Add preferences to database update - always store as JSON string
        $databaseFields['preferences'] = json_encode($updatedPrefs);

        try {
            // Update using query builder to ensure compatibility
            $updateResult = User::where('id', $user->id)->update($databaseFields);
            
            // Debug: Log update result
            \Illuminate\Support\Facades\Log::info('Database update executed', [
                'user_id' => $user->id,
                'update_result' => $updateResult, // Number of rows affected
                'updated_data' => $databaseFields,
                'preferences_stored' => $preferences // Now stored in preferences column
            ]);
            
            // Get fresh user data to verify the update
            $freshUser = User::find($user->id);
            
            // Debug: Log the fresh data to verify the update worked
            \Illuminate\Support\Facades\Log::info('Fresh user data after update', [
                'user_id' => $freshUser->id,
                'district' => $freshUser->district,
                'upazila' => $freshUser->upazila,
                'union_ward' => $freshUser->union_ward,
                'name' => $freshUser->name
            ]);
            
            // Update profile completion
            ProfileVerificationHelper::updateProfileCompletion($freshUser);
            
            // Refresh the authenticated user to show updated data
            Auth::setUser($freshUser);
            
            // Debug: Log successful completion
            \Illuminate\Support\Facades\Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($databaseFields),
                'preferences_saved' => $preferences, // Successfully stored in database
                'user_refreshed' => true
            ]);
            
            return back()->with('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            // Debug: Log any exceptions
            \Illuminate\Support\Facades\Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Profile update failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $user = User::find(Auth::id());
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Upload new avatar using the trait
            $imageData = $this->uploadAvatarImage($request->file('avatar'), 'avatars');
            
            // Update user avatar path - use the medium size for profile display
            $avatarPath = $imageData['sizes']['medium']['path'];
            $user->update(['avatar' => $avatarPath]);

            return response()->json([
                'success' => true, 
                'message' => 'Avatar uploaded successfully!',
                'avatar_url' => asset('storage/' . $avatarPath),
                'avatar_urls' => $imageData['sizes'] // All sizes available
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to upload avatar: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Send phone verification OTP
     */
    public function sendPhoneVerification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->phone) {
            return response()->json([
                'success' => false, 
                'message' => 'Please add a phone number to your profile first.'
            ], 400);
        }
        
        if ($user->is_sms_verified) {
            return response()->json([
                'success' => false, 
                'message' => 'Your phone number is already verified.'
            ], 400);
        }
        
        try {
            // Generate verification token
            $token = ProfileVerificationHelper::generatePhoneVerificationToken($user);
            
            // Here you would integrate with your SMS service
            // For now, we'll just log it or show it in development
            if (app()->environment('local', 'development')) {
                Log::info("Phone verification OTP for user {$user->id}: {$token}");
            }
            
            // TODO: Implement SMS sending service
            // Example: SMSService::send($user->phone, "Your verification code is: {$token}");
            
            return response()->json([
                'success' => true, 
                'message' => 'Verification code sent to your phone number.',
                'debug_token' => app()->environment('local') ? $token : null // Only show in development
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to send verification code: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verify phone with OTP
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6'
        ]);
        
        $user = Auth::user();
        
        if ($user->is_sms_verified) {
            return response()->json([
                'success' => false, 
                'message' => 'Your phone number is already verified.'
            ], 400);
        }
        
        try {
            $success = ProfileVerificationHelper::verifyPhoneToken($user, $request->verification_code);
            
            if ($success) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Phone number verified successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid or expired verification code.'
                ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('customer_id', $user->id)
            ->with(['items', 'payments'])
            ->latest()
            ->paginate(10);
            
        return view('member.orders', compact('orders'));
    }
    
    public function commissions(Request $request)
    {
        $user = Auth::user();
        $commissionType = $request->get('type', 'all');
        
        // Calculate summary statistics using proper database queries
        $totalEarnings = Commission::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->sum('commission_amount');
            
        $thisMonthEarnings = Commission::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('commission_amount');
            
        $pendingAmount = Commission::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('commission_amount');
            
        $thisWeekEarnings = Commission::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('commission_amount');
            
        // Add binary matching earnings to totals
        $binaryTotalEarnings = BinaryMatching::where('user_id', $user->id)
            ->whereIn('status', ['processed', 'paid'])
            ->sum('matching_bonus');
            
        $binaryThisMonthEarnings = BinaryMatching::where('user_id', $user->id)
            ->whereIn('status', ['processed', 'paid'])
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('matching_bonus');
            
        $binaryThisWeekEarnings = BinaryMatching::where('user_id', $user->id)
            ->whereIn('status', ['processed', 'paid'])
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('matching_bonus');
        
        // Add other income sources to totals
        $linkShareTotalEarnings = DB::table('daily_link_sharing_stats')
            ->where('user_id', $user->id)
            ->sum('earnings_amount');
            
        $linkShareThisMonthEarnings = DB::table('daily_link_sharing_stats')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('earnings_amount');
            
        $linkShareThisWeekEarnings = DB::table('daily_link_sharing_stats')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('earnings_amount');
        
        $summary = [
            'total_earnings' => $totalEarnings + $binaryTotalEarnings + $linkShareTotalEarnings,
            'this_month_earnings' => $thisMonthEarnings + $binaryThisMonthEarnings + $linkShareThisMonthEarnings,
            'pending_amount' => $pendingAmount,
            'this_week_earnings' => $thisWeekEarnings + $binaryThisWeekEarnings + $linkShareThisWeekEarnings
        ];
        
        // Get all commissions for detailed calculations
        $allCommissions = Commission::where('user_id', $user->id)->get();
        
        // Group commissions by type and calculate statistics
        $commissionTypes = [
            'sponsor_bonus' => ['type' => 'sponsor', 'total' => 0, 'count' => 0, 'records' => collect()],
            'generation_bonus' => ['type' => 'generation', 'total' => 0, 'count' => 0, 'records' => collect()],
            'club_bonus' => ['type' => 'bonus', 'total' => 0, 'count' => 0, 'records' => collect()],
            'daily_pool' => ['type' => 'monthly_bonus', 'total' => 0, 'count' => 0, 'records' => collect()],
            'rank_bonus' => ['type' => 'tier_bonus', 'total' => 0, 'count' => 0, 'records' => collect()],
        ];
        
        // Populate commission type data
        foreach ($commissionTypes as $key => &$typeData) {
            $typeCommissions = $allCommissions->where('commission_type', $typeData['type']);
            $typeData['total'] = $typeCommissions->where('status', '!=', 'cancelled')->sum('commission_amount');
            $typeData['count'] = $typeCommissions->where('status', '!=', 'cancelled')->count();
            $typeData['records'] = $typeCommissions->sortByDesc('created_at')->take(50);
        }
        
        // Prepare commission data for view
        $commissionsData = [
            'summary' => $summary,
            'sponsor_bonus' => $commissionTypes['sponsor_bonus'],
            'generation_bonus' => $commissionTypes['generation_bonus'],
            'club_bonus' => $commissionTypes['club_bonus'],
            'daily_pool' => $commissionTypes['daily_pool'],
            'rank_bonus' => $commissionTypes['rank_bonus']
        ];
        
        // Additional income sources for enhanced view
        $binaryMatching = BinaryMatching::where('user_id', $user->id)
            ->whereIn('status', ['processed', 'paid'])
            ->sum('matching_bonus');
        
        $linkShareBonus = DB::table('daily_link_sharing_stats')
            ->where('user_id', $user->id)
            ->sum('earnings_amount');
            
        $rankSalary = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'rank_salary')
            ->where('status', 'completed')
            ->sum('amount');
            
        $cashBack = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'cashback')
            ->where('status', 'completed')
            ->sum('amount');
            
        $kycBonus = DB::table('transactions')
            ->where('user_id', $user->id)
            ->where('type', 'kyc_bonus')
            ->where('status', 'completed')
            ->sum('amount');
        
        // Enhanced filtering for different commission types
        $commissions = Commission::where('user_id', $user->id)
            ->when($commissionType !== 'all', function ($query) use ($commissionType, $commissionTypes) {
                if ($commissionType === 'binary_bonus') {
                    // Handle binary bonus separately since it's from BinaryMatching table
                    $query->where('commission_type', 'binary');
                } elseif ($commissionType === 'cashback') {
                    // Handle cashback from transactions table
                    $query->where('commission_type', 'cashback');
                } elseif (isset($commissionTypes[$commissionType])) {
                    $query->where('commission_type', $commissionTypes[$commissionType]['type']);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('member.commissions', compact(
            'commissions', 
            'commissionsData', 
            'commissionType',
            'binaryMatching',
            'linkShareBonus', 
            'rankSalary',
            'cashBack',
            'kycBonus'
        ));
    }

    // MLM Tree & Network Methods
    public function genealogy()
    {
        $user = Auth::user();
        
        // Get user's downline/upline structure
        $genealogyData = $this->buildGenealogyTree($user);
        
        return view('member.genealogy', compact('user', 'genealogyData'));
    }
    
    public function getGenealogyNode(Request $request)
    {
        $userId = $request->input('user_id');
        $level = $request->input('level', 1);
        $maxLevel = $request->input('max_level', 5);
        
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $downlines = $this->getGenealogyDownlines($user, $level, $level + $maxLevel);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'referral_code' => $user->referral_code ?? 'REF' . $user->id,
                'status' => $user->status ?? 'active'
            ],
            'downlines' => $downlines
        ]);
    }
    
    public function searchGenealogyMembers(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        $allDownlineIds = $this->getAllDownlineIds($user);
        $allDownlineIds[] = $user->id; // Include the user themselves
        
        $members = User::whereIn('id', $allDownlineIds)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('referral_code', 'LIKE', "%{$query}%")
                  ->orWhere('id', 'LIKE', "%{$query}%");
            })
            ->with(['sponsor', 'orders'])
            ->get()
            ->map(function($member) {
                // Get sponsor name directly from database
                $sponsorName = 'N/A';
                if ($member->sponsor_id) {
                    $sponsor = User::find($member->sponsor_id);
                    $sponsorName = $sponsor ? $sponsor->name : 'N/A';
                }
                
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'referral_code' => $member->referral_code ?? 'REF' . $member->id,
                    'status' => $member->status ?? 'active',
                    'join_date' => $member->created_at->format('M d, Y'),
                    'sponsor_name' => $sponsorName,
                    'business' => $member->orders->sum('total_amount'),
                    'downline_count' => $this->getTotalDownlineCount($member),
                    'has_downline' => $this->getTotalDownlineCount($member) > 0
                ];
            });
        
        return response()->json(['members' => $members]);
    }

    public function getMemberDetails($id)
    {
        $user = Auth::user();
        $allDownlineIds = $this->getAllDownlineIds($user);
        $allDownlineIds[] = $user->id; // Include the user themselves
        
        // Check if the requested member is in user's network
        if (!in_array($id, $allDownlineIds)) {
            return response()->json(['error' => 'Member not found in your network'], 404);
        }
        
        $member = User::with(['sponsor', 'orders'])->find($id);
        
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }
        
        // Get sponsor name
        $sponsorName = 'N/A';
        if ($member->sponsor_id) {
            $sponsor = User::find($member->sponsor_id);
            $sponsorName = $sponsor ? $sponsor->name : 'N/A';
        }
        
        $memberDetails = [
            'id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'phone' => $member->phone ?? 'Not provided',
            'referral_code' => $member->referral_code ?? 'REF' . $member->id,
            'status' => $member->status ?? 'active',
            'join_date' => $member->created_at->format('M d, Y'),
            'sponsor_name' => $sponsorName,
            'business' => number_format($member->orders->sum('total_amount'), 2),
            'downline_count' => $this->getTotalDownlineCount($member),
            'has_downline' => $this->getTotalDownlineCount($member) > 0,
            'avatar' => $member->avatar ?? null,
            'address' => $member->address ?? 'Not provided',
            'city' => $member->city ?? 'Not provided',
            'country' => $member->country ?? 'Not provided'
        ];
        
        return response()->json(['member' => $memberDetails]);
    }

    public function binary(Request $request)
    {
        $user = Auth::user();
        
        // Check if we need to show a specific user's tree
        $rootUserParam = $request->get('root_user');
        $rootUser = null;
        
        if ($rootUserParam) {
            // Try to find user by ID first, then by username or referral_code
            if (is_numeric($rootUserParam)) {
                $rootUser = User::find($rootUserParam);
            } else {
                $rootUser = User::where('username', $rootUserParam)
                               ->orWhere('referral_code', $rootUserParam)
                               ->first();
            }
            
            if ($rootUser) {
                // Verify that the root user is in the current user's network
                if ($this->isUserInNetwork($user, $rootUser)) {
                    $binaryTree = $this->buildBinaryTree($rootUser);
                } else {
                    // User not in network, show error and fallback to current user
                    session()->flash('error', 'User not found in your binary tree network.');
                    $binaryTree = $this->buildBinaryTree($user);
                }
            } else {
                // User not found, show error and fallback to current user
                session()->flash('error', 'User not found.');
                $binaryTree = $this->buildBinaryTree($user);
            }
        } else {
            // Default: show current user's tree
            $binaryTree = $this->buildBinaryTree($user);
        }
        
        return view('member.binary', compact('user', 'binaryTree', 'rootUser'));
    }

    public function sponsor()
    {
        $user = Auth::user();
        $directReferrals = User::where('sponsor_id', $user->id)->get();
        $sponsorInfo = User::find($user->sponsor_id);
        
        return view('member.sponsor', compact('user', 'directReferrals', 'sponsorInfo'));
    }

    public function generations()
    {
        $user = Auth::user();
        
        // Get generation income service
        $generationService = new \App\Services\GenerationIncomeService();
        
        // Get generation income summary
        $incomeSummary = $generationService->getGenerationIncomeSummary($user);
        
        // Get generation details with pagination
        $generationIncomes = $generationService->getGenerationIncomeDetails($user, 15);
        
        // Get traditional generation levels for display
        $generations = $this->getNewGenerationLevels($user);
        
        return view('member.generations', compact(
            'user', 
            'generations', 
            'incomeSummary', 
            'generationIncomes'
        ));
    }
    
    public function getUserDownline(Request $request)
    {
        $userId = $request->input('user_id');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $levelFilter = $request->input('level', '');
        $statusFilter = $request->input('status', '');
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 404);
        }
        
        // Get multi-level downline data
        $downlineData = $this->getMultiLevelDownline($user, 5, $search, $levelFilter, $statusFilter);
        
        // Paginate results
        $collection = collect($downlineData);
        $total = $collection->count();
        $currentPage = $page;
        $perPageInt = (int) $perPage;
        $offset = ($currentPage - 1) * $perPageInt;
        
        $paginatedItems = $collection->slice($offset, $perPageInt)->values();
        
        $paginatedResult = [
            'data' => $paginatedItems,
            'current_page' => $currentPage,
            'last_page' => (int) ceil($total / $perPageInt),
            'per_page' => $perPageInt,
            'total' => $total,
            'from' => $offset + 1,
            'to' => min($offset + $perPageInt, $total)
        ];
        
        return response()->json([
            'success' => true,
            'downline' => $paginatedResult
        ]);
    }
    
    private function getMultiLevelDownline($user, $maxLevel = 5, $search = '', $levelFilter = '', $statusFilter = '')
    {
        $downlineData = [];
        $this->collectDownlineMembers($user, 1, $maxLevel, $downlineData, $search, $levelFilter, $statusFilter);
        
        // Sort by level, then by join date
        usort($downlineData, function($a, $b) {
            if ($a['level'] == $b['level']) {
                return strtotime($a['join_date_raw']) - strtotime($b['join_date_raw']);
            }
            return $a['level'] - $b['level'];
        });
        
        return $downlineData;
    }
    
    private function collectDownlineMembers($user, $currentLevel, $maxLevel, &$downlineData, $search, $levelFilter, $statusFilter)
    {
        if ($currentLevel > $maxLevel) {
            return;
        }
        
        // Get direct referrals
        $query = User::where('sponsor_id', $user->id)
            ->with(['sponsor', 'orders']);
        
        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        // Apply status filter
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }
        
        $directReferrals = $query->get();
        
        foreach ($directReferrals as $referral) {
            // Apply level filter
            if (!empty($levelFilter) && $currentLevel != $levelFilter) {
                // Still need to traverse for lower levels
                if ($currentLevel < $levelFilter) {
                    $this->collectDownlineMembers($referral, $currentLevel + 1, $maxLevel, $downlineData, $search, $levelFilter, $statusFilter);
                }
                continue;
            }
            
            $business = $referral->orders()->sum('total_amount') ?? 0;
            
            // Get sponsor name
            $sponsorName = 'N/A';
            if ($referral->sponsor_id) {
                $sponsor = User::find($referral->sponsor_id);
                $sponsorName = $sponsor ? $sponsor->name : 'N/A';
            }
            
            $memberData = [
                'id' => $referral->id,
                'name' => $referral->name,
                'email' => $referral->email,
                'phone' => $referral->phone,
                'username' => $referral->username,
                'level' => $currentLevel,
                'join_date' => $referral->created_at->format('M d, Y'),
                'join_date_raw' => $referral->created_at->toDateTimeString(),
                'join_time' => $referral->created_at->format('h:i A'),
                'status' => $referral->status ?? 'active',
                'business' => $business,
                'orders_count' => $referral->orders()->count(),
                'sponsor_id' => $referral->sponsor_id,
                'sponsor_name' => $sponsorName,
                'downline_count' => $this->getTotalDownlineCount($referral),
                'has_downline' => $this->hasDownline($referral)
            ];
            
            $downlineData[] = $memberData;
            
            // Recursively get next level
            if ($currentLevel < $maxLevel) {
                $this->collectDownlineMembers($referral, $currentLevel + 1, $maxLevel, $downlineData, $search, $levelFilter, $statusFilter);
            }
        }
    }

    // Financial Methods
    public function withdraw()
    {
        $user = Auth::user();
        $availableBalance = $user->wallet_balance ?? 0;
        $withdrawals = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->latest()
            ->paginate(10);
        
        // Check verification requirements
        $verificationStatus = [
            'email_verified' => $user->ev == 1, // Email verification
            'kyc_verified' => $user->kv == 1,   // KYC verification
            'profile_complete' => $this->isProfileComplete($user),
            'can_withdraw' => false
        ];
        
        // User can withdraw only if all verifications are complete
        $verificationStatus['can_withdraw'] = 
            $verificationStatus['email_verified'] && 
            $verificationStatus['kyc_verified'] && 
            $verificationStatus['profile_complete'];
        
        return view('member.withdraw', compact('user', 'availableBalance', 'withdrawals', 'verificationStatus'));
    }

    /**
     * Check if user profile is complete for withdrawal
     */
    private function isProfileComplete($user)
    {
        // Check required fields for withdrawal
        $requiredFields = [
            'firstname',
            'lastname', 
            'phone',
            'date_of_birth',
            'address',
            'city',
            'country'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }
        
        return true;
    }
    
    public function processWithdraw(Request $request)
    {
        $user = Auth::user();
        
        // First check all verification requirements
        if ($user->ev != 1) {
            return back()->with('error', 'Email verification is required before making withdrawals. Please verify your email address.');
        }
        
        if ($user->kv != 1) {
            return back()->with('error', 'KYC verification is required before making withdrawals. Please complete your KYC verification.');
        }
        
        if (!$this->isProfileComplete($user)) {
            return back()->with('error', 'Profile completion is required before making withdrawals. Please complete your profile information.');
        }
        
        $request->validate([
            'amount' => 'required|numeric|min:100|max:50000',
            'wallet_type' => 'required|in:deposit_wallet,interest_wallet',
            'payment_method' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'password_confirmation' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Verify password confirmation
        if (!Hash::check($request->password_confirmation, $user->password)) {
            return back()->with('error', 'Invalid password. Withdrawal cancelled for security reasons.');
        }
        
        $amount = $request->amount;
        $walletType = $request->wallet_type;
        
        // Calculate fee using dynamic fee system
        $feeCalculation = FeeCalculator::calculateWithdrawalFee($walletType, $amount);
        $fee = $feeCalculation['fee'];
        $netAmount = $feeCalculation['net_amount'];
        
        // Validate amount limits
        if ($amount < $feeCalculation['min_amount']) {
            return back()->with('error', 'Minimum withdrawal amount is ৳' . number_format($feeCalculation['min_amount'], 2));
        }
        
        if ($amount > $feeCalculation['max_amount']) {
            return back()->with('error', 'Maximum withdrawal amount is ৳' . number_format($feeCalculation['max_amount'], 2));
        }

        // Check wallet balance
        $availableBalance = $walletType === 'interest_wallet' ? $user->interest_wallet : $user->deposit_wallet;
        if ($amount > $availableBalance) {
            return back()->with('error', 'Insufficient balance in selected wallet!');
        }

        // Use database transaction to ensure data consistency
        try {
            DB::beginTransaction();

            // Generate unique transaction ID
            $transactionId = 'WD' . time() . rand(1000, 9999);

            // Create withdrawal transaction record
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->transaction_id = $transactionId;
            $transaction->type = 'withdrawal';
            $transaction->amount = $amount;
            $transaction->fee = $fee;
            $transaction->wallet_type = $walletType;
            $transaction->payment_method = $request->payment_method;
            $transaction->account_number = $request->account_number;
            $transaction->account_name = $request->account_name;
            $transaction->note = $request->note;
            $transaction->status = 'pending';
            $transaction->description = 'Withdrawal request of ৳' . number_format($amount, 2) . ' via ' . $request->payment_method;
            $transaction->save();

            // Create payment transaction record for admin approval system
            $paymentTransaction = new PaymentTransaction();
            $paymentTransaction->user_id = $user->id;
            $paymentTransaction->transaction_id = $transactionId;
            $paymentTransaction->payment_method = $request->payment_method;
            $paymentTransaction->gateway = 'manual';
            $paymentTransaction->amount = $amount;
            $paymentTransaction->fee = $fee;
            $paymentTransaction->net_amount = $netAmount;
            $paymentTransaction->status = 'pending';
            $paymentTransaction->type = 'withdrawal';
            $paymentTransaction->currency = 'BDT';
            $paymentTransaction->sender_number = $request->account_number;
            $paymentTransaction->description = 'Withdrawal request of ৳' . number_format($amount, 2) . ' via ' . $request->payment_method;
            $paymentTransaction->notes = $request->note;
            $paymentTransaction->metadata = json_encode([
                'wallet_type' => $walletType,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'original_transaction_id' => $transaction->id
            ]);
            $paymentTransaction->save();

            // Update the original transaction with payment transaction reference
            $transaction->update([
                'reference_type' => PaymentTransaction::class,
                'reference_id' => $paymentTransaction->id
            ]);

            // Deduct balance from the selected wallet
            if ($walletType === 'deposit_wallet') {
                User::where('id', $user->id)->decrement('deposit_wallet', $amount);
            } elseif ($walletType === 'interest_wallet') {
                User::where('id', $user->id)->decrement('interest_wallet', $amount);
            }

            DB::commit();

            return back()->with('success', 'Withdrawal request submitted successfully! Request ID: #' . $transaction->id . '. Amount ৳' . number_format($amount, 2) . ' has been deducted from your ' . ucfirst(str_replace('_', ' ', $walletType)) . '.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error processing withdrawal request. Please try again.');
        }
    }

    public function withdrawHistory()
    {
        $user = Auth::user();
        $withdrawals = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->latest()
            ->paginate(20);

        return view('member.withdraw-history', compact('withdrawals'));
    }

    public function withdrawDetails($id)
    {
        $user = Auth::user();
        $withdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('id', $id)
            ->firstOrFail();

        $html = view('member.partials.withdrawal-details', compact('withdrawal'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function cancelWithdraw($id)
    {
        $user = Auth::user();
        $withdrawal = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Refund the amount back to the user's wallet
            if ($withdrawal->wallet_type === 'deposit_wallet') {
                User::where('id', $user->id)->increment('deposit_wallet', $withdrawal->amount);
            } elseif ($withdrawal->wallet_type === 'interest_wallet') {
                User::where('id', $user->id)->increment('interest_wallet', $withdrawal->amount);
            }

            // Update withdrawal status
            $withdrawal->status = 'cancelled';
            $withdrawal->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request cancelled successfully. Amount ৳' . number_format($withdrawal->amount, 2) . ' has been refunded to your ' . ucfirst(str_replace('_', ' ', $withdrawal->wallet_type)) . '.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling withdrawal request. Please try again.'
            ]);
        }
    }

    public function transfer()
    {
        $user = Auth::user();
        
        // Get transfer history (both sent and received)
        $sentTransfers = Transaction::where('user_id', $user->id)
            ->where('type', 'transfer_out')
            ->latest()
            ->take(10)
            ->get();
            
        $receivedTransfers = Transaction::where('reference_id', $user->id)
            ->where('type', 'transfer_in')
            ->latest()
            ->take(10)
            ->get();
        
        return view('member.transfer', compact('user', 'sentTransfers', 'receivedTransfers'));
    }

    public function processTransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:50000',
            'recipient_identifier' => 'required|string',
            'wallet_type' => 'required|in:balance,deposit_wallet,interest_wallet',
            'note' => 'nullable|string|max:255',
            'password_confirmation' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Verify password confirmation
        if (!Hash::check($request->password_confirmation, $user->password)) {
            return back()->with('error', 'Invalid password. Transfer cancelled for security reasons.');
        }
        
        $amount = $request->amount;
        $walletType = $request->wallet_type;
        
        // Calculate fee using dynamic fee system
        $feeCalculation = FeeCalculator::calculateTransferFee($walletType, $amount);
        $transferFee = $feeCalculation['fee'];
        $totalDeduction = $feeCalculation['total_deduction'];
        
        // Validate amount limits
        if ($amount < $feeCalculation['min_amount']) {
            return back()->with('error', 'Minimum transfer amount is ৳' . number_format($feeCalculation['min_amount'], 2));
        }
        
        if ($amount > $feeCalculation['max_amount']) {
            return back()->with('error', 'Maximum transfer amount is ৳' . number_format($feeCalculation['max_amount'], 2));
        }

        // Find recipient by email, phone, or user ID
        $recipient = User::where('email', $request->recipient_identifier)
            ->orWhere('phone', $request->recipient_identifier)
            ->orWhere('id', $request->recipient_identifier)
            ->first();

        if (!$recipient) {
            return back()->with('error', 'Recipient not found. Please check the email, phone, or user ID.');
        }

        if ($recipient->id === $user->id) {
            return back()->with('error', 'You cannot transfer to yourself.');
        }

        // Check available balance
        $availableBalance = $walletType === 'interest_wallet' ? $user->interest_wallet : ($walletType === 'deposit_wallet' ? $user->deposit_wallet : $user->interest_wallet);
        if ($totalDeduction > $availableBalance) {
            return back()->with('error', 'Insufficient balance. Required: ৳' . number_format($totalDeduction, 2) . ' (including ৳' . number_format($transferFee, 2) . ' fee)');
        }

        try {
            DB::beginTransaction();

            // Create outgoing transaction for sender
            $outTransaction = new Transaction();
            $outTransaction->user_id = $user->id;
            $outTransaction->transaction_id = 'TF' . time() . rand(1000, 9999);
            $outTransaction->type = 'transfer_out';
            $outTransaction->amount = $amount;
            $outTransaction->fee = $transferFee;
            $outTransaction->wallet_type = $walletType;
            $outTransaction->reference_type = 'user';
            $outTransaction->reference_id = $recipient->id;
            $outTransaction->note = $request->note;
            $outTransaction->status = 'completed';
            $outTransaction->description = 'Transfer to ' . $recipient->name . ' (' . $recipient->email . ')';
            $outTransaction->save();

            // Create incoming transaction for recipient (always goes to deposit_wallet)
            $inTransaction = new Transaction();
            $inTransaction->user_id = $recipient->id;
            $inTransaction->transaction_id = 'TR' . time() . rand(1000, 9999);
            $inTransaction->type = 'transfer_in';
            $inTransaction->amount = $amount;
            $inTransaction->wallet_type = 'deposit_wallet';
            $inTransaction->reference_type = 'user';
            $inTransaction->reference_id = $user->id;
            $inTransaction->note = $request->note;
            $inTransaction->status = 'completed';
            $inTransaction->description = 'Transfer from ' . $user->name . ' (' . $user->email . ')';
            $inTransaction->save();

            // Deduct from sender's wallet
            if ($walletType === 'deposit_wallet') {
                User::where('id', $user->id)->decrement('deposit_wallet', $totalDeduction);
            } else {
                User::where('id', $user->id)->decrement('interest_wallet', $totalDeduction);
            }

            // Add to recipient's balance
            User::where('id', $recipient->id)->increment('deposit_wallet', $amount);

            DB::commit();

            return back()->with('success', 'Transfer completed successfully! ৳' . number_format($amount, 2) . ' sent to ' . $recipient->name . '. Transaction ID: #' . $outTransaction->id);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error processing transfer. Please try again.');
        }
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query');
        $currentUserId = Auth::id();
        
        if (strlen($query) < 3) {
            return response()->json(['users' => []]);
        }
        
        $users = User::where('id', '!=', $currentUserId)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'email', 'phone')
            ->limit(10)
            ->get();
            
        return response()->json(['users' => $users]);
    }

    public function wallet()
    {
        $user = Auth::user();
        $walletTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);
        
        $totalEarnings = Commission::where('user_id', $user->id)->sum('commission_amount');
        $totalWithdrawals = Transaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->sum('amount');
        
        return view('member.wallet', compact('user', 'walletTransactions', 'totalEarnings', 'totalWithdrawals'));
    }

    // Rank & Achievements
    public function rank()
    {
        $user = Auth::user();
        
        try {
            // Initialize binary rank service
            $binaryRankService = new \App\Services\BinaryRankService();
            
            // Get user's rank status
            $rankStatus = $binaryRankService->getUserRankStatus($user->id);
            
            // Get all rank structures
            $rankStructures = $binaryRankService->getAllRankStructures();
            
            // Get user's rank achievements
            $userRanks = \App\Models\BinaryRankAchievement::where('user_id', $user->id)->get();
            
            // Initialize ranks if not exists
            if ($userRanks->isEmpty()) {
                $binaryRankService->initializeUserRanks($user->id);
                $userRanks = \App\Models\BinaryRankAchievement::where('user_id', $user->id)->get();
            }
            
            // Process achievements based on current points
            try {
                $binaryRankService->processUserRankAchievements($user->id);
                // Reload user ranks after processing
                $userRanks = \App\Models\BinaryRankAchievement::where('user_id', $user->id)->get();
                $rankStatus = $binaryRankService->getUserRankStatus($user->id);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error processing rank achievements for user {$user->id}: " . $e->getMessage());
            }
            
            return view('member.rank', compact('user', 'rankStatus', 'rankStructures', 'userRanks'));
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error loading rank page for user {$user->id}: " . $e->getMessage());
            
            // Fallback data to prevent errors
            $rankStatus = [
                'current_rank' => null,
                'next_rank' => null,
                'left_points' => 0,
                'right_points' => 0,
                'progress_to_next' => 0,
                'monthly_qualified' => false,
                'consecutive_months' => 0
            ];
            $rankStructures = collect([]);
            $userRanks = collect([]);
            
            return view('member.rank', compact('user', 'rankStatus', 'rankStructures', 'userRanks'))
                ->with('error', 'Unable to load rank data. Please try again later.');
        }
    }

    // Report Methods
    public function salesReport()
    {
        $user = Auth::user();
        $salesData = Order::where('customer_id', $user->id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('member.reports.sales', compact('user', 'salesData'));
    }

    public function commissionReport()
    {
        $user = Auth::user();
        $commissionData = Commission::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as date, commission_type, SUM(commission_amount) as total')
            ->groupBy('date', 'commission_type')
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('member.reports.commission', compact('user', 'commissionData'));
    }

    public function teamReport()
    {
        $user = Auth::user();
        $teamData = $this->getTeamPerformance($user);
        
        return view('member.reports.team', compact('user', 'teamData'));
    }

    public function payoutReport()
    {
        $user = Auth::user();
        $payoutData = Transaction::where('user_id', $user->id)
            ->where('type', 'payout')
            ->latest()
            ->paginate(15);
        
        return view('member.reports.payout', compact('user', 'payoutData'));
    }

    // Business Methods
    public function products()
    {
        $user = Auth::user();
        // Get products related to the user (if they have any)
        $products = collect(); // Placeholder
        
        return view('member.products', compact('user', 'products'));
    }

    // Support & Training
    public function training()
    {
        $user = Auth::user();
        $trainingMaterials = collect([
            ['title' => 'Getting Started Guide', 'type' => 'PDF', 'url' => '#'],
            ['title' => 'Marketing Strategies', 'type' => 'Video', 'url' => '#'],
            ['title' => 'Product Knowledge', 'type' => 'PDF', 'url' => '#'],
        ]);
        
        return view('member.training', compact('user', 'trainingMaterials'));
    }

    public function support()
    {
        $user = Auth::user();
        // Get support tickets or FAQ
        $supportTickets = collect(); // Placeholder
        
        return view('member.support', compact('user', 'supportTickets'));
    }

    // Helper Methods
    private function buildGenealogyTree($user)
    {
        // Get sponsor information directly from database
        $sponsorName = null;
        if ($user->sponsor_id) {
            $sponsor = User::find($user->sponsor_id);
            $sponsorName = $sponsor ? $sponsor->name : null;
        }
        
        $genealogy = [
            'root' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code ?? 'REF' . $user->id,
                'join_date' => $user->created_at,
                'status' => $user->status ?? 'active',
                'level' => 0,
                'sponsor_id' => $user->sponsor_id,
                'sponsor_name' => $sponsorName,
            ],
            'downlines' => $this->getGenealogyDownlines($user, 1, 10), // Get 10 levels deep
            'uplines' => $this->getGenealogyUplines($user),
            'statistics' => $this->getGenealogyStatistics($user)
        ];
        
        return $genealogy;
    }
    
    private function getGenealogyDownlines($user, $currentLevel, $maxLevel)
    {
        if ($currentLevel > $maxLevel) {
            return [];
        }
        
        $downlines = [];
        $directReferrals = User::where('sponsor_id', $user->id)
            ->with(['sponsor', 'orders'])
            ->get();
        
        foreach ($directReferrals as $referral) {
            $business = $referral->orders()->sum('total_amount') ?? 0;
            $downlineCount = $this->getTotalDownlineCount($referral);
            
            // Get sponsor name directly from database
            $sponsorName = 'N/A';
            if ($referral->sponsor_id) {
                $sponsor = User::find($referral->sponsor_id);
                $sponsorName = $sponsor ? $sponsor->name : 'N/A';
            }
            
            $downlineData = [
                'id' => $referral->id,
                'name' => $referral->name,
                'email' => $referral->email,
                'phone' => $referral->phone,
                'referral_code' => $referral->referral_code ?? 'REF' . $referral->id,
                'join_date' => $referral->created_at,
                'status' => $referral->status ?? 'active',
                'level' => $currentLevel,
                'business' => $business,
                'downline_count' => $downlineCount,
                'sponsor_id' => $referral->sponsor_id,
                'sponsor_name' => $sponsorName,
                'has_downline' => $downlineCount > 0,
                'children' => $this->getGenealogyDownlines($referral, $currentLevel + 1, $maxLevel)
            ];
            
            $downlines[] = $downlineData;
        }
        
        return $downlines;
    }
    
    private function getGenealogyUplines($user)
    {
        $uplines = [];
        $currentUser = $user;
        $level = 1;
        
        while ($currentUser->sponsor_id && $level <= 10) {
            // Load sponsor relationship if not already loaded
            $sponsor = User::with('orders')->find($currentUser->sponsor_id);
            
            if (!$sponsor) {
                break; // No more sponsors found
            }
            
            $business = $sponsor->orders()->sum('total_amount') ?? 0;
            
            $uplineData = [
                'id' => $sponsor->id,
                'name' => $sponsor->name,
                'email' => $sponsor->email,
                'referral_code' => $sponsor->referral_code ?? 'REF' . $sponsor->id,
                'join_date' => $sponsor->created_at,
                'status' => $sponsor->status ?? 'active',
                'level' => $level,
                'business' => $business,
                'downline_count' => $this->getTotalDownlineCount($sponsor),
            ];
            
            $uplines[] = $uplineData;
            $currentUser = $sponsor;
            $level++;
        }
        
        return $uplines;
    }
    
    private function getGenealogyStatistics($user)
    {
        $totalNetwork = $this->getTotalDownlineCount($user);
        $activeMembers = User::whereIn('id', $this->getAllDownlineIds($user))
            ->where('status', 'active')
            ->count();
        $totalBusiness = User::whereIn('id', $this->getAllDownlineIds($user))
            ->with('orders')
            ->get()
            ->sum(function($member) {
                return $member->orders->sum('total_amount');
            });
        $directReferrals = User::where('sponsor_id', $user->id)->count();
        
        return [
            'total_network' => $totalNetwork,
            'active_members' => $activeMembers,
            'total_business' => $totalBusiness,
            'direct_referrals' => $directReferrals,
            'levels_deep' => $this->getMaxLevelDepth($user)
        ];
    }
    
    private function getAllDownlineIds($user, $ids = [])
    {
        $directReferrals = User::where('sponsor_id', $user->id)->pluck('id')->toArray();
        $ids = array_merge($ids, $directReferrals);
        
        foreach ($directReferrals as $referralId) {
            $referral = User::find($referralId);
            if ($referral) {
                $ids = $this->getAllDownlineIds($referral, $ids);
            }
        }
        
        return array_unique($ids);
    }
    
    private function getMaxLevelDepth($user, $currentLevel = 0)
    {
        $directReferrals = User::where('sponsor_id', $user->id)->get();
        
        if ($directReferrals->isEmpty()) {
            return $currentLevel;
        }
        
        $maxDepth = $currentLevel + 1;
        
        foreach ($directReferrals as $referral) {
            $depth = $this->getMaxLevelDepth($referral, $currentLevel + 1);
            $maxDepth = max($maxDepth, $depth);
        }
        
        return $maxDepth;
    }

    private function buildBinaryTree($user)
    {
        // Get real binary tree data from database
        $leftDownline = $user->leftDownline;
        $rightDownline = $user->rightDownline;
        
        // Get Level 2 users (downlines of Level 1 users)
        $leftLeftDownline = $leftDownline ? $leftDownline->leftDownline : null;
        $leftRightDownline = $leftDownline ? $leftDownline->rightDownline : null;
        $rightLeftDownline = $rightDownline ? $rightDownline->leftDownline : null;
        $rightRightDownline = $rightDownline ? $rightDownline->rightDownline : null;
        
        // Calculate leg points for main user
        $leftLegPoints = $this->calculateLegPoints($user->id, 'left');
        $rightLegPoints = $this->calculateLegPoints($user->id, 'right');
        
        return [
            'main_user' => [
                'id' => $user->id,
                'name' => $user->username ?: 'user_' . $user->id,
                'email' => $user->email,
                'left_count' => $user->leftDownlines()->count(),
                'right_count' => $user->rightDownlines()->count(),
                'total_business' => $user->total_points_earned ?: 0,
                'total_points' => $user->total_points_earned ?: 0,
                'active_points' => $user->active_points ?: 0,
                'left_leg_points' => $leftLegPoints,
                'right_leg_points' => $rightLegPoints,
                'rank' => $user->rank ?: 'Bronze',
                'exists' => true,
                'avatar' => $user->avatar_url
            ],
            'level_1' => [
                'left' => $leftDownline ? [
                    'id' => $leftDownline->id,
                    'name' => $leftDownline->username ?: 'user_' . $leftDownline->id,
                    'email' => $leftDownline->email,
                    'left_count' => $leftDownline->leftDownlines()->count(),
                    'right_count' => $leftDownline->rightDownlines()->count(),
                    'total_business' => $leftDownline->total_points_earned ?: 0,
                    'total_points' => $leftDownline->total_points_earned ?: 0,
                    'active_points' => $leftDownline->active_points ?: 0,
                    'rank' => $leftDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $leftDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ],
                'right' => $rightDownline ? [
                    'id' => $rightDownline->id,
                    'name' => $rightDownline->username ?: 'user_' . $rightDownline->id,
                    'email' => $rightDownline->email,
                    'left_count' => $rightDownline->leftDownlines()->count(),
                    'right_count' => $rightDownline->rightDownlines()->count(),
                    'total_business' => $rightDownline->total_points_earned ?: 0,
                    'total_points' => $rightDownline->total_points_earned ?: 0,
                    'active_points' => $rightDownline->active_points ?: 0,
                    'rank' => $rightDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $rightDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ]
            ],
            'level_2' => [
                'left_left' => $leftLeftDownline ? [
                    'id' => $leftLeftDownline->id,
                    'name' => $leftLeftDownline->username ?: 'user_' . $leftLeftDownline->id,
                    'email' => $leftLeftDownline->email,
                    'left_count' => $leftLeftDownline->leftDownlines()->count(),
                    'right_count' => $leftLeftDownline->rightDownlines()->count(),
                    'total_business' => $leftLeftDownline->total_points_earned ?: 0,
                    'total_points' => $leftLeftDownline->total_points_earned ?: 0,
                    'active_points' => $leftLeftDownline->active_points ?: 0,
                    'rank' => $leftLeftDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $leftLeftDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ],
                'left_right' => $leftRightDownline ? [
                    'id' => $leftRightDownline->id,
                    'name' => $leftRightDownline->username ?: 'user_' . $leftRightDownline->id,
                    'email' => $leftRightDownline->email,
                    'left_count' => $leftRightDownline->leftDownlines()->count(),
                    'right_count' => $leftRightDownline->rightDownlines()->count(),
                    'total_business' => $leftRightDownline->total_points_earned ?: 0,
                    'total_points' => $leftRightDownline->total_points_earned ?: 0,
                    'active_points' => $leftRightDownline->active_points ?: 0,
                    'rank' => $leftRightDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $leftRightDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ],
                'right_left' => $rightLeftDownline ? [
                    'id' => $rightLeftDownline->id,
                    'name' => $rightLeftDownline->username ?: 'user_' . $rightLeftDownline->id,
                    'email' => $rightLeftDownline->email,
                    'left_count' => $rightLeftDownline->leftDownlines()->count(),
                    'right_count' => $rightLeftDownline->rightDownlines()->count(),
                    'total_business' => $rightLeftDownline->total_points_earned ?: 0,
                    'total_points' => $rightLeftDownline->total_points_earned ?: 0,
                    'active_points' => $rightLeftDownline->active_points ?: 0,
                    'rank' => $rightLeftDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $rightLeftDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ],
                'right_right' => $rightRightDownline ? [
                    'id' => $rightRightDownline->id,
                    'name' => $rightRightDownline->username ?: 'user_' . $rightRightDownline->id,
                    'email' => $rightRightDownline->email,
                    'left_count' => $rightRightDownline->leftDownlines()->count(),
                    'right_count' => $rightRightDownline->rightDownlines()->count(),
                    'total_business' => $rightRightDownline->total_points_earned ?: 0,
                    'total_points' => $rightRightDownline->total_points_earned ?: 0,
                    'active_points' => $rightRightDownline->active_points ?: 0,
                    'rank' => $rightRightDownline->rank ?: 'Bronze',
                    'exists' => true,
                    'avatar' => $rightRightDownline->avatar_url
                ] : [
                    'id' => null,
                    'name' => 'Available Position',
                    'email' => '',
                    'left_count' => 0,
                    'right_count' => 0,
                    'total_business' => 0,
                    'total_points' => 0,
                    'active_points' => 0,
                    'rank' => '',
                    'exists' => false,
                    'avatar' => 'admin-assets/images/users/1.jpg'
                ]
            ]
        ];
    }

    private function getNewGenerationLevels($user)
    {
        $generations = [];
        $currentLevel = 1;
        $currentUsers = [$user];
        
        while (!empty($currentUsers) && $currentLevel <= 20) {
            $nextLevelUsers = [];
            $generationData = [
                'level' => $currentLevel,
                'members' => [],
                'total_members' => 0,
                'active_members' => 0,
                'total_business' => 0,
                'points' => \App\Models\GenerationIncome::getPointsForLevel($currentLevel),
                'amount_per_member' => \App\Models\GenerationIncome::calculateAmount(
                    \App\Models\GenerationIncome::getPointsForLevel($currentLevel)
                ),
                'bonus_rate' => \App\Models\GenerationIncome::getPointsForLevel($currentLevel) * 0.5, // Convert points to percentage for display
                'total_earned' => 0,
                'total_pending' => 0,
                'total_invalid' => 0
            ];
            
            foreach ($currentUsers as $currentUser) {
                // Get direct referrals for each user in current level
                $directReferrals = User::where('sponsor_id', $currentUser->id)
                    ->with(['sponsor', 'orders'])
                    ->get();
                
                foreach ($directReferrals as $referral) {
                    $business = $referral->orders()->sum('total_amount') ?? 0;
                    
                    // Get generation income for this member at this level
                    $generationIncome = \App\Models\GenerationIncome::where('user_id', $user->id)
                        ->where('from_user_id', $referral->id)
                        ->where('generation_level', $currentLevel)
                        ->first();
                    
                    $earnedAmount = $generationIncome ? $generationIncome->amount : 0;
                    $status = $generationIncome ? $generationIncome->status : 'no_income';
                    
                    $memberData = [
                        'id' => $referral->id,
                        'name' => $referral->name,
                        'email' => $referral->email,
                        'phone' => $referral->phone,
                        'username' => $referral->username,
                        'join_date' => $referral->created_at,
                        'status' => $referral->status ?? 'active',
                        'business' => $business,
                        'generation_income' => $earnedAmount,
                        'income_status' => $status,
                        'points' => $generationData['points'],
                        'potential_amount' => $generationData['amount_per_member'],
                        'downline_count' => $this->getDownlineCount($referral),
                        'has_downline' => $this->hasDownline($referral)
                    ];
                    
                    $generationData['members'][] = $memberData;
                    $generationData['total_business'] += $business;
                    $generationData['active_members'] += ($referral->status == 'active') ? 1 : 0;
                    
                    // Add to totals based on status
                    if ($status === 'paid') {
                        $generationData['total_earned'] += $earnedAmount;
                    } elseif ($status === 'pending') {
                        $generationData['total_pending'] += $earnedAmount;
                    } elseif ($status === 'invalid') {
                        $generationData['total_invalid'] += $earnedAmount;
                    }
                    
                    $nextLevelUsers[] = $referral;
                }
            }
            
            $generationData['total_members'] = count($generationData['members']);
            
            if ($generationData['total_members'] > 0) {
                $generations[] = $generationData;
            }
            
            $currentUsers = $nextLevelUsers;
            $currentLevel++;
            
            // Break if no more users
            if (empty($nextLevelUsers)) {
                break;
            }
        }
        
        return $generations;
    }
    
    private function getBonusRate($level)
    {
        $rates = [10, 8, 6, 5, 4, 3, 2.5, 2, 1.5, 1];
        
        if ($level <= count($rates)) {
            return $rates[$level - 1];
        }
        
        // For levels beyond 10, use diminishing returns
        return max(0.5, 1 - ($level - 10) * 0.1);
    }
    
    private function getDownlineCount($user)
    {
        return User::where('sponsor_id', $user->id)->count();
    }
    
    private function hasDownline($user)
    {
        return User::where('sponsor_id', $user->id)->exists();
    }

    private function calculateRankProgress($user)
    {
        // Calculate rank progress
        return 0;
    }

    private function getUserAchievements($user)
    {
        // Get user achievements
        return [];
    }

    private function getTeamPerformance($user)
    {
        // Get team performance data
        return [];
    }

    private function getTotalDownlineCount($user)
    {
        // Get total downline count recursively
        $count = 0;
        $directReferrals = User::where('sponsor_id', $user->id)->get();
        
        foreach ($directReferrals as $referral) {
            $count += 1 + $this->getTotalDownlineCount($referral);
        }
        
        return $count;
    }

    private function getBinaryVolume($user, $side)
    {
        // Get binary volume for left or right side
        // This would typically involve calculating the total volume/sales
        // in the binary tree structure
        return 0;
    }

    /**
     * Show add fund page
     */
    public function addFund()
    {
        $user = Auth::user();
        $paymentMethods = FeeCalculator::getPaymentMethods();

        // Check for location-based vendors
        $matchingVendors = LocationMatcher::getMatchingVendors($user);
        $hasMatchingVendors = LocationMatcher::hasMatchingVendors($user);
        
        // Get fund options based on location match
        $fundOptions = LocationMatcher::getFundOptions($user);
        
        $userLocationString = LocationMatcher::getUserLocationString($user);

        return view('member.add-fund', compact(
            'user', 
            'paymentMethods', 
            'matchingVendors',
            'hasMatchingVendors',
            'fundOptions',
            'userLocationString'
        ));
    }

    /**
     * Process add fund request
     */
    public function processAddFund(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:100000',
            'payment_method' => 'required|in:bkash,nagad,rocket,bank_transfer,upay',
            'vendor_id' => 'nullable|exists:users,id',
            'sender_number' => 'required_unless:payment_method,bank_transfer|nullable|string|max:20',
            'bank_account_number' => 'required_if:payment_method,bank_transfer|nullable|string|max:30',
            'bank_account_name' => 'required_if:payment_method,bank_transfer|nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'transaction_id' => 'required|string|max:50|unique:payment_transactions,transaction_id',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'transaction_id.unique' => 'This transaction ID has already been used. Please enter a different transaction ID or check if you have already submitted this payment.',
            'bank_account_number.required_if' => 'Account number is required for bank transfer.',
            'bank_account_name.required_if' => 'Account holder name is required for bank transfer.'
        ]);

        $user = Auth::user();
        $amount = (float) $request->amount;
        $paymentMethod = $request->payment_method;
        $vendorId = $request->vendor_id;

        // Get vendor information if specified
        $vendor = null;
        $vendorInfo = null;
        if ($vendorId) {
            $vendor = User::where('id', $vendorId)
                ->where('role', 'vendor')
                ->where('status', 'active')
                ->first();
                
            if ($vendor) {
                $vendorInfo = [
                    'vendor_id' => $vendor->id,
                    'vendor_name' => $vendor->name,
                    'shop_name' => $vendor->shop_name,
                    'vendor_phone' => $vendor->phone,
                    'vendor_location' => LocationMatcher::getUserLocationString($vendor)
                ];
            }
        }

        // Calculate fee using dynamic fee system
        $feeCalculation = FeeCalculator::calculateFundFee($paymentMethod, $amount);
        $fee = $feeCalculation['fee'];
        $netAmount = $feeCalculation['net_amount'];
        
        // Validate amount limits
        if ($amount < $feeCalculation['min_amount']) {
            return back()->with('error', 'Minimum deposit amount is ৳' . number_format($feeCalculation['min_amount'], 2));
        }
        
        if ($amount > $feeCalculation['max_amount']) {
            return back()->with('error', 'Maximum deposit amount is ৳' . number_format($feeCalculation['max_amount'], 2));
        }

        // Handle receipt upload
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('fund-receipts', 'public');
        }

        try {
            // Prepare metadata for different payment methods
            $metadata = [];
            if ($paymentMethod === 'bank_transfer') {
                $metadata = [
                    'bank_account_number' => $request->bank_account_number,
                    'bank_account_name' => $request->bank_account_name,
                    'bank_branch' => $request->bank_branch
                ];
            }

            // Add vendor information to metadata if available
            if ($vendorInfo) {
                $metadata['vendor_info'] = $vendorInfo;
            }

            // Determine description based on vendor or company
            $description = 'Fund addition via ' . ucfirst(str_replace('_', ' ', $paymentMethod));
            if ($vendor) {
                $description .= ' (Vendor: ' . ($vendor->shop_name ?? $vendor->name) . ')';
            } else {
                $description .= ' (Company Direct)';
            }

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'type' => 'fund_addition',
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'sender_number' => $request->sender_number,
                'transaction_id' => $request->transaction_id,
                'receipt_path' => $receiptPath,
                'description' => $description,
                'currency' => 'BDT',
                'metadata' => !empty($metadata) ? $metadata : null
            ]);

            return redirect()->route('member.fund-history')
                ->with('success', 'Fund request submitted successfully! Your deposit will be processed within 24 hours.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate transaction ID error
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
                return back()->withInput()->with('error', 'This transaction ID has already been used. Please enter a different transaction ID or check if you have already submitted this payment.');
            }
            
            // Handle other database errors
            return back()->withInput()->with('error', 'Failed to submit fund request. Please try again.');
        }
    }

    /**
     * Show fund history
     */
    public function fundHistory()
    {
        $user = Auth::user();
        
        // Use PaymentTransaction instead of transactions relationship
        $transactions = PaymentTransaction::where('user_id', $user->id)
            ->where('type', 'fund_addition')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $summary = [
            'total_added' => PaymentTransaction::where('user_id', $user->id)->where('type', 'fund_addition')->where('status', 'approved')->sum('net_amount'),
            'pending_amount' => PaymentTransaction::where('user_id', $user->id)->where('type', 'fund_addition')->where('status', 'pending')->sum('net_amount'),
            'total_transactions' => PaymentTransaction::where('user_id', $user->id)->where('type', 'fund_addition')->count(),
            'total_fees' => PaymentTransaction::where('user_id', $user->id)->where('type', 'fund_addition')->where('status', 'approved')->sum('fee')
        ];

        return view('member.fund-history', compact('user', 'transactions', 'summary'));
    }
    
    /**
     * Get a specific fund transaction for modal display
     */
    public function getFundTransaction($id)
    {
        try {
            $transaction = PaymentTransaction::where('user_id', Auth::id())
                ->where('id', $id)
                ->where('type', 'fund_addition')
                ->firstOrFail();
            
            // Add receipt URL if exists
            if ($transaction->receipt_path) {
                $transaction->receipt_url = Storage::url($transaction->receipt_path);
            }
            
            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or access denied.'
            ], 404);
        }
    }

    /**
     * Get data for compact view
     */
    public function getCompactData()
    {
        try {
            $user = Auth::user();
            
            // Get recent members across all levels
            $recentMembers = collect();
            $directReferrals = User::where('sponsor_id', $user->id)->get();
            
            foreach ($directReferrals as $member) {
                $recentMembers->push([
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'referral_code' => $member->referral_code,
                    'level' => 1,
                    'status' => $member->status ?? 'active',
                    'avatar' => $member->avatar ?? '/admin-assets/images/users/default.jpg',
                    'created_at' => $member->created_at
                ]);
                
                // Add their referrals too (level 2)
                $secondLevel = User::where('sponsor_id', $member->id)->take(3)->get();
                foreach ($secondLevel as $subMember) {
                    $recentMembers->push([
                        'id' => $subMember->id,
                        'name' => $subMember->name,
                        'email' => $subMember->email,
                        'referral_code' => $subMember->referral_code,
                        'level' => 2,
                        'status' => $subMember->status ?? 'active',
                        'avatar' => $subMember->avatar ?? '/admin-assets/images/users/default.jpg',
                        'created_at' => $subMember->created_at
                    ]);
                }
            }
            
            // Sort by created_at and take latest 10
            $members = $recentMembers->sortByDesc('created_at')->take(10)->values();
            
            return response()->json([
                'success' => true,
                'members' => $members
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading compact data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data for level view
     */
    public function getLevelData()
    {
        try {
            $user = Auth::user();
            $levels = [];
            
            // Build levels 1-5
            for ($level = 1; $level <= 5; $level++) {
                $members = $this->getMembersAtLevel($user->id, $level);
                
                if (!empty($members)) {
                    $levels[] = [
                        'level' => $level,
                        'count' => count($members),
                        'members' => array_map(function($member) use ($level) {
                            return [
                                'id' => $member['id'],
                                'name' => $member['name'],
                                'email' => $member['email'],
                                'referral_code' => $member['referral_code'],
                                'level' => $level,
                                'status' => $member['status'] ?? 'active',
                                'avatar' => $member['avatar'] ?? '/admin-assets/images/users/default.jpg',
                                'sponsor_name' => $member['sponsor_name'] ?? 'Unknown'
                            ];
                        }, $members)
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'levels' => $levels
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading level data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data for hierarchy view
     */
    public function getHierarchyData()
    {
        try {
            $user = Auth::user();
            
            // Get direct referrals (level 1)
            $directReferrals = User::where('sponsor_id', $user->id)->get();
            
            $level1Data = $directReferrals->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'referral_code' => $member->referral_code,
                    'status' => $member->status ?? 'active',
                    'avatar' => $member->avatar ?? '/admin-assets/images/users/default.jpg',
                    'direct_referrals' => User::where('sponsor_id', $member->id)->count(),
                    'created_at' => $member->created_at
                ];
            });
            
            return response()->json([
                'success' => true,
                'level1' => $level1Data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load hierarchy data'
            ], 500);
        }
    }
    
    /**
     * Get real-time wallet balance data in JSON format
     */
    public function getWalletBalance()
    {
        try {
            $user = Auth::user();
            $userFresh = User::find($user->id); // Get fresh data from database
            
            // Real-time wallet balances
            $walletData = [
                'total_balance' => ($userFresh->balance ?? 0) + ($userFresh->deposit_wallet ?? 0) + ($userFresh->interest_wallet ?? 0),
                'deposit_wallet' => $userFresh->deposit_wallet ?? 0,
                'interest_wallet' => $userFresh->interest_wallet ?? 0,
                'main_balance' => $userFresh->balance ?? 0,
                'available_balance' => ($userFresh->deposit_wallet ?? 0) + ($userFresh->interest_wallet ?? 0),
                'pending_balance' => $userFresh->pending_balance ?? 0,
                'withdrawn_amount' => Transaction::where('user_id', $user->id)
                    ->where('type', 'withdrawal')
                    ->where('status', 'completed')
                    ->sum('amount'),
                'total_earnings' => $userFresh->total_earnings ?? 0,
                'reserve_points' => $userFresh->reserve_points ?? 0,
            ];

            // Get pending cashback amount sum from user_daily_cashbacks table
            $pendingCashbackAmount = DB::table('user_daily_cashbacks')
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('cashback_amount');
            
            // Get bonus statistics
            $sponsorBonus = Commission::where('user_id', $user->id)
                ->where('commission_type', 'sponsor')
                ->where('status', 'approved')
                ->sum('commission_amount');
                
            $binaryBonus = BinaryMatching::where('user_id', $user->id)
                ->whereIn('status', ['processed', 'paid'])
                ->sum('matching_bonus');
                
            $teamBonus = Commission::where('user_id', $user->id)
                ->where('commission_type', 'team')
                ->where('status', 'approved')
                ->sum('commission_amount');
                
            $rankBonus = Commission::where('user_id', $user->id)
                ->where('commission_type', 'rank')
                ->where('status', 'approved')
                ->sum('commission_amount');

            // Link Share Bonus - Cumulative from daily link sharing stats
            $linkShareBonus = DB::table('daily_link_sharing_stats')
                ->where('user_id', $user->id)
                ->sum('earnings_amount');
                
            // Rank Salary from transactions
            $rankSalary = DB::table('transactions')
                ->where('user_id', $user->id)
                ->where('type', 'rank_salary')
                ->where('status', 'completed')
                ->sum('amount');
                
            // Cash Back from transactions
            $cashBack = DB::table('transactions')
                ->where('user_id', $user->id)
                ->where('type', 'cashback')
                ->where('status', 'completed')
                ->sum('amount');
                
            // KYC Bonus from transactions
            $kycBonus = DB::table('transactions')
                ->where('user_id', $user->id)
                ->where('type', 'kyc_bonus')
                ->where('status', 'completed')
                ->sum('amount');
            
            $bonusData = [
                'sponsor_bonus' => $sponsorBonus,
                'binary_bonus' => $binaryBonus,
                'team_bonus' => $teamBonus,
                'rank_bonus' => $rankBonus,
                'link_share_bonus' => $linkShareBonus,
                'rank_salary' => $rankSalary,
                'cash_back' => $cashBack,
                'kyc_bonus' => $kycBonus,
                'total_bonus' => $sponsorBonus + $binaryBonus + $teamBonus + $rankBonus + $linkShareBonus,
            ];
            
            return response()->json([
                'success' => true,
                'wallet_stats' => $walletData,
                'bonus_stats' => $bonusData,
                'pending_cashback_amount' => $pendingCashbackAmount,
                'formatted' => [
                    'total_balance' => formatCurrency($walletData['total_balance']),
                    'deposit_wallet' => formatCurrency($walletData['deposit_wallet']),
                    'interest_wallet' => formatCurrency($walletData['interest_wallet']),
                    'reserve_points' => number_format($walletData['reserve_points']),
                    'available_balance' => formatCurrency($walletData['available_balance']),
                    'withdrawn_amount' => formatCurrency($walletData['withdrawn_amount']),
                    'sponsor_bonus' => formatCurrency($bonusData['sponsor_bonus']),
                    'binary_bonus' => formatCurrency($bonusData['binary_bonus']),
                    'team_bonus' => formatCurrency($bonusData['team_bonus']),
                    'rank_bonus' => formatCurrency($bonusData['rank_bonus']),
                    'link_share_bonus' => formatCurrency($bonusData['link_share_bonus']),
                    'rank_salary' => formatCurrency($bonusData['rank_salary']),
                    'cash_back' => formatCurrency($bonusData['cash_back']),
                    'kyc_bonus' => formatCurrency($bonusData['kyc_bonus']),
                    'pending_cashback_amount' => formatCurrency($pendingCashbackAmount),
                ],
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load wallet data'
            ], 500);
        }
    }

    /**
     * Helper method to get members at specific level
     */
    private function getMembersAtLevel($userId, $targetLevel, $currentLevel = 0)
    {
        if ($currentLevel >= $targetLevel) {
            if ($currentLevel == $targetLevel) {
                $user = User::find($userId);
                if ($user) {
                    $sponsor = User::find($user->sponsor_id);
                    return [[
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'referral_code' => $user->referral_code,
                        'status' => $user->status ?? 'active',
                        'avatar' => $user->avatar ?? '/admin-assets/images/users/default.jpg',
                        'sponsor_name' => $sponsor ? $sponsor->name : 'Unknown'
                    ]];
                }
            }
            return [];
        }
        
        $directReferrals = User::where('sponsor_id', $userId)->get();
        $members = [];
        
        foreach ($directReferrals as $referral) {
            $subMembers = $this->getMembersAtLevel($referral->id, $targetLevel, $currentLevel + 1);
            $members = array_merge($members, $subMembers);
        }
        
        return $members;
    }

    /**
     * Get transfer fee information for a wallet type
     */
    public function getTransferFeeInfo(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|in:balance,deposit_wallet,interest_wallet',
            'amount' => 'nullable|numeric|min:0'
        ]);

        $walletType = $request->wallet_type;
        $amount = $request->amount ?: 100; // Default amount for calculation

        $feeInfo = FeeCalculator::calculateTransferFee($walletType, $amount);

        return response()->json([
            'success' => true,
            'wallet_type' => $walletType,
            'fee_info' => $feeInfo,
            'message' => $walletType === 'interest_wallet' 
                ? 'Interest wallet transfers are completely free!' 
                : 'Transfer fee will be calculated based on amount'
        ]);
    }

    /**
     * Get withdrawal fee information for a wallet type
     */
    public function getWithdrawalFeeInfo(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|in:balance,deposit_wallet,interest_wallet',
            'amount' => 'nullable|numeric|min:0'
        ]);

        $walletType = $request->wallet_type;
        $amount = $request->amount ?: 100; // Default amount for calculation

        $feeInfo = FeeCalculator::calculateWithdrawalFee($walletType, $amount);
        $settings = GeneralSetting::first();

        return response()->json([
            'success' => true,
            'wallet_type' => $walletType,
            'amount' => $amount,
            'fee_info' => $feeInfo,
            'settings' => [
                'balance_withdrawal_fee_type' => $settings->withdrawal_balance_fee_type,
                'balance_withdrawal_fee_amount' => $settings->withdrawal_balance_fee_amount,
                'deposit_withdrawal_fee_type' => $settings->withdrawal_deposit_fee_type,
                'deposit_withdrawal_fee_amount' => $settings->withdrawal_deposit_fee_amount,
                'interest_withdrawal_fee_type' => $settings->withdrawal_interest_fee_type,
                'interest_withdrawal_fee_amount' => $settings->withdrawal_interest_fee_amount,
            ],
            'message' => 'Withdrawal fee calculated successfully'
        ]);
    }

    /**
     * Check if a target user is in the current user's binary network
     */
    private function isUserInNetwork($currentUser, $targetUser)
    {
        // If target user is the current user
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Check if target user is in current user's downline (binary tree)
        $queue = [$currentUser->id];
        $visited = [];
        $maxDepth = 10; // Limit depth to prevent infinite loops
        $currentDepth = 0;

        while (!empty($queue) && $currentDepth < $maxDepth) {
            $levelSize = count($queue);
            
            for ($i = 0; $i < $levelSize; $i++) {
                $userId = array_shift($queue);
                
                if (in_array($userId, $visited)) {
                    continue;
                }
                
                $visited[] = $userId;
                
                // Check left and right binary positions
                $leftChild = User::where('upline_id', $userId)
                               ->where('position', 'left')
                               ->first();
                               
                $rightChild = User::where('upline_id', $userId)
                                ->where('position', 'right')
                                ->first();
                
                if ($leftChild) {
                    if ($leftChild->id === $targetUser->id) {
                        return true;
                    }
                    $queue[] = $leftChild->id;
                }
                
                if ($rightChild) {
                    if ($rightChild->id === $targetUser->id) {
                        return true;
                    }
                    $queue[] = $rightChild->id;
                }
            }
            
            $currentDepth++;
        }

        // Check if current user is in target user's upline
        $currentParent = $targetUser->upline_id;
        $uplineDepth = 0;
        
        while ($currentParent && $uplineDepth < $maxDepth) {
            if ($currentParent === $currentUser->id) {
                return true;
            }
            
            $parent = User::find($currentParent);
            if (!$parent) {
                break;
            }
            
            $currentParent = $parent->upline_id;
            $uplineDepth++;
        }

        return false;
    }

    /**
     * Calculate total points for a specific leg (left or right)
     */
    private function calculateLegPoints($userId, $leg)
    {
        // Get all users in the specified leg recursively
        $allLegUsers = $this->getAllLegUsers($userId, $leg);
        
        // Sum up the total points earned by all users in this leg
        $totalPoints = User::whereIn('id', $allLegUsers)
                          ->sum('total_points_earned');

        return $totalPoints ?: 0;
    }

    /**
     * Recursively get all users in a specific leg
     */
    private function getAllLegUsers($userId, $leg, $depth = 0, $maxDepth = 20)
    {
        // Prevent infinite recursion
        if ($depth > $maxDepth) {
            return [];
        }

        // Get direct children in this leg
        $directChildren = User::where('upline_id', $userId)
                             ->where('position', $leg)
                             ->pluck('id')
                             ->toArray();

        $allUsers = $directChildren;

        // Get children of children recursively
        foreach ($directChildren as $childId) {
            $leftChildren = $this->getAllLegUsers($childId, 'left', $depth + 1, $maxDepth);
            $rightChildren = $this->getAllLegUsers($childId, 'right', $depth + 1, $maxDepth);
            $allUsers = array_merge($allUsers, $leftChildren, $rightChildren);
        }

        return array_unique($allUsers);
    }

    /**
     * Show vendor application form (for affiliate members)
     */
    public function vendorApplication()
    {
        $user = Auth::user();
        
        // Check if user is affiliate
        if ($user->role !== 'affiliate') {
            return redirect()->route('member.dashboard')
                ->with('error', 'Only affiliate members can apply to become vendors.');
        }
        
        // Check if user already has a vendor application
        $existingApplication = VendorApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        // Add applied_at for blade template compatibility
        if ($existingApplication) {
            $existingApplication->applied_at = $existingApplication->created_at;
        }
        
        // Check if user is already a vendor
        if ($user->role === 'vendor') {
            return redirect()->route('member.dashboard')
                ->with('info', 'You are already a vendor.');
        }
        
        return view('member.vendor-application', compact('user', 'existingApplication'));
    }

    /**
     * Submit vendor application
     */
    public function submitVendorApplication(Request $request)
    {
        $user = Auth::user();
        
        // Validate request - now matches settings form structure
        $request->validate([
            'business_name' => 'required|string|min:3|max:100',
            'contact_person' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'business_description' => 'required|string|min:20|max:1000',
            'website' => 'nullable|url|max:255',
        ]);
        
        // Check if user is affiliate
        if ($user->role !== 'affiliate') {
            return redirect()->back()
                ->with('error', 'Only affiliate members can apply to become vendors.');
        }
        
        // Check for existing pending application
        $existingApplication = VendorApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        
        if ($existingApplication) {
            $status = ucfirst($existingApplication->status);
            return redirect()->back()
                ->with('warning', "You already have a {$status} vendor application. Please wait for admin review.");
        }
        
        try {
            DB::beginTransaction();
            
            // Create vendor application using database column names directly
            $application = VendorApplication::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'business_description' => $request->business_description,
                'website' => $request->website,
                'status' => 'pending',
            ]);
            
            // Load sponsor relationship safely
            $sponsorUser = null;
            $sponsorUsername = null;
            if ($user->sponsor_id) {
                $sponsorUser = User::find($user->sponsor_id);
                $sponsorUsername = $sponsorUser ? $sponsorUser->username : null;
            }
            
            // Create admin notification
            AdminNotification::create([
                'type' => 'vendor_application',
                'title' => 'New Vendor Application (Member)',
                'message' => "Affiliate member {$user->name} ({$user->username}) has submitted a vendor application for '{$request->business_name}'.",
                'data' => json_encode([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'application_id' => $application->id,
                    'business_name' => $request->business_name,
                    'contact_person' => $request->contact_person,
                    'affiliate_data' => [
                        'sponsor_id' => $user->sponsor_id ?? null,
                        'sponsor_username' => $sponsorUsername,
                        'total_downlines' => User::where('sponsor_id', $user->id)->count(),
                        'points' => $user->points ?? 0,
                        'affiliate_since' => $user->created_at,
                    ],
                ]),
                'is_read' => false,
            ]);
            
            // Log the vendor application
            Log::info("Vendor application submitted", [
                'user_id' => $user->id,
                'username' => $user->username,
                'business_name' => $request->business_name,
                'application_id' => $application->id,
            ]);
            
            DB::commit();
            
            return redirect()->route('member.vendor-application')
                ->with('success', 'Your vendor application has been submitted successfully! Admin will review your application within 2-3 business days.');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error submitting vendor application: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to submit vendor application. Please try again.');
        }
    }

    /**
     * Calculate total network size for user
     */
    private function calculateNetworkSize($userId)
    {
        $directReferrals = User::where('sponsor_id', $userId)->count();
        $totalNetwork = $this->countAllDownlines($userId);
        
        return [
            'direct_referrals' => $directReferrals,
            'total_network' => $totalNetwork,
        ];
    }

    /**
     * Count all downlines recursively
     */
    private function countAllDownlines($userId, $depth = 0, $maxDepth = 10)
    {
        if ($depth > $maxDepth) {
            return 0;
        }
        
        $directCount = User::where('sponsor_id', $userId)->count();
        $totalCount = $directCount;
        
        $directReferrals = User::where('sponsor_id', $userId)->pluck('id');
        foreach ($directReferrals as $referralId) {
            $totalCount += $this->countAllDownlines($referralId, $depth + 1, $maxDepth);
        }
        
        return $totalCount;
    }
}
