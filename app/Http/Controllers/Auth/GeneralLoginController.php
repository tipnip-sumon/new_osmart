<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GeneralLoginController extends Controller
{
    /**
     * Show the general login form.
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.general-login');
    }

    /**
     * Handle general login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Check if user exists and has general role (customer, vendor, admin)
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->withInput();
        }

        // Check if user is not an affiliate (general users)
        if ($user->role === 'affiliate') {
            return back()->withErrors([
                'email' => 'This account is registered as an affiliate. Please use affiliate login.',
            ])->withInput();
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            if ($user->isAdmin) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isVendor) {
                return redirect()->intended('/vendor/dashboard');
            } else {
                // For general customers, redirect to home page
                return redirect()->intended('/')->with('success', 'Welcome! You can start shopping now.');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
