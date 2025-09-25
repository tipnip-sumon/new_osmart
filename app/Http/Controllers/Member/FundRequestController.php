<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\VendorTransfer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FundRequestController extends Controller
{
    /**
     * Display fund request page
     */
    public function index()
    {
        $member = Auth::user();
        
        $recentRequests = FundRequest::where('member_id', $member->id)
            ->with(['vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $availableVendors = User::whereHas('roles', function($q) {
                $q->where('name', 'vendor');
            })
            ->where('is_active', true)
            ->where('deposit_wallet', '>', 100) // Only show vendors with sufficient balance
            ->select('id', 'name', 'email', 'shop_name', 'deposit_wallet')
            ->get();

        $stats = [
            'total_requested' => FundRequest::where('member_id', $member->id)->sum('amount'),
            'total_received' => FundRequest::where('member_id', $member->id)
                ->where('status', 'completed')
                ->sum('approved_amount'),
            'pending_requests' => FundRequest::where('member_id', $member->id)
                ->where('status', 'pending')
                ->count()
        ];

        return view('member.fund-requests.index', compact(
            'recentRequests', 
            'availableVendors', 
            'stats'
        ));
    }

    /**
     * Create new fund request via AJAX
     */
    public function createRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:10|max:10000',
            'request_type' => 'required|in:withdrawal,bonus,commission,emergency',
            'purpose' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $member = Auth::user();
        
        // Check if member has too many pending requests
        $pendingCount = FundRequest::where('member_id', $member->id)
            ->where('status', 'pending')
            ->count();
            
        if ($pendingCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'You have too many pending requests. Please wait for them to be processed.'
            ], 400);
        }

        // Check if member has made request to this vendor recently
        $recentRequest = FundRequest::where('member_id', $member->id)
            ->where('vendor_id', $request->vendor_id)
            ->where('created_at', '>=', now()->subHours(24))
            ->where('status', 'pending')
            ->first();
            
        if ($recentRequest) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending request to this vendor within the last 24 hours.'
            ], 400);
        }

        try {
            $vendor = User::findOrFail($request->vendor_id);
            
            // Create fund request
            $fundRequest = FundRequest::create([
                'member_id' => $member->id,
                'vendor_id' => $request->vendor_id,
                'amount' => $request->amount,
                'request_type' => $request->request_type,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
                'status' => FundRequest::STATUS_PENDING,
                'expires_at' => now()->addDays(7), // Request expires in 7 days
                'priority' => $this->calculatePriority($request->request_type, $request->amount)
            ]);

            return response()->json([
                'success' => true,
                'message' => "Fund request sent to {$vendor->name} successfully!",
                'request_id' => $fundRequest->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fund request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fund request history via AJAX
     */
    public function getRequestHistory(Request $request): JsonResponse
    {
        $member = Auth::user();
        $page = $request->get('page', 1);
        $status = $request->get('status', 'all');
        
        $query = FundRequest::where('member_id', $member->id)
            ->with(['vendor']);
            
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page', $page);

        $data = $requests->getCollection()->map(function($request) {
            return [
                'id' => $request->id,
                'vendor_name' => $request->vendor->name ?? 'Unknown Vendor',
                'vendor_shop' => $request->vendor->shop_name ?? '',
                'amount' => number_format($request->amount, 2),
                'approved_amount' => $request->approved_amount ? number_format($request->approved_amount, 2) : null,
                'request_type' => ucfirst($request->request_type),
                'purpose' => $request->purpose,
                'status' => $request->status,
                'status_display' => $this->getStatusDisplay($request->status),
                'status_badge' => $this->getStatusBadge($request->status),
                'created_at' => $request->created_at->format('M d, Y H:i'),
                'processed_at' => $request->processed_at ? $request->processed_at->format('M d, Y H:i') : null,
                'expires_at' => $request->expires_at ? $request->expires_at->format('M d, Y') : null,
                'can_cancel' => $request->status === 'pending' && $request->created_at->diffInHours(now()) <= 24,
                'notes' => $request->notes,
                'rejection_reason' => $request->rejection_reason
            ];
        });

        return response()->json([
            'success' => true,
            'requests' => $data,
            'pagination' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'total' => $requests->total()
            ]
        ]);
    }

    /**
     * Cancel fund request via AJAX
     */
    public function cancelRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|exists:fund_requests,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ], 422);
        }

        $member = Auth::user();
        
        try {
            $fundRequest = FundRequest::where('member_id', $member->id)
                ->where('id', $request->request_id)
                ->firstOrFail();
            
            if ($fundRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request cannot be cancelled'
                ], 400);
            }

            // Check if request is too old to cancel
            if ($fundRequest->created_at->diffInHours(now()) > 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request is too old to cancel'
                ], 400);
            }

            $fundRequest->update([
                'status' => FundRequest::STATUS_CANCELLED,
                'processed_at' => now(),
                'notes' => $fundRequest->notes . "\nCancelled by member"
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fund request cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cancellation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available vendors via AJAX
     */
    public function getAvailableVendors(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        
        $vendors = User::whereHas('roles', function($q) {
                $q->where('name', 'vendor');
            })
            ->where('is_active', true)
            ->where('deposit_wallet', '>', 50) // Minimum balance requirement
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('shop_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->select('id', 'name', 'email', 'shop_name', 'deposit_wallet')
            ->limit(20)
            ->get()
            ->map(function($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'shop_name' => $vendor->shop_name,
                    'email' => $vendor->email,
                    'balance' => number_format($vendor->deposit_wallet, 2),
                    'display_name' => ($vendor->shop_name ? $vendor->shop_name : $vendor->name) . " ({$vendor->email})"
                ];
            });

        return response()->json([
            'success' => true,
            'vendors' => $vendors
        ]);
    }

    /**
     * Get request statistics
     */
    public function getStatistics(): JsonResponse
    {
        $member = Auth::user();
        
        $stats = [
            'total_requested' => FundRequest::where('member_id', $member->id)->sum('amount'),
            'total_received' => FundRequest::where('member_id', $member->id)
                ->where('status', 'completed')
                ->sum('approved_amount'),
            'pending_requests' => FundRequest::where('member_id', $member->id)
                ->where('status', 'pending')
                ->count(),
            'approved_requests' => FundRequest::where('member_id', $member->id)
                ->where('status', 'completed')
                ->count(),
            'rejected_requests' => FundRequest::where('member_id', $member->id)
                ->where('status', 'rejected')
                ->count(),
            'current_balance' => number_format($member->deposit_wallet ?? 0, 2)
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Calculate request priority based on type and amount
     */
    private function calculatePriority($type, $amount): string
    {
        if ($type === 'emergency') {
            return 'high';
        }
        
        if ($amount >= 1000) {
            return 'high';
        }
        
        if ($amount >= 500) {
            return 'medium';
        }
        
        return 'low';
    }

    /**
     * Get status display text
     */
    private function getStatusDisplay($status): string
    {
        return match($status) {
            'pending' => 'Awaiting Approval',
            'approved' => 'Approved',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
            default => ucfirst($status)
        };
    }

    /**
     * Get status badge class
     */
    private function getStatusBadge($status): string
    {
        return match($status) {
            'completed' => 'success',
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            'expired' => 'dark',
            default => 'secondary'
        };
    }
}
