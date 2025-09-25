<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Mail\MiniVendorAssigned;
use App\Mail\MiniVendorRemoved;
use App\Models\MiniVendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MiniVendorController extends Controller
{
    /**
     * Display a listing of mini vendors for the current vendor
     */
    public function index()
    {
        $vendor = Auth::user();
        
        $miniVendors = MiniVendor::where('vendor_id', $vendor->id)
            ->with('affiliate')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('vendor.mini-vendors.index', compact('miniVendors'));
    }

    /**
     * Show the form for assigning a new mini vendor
     */
    public function create()
    {
        $vendor = Auth::user();
        
        // Check if vendor has district set
        if (empty($vendor->district)) {
            // For vendors without district, we'll show search interface
            $potentialMiniVendors = collect(); // Empty collection, will use search instead
        } else {
            // Get potential mini vendors (affiliates from same district not already assigned)
            $potentialMiniVendors = $vendor->getPotentialMiniVendors();
        }
        
        return view('vendor.mini-vendors.create', compact('potentialMiniVendors'));
    }

    /**
     * Search for affiliate users (for vendors without district set)
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $vendor = Auth::user();
        
        // Search affiliate users
        $users = User::where('role', 'affiliate')
            ->where('status', 'active')
            ->where('id', '!=', $vendor->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%");
            })
            ->whereDoesntHave('miniVendorRecord', function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id)
                  ->where('status', 'active');
            })
            ->select('id', 'name', 'email', 'username', 'phone', 'district', 'role')
            ->limit(10)
            ->get();
            
        return response()->json($users);
    }

    /**
     * Store a newly assigned mini vendor
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $vendor = Auth::user();
        
        // Debug: Log the request data
        Log::info('MiniVendor store request', [
            'vendor_id' => $vendor->id,
            'request_data' => $request->all(),
            'vendor_district' => $vendor->district
        ]);
        
        $request->validate([
            'affiliate_id' => 'required|exists:users,id',
            'commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $affiliate = User::findOrFail($request->affiliate_id);
        
        // Debug: Log affiliate data
        Log::info('Found affiliate', [
            'affiliate_id' => $affiliate->id,
            'affiliate_name' => $affiliate->name,
            'affiliate_district' => $affiliate->district,
            'affiliate_role' => $affiliate->role
        ]);
        
        // Validate same district (only if vendor has district set) - case insensitive comparison
        if (!empty($vendor->district) && strtolower(trim($vendor->district)) !== strtolower(trim($affiliate->district))) {
            Log::info('District validation failed', [
                'vendor_district' => $vendor->district,
                'affiliate_district' => $affiliate->district,
                'vendor_district_lower' => strtolower(trim($vendor->district)),
                'affiliate_district_lower' => strtolower(trim($affiliate->district))
            ]);
            return back()->withErrors(['affiliate_id' => 'Selected user must be from the same district as you.']);
        }
        
        // Check if affiliate is not already a mini vendor for this vendor
        $existingMiniVendor = MiniVendor::where('vendor_id', $vendor->id)
            ->where('affiliate_id', $affiliate->id)
            ->first();
            
        Log::info('Existing mini vendor check', [
            'vendor_id' => $vendor->id,
            'affiliate_id' => $affiliate->id,
            'existing_mini_vendor' => $existingMiniVendor ? $existingMiniVendor->id : null
        ]);
            
        if ($existingMiniVendor) {
            Log::info('Existing mini vendor found, returning error');
            return back()->withErrors(['affiliate_id' => 'This user is already assigned as your mini vendor.']);
        }
        
        // Check if user is eligible (affiliate role only)
        if ($affiliate->role !== 'affiliate') {
            Log::info('Affiliate role check failed', [
                'affiliate_role' => $affiliate->role,
                'expected_role' => 'affiliate'
            ]);
            return back()->withErrors(['affiliate_id' => 'Selected user must be an affiliate user. Only affiliates can be assigned as mini vendors.']);
        }
        
        // Additional check: Make sure user is not already a mini vendor for any other vendor
        $existingAsOtherMiniVendor = MiniVendor::where('affiliate_id', $affiliate->id)
            ->where('vendor_id', '!=', $vendor->id)
            ->where('status', 'active')
            ->first();
            
        if ($existingAsOtherMiniVendor) {
            return back()->withErrors(['affiliate_id' => 'This user is already assigned as a mini vendor to another vendor.']);
        }

        Log::info('Starting DB transaction for MiniVendor creation');

        DB::transaction(function () use ($vendor, $affiliate, $request) {
            $data = [
                'vendor_id' => $vendor->id,
                'affiliate_id' => $affiliate->id,
                'district' => $vendor->district ?: $affiliate->district, // Use affiliate's district if vendor has none
                'status' => 'active',
                'commission_rate' => $request->commission_rate ?? 3.00,
                'total_earned_commission' => 0.00
            ];
            
            Log::info('Creating MiniVendor with data', $data);
            
            $miniVendor = MiniVendor::create($data);
            
            Log::info('MiniVendor created', ['mini_vendor_id' => $miniVendor->id]);
            
            // Change affiliate's role to vendor
            $oldRole = $affiliate->role;
            $affiliate->update(['role' => 'vendor']);
            
            Log::info('User role updated', [
                'user_id' => $affiliate->id,
                'old_role' => $oldRole,
                'new_role' => 'vendor',
                'reason' => 'assigned_as_mini_vendor'
            ]);
            
            // Send email notification to the newly assigned mini vendor
            try {
                Mail::to($affiliate->email)->send(new MiniVendorAssigned($affiliate, $vendor, $miniVendor));
                Log::info('Mini vendor assignment email sent', [
                    'recipient' => $affiliate->email,
                    'mini_vendor_id' => $miniVendor->id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send mini vendor assignment email', [
                    'recipient' => $affiliate->email,
                    'error' => $e->getMessage(),
                    'mini_vendor_id' => $miniVendor->id
                ]);
            }
        });

        return redirect()->route('vendor.mini-vendors.index')
            ->with('success', 'Mini vendor assigned successfully! User role changed to vendor. Welcome email sent.');
    }

    /**
     * Display the specified mini vendor
     */
    public function show(MiniVendor $miniVendor)
    {
        // Ensure vendor owns this mini vendor
        if ($miniVendor->vendor_id !== Auth::id()) {
            abort(403);
        }
        
        $miniVendor->load('affiliate');
        
        return view('vendor.mini-vendors.show', compact('miniVendor'));
    }

    /**
     * Update the status of a mini vendor
     */
    public function updateStatus(Request $request, MiniVendor $miniVendor)
    {
        // Ensure vendor owns this mini vendor
        if ($miniVendor->vendor_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access'], 403);
            }
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $oldStatus = $miniVendor->status;
        $miniVendor->update([
            'status' => $request->status
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Mini vendor status updated from {$oldStatus} to {$request->status} successfully!",
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'mini_vendor' => [
                    'id' => $miniVendor->id,
                    'status' => $miniVendor->status,
                    'affiliate_name' => $miniVendor->affiliate->name
                ]
            ]);
        }

        return back()->with('success', 'Mini vendor status updated successfully!');
    }

    /**
     * Remove a mini vendor assignment
     */
    public function destroy(Request $request, MiniVendor $miniVendor)
    {
        // Ensure vendor owns this mini vendor
        if ($miniVendor->vendor_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access'], 403);
            }
            abort(403);
        }

        $affiliate = $miniVendor->affiliate;
        $vendor = Auth::user(); // Get the main vendor who is removing the assignment
        $affiliateName = $affiliate->name;
        $totalCommissionEarned = $miniVendor->total_earned_commission;
        
        DB::transaction(function () use ($miniVendor, $affiliate, $vendor, $totalCommissionEarned) {
            // Delete the mini vendor assignment
            $miniVendor->delete();
            
            // Change user's role back to affiliate
            $oldRole = $affiliate->role;
            $affiliate->update(['role' => 'affiliate']);
            
            Log::info('User role reverted', [
                'user_id' => $affiliate->id,
                'old_role' => $oldRole,
                'new_role' => 'affiliate',
                'reason' => 'mini_vendor_assignment_removed'
            ]);
            
            // Send email notification about removal
            try {
                Mail::to($affiliate->email)->send(new MiniVendorRemoved($affiliate, $vendor, $totalCommissionEarned));
                Log::info('Mini vendor removal email sent', [
                    'recipient' => $affiliate->email,
                    'total_commission_earned' => $totalCommissionEarned
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send mini vendor removal email', [
                    'recipient' => $affiliate->email,
                    'error' => $e->getMessage()
                ]);
            }
        });

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Mini vendor assignment for {$affiliateName} removed successfully! User role changed back to affiliate. Notification email sent.",
                'affiliate_name' => $affiliateName,
                'role_changed' => true,
                'email_sent' => true
            ]);
        }

        return redirect()->route('vendor.mini-vendors.index')
            ->with('success', 'Mini vendor assignment removed successfully! User role changed back to affiliate. Notification email sent.');
    }

    /**
     * Get commission statistics for mini vendors
     */
    public function commissionStats()
    {
        $vendor = Auth::user();
        
        $stats = [
            'total_mini_vendors' => MiniVendor::where('vendor_id', $vendor->id)->count(),
            'active_mini_vendors' => MiniVendor::where('vendor_id', $vendor->id)->where('status', 'active')->count(),
            'total_commission_paid' => MiniVendor::where('vendor_id', $vendor->id)->sum('total_earned_commission'),
            'this_month_transfers' => 0 // This would need to be calculated from transfer records
        ];
        
        return response()->json($stats);
    }

    /**
     * Get mini vendor dashboard data
     */
    public function dashboard()
    {
        $vendor = Auth::user();
        
        $miniVendors = MiniVendor::where('vendor_id', $vendor->id)
            ->with('affiliate')
            ->where('status', 'active')
            ->orderBy('total_earned_commission', 'desc')
            ->take(5)
            ->get();
            
        $stats = [
            'total_mini_vendors' => MiniVendor::where('vendor_id', $vendor->id)->count(),
            'active_mini_vendors' => MiniVendor::where('vendor_id', $vendor->id)->where('status', 'active')->count(),
            'total_commission_paid' => MiniVendor::where('vendor_id', $vendor->id)->sum('total_earned_commission'),
        ];
        
        return view('vendor.mini-vendors.dashboard', compact('miniVendors', 'stats'));
    }
}