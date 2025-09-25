<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        try {
            $payments = $this->getPaymentsQuery();
            
            // Apply filters (for mock data, basic filtering)
            if ($request->filled('status')) {
                $payments = $payments->where('status', $request->status);
            }
            
            if ($request->filled('payment_method')) {
                $payments = $payments->where('payment_method', $request->payment_method);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $payments = $payments->filter(function($payment) use ($search) {
                    return strpos($payment['transaction_id'], $search) !== false ||
                           strpos($payment['reference_number'], $search) !== false ||
                           strpos($payment['order']['order_number'], $search) !== false;
                });
            }
            
            // Convert to paginated result (mock pagination)
            $paymentsArray = $payments->toArray();
            $currentPage = request()->get('page', 1);
            $perPage = 20;
            
            // Get payment statistics
            $stats = $this->getPaymentStatistics();
            
            // Get payment methods
            $paymentMethods = $this->getPaymentMethods();
            
            return view('admin.payments.index', compact('paymentsArray', 'stats', 'paymentMethods'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return back()->with('error', 'Failed to load payments.');
        }
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        try {
            $paymentMethods = $this->getPaymentMethods();
            $orders = $this->getPendingOrders();
            
            return view('admin.payments.create', compact('paymentMethods', 'orders'));
            
        } catch (\Exception $e) {
            Log::error('Error loading payment create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load payment form.');
        }
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:paypal,stripe,razorpay,bank_transfer,cash_on_delivery',
            'amount' => 'required|numeric|min:0.01',
            'transaction_id' => 'nullable|string|max:255|unique:payments,transaction_id',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,failed,cancelled,refunded'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $payment = $this->createPayment($request->all());
            
            // Update order status if payment is completed
            if ($request->status === 'completed') {
                $this->updateOrderStatus($request->order_id, 'paid');
            }
            
            DB::commit();
            
            Log::info('Payment created successfully', ['payment_id' => $payment['id']]);
            
            return redirect()->route('admin.payments.index')
                           ->with('success', 'Payment created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment: ' . $e->getMessage());
            return back()->with('error', 'Failed to create payment.')->withInput();
        }
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        try {
            $payment = $this->findPayment($id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found.');
            }
            
            // Get related transactions
            $relatedTransactions = $this->getRelatedTransactions($id);
            
            return view('admin.payments.show', compact('payment', 'relatedTransactions'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching payment details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load payment details.');
        }
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($id)
    {
        try {
            $payment = $this->findPayment($id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found.');
            }
            
            $paymentMethods = $this->getPaymentMethods();
            
            return view('admin.payments.edit', compact('payment', 'paymentMethods'));
            
        } catch (\Exception $e) {
            Log::error('Error loading payment edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load payment form.');
        }
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:paypal,stripe,razorpay,bank_transfer,cash_on_delivery',
            'amount' => 'required|numeric|min:0.01',
            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('payments', 'transaction_id')->ignore($id)
            ],
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,failed,cancelled,refunded'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $payment = $this->findPayment($id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found.');
            }
            
            DB::beginTransaction();
            
            $oldStatus = $payment['status'];
            $payment = $this->updatePayment($id, $request->all());
            
            // Update order status if payment status changed
            if ($oldStatus !== $request->status) {
                $this->handleStatusChange($payment, $oldStatus, $request->status);
            }
            
            DB::commit();
            
            Log::info('Payment updated successfully', ['payment_id' => $id]);
            
            return redirect()->route('admin.payments.show', $id)
                           ->with('success', 'Payment updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment: ' . $e->getMessage());
            return back()->with('error', 'Failed to update payment.')->withInput();
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        try {
            $payment = $this->findPayment($id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found.');
            }
            
            // Check if payment can be deleted
            if (in_array($payment['status'], ['completed', 'refunded'])) {
                return back()->with('error', 'Cannot delete completed or refunded payments.');
            }
            
            DB::beginTransaction();
            
            $this->deletePayment($id);
            
            DB::commit();
            
            Log::info('Payment deleted successfully', ['payment_id' => $id]);
            
            return redirect()->route('admin.payments.index')
                           ->with('success', 'Payment deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Process refund for a payment.
     */
    public function refund(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $payment = $this->findPayment($id);
            
            if (!$payment) {
                return back()->with('error', 'Payment not found.');
            }
            
            if ($payment['status'] !== 'completed') {
                return back()->with('error', 'Only completed payments can be refunded.');
            }
            
            if ($request->refund_amount > $payment['amount']) {
                return back()->with('error', 'Refund amount cannot exceed payment amount.');
            }
            
            DB::beginTransaction();
            
            // Process refund based on payment method
            $refundResult = $this->processRefund($payment, $request->refund_amount, $request->refund_reason);
            
            if ($refundResult['success']) {
                // Update payment status
                $this->updatePayment($id, [
                    'status' => 'refunded',
                    'refund_amount' => $request->refund_amount,
                    'refund_reason' => $request->refund_reason,
                    'refunded_at' => now()
                ]);
                
                // Update order status
                $this->updateOrderStatus($payment['order_id'], 'refunded');
            }
            
            DB::commit();
            
            Log::info('Payment refund processed', ['payment_id' => $id, 'amount' => $request->refund_amount]);
            
            return back()->with('success', 'Refund processed successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing refund: ' . $e->getMessage());
            return back()->with('error', 'Failed to process refund.');
        }
    }

    /**
     * Export payments to CSV.
     */
    public function export(Request $request)
    {
        try {
            $payments = $this->getPaymentsQuery();
            
            // Apply same filters as index (for mock data, we'll use the collection as is)
            // In real implementation, apply filters to the database query
            
            $filename = 'payments_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($payments, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting payments: ' . $e->getMessage());
            return back()->with('error', 'Failed to export payments.');
        }
    }

    /**
     * Get payment statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $stats = $this->getPaymentStatistics();
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            Log::error('Error fetching payment stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch stats'], 500);
        }
    }

    // Private helper methods

    private function getPaymentsQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'order_id' => 1,
                'payment_method' => 'stripe',
                'amount' => 299.99,
                'status' => 'completed',
                'transaction_id' => 'pi_1234567890',
                'reference_number' => 'REF001',
                'created_at' => now()->subDays(1),
                'order' => [
                    'id' => 1,
                    'order_number' => 'ORD-001',
                    'customer_name' => 'John Doe'
                ]
            ],
            [
                'id' => 2,
                'order_id' => 2,
                'payment_method' => 'paypal',
                'amount' => 150.00,
                'status' => 'pending',
                'transaction_id' => 'PAY-987654321',
                'reference_number' => 'REF002',
                'created_at' => now(),
                'order' => [
                    'id' => 2,
                    'order_number' => 'ORD-002',
                    'customer_name' => 'Jane Smith'
                ]
            ]
        ]);
    }

    private function getPaymentStatistics()
    {
        return [
            'total_payments' => 1250,
            'total_amount' => 125000.50,
            'completed_payments' => 1100,
            'pending_payments' => 75,
            'failed_payments' => 50,
            'refunded_payments' => 25,
            'monthly_revenue' => 25000.00,
            'payment_methods' => [
                'stripe' => 45,
                'paypal' => 30,
                'razorpay' => 15,
                'bank_transfer' => 8,
                'cash_on_delivery' => 2
            ]
        ];
    }

    private function getPaymentMethods()
    {
        return [
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'razorpay' => 'Razorpay',
            'bank_transfer' => 'Bank Transfer',
            'cash_on_delivery' => 'Cash on Delivery'
        ];
    }

    private function getPendingOrders()
    {
        // Mock data - replace with actual database query
        return collect([
            ['id' => 1, 'order_number' => 'ORD-001', 'customer_name' => 'John Doe', 'total' => 299.99],
            ['id' => 2, 'order_number' => 'ORD-002', 'customer_name' => 'Jane Smith', 'total' => 150.00]
        ]);
    }

    private function createPayment($data)
    {
        // Mock creation - replace with actual database insert
        return [
            'id' => rand(1000, 9999),
            'order_id' => $data['order_id'],
            'payment_method' => $data['payment_method'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'reference_number' => $data['reference_number'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_at' => now()
        ];
    }

    private function findPayment($id)
    {
        // Mock data - replace with actual database query
        return [
            'id' => $id,
            'order_id' => 1,
            'payment_method' => 'stripe',
            'amount' => 299.99,
            'status' => 'completed',
            'transaction_id' => 'pi_1234567890',
            'reference_number' => 'REF001',
            'notes' => 'Payment processed successfully',
            'created_at' => now()->subDays(1),
            'order' => [
                'id' => 1,
                'order_number' => 'ORD-001',
                'customer_name' => 'John Doe',
                'total' => 299.99
            ]
        ];
    }

    private function updatePayment($id, $data)
    {
        // Mock update - replace with actual database update
        Log::info('Payment updated', ['id' => $id, 'data' => $data]);
        return array_merge($this->findPayment($id), $data);
    }

    private function deletePayment($id)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Payment deleted', ['id' => $id]);
    }

    private function updateOrderStatus($orderId, $status)
    {
        // Mock update - replace with actual order status update
        Log::info('Order status updated', ['order_id' => $orderId, 'status' => $status]);
    }

    private function handleStatusChange($payment, $oldStatus, $newStatus)
    {
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $this->updateOrderStatus($payment['order_id'], 'paid');
        } elseif ($newStatus === 'failed' && $oldStatus !== 'failed') {
            $this->updateOrderStatus($payment['order_id'], 'payment_failed');
        } elseif ($newStatus === 'refunded' && $oldStatus !== 'refunded') {
            $this->updateOrderStatus($payment['order_id'], 'refunded');
        }
    }

    private function getRelatedTransactions($paymentId)
    {
        // Mock data - replace with actual database query
        return collect([
            [
                'id' => 1,
                'type' => 'payment',
                'amount' => 299.99,
                'status' => 'completed',
                'created_at' => now()->subDays(1)
            ]
        ]);
    }

    private function processRefund($payment, $amount, $reason)
    {
        // Mock refund processing - replace with actual payment gateway integration
        switch ($payment['payment_method']) {
            case 'stripe':
                return $this->processStripeRefund($payment, $amount, $reason);
            case 'paypal':
                return $this->processPayPalRefund($payment, $amount, $reason);
            case 'razorpay':
                return $this->processRazorpayRefund($payment, $amount, $reason);
            default:
                return ['success' => true, 'message' => 'Refund processed manually'];
        }
    }

    private function processStripeRefund($payment, $amount, $reason)
    {
        // Mock Stripe refund - replace with actual Stripe API call
        return ['success' => true, 'refund_id' => 're_' . uniqid()];
    }

    private function processPayPalRefund($payment, $amount, $reason)
    {
        // Mock PayPal refund - replace with actual PayPal API call
        return ['success' => true, 'refund_id' => 'PP_' . uniqid()];
    }

    private function processRazorpayRefund($payment, $amount, $reason)
    {
        // Mock Razorpay refund - replace with actual Razorpay API call
        return ['success' => true, 'refund_id' => 'rfnd_' . uniqid()];
    }

    private function generateCsvExport($payments, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Payment ID',
                'Order Number',
                'Customer',
                'Payment Method',
                'Amount',
                'Status',
                'Transaction ID',
                'Reference Number',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment['id'],
                    $payment['order']['order_number'] ?? '',
                    $payment['order']['customer_name'] ?? '',
                    ucfirst($payment['payment_method']),
                    '$' . number_format($payment['amount'], 2),
                    ucfirst($payment['status']),
                    $payment['transaction_id'] ?? '',
                    $payment['reference_number'] ?? '',
                    $payment['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
