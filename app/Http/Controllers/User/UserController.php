<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if user is affiliate or general customer
        if ($user->role === 'affiliate') {
            // Affiliate dashboard with MLM data
            $userStats = (object) [
                'current_rank' => 'Silver Executive',
                'total_earnings' => 2850.75,
                'this_month_earnings' => 485.50,
                'direct_referrals' => 12,
                'team_size' => 34,
                'personal_volume' => 1250,
                'team_volume' => 8940,
                'next_rank' => 'Gold Executive',
                'next_rank_requirement' => 15000,
                'rank_progress' => 59.6
            ];

            // Recent commissions for affiliates
            $recentCommissions = collect([
                (object) [
                    'date' => '2024-01-15',
                    'type' => 'Direct Commission',
                    'amount' => 45.00,
                    'from' => 'Sarah Johnson',
                    'product' => 'Wellness Package'
                ],
                (object) [
                    'date' => '2024-01-14',
                    'type' => 'Team Bonus',
                    'amount' => 28.50,
                    'from' => 'Team Sales',
                    'product' => 'Multiple Products'
                ],
                (object) [
                    'date' => '2024-01-13',
                    'type' => 'Direct Commission',
                    'amount' => 32.00,
                    'from' => 'Mike Chen',
                    'product' => 'Beauty Serum Set'
                ]
            ]);

            return view('user.affiliate-dashboard', compact('user', 'userStats', 'recentCommissions'));
        } else {
            // General customer dashboard - simple profile view
            $userInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'Not provided',
                'joined_date' => $user->created_at->format('M d, Y'),
                'total_orders' => 0, // You can implement actual order count
                'total_spent' => 0,  // You can implement actual spent amount
            ];

            return view('user.customer-dashboard', compact('user', 'userInfo'));
        }
    }

    /**
     * Display the member dashboard (for all users).
     */
    public function memberDashboard()
    {
        $user = Auth::user();
        
        // All users can access member dashboard
        // Affiliate dashboard with MLM data
        $userStats = (object) [
            'current_rank' => 'Silver Executive',
            'total_earnings' => 2850.75,
            'this_month_earnings' => 485.50,
            'direct_referrals' => 12,
            'team_size' => 34,
            'personal_volume' => 1250,
            'team_volume' => 8940,
            'next_rank' => 'Gold Executive',
            'next_rank_requirement' => 15000,
            'rank_progress' => 59.6
        ];

        // Recent commissions for affiliates
        $recentCommissions = collect([
            (object) [
                'date' => '2024-01-15',
                'type' => 'Direct Commission',
                'amount' => 45.00,
                'from' => 'Sarah Johnson',
                'product' => 'Wellness Package'
            ],
            (object) [
                'date' => '2024-01-14',
                'type' => 'Team Bonus',
                'amount' => 28.50,
                'from' => 'Team Sales',
                'product' => 'Multiple Products'
            ],
            (object) [
                'date' => '2024-01-13',
                'type' => 'Direct Commission',
                'amount' => 32.00,
                'from' => 'Mike Chen',
                'product' => 'Beauty Serum Set'
            ]
        ]);

        // Additional member dashboard data
        $memberData = (object) [
            'total_members' => 156,
            'active_members' => 142,
            'pending_approvals' => 8,
            'monthly_growth' => 12.5
        ];

        return view('member.dashboard', compact('user', 'userStats', 'recentCommissions', 'memberData'));
    }

    /**
     * Display genealogy/network tree
     */
    public function genealogy()
    {
        // Network tree data (simplified)
        $networkTree = (object) [
            'user' => (object) [
                'name' => 'You',
                'rank' => 'Silver Executive',
                'level' => 0
            ],
            'level_1' => collect([
                (object) [
                    'name' => 'Sarah Johnson',
                    'rank' => 'Bronze',
                    'volume' => 850,
                    'team_count' => 5
                ],
                (object) [
                    'name' => 'Mike Chen',
                    'rank' => 'Silver',
                    'volume' => 1240,
                    'team_count' => 8
                ],
                (object) [
                    'name' => 'David Brown',
                    'rank' => 'Starter',
                    'volume' => 150,
                    'team_count' => 1
                ]
            ]),
            'statistics' => (object) [
                'total_levels' => 5,
                'total_members' => 34,
                'active_this_month' => 28,
                'total_volume' => 8940
            ]
        ];

        return view('user.genealogy', compact('networkTree'));
    }

    /**
     * Display commissions and earnings (for affiliates only)
     */
    public function commissions()
    {
        // Commission history
        $commissions = collect([
            (object) [
                'date' => '2024-01-15',
                'type' => 'Direct Commission',
                'description' => 'Sarah Johnson - Wellness Package',
                'amount' => 45.00,
                'status' => 'paid'
            ],
            (object) [
                'date' => '2024-01-14',
                'type' => 'Team Bonus',
                'description' => 'Team Sales Volume Bonus',
                'amount' => 28.50,
                'status' => 'paid'
            ],
            (object) [
                'date' => '2024-01-13',
                'type' => 'Direct Commission',
                'description' => 'Mike Chen - Beauty Serum Set',
                'amount' => 32.00,
                'status' => 'paid'
            ],
            (object) [
                'date' => '2024-01-12',
                'type' => 'Leadership Bonus',
                'description' => 'Team Achievement Bonus',
                'amount' => 15.75,
                'status' => 'paid'
            ]
        ]);

        // Commission summary
        $commissionSummary = (object) [
            'total_earned' => 2850.75,
            'this_month' => 485.50,
            'pending' => 67.25,
            'this_week' => 121.25,
            'direct_commissions' => 1890.25,
            'team_bonuses' => 750.50,
            'leadership_bonuses' => 210.00
        ];

        return view('user.commissions', compact('commissions', 'commissionSummary'));
    }

    /**
     * Display user profile and settings
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Display training and resources (for affiliates only)
     */
    public function training()
    {
        // Training modules
        $trainingModules = collect([
            (object) [
                'title' => 'Getting Started with MLM',
                'description' => 'Learn the basics of multi-level marketing',
                'duration' => '45 minutes',
                'type' => 'video',
                'completed' => true
            ],
            (object) [
                'title' => 'Product Knowledge Training',
                'description' => 'Deep dive into our product catalog',
                'duration' => '90 minutes',
                'type' => 'video',
                'completed' => true
            ],
            (object) [
                'title' => 'Building Your Network',
                'description' => 'Effective strategies for team building',
                'duration' => '60 minutes',
                'type' => 'video',
                'completed' => false
            ]
        ]);

        // Marketing materials
        $marketingMaterials = collect([
            (object) [
                'title' => 'Product Brochures',
                'type' => 'PDF',
                'size' => '2.5 MB',
                'downloads' => 45
            ],
            (object) [
                'title' => 'Social Media Graphics',
                'type' => 'ZIP',
                'size' => '15.2 MB',
                'downloads' => 23
            ]
        ]);

        return view('user.training', compact('trainingModules', 'marketingMaterials'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile-edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->firstname = $request->first_name;
        $user->lastname = $request->last_name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                // Delete old image if exists and has image_data
                if ($user->profile_image && $user->image_data) {
                    $oldImageData = json_decode($user->image_data, true);
                    $this->deleteImageFiles($oldImageData);
                }
                
                $imageData = $this->uploadAvatarImage($request->file('profile_image'), 'profile-images');
                $user->profile_image = $imageData['filename'];
                $user->image_data = json_encode($imageData);
            } catch (\Exception $e) {
                return back()->withErrors(['profile_image' => 'Failed to upload profile image: ' . $e->getMessage()])->withInput();
            }
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form for changing password.
     */
    public function changePassword()
    {
        return view('user.password-change');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password changed successfully!');
    }

    /**
     * Display user's orders.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = \App\Models\Order::byCustomer($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    /**
     * Show order details.
     */
    public function orderDetails($order)
    {
        $user = Auth::user();
        $order = \App\Models\Order::where('id', $order)
            ->where('customer_id', $user->id)
            ->with(['items.product', 'customer'])
            ->firstOrFail();

        return view('user.order-details', compact('order'));
    }

    /**
     * Display user's wallet information.
     */
    public function wallet()
    {
        $user = Auth::user();
        
        // Get wallet balance and transactions
        $walletBalance = $user->wallet_balance ?? 0;
        $recentTransactions = collect([
            (object) [
                'id' => 1,
                'type' => 'credit',
                'amount' => 100.00,
                'description' => 'Commission Payment',
                'date' => now()->subDays(1),
                'status' => 'completed'
            ],
            (object) [
                'id' => 2,
                'type' => 'debit',
                'amount' => 25.00,
                'description' => 'Purchase Deduction',
                'date' => now()->subDays(3),
                'status' => 'completed'
            ]
        ]);

        return view('user.wallet', compact('walletBalance', 'recentTransactions'));
    }
}
