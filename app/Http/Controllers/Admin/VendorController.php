<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commission;
use App\Models\VendorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        // Fetch all vendors from the database
        $vendors = User::where('role', 'vendor')
                      ->withCount(['products', 'orders'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        // Calculate statistics
        $totalVendors = User::where('role', 'vendor')->count();
        $activeVendors = User::where('role', 'vendor')->where('status', 'active')->count();
        $pendingVendors = User::where('role', 'vendor')->where('status', 'pending')->count();
        $suspendedVendors = User::where('role', 'vendor')->where('status', 'suspended')->count();

        return view('admin.vendors.index', compact(
            'vendors',
            'totalVendors',
            'activeVendors', 
            'pendingVendors',
            'suspendedVendors'
        ));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'shop_address' => 'nullable|string',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        try {
            // Create the vendor with all information
            $vendor = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'role' => 'vendor',
                'status' => 'active',
                'is_verified_vendor' => true,
                'email_verified_at' => now(),
                
                // Shop information
                'shop_name' => $request->shop_name,
                'shop_description' => $request->shop_description,
                'shop_address' => $request->shop_address,
                'business_license' => $request->business_license,
                'tax_id' => $request->tax_id,
                
                // Address information
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor created successfully!',
                    'vendor' => $vendor
                ]);
            }

            return redirect()->route('admin.vendors.show', $vendor->id)
                ->with('success', 'Vendor created successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create vendor: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Failed to create vendor: ' . $e->getMessage());
        }
    }

    public function pending()
    {
        // Show pending vendor applications instead of pending vendor users
        $applications = VendorApplication::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'pending' => VendorApplication::where('status', 'pending')->count(),
            'approved' => VendorApplication::where('status', 'approved')->count(),
            'rejected' => VendorApplication::where('status', 'rejected')->count(),
            'total' => VendorApplication::count(),
        ];
            
        return view('admin.vendors.pending', compact('applications', 'stats'));
    }

    public function approved()
    {
        $vendors = User::where('role', 'vendor')
            ->where('status', 'active')
            ->latest()
            ->paginate(15);
            
        return view('admin.vendors.approved', compact('vendors'));
    }

    public function suspended()
    {
        $vendors = User::where('role', 'vendor')
            ->where('status', 'suspended')
            ->latest()
            ->paginate(15);
            
        return view('admin.vendors.suspended', compact('vendors'));
    }

    public function commissions()
    {
        $commissions = Commission::with(['user', 'referredUser', 'order'])
            ->latest()
            ->paginate(20);
            
        $totalCommissions = Commission::sum('commission_amount');
        $pendingCommissions = Commission::where('status', 'pending')->sum('commission_amount');
        $approvedCommissions = Commission::where('status', 'approved')->sum('commission_amount');
        $paidCommissions = Commission::where('status', 'paid')->sum('commission_amount');
        
        return view('admin.vendors.commissions', compact(
            'commissions', 
            'totalCommissions', 
            'pendingCommissions', 
            'approvedCommissions', 
            'paidCommissions'
        ));
    }

    public function show($id)
    {
        $vendor = User::where('role', 'vendor')
            ->with(['products', 'vendorOrders'])
            ->findOrFail($id);
            
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit($id)
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = User::where('role', 'vendor')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'shop_address' => 'nullable|string',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,pending,suspended',
            'commission_rate' => 'nullable|numeric|min:0|max:1',
        ]);

        try {
            $vendor->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'shop_name' => $request->shop_name,
                'shop_description' => $request->shop_description,
                'shop_address' => $request->shop_address,
                'business_license' => $request->business_license,
                'tax_id' => $request->tax_id,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'status' => $request->status,
                'commission_rate' => $request->commission_rate ?? $vendor->commission_rate,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor updated successfully!',
                    'vendor' => $vendor
                ]);
            }

            return redirect()->route('admin.vendors.show', $vendor->id)
                ->with('success', 'Vendor updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating vendor: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update vendor. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update vendor. Please try again.')
                ->withInput();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Status update logic
        return response()->json(['success' => true]);
    }

    public function approve($id)
    {
        try {
            $vendor = User::where('role', 'vendor')->findOrFail($id);
            
            $vendor->update([
                'status' => 'active',
                'is_verified_vendor' => true,
                'email_verified_at' => $vendor->email_verified_at ?? now(),
            ]);
            
            // You can add notification logic here
            // event(new VendorApproved($vendor));
            
            return response()->json([
                'success' => true,
                'message' => 'Vendor approved successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error approving vendor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve vendor.'
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $vendor = User::where('role', 'vendor')->findOrFail($id);
            
            $vendor->update([
                'status' => 'suspended',
                'is_verified_vendor' => false,
            ]);
            
            // You can add notification logic here
            // event(new VendorRejected($vendor));
            
            return response()->json([
                'success' => true,
                'message' => 'Vendor rejected successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error rejecting vendor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject vendor.'
            ], 500);
        }
    }

    public function suspend($id)
    {
        // Suspension logic
        return response()->json(['success' => true]);
    }

    /**
     * Display all vendor applications.
     */
    public function applications()
    {
        $applications = VendorApplication::with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'pending' => VendorApplication::where('status', 'pending')->count(),
            'approved' => VendorApplication::where('status', 'approved')->count(),
            'rejected' => VendorApplication::where('status', 'rejected')->count(),
            'total' => VendorApplication::count(),
        ];

        return view('admin.vendors.applications', compact('applications', 'stats'));
    }

    /**
     * Show specific vendor application.
     */
    public function showApplication($id)
    {
        $application = VendorApplication::with(['user', 'reviewer'])->findOrFail($id);
        return view('admin.vendors.application-detail', compact('application'));
    }

    /**
     * Approve vendor application.
     */
    public function approveApplication(Request $request, $id)
    {
        $application = VendorApplication::findOrFail($id);
        
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Convert user to vendor and set shop details from application
        $application->user->becomeVendor([
            'shop_name' => $application->business_name,
            'shop_description' => $application->business_description,
            'business_license' => null, // Can be set later
            'tax_id' => null, // Can be set later
        ]);

        return redirect()
            ->route('admin.vendors.applications')
            ->with('success', 'Vendor application approved successfully! User role updated to vendor.');
    }

    /**
     * Reject vendor application.
     */
    public function rejectApplication(Request $request, $id)
    {
        $application = VendorApplication::findOrFail($id);
        
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes ?? 'Application rejected.',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.vendors.applications')
            ->with('success', 'Vendor application rejected.');
    }
}
