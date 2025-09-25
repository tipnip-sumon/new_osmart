<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Exception;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\BinarySummary;
use App\Services\MlmBinaryTreeService;

class AffiliateLoginController extends Controller
{

    
    /**
     * Show the affiliate login form.
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.affiliate-login');
    }

    /**
     * Handle affiliate login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->withInput();
        }

        // Check user status before attempting login
        if ($user->status !== 'active' || !$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact support.',
            ])->withInput();
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            Log::info('Affiliate login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip()
            ]);
            
            // Check for redirect_to parameter (for checkout login) or referer URL
            if ($request->filled('redirect_to')) {
                return redirect($request->redirect_to)->with('success', 'Welcome back! You are now logged in.');
            }
            
            // Check if request came from checkout page
            if ($request->filled('from_checkout')) {
                return redirect()->route('checkout.index')->with('success', 'Welcome back! You are now logged in.');
            }
            
            $referer = $request->header('referer');
            if ($referer && (str_contains($referer, '/checkout') || str_contains($referer, 'checkout'))) {
                return redirect()->route('checkout.index')->with('success', 'Welcome back! You are now logged in.');
            }
            
            // Check for intended URL from session
            $intendedUrl = $request->session()->pull('url.intended');
            if ($intendedUrl && (str_contains($intendedUrl, '/checkout') || str_contains($intendedUrl, 'checkout'))) {
                return redirect($intendedUrl)->with('success', 'Welcome back! You are now logged in.');
            }
            
            // For affiliate login, always redirect to appropriate dashboard
            // Use the already pulled intended URL or default to role-based redirect
            switch ($user->role) {
                case 'affiliate':
                    return redirect()->route('member.dashboard')->with([
                        'login_success' => true
                    ]);
                case 'vendor':
                    return redirect()->route('vendor.dashboard')->with('success', 'Welcome to your vendor dashboard!');
                case 'customer':
                default:
                    // Customers should go to home page, not member dashboard
                    return redirect()->route('home')->with('success', 'Welcome back! You are now logged in.');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Show affiliate registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.affiliate-register');
    }

    /**
     * Handle affiliate registration request.
     */
    public function register(Request $request)
    {
        // Check if user is authenticated as customer first
        // if (!Auth::check()) {
        //     return redirect()->route('register')->with('info', 'Please register as a customer first, then apply to become an affiliate.');
        // }

        // Basic validation
        $rules = [
            'sponsor_id' => 'required|string',
            'position' => 'required|in:left,right',
            'placement' => 'required|in:auto,manual',
            'username' => 'required|string|max:255|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:2',
            'address' => 'nullable|string|max:500',
            'terms' => 'required|accepted',
            'marketing' => 'nullable',
        ];

        // Add upline_username validation for manual placement
        if ($request->placement === 'manual') {
            $rules['upline_username'] = 'required|string|exists:users,username';
        }

        $messages = [
            'sponsor_id.required' => 'Sponsor ID is required.',
            'position.required' => 'Position selection is required.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'password.min' => 'Password must be at least 8 characters long.',
            'terms.required' => 'You must agree to the terms and conditions.',
            'upline_username.required' => 'Upline username is required for manual placement.',
            'upline_username.exists' => 'Upline username does not exist.',
        ];

        $request->validate($rules, $messages);

        // Find sponsor by username, referral_code, or referral_hash
        $sponsor = User::where(function($query) use ($request) {
            $query->where('username', $request->sponsor_id)
                  ->orWhere('referral_code', $request->sponsor_id)
                  ->orWhere('referral_hash', $request->sponsor_id);
        })->first();
        
        if (!$sponsor) {
            return back()->withErrors([
                'sponsor_id' => 'Sponsor not found. Please check the sponsor ID, username, or referral hash.',
            ])->withInput();
        }

        // For manual placement, validate upline user and position availability
        $uplineId = null;
        if ($request->placement === 'manual') {
            $uplineUser = User::where('username', $request->upline_username)
                             ->where('status', 'active')
                             ->first();
            
            if (!$uplineUser) {
                return back()->withErrors([
                    'upline_username' => 'Upline user not found or inactive.',
                ])->withInput();
            }
            
            // Check if the requested position is already taken
            $existingDownline = User::where('upline_id', $uplineUser->id)
                                   ->where('position', $request->position)
                                   ->first();
            
            if ($existingDownline) {
                return back()->withErrors([
                    'position' => "The {$request->position} position under {$uplineUser->username} is already occupied by {$existingDownline->username}.",
                ])->withInput();
            }
            
            $uplineId = $uplineUser->id;
            
        } else if ($request->placement === 'auto') {
            // For auto placement, find the best available position
            $autoPlacement = $this->findAutoPlacement($sponsor, $request->position);
            
            if (!$autoPlacement) {
                return back()->withErrors([
                    'position' => "No available {$request->position} positions found in your sponsor's network. Please try the other position.",
                ])->withInput();
            }
            
            $uplineId = $autoPlacement['upline_id'];
        }

        // Generate referral code and hash
        $referralCode = $this->generateReferralCode($request->username);
        $referralHash = $this->generateReferralHash();

        // Create user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'name' => $request->firstname . ' ' . $request->lastname, // For backward compatibility
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'affiliate',
            'status' => 'active', // Affiliates need approval
            'sponsor_id' => $sponsor->id,
            'sponsor' => $sponsor->username,
            'ref_by' => $sponsor->id, // Must be integer (sponsor's user ID), not username
            'referral_code' => $referralCode,
            'referral_hash' => $referralHash,
            'country' => $request->country,
            'address' => $request->address,
            'position' => $request->position,
            'placement_type' => $request->placement,
            'upline_id' => $uplineId, // Store upline ID for manual placement
            'upline_username' => $request->upline_username, // Store upline username
            'marketing_consent' => $this->convertToBoolean($request->marketing),
            'ev' => 0, // Email not verified yet
            'sv' => 0, // SMS not verified yet
            'kv' => 0, // KYC not verified yet
        ]);

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
                'left_carry_balance' => 0,
                'right_carry_balance' => 0,
                'lifetime_left_volume' => 0,
                'lifetime_right_volume' => 0,
                'lifetime_matching_bonus' => 0,
                'lifetime_slot_bonus' => 0,
                'lifetime_capped_amount' => 0,
                'current_period_left' => 0,
                'current_period_right' => 0,
                'current_period_bonus' => 0,
                'monthly_left_volume' => 0,
                'monthly_right_volume' => 0,
                'monthly_matching_bonus' => 0,
                'monthly_capped_amount' => 0,
                'weekly_left_volume' => 0,
                'weekly_right_volume' => 0,
                'weekly_matching_bonus' => 0,
                'weekly_capped_amount' => 0,
                'daily_left_volume' => 0,
                'daily_right_volume' => 0,
                'daily_matching_bonus' => 0,
                'daily_capped_amount' => 0,
                'total_matching_records' => 0,
                'total_slot_matches' => 0,
                'is_active' => true,
                'last_calculated_at' => now(),
            ]);
            Log::info("BinarySummary record created for user {$user->id} during registration");
        } catch (Exception $e) {
            Log::error("Failed to create BinarySummary for user {$user->id}: " . $e->getMessage());
            // Continue with registration even if binary summary creation fails
        }

        // Send custom email verification notification
        $this->sendEmailVerification($user);

        // Send welcome email to new affiliate
        $this->sendWelcomeEmail($user, $sponsor);

        // Send notification email to sponsor about new referral
        $this->sendSponsorNotificationEmail($sponsor, $user);

        // Create admin notification for new affiliate registration
        AdminNotification::create([
            'type' => 'affiliate_registration',
            'title' => 'New Affiliate Registration',
            'message' => "New affiliate {$user->name} ({$user->username}) has registered under sponsor {$sponsor->username}.",
            'data' => json_encode([
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'sponsor' => $sponsor->username,
                'sponsor_id' => $sponsor->id,
                'position' => $request->position,
                'placement_type' => $request->placement,
            ]),
            'is_read' => false,
        ]);

        // Send admin notification email
        $this->sendAdminNotificationEmail($user, $sponsor);

        return redirect()->route('affiliate.login')
            ->with('success', 'Registration successful! Please check your email to verify your account. Your affiliate account is pending approval.');
    }

    /**
     * Generate unique username.
     */
    // private function generateUsername($name)
    // {
    //     $username = strtolower(str_replace(' ', '', $name));
    //     $counter = 1;
        
    //     while (User::where('username', $username)->exists()) {
    //         $username = strtolower(str_replace(' ', '', $name)) . $counter;
    //         $counter++;
    //     }
        
    //     return $username;
    // }

    /**
     * Generate unique referral code.
     */
    private function generateReferralCode($username)
    {
        return strtoupper($username);
    }

    /**
     * Generate unique referral hash.
     */
    private function generateReferralHash()
    {
        do {
            $hash = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (User::where('referral_hash', $hash)->exists());
        
        return $hash;
    }

    /**
     * Convert various input types to boolean.
     */
    private function convertToBoolean($value)
    {
        // Handle null/empty values (unchecked checkbox)
        if (is_null($value) || $value === '') {
            return false;
        }
        
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['true', '1', 'yes', 'on'], true);
        }
        
        if (is_numeric($value)) {
            return (bool) $value;
        }
        
        return false; // Default to false for unknown values
    }

    /**
     * Find the best available position for auto placement.
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
     * Find the next available position using breadth-first search.
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
     * Public method to find next available position for use in routes.
     */
    public function findNextAvailablePositionPublic($sponsorId, $preferredSide, $maxDepth = 10)
    {
        return $this->findNextAvailablePosition($sponsorId, $preferredSide, $maxDepth);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? Auth::user()->firstname . ' ' . Auth::user()->lastname;
        
        // Clear ALL session data first
        $request->session()->flush();
        
        // Clear affiliate-specific session data
        $request->session()->forget([
            'affiliate_data',
            'mlm_session',
            'cart',
            'guest_wishlist',
            'recent_products',
            'temp_uploads',
            'fresh_login',
            'url.intended',
            'login.intended',
            '_previous',
            '_flash'
        ]);
        
        // Perform logout
        Auth::logout();
        
        // Completely invalidate the session
        $request->session()->invalidate();
        
        // Generate new session and CSRF token
        $request->session()->regenerateToken();
        $request->session()->regenerate(true);
        
        // Handle AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Goodbye {$userName}! You have been successfully logged out.",
                'redirect' => route('affiliate.login'),
                'clear_cache' => true,
                'new_token' => csrf_token()
            ])->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Clear-Site-Data' => '"cache", "cookies", "storage", "executionContexts"'
            ]);
        }
        
        // For regular requests, redirect with cache clearing headers
        return redirect()->route('affiliate.login')
            ->with('success', "Goodbye {$userName}! You have been successfully logged out.")
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Clear-Site-Data' => '"cache", "cookies", "storage", "executionContexts"'
            ]);
    }

    /**
     * Send welcome email to new affiliate.
     */
    private function sendWelcomeEmail($user, $sponsor)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'O-Smart BD';
            
            $emailData = [
                'user' => $user,
                'sponsor' => $sponsor,
                'site_name' => $siteName,
                'login_url' => route('affiliate.login'),
                'dashboard_url' => route('member.dashboard'),
            ];

            Mail::send('emails.affiliate-welcome', $emailData, function ($message) use ($user, $siteName, $settings) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject("Welcome to {$siteName} - Your Affiliate Account")
                        ->from($settings->email_from ?? 'noreply@osmartbd.com', $siteName);
            });

            Log::info("Welcome email sent to new affiliate: {$user->email}");
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
            $siteName = $settings->site_name ?? 'O-Smart BD';
            
            $emailData = [
                'sponsor' => $sponsor,
                'new_user' => $newUser,
                'site_name' => $siteName,
                'dashboard_url' => route('member.dashboard'),
            ];

            Mail::send('emails.sponsor-notification', $emailData, function ($message) use ($sponsor, $newUser, $siteName, $settings) {
                $message->to($sponsor->email, $sponsor->firstname . ' ' . $sponsor->lastname)
                        ->subject("New Referral Registered - {$newUser->username} joined your team at {$siteName}")
                        ->from($settings->email_from ?? 'noreply@osmartbd.com', $siteName);
            });

            Log::info("Sponsor notification email sent to: {$sponsor->email} for new referral: {$newUser->username}");
        } catch (\Exception $e) {
            Log::error("Failed to send sponsor notification email to {$sponsor->email}: " . $e->getMessage());
        }
    }

    /**
     * Send admin notification email about new affiliate registration.
     */
    private function sendAdminNotificationEmail($user, $sponsor)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'O-Smart BD';
            $adminEmail = $settings->email_from ?? 'admin@osmartbd.com';
            
            $emailData = [
                'user' => $user,
                'sponsor' => $sponsor,
                'site_name' => $siteName,
                'admin_dashboard_url' => route('admin.dashboard'),
                'user_details_url' => route('admin.users.show', $user->id),
            ];

            Mail::send('emails.admin-affiliate-notification', $emailData, function ($message) use ($user, $siteName, $adminEmail, $settings) {
                $message->to($adminEmail)
                        ->subject("New Affiliate Registration - {$user->username} at {$siteName}")
                        ->from($settings->email_from ?? 'noreply@osmartbd.com', $siteName);
            });

            Log::info("Admin notification email sent about new affiliate: {$user->username}");
        } catch (\Exception $e) {
            Log::error("Failed to send admin notification email for new affiliate {$user->username}: " . $e->getMessage());
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

            Mail::raw('This is a test email from the O-Smart BD Affiliate System. The MailConfigServiceProvider is working correctly!', function ($message) use ($testEmail, $settings) {
                $message->to($testEmail)
                        ->subject('Mail Configuration Test - ' . ($settings->site_name ?? 'O-Smart BD'));
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
     * Send custom email verification notification.
     */
    private function sendEmailVerification($user)
    {
        try {
            $settings = GeneralSetting::getSettings();
            $siteName = $settings->site_name ?? 'O-Smart BD';
            
            // Generate verification URL
            $verificationUrl = $this->generateVerificationUrl($user);
            
            $emailData = [
                'user' => $user,
                'site_name' => $siteName,
                'verification_url' => $verificationUrl,
                'support_email' => $settings->email_from ?? 'support@osmartbd.com',
            ];

            Mail::send('emails.email-verification', $emailData, function ($message) use ($user, $siteName, $settings) {
                $message->to($user->email, $user->firstname . ' ' . $user->lastname)
                        ->subject("Verify Your Email Address - {$siteName}")
                        ->from($settings->email_from ?? 'noreply@osmartbd.com', $siteName);
            });

            Log::info("✅ Email verification sent successfully", [
                'user_email' => $user->email,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Failed to send email verification", [
                'user_email' => $user->email,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate email verification URL.
     */
    private function generateVerificationUrl($user)
    {
        // Create a temporary signed URL for email verification
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60), // Link expires in 60 minutes
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
    }
}