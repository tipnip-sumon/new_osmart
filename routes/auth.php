<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GeneralLoginController;
use App\Http\Controllers\Auth\AffiliateLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Vendor\Auth\VendorAuthController;
use App\Http\Controllers\Vendor\VendorApplicationController;
use App\Models\VendorApplication;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register authentication routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::get('/affiliate', function() {
    return redirect()->route('affiliate.login');
});

// General Login Routes (for customers/product buyers)
Route::get('/login', [GeneralLoginController::class, 'showLoginForm'])
    ->middleware(['role.session:customer|user|member'])->name('login');

Route::post('/login', [GeneralLoginController::class, 'login'])
    ->middleware('guest')->name('login.submit');

Route::get('/general/login', [GeneralLoginController::class, 'showLoginForm'])
    ->middleware(['role.session:customer|user|member'])->name('general.login');

Route::post('/general/login', [GeneralLoginController::class, 'login'])
    ->middleware('guest')->name('general.login.submit');

// Affiliate Login Routes (for affiliate dashboard access)
Route::get('/affiliate/login', [AffiliateLoginController::class, 'showLoginForm'])
    ->middleware('guest')->name('affiliate.login');

Route::post('/affiliate/login', [AffiliateLoginController::class, 'login'])
    ->middleware('guest')->name('affiliate.login.submit');

Route::get('/affiliate/register', [AffiliateLoginController::class, 'showRegistrationForm'])
    ->name('affiliate.register');

Route::post('/affiliate/register', [AffiliateLoginController::class, 'register'])
    ->name('affiliate.register.submit');

// Test mail configuration route (can be removed in production)
Route::get('/test-mail-config', [AffiliateLoginController::class, 'testMailConfiguration'])
    ->name('test.mail.config');

// Vendor Login Routes (for vendor dashboard access)
Route::get('/vendor/login', [VendorAuthController::class, 'showLoginForm'])
    ->name('vendor.login');

Route::post('/vendor/login', [VendorAuthController::class, 'login'])
    ->middleware('guest')->name('vendor.login.submit');

Route::get('/vendor/register', [VendorAuthController::class, 'showRegistrationForm'])
    ->name('vendor.register');

Route::post('/vendor/register', [VendorApplicationController::class, 'submitApplication'])
    ->name('vendor.register.submit');

Route::get('/vendor/info', function() {
    return view('pages.vendor-info');
})->name('vendor.info');

Route::get('/affiliate/info', function() {
    return view('pages.affiliate-info');
})->name('affiliate.info');

// Logout Routes
Route::post('/logout', [LogoutController::class, 'logout'])
    ->name('logout');

Route::post('/general/logout', [GeneralLoginController::class, 'logout'])
    ->middleware('auth')->name('general.logout');

Route::post('/affiliate/logout', [AffiliateLoginController::class, 'logout'])
    ->middleware('auth')->name('affiliate.logout');

Route::post('/vendor/logout', [VendorAuthController::class, 'logout'])
    ->middleware('auth')->name('vendor.logout');

// Emergency logout route that always works (GET method for direct browser access)
Route::get('/emergency-logout', function (Request $request) {
    $user = Auth::user();
    $userName = $user ? $user->name : 'User';
    
    // Simple logout without complex session handling
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Clear all cached data
    $request->session()->flush();
    
    return redirect()->route('affiliate.login')
        ->with('success', "Emergency logout successful! {$userName}, you have been logged out.")
        ->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
})->name('emergency.logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->middleware('guest')->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest')->name('register.submit');

// Real-time validation routes for registration
Route::post('/register/validate-sponsor', [RegisterController::class, 'validateSponsor'])
    ->middleware('guest')->name('register.validate-sponsor');

Route::post('/register/check-username', [RegisterController::class, 'checkUsername'])
    ->middleware('guest')->name('register.check-username');

Route::post('/register/check-email', [RegisterController::class, 'checkEmail'])
    ->middleware('guest')->name('register.check-email');

Route::post('/register/check-phone', [RegisterController::class, 'checkPhone'])
    ->middleware('guest')->name('register.check-phone');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function () {
    // Handle password reset email logic here
    return back()->with('status', 'Password reset link sent to your email!');
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token, 'email' => request('email')]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function () {
    // Handle password reset logic here
    return redirect()->route('login')->with('status', 'Password has been reset successfully!');
})->middleware('guest')->name('password.update');

// Email Verification Routes (if needed)
Route::get('/email/verify', function (Request $request) {
    $user = $request->user();
    
    if ($user && $user->hasVerifiedEmail()) {
        // If already verified, redirect to appropriate dashboard
        if ($user->role === 'affiliate') {
            return redirect()->route('affiliate.login')->with('success', 'Email already verified! Please login.');
        } else {
            return redirect()->route('login')->with('success', 'Email already verified! Please login.');
        }
    }
    
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    
    // Verify the hash matches
    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }
    
    // Check if the link has expired
    if ($request->hasValidSignature() === false) {
        abort(403, 'Verification link has expired.');
    }
    
    // Check if email is already verified
    if ($user->hasVerifiedEmail()) {
        // Already verified - show message and redirect to login
        if ($user->role === 'affiliate') {
            return redirect()->route('affiliate.login')->with('info', 'Email already verified! Please login to continue.');
        } else {
            return redirect()->route('login')->with('info', 'Email already verified! Please login to continue.');
        }
    }
    
    // Mark email as verified
    if ($user->markEmailAsVerified()) {
        // Update ev field to 1 (verified)
        $user->update(['ev' => 1]);
        
        // Fire the email verified event
        event(new \Illuminate\Auth\Events\Verified($user));
        
        // Log the user in automatically after verification
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Redirect based on user role with success message
        if ($user->role === 'affiliate') {
            return redirect()->route('member.dashboard')->with('success', 'Email verified successfully! Welcome to your affiliate dashboard.');
        } else {
            return redirect()->route('home')->with('success', 'Email verified successfully! Welcome! You can now start shopping.');
        }
    }
    
    // If verification failed
    if ($user->role === 'affiliate') {
        return redirect()->route('affiliate.login')->with('error', 'Unable to verify email. Please try again or contact support.');
    } else {
        return redirect()->route('login')->with('error', 'Unable to verify email. Please try again or contact support.');
    }
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $user = $request->user();
    
    if ($user->hasVerifiedEmail()) {
        // If already verified, redirect to appropriate login
        if ($user->role === 'affiliate') {
            return redirect()->route('affiliate.login')->with('success', 'Email already verified! Please login.');
        } else {
            return redirect()->route('login')->with('success', 'Email already verified! Please login.');
        }
    }
    
    $user->sendEmailVerificationNotification();
    
    return back()->with('message', 'Verification link sent to your email!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
