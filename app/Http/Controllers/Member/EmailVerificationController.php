<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Transaction;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice()
    {
        $user = Auth::user();
        
        // If already verified, redirect to dashboard
        if ($user->email_verified_at && $user->ev == 1) {
            return redirect()->route('member.dashboard')->with('success', 'Your email is already verified!');
        }
        
        return view('member.email-verification-notice', compact('user'));
    }
    
    /**
     * Send a new email verification notification.
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        
        // If already verified, return appropriate response
        if ($user->email_verified_at && $user->ev == 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your email is already verified!',
                    'redirect' => route('member.dashboard')
                ]);
            }
            return redirect()->route('member.dashboard')->with('success', 'Your email is already verified!');
        }
        
        // Rate limiting - allow 3 attempts per minute
        $throttleKey = 'email-verification-' . $user->id;
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $errorMessage = "Too many verification attempts. Please try again in {$seconds} seconds.";
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 429);
            }
            return back()->with('error', $errorMessage);
        }
        
        // Increment the rate limiter
        RateLimiter::hit($throttleKey, 60);
        
        try {
            // Generate verification URL
            $verificationUrl = $this->generateVerificationUrl($user);
            
            // Send email using Mail facade
            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? $user->firstname . ' ' . $user->lastname)
                        ->subject('Verify Your Email Address - ' . config('app.name'));
            });
            
            // Clear the rate limiter on successful send
            RateLimiter::clear($throttleKey);
            
            $successMessage = 'A fresh verification link has been sent to your email address!';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }
            return back()->with('success', $successMessage);
            
        } catch (\Exception $e) {
            Log::error('Email verification send failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            $errorMessage = 'Failed to send verification email. Please try again later.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            return back()->with('error', $errorMessage);
        }
    }
    
    /**
     * Generate email verification URL
     */
    private function generateVerificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'member.email.verify',
            Carbon::now()->addMinutes(60), // Link expires in 60 minutes
            [
                'id' => $user->id,
                'hash' => sha1($user->email)
            ]
        );
    }
    
    /**
     * Fulfill the email verification request.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        
        // Verify the hash
        if (!hash_equals($hash, sha1($user->email))) {
            return redirect()->route('member.email.notice')->with('error', 'Invalid verification link.');
        }
        
        // Check if link is signed and valid
        if (!$request->hasValidSignature()) {
            return redirect()->route('member.email.notice')->with('error', 'Verification link has expired or is invalid.');
        }
        
        // If already verified
        if ($user->email_verified_at && $user->ev == 1) {
            return redirect()->route('member.dashboard')->with('info', 'Your email was already verified!');
        }
        
        try {
            // Mark email as verified
            User::where('id', $user->id)->update([
                'email_verified_at' => now(),
                'ev' => 1
            ]);
            
            // Refresh user model
            $user = User::find($user->id);
            
            // Fire the verified event
            event(new Verified($user));
            
            // Clear any rate limiting
            RateLimiter::clear('email-verification-' . $user->id);
            
            // Auto login if not logged in
            if (!Auth::check()) {
                Auth::login($user);
            }
            
            return redirect()->route('member.dashboard')->with('success', 'Email verified successfully! You can now access all features.');
            
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }
        
        return redirect()->route('member.email.notice')->with('error', 'Email verification failed. Please try again or contact support.');
    }
    
    /**
     * Check verification status (AJAX)
     */
    public function status(Request $request)
    {
        $user = Auth::user();
        
        return response()->json([
            'verified' => $user->email_verified_at && $user->ev == 1,
            'ev_status' => $user->ev == 1,
            'email' => $user->email,
            'message' => ($user->email_verified_at && $user->ev == 1) ? 'Email is verified' : 'Email verification pending'
        ]);
    }
    
    /**
     * Update email address and send new verification
     */
    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        $isEmailVerified = $user->email_verified_at && $user->ev == 1;
        
        // Validation rules based on verification status
        $validationRules = [
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
        
        // If email is verified, require existing email confirmation and sufficient balance
        if ($isEmailVerified) {
            $validationRules['current_email_confirmation'] = 'required|email';
            $validationRules['password'] = 'required|string';
        }
        
        $request->validate($validationRules);
        
        $oldEmail = $user->email;
        $newEmail = $request->new_email;
        $emailChangeFee = 50; // 50 taka fee
        
        // For verified users, validate existing email confirmation and check balance
        if ($isEmailVerified) {
            // Verify current email matches
            if ($request->current_email_confirmation !== $user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current email confirmation does not match your registered email.'
                ], 422);
            }
            
            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password provided.'
                ], 422);
            }
            
            // Check if user has sufficient balance for the fee
            $availableBalance = ($user->deposit_wallet ?? 0) + ($user->interest_wallet ?? 0) + ($user->balance ?? 0);
            
            if ($availableBalance < $emailChangeFee) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient balance. Email change fee is ৳{$emailChangeFee}. Your available balance is ৳" . number_format($availableBalance, 2)
                ], 422);
            }
        }
        
        try {
            DB::beginTransaction();
            
            // For verified users, deduct the fee
            if ($isEmailVerified) {
                $this->deductEmailChangeFee($user, $emailChangeFee);
                
                // Create transaction record
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'email_change_fee',
                    'amount' => $emailChangeFee,
                    'status' => 'completed',
                    'description' => "Email change fee: {$oldEmail} → {$newEmail}",
                    'reference' => 'EMAIL_CHANGE_' . time()
                ]);
            }
            
            // Update email and reset verification
            User::where('id', $user->id)->update([
                'email' => $newEmail,
                'email_verified_at' => null,
                'ev' => 0
            ]);
            
            // Refresh user model
            $user = User::find($user->id);
            
            // Generate verification URL for new email
            $verificationUrl = $this->generateVerificationUrl($user);
            
            // Send verification to new email
            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name ?? $user->firstname . ' ' . $user->lastname)
                        ->subject('Verify Your New Email Address - ' . config('app.name'));
            });
            
            DB::commit();
            
            $message = $isEmailVerified 
                ? "Email updated successfully! ৳{$emailChangeFee} fee has been deducted. Please check your new email for verification link."
                : 'Email updated successfully! Please check your new email for verification link.';
            
            Log::info('Email updated and verification sent', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
                'fee_charged' => $isEmailVerified ? $emailChangeFee : 0,
                'was_verified' => $isEmailVerified
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'fee_charged' => $isEmailVerified ? $emailChangeFee : 0
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Email update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update email. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Deduct email change fee from user's wallets
     */
    private function deductEmailChangeFee($user, $fee)
    {
        $remaining = $fee;
        
        // First try deposit wallet
        if ($user->deposit_wallet >= $remaining) {
            User::where('id', $user->id)->decrement('deposit_wallet', $remaining);
            return;
        } elseif ($user->deposit_wallet > 0) {
            $deductFromDeposit = $user->deposit_wallet;
            $remaining -= $deductFromDeposit;
            User::where('id', $user->id)->update(['deposit_wallet' => 0]);
        }
        
        // Then try interest wallet
        if ($user->interest_wallet >= $remaining) {
            User::where('id', $user->id)->decrement('interest_wallet', $remaining);
            return;
        } elseif ($user->interest_wallet > 0) {
            $deductFromInterest = $user->interest_wallet;
            $remaining -= $deductFromInterest;
            User::where('id', $user->id)->update(['interest_wallet' => 0]);
        }
        
        // Finally, use main balance if needed
        if ($user->balance >= $remaining) {
            User::where('id', $user->id)->decrement('balance', $remaining);
        }
    }
    
    /**
     * Verification statistics for admin/monitoring
     */
    public function stats()
    {
        $user = Auth::user();
        $throttleKey = 'email-verification-' . $user->id;
        
        try {
            $isRateLimited = RateLimiter::tooManyAttempts($throttleKey, 3);
            $attemptsRemaining = RateLimiter::remaining($throttleKey, 3);
            $resetInSeconds = $isRateLimited ? RateLimiter::availableIn($throttleKey) : 0;
            
            return response()->json([
                'user_id' => $user->id,
                'email' => $user->email,
                'verified' => $user->email_verified_at && $user->ev == 1,
                'ev_status' => $user->ev,
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : null,
                'rate_limited' => $isRateLimited,
                'attempts_remaining' => max(0, $attemptsRemaining),
                'reset_in_seconds' => $resetInSeconds,
                'attempts_today' => 3 - max(0, $attemptsRemaining), // Calculate attempts made
                'last_attempt' => $isRateLimited ? 'Recently' : 'No recent attempts'
            ]);
        } catch (\Exception $e) {
            Log::error('Stats retrieval failed: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'user_id' => $user->id,
                'email' => $user->email,
                'verified' => $user->email_verified_at && $user->ev == 1,
                'ev_status' => $user->ev,
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : null,
                'rate_limited' => false,
                'attempts_remaining' => 3,
                'reset_in_seconds' => 0,
                'attempts_today' => 0,
                'last_attempt' => 'Never'
            ]);
        }
    }
}