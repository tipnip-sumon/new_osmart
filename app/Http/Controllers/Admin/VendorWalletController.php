<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorTransfer;
use App\Models\FundRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VendorWalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display vendor wallet management dashboard
     */
    public function index()
    {
        $vendors = User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })
            ->withCount(['sentTransfers', 'receivedTransfers'])
            ->paginate(20);

        $totalVendorBalance = User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->sum('wallet_balance');
        
        $pendingTransfers = VendorTransfer::where('status', 'pending')->count();
        $pendingFundRequests = FundRequest::where('status', 'pending')->count();

        return view('admin.vendor-wallet.index', compact(
            'vendors', 
            'totalVendorBalance', 
            'pendingTransfers', 
            'pendingFundRequests'
        ));
    }

    /**
     * Add balance to vendor via AJAX
     */
    public function addBalance(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1|max:100000',
            'type' => 'required|in:deposit,bonus,discount,refund',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $vendor = User::findOrFail($request->vendor_id);
            $amount = $request->amount;
            $type = $request->type;
            $notes = $request->notes ?? '';

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $vendor->id,
                'type' => 'credit',
                'category' => 'admin_transfer',
                'amount' => $amount,
                'description' => "Admin {$type}: {$notes}",
                'status' => 'completed',
                'reference_type' => 'admin_deposit',
                'reference_id' => Auth::id(),
                'metadata' => json_encode([
                    'admin_id' => Auth::id(),
                    'admin_name' => Auth::user()->name,
                    'transfer_type' => $type,
                    'notes' => $notes
                ])
            ]);

            // Update vendor balance
            $vendor->increment('wallet_balance', $amount);

            Log::info("Admin added {$amount} balance to vendor", [
                'admin_id' => Auth::id(),
                'vendor_id' => $vendor->id,
                'amount' => $amount,
                'type' => $type
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully added ৳{$amount} to vendor wallet",
                'new_balance' => number_format($vendor->fresh()->wallet_balance, 2),
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor transaction history via AJAX
     */
    public function getTransactionHistory(Request $request): JsonResponse
    {
        $vendorId = $request->get('vendor_id');
        $limit = $request->get('limit', 20);
        
        $transactions = Transaction::where('user_id', $vendorId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'category' => $transaction->category,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'status' => $transaction->status,
                    'created_at' => $transaction->created_at->format('M d, Y H:i'),
                    'badge_class' => $transaction->type === 'credit' ? 'success' : 'danger'
                ];
            });

        return response()->json([
            'success' => true,
            'transactions' => $transactions
        ]);
    }

    /**
     * Process fund request via AJAX
     */
    public function processFundRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|exists:fund_requests,id',
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $fundRequest = FundRequest::findOrFail($request->request_id);
            
            if ($fundRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            if ($request->action === 'approve') {
                $fundRequest->update([
                    'status' => 'approved',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                    'admin_notes' => $request->admin_notes
                ]);
                $message = 'Fund request approved successfully';
                
            } else {
                $fundRequest->update([
                    'status' => 'rejected',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                    'admin_notes' => $request->admin_notes
                ]);
                $message = 'Fund request rejected successfully';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $fundRequest->status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor statistics via AJAX
     */
    public function getVendorStats(Request $request): JsonResponse
    {
        $stats = [
            'total_vendors' => User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->count(),
            'active_vendors' => User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->where('is_active', true)->count(),
            'total_balance' => User::whereHas('roles', function($query) {
                $query->where('name', 'vendor');
            })->sum('wallet_balance'),
            'pending_transfers' => VendorTransfer::where('status', 'pending')->count(),
            'completed_transfers_today' => VendorTransfer::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'pending_fund_requests' => FundRequest::where('status', 'pending')->count()
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
