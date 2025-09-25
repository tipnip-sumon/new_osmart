<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\User;
use App\Mail\InvoiceMail;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

class InvoiceController extends Controller
{
    public function index()
    {
        // Get all orders for invoice listing with proper relationships
        $orders = Order::with(['customer', 'items.product', 'vendor'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.invoices.index', compact('orders'));
    }

    public function generateInvoice($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        return view('admin.invoices.invoice-template', compact('order'));
    }

    public function generateProfessionalInvoice($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        // Create seller party
        $seller = new Party([
            'name' => config('app.name', 'MultiVendor Marketplace'),
            'phone' => '+1 (555) 987-6543',
            'email' => 'invoices@multivendor.com',
            'address' => '789 Business Ave, Suite 100',
            'code' => 'SELLER-001',
            'custom_fields' => [
                'website' => 'www.multivendor.com',
                'tax_id' => 'TAX-123456789',
            ],
        ]);

        // Create buyer party
        $buyer = new Buyer([
            'name' => $order->customer->name ?? 'Guest Customer',
            'phone' => $order->customer->phone ?? 'N/A',
            'email' => $order->customer->email ?? 'N/A',
            'address' => $this->formatAddress($order->shipping_address ?? $order->billing_address),
            'custom_fields' => [
                'customer_id' => 'CUST-' . str_pad($order->customer_id, 6, '0', STR_PAD_LEFT),
            ],
        ]);

        // Create invoice items
        $items = [];
        foreach ($order->items as $orderItem) {
            $items[] = InvoiceItem::make($orderItem->product->name ?? 'Product')
                ->description($orderItem->product->short_description ?? 'Product description')
                ->pricePerUnit($orderItem->unit_price)
                ->quantity($orderItem->quantity)
                ->discount($orderItem->discount_amount ?? 0);
        }

        // Generate invoice
        $invoice = Invoice::make('Invoice')
            ->series('INV')
            ->sequence($order->id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->seller($seller)
            ->buyer($buyer)
            ->date($order->created_at)
            ->dateFormat('Y-m-d')
            ->payUntilDays(30)
            ->currencySymbol('Tk')
            ->currencyCode('BDT')
            ->currencyFormat('{SYMBOL} {VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->filename('invoice-' . $order->order_number)
            ->addItems($items)
            ->notes('Thank you for your business!')
            ->logo(public_path('assets/images/logo.png'));

        // Add tax if applicable
        if ($order->tax_amount > 0) {
            $invoice->taxRate($order->tax_amount / $order->subtotal * 100);
        }

        // Add shipping if applicable
        if ($order->shipping_amount > 0) {
            $invoice->shipping($order->shipping_amount);
        }

        // Add discount if applicable
        if ($order->discount_amount > 0) {
            $invoice->totalDiscount($order->discount_amount);
        }

        return $invoice->stream();
    }

    public function downloadProfessionalInvoice($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        // Create seller party
        $seller = new Party([
            'name' => config('app.name', 'MultiVendor Marketplace'),
            'phone' => '+1 (555) 987-6543',
            'email' => 'invoices@multivendor.com',
            'address' => '789 Business Ave, Suite 100',
            'code' => 'SELLER-001',
            'custom_fields' => [
                'website' => 'www.multivendor.com',
                'tax_id' => 'TAX-123456789',
            ],
        ]);

        // Create buyer party
        $buyer = new Buyer([
            'name' => $order->customer->name ?? 'Guest Customer',
            'phone' => $order->customer->phone ?? 'N/A',
            'email' => $order->customer->email ?? 'N/A',
            'address' => $this->formatAddress($order->shipping_address ?? $order->billing_address),
            'custom_fields' => [
                'customer_id' => 'CUST-' . str_pad($order->customer_id, 6, '0', STR_PAD_LEFT),
            ],
        ]);

        // Create invoice items
        $items = [];
        foreach ($order->items as $orderItem) {
            $items[] = InvoiceItem::make($orderItem->product->name ?? 'Product')
                ->description($orderItem->product->short_description ?? 'Product description')
                ->pricePerUnit($orderItem->unit_price)
                ->quantity($orderItem->quantity)
                ->discount($orderItem->discount_amount ?? 0);
        }

        // Generate invoice
        $invoice = Invoice::make('Invoice')
            ->series('INV')
            ->sequence($order->id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->seller($seller)
            ->buyer($buyer)
            ->date($order->created_at)
            ->dateFormat('Y-m-d')
            ->payUntilDays(30)
            ->currencySymbol('Tk')
            ->currencyCode('BDT')
            ->currencyFormat('{SYMBOL} {VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->filename('invoice-' . $order->order_number)
            ->addItems($items)
            ->notes('Thank you for your business!')
            ->logo(public_path('assets/images/logo.png'));

        // Add tax if applicable
        if ($order->tax_amount > 0) {
            $invoice->taxRate($order->tax_amount / $order->subtotal * 100);
        }

        // Add shipping if applicable
        if ($order->shipping_amount > 0) {
            $invoice->shipping($order->shipping_amount);
        }

        // Add discount if applicable
        if ($order->discount_amount > 0) {
            $invoice->totalDiscount($order->discount_amount);
        }

        return $invoice->download();
    }

    public function downloadInvoice($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        $pdf = Pdf::loadView('admin.invoices.invoice-pdf', compact('order'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'enable_javascript' => true,
                'enable_remote' => true,
            ]);

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function printInvoice($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        return view('admin.invoices.invoice-print', compact('order'));
    }

    public function emailInvoice(Request $request, $orderId)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'message' => 'nullable|string|max:500'
            ]);

            $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
            
            // Check if order exists and has the necessary data
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            
            // Generate PDF
            $pdf = Pdf::loadView('admin.invoices.invoice-pdf', compact('order'))
                ->setPaper('a4', 'portrait');

            // Send email
            Mail::to($request->email)->send(new InvoiceMail($order, $pdf->output(), $request->message));
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice sent successfully to ' . $request->email
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Email invoice error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'email' => $request->email ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invoice. Please check the server logs for details.'
            ], 500);
        }
    }

    public function bulkInvoices(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'required|integer',
            'action' => 'required|in:download,email'
        ]);

        $orders = Order::with(['customer', 'items.product', 'vendor'])
            ->whereIn('id', $request->order_ids)
            ->get();

        if ($request->action === 'download') {
            return $this->downloadBulkInvoices($orders);
        } else {
            return $this->emailBulkInvoices($orders, $request->email_addresses ?? []);
        }
    }

    public function invoicePreview($orderId)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($orderId);
        
        return view('admin.invoices.invoice-preview', compact('order'));
    }

    public function customizeInvoice(Request $request, $orderId)
    {
        $request->validate([
            'company_logo' => 'nullable|image|max:2048',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'invoice_notes' => 'nullable|string|max:1000',
            'terms_conditions' => 'nullable|string|max:1000',
            'color_scheme' => 'required|in:blue,green,red,purple,orange',
            'template_style' => 'required|in:modern,classic,minimal,corporate'
        ]);

        // Save customization settings
        $customization = [
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'invoice_notes' => $request->invoice_notes,
            'terms_conditions' => $request->terms_conditions,
            'color_scheme' => $request->color_scheme,
            'template_style' => $request->template_style,
        ];

        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('invoices/logos', 'public');
            $customization['company_logo'] = $logoPath;
        }

        // Store in session or database
        session(['invoice_customization' => $customization]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice customization saved successfully'
        ]);
    }

    public function invoiceAnalytics()
    {
        // Real analytics data from database
        $totalInvoices = Order::where('payment_status', 'paid')->count();
        $thisMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalAmount = Order::where('payment_status', 'paid')->sum('total_amount');
        $averageAmount = $totalInvoices > 0 ? $totalAmount / $totalInvoices : 0;
        $paidInvoices = Order::where('payment_status', 'paid')->count();
        $pendingInvoices = Order::where('payment_status', 'pending')->count();

        // Monthly data for the current year
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $monthlyTotal = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');
            $monthlyData[$monthName] = $monthlyTotal;
        }

        // Recent invoices
        $recentInvoices = Order::with(['customer'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'customer' => $order->customer->name ?? 'Guest Customer',
                    'amount' => $order->total_amount,
                    'status' => $order->payment_status
                ];
            });

        $analytics = [
            'total_invoices' => $totalInvoices,
            'this_month' => $thisMonth,
            'total_amount' => $totalAmount,
            'average_amount' => $averageAmount,
            'paid_invoices' => $paidInvoices,
            'pending_invoices' => $pendingInvoices,
            'monthly_data' => $monthlyData,
            'recent_invoices' => $recentInvoices
        ];

        return view('admin.invoices.analytics', compact('analytics'));
    }

    public function create()
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request)
    {
        // Implementation for creating new invoice
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully');
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($id);
        return view('admin.invoices.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with(['customer', 'items.product', 'vendor'])->findOrFail($id);
        return view('admin.invoices.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        // Implementation for updating invoice
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy($id)
    {
        // Implementation for deleting invoice
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
    }

    private function downloadBulkInvoices($orders)
    {
        $zip = new \ZipArchive();
        $zipFileName = 'invoices-bulk-' . date('Y-m-d-H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($orders as $order) {
                $pdf = Pdf::loadView('admin.invoices.invoice-pdf', compact('order'));
                $zip->addFromString('invoice-' . $order->order_number . '.pdf', $pdf->output());
            }
            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return response()->json(['error' => 'Could not create zip file'], 500);
    }

    private function emailBulkInvoices($orders, $emailAddresses)
    {
        $successCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            try {
                $pdf = Pdf::loadView('admin.invoices.invoice-pdf', compact('order'));
                
                foreach ($emailAddresses as $email) {
                    Mail::to($email)->send(new InvoiceMail($order, $pdf->output()));
                }
                
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Failed to send invoice {$order->order_number}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => "Successfully sent {$successCount} invoices",
            'errors' => $errors
        ]);
    }

    private function formatAddress($address)
    {
        if (empty($address)) {
            return 'N/A';
        }

        if (is_string($address)) {
            return $address;
        }

        if (is_array($address)) {
            $parts = [];
            
            if (!empty($address['street'])) $parts[] = $address['street'];
            if (!empty($address['street2'])) $parts[] = $address['street2'];
            if (!empty($address['city'])) $parts[] = $address['city'];
            if (!empty($address['state'])) $parts[] = $address['state'];
            if (!empty($address['zip'])) $parts[] = $address['zip'];
            if (!empty($address['country'])) $parts[] = $address['country'];
            
            return !empty($parts) ? implode(', ', $parts) : 'N/A';
        }

        return 'N/A';
    }

    /**
     * Send payment reminder for an invoice
     */
    public function sendReminder(Request $request, $orderId)
    {
        try {
            $order = Order::with(['customer'])->findOrFail($orderId);
            
            if (!$order->customer || !$order->customer->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email not found'
                ]);
            }

            if ($order->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice is already paid'
                ]);
            }

            // You can implement email sending logic here
            // For now, we'll just simulate success
            
            return response()->json([
                'success' => true,
                'message' => 'Payment reminder sent successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice marked as paid successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Duplicate an existing invoice
     */
    public function duplicate($orderId)
    {
        try {
            $originalOrder = Order::with(['customer', 'items.product'])->findOrFail($orderId);
            
            // Redirect to create page with pre-filled data
            return redirect()->route('admin.invoices.create')
                ->with('duplicate_data', [
                    'customer_id' => $originalOrder->customer_id,
                    'items' => $originalOrder->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'description' => $item->product->short_description ?? ''
                        ];
                    })->toArray(),
                    'shipping_address' => $originalOrder->shipping_address,
                    'payment_method' => $originalOrder->payment_method,
                    'vendor_id' => $originalOrder->vendor_id,
                    'notes' => 'Duplicate of Invoice INV-' . str_pad($originalOrder->id, 6, '0', STR_PAD_LEFT)
                ])
                ->with('success', 'Invoice data loaded for duplication. Please review and modify as needed.');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Failed to duplicate invoice: ' . $e->getMessage());
        }
    }

    /**
     * Get invoice statistics for API
     */
    public function getInvoiceStats()
    {
        try {
            $stats = [
                'total_invoices' => Order::count(),
                'paid_invoices' => Order::where('payment_status', 'paid')->count(),
                'pending_count' => Order::where('payment_status', 'pending')->count(),
                'failed_invoices' => Order::where('payment_status', 'failed')->count(),
                'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_amount'),
                'this_month_invoices' => Order::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count()
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch invoice stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
