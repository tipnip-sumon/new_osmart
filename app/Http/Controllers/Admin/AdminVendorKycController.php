<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorKycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminVendorKycController extends Controller
{
    /**
     * Display all Vendor KYC verifications
     */
    public function index(Request $request)
    {
        $query = VendorKycVerification::with('vendor')->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by vendor details
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('owner_full_name', 'like', "%{$search}%");
        }
        
        $kycVerifications = $query->paginate(20);
        
        // Statistics
        $stats = $this->getVendorKycStats();
        
        $data = [
            'pageTitle' => 'Vendor KYC Verifications Management',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['status', 'search']),
            'stats' => $stats
        ];
        
        return view('admin.vendor-kyc.index', $data);
    }

    /**
     * Show pending vendor KYC verifications
     */
    public function pending(Request $request)
    {
        $query = VendorKycVerification::with('vendor')
            ->where('status', 'pending')
            ->latest();
            
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('owner_full_name', 'like', "%{$search}%");
        }
        
        $kycVerifications = $query->paginate(20);
        $stats = $this->getVendorKycStats();
        
        $data = [
            'pageTitle' => 'Pending Vendor KYC Verifications',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['search']),
            'stats' => $stats,
            'currentStatus' => 'pending'
        ];
        
        return view('admin.vendor-kyc.index', $data);
    }

    /**
     * Show approved vendor KYC verifications
     */
    public function approved(Request $request)
    {
        $query = VendorKycVerification::with('vendor')
            ->where('status', 'approved')
            ->latest('approved_at');
            
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('owner_full_name', 'like', "%{$search}%");
        }
        
        $kycVerifications = $query->paginate(20);
        $stats = $this->getVendorKycStats();
        
        $data = [
            'pageTitle' => 'Approved Vendor KYC Verifications',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['search']),
            'stats' => $stats,
            'currentStatus' => 'approved'
        ];
        
        return view('admin.vendor-kyc.index', $data);
    }

    /**
     * Show rejected vendor KYC verifications
     */
    public function rejected(Request $request)
    {
        $query = VendorKycVerification::with('vendor')
            ->where('status', 'rejected')
            ->latest('rejected_at');
            
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('owner_full_name', 'like', "%{$search}%");
        }
        
        $kycVerifications = $query->paginate(20);
        $stats = $this->getVendorKycStats();
        
        $data = [
            'pageTitle' => 'Rejected Vendor KYC Verifications',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['search']),
            'stats' => $stats,
            'currentStatus' => 'rejected'
        ];
        
        return view('admin.vendor-kyc.index', $data);
    }

    /**
     * Show under review vendor KYC verifications
     */
    public function getUnderReview(Request $request)
    {
        $query = VendorKycVerification::with('vendor')
            ->where('status', 'under_review')
            ->latest('reviewed_at');
            
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('business_name', 'like', "%{$search}%")
              ->orWhere('owner_full_name', 'like', "%{$search}%");
        }
        
        $kycVerifications = $query->paginate(20);
        $stats = $this->getVendorKycStats();
        
        $data = [
            'pageTitle' => 'Under Review Vendor KYC Verifications',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['search']),
            'stats' => $stats,
            'currentStatus' => 'under_review'
        ];
        
        return view('admin.vendor-kyc.index', $data);
    }

    /**
     * Show specific vendor KYC verification
     */
    public function show($id)
    {
        $kyc = VendorKycVerification::with('vendor')->findOrFail($id);
        
        $data = [
            'pageTitle' => 'Vendor KYC Details - ' . $kyc->business_name,
            'kyc' => $kyc
        ];
        
        return view('admin.vendor-kyc.show', $data);
    }

    /**
     * Update vendor KYC status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected,under_review,pending',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $kyc = VendorKycVerification::findOrFail($id);
            $oldStatus = $kyc->status;
            
            DB::beginTransaction();
            
            // Update KYC status
            $kyc->status = $request->status;
            $kyc->admin_notes = $request->admin_notes;
            $kyc->reviewed_by = Auth::id();
            $kyc->reviewed_at = now();
            
            if ($request->status === 'approved') {
                $kyc->approved_at = now();
                $kyc->rejected_at = null;
                $kyc->rejection_reason = null;
            } elseif ($request->status === 'rejected') {
                $kyc->rejected_at = now();
                $kyc->approved_at = null;
                $kyc->rejection_reason = $request->rejection_reason;
            }
            
            $kyc->save();
            
            DB::commit();
            
            // Log the status change
            Log::info('Vendor KYC status updated', [
                'kyc_id' => $kyc->id,
                'vendor_id' => $kyc->vendor_id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_id' => Auth::id()
            ]);
            
            return back()->with('success', 'Vendor KYC status updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update vendor KYC status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update KYC status. Please try again.');
        }
    }

    /**
     * Bulk approve vendor KYCs
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kyc_ids' => 'required|array',
            'kyc_ids.*' => 'exists:vendor_kyc_verifications,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();
            
            $updated = VendorKycVerification::whereIn('id', $request->kyc_ids)
                ->where('status', '!=', 'approved')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'rejected_at' => null,
                    'rejection_reason' => null,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now()
                ]);
            
            DB::commit();
            
            return back()->with('success', "Successfully approved {$updated} vendor KYC verifications!");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk approve vendor KYC failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to bulk approve. Please try again.');
        }
    }

    /**
     * Bulk change status
     */
    public function bulkChangeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kyc_ids' => 'required|array',
            'kyc_ids.*' => 'exists:vendor_kyc_verifications,id',
            'status' => 'required|in:approved,rejected,under_review,pending'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();
            
            $updateData = [
                'status' => $request->status,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now()
            ];
            
            if ($request->status === 'approved') {
                $updateData['approved_at'] = now();
                $updateData['rejected_at'] = null;
                $updateData['rejection_reason'] = null;
            } elseif ($request->status === 'rejected') {
                $updateData['rejected_at'] = now();
                $updateData['approved_at'] = null;
            }
            
            $updated = VendorKycVerification::whereIn('id', $request->kyc_ids)
                ->update($updateData);
            
            DB::commit();
            
            return back()->with('success', "Successfully updated {$updated} vendor KYC verifications to {$request->status}!");
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk change vendor KYC status failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update statuses. Please try again.');
        }
    }

    /**
     * Mark vendor KYC as under review
     */
    public function markUnderReview($id)
    {
        try {
            $kyc = VendorKycVerification::findOrFail($id);
            
            $kyc->update([
                'status' => 'under_review',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now()
            ]);
            
            return back()->with('success', 'Vendor KYC marked as under review!');
            
        } catch (\Exception $e) {
            Log::error('Failed to mark vendor KYC under review: ' . $e->getMessage());
            return back()->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * View document
     */
    public function viewDocument($id, $type)
    {
        $kyc = VendorKycVerification::findOrFail($id);
        
        if (!$kyc->$type) {
            abort(404, 'Document not found');
        }
        
        $path = $kyc->$type;
        
        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }
        
        return Storage::response($path);
    }

    /**
     * Download document
     */
    public function downloadDocument($id, $type)
    {
        $kyc = VendorKycVerification::findOrFail($id);
        
        if (!$kyc->$type) {
            abort(404, 'Document not found');
        }
        
        $path = $kyc->$type;
        
        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }
        
        return Storage::download($path, basename($path));
    }

    /**
     * Get vendor KYC statistics
     */
    public function statistics(Request $request)
    {
        $stats = $this->getVendorKycStats();
        
        if ($request->ajax()) {
            return response()->json($stats);
        }
        
        return view('admin.vendor-kyc.statistics', compact('stats'));
    }

    /**
     * Get vendor KYC statistics data
     */
    private function getVendorKycStats()
    {
        $total = VendorKycVerification::count();
        $pending = VendorKycVerification::where('status', 'pending')->count();
        $approved = VendorKycVerification::where('status', 'approved')->count();
        $rejected = VendorKycVerification::where('status', 'rejected')->count();
        $underReview = VendorKycVerification::where('status', 'under_review')->count();
        $draft = VendorKycVerification::where('status', 'draft')->count();
        
        // Recent submissions (last 7 days)
        $recentSubmissions = VendorKycVerification::where('submitted_at', '>=', Carbon::now()->subDays(7))->count();
        
        // Approval rate
        $approvalRate = $total > 0 ? round(($approved / $total) * 100, 2) : 0;
        
        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'under_review' => $underReview,
            'draft' => $draft,
            'recent_submissions' => $recentSubmissions,
            'approval_rate' => $approvalRate
        ];
    }
}