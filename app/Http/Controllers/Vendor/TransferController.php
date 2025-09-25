<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorTransfer;
use App\Models\FundRequest;
use App\Models\Transaction;
use App\Models\MiniVendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    /**
     * Display vendor transfer dashboard
     */
    public function index()
    {
        $vendor = Auth::user();
        
        $recentTransfers = VendorTransfer::where('vendor_id', $vendor->id)
            ->with(['recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $pendingFundRequests = FundRequest::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->with(['member'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_sent' => VendorTransfer::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_transfers' => VendorTransfer::where('vendor_id', $vendor->id)
                ->where('status', 'pending')
                ->count(),
            'fund_requests_count' => $pendingFundRequests->count()
        ];

        return view('vendor.transfers.index', compact(
            'recentTransfers',
            'pendingFundRequests',
            'stats'
        ));
    }

    /**
     * Search members via AJAX
     */
    public function searchMembers(Request $request): JsonResponse
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }

        $members = User::where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%");
            })
            ->whereIn('role', ['affiliate', 'customer']) // Allow transfers to affiliates, customers, and members
            ->where('status', 'active')
            ->limit(10)
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'username' => $member->username ?? '',
                    'role' => ucfirst($member->role),
                    'avatar' => $member->avatar_url ?? '',
                    'deposit_wallet' => number_format($member->deposit_wallet ?? 0, 2)
                ];
            });

        return response()->json([
            'success' => true,
            'members' => $members
        ]);
    }

    /**
     * Transfer balance to member via AJAX
     */
    public function transferToMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1|max:50000',
            'notes' => 'nullable|string|max:500',
            'purpose' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Auth::user();
        $amount = $request->amount;

        // Ensure we have a fresh model instance
        $vendor = User::find($vendor->id);
        
        // Get current balance (should never be null now)
        $currentBalance = (float) $vendor->deposit_wallet;
        
        // Check if recipient is a mini vendor and calculate commission
        $member = User::findOrFail($request->member_id);
        $miniVendor = MiniVendor::where('vendor_id', $vendor->id)
            ->where('affiliate_id', $member->id)
            ->where('status', 'active')
            ->first();
            
        $commissionAmount = 0;
        $totalAmount = $amount;
        
        // If transferring to mini vendor and amount >= 100, add commission
        if ($miniVendor && $amount >= 100) {
            $commissionAmount = $miniVendor->calculateCommission($amount);
            $totalAmount = $miniVendor->calculateTotalWithCommission($amount);
        }
        
        // Check if vendor has sufficient balance for total amount (including commission)
        if ($currentBalance < $totalAmount) {
            $message = "Insufficient balance. Current balance: ৳" . number_format($currentBalance, 2) . 
                      " but you need ৳" . number_format($totalAmount, 2);
            
            if ($commissionAmount > 0) {
                $message .= " (Amount: ৳" . number_format($amount, 2) . 
                           " + Commission: ৳" . number_format($commissionAmount, 2) . ")";
            }
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'current_balance' => number_format($currentBalance, 2),
                'required_amount' => number_format($totalAmount, 2),
                'commission_amount' => number_format($commissionAmount, 2)
            ], 400);
        }

        try {
            DB::beginTransaction();
            
            Log::info('Transfer attempt started', [
                'vendor_id' => $vendor->id,
                'member_id' => $request->member_id,
                'amount' => $amount,
                'commission_amount' => $commissionAmount,
                'total_amount' => $totalAmount,
                'current_balance' => $currentBalance,
                'is_mini_vendor' => $miniVendor ? true : false
            ]);
            
            // Set default transfer type for deposit wallet transfers
            $transferType = 'direct';
            
            // Calculate fee if applicable
            $transferFee = 0;
            //$transferFee = $this->calculateTransferFee($amount, $transferType);
            $netAmount = $amount - $transferFee;

            // Create transfer record
            $transfer = VendorTransfer::create([
                'vendor_id' => $vendor->id,
                'recipient_id' => $member->id,
                'amount' => $amount,
                'fee' => $transferFee,
                'net_amount' => $netAmount,
                'transfer_type' => $transferType,
                'notes' => $request->notes,
                'purpose' => $request->purpose,
                'status' => 'pending'
            ]);
            
            Log::info('VendorTransfer created', ['id' => $transfer->id]);

            // Create vendor debit transaction (for total amount including commission)
            Log::info('Creating vendor transaction');
            $transferDescription = "Transfer to {$member->name}: {$request->purpose}";
            if ($commissionAmount > 0) {
                $transferDescription .= " (includes ৳{$commissionAmount} mini vendor commission)";
            }
            
            $vendorTransaction = Transaction::create([
                'user_id' => $vendor->id,
                'type' => 'debit',
                'category' => 'vendor_transfer',
                'amount' => $totalAmount,
                'commission_amount' => $commissionAmount,
                'description' => $transferDescription,
                'status' => 'completed',
                'reference_type' => 'vendor_transfer',
                'reference_id' => $transfer->id
            ]);
            
            // Create member credit transaction (for the transfer amount)
            Log::info('Creating member transaction');
            $memberTransaction = Transaction::create([
                'user_id' => $member->id,
                'type' => 'credit',
                'category' => 'vendor_received',
                'amount' => $netAmount,
                'description' => "Received from vendor {$vendor->name}: {$request->purpose}",
                'status' => 'completed',
                'reference_type' => 'vendor_transfer',
                'reference_id' => $transfer->id
            ]);
            
            // Create commission transaction if applicable
            if ($commissionAmount > 0 && $miniVendor) {
                Log::info('Creating commission transaction');
                $commissionTransaction = Transaction::create([
                    'user_id' => $vendor->id,
                    'type' => 'debit',
                    'category' => 'mini_vendor_commission',
                    'amount' => $commissionAmount,
                    'description' => "Mini vendor commission for transfer to {$member->name} (Rate: {$miniVendor->commission_rate}%)",
                    'status' => 'completed',
                    'reference_type' => 'mini_vendor',
                    'reference_id' => $miniVendor->id
                ]);
                
                // Update mini vendor's total earned commission
                $miniVendor->increment('total_earned_commission', $commissionAmount);
                
                Log::info('Mini vendor commission processed', [
                    'mini_vendor_id' => $miniVendor->id,
                    'commission_amount' => $commissionAmount,
                    'new_total' => $miniVendor->refresh()->total_earned_commission
                ]);
            }

            // Update balances - deduct total amount from vendor, credit net amount to member
            $vendor->decrement('deposit_wallet', $totalAmount);
            $member->increment('deposit_wallet', $netAmount);

            // Update transfer status
            $transfer->update([
                'status' => 'completed',
                'processed_at' => now(),
                'transaction_id' => $vendorTransaction->id
            ]);

            DB::commit();

            $responseMessage = "Successfully transferred ৳{$netAmount} to {$member->name}";
            if ($commissionAmount > 0) {
                $responseMessage .= " (৳{$commissionAmount} mini vendor commission added)";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'transfer_id' => $transfer->id,
                'transfer_amount' => number_format($amount, 2),
                'commission_amount' => number_format($commissionAmount, 2),
                'total_deducted' => number_format($totalAmount, 2),
                'new_balance' => number_format((float) $vendor->refresh()->deposit_wallet, 2),
                'is_mini_vendor_transfer' => $miniVendor ? true : false
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
     * Quick retransfer to previous member with password verification
     */
    public function retransferToMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1|max:50000',
            'password' => 'required|string',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $vendor->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password. Please try again.'
            ], 401);
        }

        $member = User::find($request->recipient_id);
        $amount = $request->amount;
        
        // For vendor transfers, fees are disabled (set to 0)
        $fee = 0;
        $netAmount = $amount;

        // Check vendor balance
        if ($vendor->deposit_wallet < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance. Your current balance is ৳' . number_format($vendor->deposit_wallet, 2)
            ], 400);
        }

        // Verify member exists and is not the vendor
        if (!$member || $member->id === $vendor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid recipient'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create vendor transfer record
            $transfer = VendorTransfer::create([
                'vendor_id' => $vendor->id,
                'recipient_id' => $member->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'transfer_type' => 'direct',
                'wallet_type' => 'deposit_wallet',
                'recipient_wallet' => 'deposit_wallet',
                'purpose' => $request->purpose ?: 'Quick retransfer',
                'notes' => $request->notes,
                'status' => 'completed',
                'processed_at' => now(),
                'completed_at' => now(),
                'transfer_reference' => 'RT' . date('YmdHis') . rand(1000, 9999)
            ]);

            // Create vendor transaction
            $vendorTransaction = Transaction::create([
                'user_id' => $vendor->id,
                'type' => 'debit',
                'category' => 'vendor_sent',
                'amount' => $amount,
                'fee' => $fee,
                'description' => "Retransfer to {$member->name}: " . ($request->purpose ?: 'Quick retransfer'),
                'status' => 'completed',
                'reference_type' => 'vendor_transfer',
                'reference_id' => $transfer->id
            ]);
            
            // Create member transaction
            $memberTransaction = Transaction::create([
                'user_id' => $member->id,
                'type' => 'credit',
                'category' => 'vendor_received',
                'amount' => $netAmount,
                'description' => "Retransfer from vendor {$vendor->name}: " . ($request->purpose ?: 'Quick retransfer'),
                'status' => 'completed',
                'reference_type' => 'vendor_transfer',
                'reference_id' => $transfer->id
            ]);

            // Update balances using direct database updates
            User::where('id', $vendor->id)->decrement('deposit_wallet', $amount);
            User::where('id', $member->id)->increment('deposit_wallet', $netAmount);

            DB::commit();

            Log::info("Retransfer completed: Vendor {$vendor->id} sent ৳{$netAmount} to Member {$member->id}");

            // Get updated vendor balance
            $updatedVendor = User::find($vendor->id);

            return response()->json([
                'success' => true,
                'message' => "Successfully retransferred ৳{$netAmount} to {$member->name}",
                'transfer_id' => $transfer->id,
                'new_balance' => number_format((float) $updatedVendor->deposit_wallet, 2)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("Retransfer failed: " . $e->getMessage(), [
                'vendor_id' => $vendor->id,
                'recipient_id' => $request->recipient_id,
                'amount' => $amount
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Retransfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show transfer history page
     */
    public function transferHistory(Request $request)
    {
        $vendor = Auth::user();
        
        // Get some initial data for the page
        $totalTransfers = VendorTransfer::where('vendor_id', $vendor->id)->count();
        $totalAmount = VendorTransfer::where('vendor_id', $vendor->id)->sum('amount');
        $completedTransfers = VendorTransfer::where('vendor_id', $vendor->id)->where('status', 'completed')->count();
        
        $stats = [
            'total_transfers' => $totalTransfers,
            'total_amount' => $totalAmount,
            'completed_transfers' => $completedTransfers,
            'pending_transfers' => VendorTransfer::where('vendor_id', $vendor->id)->where('status', 'pending')->count()
        ];
        
        return view('vendor.transfers.history', compact('stats'));
    }

    /**
     * Get transfer history data via AJAX
     */
    public function getTransferHistoryData(Request $request)
    {
        $vendor = Auth::user();
        
        // Handle CSV export
        if ($request->get('export') === 'csv') {
            return $this->exportTransfersCSV($request);
        }
        
        // Build query
        $query = VendorTransfer::where('vendor_id', $vendor->id)
            ->with(['recipient']);
        
        // Apply filters
        if ($request->filled('search_value')) {
            $searchValue = $request->get('search_value');
            $query->where(function($q) use ($searchValue) {
                $q->whereHas('recipient', function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('phone', 'like', "%{$searchValue}%");
                })
                ->orWhere('transfer_reference', 'like', "%{$searchValue}%")
                ->orWhere('purpose', 'like', "%{$searchValue}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        // Get total records
        $totalRecords = VendorTransfer::where('vendor_id', $vendor->id)->count();
        $filteredRecords = $query->count();
        
        // Apply ordering
        $orderColumn = $request->get('order.0.column', 0);
        $orderDirection = $request->get('order.0.dir', 'desc');
        
        $columns = ['created_at', 'recipient.name', 'amount', 'status', 'transfer_reference', 'purpose'];
        
        if (isset($columns[$orderColumn])) {
            if ($columns[$orderColumn] === 'recipient.name') {
                $query->join('users', 'vendor_transfers.recipient_id', '=', 'users.id')
                      ->orderBy('users.name', $orderDirection)
                      ->select('vendor_transfers.*');
            } else {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Apply pagination
        $start = $request->get('start', 0);
        $length = $request->get('length', 25);
        
        $transfers = $query->offset($start)->limit($length)->get();
        
        // Format data for DataTables
        $data = $transfers->map(function($transfer) {
            return [
                'created_at' => $transfer->created_at->toISOString(),
                'to_user' => [
                    'name' => $transfer->recipient->name,
                    'phone' => $transfer->recipient->phone
                ],
                'amount' => $transfer->amount,
                'status' => $transfer->status,
                'reference' => $transfer->transfer_reference,
                'purpose' => $transfer->purpose
            ];
        });
        
        return response()->json([
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Export transfers to CSV
     */
    private function exportTransfersCSV(Request $request)
    {
        $vendor = Auth::user();
        
        $query = VendorTransfer::where('vendor_id', $vendor->id)
            ->with(['recipient']);
        
        // Apply same filters as the main query
        if ($request->filled('search_value')) {
            $searchValue = $request->get('search_value');
            $query->where(function($q) use ($searchValue) {
                $q->whereHas('recipient', function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('phone', 'like', "%{$searchValue}%");
                })
                ->orWhere('transfer_reference', 'like', "%{$searchValue}%")
                ->orWhere('purpose', 'like', "%{$searchValue}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        
        $transfers = $query->orderBy('created_at', 'desc')->get();
        
        $csvData = [];
        $csvData[] = ['Date', 'Recipient', 'Phone', 'Amount', 'Status', 'Reference', 'Purpose'];
        
        foreach ($transfers as $transfer) {
            $csvData[] = [
                $transfer->created_at->format('Y-m-d H:i:s'),
                $transfer->recipient->name,
                $transfer->recipient->phone ?? 'N/A',
                $transfer->amount,
                ucfirst($transfer->status),
                $transfer->transfer_reference ?? '-',
                $transfer->purpose ?? '-'
            ];
        }
        
        $filename = 'vendor_transfers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://output', 'w');
        ob_start();
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        $csvContent = ob_get_clean();
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Process fund request from member via AJAX
     */
    public function processFundRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_id' => 'required|exists:fund_requests,id',
            'action' => 'required|in:approve,reject',
            'amount' => 'nullable|numeric|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $vendor = Auth::user();
        
        // Ensure we have a fresh model instance  
        $vendor = User::find($vendor->id);
        
        try {
            DB::beginTransaction();

            $fundRequest = FundRequest::where('vendor_id', $vendor->id)
                ->where('id', $request->request_id)
                ->firstOrFail();
            
            if ($fundRequest->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed'
                ], 400);
            }

            if ($request->action === 'approve') {
                $amount = $request->amount ?? $fundRequest->amount;
                
                // Get current balance (should never be null now)
                $currentBalance = (float) $vendor->deposit_wallet;
                
                // Check vendor balance
                if ($currentBalance < $amount) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient balance to fulfill this request. Current balance: ৳" . number_format($currentBalance, 2) . " but you need ৳" . number_format($amount, 2),
                        'current_balance' => number_format($currentBalance, 2),
                        'required_amount' => number_format($amount, 2)
                    ], 400);
                }

                // Process the transfer
                $transfer = VendorTransfer::create([
                    'vendor_id' => $vendor->id,
                    'recipient_id' => $fundRequest->member_id,
                    'amount' => $amount,
                    'fee' => 0, // No fee for fund requests
                    'net_amount' => $amount,
                    'transfer_type' => 'fund_request',
                    'notes' => $request->notes,
                    'purpose' => 'Fund Request: ' . $fundRequest->purpose,
                    'status' => 'completed',
                    'processed_at' => now()
                ]);

                // Update balances (deposit_wallet should never be null now)
                $member = User::find($fundRequest->member_id);
                $vendor->decrement('deposit_wallet', $amount);
                $member->increment('deposit_wallet', $amount);

                // Update fund request
                $fundRequest->update([
                    'status' => 'completed',
                    'approved_amount' => $amount,
                    'processed_at' => now(),
                    'notes' => $request->notes
                ]);

                $message = "Fund request approved and ৳{$amount} transferred";
                
            } else {
                $fundRequest->update([
                    'status' => 'rejected',
                    'processed_at' => now(),
                    'rejection_reason' => $request->notes
                ]);
                
                $message = 'Fund request rejected';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_status' => $fundRequest->status,
                'new_balance' => number_format((float) $vendor->refresh()->deposit_wallet, 2)
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
     * Get pending fund requests via AJAX
     */
    public function getPendingFundRequests(Request $request): JsonResponse
    {
        $vendor = Auth::user();
        
        $requests = FundRequest::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->with(['member'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'member_name' => $request->member->name,
                    'member_email' => $request->member->email,
                    'amount' => number_format($request->amount, 2),
                    'purpose' => $request->purpose,
                    'request_message' => $request->notes,
                    'created_at' => $request->created_at->format('M d, Y H:i'),
                    'days_pending' => $request->created_at->diffInDays(now())
                ];
            });

        return response()->json([
            'success' => true,
            'requests' => $requests
        ]);
    }

    /**
     * Display fund requests page
     */
    public function fundRequestsPage()
    {
        $vendor = Auth::user();
        
        $pendingFundRequests = FundRequest::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->with(['member'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $recentlyProcessed = FundRequest::where('vendor_id', $vendor->id)
            ->whereIn('status', ['completed', 'rejected'])
            ->with(['member'])
            ->orderBy('processed_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics
        $stats = [
            'processed_today' => FundRequest::where('vendor_id', $vendor->id)
                ->whereDate('processed_at', today())
                ->count()
        ];

        return view('vendor.transfers.fund-requests', compact(
            'pendingFundRequests',
            'recentlyProcessed',
            'stats'
        ));
    }

    /**
     * Calculate transfer fee based on amount and type
     */
    private function calculateTransferFee($amount, $type)
    {
        $feeRates = config('wallet.transfer_fees', [
            'direct' => 0.02, // 2%
            'bonus' => 0,     // No fee
            'commission' => 0, // No fee
            'refund' => 0     // No fee
        ]);

        $rate = $feeRates[$type] ?? 0.01;
        $fee = $amount * $rate;
        
        // Apply minimum and maximum fee limits
        $minFee = config('wallet.min_transfer_fee', 5);
        $maxFee = config('wallet.max_transfer_fee', 100);
        
        return max($minFee, min($fee, $maxFee));
    }

    /**
     * Get status badge class for transfer status
     */
    private function getStatusBadge($status)
    {
        return match($status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            default => 'info'
        };
    }
}
