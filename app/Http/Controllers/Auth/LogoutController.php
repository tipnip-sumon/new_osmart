<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    /**
     * Handle logout request for all user types
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userRole = $user ? $user->role : null;
        $userName = $user ? $user->name : 'User';
        
        // Clear any specific session data based on user role
        if ($userRole === 'affiliate') {
            Session::forget('affiliate_data');
            Session::forget('mlm_session');
        } elseif ($userRole === 'vendor') {
            Session::forget('vendor_data');
        }
        
        // Clear cart and wishlist session data for customers
        if ($userRole === 'customer' || !$userRole) {
            Session::forget('cart');
            Session::forget('guest_wishlist');
            Session::forget('recent_products');
        }
        
        // Perform logout
        Auth::logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear all flash data
        Session::flush();
        
        // Determine redirect URL based on user role
        $redirectUrl = $this->getRedirectUrl($userRole);
        
        // Set success message
        $message = "Goodbye {$userName}! You have been logged out successfully.";
        
        return redirect($redirectUrl)->with('success', $message);
    }
    
    /**
     * Handle AJAX logout request
     */
    public function ajaxLogout(Request $request)
    {
        $user = Auth::user();
        $userRole = $user ? $user->role : null;
        
        // Perform logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect_url' => $this->getRedirectUrl($userRole)
        ]);
    }
    
    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl($userRole)
    {
        switch ($userRole) {
            case 'affiliate':
                return route('affiliate.login');
            case 'vendor':
                return route('vendor.login') ?? route('home');
            case 'customer':
            default:
                return route('home');
        }
    }
    
    /**
     * Logout and redirect to specific page
     */
    public function logoutAndRedirect(Request $request, $redirectTo = null)
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'User';
        
        // Perform logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Determine redirect URL
        $redirectUrl = $redirectTo ?? route('home');
        
        return redirect($redirectUrl)->with('success', "Goodbye {$userName}! You have been logged out successfully.");
    }
    
    /**
     * Force logout all sessions for a user (security feature)
     */
    public function forceLogoutAllSessions(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('home');
        }
        
        // Force logout from all devices
        Auth::logoutOtherDevices($request->password ?? '');
        
        // Current session logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out from all devices successfully.');
    }
}
