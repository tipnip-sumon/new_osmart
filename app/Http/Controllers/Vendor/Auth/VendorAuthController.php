<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VendorAuthController extends Controller
{
    /**
     * Show the vendor login form.
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.vendor-login');
    }

    /**
     * Handle vendor login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Check if user exists and is a vendor
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->withInput();
        }
        
        if ($user->role !== 'vendor') {
            return back()->withErrors([
                'email' => 'This account is not registered as a vendor. Please contact support.',
            ])->withInput();
        }
        
        if ($user->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your vendor account is not active. Please contact support.',
            ])->withInput();
        }
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('vendor.dashboard'))
                           ->with('success', 'Welcome back to your vendor dashboard!');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle vendor logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Vendor';
        
        // Clear vendor-specific session data
        $request->session()->forget([
            'vendor_data',
            'vendor_cart',
            'vendor_preferences',
            'temp_uploads'
        ]);
        
        // Perform logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('vendor.login')->with('success', "Goodbye {$userName}! You have been logged out successfully from your vendor account.");
    }

    /**
     * Handle AJAX vendor logout request.
     */
    public function ajaxLogout(Request $request)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Vendor';
        
        // Clear vendor-specific session data
        $request->session()->forget([
            'vendor_data',
            'vendor_cart',
            'vendor_preferences',
            'temp_uploads'
        ]);
        
        // Perform logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => "Goodbye {$userName}! Logged out successfully.",
            'redirect_url' => route('vendor.login')
        ]);
    }

    /**
     * Show the vendor registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.vendor-register');
    }
}
