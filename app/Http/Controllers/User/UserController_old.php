<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        
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
     * Display the user genealogy/network.
     */
    public function genealogy()
        }
    }
            ],
            return view('user.customer-dashboard', compact('user', 'userInfo'));
        }
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
            ],
            (object) [
                'date' => '2024-01-11',
                'type' => 'Direct Commission',
                'description' => 'Lisa Wang - Nutrition Combo',
                'amount' => 22.50,
                'status' => 'pending'
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
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    /**
     * Display training and resources (for affiliates only)
     */
    public function training()
    {
        // Training modules
        $trainingModules = collect([

        // Team members
        $teamMembers = collect([
            (object) [
                'name' => 'Sarah Johnson',
                'level' => 'Level 1',
                'rank' => 'Bronze',
                'join_date' => '2023-12-01',
                'this_month_volume' => 850,
                'status' => 'active'
            ],
            (object) [
                'name' => 'Mike Chen',
                'level' => 'Level 1',
                'rank' => 'Silver',
                'join_date' => '2023-11-15',
                'this_month_volume' => 1240,
                'status' => 'active'
            ],
            (object) [
                'name' => 'Lisa Wang',
                'level' => 'Level 2',
                'rank' => 'Bronze',
                'join_date' => '2023-10-20',
                'this_month_volume' => 650,
                'status' => 'active'
            ],
            (object) [
                'name' => 'David Brown',
                'level' => 'Level 1',
                'rank' => 'Starter',
                'join_date' => '2024-01-10',
                'this_month_volume' => 150,
                'status' => 'new'
            ]
        ]);

        // Recent orders
        $recentOrders = collect([
            (object) [
                'id' => 'ORD-001',
                'date' => '2024-01-15',
                'products' => 'Wellness Package + Protein Combo',
                'amount' => 449.98,
                'pv_points' => 240,
                'status' => 'delivered'
            ],
            (object) [
                'id' => 'ORD-002',
                'date' => '2024-01-10',
                'products' => 'Beauty Serum Set',
                'amount' => 199.99,
                'pv_points' => 120,
                'status' => 'shipped'
            ]
        ]);

        return view('user.dashboard', compact(
            'userStats',
            'recentCommissions',
            'teamMembers',
            'recentOrders'
        ));
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
     * Display commissions and earnings
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
            ],
            (object) [
                'date' => '2024-01-11',
                'type' => 'Direct Commission',
                'description' => 'Lisa Wang - Nutrition Combo',
                'amount' => 22.50,
                'status' => 'pending'
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
        // User profile data
        $user = (object) [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'address' => '123 Main St, City, State 12345',
            'join_date' => '2023-08-15',
            'sponsor' => 'Alex Smith',
            'user_id' => 'MLM123456',
            'rank' => 'Silver Executive',
            'profile_image' => 'default-avatar.png'
        ];

        return view('user.profile', compact('user'));
    }

    /**
     * Display training and resources
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
            ],
            (object) [
                'title' => 'Sales Techniques',
                'description' => 'Master the art of selling',
                'duration' => '75 minutes',
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
            ],
            (object) [
                'title' => 'Email Templates',
                'type' => 'HTML',
                'size' => '1.1 MB',
                'downloads' => 67
            ]
        ]);

        return view('user.training', compact('trainingModules', 'marketingMaterials'));
    }
}
