<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberKycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;

class AdminKycController extends Controller
{
    /**
     * Display all KYC verifications with advanced filtering
     */
    public function index(Request $request)
    {
        $query = MemberKycVerification::with(['user', 'verifiedBy', 'rejectedBy'])->latest();
        
        // Advanced filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Advanced search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('full_name', 'like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
                ->orWhere('email_address', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'updated_at', 'submitted_at', 'verified_at', 'rejected_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $perPage = $request->get('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }
        
        $kycVerifications = $query->paginate($perPage);
        
        // Enhanced statistics
        $stats = $this->getAdvancedKycStats();
        
        // If AJAX request, return JSON for real-time updates
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.kyc.partials.table', compact('kycVerifications'))->render(),
                'pagination' => (string) $kycVerifications->appends(request()->query())->links(),
                'stats' => $stats
            ]);
        }
        
        $data = [
            'pageTitle' => 'KYC Verifications Management',
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['status', 'search', 'risk_level', 'document_type', 'date_from', 'date_to', 'sort_by', 'sort_order', 'per_page']),
            'stats' => $stats
        ];
        
        return view('admin.kyc.index', $data);
    }
    
    /**
     * Show specific KYC verification details
     */
    public function show($id)
    {
        $kycVerification = MemberKycVerification::with(['user'])->find($id);
        if (!$kycVerification) {
            return redirect()->route('admin.kyc.index')->with('error', 'KYC Record not found.');
        }
        
        $pageTitle = 'KYC Verification Details - ' . ($kycVerification->user->username ?? 'User #' . $kycVerification->id);
        
        return view('admin.kyc.show', compact('kycVerification', 'pageTitle'));
    }

    /**
     * Show pending KYC verifications
     */
    public function pending(Request $request)
    {
        return $this->getKycByStatus('pending', 'Pending KYC Verifications', $request);
    }

    /**
     * Show approved KYC verifications
     */
    public function approved(Request $request)
    {
        return $this->getKycByStatus('verified', 'Verified KYC Verifications', $request);
    }

    /**
     * Show rejected KYC verifications
     */
    public function rejected(Request $request)
    {
        return $this->getKycByStatus('rejected', 'Rejected KYC Verifications', $request);
    }

    /**
     * Helper method to get KYC verifications by status
     */
    private function getKycByStatus($status, $pageTitle, Request $request)
    {
        $query = MemberKycVerification::with('user')->where('status', $status)->latest();
        
        // Search by user details
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $kycVerifications = $query->paginate(20);
        
        // Statistics
        $stats = $this->getKycStats();
        
        $data = [
            'pageTitle' => $pageTitle,
            'kycVerifications' => $kycVerifications,
            'filters' => $request->only(['search']),
            'stats' => $stats,
            'currentStatus' => $status
        ];
        
        return view('admin.kyc.index', $data);
    }
    
    /**
     * Update KYC verification status (approve/reject/under_review)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Log the incoming request
            Log::info('KYC Update Status Request', [
                'kyc_id' => $id,
                'request_data' => $request->all(),
                'admin_id' => Auth::id()
            ]);
            
            // Validate request
            $validated = $request->validate([
                'status' => 'required|in:verified,rejected,under_review,pending',
                'admin_remarks' => 'nullable|string|max:1000'
            ]);
            
            // Find KYC record
            $kycVerification = MemberKycVerification::find($id);
            if (!$kycVerification) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC verification not found.'
                ], 404);
            }
            
            // Update KYC status
            $updateData = [
                'status' => $validated['status'],
                'admin_remarks' => $validated['admin_remarks'],
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ];
            
            // Set status-specific timestamps
            switch ($validated['status']) {
                case 'verified':
                    $updateData['verified_at'] = now();
                    $updateData['rejected_at'] = null;
                    $updateData['under_review_at'] = null;
                    break;
                    
                case 'rejected':
                    $updateData['rejected_at'] = now();
                    $updateData['verified_at'] = null;
                    $updateData['under_review_at'] = null;
                    break;
                    
                case 'under_review':
                    $updateData['under_review_at'] = now();
                    $updateData['verified_at'] = null;
                    $updateData['rejected_at'] = null;
                    break;
                    
                case 'pending':
                    $updateData['verified_at'] = null;
                    $updateData['rejected_at'] = null;
                    $updateData['under_review_at'] = null;
                    break;
            }
            
            $kycVerification->update($updateData);
            
            // Update user KYC status
            if ($kycVerification->user) {
                $userKvStatus = [
                    'verified' => 1,
                    'rejected' => 0,
                    'under_review' => 2,
                    'pending' => 0
                ];
                
                $kycVerification->user->update([
                    'kv' => $userKvStatus[$validated['status']] ?? 0
                ]);
            }
            
            Log::info('KYC Status Updated Successfully', [
                'kyc_id' => $id,
                'new_status' => $validated['status'],
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'KYC verification status updated successfully!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('KYC Update Validation Error', [
                'kyc_id' => $id,
                'errors' => $e->errors(),
                'admin_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('KYC Update Error', [
                'kyc_id' => $id ?? 'unknown',
                'request_data' => $request->all(),
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'admin_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')'
            ], 500);
        }
    }
    
    /**
     * Mark KYC as under review
     */
    public function markUnderReview(Request $request, $id)
    {
        try {
            $request->validate([
                'admin_remarks' => 'nullable|string|max:1000'
            ]);
            
            $kycVerification = MemberKycVerification::findOrFail($id);
            $oldStatus = $kycVerification->status;
            
            // Update to under review
            $kycVerification->update([
                'status' => 'under_review',
                'admin_remarks' => $request->admin_remarks ?? 'Document under review by admin.',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
                'under_review_at' => now(),
                'verified_at' => null,
                'rejected_at' => null,
            ]);
            
            // Update user's KYC status
            $user = $kycVerification->user;
            if ($user) {
                $user->kv = 2; // Under review
                $user->save();
            }
            
            Log::info('KYC marked as under review', [
                'kyc_id' => $id,
                'user_id' => $kycVerification->user_id,
                'old_status' => $oldStatus,
                'admin_id' => Auth::id(),
                'admin_remarks' => $request->admin_remarks
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'KYC verification marked as under review!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error marking KYC under review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the status.'
            ], 500);
        }
    }
    
    /**
     * Bulk change status
     */
    public function bulkChangeStatus(Request $request)
    {
        try {
            $request->validate([
                'kyc_ids' => 'required|array',
                'kyc_ids.*' => 'exists:member_kyc_verifications,id',
                'status' => 'required|in:verified,rejected,under_review,pending',
                'admin_remarks' => 'nullable|string|max:1000'
            ]);
            
            $kycVerifications = MemberKycVerification::whereIn('id', $request->kyc_ids)
                ->with('user')
                ->get();
            
            $updated = 0;
            $adminRemarks = $request->admin_remarks ?? "Bulk {$request->status} by admin";
            
            foreach ($kycVerifications as $kyc) {
                $updateData = [
                    'status' => $request->status,
                    'admin_remarks' => $adminRemarks,
                    'reviewed_at' => now(),
                    'reviewed_by' => Auth::id(),
                ];
                
                // Set status-specific timestamps and user KV status
                switch ($request->status) {
                    case 'verified':
                        $updateData['verified_at'] = now();
                        $updateData['rejected_at'] = null;
                        $updateData['under_review_at'] = null;
                        $userKvStatus = 1;
                        break;
                        
                    case 'rejected':
                        $updateData['rejected_at'] = now();
                        $updateData['verified_at'] = null;
                        $updateData['under_review_at'] = null;
                        $userKvStatus = 0;
                        break;
                        
                    case 'under_review':
                        $updateData['under_review_at'] = now();
                        $updateData['verified_at'] = null;
                        $updateData['rejected_at'] = null;
                        $userKvStatus = 2;
                        break;
                        
                    default:
                        $updateData['verified_at'] = null;
                        $updateData['rejected_at'] = null;
                        $updateData['under_review_at'] = null;
                        $userKvStatus = 0;
                        break;
                }
                
                $kyc->update($updateData);
                
                // Update user KYC status
                if ($kyc->user) {
                    $kyc->user->update(['kv' => $userKvStatus]);
                }
                
                $updated++;
            }
            
            Log::info('Bulk KYC status change completed', [
                'admin_id' => Auth::id(),
                'status' => $request->status,
                'updated_count' => $updated,
                'kyc_ids' => $request->kyc_ids
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "{$updated} KYC verification(s) status changed to {$request->status} successfully!"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bulk status change: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during bulk status change.'
            ], 500);
        }
    }
    
    /**
     * Bulk approve KYC verifications (legacy method)
     */
    public function bulkApprove(Request $request)
    {
        $request->merge(['status' => 'verified']);
        return $this->bulkChangeStatus($request);
    }
    
    /**
     * Get KYC verifications under review
     */
    public function getUnderReview(Request $request)
    {
        $query = MemberKycVerification::with('user')
            ->where('status', 'under_review')
            ->latest('under_review_at');
        
        $kycVerifications = $query->paginate(20);
        
        $data = [
            'pageTitle' => 'KYC Verifications Under Review',
            'kycVerifications' => $kycVerifications,
            'filters' => ['status' => 'under_review']
        ];
        
        return view('admin.kyc.index', $data);
    }
    
    /**
     * View KYC document with comprehensive path resolution
     */
    public function viewDocument($id, $type)
    {
        try {
            $kyc = MemberKycVerification::findOrFail($id);
            
            $filePath = null;
            $fieldName = null;
            
            switch ($type) {
                case 'front':
                    $filePath = $kyc->document_front_image;
                    $fieldName = 'document_front_image';
                    break;
                case 'back':
                    $filePath = $kyc->document_back_image;
                    $fieldName = 'document_back_image';
                    break;
                case 'selfie':
                    $filePath = $kyc->user_photo;
                    $fieldName = 'user_photo';
                    break;
                case 'utility':
                    $filePath = $kyc->utility_bill;
                    $fieldName = 'utility_bill';
                    break;
                case 'signature':
                    $filePath = $kyc->user_signature;
                    $fieldName = 'user_signature';
                    break;
                default:
                    return response()->json(['error' => "Invalid document type: {$type}"], 400);
            }
            
            if (!$filePath) {
                return response()->json(['error' => "Document {$type} not found for KYC ID {$id}. Field {$fieldName} is empty."], 404);
            }
            
            // Comprehensive file path resolution (similar to product image handling)
            $possiblePaths = [
                // Standard Laravel storage paths
                $filePath,                                    // Direct path
                "public/{$filePath}",                        // Public disk path
                "app/public/{$filePath}",                    // Full storage path
                
                // Legacy paths
                "uploads/{$filePath}",                       // Legacy uploads
                "storage/{$filePath}",                       // Legacy storage
                
                // Alternative structures
                str_replace('kyc/', '', $filePath),          // Without kyc prefix
                "kyc/{$filePath}",                          // With kyc prefix
                "documents/{$filePath}",                     // Documents folder
                
                // Direct file system paths
                storage_path("app/public/{$filePath}"),      // Full system path
                storage_path("app/{$filePath}"),             // App path
                public_path("storage/{$filePath}"),          // Public storage
                public_path("uploads/{$filePath}"),          // Public uploads
            ];
            
            $actualPath = null;
            $usePublicDisk = false;
            $useDirectPath = false;
            
            // Try each possible path
            foreach ($possiblePaths as $testPath) {
                if (file_exists($testPath)) {
                    $actualPath = $testPath;
                    $useDirectPath = true;
                    break;
                } elseif (Storage::disk('public')->exists(str_replace('storage/app/public/', '', $testPath))) {
                    $actualPath = str_replace('storage/app/public/', '', $testPath);
                    $usePublicDisk = true;
                    break;
                } elseif (Storage::exists($testPath)) {
                    $actualPath = $testPath;
                    break;
                }
            }
            
            if (!$actualPath) {
                // Create debug info
                $debugPaths = [];
                foreach (array_slice($possiblePaths, 0, 5) as $path) {
                    $debugPaths[] = [
                        'path' => $path,
                        'exists' => file_exists($path),
                        'storage_exists' => Storage::exists($path),
                        'public_disk_exists' => Storage::disk('public')->exists(str_replace(['storage/app/public/', 'public/'], '', $path))
                    ];
                }
                
                return response()->json([
                    'error' => "File not found in any location",
                    'debug' => [
                        'kyc_id' => $id,
                        'document_type' => $type,
                        'field_name' => $fieldName,
                        'original_path' => $filePath,
                        'checked_paths' => $debugPaths,
                        'storage_root' => storage_path('app'),
                        'public_root' => public_path('storage')
                    ]
                ], 404);
            }
            
            // Get file content based on resolution method
            if ($useDirectPath) {
                $fileContent = file_get_contents($actualPath);
                $mimeType = mime_content_type($actualPath);
            } elseif ($usePublicDisk) {
                $fileContent = Storage::disk('public')->get($actualPath);
                $fullPath = Storage::disk('public')->path($actualPath);
                $mimeType = mime_content_type($fullPath);
            } else {
                $fileContent = Storage::get($actualPath);
                $fullPath = Storage::path($actualPath);
                $mimeType = mime_content_type($fullPath);
            }
            
            if (!$fileContent) {
                return response()->json(['error' => 'Failed to read file content'], 500);
            }
            
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline')
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('X-File-Path', $actualPath);
                
        } catch (\Exception $e) {
            Log::error('Error viewing KYC document: ' . $e->getMessage(), [
                'kyc_id' => $id ?? 'unknown',
                'document_type' => $type ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to view document', 
                'message' => $e->getMessage(),
                'type' => get_class($e)
            ], 500);
        }
    }

    /**
     * Download KYC document with comprehensive path resolution
     */
    public function downloadDocument($id, $type)
    {
        try {
            $kyc = MemberKycVerification::with('user')->findOrFail($id);
            
            $filePath = null;
            $fileName = null;
            
            switch ($type) {
                case 'front':
                    $filePath = $kyc->document_front_image;
                    $fileName = 'document_front_' . ($kyc->user->username ?? $kyc->user_id);
                    break;
                case 'back':
                    $filePath = $kyc->document_back_image;
                    $fileName = 'document_back_' . ($kyc->user->username ?? $kyc->user_id);
                    break;
                case 'selfie':
                    $filePath = $kyc->user_photo;
                    $fileName = 'user_photo_' . ($kyc->user->username ?? $kyc->user_id);
                    break;
                case 'utility':
                    $filePath = $kyc->utility_bill;
                    $fileName = 'utility_bill_' . ($kyc->user->username ?? $kyc->user_id);
                    break;
                case 'signature':
                    $filePath = $kyc->user_signature;
                    $fileName = 'signature_' . ($kyc->user->username ?? $kyc->user_id);
                    break;
                default:
                    return response()->json(['error' => "Invalid document type: {$type}"], 400);
            }
            
            if (!$filePath) {
                return response()->json(['error' => "Document not found"], 404);
            }
            
            // Use same comprehensive path resolution as viewDocument
            $possiblePaths = [
                $filePath,
                "public/{$filePath}",
                "app/public/{$filePath}",
                "uploads/{$filePath}",
                "storage/{$filePath}",
                str_replace('kyc/', '', $filePath),
                "kyc/{$filePath}",
                "documents/{$filePath}",
                storage_path("app/public/{$filePath}"),
                storage_path("app/{$filePath}"),
                public_path("storage/{$filePath}"),
                public_path("uploads/{$filePath}"),
            ];
            
            $actualPath = null;
            $useDirectPath = false;
            
            foreach ($possiblePaths as $testPath) {
                if (file_exists($testPath)) {
                    $actualPath = $testPath;
                    $useDirectPath = true;
                    break;
                } elseif (Storage::disk('public')->exists(str_replace(['storage/app/public/', 'public/'], '', $testPath))) {
                    $actualPath = Storage::disk('public')->path(str_replace(['storage/app/public/', 'public/'], '', $testPath));
                    $useDirectPath = true;
                    break;
                } elseif (Storage::exists($testPath)) {
                    $actualPath = Storage::path($testPath);
                    $useDirectPath = true;
                    break;
                }
            }
            
            if (!$actualPath || !file_exists($actualPath)) {
                return response()->json([
                    'error' => 'File not found on server',
                    'debug' => [
                        'original_path' => $filePath,
                        'checked_first_5' => array_slice($possiblePaths, 0, 5)
                    ]
                ], 404);
            }
            
            $extension = pathinfo($actualPath, PATHINFO_EXTENSION);
            $downloadFileName = $fileName . '.' . $extension;
            
            return response()->download($actualPath, $downloadFileName);
            
        } catch (\Exception $e) {
            Log::error('Error downloading KYC document: ' . $e->getMessage(), [
                'kyc_id' => $id ?? 'unknown',
                'document_type' => $type ?? 'unknown'
            ]);
            
            return response()->json([
                'error' => 'Failed to download document',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get KYC statistics
     */
    public function statistics()
    {
        $stats = $this->getKycStats();
        return response()->json($stats);
    }

    /**
     * Get advanced KYC statistics helper method
     */
    private function getAdvancedKycStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total' => MemberKycVerification::count(),
            'pending' => MemberKycVerification::where('status', 'pending')->count(),
            'verified' => MemberKycVerification::where('status', 'verified')->count(),
            'rejected' => MemberKycVerification::where('status', 'rejected')->count(),
            'under_review' => MemberKycVerification::where('status', 'under_review')->count(),
            'draft' => MemberKycVerification::where('status', 'draft')->count(),
            
            // Time-based stats
            'today' => MemberKycVerification::whereDate('created_at', $today)->count(),
            'this_week' => MemberKycVerification::where('created_at', '>=', $thisWeek)->count(),
            'this_month' => MemberKycVerification::where('created_at', '>=', $thisMonth)->count(),
            
            // Risk level stats
            'high_risk' => MemberKycVerification::where('risk_level', 'high')->count(),
            'medium_risk' => MemberKycVerification::where('risk_level', 'medium')->count(),
            'low_risk' => MemberKycVerification::where('risk_level', 'low')->count(),
            
            // Document type stats
            'nid_count' => MemberKycVerification::where('document_type', 'nid')->count(),
            'passport_count' => MemberKycVerification::where('document_type', 'passport')->count(),
            'driving_license_count' => MemberKycVerification::where('document_type', 'driving_license')->count(),
            
            // Processing time stats
            'avg_processing_days' => $this->getAverageProcessingDays(),
            'pending_over_7_days' => MemberKycVerification::where('status', 'pending')
                                     ->where('created_at', '<', Carbon::now()->subDays(7))
                                     ->count()
        ];
    }
    
    /**
     * Calculate average processing days for approved KYCs
     */
    private function getAverageProcessingDays()
    {
        $approvedKycs = MemberKycVerification::whereNotNull('verified_at')
            ->whereNotNull('submitted_at')
            ->get();        if ($approvedKycs->isEmpty()) {
            return 0;
        }
        
        $totalDays = 0;
        foreach ($approvedKycs as $kyc) {
            $totalDays += $kyc->submitted_at->diffInDays($kyc->verified_at);
        }
        
        return round($totalDays / $approvedKycs->count(), 1);
    }
    
    /**
     * Get legacy KYC statistics helper method for backward compatibility
     */
    private function getKycStats()
    {
        return [
            'total' => MemberKycVerification::count(),
            'pending' => MemberKycVerification::where('status', 'pending')->count(),
            'verified' => MemberKycVerification::where('status', 'verified')->count(),
            'rejected' => MemberKycVerification::where('status', 'rejected')->count(),
            'draft' => MemberKycVerification::where('status', 'draft')->count(),
        ];
    }
    
    /**
     * Export KYC data to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = MemberKycVerification::with(['user', 'verifiedBy']);
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $kycData = $query->get();
        
        $filename = 'kyc_verifications_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($kycData) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'User Name', 'Email', 'Full Name', 'Document Type', 'Document Number',
                'Phone', 'Status', 'Risk Level', 'Submitted At', 'Verified At', 'Verified By',
                'Created At', 'Admin Notes'
            ]);
            
            foreach ($kycData as $kyc) {
                fputcsv($file, [
                    $kyc->id,
                    $kyc->user->name ?? 'N/A',
                    $kyc->user->email ?? 'N/A',
                    $kyc->full_name,
                    $kyc->document_type,
                    $kyc->document_number,
                    $kyc->phone_number,
                    ucfirst($kyc->status),
                    ucfirst($kyc->risk_level),
                    $kyc->submitted_at ? $kyc->submitted_at->format('Y-m-d H:i:s') : 'N/A',
                    $kyc->verified_at ? $kyc->verified_at->format('Y-m-d H:i:s') : 'N/A',
                    $kyc->verifiedBy->name ?? 'N/A',
                    $kyc->created_at->format('Y-m-d H:i:s'),
                    $kyc->admin_remarks ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get real-time dashboard stats
     */
    public function getDashboardStats()
    {
        return response()->json($this->getAdvancedKycStats());
    }
    
    /**
     * Assign KYC to admin for review
     */
    public function assignToAdmin(Request $request, $id)
    {
        try {
            $request->validate([
                'admin_id' => 'required|exists:users,id',
                'notes' => 'nullable|string|max:500'
            ]);
            
            $kyc = MemberKycVerification::findOrFail($id);
            
            $kyc->update([
                'assigned_to' => $request->admin_id,
                'assigned_at' => now(),
                'status' => 'under_review',
                'admin_remarks' => ($kyc->admin_remarks ? $kyc->admin_remarks . "\n\n" : '') . 
                                  "Assigned to admin on " . now()->format('Y-m-d H:i:s') . 
                                  ($request->notes ? ": {$request->notes}" : '')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'KYC assigned to admin successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign KYC: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update risk level
     */
    public function updateRiskLevel(Request $request, $id)
    {
        try {
            $request->validate([
                'risk_level' => 'required|in:low,medium,high',
                'risk_notes' => 'nullable|string|max:1000'
            ]);
            
            $kyc = MemberKycVerification::findOrFail($id);
            
            $kyc->update([
                'risk_level' => $request->risk_level,
                'risk_notes' => $request->risk_notes,
                'updated_by' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Risk level updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update risk level: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get KYC activity log
     */
    public function getActivityLog($id)
    {
        $kyc = MemberKycVerification::findOrFail($id);
        
        $activities = [];
        
        if ($kyc->created_at) {
            $activities[] = [
                'date' => $kyc->created_at,
                'action' => 'KYC Application Created',
                'admin' => null,
                'notes' => 'Initial KYC application created'
            ];
        }
        
        if ($kyc->submitted_at) {
            $activities[] = [
                'date' => $kyc->submitted_at,
                'action' => 'KYC Submitted',
                'admin' => null,
                'notes' => 'KYC submitted for review'
            ];
        }
        
        if ($kyc->under_review_at) {
            $activities[] = [
                'date' => $kyc->under_review_at,
                'action' => 'Under Review',
                'admin' => $kyc->reviewed_by,
                'notes' => 'KYC marked as under review'
            ];
        }
        
        if ($kyc->verified_at) {
            $activities[] = [
                'date' => $kyc->verified_at,
                'action' => 'Approved',
                'admin' => $kyc->verified_by,
                'notes' => 'KYC approved'
            ];
        }
        
        if ($kyc->rejected_at) {
            $activities[] = [
                'date' => $kyc->rejected_at,
                'action' => 'Rejected',
                'admin' => $kyc->rejected_by,
                'notes' => $kyc->rejection_reason ?? 'KYC rejected'
            ];
        }
        
        // Sort by date
        usort($activities, function($a, $b) {
            return $a['date']->timestamp - $b['date']->timestamp;
        });
        
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
    
    /**
     * Download all documents for a KYC verification
     * If ZIP is available, creates a ZIP file. Otherwise, returns list of document URLs.
     */
    public function downloadAllDocuments($id)
    {
        try {
            $kyc = MemberKycVerification::with('user')->findOrFail($id);
            
            $documents = [
                'document_front_image' => 'Front Document',
                'document_back_image' => 'Back Document', 
                'user_photo' => 'User Photo',
                'user_signature' => 'User Signature',
                'utility_bill' => 'Utility Bill'
            ];
            
            $availableDocuments = [];
            foreach ($documents as $field => $name) {
                if ($kyc->$field) {
                    $filePath = storage_path('app/public/' . $kyc->$field);
                    // Also try without 'public/' prefix for older file paths
                    if (!file_exists($filePath)) {
                        $filePath = storage_path('app/' . $kyc->$field);
                    }
                    
                    if (file_exists($filePath)) {
                        $availableDocuments[] = [
                            'name' => $name,
                            'field' => $field,
                            'path' => $kyc->$field,
                            'url' => asset('storage/' . $kyc->$field),
                            'size' => filesize($filePath),
                            'extension' => pathinfo($filePath, PATHINFO_EXTENSION)
                        ];
                    }
                }
            }
            
            if (empty($availableDocuments)) {
                return response()->json(['error' => 'No documents found'], 404);
            }

            // If ZIP is available, try to create ZIP file
            if (class_exists('ZipArchive')) {
                $username = $kyc->user->username ?? $kyc->user->name ?? 'user';
                $zip = new ZipArchive();
                $zipFileName = "kyc_documents_{$kyc->id}_{$username}.zip";
                $zipPath = storage_path('app/temp/' . $zipFileName);
                
                // Create temp directory if it doesn't exist
                if (!file_exists(storage_path('app/temp'))) {
                    mkdir(storage_path('app/temp'), 0755, true);
                }
                
                if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                    foreach ($availableDocuments as $doc) {
                        $filePath = storage_path('app/public/' . $doc['path']);
                        if (!file_exists($filePath)) {
                            $filePath = storage_path('app/' . $doc['path']);
                        }
                        
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, $doc['name'] . '.' . $doc['extension']);
                        }
                    }
                    
                    $zip->close();
                    return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
                }
            }
            
            // If ZIP is not available or failed, return document list for individual download
            return response()->json([
                'message' => 'ZIP functionality not available. Individual downloads provided.',
                'documents' => $availableDocuments,
                'download_individual_url' => route('admin.kyc.document.download', ['id' => $kyc->id, 'field' => 'FIELD_NAME'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error downloading KYC documents: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to download documents: ' . $e->getMessage()], 500);
        }
    }
}
