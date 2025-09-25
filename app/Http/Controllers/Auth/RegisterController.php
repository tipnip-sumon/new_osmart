<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MlmBinaryTree;
use App\Models\BinarySummary;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Services\MlmBinaryTreeService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RegisterController extends Controller
{

    /**
     * Show the registration form
     */
    public function showRegistrationForm(Request $request)
    {
        $sponsor_id = $request->get('ref', '');
        $sponsor = null;
        
        if ($sponsor_id) {
            $sponsor = User::where('id', $sponsor_id)
                         ->orWhere('username', $sponsor_id)
                         ->orWhere('email', $sponsor_id)
                         ->first();
        }
        
        return view('auth.register', compact('sponsor_id', 'sponsor'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validation rules
        $rules = [
            'username' => 'required|string|max:255|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'sponsor_id' => 'nullable|string',
            'position' => 'nullable|in:left,right',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:500',
            'terms' => 'required|accepted',
        ];

        $messages = [
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'password.min' => 'Password must be at least 8 characters long.',
            'terms.required' => 'You must agree to the terms and conditions.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
            'username.unique' => 'This username is already taken.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Find sponsor or use default (ID: 1)
            $sponsor = null;
            $sponsorId = 1; // Default sponsor ID
            $placementType = 'auto'; // Default placement type

            if ($request->sponsor_id && !empty(trim($request->sponsor_id))) {
                $sponsor = User::where(function($query) use ($request) {
                    $query->where('username', $request->sponsor_id)
                          ->orWhere('referral_code', $request->sponsor_id)
                          ->orWhere('referral_hash', $request->sponsor_id)
                          ->orWhere('id', $request->sponsor_id);
                })->first();

                if ($sponsor) {
                    $sponsorId = $sponsor->id;
                    $placementType = 'auto'; // User specified a sponsor
                    Log::info("Sponsor found for registration: {$sponsor->username} (ID: {$sponsor->id})");
                } else {
                    // If provided sponsor doesn't exist, fallback to default (ID: 1)
                    Log::warning("Sponsor '{$request->sponsor_id}' not found for registration, using default sponsor ID: 1");
                    $sponsorId = 1;
                    $placementType = 'auto';
                }
            } else {
                Log::info("No sponsor provided for registration, using default sponsor ID: 1");
            }

            // Ensure sponsor with ID 1 exists, create if not
            $defaultSponsor = User::find(1);
            if (!$defaultSponsor) {
                Log::warning("Default sponsor (ID: 1) does not exist. Registration may fail.");
                // Could create a default sponsor here if needed
            }

            // If no sponsor was found but we have a sponsorId (fallback to ID 1), get the sponsor object
            if (!$sponsor && $sponsorId) {
                $sponsor = User::find($sponsorId);
                if ($sponsor) {
                    Log::info("Using fallback sponsor: {$sponsor->username} (ID: {$sponsor->id})");
                }
            }

            // Determine position - auto select if not specified
            $position = $request->position;
            
            // Validate position if provided
            if ($position && !in_array($position, ['left', 'right'])) {
                $position = null; // Invalid position, will auto-calculate
            }
            
            // Auto-calculate position if not provided or invalid
            if (!$position) {
                $position = $this->getAutoPosition($sponsorId);
            }
            
            // Final validation - ensure position is always set
            if (!in_array($position, ['left', 'right'])) {
                $position = 'left'; // Fallback to left if still invalid
                Log::warning("Position fallback to 'left' for registration");
            }
            
            Log::info("Final position for registration: {$position}");

            // Generate referral code and hash
            $referralCode = $this->generateReferralCode($request->username);
            $referralHash = $this->generateReferralHash();

            // Determine upline for binary tree placement (different from sponsor for auto placement)
            $uplineId = $sponsorId; // Default to sponsor
            $uplineUsername = $sponsor ? $sponsor->username : ($defaultSponsor ? $defaultSponsor->username : 'admin');
            
            // For auto placement, find the actual upline in the binary tree
            if ($placementType === 'auto') {
                $autoPlacement = $this->findBestUplineForAutoPlacement($sponsorId, $position);
                if ($autoPlacement) {
                    $uplineId = $autoPlacement['upline_id'];
                    $position = $autoPlacement['position']; // Use the position determined by auto placement
                    
                    // Always get the upline user fresh from database to ensure correct username
                    $uplineUser = User::find($uplineId);
                    if ($uplineUser) {
                        $uplineUsername = $uplineUser->username;
                        Log::info("Auto placement: User will be placed under upline ID {$uplineId} (username: {$uplineUsername}) in {$position} position at depth {$autoPlacement['depth']}");
                    } else {
                        Log::error("Auto placement upline user with ID {$uplineId} not found! Falling back to sponsor as upline");
                        // Fallback to sponsor if upline user not found
                        $uplineId = $sponsorId;
                        $uplineUsername = $sponsor ? $sponsor->username : ($defaultSponsor ? $defaultSponsor->username : 'admin');
                    }
                } else {
                    Log::warning("Auto placement failed, using sponsor as upline");
                    // Fallback to sponsor if auto placement fails
                    $uplineId = $sponsorId;
                    $uplineUsername = $sponsor ? $sponsor->username : ($defaultSponsor ? $defaultSponsor->username : 'admin');
                }
            }

            // Final validation: Ensure upline_username matches upline_id
            if ($uplineId) {
                $finalUplineUser = User::find($uplineId);
                if ($finalUplineUser) {
                    $uplineUsername = $finalUplineUser->username;
                    Log::info("Final upline validation: upline_id={$uplineId}, upline_username='{$uplineUsername}'");
                } else {
                    Log::error("Final upline validation failed: User with ID {$uplineId} not found!");
                    // This should not happen, but if it does, fallback to sponsor
                    $uplineId = $sponsorId;
                    $uplineUsername = $sponsor ? $sponsor->username : 'admin';
                    Log::warning("Falling back to sponsor: upline_id={$uplineId}, upline_username='{$uplineUsername}'");
                }
            }

            // Create user with essential data only
            $userData = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'name' => $request->firstname . ' ' . $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'status' => 'active',
                'sponsor_id' => $sponsorId,
                'sponsor' => $sponsor ? $sponsor->username : ($defaultSponsor ? $defaultSponsor->username : 'admin'),
                'ref_by' => $sponsor ? $sponsor->id : 1, // Must be integer (sponsor's user ID), fallback to admin ID 1
                'referral_code' => $referralCode,
                'referral_hash' => $referralHash,
                'position' => $position,
                'placement_type' => $placementType,
                'address' => $request->address,
                'country' => $request->country,
                'upline_id' => $uplineId, // Actual upline in binary tree (may differ from sponsor)
                'upline_username' => $uplineUsername, // Username of actual upline
                'preferences' => json_encode([
                    'email_notifications' => true,
                    'sms_notifications' => false,
                    'marketing_emails' => true,
                    'newsletter' => true,
                    'two_factor_auth' => false,
                    'session_timeout' => 120,
                    'login_alerts' => true
                ]),
            ];
            
            Log::info('Creating user with data: ' . json_encode($userData));
            
            try {
                $user = User::create($userData);
                Log::info("User created successfully: ID {$user->id}");
                
                // Insert user into MLM binary tree
                $mlmTreeService = new MlmBinaryTreeService();
                $treeInserted = $mlmTreeService->insertUserIntoTree($user);

                if (!$treeInserted) {
                    Log::warning("Failed to insert user {$user->id} into MLM binary tree during registration");
                    // Continue with registration process even if tree insertion fails
                } else {
                    Log::info("User {$user->id} successfully inserted into MLM binary tree during registration");
                }
                
                // Create binary summary record for new user
                try {
                    BinarySummary::create([
                        'user_id' => $user->id,
                        'left_carry_balance' => 0.00,
                        'right_carry_balance' => 0.00,
                        'lifetime_left_volume' => 0.00,
                        'lifetime_right_volume' => 0.00,
                        'lifetime_matching_bonus' => 0.00,
                        'lifetime_slot_bonus' => 0.00,
                        'lifetime_capped_amount' => 0.00,
                        'current_period_left' => 0.00,
                        'current_period_right' => 0.00,
                        'current_period_bonus' => 0.00,
                        'monthly_left_volume' => 0.00,
                        'monthly_right_volume' => 0.00,
                        'monthly_matching_bonus' => 0.00,
                        'monthly_capped_amount' => 0.00,
                        'weekly_left_volume' => 0.00,
                        'weekly_right_volume' => 0.00,
                        'weekly_matching_bonus' => 0.00,
                        'weekly_capped_amount' => 0.00,
                        'daily_left_volume' => 0.00,
                        'daily_right_volume' => 0.00,
                        'daily_matching_bonus' => 0.00,
                        'daily_capped_amount' => 0.00,
                        'total_matching_records' => 0,
                        'total_slot_matches' => 0,
                        'is_active' => true,
                        'last_calculated_at' => now(),
                    ]);
                    Log::info("BinarySummary record created for user {$user->id} during registration");
                } catch (\Exception $e) {
                    Log::error("Failed to create BinarySummary for user {$user->id}: " . $e->getMessage());
                    // Continue with registration even if binary summary creation fails
                }
                
            } catch (\Exception $userCreateException) {
                Log::error('User creation failed: ' . $userCreateException->getMessage());
                Log::error('User creation stack trace: ' . $userCreateException->getTraceAsString());
                throw $userCreateException;
            }

            DB::commit();

            // Auto-login the user after successful registration
            Auth::login($user);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Registration failed: ' . $e->getMessage());
            
            return back()->withErrors(['registration' => 'Registration failed. Please try again.'])->withInput();
        }

        // Handle emails and notifications after successful database transaction
        try {
            // Fire the Registered event
            event(new Registered($user));

            // Send welcome email to new user
            $this->sendWelcomeEmail($user, $sponsor);

            // Send notification email to sponsor about new referral (if sponsor exists)
            if ($sponsor) {
                $this->sendSponsorNotificationEmail($sponsor, $user);
            }

            // Create admin notification for new user registration
            AdminNotification::create([
                'type' => 'user_registration',
                'title' => 'New User Registration',
                'message' => "New user {$user->name} ({$user->username}) has registered" . ($sponsor ? " under sponsor {$sponsor->username}" : "") . ".",
                'data' => json_encode([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'sponsor' => $sponsor ? $sponsor->username : null,
                    'sponsor_id' => $sponsor ? $sponsor->id : null,
                    'position' => $position,
                    'placement_type' => $placementType,
                ]),
                'is_read' => false,
            ]);

            // Send admin notification email
            $this->sendAdminNotificationEmail($user, $sponsor);

        } catch (\Exception $emailException) {
            // Log email errors but don't fail the registration
            Log::warning('Email sending failed during registration for user ' . $user->id . ': ' . $emailException->getMessage());
        }

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to our platform.');
    }

    /**
     * Auto-select position based on sponsor's network
     */
    private function getAutoPosition($sponsorId)
    {
        try {
            // Check MLM Binary Tree for existing positions under this sponsor
            $leftCount = MlmBinaryTree::where('sponsor_id', $sponsorId)->where('position', 'left')->count();
            $rightCount = MlmBinaryTree::where('sponsor_id', $sponsorId)->where('position', 'right')->count();

            // Also check direct users table as fallback
            $userLeftCount = User::where('sponsor_id', $sponsorId)->where('position', 'left')->count();
            $userRightCount = User::where('sponsor_id', $sponsorId)->where('position', 'right')->count();

            // Use the higher count between MLM tree and users table
            $totalLeft = max($leftCount, $userLeftCount);
            $totalRight = max($rightCount, $userRightCount);

            // Return position with fewer members (for balanced binary tree)
            $position = $totalLeft <= $totalRight ? 'left' : 'right';
            
            Log::info("Auto-position calculation for sponsor {$sponsorId}: Left={$totalLeft}, Right={$totalRight}, Selected={$position}");
            
            return $position;
            
        } catch (\Exception $e) {
            Log::error("Error calculating auto position for sponsor {$sponsorId}: " . $e->getMessage());
            // Default to 'left' if there's any error
            return 'left';
        }
    }

    /**
     * Generate unique referral code
     */
    private function generateReferralCode($username)
    {
        $baseCode = strtoupper(substr($username, 0, 3)) . rand(100, 999);
        
        // Ensure uniqueness
        while (User::where('referral_code', $baseCode)->exists()) {
            $baseCode = strtoupper(substr($username, 0, 3)) . rand(100, 999);
        }
        
        return $baseCode;
    }

    /**
     * Generate unique referral hash
     */
    private function generateReferralHash()
    {
        $hash = bin2hex(random_bytes(16));
        
        // Ensure uniqueness
        while (User::where('referral_hash', $hash)->exists()) {
            $hash = bin2hex(random_bytes(16));
        }
        
        return $hash;
    }

    /**
     * Validate sponsor via AJAX
     */
    public function validateSponsor(Request $request)
    {
        $sponsorId = $request->sponsor_id;
        
        if (!$sponsorId) {
            return response()->json(['success' => false, 'message' => 'Sponsor ID is required']);
        }

        $sponsor = User::where(function($query) use ($sponsorId) {
            $query->where('username', $sponsorId)
                  ->orWhere('referral_code', $sponsorId)
                  ->orWhere('referral_hash', $sponsorId)
                  ->orWhere('id', $sponsorId);
        })->first();

        if ($sponsor) {
            return response()->json([
                'success' => true,
                'sponsor' => [
                    'name' => $sponsor->name,
                    'username' => $sponsor->username,
                    'avatar' => $sponsor->avatar ?? '/assets/img/default-avatar.svg'
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Sponsor not found']);
    }

    /**
     * Check username availability via AJAX
     */
    public function checkUsername(Request $request)
    {
        $username = $request->username;
        
        if (!$username) {
            return response()->json(['available' => false, 'message' => 'Username is required']);
        }

        $exists = User::where('username', $username)->exists();
        
        return response()->json(['available' => !$exists]);
    }

    /**
     * Check email availability via AJAX
     */
    public function checkEmail(Request $request)
    {
        $email = $request->email;
        
        if (!$email) {
            return response()->json(['available' => false, 'message' => 'Email is required']);
        }

        $exists = User::where('email', $email)->exists();
        
        return response()->json(['available' => !$exists]);
    }

    /**
     * Check phone availability via AJAX
     */
    public function checkPhone(Request $request)
    {
        $phone = $request->phone;
        
        if (!$phone) {
            return response()->json(['available' => false, 'message' => 'Phone number is required']);
        }

        $exists = User::where('phone', $phone)->exists();
        
        return response()->json(['available' => !$exists]);
    }

    /**
     * Send welcome email to new user.
     */
    private function sendWelcomeEmail($user, $sponsor)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'user' => $user,
                'sponsor' => $sponsor,
                'site_name' => $siteName,
                'login_url' => route('login'),
                'dashboard_url' => route('home'),
            ];

            Mail::send('emails.user-welcome', $emailData, function ($message) use ($user, $siteName) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject("Welcome to {$siteName} - Your Account is Ready!");
            });

            Log::info("Welcome email sent to new user: {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
        }
    }

    /**
     * Send notification email to sponsor about new referral.
     */
    private function sendSponsorNotificationEmail($sponsor, $newUser)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            
            $emailData = [
                'sponsor' => $sponsor,
                'new_user' => $newUser,
                'site_name' => $siteName,
                'dashboard_url' => route('home'),
            ];

            Mail::send('emails.sponsor-notification', $emailData, function ($message) use ($sponsor, $newUser, $siteName) {
                $message->to($sponsor->email, $sponsor->firstname . ' ' . $sponsor->lastname)
                        ->subject("New Referral Registered - {$newUser->username} joined your team at {$siteName}");
            });

            Log::info("Sponsor notification email sent to: {$sponsor->email} for new referral: {$newUser->username}");
        } catch (\Exception $e) {
            Log::error("Failed to send sponsor notification email to {$sponsor->email}: " . $e->getMessage());
        }
    }

    /**
     * Send admin notification email about new user registration.
     */
    private function sendAdminNotificationEmail($user, $sponsor)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'osmartbd';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'user' => $user,
                'sponsor' => $sponsor,
                'site_name' => $siteName,
                'admin_dashboard_url' => route('admin.dashboard'),
                'user_details_url' => route('admin.users.show', $user->id),
            ];

            Mail::send('emails.admin-user-notification', $emailData, function ($message) use ($user, $siteName, $adminEmail) {
                $message->to($adminEmail)
                        ->subject("New User Registration - {$user->username} at {$siteName}");
            });

            Log::info("Admin notification email sent about new user: {$user->username}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin notification email for new user {$user->username}: " . $e->getMessage());
        }
    }

    /**
     * Test mail configuration by sending a test email.
     * This method can be used for debugging mail setup.
     */
    public function testMailConfiguration(Request $request)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $testEmail = $request->input('email', 'test@example.com');
            
            Mail::raw('This is a test email from the osmartbd Registration System. The MailConfigServiceProvider is working correctly!', function ($message) use ($testEmail, $settings) {
                $message->to($testEmail)
                        ->subject('Mail Configuration Test - ' . ($settings->site_name ?? 'osmartbd'));
            });

            Log::info("Test email sent successfully to: {$testEmail}");
            
            return response()->json([
                'success' => true,
                'message' => "Test email sent successfully to {$testEmail}",
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Mail configuration test failed: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Mail configuration test failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find the best upline for auto placement in binary tree
     */
    protected function findBestUplineForAutoPlacement($sponsorId, $position)
    {
        try {
            $sponsor = User::find($sponsorId);
            if (!$sponsor) {
                Log::error("Sponsor with ID {$sponsorId} not found for auto placement");
                return null;
            }
            
            $placement = $this->findAutoPlacement($sponsor, $position);
            if ($placement) {
                Log::info("Auto placement found: upline_id={$placement['upline_id']}, position={$placement['position']}, depth={$placement['depth']}");
                return $placement;
            }
            
            Log::warning("No suitable auto placement found for sponsor {$sponsorId}");
            return null;
            
        } catch (\Exception $e) {
            Log::error("Error in findBestUplineForAutoPlacement: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Find the best available position for auto placement.
     */
    protected function findAutoPlacement($sponsor, $preferredPosition)
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
     * Find the next available position using breadth-first search.
     */
    protected function findNextAvailablePosition($sponsorId, $preferredSide, $maxDepth = 10)
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
     * Public method to find next available position for use in routes.
     */
    public function findNextAvailablePositionPublic($sponsorId, $preferredSide, $maxDepth = 10)
    {
        return $this->findNextAvailablePosition($sponsorId, $preferredSide, $maxDepth);
    }
}
