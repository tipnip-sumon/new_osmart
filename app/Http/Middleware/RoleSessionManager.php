<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleSessionManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        // Log the request for debugging
        Log::info("RoleSessionManager middleware hit", [
            'url' => $request->fullUrl(),
            'route_name' => $request->route() ? $request->route()->getName() : 'no_route',
            'required_role' => $requiredRole,
            'is_authenticated' => Auth::check(),
            'user_role' => Auth::check() ? Auth::user()->role : 'not_authenticated',
            'session_id' => session()->getId()
        ]);

        // If user is not authenticated, continue to login form
        if (!Auth::check()) {
            Log::info("User not authenticated, continuing to login form");
            return $next($request);
        }

        $user = Auth::user();
        $currentRole = $user->role;

        // Handle multiple roles for customer/general login
        $allowedRoles = explode('|', $requiredRole);
        
        // If user has any of the correct roles, allow access
        if (in_array($currentRole, $allowedRoles)) {
            // If this is a login route and user is already authenticated with correct role,
            // redirect them to their appropriate dashboard
            $routeName = $request->route()->getName();
            
            if (str_contains($routeName, '.login')) {
                switch ($currentRole) {
                    case 'vendor':
                        return redirect()->route('vendor.dashboard')
                            ->with('info', 'You are already logged in as a vendor.');
                    
                    case 'affiliate':
                        return redirect()->route('member.dashboard')
                            ->with('info', 'You are already logged in as an affiliate.');

                    case 'customer':
                        return redirect()->route('home')
                            ->with('info', 'You are already logged in as a customer.');
                    
                    default:
                        return redirect()->route('home')
                            ->with('info', 'You are already logged in.');
                }
            }
            
            return $next($request);
        }

        // If user has a different role, logout and redirect with message
        $userName = $user->name ?? $user->firstname . ' ' . $user->lastname;
        $currentRoleDisplay = ucfirst($currentRole);
        $requiredRoleDisplay = ucfirst($allowedRoles[0]); // Use first role for display

        // Log the role mismatch for security tracking
        Log::info("Role session conflict", [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'current_role' => $currentRole,
            'required_role' => $requiredRole,
            'allowed_roles' => $allowedRoles,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl()
        ]);

        // Clear all session data and logout
        $request->session()->flush();
        Auth::logout();
        $request->session()->regenerate();

        // Set appropriate message based on the role transition
        $message = "You were automatically logged out from your {$currentRoleDisplay} account ({$userName}). Please login with your {$requiredRoleDisplay} credentials to access this section.";

        // Store the message in session for the login form
        $request->session()->flash('info', $message);

        // Show SweetAlert message if possible
        $request->session()->flash('show_alert', [
            'type' => 'warning',
            'title' => 'Session Changed',
            'message' => $message,
            'timer' => 8000
        ]);

        // Redirect to appropriate login page based on required role
        switch ($allowedRoles[0]) {
            case 'vendor':
                return redirect()->route('vendor.login');
            
            case 'affiliate':
                return redirect()->route('affiliate.login');
            
            case 'customer':
                return redirect()->route('login');
            
            default:
                return redirect()->route('affiliate.login');
        }
    }
}
