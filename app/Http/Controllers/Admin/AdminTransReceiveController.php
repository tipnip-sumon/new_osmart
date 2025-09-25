<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AdminTransReceiveController extends Controller
{
    /**
     * Display a listing of transaction receipts.
     */
    public function index(Request $request)
    {
        try {
            $receipts = $this->getTransReceiptsQuery();
            
            // Apply filters
            if ($request->filled('status')) {
                $receipts = $receipts->where('status', $request->status);
            }
            
            if ($request->filled('transaction_type')) {
                $receipts = $receipts->where('transaction_type', $request->transaction_type);
            }
            
            if ($request->filled('payment_method')) {
                $receipts = $receipts->where('payment_method', $request->payment_method);
            }
            
            if ($request->filled('vendor_id')) {
                $receipts = $receipts->where('vendor_id', $request->vendor_id);
            }
            
            if ($request->filled('date_from')) {
                $receipts = $receipts->filter(function($receipt) use ($request) {
                    return Carbon::parse($receipt['created_at'])->gte($request->date_from);
                });
            }
            
            if ($request->filled('date_to')) {
                $receipts = $receipts->filter(function($receipt) use ($request) {
                    return Carbon::parse($receipt['created_at'])->lte($request->date_to);
                });
            }
            
            if ($request->filled('amount_min')) {
                $receipts = $receipts->where('amount', '>=', $request->amount_min);
            }
            
            if ($request->filled('amount_max')) {
                $receipts = $receipts->where('amount', '<=', $request->amount_max);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $receipts = $receipts->filter(function($receipt) use ($search) {
                    return stripos($receipt['transaction_id'], $search) !== false ||
                           stripos($receipt['reference_number'], $search) !== false ||
                           stripos($receipt['vendor_name'], $search) !== false ||
                           stripos($receipt['customer_name'], $search) !== false;
                });
            }
            
            // Get transaction statistics
            $stats = $this->getTransReceiptStatistics();
            
            // Get filter options
            $statuses = $this->getReceiptStatuses();
            $transactionTypes = $this->getTransactionTypes();
            $paymentMethods = $this->getPaymentMethods();
            $vendors = $this->getVendors();
            
            return view('admin.trans-receipts.index', compact('receipts', 'stats', 'statuses', 'transactionTypes', 'paymentMethods', 'vendors'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching transaction receipts: ' . $e->getMessage());
            return back()->with('error', 'Failed to load transaction receipts.');
        }
    }

    /**
     * Show the form for creating a new transaction receipt.
     */
    public function create()
    {
        try {
            $transactionTypes = $this->getTransactionTypes();
            $paymentMethods = $this->getPaymentMethods();
            $vendors = $this->getVendors();
            $customers = $this->getCustomers();
            $currencies = $this->getSupportedCurrencies();
            
            return view('admin.trans-receipts.create', compact('transactionTypes', 'paymentMethods', 'vendors', 'customers', 'currencies'));
            
        } catch (\Exception $e) {
            Log::error('Error loading transaction receipt create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load transaction receipt form.');
        }
    }

    /**
     * Store a newly created transaction receipt in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:payment,refund,payout,commission,withdrawal,deposit,transfer',
            'vendor_id' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
            'order_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'required|string|max:255|unique:transaction_receipts,transaction_id',
            'reference_number' => 'nullable|string|max:255',
            'gateway_transaction_id' => 'nullable|string|max:255',
            'gateway_response' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
            'receipt_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'invoice_attachment' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:pending,confirmed,failed,cancelled,refunded',
            'transaction_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:transaction_date',
            'notes' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Handle receipt attachment upload
            $receiptPath = null;
            if ($request->hasFile('receipt_attachment')) {
                $receiptPath = $this->uploadFile($request->file('receipt_attachment'), 'transactions/receipts');
            }
            
            // Handle invoice attachment upload
            $invoicePath = null;
            if ($request->hasFile('invoice_attachment')) {
                $invoicePath = $this->uploadFile($request->file('invoice_attachment'), 'transactions/invoices');
            }
            
            $receiptData = [
                'transaction_type' => $request->transaction_type,
                'vendor_id' => $request->vendor_id,
                'customer_id' => $request->customer_id,
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'currency' => strtoupper($request->currency),
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'reference_number' => $request->reference_number ?: $this->generateReferenceNumber(),
                'gateway_transaction_id' => $request->gateway_transaction_id,
                'gateway_response' => $request->gateway_response,
                'description' => $request->description,
                'receipt_attachment' => $receiptPath,
                'invoice_attachment' => $invoicePath,
                'status' => $request->status,
                'transaction_date' => Carbon::parse($request->transaction_date),
                'due_date' => $request->due_date ? Carbon::parse($request->due_date) : null,
                'processed_by' => Auth::id(),
                'notes' => $request->notes,
                'metadata' => $request->metadata ? json_encode($request->metadata) : null,
                'verification_status' => 'pending',
                'verification_notes' => null,
                'verified_by' => null,
                'verified_at' => null
            ];
            
            $receipt = $this->createTransReceipt($receiptData);
            
            // Create transaction history entry
            $this->createTransactionHistory($receipt['id'], 'created', 'Transaction receipt created', Auth::id());
            
            // Send notifications if confirmed
            if ($request->status === 'confirmed') {
                $this->sendConfirmationNotifications($receipt);
            }
            
            DB::commit();
            
            Log::info('Transaction receipt created successfully', ['receipt_id' => $receipt['id']]);
            
            return redirect()->route('admin.trans-receipts.show', $receipt['id'])
                           ->with('success', 'Transaction receipt created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating transaction receipt: ' . $e->getMessage());
            return back()->with('error', 'Failed to create transaction receipt.')->withInput();
        }
    }

    /**
     * Display the specified transaction receipt.
     */
    public function show($id)
    {
        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            // Get transaction history
            $history = $this->getTransactionHistory($id);
            
            // Get related transactions
            $relatedTransactions = $this->getRelatedTransactions($receipt);
            
            // Get verification details
            $verificationDetails = $this->getVerificationDetails($id);
            
            return view('admin.trans-receipts.show', compact('receipt', 'history', 'relatedTransactions', 'verificationDetails'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching transaction receipt details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load transaction receipt details.');
        }
    }

    /**
     * Show the form for editing the specified transaction receipt.
     */
    public function edit($id)
    {
        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            // Check if receipt can be edited
            if (in_array($receipt['status'], ['confirmed', 'refunded'])) {
                return back()->with('error', 'Cannot edit confirmed or refunded transaction receipts.');
            }
            
            $transactionTypes = $this->getTransactionTypes();
            $paymentMethods = $this->getPaymentMethods();
            $vendors = $this->getVendors();
            $customers = $this->getCustomers();
            $currencies = $this->getSupportedCurrencies();
            $statuses = $this->getReceiptStatuses();
            
            return view('admin.trans-receipts.edit', compact('receipt', 'transactionTypes', 'paymentMethods', 'vendors', 'customers', 'currencies', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('Error loading transaction receipt edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load transaction receipt form.');
        }
    }

    /**
     * Update the specified transaction receipt in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:payment,refund,payout,commission,withdrawal,deposit,transfer',
            'vendor_id' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
            'order_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => ['required', 'string', 'max:255', Rule::unique('transaction_receipts')->ignore($id)],
            'reference_number' => 'nullable|string|max:255',
            'gateway_transaction_id' => 'nullable|string|max:255',
            'gateway_response' => 'nullable|string',
            'description' => 'nullable|string|max:1000',
            'receipt_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'invoice_attachment' => 'nullable|file|mimes:pdf|max:5120',
            'status' => 'required|in:pending,confirmed,failed,cancelled,refunded',
            'transaction_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:transaction_date',
            'notes' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            DB::beginTransaction();
            
            $oldStatus = $receipt['status'];
            
            // Handle receipt attachment upload
            $receiptPath = $receipt['receipt_attachment'];
            if ($request->hasFile('receipt_attachment')) {
                if ($receiptPath) {
                    $this->deleteFile($receiptPath);
                }
                $receiptPath = $this->uploadFile($request->file('receipt_attachment'), 'transactions/receipts');
            }
            
            // Handle invoice attachment upload
            $invoicePath = $receipt['invoice_attachment'];
            if ($request->hasFile('invoice_attachment')) {
                if ($invoicePath) {
                    $this->deleteFile($invoicePath);
                }
                $invoicePath = $this->uploadFile($request->file('invoice_attachment'), 'transactions/invoices');
            }
            
            $receiptData = [
                'transaction_type' => $request->transaction_type,
                'vendor_id' => $request->vendor_id,
                'customer_id' => $request->customer_id,
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'currency' => strtoupper($request->currency),
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'reference_number' => $request->reference_number ?: $receipt['reference_number'],
                'gateway_transaction_id' => $request->gateway_transaction_id,
                'gateway_response' => $request->gateway_response,
                'description' => $request->description,
                'receipt_attachment' => $receiptPath,
                'invoice_attachment' => $invoicePath,
                'status' => $request->status,
                'transaction_date' => Carbon::parse($request->transaction_date),
                'due_date' => $request->due_date ? Carbon::parse($request->due_date) : null,
                'updated_by' => Auth::id(),
                'notes' => $request->notes,
                'metadata' => $request->metadata ? json_encode($request->metadata) : null
            ];
            
            $this->updateTransReceipt($id, $receiptData);
            
            // Create transaction history entry
            $this->createTransactionHistory($id, 'updated', 'Transaction receipt updated', Auth::id());
            
            // Handle status change notifications
            if ($oldStatus !== $request->status) {
                $this->createTransactionHistory($id, 'status_changed', "Status changed from {$oldStatus} to {$request->status}", Auth::id());
                
                if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
                    $this->sendConfirmationNotifications($receipt);
                }
            }
            
            DB::commit();
            
            Log::info('Transaction receipt updated successfully', ['receipt_id' => $id]);
            
            return redirect()->route('admin.trans-receipts.show', $id)
                           ->with('success', 'Transaction receipt updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transaction receipt: ' . $e->getMessage());
            return back()->with('error', 'Failed to update transaction receipt.')->withInput();
        }
    }

    /**
     * Remove the specified transaction receipt from storage.
     */
    public function destroy($id)
    {
        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            // Check if receipt can be deleted
            if (in_array($receipt['status'], ['confirmed', 'refunded'])) {
                return back()->with('error', 'Cannot delete confirmed or refunded transaction receipts.');
            }
            
            DB::beginTransaction();
            
            // Delete attachments
            if ($receipt['receipt_attachment']) {
                $this->deleteFile($receipt['receipt_attachment']);
            }
            if ($receipt['invoice_attachment']) {
                $this->deleteFile($receipt['invoice_attachment']);
            }
            
            // Delete transaction history
            $this->deleteTransactionHistory($id);
            
            // Delete the receipt
            $this->deleteTransReceipt($id);
            
            DB::commit();
            
            Log::info('Transaction receipt deleted successfully', ['receipt_id' => $id]);
            
            return redirect()->route('admin.trans-receipts.index')
                           ->with('success', 'Transaction receipt deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting transaction receipt: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete transaction receipt.');
        }
    }

    /**
     * Verify a transaction receipt.
     */
    public function verify(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'verification_status' => 'required|in:verified,rejected',
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            DB::beginTransaction();
            
            $verificationData = [
                'verification_status' => $request->verification_status,
                'verification_notes' => $request->verification_notes,
                'verified_by' => Auth::id(),
                'verified_at' => now()
            ];
            
            // Auto-confirm if verified and status is pending
            if ($request->verification_status === 'verified' && $receipt['status'] === 'pending') {
                $verificationData['status'] = 'confirmed';
            }
            
            $this->updateTransReceipt($id, $verificationData);
            
            // Create transaction history entry
            $this->createTransactionHistory($id, $request->verification_status, $request->verification_notes ?: "Transaction {$request->verification_status}", Auth::id());
            
            // Send notifications
            $this->sendVerificationNotifications($receipt, $request->verification_status);
            
            DB::commit();
            
            Log::info('Transaction receipt verification updated', ['receipt_id' => $id, 'status' => $request->verification_status]);
            
            return back()->with('success', 'Transaction verification updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transaction verification: ' . $e->getMessage());
            return back()->with('error', 'Failed to update verification status.');
        }
    }

    /**
     * Change transaction receipt status.
     */
    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,failed,cancelled,refunded',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid status.'], 400);
        }

        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return response()->json(['error' => 'Transaction receipt not found.'], 404);
            }
            
            $oldStatus = $receipt['status'];
            $newStatus = $request->status;
            
            // Validate status transition
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                return response()->json(['error' => 'Invalid status transition.'], 400);
            }
            
            DB::beginTransaction();
            
            $this->updateTransReceipt($id, ['status' => $newStatus, 'updated_by' => Auth::id()]);
            
            // Create transaction history entry
            $reason = $request->reason ? " - {$request->reason}" : '';
            $this->createTransactionHistory($id, 'status_changed', "Status changed from {$oldStatus} to {$newStatus}{$reason}", Auth::id());
            
            // Send notifications
            $this->sendStatusChangeNotifications($receipt, $oldStatus, $newStatus);
            
            DB::commit();
            
            Log::info('Transaction receipt status changed', ['receipt_id' => $id, 'old_status' => $oldStatus, 'new_status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing transaction status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Bulk actions for transaction receipts.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:confirm,cancel,verify,reject,delete',
            'receipt_ids' => 'required|array|min:1',
            'receipt_ids.*' => 'integer',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $receiptIds = $request->receipt_ids;
            $action = $request->action;
            $reason = $request->reason;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($receiptIds as $receiptId) {
                $receipt = $this->findTransReceipt($receiptId);
                if (!$receipt) continue;
                
                switch ($action) {
                    case 'confirm':
                        if ($this->isValidStatusTransition($receipt['status'], 'confirmed')) {
                            $this->updateTransReceipt($receiptId, ['status' => 'confirmed', 'updated_by' => Auth::id()]);
                            $this->createTransactionHistory($receiptId, 'status_changed', "Status changed to confirmed{$reason}", Auth::id());
                            $processedCount++;
                        }
                        break;
                        
                    case 'cancel':
                        if ($this->isValidStatusTransition($receipt['status'], 'cancelled')) {
                            $this->updateTransReceipt($receiptId, ['status' => 'cancelled', 'updated_by' => Auth::id()]);
                            $this->createTransactionHistory($receiptId, 'status_changed', "Status changed to cancelled{$reason}", Auth::id());
                            $processedCount++;
                        }
                        break;
                        
                    case 'verify':
                        $this->updateTransReceipt($receiptId, [
                            'verification_status' => 'verified',
                            'verified_by' => Auth::id(),
                            'verified_at' => now()
                        ]);
                        $this->createTransactionHistory($receiptId, 'verified', "Transaction verified{$reason}", Auth::id());
                        $processedCount++;
                        break;
                        
                    case 'reject':
                        $this->updateTransReceipt($receiptId, [
                            'verification_status' => 'rejected',
                            'verified_by' => Auth::id(),
                            'verified_at' => now()
                        ]);
                        $this->createTransactionHistory($receiptId, 'rejected', "Transaction rejected{$reason}", Auth::id());
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        if (!in_array($receipt['status'], ['confirmed', 'refunded'])) {
                            // Delete attachments
                            if ($receipt['receipt_attachment']) {
                                $this->deleteFile($receipt['receipt_attachment']);
                            }
                            if ($receipt['invoice_attachment']) {
                                $this->deleteFile($receipt['invoice_attachment']);
                            }
                            $this->deleteTransactionHistory($receiptId);
                            $this->deleteTransReceipt($receiptId);
                            $processedCount++;
                        }
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on transaction receipts', [
                'action' => $action,
                'processed_count' => $processedCount,
                'receipt_ids' => $receiptIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} transaction receipt(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Export transaction receipts to CSV.
     */
    public function export(Request $request)
    {
        try {
            $receipts = $this->getTransReceiptsQuery();
            
            // Apply same filters as index
            if ($request->filled('status')) {
                $receipts = $receipts->where('status', $request->status);
            }
            if ($request->filled('transaction_type')) {
                $receipts = $receipts->where('transaction_type', $request->transaction_type);
            }
            if ($request->filled('payment_method')) {
                $receipts = $receipts->where('payment_method', $request->payment_method);
            }
            
            $filename = 'transaction_receipts_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($receipts, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting transaction receipts: ' . $e->getMessage());
            return back()->with('error', 'Failed to export transaction receipts.');
        }
    }

    /**
     * Generate transaction receipt PDF.
     */
    public function generatePdf($id)
    {
        try {
            $receipt = $this->findTransReceipt($id);
            
            if (!$receipt) {
                return back()->with('error', 'Transaction receipt not found.');
            }
            
            // Generate PDF using your preferred PDF library
            $pdf = $this->generateReceiptPdf($receipt);
            
            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="receipt_' . $receipt['reference_number'] . '.pdf"'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating receipt PDF: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF.');
        }
    }

    /**
     * Transaction receipts dashboard with analytics.
     */
    public function dashboard()
    {
        try {
            $stats = $this->getTransReceiptDashboardStats();
            $recentReceipts = $this->getRecentReceipts();
            $chartData = $this->getChartData();
            
            return view('admin.trans-receipts.dashboard', compact('stats', 'recentReceipts', 'chartData'));
            
        } catch (\Exception $e) {
            Log::error('Error loading transaction receipts dashboard: ' . $e->getMessage());
            return back()->with('error', 'Failed to load dashboard.');
        }
    }

    // Private helper methods

    private function getTransReceiptsQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'transaction_type' => 'payment',
                'vendor_id' => 1,
                'vendor_name' => 'Tech Store',
                'customer_id' => 1,
                'customer_name' => 'John Doe',
                'order_id' => 1001,
                'amount' => 299.99,
                'currency' => 'USD',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'TXN_2025_001',
                'reference_number' => 'REF_2025_001',
                'gateway_transaction_id' => 'stripe_ch_1234567890',
                'gateway_response' => 'Payment successful',
                'description' => 'Payment for order #1001',
                'receipt_attachment' => 'transactions/receipts/receipt_001.pdf',
                'invoice_attachment' => 'transactions/invoices/invoice_001.pdf',
                'status' => 'confirmed',
                'transaction_date' => now()->subDays(2),
                'due_date' => null,
                'processed_by' => 1,
                'processed_by_name' => 'Admin User',
                'updated_by' => 1,
                'notes' => 'Payment processed successfully',
                'metadata' => json_encode(['gateway' => 'stripe', 'card_last4' => '1234']),
                'verification_status' => 'verified',
                'verification_notes' => 'Payment verified with bank',
                'verified_by' => 1,
                'verified_by_name' => 'Admin User',
                'verified_at' => now()->subDays(1),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1)
            ],
            [
                'id' => 2,
                'transaction_type' => 'refund',
                'vendor_id' => 2,
                'vendor_name' => 'Fashion Hub',
                'customer_id' => 2,
                'customer_name' => 'Jane Smith',
                'order_id' => 1002,
                'amount' => 159.50,
                'currency' => 'USD',
                'payment_method' => 'PayPal',
                'transaction_id' => 'TXN_2025_002',
                'reference_number' => 'REF_2025_002',
                'gateway_transaction_id' => 'paypal_ref_0987654321',
                'gateway_response' => 'Refund processed',
                'description' => 'Refund for returned item',
                'receipt_attachment' => null,
                'invoice_attachment' => null,
                'status' => 'pending',
                'transaction_date' => now()->subHours(6),
                'due_date' => now()->addDays(3),
                'processed_by' => 1,
                'processed_by_name' => 'Admin User',
                'updated_by' => null,
                'notes' => 'Customer requested refund for defective item',
                'metadata' => json_encode(['gateway' => 'paypal', 'reason' => 'defective']),
                'verification_status' => 'pending',
                'verification_notes' => null,
                'verified_by' => null,
                'verified_by_name' => null,
                'verified_at' => null,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6)
            ],
            [
                'id' => 3,
                'transaction_type' => 'payout',
                'vendor_id' => 1,
                'vendor_name' => 'Tech Store',
                'customer_id' => null,
                'customer_name' => null,
                'order_id' => null,
                'amount' => 1250.00,
                'currency' => 'USD',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN_2025_003',
                'reference_number' => 'REF_2025_003',
                'gateway_transaction_id' => null,
                'gateway_response' => null,
                'description' => 'Weekly vendor payout',
                'receipt_attachment' => 'transactions/receipts/payout_001.pdf',
                'invoice_attachment' => null,
                'status' => 'confirmed',
                'transaction_date' => now()->subDays(1),
                'due_date' => null,
                'processed_by' => 1,
                'processed_by_name' => 'Admin User',
                'updated_by' => null,
                'notes' => 'Vendor payout for week ending July 20',
                'metadata' => json_encode(['payout_period' => 'weekly', 'bank_account' => '****1234']),
                'verification_status' => 'verified',
                'verification_notes' => 'Bank transfer confirmed',
                'verified_by' => 1,
                'verified_by_name' => 'Admin User',
                'verified_at' => now(),
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ]
        ]);
    }

    private function getTransReceiptStatistics()
    {
        return [
            'total_receipts' => 245,
            'confirmed_receipts' => 198,
            'pending_receipts' => 32,
            'failed_receipts' => 15,
            'total_amount' => 125670.50,
            'avg_transaction_amount' => 512.53,
            'verification_pending' => 18,
            'type_breakdown' => [
                'payment' => 156,
                'refund' => 34,
                'payout' => 28,
                'commission' => 15,
                'withdrawal' => 12
            ],
            'payment_method_breakdown' => [
                'Credit Card' => 98,
                'PayPal' => 67,
                'Bank Transfer' => 45,
                'Stripe' => 35
            ]
        ];
    }

    private function getReceiptStatuses()
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded'
        ];
    }

    private function getTransactionTypes()
    {
        return [
            'payment' => 'Payment',
            'refund' => 'Refund',
            'payout' => 'Vendor Payout',
            'commission' => 'Commission',
            'withdrawal' => 'Withdrawal',
            'deposit' => 'Deposit',
            'transfer' => 'Transfer'
        ];
    }

    private function getPaymentMethods()
    {
        return [
            'Credit Card' => 'Credit Card',
            'Debit Card' => 'Debit Card',
            'PayPal' => 'PayPal',
            'Stripe' => 'Stripe',
            'Bank Transfer' => 'Bank Transfer',
            'Wire Transfer' => 'Wire Transfer',
            'Cash' => 'Cash',
            'Check' => 'Check'
        ];
    }

    private function getVendors()
    {
        return collect([
            ['id' => 1, 'name' => 'Tech Store', 'email' => 'admin@techstore.com'],
            ['id' => 2, 'name' => 'Fashion Hub', 'email' => 'info@fashionhub.com'],
            ['id' => 3, 'name' => 'Book World', 'email' => 'contact@bookworld.com']
        ]);
    }

    private function getCustomers()
    {
        return collect([
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['id' => 3, 'name' => 'Bob Wilson', 'email' => 'bob@example.com']
        ]);
    }

    private function getSupportedCurrencies()
    {
        return [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'CAD' => 'Canadian Dollar',
            'AUD' => 'Australian Dollar'
        ];
    }

    private function generateReferenceNumber()
    {
        return 'REF_' . date('Y') . '_' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    private function createTransReceipt($data)
    {
        // Mock creation - replace with actual database insert
        return array_merge(['id' => rand(1000, 9999)], $data, ['created_at' => now(), 'updated_at' => now()]);
    }

    private function findTransReceipt($id)
    {
        // Mock data - replace with actual database query
        $receipts = $this->getTransReceiptsQuery();
        return $receipts->firstWhere('id', $id);
    }

    private function updateTransReceipt($id, $data)
    {
        // Mock update - replace with actual database update
        Log::info('Transaction receipt updated', ['id' => $id, 'data' => $data]);
    }

    private function deleteTransReceipt($id)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Transaction receipt deleted', ['id' => $id]);
    }

    private function uploadFile($file, $directory)
    {
        // Mock upload - replace with actual file upload logic
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $directory . '/' . $filename;
        
        Log::info('Transaction file uploaded', ['path' => $path, 'size' => $file->getSize()]);
        
        return $path;
    }

    private function deleteFile($path)
    {
        // Mock deletion - replace with actual file deletion
        Log::info('Transaction file deleted', ['path' => $path]);
    }

    private function createTransactionHistory($receiptId, $action, $description, $userId)
    {
        // Mock history creation - replace with actual database insert
        Log::info('Transaction history created', [
            'receipt_id' => $receiptId,
            'action' => $action,
            'description' => $description,
            'user_id' => $userId
        ]);
    }

    private function getTransactionHistory($receiptId)
    {
        // Mock history - replace with actual database query
        return collect([
            [
                'id' => 1,
                'action' => 'created',
                'description' => 'Transaction receipt created',
                'created_by_name' => 'Admin User',
                'created_at' => now()->subDays(2)
            ],
            [
                'id' => 2,
                'action' => 'verified',
                'description' => 'Payment verified with bank',
                'created_by_name' => 'Admin User',
                'created_at' => now()->subDays(1)
            ]
        ]);
    }

    private function deleteTransactionHistory($receiptId)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Transaction history deleted', ['receipt_id' => $receiptId]);
    }

    private function getRelatedTransactions($receipt)
    {
        // Mock related transactions - replace with actual database query
        return collect([]);
    }

    private function getVerificationDetails($receiptId)
    {
        // Mock verification details - replace with actual database query
        return [
            'verification_required' => true,
            'verification_documents' => ['ID verification', 'Bank statement'],
            'verification_deadline' => now()->addDays(7)
        ];
    }

    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['confirmed', 'failed', 'cancelled'],
            'confirmed' => ['refunded'],
            'failed' => ['confirmed', 'cancelled'],
            'cancelled' => [],
            'refunded' => []
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    private function sendConfirmationNotifications($receipt)
    {
        // Mock notification - replace with actual email/notification sending
        Log::info('Confirmation notifications sent', ['receipt_id' => $receipt['id']]);
    }

    private function sendVerificationNotifications($receipt, $status)
    {
        // Mock notification - replace with actual email/notification sending
        Log::info('Verification notifications sent', ['receipt_id' => $receipt['id'], 'status' => $status]);
    }

    private function sendStatusChangeNotifications($receipt, $oldStatus, $newStatus)
    {
        // Mock notification - replace with actual email/notification sending
        Log::info('Status change notifications sent', [
            'receipt_id' => $receipt['id'],
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    private function generateReceiptPdf($receipt)
    {
        // Mock PDF generation - replace with actual PDF library
        return 'Mock PDF content for receipt: ' . $receipt['reference_number'];
    }

    private function getTransReceiptDashboardStats()
    {
        return [
            'today_receipts' => 15,
            'today_amount' => 3456.78,
            'weekly_receipts' => 89,
            'weekly_amount' => 23456.90,
            'monthly_receipts' => 245,
            'monthly_amount' => 125670.50,
            'pending_verification' => 18,
            'failed_transactions' => 5
        ];
    }

    private function getRecentReceipts()
    {
        return $this->getTransReceiptsQuery()->take(5);
    }

    private function getChartData()
    {
        return [
            'daily_amounts' => [150, 200, 180, 220, 190, 250, 280],
            'daily_counts' => [5, 8, 6, 9, 7, 10, 12],
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        ];
    }

    private function generateCsvExport($receipts, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($receipts) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Transaction ID',
                'Reference Number',
                'Type',
                'Vendor',
                'Customer',
                'Amount',
                'Currency',
                'Payment Method',
                'Status',
                'Verification Status',
                'Transaction Date',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($receipts as $receipt) {
                fputcsv($file, [
                    $receipt['transaction_id'],
                    $receipt['reference_number'],
                    ucfirst($receipt['transaction_type']),
                    $receipt['vendor_name'] ?? 'N/A',
                    $receipt['customer_name'] ?? 'N/A',
                    number_format($receipt['amount'], 2),
                    $receipt['currency'],
                    $receipt['payment_method'],
                    ucfirst($receipt['status']),
                    ucfirst($receipt['verification_status']),
                    $receipt['transaction_date'],
                    $receipt['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
