<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserActivePackage;
use App\Models\PointTransaction;
use App\Services\DailyPointDistributionService;
use App\Services\GenerationIncomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PackageController extends Controller
{
    protected $distributionService;
    protected $generationIncomeService;

    public function __construct(DailyPointDistributionService $distributionService, GenerationIncomeService $generationIncomeService)
    {
        $this->distributionService = $distributionService;
        $this->generationIncomeService = $generationIncomeService;
    }

    /**
     * Display user's packages and available upgrades
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's active packages
        $activePackages = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('plan')
            ->get();
        
        // Get IDs of packages already activated by the user
        $activatedPackageIds = $activePackages->pluck('plan_id')->toArray();
        
        // Get available packages (exclude already activated packages)
        $availablePackages = Plan::where('status', 1) // Use integer 1 instead of 'active'
            ->whereNotIn('id', $activatedPackageIds) // Exclude already activated packages
            ->orderBy('minimum_points', 'asc')
            ->orderBy('points_reward', 'asc')
            ->get();
        
        // Get package history
        $packageHistory = UserActivePackage::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
        
        // Get package summary
        $packageSummary = [
            'total_active_packages' => $activePackages->count(),
            'total_points_invested' => $activePackages->sum('points_allocated'),
            'total_packages_purchased' => UserActivePackage::where('user_id', $user->id)->count()
        ];
        
        // Get eligible packages for payout
        $eligiblePackages = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('next_payout_eligible_at', '<=', now())
            ->with('plan')
            ->get();
        
        // Get user's point status for package activation
        $pointStatus = [
            'reserve_points' => $user->reserve_points ?? 0,
            'active_points' => $user->active_points ?? 0,
            'total_points_earned' => $user->total_points_earned ?? 0,
            'points_used_for_packages' => $user->total_points_used ?? 0,
            'can_activate_starter' => ($user->reserve_points ?? 0) >= 100,
            'can_activate_premium' => ($user->reserve_points ?? 0) >= 200,
            'can_activate_vip' => ($user->reserve_points ?? 0) >= 500
        ];
        
        return view('member.packages.index', compact(
            'user',
            'activePackages',
            'availablePackages', 
            'packageHistory',
            'packageSummary',
            'eligiblePackages',
            'pointStatus'
        ));
    }

    /**
     * Show package purchase form
     */
    public function purchase($planId)
    {
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();
        
        // Validate plan is available for purchase (user has enough points)
        if (($user->reserve_points ?? 0) < ($plan->minimum_points ?? $plan->points_reward ?? 100)) {
            return redirect()->route('member.packages.index')
                ->with('error', 'Insufficient reserve points for this package.');
        }
        
        // Get user's current packages for display
        $userPackages = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->get();
        
        // Get user's point status for package activation
        $pointStatus = [
            'reserve_points' => $user->reserve_points ?? 0,
            'active_points' => $user->active_points ?? 0,
            'total_points_earned' => $user->total_points_earned ?? 0,
            'can_activate_package' => ($user->reserve_points ?? 0) >= ($plan->minimum_points ?? $plan->points_reward ?? 100)
        ];
        
        return view('member.packages.purchase', compact('plan', 'user', 'userPackages', 'pointStatus'));
    }

    /**
     * Store/Activate a new package using user's accumulated points
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);
        $activePackage = null; // Initialize variable

        try {
            $pointsRequired = $plan->minimum_points ?? $plan->points_reward ?? 100;
            $fixedAmount = (float)($plan->fixed_amount ?? 0); // Dynamic from plans table
            
            // Check if user has sufficient reserve points for activation
            if (($user->reserve_points ?? 0) < $pointsRequired) {
                return back()->withErrors([
                    'error' => "Insufficient reserve points for package activation. Required: {$pointsRequired} points, Available: {$user->reserve_points}. Please purchase more points to activate this package."
                ]);
            }
            
            // Check wallet balance if fixed_amount is required
            if ($fixedAmount > 0 && ($user->deposit_wallet ?? 0) < $fixedAmount) {
                return back()->withErrors([
                    'error' => "Insufficient wallet balance for package activation. Required: ৳" . number_format($fixedAmount, 2) . " + {$pointsRequired} points, Available: ৳" . number_format($user->deposit_wallet ?? 0, 2) . " + {$user->reserve_points} points."
                ]);
            }

            DB::transaction(function () use ($user, $plan, $pointsRequired, $fixedAmount, &$activePackage) {
                // Deduct points from user's reserve
                $newReservePoints = $user->reserve_points - $pointsRequired;
                $newActivePoints = ($user->active_points ?? 0) + $pointsRequired;
                $newTotalUsed = ($user->total_points_used ?? 0) + $pointsRequired;
                
                // Prepare update data
                $updateData = [
                    'reserve_points' => $newReservePoints,
                    'active_points' => $newActivePoints,
                    'total_points_used' => $newTotalUsed,
                    'updated_at' => now()
                ];
                
                // Deduct wallet amount if fixed_amount is set
                if ($fixedAmount > 0) {
                    $updateData['deposit_wallet'] = $user->deposit_wallet - $fixedAmount;
                }
                
                // Update user points and wallet using DB
                DB::table('users')
                    ->where('id', $user->id)
                    ->update($updateData);

                // Create user package activation record
                $activePackage = \App\Models\UserActivePackage::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'package_tier' => $plan->name,
                    'amount_invested' => $fixedAmount, // Dynamic investment amount from plan
                    'points_allocated' => $pointsRequired,
                    'points_remaining' => $pointsRequired,
                    'activated_at' => now(),
                    'next_payout_eligible_at' => now()->addDays(30),
                    'is_active' => true,
                    'package_details' => [
                        'activation_method' => $fixedAmount > 0 ? 'wallet_and_points' : 'points_only',
                        'points_used' => $pointsRequired,
                        'wallet_amount_used' => $fixedAmount,
                        'plan_name' => $plan->name,
                        'activated_via' => 'member_portal'
                    ]
                ]);

                // Record point transaction for package activation
                \App\Models\PointTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $pointsRequired,
                    'description' => "Package activation: {$plan->name} ({$pointsRequired} points)",
                    'reference_type' => 'package_activation',
                    'reference_id' => $activePackage->id,
                    'status' => 'completed'
                ]);
                
                // Record wallet transaction if fixed amount was deducted
                if ($fixedAmount > 0) {
                    \App\Models\Transaction::create([
                        'user_id' => $user->id,
                        'transaction_id' => 'PKG-' . strtoupper(uniqid()),
                        'type' => 'debit',
                        'amount' => -$fixedAmount,
                        'fee' => 0.00,
                        'status' => 'completed',
                        'payment_method' => 'deposit_wallet',
                        'wallet_type' => 'deposit_wallet',
                        'description' => "Package activation cost: {$plan->name}",
                        'reference_type' => 'package_activation',
                        'reference_id' => $activePackage->id,
                        'balance_before' => $user->deposit_wallet,
                        'balance_after' => $user->deposit_wallet - $fixedAmount,
                        'metadata' => json_encode([
                            'package_name' => $plan->name,
                            'points_also_deducted' => $pointsRequired,
                            'activation_type' => 'wallet_and_points'
                        ])
                    ]);
                }

                // Log the activation
                Log::info('Package activated successfully', [
                    'user_id' => $user->id,
                    'package_id' => $plan->id,
                    'package_name' => $plan->name,
                    'points_used' => $pointsRequired,
                    'remaining_reserve_points' => $newReservePoints,
                    'active_points' => $newActivePoints
                ]);
                
                // Process generation income distribution for package activation
                try {
                    $this->generationIncomeService->processGenerationIncome(
                        $user,
                        $pointsRequired,
                        'package_purchase'
                    );
                    
                    Log::info('Generation income distribution completed for package activation', [
                        'user_id' => $user->id,
                        'package_name' => $plan->name,
                        'points_for_commission' => $pointsRequired
                    ]);
                } catch (\Exception $e) {
                    // Log commission error but don't fail the package activation
                    Log::error('Generation income distribution failed for package activation', [
                        'user_id' => $user->id,
                        'package_name' => $plan->name,
                        'points' => $pointsRequired,
                        'error' => $e->getMessage()
                    ]);
                }

                // Process sponsor bonus only (direct referral commission)
                try {
                    $sponsorBonus = $this->distributionService->processSponsorBonus($user, $pointsRequired);
                    
                    Log::info('Sponsor bonus processing completed for package activation', [
                        'user_id' => $user->id,
                        'package_name' => $plan->name,
                        'points_for_sponsor_bonus' => $pointsRequired,
                        'sponsor_bonus_amount' => $sponsorBonus
                    ]);
                } catch (\Exception $e) {
                    // Log sponsor bonus error but don't fail the package activation
                    Log::error('Sponsor bonus processing failed for package activation', [
                        'user_id' => $user->id,
                        'package_name' => $plan->name,
                        'points' => $pointsRequired,
                        'error' => $e->getMessage()
                    ]);
                }
                
                // Send notifications and emails
                $this->sendPackageUpgradeNotifications($user, $plan, $pointsRequired, $fixedAmount);
            });

            $successData = [
                'package_name' => $plan->name,
                'package_tier' => $plan->name,
                'amount_invested' => $fixedAmount,
                'points_used' => $pointsRequired,
                'points_activated' => $pointsRequired,
                'next_payout_date' => $activePackage ? $activePackage->next_payout_eligible_at : now()->addDays(30),
                'activation_method' => $fixedAmount > 0 ? 'wallet_and_points' : 'points_only'
            ];

            $successMessage = "Package '{$plan->name}' activated successfully! ";
            if ($fixedAmount > 0) {
                $successMessage .= "Deducted: ৳" . number_format($fixedAmount, 2) . " + {$pointsRequired} points.";
            } else {
                $successMessage .= "Deducted: {$pointsRequired} points only.";
            }

            return redirect()->route('member.packages.success')
                ->with('success_data', $successData)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Package activation failed', [
                'user_id' => $user->id,
                'package_id' => $plan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Package activation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Process package purchase (legacy method - not used in point-based system)
     */
    public function processPurchase(Request $request)
    {
        // This method is not used in the point-based system
        // Package activation is handled by the store() method
        return redirect()->route('member.packages.index')
            ->with('info', 'Please use the point-based activation system.');
    }

    /**
     * Show purchase success page
     */
    public function success()
    {
        if (!session('success_data')) {
            return redirect()->route('member.packages.index');
        }

        $user = Auth::user();
        $successData = session('success_data');
        
        return view('member.packages.success', compact('user', 'successData'));
    }

    /**
     * Show payout page for eligible packages
     */
    public function payout()
    {
        $user = Auth::user();
        
        // Get eligible packages using direct query
        $eligiblePackages = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('next_payout_eligible_at', '<=', now())
            ->with('plan')
            ->get();
        
        if ($eligiblePackages->isEmpty()) {
            return redirect()->route('member.packages.index')
                ->with('info', 'No packages are eligible for payout at this time.');
        }
        
        return view('member.packages.payout', compact('user', 'eligiblePackages'));
    }

    /**
     * Process payout for selected packages
     */
    public function processPayout(Request $request)
    {
        $request->validate([
            'package_ids' => 'required|array|min:1',
            'package_ids.*' => 'exists:user_active_packages,id',
            'confirm_payout' => 'required|accepted'
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();
            
            // Get eligible packages directly using query
            $eligiblePackages = UserActivePackage::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('next_payout_eligible_at', '<=', now())
                ->whereIn('id', $request->package_ids)
                ->with('plan')
                ->get();
            
            if ($eligiblePackages->isEmpty()) {
                throw new \Exception('No eligible packages found for payout.');
            }
            
            $totalPayoutProcessed = 0;
            $userActivePointsIncrease = 0;
            $userTotalEarnedIncrease = 0;
            
            foreach ($eligiblePackages as $package) {
                // Calculate payout amount based on package plan
                $payoutAmount = $package->plan->daily_return_amount ?? 0;
                
                // Accumulate user point changes
                $userActivePointsIncrease += $payoutAmount;
                $userTotalEarnedIncrease += $payoutAmount;
                
                // Create point transaction record
                PointTransaction::createCredit($user->id, $payoutAmount, 'Package Payout', $package->id);
                
                // Update package payout eligibility
                $package->next_payout_eligible_at = now()->addDay();
                $package->total_payouts_received += $payoutAmount;
                $package->save();
                
                $totalPayoutProcessed += $payoutAmount;
            }
            
            // Update user points using DB
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'active_points' => DB::raw('active_points + ' . $userActivePointsIncrease),
                    'total_points_earned' => DB::raw('total_points_earned + ' . $userTotalEarnedIncrease),
                    'updated_at' => now()
                ]);
            
            DB::commit();

            return redirect()->route('member.packages.payout-success')
                ->with('payout_data', [
                    'total_payout' => $totalPayoutProcessed,
                    'total_points_invalidated' => $totalPayoutProcessed,
                    'processed_packages' => count($request->package_ids),
                    'updated_balance' => $user->active_points
                ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Package payout failed', [
                'user_id' => $user->id,
                'package_ids' => $request->package_ids,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show payout success page
     */
    public function payoutSuccess()
    {
        if (!session('payout_data')) {
            return redirect()->route('member.packages.index');
        }

        $user = Auth::user();
        $payoutData = session('payout_data');
        
        return view('member.packages.payout-success', compact('user', 'payoutData'));
    }

    /**
     * Show package history
     */
    public function history()
    {
        $user = Auth::user();
        
        // Get package history directly from database
        $packageHistory = UserActivePackage::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        
        return view('member.packages.history', compact('user', 'packageHistory'));
    }

    /**
     * API endpoint to calculate package cost and validate eligibility
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        try {
            // Check if plan is available for this user (basic validation)
            $isAvailable = $plan->is_active == 1;

            $packageTier = $plan->minimum_points ?? $plan->points ?? 100;
            $packageAmount = $plan->fixed_amount ?? $plan->minimum ?? 100;
            $pointsToAward = $plan->points ?? $plan->minimum_points ?? $packageTier;

            return response()->json([
                'success' => true,
                'is_available' => $isAvailable,
                'plan_name' => $plan->name,
                'package_tier' => $packageTier,
                'package_amount' => $packageAmount,
                'formatted_amount' => '৳' . number_format($packageAmount, 2),
                'points_to_award' => $pointsToAward,
                'can_afford' => $user->deposit_wallet >= $packageAmount,
                'wallet_balance' => $user->deposit_wallet,
                'formatted_wallet_balance' => '৳' . number_format($user->deposit_wallet, 2),
                'remaining_after_purchase' => $user->deposit_wallet - $packageAmount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * API endpoint to get package summary
     */
    public function getSummary()
    {
        $user = Auth::user();
        
        // Calculate summary directly from database
        $activePackagesCount = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->count();
            
        $totalInvestment = UserActivePackage::where('user_id', $user->id)
            ->sum('amount');
            
        $totalPayouts = UserActivePackage::where('user_id', $user->id)
            ->sum('total_payouts_received');
        
        $summary = [
            'active_packages' => $activePackagesCount,
            'total_investment' => $totalInvestment,
            'total_payouts' => $totalPayouts,
            'available_points' => $user->active_points,
            'reserve_points' => $user->reserve_points
        ];
        
        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Show current active packages
     */
    public function current()
    {
        $user = Auth::user();
        
        // Get user's active packages with details
        $activePackages = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['plan'])
            ->orderBy('activated_at', 'desc')
            ->get();
        
        // Get eligible packages for payout
        $eligibleForPayout = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('next_payout_eligible_at', '<=', now())
            ->count();
        
        // Get package statistics
        $packageStats = [
            'total_active_packages' => $activePackages->count(),
            'total_points_invested' => $activePackages->sum('activation_cost'),
            'total_commission_earned' => 0, // Placeholder - implement if commissions table exists
            'packages_ready_for_payout' => $eligibleForPayout
        ];
        
        // Get recent package activities
        $recentActivities = UserActivePackage::where('user_id', $user->id)
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('member.packages.current', compact(
            'user',
            'activePackages',
            'packageStats',
            'recentActivities'
        ));
    }

    /**
     * Show package upgrade options
     */
    public function upgrade()
    {
        $user = Auth::user();
        
        // Get user's current highest package
        $currentPackage = UserActivePackage::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('plan')
            ->orderBy('activation_cost', 'desc')
            ->first();
        
        // Get available upgrade options
        $upgradeOptions = collect();
        
        if ($currentPackage) {
            $upgradeOptions = Plan::where('status', 1) // Use integer 1 instead of 'active'
                ->where('package_price', '>', $currentPackage->activation_cost)
                ->where('package_price', '<=', $user->reserve_points ?? 0)
                ->orderBy('package_price', 'asc')
                ->get();
        } else {
            // If no current package, show all available packages
            $upgradeOptions = Plan::where('status', 1) // Use integer 1 instead of 'active'
                ->where('package_price', '<=', $user->reserve_points ?? 0)
                ->orderBy('package_price', 'asc')
                ->get();
        }
        
        // Calculate upgrade costs and benefits
        $upgradeOptions = $upgradeOptions->map(function ($plan) use ($currentPackage) {
            $upgradeData = [
                'plan' => $plan,
                'upgrade_cost' => $plan->package_price,
                'additional_cost' => 0,
                'commission_increase' => 0,
                'bonus_increase' => 0
            ];
            
            if ($currentPackage) {
                $upgradeData['additional_cost'] = $plan->package_price - $currentPackage->activation_cost;
                $upgradeData['commission_increase'] = $plan->direct_commission - $currentPackage->plan->direct_commission;
                $upgradeData['bonus_increase'] = $plan->matching_bonus - $currentPackage->plan->matching_bonus;
            }
            
            return $upgradeData;
        });
        
        // Get user's point status directly
        $pointStatus = [
            'active_points' => $user->active_points,
            'reserve_points' => $user->reserve_points,
            'total_points_earned' => $user->total_points_earned,
            'total_points_used' => $user->total_points_used
        ];
        
        return view('member.packages.upgrade', compact(
            'user',
            'currentPackage',
            'upgradeOptions',
            'pointStatus'
        ));
    }

    /**
     * Send notifications and emails for package upgrade/activation
     */
    private function sendPackageUpgradeNotifications($user, $plan, $pointsUsed, $amountDeducted = 0)
    {
        try {
            // Import necessary classes
            $notificationService = app(\App\Services\NotificationService::class);
            
            // 1. Send notification to the user who upgraded
            $userMessage = "🎉 Package Activated Successfully!\n\n";
            $userMessage .= "Package: {$plan->name}\n";
            $userMessage .= "Points Used: {$pointsUsed}\n";
            if ($amountDeducted > 0) {
                $userMessage .= "Amount Deducted: ৳" . number_format($amountDeducted, 2) . "\n";
            }
            $userMessage .= "Activation Date: " . now()->format('d M Y, h:i A') . "\n\n";
            $userMessage .= "Your package is now active and ready for all included benefits!";

            $notificationService->sendToUser(
                $user->id,
                'package_upgrade',
                'Package Activated Successfully! 🎉',
                $userMessage,
                [
                    'category' => 'success',
                    'icon' => 'fe-gift',
                    'color' => 'success',
                    'is_important' => true,
                    'action_url' => route('member.packages.index'),
                    'action_text' => 'View My Packages',
                    'data' => [
                        'package_name' => $plan->name,
                        'points_used' => $pointsUsed,
                        'amount_deducted' => $amountDeducted,
                        'activation_type' => $amountDeducted > 0 ? 'wallet_and_points' : 'points_only'
                    ]
                ]
            );

            // 2. Find and notify sponsor
            $sponsor = User::find($user->sponsor_id);
            if ($sponsor) {
                $sponsorMessage = "💰 Team Member Package Upgrade!\n\n";
                $sponsorMessage .= "Member: {$user->first_name} {$user->last_name}\n";
                $sponsorMessage .= "Email: {$user->email}\n";
                $sponsorMessage .= "New Package: {$plan->name}\n";
                $sponsorMessage .= "Investment: ";
                if ($amountDeducted > 0) {
                    $sponsorMessage .= "৳" . number_format($amountDeducted, 2) . " + {$pointsUsed} points\n";
                } else {
                    $sponsorMessage .= "{$pointsUsed} points only\n";
                }
                $sponsorMessage .= "Upgrade Date: " . now()->format('d M Y, h:i A') . "\n\n";
                $sponsorMessage .= "Great work building your team! This upgrade may qualify for commission bonuses.";

                $notificationService->sendToUser(
                    $sponsor->id,
                    'team_package_upgrade',
                    'Team Member Upgraded Package! 💰',
                    $sponsorMessage,
                    [
                        'category' => 'info',
                        'icon' => 'fe-users',
                        'color' => 'primary',
                        'is_important' => true,
                        'action_url' => route('member.team.downline'),
                        'action_text' => 'View Team',
                        'data' => [
                            'member_id' => $user->id,
                            'member_name' => $user->first_name . ' ' . $user->last_name,
                            'package_name' => $plan->name,
                            'investment_amount' => $amountDeducted,
                            'points_used' => $pointsUsed
                        ]
                    ]
                );
            }

            // 3. Send email to user
            $this->sendPackageUpgradeEmailToUser($user, $plan, $pointsUsed, $amountDeducted);

            // 4. Send email to sponsor if exists
            if ($sponsor) {
                $this->sendPackageUpgradeEmailToSponsor($sponsor, $user, $plan, $pointsUsed, $amountDeducted);
            }

        } catch (\Exception $e) {
            // Log the error but don't fail the main transaction
            Log::error('Failed to send package upgrade notifications', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email to user about package upgrade
     */
    private function sendPackageUpgradeEmailToUser($user, $plan, $pointsUsed, $amountDeducted)
    {
        try {
            $emailData = [
                'user' => $user,
                'plan' => $plan,
                'points_used' => $pointsUsed,
                'amount_deducted' => $amountDeducted,
                'activation_date' => now()->format('d M Y, h:i A'),
                'total_cost' => $amountDeducted > 0 ? "৳" . number_format($amountDeducted, 2) . " + {$pointsUsed} points" : "{$pointsUsed} points only"
            ];

            Mail::to($user->email)->send(new \App\Mail\PackageUpgradeUser($emailData));
            
        } catch (\Exception $e) {
            Log::error('Failed to send package upgrade email to user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email to sponsor about team member's package upgrade
     */
    private function sendPackageUpgradeEmailToSponsor($sponsor, $user, $plan, $pointsUsed, $amountDeducted)
    {
        try {
            $emailData = [
                'sponsor' => $sponsor,
                'user' => $user,
                'plan' => $plan,
                'points_used' => $pointsUsed,
                'amount_deducted' => $amountDeducted,
                'activation_date' => now()->format('d M Y, h:i A'),
                'total_investment' => $amountDeducted > 0 ? "৳" . number_format($amountDeducted, 2) . " + {$pointsUsed} points" : "{$pointsUsed} points only"
            ];

            Mail::to($sponsor->email)->send(new \App\Mail\PackageUpgradeSponsor($emailData));
            
        } catch (\Exception $e) {
            Log::error('Failed to send package upgrade email to sponsor', [
                'sponsor_id' => $sponsor->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
