<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class FinanceController extends Controller
{
    /**
     * Display finance dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_deposits' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'approved')->sum('net_amount'),
            'pending_deposits' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'pending')->sum('net_amount'),
            'approved_today' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'approved')->whereDate('processed_at', today())->sum('net_amount'),
            'total_requests' => PaymentTransaction::where('type', 'fund_addition')->count(),
        ];

        return view('admin.finance.dashboard', compact('stats'));
    }

    /**
     * Display deposits management page
     */
    public function deposits(Request $request)
    {
        $query = PaymentTransaction::with('user')
            ->where('type', 'fund_addition')
            ->latest();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $deposits = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total_deposits' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'approved')->sum('net_amount'),
            'pending_deposits' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'pending')->sum('net_amount'),
            'approved_today' => PaymentTransaction::where('type', 'fund_addition')->where('status', 'approved')->whereDate('processed_at', today())->sum('net_amount'),
            'total_requests' => PaymentTransaction::where('type', 'fund_addition')->count(),
        ];

        return view('admin.finance.deposits', compact('deposits', 'stats'));
    }

    /**
     * Show deposit details
     */
    public function showDeposit($id)
    {
        $deposit = PaymentTransaction::with('user')
            ->where('type', 'fund_addition')
            ->findOrFail($id);

        $html = view('admin.finance.partials.deposit-details', compact('deposit'))->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    /**
     * Approve a deposit
     */
    public function approveDeposit($id)
    {
        try {
            DB::beginTransaction();

            $deposit = PaymentTransaction::where('type', 'fund_addition')
                ->where('status', 'pending')
                ->findOrFail($id);

            // Get current admin user ID (handle both admin guard and default guard)
            $adminId = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : (Auth::check() ? Auth::user()->id : null);

            // Prepare update data
            $updateData = [
                'status' => 'approved',
                'processed_at' => now()
            ];
            
            // Only add processed_by if we have a valid admin ID
            if ($adminId) {
                $updateData['processed_by'] = $adminId;
            }

            // Update deposit status
            $deposit->update($updateData);

            // Credit user's deposit wallet
            $user = User::findOrFail($deposit->user_id);
            $user->increment('deposit_wallet', $deposit->net_amount);
            
            // Create a transaction record for the user using the Transaction model (if the relationship works)
            try {
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->transaction_id = 'DEP_' . $deposit->id . '_' . time();
                $transaction->type = 'deposit';
                $transaction->amount = $deposit->net_amount;
                $transaction->fee = 0;
                $transaction->status = 'completed';
                $transaction->payment_method = $deposit->payment_method;
                $transaction->description = 'Fund deposit approved - ' . ucfirst(str_replace('_', ' ', $deposit->payment_method));
                $transaction->reference_type = 'payment_transaction';
                $transaction->reference_id = $deposit->id;
                if ($adminId) {
                    $transaction->processed_by = $adminId;
                }
                $transaction->processed_at = now();
                $transaction->save();
            } catch (\Exception $transactionError) {
                // Log transaction creation error but don't fail the whole operation
                Log::warning('Transaction record creation failed during deposit approval: ' . $transactionError->getMessage(), [
                    'deposit_id' => $id,
                    'user_id' => $user->id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit approved successfully! ৳' . number_format($deposit->net_amount, 2) . ' has been credited to user\'s wallet.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Deposit approval failed: ' . $e->getMessage(), [
                'deposit_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a deposit
     */
    public function rejectDeposit(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $deposit = PaymentTransaction::where('type', 'fund_addition')
                ->where('status', 'pending')
                ->findOrFail($id);

            $adminId = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : (Auth::check() ? Auth::user()->id : null);

            // Prepare update data
            $updateData = [
                'status' => 'rejected',
                'processed_at' => now(),
                'rejection_reason' => $request->reason
            ];
            
            // Only add processed_by if we have a valid admin ID
            if ($adminId) {
                $updateData['processed_by'] = $adminId;
            }

            $deposit->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Deposit rejected successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Deposit rejection failed: ' . $e->getMessage(), [
                'deposit_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve deposits
     */
    public function bulkApproveDeposits(Request $request)
    {
        $request->validate([
            'deposit_ids' => 'required|array',
            'deposit_ids.*' => 'exists:payment_transactions,id'
        ]);

        try {
            DB::beginTransaction();

            $deposits = PaymentTransaction::whereIn('id', $request->deposit_ids)
                ->where('type', 'fund_addition')
                ->where('status', 'pending')
                ->get();

            $approvedCount = 0;
            $totalAmount = 0;
            $adminId = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : (Auth::check() ? Auth::user()->id : null);

            foreach ($deposits as $deposit) {
                // Prepare update data
                $updateData = [
                    'status' => 'approved',
                    'processed_at' => now()
                ];
                
                // Only add processed_by if we have a valid admin ID
                if ($adminId) {
                    $updateData['processed_by'] = $adminId;
                }

                // Update deposit status
                $deposit->update($updateData);

                // Credit user's deposit wallet
                $user = User::findOrFail($deposit->user_id);
                $user->increment('deposit_wallet', $deposit->net_amount);
                
                // Create a transaction record for the user using the Transaction model
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->transaction_id = 'DEP_' . $deposit->id . '_' . time();
                $transaction->type = 'deposit';
                $transaction->amount = $deposit->net_amount;
                $transaction->fee = 0;
                $transaction->status = 'completed';
                $transaction->payment_method = $deposit->payment_method;
                $transaction->description = 'Fund deposit approved - ' . ucfirst(str_replace('_', ' ', $deposit->payment_method));
                $transaction->reference_type = 'payment_transaction';
                $transaction->reference_id = $deposit->id;
                $transaction->processed_by = $adminId;
                $transaction->processed_at = now();
                $transaction->save();

                $approvedCount++;
                $totalAmount += $deposit->net_amount;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$approvedCount} deposits approved successfully! Total ৳" . number_format($totalAmount, 2) . " credited to users' wallets."
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Bulk deposit approval failed: ' . $e->getMessage(), [
                'deposit_ids' => $request->deposit_ids,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve deposits: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk reject deposits
     */
    public function bulkRejectDeposits(Request $request)
    {
        $request->validate([
            'deposit_ids' => 'required|array',
            'deposit_ids.*' => 'exists:payment_transactions,id',
            'reason' => 'required|string|max:500'
        ]);

        try {
            $deposits = PaymentTransaction::whereIn('id', $request->deposit_ids)
                ->where('type', 'fund_addition')
                ->where('status', 'pending')
                ->get();

            $rejectedCount = 0;
            $adminId = Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : (Auth::check() ? Auth::user()->id : null);

            foreach ($deposits as $deposit) {
                // Prepare update data
                $updateData = [
                    'status' => 'rejected',
                    'processed_at' => now(),
                    'rejection_reason' => $request->reason
                ];
                
                // Only add processed_by if we have a valid admin ID
                if ($adminId) {
                    $updateData['processed_by'] = $adminId;
                }

                $deposit->update($updateData);
                $rejectedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "{$rejectedCount} deposits rejected successfully."
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk deposit rejection failed: ' . $e->getMessage(), [
                'deposit_ids' => $request->deposit_ids,
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject deposits: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display transactions page
     */
    public function transactions()
    {
        $transactions = PaymentTransaction::with('user', 'order')
            ->latest()
            ->paginate(20);

        return view('admin.finance.transactions', compact('transactions'));
    }

    /**
     * Display withdrawals page
     */
    public function withdrawals()
    {
        $withdrawals = PaymentTransaction::with('user')
            ->where('type', 'withdrawal')
            ->latest()
            ->paginate(20);

        // Calculate statistics
        $stats = [
            'pending_count' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'pending')->count(),
            'pending_amount' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'pending')->sum('net_amount'),
            'approved_count' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'approved')->count(),
            'approved_amount' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'approved')->sum('net_amount'),
            'completed_count' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'completed')->count(),
            'completed_amount' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'completed')->sum('net_amount'),
            'rejected_count' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'rejected')->count(),
            'rejected_amount' => PaymentTransaction::where('type', 'withdrawal')->where('status', 'rejected')->sum('net_amount'),
        ];

        return view('admin.finance.withdrawals', compact('withdrawals', 'stats'));
    }

    /**
     * Display wallets overview
     */
    public function wallets()
    {
        $walletStats = [
            'total_balance' => User::sum('balance'),
            'total_deposit_wallet' => User::sum('deposit_wallet'),
            'total_users' => User::count(),
            'active_wallets' => User::where('balance', '>', 0)->orWhere('deposit_wallet', '>', 0)->count()
        ];

        $topWallets = User::select('id', 'name', 'email', 'balance', 'deposit_wallet')
            ->orderByRaw('(balance + deposit_wallet) DESC')
            ->take(20)
            ->get();

        return view('admin.finance.wallets', compact('walletStats', 'topWallets'));
    }

    /**
     * Show admin balance transfer form
     */
    public function showTransferForm()
    {
        return view('admin.finance.transfer');
    }

    /**
     * Search users for balance transfer
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('query');
        
        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->select('id', 'name', 'username', 'email', 'phone', 'deposit_wallet', 'balance', 'role')
            ->limit(10)
            ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Transfer balance to user deposit wallet
     */
    public function transferBalance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1|max:1000000',
            'commission_rate' => 'nullable|numeric|min:0|max:50',
            'note' => 'nullable|string|max:500',
            'admin_password' => 'required|string'
        ]);

        // Verify admin password
        if (!Auth::guard('admin')->check() || !password_verify($request->admin_password, Auth::guard('admin')->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid admin password. Please verify your identity.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->user_id);
            $baseAmount = (float) $request->amount;
            $commissionRate = (float) ($request->commission_rate ?? 0);
            $commissionAmount = ($baseAmount * $commissionRate) / 100;
            $totalAmount = $baseAmount + $commissionAmount;
            
            $note = $request->note ?? 'Admin balance transfer';
            $admin = Auth::guard('admin')->user();

            // For vendors, check if commission should be applied
            $isVendor = $user->role === 'vendor';
            $finalTransferAmount = $isVendor ? $totalAmount : $baseAmount;

            // Add balance to user's deposit wallet
            $oldBalance = $user->deposit_wallet;
            $user->increment('deposit_wallet', $finalTransferAmount);
            $user->refresh();

            // Create transaction record for user
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->transaction_id = 'ADT' . time() . rand(1000, 9999);
            $transaction->type = 'credit';
            $transaction->amount = $finalTransferAmount;
            $transaction->wallet_type = 'deposit_wallet';
            $transaction->reference_type = 'admin_transfer';
            $transaction->reference_id = $admin->id;
            
            // Add commission fields for proper tracking
            if ($isVendor && $commissionAmount > 0) {
                $transaction->base_amount = $baseAmount;
                $transaction->commission_rate = $commissionRate;
                $transaction->commission_amount = $commissionAmount;
                $transaction->note = $note . " (Base: ৳{$baseAmount}, Commission: {$commissionRate}% = ৳{$commissionAmount})";
                $transaction->description = "Admin balance transfer with commission by {$admin->name} - Base: ৳{$baseAmount} + Commission: ৳{$commissionAmount} = Total: ৳{$finalTransferAmount}";
            } else {
                $transaction->base_amount = $finalTransferAmount;
                $transaction->commission_rate = 0;
                $transaction->commission_amount = 0;
                $transaction->note = $note;
                $transaction->description = "Admin balance transfer by {$admin->name} - {$note}";
            }
            
            $transaction->status = 'completed';
            $transaction->save();

            // Log the transfer
            Log::info("Admin balance transfer completed", [
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'base_amount' => $baseAmount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'total_amount' => $finalTransferAmount,
                'old_balance' => $oldBalance,
                'new_balance' => $user->deposit_wallet,
                'note' => $note,
                'transaction_id' => $transaction->transaction_id
            ]);

            DB::commit();

            $responseMessage = "Successfully transferred ৳{$finalTransferAmount} to {$user->name}";
            if ($isVendor && $commissionAmount > 0) {
                $responseMessage .= " (Base: ৳{$baseAmount} + Commission: ৳{$commissionAmount})";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'data' => [
                    'user_name' => $user->name,
                    'base_amount' => $baseAmount,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'total_amount' => $finalTransferAmount,
                    'new_balance' => $user->deposit_wallet,
                    'transaction_id' => $transaction->transaction_id
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error("Admin balance transfer failed", [
                'admin_id' => Auth::guard('admin')->id(),
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'commission_rate' => $request->commission_rate,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Transfer failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admin transfer history
     */
    public function transferHistory(Request $request)
    {
        $query = Transaction::with('user:id,name,email,role')
            ->where('reference_type', 'admin_transfer')
            ->where('type', 'credit')
            ->latest();

        // Apply filters
        if ($request->filled('user_search')) {
            $userSearch = $request->user_search;
            $query->whereHas('user', function($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                  ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $transfers = $query->paginate(20);

        // Calculate stats
        $stats = [
            'total_transfers' => Transaction::where('reference_type', 'admin_transfer')->where('type', 'credit')->count(),
            'total_amount' => Transaction::where('reference_type', 'admin_transfer')->where('type', 'credit')->sum('amount'),
            'today_transfers' => Transaction::where('reference_type', 'admin_transfer')->where('type', 'credit')->whereDate('created_at', today())->count(),
            'today_amount' => Transaction::where('reference_type', 'admin_transfer')->where('type', 'credit')->whereDate('created_at', today())->sum('amount')
        ];

        return view('admin.finance.transfer-history', compact('transfers', 'stats'));
    }

    /**
     * Get transfer details
     */
    public function getTransferDetails($id)
    {
        try {
            $transfer = Transaction::with(['user:id,name,email,phone,created_at'])
                ->where('id', $id)
                ->where('reference_type', 'admin_transfer')
                ->where('type', 'credit')
                ->firstOrFail();

            // Get admin who made the transfer
            $adminId = $transfer->reference_id;
            $admin = \App\Models\Admin::find($adminId);

            return response()->json([
                'success' => true,
                'transfer' => [
                    'id' => $transfer->id,
                    'transaction_id' => $transfer->transaction_id,
                    'amount' => $transfer->amount,
                    'wallet_type' => $transfer->wallet_type,
                    'note' => $transfer->note,
                    'description' => $transfer->description,
                    'status' => $transfer->status,
                    'created_at' => $transfer->created_at->format('M d, Y h:i A'),
                    'user' => [
                        'id' => $transfer->user->id,
                        'name' => $transfer->user->name,
                        'email' => $transfer->user->email,
                        'phone' => $transfer->user->phone ?? 'N/A',
                        'member_since' => $transfer->user->created_at->format('M d, Y'),
                    ],
                    'admin' => [
                        'name' => $admin->name ?? 'Unknown Admin',
                        'email' => $admin->email ?? 'N/A',
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer details not found.'
            ], 404);
        }
    }

    /**
     * Export transfer history to CSV
     */
    public function exportTransfers(Request $request)
    {
        $query = Transaction::with(['user:id,name,email,phone'])
            ->where('reference_type', 'admin_transfer')
            ->where('type', 'credit')
            ->latest();

        // Apply same filters as history view
        if ($request->filled('user_search')) {
            $userSearch = $request->user_search;
            $query->whereHas('user', function($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                  ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $transfers = $query->get();

        // Create CSV content
        $csvData = [];
        $csvData[] = [
            'Transaction ID',
            'User Name',
            'User Email',
            'User Phone',
            'User Role',
            'Amount (৳)',
            'Commission Rate (%)',
            'Commission Amount (৳)', 
            'Wallet Type',
            'Note',
            'Status',
            'Transfer Date',
            'Transfer Time'
        ];

        foreach ($transfers as $transfer) {
            $csvData[] = [
                $transfer->transaction_id,
                $transfer->user->name ?? 'Unknown User',
                $transfer->user->email ?? 'N/A',
                $transfer->user->phone ?? 'N/A',
                ucfirst($transfer->user->role ?? 'User'),
                number_format($transfer->amount, 2),
                $transfer->commission_rate ? number_format($transfer->commission_rate, 2) : '0.00',
                $transfer->commission_amount ? number_format($transfer->commission_amount, 2) : '0.00',
                ucfirst(str_replace('_', ' ', $transfer->wallet_type)),
                $transfer->note ?? 'No note',
                ucfirst($transfer->status),
                $transfer->created_at->format('M d, Y'),
                $transfer->created_at->format('h:i A')
            ];
        }

        // Generate filename
        $filename = 'admin-transfers-' . date('Y-m-d-H-i-s') . '.csv';

        // Create CSV response
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset-UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Approve a withdrawal request
     */
    public function approveWithdrawal($id)
    {
        try {
            DB::beginTransaction();

            $withdrawal = PaymentTransaction::with('user')
                ->where('id', $id)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->first();

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal request not found or already processed.'
                ], 404);
            }

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'notes' => 'Approved by admin: ' . Auth::user()->name
            ]);

            // Update the corresponding transaction record - use multiple methods to ensure synchronization
            $originalTransactionUpdated = false;
            
            // Method 1: Try using metadata original_transaction_id if available
            if ($withdrawal->metadata && isset($withdrawal->metadata['original_transaction_id'])) {
                $originalTransactionId = $withdrawal->metadata['original_transaction_id'];
                $updateCount = Transaction::where('id', $originalTransactionId)
                    ->update([
                        'status' => 'approved',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 2: If metadata method failed, try to find by matching criteria
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('user_id', $withdrawal->user_id)
                    ->where('type', 'withdrawal')
                    ->where('amount', $withdrawal->amount)
                    ->where('status', 'pending')
                    ->where('payment_method', $withdrawal->payment_method)
                    ->update([
                        'status' => 'approved',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 3: If still not found, try to find by transaction reference relationship
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('reference_type', PaymentTransaction::class)
                    ->where('reference_id', $withdrawal->id)
                    ->where('type', 'withdrawal')
                    ->update([
                        'status' => 'approved',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }

            // Log approval activity
            Log::info('Withdrawal approved', [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'amount' => $withdrawal->net_amount,
                'approved_by' => Auth::id(),
                'transaction_updated' => $originalTransactionUpdated,
                'both_tables_synchronized' => $originalTransactionUpdated
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal approved successfully! ৳' . number_format($withdrawal->net_amount, 2) . ' withdrawal request has been approved.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve withdrawal: ' . $e->getMessage(), [
                'withdrawal_id' => $id,
                'approved_by' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a withdrawal request
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = PaymentTransaction::with('user')
                ->where('id', $id)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->first();

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal request not found or already processed.'
                ], 404);
            }

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'failed_at' => now(),
                'notes' => 'Rejected by admin: ' . Auth::user()->name . ' - Reason: ' . $request->rejection_reason
            ]);

            // Update the corresponding transaction record - use multiple methods to ensure synchronization
            $originalTransactionUpdated = false;
            
            // Method 1: Try using metadata original_transaction_id if available
            if ($withdrawal->metadata && isset($withdrawal->metadata['original_transaction_id'])) {
                $originalTransactionId = $withdrawal->metadata['original_transaction_id'];
                $updateCount = Transaction::where('id', $originalTransactionId)
                    ->update([
                        'status' => 'rejected',
                        'rejection_reason' => $request->rejection_reason,
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 2: If metadata method failed, try to find by matching criteria
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('user_id', $withdrawal->user_id)
                    ->where('type', 'withdrawal')
                    ->where('amount', $withdrawal->amount)
                    ->where('status', 'pending')
                    ->where('payment_method', $withdrawal->payment_method)
                    ->update([
                        'status' => 'rejected',
                        'rejection_reason' => $request->rejection_reason,
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 3: If still not found, try to find by transaction reference relationship
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('reference_type', PaymentTransaction::class)
                    ->where('reference_id', $withdrawal->id)
                    ->where('type', 'withdrawal')
                    ->update([
                        'status' => 'rejected',
                        'rejection_reason' => $request->rejection_reason,
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }

            // Refund the amount back to user's wallet (since withdrawal was deducted when requested)
            $user = $withdrawal->user;
            if ($user) {
                // Determine which wallet to refund based on withdrawal source
                $walletField = 'deposit_wallet'; // Default to deposit wallet

                // If withdrawal has metadata about source wallet, use that
                if ($withdrawal->metadata && isset($withdrawal->metadata['wallet_type'])) {
                    $sourceWallet = $withdrawal->metadata['wallet_type'];
                    // Map wallet types correctly based on User model structure
                    if ($sourceWallet === 'deposit_wallet') {
                        $walletField = 'deposit_wallet';
                    } elseif ($sourceWallet === 'interest_wallet') {
                        $walletField = 'interest_wallet';
                    } elseif ($sourceWallet === 'balance' || $sourceWallet === 'main') {
                        $walletField = 'balance';
                    } else {
                        $walletField = 'balance'; // fallback to main balance
                    }
                }

                // Refund the amount including fee
                $refundAmount = $withdrawal->amount; // Full amount including fee
                $user->increment($walletField, $refundAmount);

                // Create a transaction record for the refund
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->type = 'credit';
                // Set wallet_type based on the actual wallet field used
                if ($walletField === 'deposit_wallet') {
                    $transaction->wallet_type = 'deposit_wallet';
                } elseif ($walletField === 'interest_wallet') {
                    $transaction->wallet_type = 'interest_wallet';
                } else {
                    $transaction->wallet_type = 'balance';
                }
                $transaction->amount = $refundAmount;
                $transaction->description = 'Withdrawal refund - Request rejected: ' . $request->rejection_reason;
                $transaction->reference_type = PaymentTransaction::class;
                $transaction->reference_id = $withdrawal->id;
                $transaction->status = 'completed';
                
                // Generate a unique transaction ID
                $transaction->transaction_id = 'TXN' . strtoupper(uniqid());
                
                $transaction->save();

                Log::info('Withdrawal rejected and refunded', [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $user->id,
                    'refund_amount' => $refundAmount,
                    'wallet_type' => $walletField,
                    'rejected_by' => Auth::id(),
                    'reason' => $request->rejection_reason,
                    'transaction_updated' => $originalTransactionUpdated,
                    'both_tables_synchronized' => $originalTransactionUpdated
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal rejected successfully! ৳' . number_format($withdrawal->net_amount, 2) . ' has been refunded to user\'s wallet.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject withdrawal: ' . $e->getMessage(), [
                'withdrawal_id' => $id,
                'rejected_by' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark withdrawal as processed/completed
     */
    public function processWithdrawal($id)
    {
        try {
            DB::beginTransaction();

            $withdrawal = PaymentTransaction::with('user')
                ->where('id', $id)
                ->where('type', 'withdrawal')
                ->where('status', 'approved')
                ->first();

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal request not found or not in approved status.'
                ], 404);
            }

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'completed',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'notes' => ($withdrawal->notes ?? '') . ' | Processed by admin: ' . Auth::user()->name
            ]);

            // Update the corresponding transaction record - use multiple methods to ensure synchronization
            $originalTransactionUpdated = false;
            
            // Method 1: Try using metadata original_transaction_id if available
            if ($withdrawal->metadata && isset($withdrawal->metadata['original_transaction_id'])) {
                $originalTransactionId = $withdrawal->metadata['original_transaction_id'];
                $updateCount = Transaction::where('id', $originalTransactionId)
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 2: If metadata method failed, try to find by matching criteria
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('user_id', $withdrawal->user_id)
                    ->where('type', 'withdrawal')
                    ->where('amount', $withdrawal->amount)
                    ->where('status', 'approved')
                    ->where('payment_method', $withdrawal->payment_method)
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }
            
            // Method 3: If still not found, try to find by transaction reference relationship
            if (!$originalTransactionUpdated) {
                $updateCount = Transaction::where('reference_type', PaymentTransaction::class)
                    ->where('reference_id', $withdrawal->id)
                    ->where('type', 'withdrawal')
                    ->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                        'processed_by' => Auth::id()
                    ]);
                if ($updateCount > 0) {
                    $originalTransactionUpdated = true;
                }
            }

            // Log processing activity
            Log::info('Withdrawal marked as processed', [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'amount' => $withdrawal->net_amount,
                'processed_by' => Auth::id(),
                'transaction_updated' => $originalTransactionUpdated,
                'both_tables_synchronized' => $originalTransactionUpdated
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal marked as processed successfully! ৳' . number_format($withdrawal->net_amount, 2) . ' withdrawal has been completed.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process withdrawal: ' . $e->getMessage(), [
                'withdrawal_id' => $id,
                'processed_by' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve withdrawal requests
     */
    public function bulkApproveWithdrawals(Request $request)
    {
        $request->validate([
            'withdrawal_ids' => 'required|array|min:1',
            'withdrawal_ids.*' => 'exists:payment_transactions,id'
        ]);

        try {
            DB::beginTransaction();

            $withdrawals = PaymentTransaction::with('user')
                ->whereIn('id', $request->withdrawal_ids)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->get();

            if ($withdrawals->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid pending withdrawal requests found for approval.'
                ], 404);
            }

            $approvedCount = 0;
            $totalAmount = 0;

            foreach ($withdrawals as $withdrawal) {
                $withdrawal->update([
                    'status' => 'approved',
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                    'notes' => 'Bulk approved by admin: ' . Auth::user()->name
                ]);

                // Update the corresponding transaction record - use multiple methods to ensure synchronization
                $originalTransactionUpdated = false;
                
                // Method 1: Try using metadata original_transaction_id if available
                if ($withdrawal->metadata && isset($withdrawal->metadata['original_transaction_id'])) {
                    $originalTransactionId = $withdrawal->metadata['original_transaction_id'];
                    $updateCount = Transaction::where('id', $originalTransactionId)
                        ->update([
                            'status' => 'approved',
                            'processed_at' => now(),
                            'processed_by' => Auth::id()
                        ]);
                    if ($updateCount > 0) {
                        $originalTransactionUpdated = true;
                    }
                }
                
                // Method 2: If metadata method failed, try to find by matching criteria
                if (!$originalTransactionUpdated) {
                    $updateCount = Transaction::where('user_id', $withdrawal->user_id)
                        ->where('type', 'withdrawal')
                        ->where('amount', $withdrawal->amount)
                        ->where('status', 'pending')
                        ->where('payment_method', $withdrawal->payment_method)
                        ->update([
                            'status' => 'approved',
                            'processed_at' => now(),
                            'processed_by' => Auth::id()
                        ]);
                    if ($updateCount > 0) {
                        $originalTransactionUpdated = true;
                    }
                }
                
                // Method 3: If still not found, try to find by transaction reference relationship
                if (!$originalTransactionUpdated) {
                    $updateCount = Transaction::where('reference_type', PaymentTransaction::class)
                        ->where('reference_id', $withdrawal->id)
                        ->where('type', 'withdrawal')
                        ->update([
                            'status' => 'approved',
                            'processed_at' => now(),
                            'processed_by' => Auth::id()
                        ]);
                    if ($updateCount > 0) {
                        $originalTransactionUpdated = true;
                    }
                }

                $totalAmount += $withdrawal->net_amount;
                $approvedCount++;

                Log::info('Withdrawal bulk approved', [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $withdrawal->user_id,
                    'amount' => $withdrawal->net_amount,
                    'approved_by' => Auth::id(),
                    'transaction_updated' => $originalTransactionUpdated
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$approvedCount} withdrawal requests approved successfully! Total ৳" . number_format($totalAmount, 2) . " withdrawals have been approved."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to bulk approve withdrawals: ' . $e->getMessage(), [
                'withdrawal_ids' => $request->withdrawal_ids,
                'approved_by' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve withdrawals: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get withdrawal details for modal
     */
    public function getWithdrawalDetails($id)
    {
        try {
            $withdrawal = PaymentTransaction::with(['user', 'processedBy'])
                ->where('id', $id)
                ->where('type', 'withdrawal')
                ->first();

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal request not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $withdrawal->id,
                    'transaction_id' => $withdrawal->transaction_id,
                    'user' => [
                        'id' => $withdrawal->user->id,
                        'name' => $withdrawal->user->name,
                        'email' => $withdrawal->user->email,
                        'club_id' => $withdrawal->user->club_id ?? 'N/A'
                    ],
                    'amount' => $withdrawal->amount,
                    'fee' => $withdrawal->fee,
                    'net_amount' => $withdrawal->net_amount,
                    'payment_method' => $withdrawal->payment_method,
                    'gateway' => $withdrawal->gateway,
                    'sender_number' => $withdrawal->sender_number,
                    'status' => $withdrawal->status,
                    'description' => $withdrawal->description,
                    'notes' => $withdrawal->notes,
                    'rejection_reason' => $withdrawal->rejection_reason,
                    'metadata' => $withdrawal->metadata,
                    'created_at' => $withdrawal->created_at->format('M d, Y h:i A'),
                    'processed_at' => $withdrawal->processed_at?->format('M d, Y h:i A'),
                    'processed_by' => $withdrawal->processedBy ? [
                        'name' => $withdrawal->processedBy->name,
                        'email' => $withdrawal->processedBy->email
                    ] : null
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get withdrawal details: ' . $e->getMessage(), [
                'withdrawal_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load withdrawal details: ' . $e->getMessage()
            ], 500);
        }
    }
}
