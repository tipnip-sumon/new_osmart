<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Mail\WelcomeUserMail;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HandlesImageUploads;
    public function index()
    {
        // Check if the request is AJAX for DataTable
        if (request()->ajax()) {
            return $this->getUsersDataTable();
        }

        // Get basic stats for the view
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'total_commissions' => User::sum('total_earnings')
        ];

        return view('admin.users.index', compact('stats'));
    }

    /**
     * Handle DataTable AJAX request for users
     */
    public function getUsersDataTable()
    {
        $draw = request()->get('draw');
        $start = request()->get('start');
        $length = request()->get('length');
        $search = request()->get('search')['value'] ?? '';
        $order = request()->get('order')[0] ?? ['column' => 0, 'dir' => 'asc'];
        $columns = ['id', 'name', 'email', 'role', 'status', 'rank', 'total_orders', 'total_spent', 'total_pv', 'downline_count', 'total_commissions', 'created_at'];

        // Build the query
        $query = User::with(['sponsor'])
            ->select('users.*')
            ->addSelect([
                'total_orders' => Order::selectRaw('COALESCE(COUNT(*), 0)')
                    ->whereColumn('customer_id', 'users.id'),
                'total_spent' => Order::selectRaw('COALESCE(SUM(total_amount), 0)')
                    ->whereColumn('customer_id', 'users.id')
                    ->where('status', 'completed'),
                'downline_count' => User::selectRaw('COALESCE(COUNT(*), 0)')
                    ->whereColumn('sponsor_id', 'users.id')
            ]);

        // Apply search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('users.firstname', 'like', "%{$search}%")
                  ->orWhere('users.lastname', 'like', "%{$search}%")
                  ->orWhere('users.username', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%")
                  ->orWhere('users.role', 'like', "%{$search}%")
                  ->orWhere('users.status', 'like', "%{$search}%");
            });
        }

        // Get total count (before pagination)
        $totalRecords = User::count();
        $filteredRecords = clone $query;
        $filteredRecords = $filteredRecords->count();

        // Apply ordering
        $orderColumn = $columns[$order['column']] ?? 'created_at';
        $orderDir = $order['dir'] ?? 'desc';
        
        if ($orderColumn === 'name') {
            $query->orderBy('users.firstname', $orderDir);
        } elseif ($orderColumn === 'total_orders') {
            $query->orderBy('total_orders', $orderDir);
        } elseif ($orderColumn === 'total_spent') {
            $query->orderBy('total_spent', $orderDir);
        } elseif ($orderColumn === 'downline_count') {
            $query->orderBy('downline_count', $orderDir);
        } elseif (in_array($orderColumn, ['total_pv', 'total_commissions'])) {
            // These are virtual columns from model attributes, order by database equivalents
            if ($orderColumn === 'total_pv') {
                $query->orderBy('users.total_sales_volume', $orderDir);
            } elseif ($orderColumn === 'total_commissions') {
                $query->orderBy('users.total_earnings', $orderDir);
            }
        } else {
            // Default to actual database columns
            $query->orderBy("users.{$orderColumn}", $orderDir);
        }

        // Apply pagination
        $users = $query->skip($start)->take($length)->get();

        // Format the data
        $data = $users->map(function ($user) {
            // Determine rank based on total sales volume
            $totalPV = $user->total_sales_volume ?? 0;
            $rank = 'Starter';
            if ($totalPV >= 5000) $rank = 'Diamond';
            elseif ($totalPV >= 3000) $rank = 'Platinum';
            elseif ($totalPV >= 2000) $rank = 'Gold';
            elseif ($totalPV >= 1000) $rank = 'Silver';
            elseif ($totalPV >= 500) $rank = 'Bronze';

            $userName = $user->full_name ?: $user->username ?: 'N/A';
            $avatarUrl = $user->avatar_url ?? asset('assets/images/avatars/default-' . strtolower($user->role ?? 'user') . '.svg');
            
            return [
                'DT_RowId' => $user->id,
                'id' => $user->id,
                'name' => $userName,
                'email' => $user->email,
                'full_name' => $userName,
                'avatar' => $avatarUrl,
                'role' => $this->getRoleBadge($user->role ?? 'member'),
                'rank' => $this->getRankBadge($rank),
                'status' => $this->getStatusBadge($user->status ?? 'active'),
                'total_orders' => $user->total_orders ?? 0,
                'total_spent' => '৳' . number_format($user->total_spent ?? 0, 2),
                'total_pv' => number_format($totalPV),
                'downline_count' => $user->downline_count ?? 0,
                'total_commissions' => '৳' . number_format($user->total_earnings ?? 0, 2),
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get role badge HTML
     */
    private function getRoleBadge($role)
    {
        $role = ucfirst($role);
        switch ($role) {
            case 'Admin':
                return '<span class="badge bg-danger-transparent">' . $role . '</span>';
            case 'Vendor':
                return '<span class="badge bg-warning-transparent">' . $role . '</span>';
            case 'Member':
                return '<span class="badge bg-primary-transparent">' . $role . '</span>';
            default:
                return '<span class="badge bg-light">' . $role . '</span>';
        }
    }

    /**
     * Get rank badge HTML
     */
    private function getRankBadge($rank)
    {
        switch ($rank) {
            case 'Diamond':
                return '<span class="badge bg-info-transparent"><i class="ri-vip-diamond-line me-1"></i>' . $rank . '</span>';
            case 'Platinum':
                return '<span class="badge bg-secondary-transparent"><i class="ri-medal-line me-1"></i>' . $rank . '</span>';
            case 'Gold':
                return '<span class="badge bg-warning-transparent"><i class="ri-trophy-line me-1"></i>' . $rank . '</span>';
            case 'Silver':
                return '<span class="badge bg-light"><i class="ri-award-line me-1"></i>' . $rank . '</span>';
            case 'Bronze':
                return '<span class="badge bg-primary-transparent"><i class="ri-copper-coin-line me-1"></i>' . $rank . '</span>';
            default:
                return '<span class="badge bg-light">' . $rank . '</span>';
        }
    }

    /**
     * Get status badge HTML
     */
    private function getStatusBadge($status)
    {
        $status = ucfirst($status);
        switch ($status) {
            case 'Active':
                return '<span class="badge bg-success-transparent">' . $status . '</span>';
            case 'Inactive':
                return '<span class="badge bg-danger-transparent">' . $status . '</span>';
            case 'Pending':
                return '<span class="badge bg-warning-transparent">' . $status . '</span>';
            case 'Suspended':
                return '<span class="badge bg-dark-transparent">' . $status . '</span>';
            default:
                return '<span class="badge bg-light">' . $status . '</span>';
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        // Fetch user with all relationships
        $user = User::with(['sponsor', 'referrals', 'orders' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(3);
            }])
            ->where('id', $id)
            ->first();

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found!');
        }

        // Calculate user statistics
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount') ?? 0;
        $totalPV = $user->total_sales_volume ?? 0;
        $currentMonthPV = $user->monthly_sales_volume ?? 0;
        $downlineCount = $user->referrals()->count();
        $directReferrals = $user->referrals()->count();
        $totalCommissions = $user->total_earnings ?? 0;
        $thisMonthCommissions = $user->commission_rate ?? 0;

        // Determine rank based on sales volume
        $rank = 'Starter';
        if ($totalPV >= 5000) $rank = 'Diamond';
        elseif ($totalPV >= 3000) $rank = 'Platinum';
        elseif ($totalPV >= 2000) $rank = 'Gold';
        elseif ($totalPV >= 1000) $rank = 'Silver';
        elseif ($totalPV >= 500) $rank = 'Bronze';

        // Format user data for the view
        $userData = [
            'id' => $user->id,
            'name' => trim($user->firstname . ' ' . $user->lastname) ?: $user->username ?: 'N/A',
            'email' => $user->email,
            'phone' => $user->phone ?? $user->mobile ?? 'N/A',
            'role' => ucfirst($user->role),
            'status' => ucfirst($user->status ?? 'active'),
            'rank' => $rank,
            'sponsor' => $user->sponsor ? trim($user->sponsor->firstname . ' ' . $user->sponsor->lastname) : 'N/A',
            'sponsor_id' => $user->sponsor_id ?? null,
            'joined_date' => $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
            'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
            'address' => [
                'street' => $user->address ?? 'N/A',
                'city' => $user->city ?? 'N/A',
                'state' => $user->state ?? 'N/A',
                'zip' => $user->postal_code ?? 'N/A',
                'country' => $user->country ?? 'N/A'
            ],
            'statistics' => [
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'total_pv' => $totalPV,
                'current_month_pv' => $currentMonthPV,
                'downline_count' => $downlineCount,
                'direct_referrals' => $directReferrals,
                'total_commissions' => $totalCommissions,
                'this_month_commissions' => $thisMonthCommissions
            ],
            'recent_orders' => $user->orders->map(function($order) {
                return [
                    'id' => $order->order_number ?? 'ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                    'date' => $order->created_at->format('Y-m-d'),
                    'total' => $order->total_amount ?? 0,
                    'status' => ucfirst($order->status ?? 'pending')
                ];
            })->toArray(),
            'downline' => $user->referrals->map(function($referral) {
                return [
                    'name' => trim($referral->firstname . ' ' . $referral->lastname) ?: $referral->username ?: 'N/A',
                    'level' => 1, // Direct referrals are level 1
                    'pv_this_month' => $referral->monthly_sales_volume ?? 0,
                    'status' => ucfirst($referral->status ?? 'active')
                ];
            })->toArray(),
            'avatar_url' => $user->avatar_url ?? asset('assets/images/avatars/default-' . strtolower($user->role) . '.svg')
        ];

        return view('admin.users.show', compact('user', 'userData'));
    }

    public function edit($id)
    {
        // Fetch real user data from database
        $user = User::findOrFail($id);

        // Format user data for the edit form
        $userData = [
            'id' => $user->id,
            'name' => trim($user->firstname . ' ' . $user->lastname) ?: $user->username ?: '',
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'phone' => $user->phone ?? $user->mobile ?? '',
            'role' => $user->role,
            'status' => $user->status ?? 'active',
            'sponsor' => $user->sponsor ? trim($user->sponsor->firstname . ' ' . $user->sponsor->lastname) : '',
            'sponsor_id' => $user->sponsor_id ?? null,
            'address' => $user->address ?? '',
            'city' => $user->city ?? '',
            'state' => $user->state ?? '',
            'country' => $user->country ?? '',
            'postal_code' => $user->postal_code ?? ''
        ];

        return view('admin.users.edit', compact('user', 'userData'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,vendor,affiliate,admin',
            'status' => 'required|in:active,inactive,suspended',
            'sponsor_id' => 'nullable|exists:users,id',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20'
        ]);

        // Update user data
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'sponsor_id' => $request->sponsor_id,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $user->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.users.show', $id)->with('success', 'User status updated successfully!');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'sponsor_id' => 'nullable|exists:users,id',
            'role' => 'required|in:customer,vendor,affiliate',
            'status' => 'nullable|in:active,inactive,suspended,pending',
            'position' => 'nullable|in:left,right,auto',
            'placement_type' => 'nullable|in:direct,specific', // Updated for manual placement types
            'placement_under_user_id' => 'nullable|exists:users,id', // For specific user placement
            'auto_placement_preference' => 'nullable|in:balanced,left_first,right_first', // For auto placement
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'marketing_consent' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'phone_verified' => 'nullable|boolean',
            'kyc_verified' => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Get sponsor information if sponsor_id is provided
            $sponsor = null;
            $sponsorUsername = null;
            if ($request->sponsor_id) {
                $sponsor = User::find($request->sponsor_id);
                $sponsorUsername = $sponsor ? $sponsor->username : null;
            }

            // Handle avatar upload using trait
            $avatarData = null;
            if ($request->hasFile('avatar')) {
                $avatarData = $this->processImageUpload(
                    $request->file('avatar'),
                    'avatars',
                    [
                        'original' => ['width' => 400, 'height' => 400],
                        'large' => ['width' => 200, 'height' => 200],
                        'medium' => ['width' => 100, 'height' => 100],
                        'thumbnail' => ['width' => 50, 'height' => 50]
                    ]
                );
            }

            // Handle MLM placement logic
            $uplineId = $request->sponsor_id; // Default to sponsor
            $uplineUsername = $sponsorUsername;
            $actualPosition = $request->position;

            // Handle different placement scenarios
            if ($request->position === 'auto') {
                // Auto placement - system finds best balanced position
                if ($sponsor) {
                    $autoPlacement = $this->findAutoPlacement($sponsor, 'left'); // Start with left preference
                    
                    if ($autoPlacement) {
                        $uplineId = $autoPlacement['upline_id'];
                        $actualPosition = $autoPlacement['position'];
                        
                        // Get upline username
                        $uplineUser = User::find($uplineId);
                        $uplineUsername = $uplineUser ? $uplineUser->username : $sponsorUsername;
                        
                        Log::info("Auto placement found for user {$request->username}: upline_id={$uplineId}, position={$actualPosition}, depth={$autoPlacement['depth']}");
                    } else {
                        Log::warning("Auto placement failed for user {$request->username}, falling back to sponsor placement");
                    }
                }
            } elseif (($request->position === 'left' || $request->position === 'right') && $request->placement_type === 'specific' && $request->placement_under_user_id) {
                // Manual placement under specific user
                $specificUser = User::find($request->placement_under_user_id);
                if ($specificUser) {
                    $uplineId = $specificUser->id;
                    $uplineUsername = $specificUser->username;
                    Log::info("Manual placement under specific user {$specificUser->username} for user {$request->username}");
                }
            }
            // For direct placement under sponsor, use defaults (already set above)

            // Create the user
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'name' => $request->firstname . ' ' . $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'mobile' => $request->phone, // Set mobile to phone value
                'password' => bcrypt($request->password),
                'sponsor_id' => $request->sponsor_id,
                'sponsor' => $sponsorUsername, // Set sponsor username
                'upline_id' => $uplineId, // MLM upline (may differ from sponsor)
                'upline_username' => $uplineUsername, // MLM upline username
                'ref_by' => $request->sponsor_id, // Set ref_by to sponsor ID
                'role' => $request->role,
                'status' => $request->status ?? 'active',
                'position' => $actualPosition, // Actual position (may be adjusted by auto-placement)
                'placement_type' => $request->placement_type ?? 'auto',
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'avatar' => $avatarData ? $avatarData['sizes']['medium']['path'] : null,
                'image_data' => $avatarData ? json_encode($avatarData) : null,
                'marketing_consent' => $request->marketing_consent ? 1 : 0,
                'ev' => $request->email_verified ? 1 : 0,
                'sv' => $request->phone_verified ? 1 : 0,
                'kv' => $request->kyc_verified ? 1 : 0,
                'email_verified_at' => $request->email_verified ? now() : null,
                'referral_code' => $this->generateUniqueReferralCode(),
                'referral_hash' => $this->generateUniqueReferralHash()
            ]);

            DB::commit();

            // Send welcome email if requested
            if ($request->send_welcome_email) {
                try {
                    // Pass the plain password to the email if configured to include it
                    $plainPassword = env('INCLUDE_PASSWORD_IN_EMAIL', false) ? $request->password : null;
                    Mail::to($user->email)->send(new WelcomeUserMail($user, $plainPassword));
                } catch (\Exception $e) {
                    // Log email error but don't fail the user creation
                    Log::error('Failed to send welcome email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            return redirect()->route('admin.users.index')->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Error creating user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Generate unique referral code
     */
    private function generateUniqueReferralCode()
    {
        do {
            $code = 'OSM' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (User::where('referral_code', $code)->exists());
        
        return $code;
    }

    /**
     * Generate unique referral hash
     */
    private function generateUniqueReferralHash()
    {
        do {
            // Generate a 32-character MD5 hash like: f50d60a46c4df07b21f0d6b69bb61f60
            $hash = md5(uniqid(time(), true));
        } while (User::where('referral_hash', $hash)->exists());
        
        return $hash;
    }

    /**
     * Find the best available position for auto placement in MLM binary tree.
     */
    private function findAutoPlacement($sponsor, $preferredPosition)
    {
        // First, check if the preferred position is available directly under the sponsor
        $directPositionTaken = User::where('upline_id', $sponsor->id)
                                  ->where('position', $preferredPosition)
                                  ->exists();
        
        if (!$directPositionTaken) {
            return [
                'upline_id' => $sponsor->id,
                'position' => $preferredPosition,
                'depth' => 0
            ];
        }
        
        // If direct position is taken, find the next available position in the tree
        return $this->findNextAvailablePosition($sponsor->id, $preferredPosition);
    }

    /**
     * Find the next available position using breadth-first search in MLM binary tree.
     */
    private function findNextAvailablePosition($sponsorId, $preferredSide, $maxDepth = 10)
    {
        $queue = [['user_id' => $sponsorId, 'depth' => 0]];
        $visited = [];
        
        while (!empty($queue) && $queue[0]['depth'] < $maxDepth) {
            $current = array_shift($queue);
            $userId = $current['user_id'];
            $depth = $current['depth'];
            
            if (in_array($userId, $visited)) {
                continue;
            }
            $visited[] = $userId;
            
            // Check if this user has space for the preferred position
            $positionTaken = User::where('upline_id', $userId)
                                ->where('position', $preferredSide)
                                ->exists();
            
            if (!$positionTaken && $depth > 0) { // Don't place directly under sponsor if we're here
                return [
                    'upline_id' => $userId,
                    'position' => $preferredSide,
                    'depth' => $depth
                ];
            }
            
            // Add children to queue for further searching
            $children = User::where('upline_id', $userId)->get();
            foreach ($children as $child) {
                $queue[] = ['user_id' => $child->id, 'depth' => $depth + 1];
            }
        }
        
        return null;
    }

    /**
     * Public method to find next available position for use in routes/AJAX requests.
     */
    public function findNextAvailablePositionPublic($sponsorId, $preferredSide, $maxDepth = 10)
    {
        try {
            $sponsor = User::find($sponsorId);
            if (!$sponsor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sponsor not found'
                ]);
            }

            $result = $this->findAutoPlacement($sponsor, $preferredSide);
            
            if ($result) {
                $uplineUser = User::find($result['upline_id']);
                return response()->json([
                    'success' => true,
                    'placement' => $result,
                    'upline' => [
                        'id' => $uplineUser->id,
                        'username' => $uplineUser->username,
                        'name' => $uplineUser->firstname . ' ' . $uplineUser->lastname
                    ],
                    'message' => "Auto-placement found: {$result['position']} under {$uplineUser->username} (depth: {$result['depth']})"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "No available {$preferredSide} positions found in the sponsor's network"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error finding auto-placement: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if a specific position is available under a given upline.
     */
    public function checkPositionAvailability(Request $request)
    {
        // Accept both sponsor_id and upline_id for flexibility
        $uplineId = $request->get('upline_id') ?? $request->get('sponsor_id');
        $position = $request->get('position');
        
        if (!$uplineId) {
            return response()->json([
                'success' => false,
                'message' => 'Upline ID or Sponsor ID is required'
            ]);
        }

        // Check if upline exists
        $upline = User::find($uplineId);
        if (!$upline) {
            return response()->json([
                'success' => false,
                'message' => 'Upline user not found'
            ]);
        }

        // Get both left and right position status
        $leftUser = User::where('upline_id', $uplineId)->where('position', 'left')->first();
        $rightUser = User::where('upline_id', $uplineId)->where('position', 'right')->first();

        $positions = [
            'left' => [
                'available' => !$leftUser,
                'username' => $leftUser ? $leftUser->username : null,
                'user_id' => $leftUser ? $leftUser->id : null,
                'name' => $leftUser ? ($leftUser->firstname . ' ' . $leftUser->lastname) : null
            ],
            'right' => [
                'available' => !$rightUser,
                'username' => $rightUser ? $rightUser->username : null,
                'user_id' => $rightUser ? $rightUser->id : null,
                'name' => $rightUser ? ($rightUser->firstname . ' ' . $rightUser->lastname) : null
            ]
        ];

        // Determine recommendation for auto placement
        $recommendation = null;
        if (!$leftUser && !$rightUser) {
            $recommendation = 'left'; // Both available, prefer left
        } elseif (!$leftUser) {
            $recommendation = 'left';
        } elseif (!$rightUser) {
            $recommendation = 'right';
        }

        return response()->json([
            'success' => true,
            'positions' => $positions,
            'recommendation' => $recommendation,
            'upline' => [
                'id' => $upline->id,
                'username' => $upline->username,
                'name' => $upline->firstname . ' ' . $upline->lastname
            ]
        ]);
    }

    /**
     * Validate username in real-time
     */
    public function validateUsername(Request $request)
    {
        $username = $request->get('username');
        $userId = $request->get('user_id'); // For edit form
        
        if (!$username) {
            return response()->json([
                'valid' => false,
                'message' => 'Username is required'
            ]);
        }

        // Check username format
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return response()->json([
                'valid' => false,
                'message' => 'Username can only contain letters, numbers, and underscores'
            ]);
        }

        // Check if username exists (exclude current user in edit mode)
        $query = User::where('username', $username);
        if ($userId) {
            $query->where('id', '!=', $userId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Username already exists' : 'Username is available'
        ]);
    }

    /**
     * Validate sponsor username in real-time
     */
    public function validateSponsorUsername(Request $request)
    {
        $sponsor = $request->get('sponsor');
        
        if (!$sponsor) {
            return response()->json([
                'valid' => true,
                'message' => 'Sponsor is optional'
            ]);
        }

        // Check if sponsor exists and is active
        $sponsorUser = User::where('username', $sponsor)
            ->where('status', 'active')
            ->first();

        if (!$sponsorUser) {
            return response()->json([
                'valid' => false,
                'message' => 'Sponsor username not found or inactive'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Valid sponsor: ' . $sponsorUser->firstname . ' ' . $sponsorUser->lastname,
            'sponsor' => [
                'id' => $sponsorUser->id,
                'username' => $sponsorUser->username,
                'name' => $sponsorUser->firstname . ' ' . $sponsorUser->lastname,
                'email' => $sponsorUser->email
            ]
        ]);
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        
        $user->update(['status' => $newStatus]);
        
        return redirect()->back()->with('success', 'User status toggled successfully!');
    }

    public function ban(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'suspended']);
        
        return redirect()->back()->with('success', 'User banned successfully!');
    }

    public function unban(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        
        return redirect()->back()->with('success', 'User unbanned successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,suspend,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $action = $request->action;
        $userIds = $request->user_ids;
        $count = count($userIds);

        try {
            switch ($action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['status' => 'active']);
                    break;
                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    break;
                case 'suspend':
                    User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                    break;
                case 'delete':
                    User::whereIn('id', $userIds)->delete();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Bulk {$action} applied to {$count} users successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error applying bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $filename = 'users_export_' . now()->format('Y_m_d_H_i_s') . '.' . $format;

            // Get all users with calculated fields
            $users = User::with(['sponsor'])
                ->select('users.*')
                ->selectRaw('
                    COALESCE(users.firstname, "") as firstname,
                    COALESCE(users.lastname, "") as lastname,
                    CONCAT(COALESCE(users.firstname, ""), " ", COALESCE(users.lastname, "")) as full_name,
                    COALESCE(users.phone, users.mobile, "N/A") as phone_number,
                    COALESCE(users.status, "active") as user_status,
                    COALESCE(users.total_earnings, 0) as total_commissions,
                    COALESCE(users.total_sales_volume, 0) as total_pv
                ')
                ->addSelect([
                    'total_orders' => Order::selectRaw('COALESCE(COUNT(*), 0)')
                        ->whereColumn('customer_id', 'users.id'),
                    'total_spent' => Order::selectRaw('COALESCE(SUM(total_amount), 0)')
                        ->whereColumn('customer_id', 'users.id')
                        ->where('status', 'completed'),
                    'downline_count' => User::selectRaw('COALESCE(COUNT(*), 0)')
                        ->whereColumn('sponsor_id', 'users.id')
                ])
                ->get();

            if ($format === 'csv') {
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];

                $callback = function() use ($users) {
                    $file = fopen('php://output', 'w');
                    
                    // Add CSV headers
                    fputcsv($file, [
                        'ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Sponsor',
                        'Total Orders', 'Total Spent', 'PV Points', 'Downline Count',
                        'Total Commissions', 'Joined Date', 'Last Login'
                    ]);

                    // Add data rows
                    foreach ($users as $user) {
                        $totalPV = $user->total_pv ?? 0;
                        $rank = 'Starter';
                        if ($totalPV >= 5000) $rank = 'Diamond';
                        elseif ($totalPV >= 3000) $rank = 'Platinum';
                        elseif ($totalPV >= 2000) $rank = 'Gold';
                        elseif ($totalPV >= 1000) $rank = 'Silver';
                        elseif ($totalPV >= 500) $rank = 'Bronze';

                        fputcsv($file, [
                            $user->id,
                            trim($user->full_name) ?: $user->username ?: 'N/A',
                            $user->email,
                            $user->phone_number,
                            ucfirst($user->role),
                            ucfirst($user->user_status),
                            $user->sponsor ? trim($user->sponsor->firstname . ' ' . $user->sponsor->lastname) : 'N/A',
                            $user->total_orders ?? 0,
                            number_format($user->total_spent ?? 0, 2),
                            $user->total_pv ?? 0,
                            $user->downline_count ?? 0,
                            number_format($user->total_commissions ?? 0, 2),
                            $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A',
                            $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            return response()->json(['error' => 'Unsupported format'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    public function analytics()
    {
        // Get real analytics data
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $newThisMonth = User::whereMonth('created_at', now()->month)->count();
        
        $analytics = [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'suspended_users' => $suspendedUsers,
            'pending_users' => User::where('status', 'pending')->count(),
            'new_users_this_month' => $newThisMonth,
            'growth_rate' => $totalUsers > 0 ? round(($newThisMonth / $totalUsers) * 100, 1) : 0,
            'top_sponsors' => User::withCount('referrals')
                ->orderBy('referrals_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return [
                        'name' => trim($user->firstname . ' ' . $user->lastname) ?: $user->username,
                        'referrals' => $user->referrals_count
                    ];
                }),
            'user_ranks' => [
                'Starter' => User::where('total_sales_volume', '<', 500)->count(),
                'Bronze' => User::whereBetween('total_sales_volume', [500, 999])->count(),
                'Silver' => User::whereBetween('total_sales_volume', [1000, 1999])->count(),
                'Gold' => User::whereBetween('total_sales_volume', [2000, 2999])->count(),
                'Platinum' => User::whereBetween('total_sales_volume', [3000, 4999])->count(),
                'Diamond' => User::where('total_sales_volume', '>=', 5000)->count()
            ]
        ];

        return view('admin.users.analytics', compact('analytics'));
    }

    /**
     * Search for sponsor by ID
     */
    public function searchSponsor(Request $request)
    {
        try {
            $sponsorId = $request->get('id');
            
            if (!$sponsorId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sponsor ID is required'
                ]);
            }

            // Search for user by ID
            $sponsor = User::where('id', $sponsorId)
                ->where('status', 'active')
                ->first();

            if ($sponsor) {
                return response()->json([
                    'success' => true,
                    'sponsor' => [
                        'id' => $sponsor->id,
                        'name' => $sponsor->first_name . ' ' . $sponsor->last_name,
                        'email' => $sponsor->email,
                        'rank' => $sponsor->rank ?? 'Bronze',
                        'total_downline' => $sponsor->sponsoredUsers ? $sponsor->sponsoredUsers->count() : 0,
                        'status' => $sponsor->status
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No active user found with this ID'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching for sponsor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validate email in real-time
     */
    public function validateEmail(Request $request)
    {
        $email = $request->get('email');
        $userId = $request->get('user_id'); // For edit form
        
        if (!$email) {
            return response()->json([
                'valid' => false,
                'message' => 'Email is required'
            ]);
        }

        // Check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid email format'
            ]);
        }

        // Check if email exists (exclude current user in edit mode)
        $query = User::where('email', $email);
        if ($userId) {
            $query->where('id', '!=', $userId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Email already exists' : 'Email is available'
        ]);
    }

    /**
     * Validate mobile in real-time
     */
    public function validateMobile(Request $request)
    {
        $mobile = $request->get('mobile');
        $userId = $request->get('user_id'); // For edit form
        
        if (!$mobile) {
            return response()->json([
                'valid' => true,
                'message' => 'Mobile is optional'
            ]);
        }

        // Check mobile format (Bangladesh format)
        if (!preg_match('/^(\+88)?01[3-9]\d{8}$/', $mobile)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid mobile format (use: 01XXXXXXXXX)'
            ]);
        }

        // Check if mobile exists (exclude current user in edit mode)
        $query = User::where(function($q) use ($mobile) {
            $q->where('phone', $mobile)->orWhere('mobile', $mobile);
        });
        
        if ($userId) {
            $query->where('id', '!=', $userId);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'valid' => !$exists,
            'message' => $exists ? 'Mobile number already exists' : 'Mobile number is available'
        ]);
    }

    /**
     * Validate sponsor ID in real-time
     */
    public function validateSponsorId(Request $request)
    {
        $sponsorId = $request->get('sponsor_id');
        
        if (!$sponsorId) {
            return response()->json([
                'valid' => true,
                'message' => 'Sponsor is optional'
            ]);
        }

        // Check if sponsor exists and is active
        $sponsor = User::where('id', $sponsorId)
            ->where('status', 'active')
            ->first();

        if (!$sponsor) {
            return response()->json([
                'valid' => false,
                'message' => 'Sponsor not found or inactive'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Valid sponsor: ' . $sponsor->first_name . ' ' . $sponsor->last_name,
            'sponsor' => [
                'id' => $sponsor->id,
                'name' => $sponsor->firstname . ' ' . $sponsor->lastname,
                'email' => $sponsor->email,
                'rank' => $sponsor->rank ?? 'Bronze'
            ]
        ]);
    }

    /**
     * Comprehensive MLM placement validation with cross-link and hierarchy checks
     */
    public function validatePlacement(Request $request)
    {
        $sponsorId = $request->get('sponsor_id');
        $uplineId = $request->get('upline_id', $sponsorId); // Default to sponsor if no specific upline
        $position = $request->get('position');
        $placementType = $request->get('placement_type', 'direct');
        
        $validationResults = [
            'valid' => false,
            'message' => 'Invalid placement configuration',
            'checks' => [],
            'position_availability' => [],
            'warnings' => []
        ];

        try {
            // 1. Basic validation
            if (!$sponsorId || !$position || !in_array($position, ['left', 'right', 'auto'])) {
                $validationResults['message'] = 'Missing required placement parameters (sponsor_id, position)';
                return response()->json($validationResults);
            }

            // For specific placement, ensure upline_id is provided and different from sponsor
            if ($placementType === 'specific' && (!$uplineId || $uplineId == $sponsorId)) {
                $validationResults['message'] = 'For specific placement, please select a different user than the sponsor';
                return response()->json($validationResults);
            }

            // Default uplineId to sponsorId for direct placement
            if (!$uplineId) {
                $uplineId = $sponsorId;
            }

            $sponsor = User::find($sponsorId);
            $upline = User::find($uplineId);

            if (!$sponsor) {
                $validationResults['message'] = 'Sponsor not found';
                return response()->json($validationResults);
            }

            if (!$upline) {
                $validationResults['message'] = 'Upline user not found';
                return response()->json($validationResults);
            }

            // 2. Check if sponsor is in downline of upline (prevent circular references)
            $validationResults['checks']['circular_reference'] = $this->checkCircularReference($sponsorId, $uplineId);
            
            // 3. Check cross-link validation
            $validationResults['checks']['cross_link'] = $this->checkCrossLinkValidation($sponsorId, $uplineId, $position);
            
            // 4. Check position availability
            $validationResults['checks']['position_availability'] = $this->checkMLMPositionAvailability($uplineId, $position);
            
            // 5. Check hierarchy depth (prevent too deep placement)
            $validationResults['checks']['hierarchy_depth'] = $this->checkHierarchyDepth($uplineId);
            
            // 6. Check upline capacity (if there are limits)
            $validationResults['checks']['upline_capacity'] = $this->checkUplineCapacity($uplineId);
            
            // 7. Generate position availability status
            $validationResults['position_availability'] = $this->getPositionAvailabilityStatus($uplineId);

            // Determine overall validity
            $allChecksValid = true;
            $criticalErrors = [];
            $warnings = [];

            foreach ($validationResults['checks'] as $checkName => $checkResult) {
                if (!$checkResult['valid']) {
                    if ($checkResult['critical']) {
                        $allChecksValid = false;
                        $criticalErrors[] = $checkResult['message'];
                    } else {
                        $warnings[] = $checkResult['message'];
                    }
                }
            }

            $validationResults['valid'] = $allChecksValid;
            $validationResults['warnings'] = $warnings;
            
            if ($allChecksValid) {
                $validationResults['message'] = $position === 'auto' 
                    ? 'Auto placement will find the best available position'
                    : "Position '{$position}' is available and valid for placement";
            } else {
                $validationResults['message'] = implode('. ', $criticalErrors);
            }

            return response()->json($validationResults);

        } catch (\Exception $e) {
            Log::error('MLM Placement validation error: ' . $e->getMessage());
            
            return response()->json([
                'valid' => false,
                'message' => 'Validation error occurred',
                'checks' => [],
                'position_availability' => []
            ]);
        }
    }

    /**
     * Check for circular reference (sponsor in downline of upline)
     */
    private function checkCircularReference($sponsorId, $uplineId)
    {
        // For direct placement under sponsor, sponsor_id == upline_id is valid and expected
        if ($sponsorId == $uplineId) {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Direct placement under sponsor (valid)'
            ];
        }

        // Check if sponsor is in the upline chain of the proposed upline
        $currentUpline = $uplineId;
        $maxDepth = 50; // Prevent infinite loops
        $depth = 0;

        while ($currentUpline && $depth < $maxDepth) {
            $uplineUser = User::find($currentUpline);
            if (!$uplineUser) break;

            // Check if the current upline in the chain is sponsored by or placed under our sponsor
            if ($uplineUser->sponsor_id == $sponsorId || $uplineUser->upline_id == $sponsorId) {
                return [
                    'valid' => false,
                    'critical' => true,
                    'message' => 'Cannot place under a user who is sponsored by or under the sponsor (circular reference detected)'
                ];
            }

            $currentUpline = $uplineUser->upline_id;
            $depth++;
        }

        return [
            'valid' => true,
            'critical' => false,
            'message' => 'No circular reference detected'
        ];
    }

    /**
     * Check cross-link validation - prevents improper MLM structure violations
     */
    private function checkCrossLinkValidation($sponsorId, $uplineId, $position)
    {
        if ($position === 'auto') {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Auto placement will handle cross-link optimization'
            ];
        }

        // If sponsor and upline are the same (direct placement), no cross-link
        if ($sponsorId == $uplineId) {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Direct placement under sponsor - no cross-link'
            ];
        }

        // Get sponsor and upline users
        $sponsor = User::find($sponsorId);
        $upline = User::find($uplineId);

        if (!$sponsor || !$upline) {
            return [
                'valid' => false,
                'critical' => true,
                'message' => 'Sponsor or upline user not found'
            ];
        }

        // Check if upline is in sponsor's downline network (acceptable)
        if ($this->isInDownlineNetwork($sponsorId, $uplineId)) {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Placement within sponsor\'s network - valid'
            ];
        }

        // Check if sponsor is in upline's upline chain (acceptable - placing in own upline)
        if ($this->isInUplineChain($sponsorId, $uplineId)) {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Placement in sponsor\'s upline chain - valid'
            ];
        }

        // If we reach here, it's a true cross-link (different network branches)
        // This should be flagged as a critical error to maintain MLM structure
        return [
            'valid' => false,
            'critical' => true,
            'message' => "Cross-link detected: Cannot place {$sponsor->username}'s recruit under {$upline->username} (different network branches). This violates MLM structure."
        ];
    }

    /**
     * Check if target user is in the downline network of source user
     */
    private function isInDownlineNetwork($sourceUserId, $targetUserId)
    {
        $queue = [$sourceUserId];
        $visited = [];
        $maxDepth = 50; // Prevent infinite loops
        $currentDepth = 0;

        while (!empty($queue) && $currentDepth < $maxDepth) {
            $currentLevelQueue = $queue;
            $queue = [];
            
            foreach ($currentLevelQueue as $currentUserId) {
                if (in_array($currentUserId, $visited)) continue;
                $visited[] = $currentUserId;

                if ($currentUserId == $targetUserId) {
                    return true;
                }

                // Get direct downline
                $children = User::where('upline_id', $currentUserId)->pluck('id')->toArray();
                $queue = array_merge($queue, $children);
            }
            $currentDepth++;
        }

        return false;
    }

    /**
     * Check if target user is in the upline chain of source user
     */
    private function isInUplineChain($sourceUserId, $targetUserId)
    {
        $currentUser = User::find($sourceUserId);
        $maxDepth = 50; // Prevent infinite loops
        $depth = 0;

        while ($currentUser && $currentUser->upline_id && $depth < $maxDepth) {
            if ($currentUser->upline_id == $targetUserId) {
                return true;
            }
            $currentUser = User::find($currentUser->upline_id);
            $depth++;
        }

        return false;
    }

    /**
     * Enhanced position availability check for MLM validation
     */
    private function checkMLMPositionAvailability($uplineId, $position)
    {
        if ($position === 'auto') {
            return [
                'valid' => true,
                'critical' => false,
                'message' => 'Auto placement will find available positions'
            ];
        }

        $leftChild = User::where('upline_id', $uplineId)->where('position', 'left')->first();
        $rightChild = User::where('upline_id', $uplineId)->where('position', 'right')->first();

        $positionTaken = ($position === 'left' && $leftChild) || ($position === 'right' && $rightChild);

        if ($positionTaken) {
            return [
                'valid' => false,
                'critical' => true,
                'message' => "Position '{$position}' is already occupied"
            ];
        }

        return [
            'valid' => true,
            'critical' => false,
            'message' => "Position '{$position}' is available"
        ];
    }

    /**
     * Check hierarchy depth to prevent too deep placement
     */
    private function checkHierarchyDepth($uplineId)
    {
        $depth = $this->calculateDepthFromRoot($uplineId);
        $maxDepth = config('mlm.max_hierarchy_depth', 20); // Configurable max depth

        if ($depth > $maxDepth) {
            return [
                'valid' => false,
                'critical' => false,
                'message' => "Placement depth ({$depth}) exceeds recommended maximum ({$maxDepth})",
                'warning' => true
            ];
        }

        return [
            'valid' => true,
            'critical' => false,
            'message' => "Hierarchy depth ({$depth}) is within limits"
        ];
    }

    /**
     * Check upline capacity limits
     */
    private function checkUplineCapacity($uplineId)
    {
        $downlineCount = User::where('upline_id', $uplineId)->count();
        $maxDirectDownline = config('mlm.max_direct_downline', 2); // For binary tree, typically 2

        if ($downlineCount >= $maxDirectDownline) {
            return [
                'valid' => false,
                'critical' => true,
                'message' => "Upline has reached maximum direct downline capacity ({$maxDirectDownline})"
            ];
        }

        return [
            'valid' => true,
            'critical' => false,
            'message' => "Upline capacity available ({$downlineCount}/{$maxDirectDownline})"
        ];
    }

    /**
     * Get detailed position availability status
     */
    private function getPositionAvailabilityStatus($uplineId)
    {
        $leftChild = User::where('upline_id', $uplineId)->where('position', 'left')->first();
        $rightChild = User::where('upline_id', $uplineId)->where('position', 'right')->first();

        return [
            'left' => [
                'available' => !$leftChild,
                'occupied_by' => $leftChild ? [
                    'id' => $leftChild->id,
                    'name' => $leftChild->firstname . ' ' . $leftChild->lastname,
                    'username' => $leftChild->username,
                    'joined' => $leftChild->created_at->format('M d, Y')
                ] : null
            ],
            'right' => [
                'available' => !$rightChild,
                'occupied_by' => $rightChild ? [
                    'id' => $rightChild->id,
                    'name' => $rightChild->firstname . ' ' . $rightChild->lastname,
                    'username' => $rightChild->username,
                    'joined' => $rightChild->created_at->format('M d, Y')
                ] : null
            ]
        ];
    }

    /**
     * Find which primary leg a user belongs to
     */
    private function findUserLeg($userId)
    {
        $user = User::find($userId);
        if (!$user || !$user->upline_id) return null;

        // Traverse up to find the root's direct child (primary leg)
        $current = $user;
        while ($current && $current->upline_id) {
            $parent = User::find($current->upline_id);
            if (!$parent || !$parent->upline_id) {
                // This means current is a direct child of root
                return $current->position; // 'left' or 'right'
            }
            $current = $parent;
        }

        return null;
    }

    /**
     * Calculate depth from root user
     */
    private function calculateDepthFromRoot($userId)
    {
        $depth = 0;
        $current = User::find($userId);
        $maxDepth = 50; // Prevent infinite loops

        while ($current && $current->upline_id && $depth < $maxDepth) {
            $current = User::find($current->upline_id);
            $depth++;
        }

        return $depth;
    }
}
