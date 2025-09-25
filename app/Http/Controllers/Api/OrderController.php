<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Get order details by ID
     */
    public function show($id)
    {
        try {
            $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
            
            $order = Order::where('id', $id)
                         ->where('customer_id', $userId)
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Parse shipping address and billing address
            $shippingAddress = is_string($order->shipping_address) 
                ? json_decode($order->shipping_address, true) 
                : $order->shipping_address;
            
            $billingAddress = is_string($order->billing_address) 
                ? json_decode($order->billing_address, true) 
                : $order->billing_address;

            // Parse order items from notes (temporary solution)
            $orderItems = [];
            if ($order->notes && str_contains($order->notes, 'Items: ')) {
                $itemsJson = str_replace('Items: ', '', $order->notes);
                $orderItems = json_decode($itemsJson, true) ?? [];
            }

            $orderData = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'shipping_status' => $order->shipping_status,
                'total_amount' => $order->total_amount,
                'subtotal' => $order->subtotal,
                'shipping_amount' => $order->shipping_amount,
                'discount_amount' => $order->discount_amount,
                'tax_amount' => $order->tax_amount,
                'currency' => $order->currency,
                'payment_method' => $order->payment_method,
                'created_at' => $order->created_at->format('M d, Y g:i A'),
                'shipping_address' => $shippingAddress,
                'billing_address' => $billingAddress,
                'items' => $orderItems
            ];

            return response()->json([
                'success' => true,
                'order' => $orderData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order details'
            ], 500);
        }
    }

    /**
     * Generate and download invoice for an order
     */
    public function downloadInvoice($id)
    {
        try {
            $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
            
            $order = Order::where('id', $id)
                         ->where('customer_id', $userId)
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Parse shipping address
            $shippingAddress = [];
            if ($order->shipping_address) {
                if (is_string($order->shipping_address)) {
                    $shippingAddress = json_decode($order->shipping_address, true) ?? [];
                } elseif (is_array($order->shipping_address)) {
                    $shippingAddress = $order->shipping_address;
                }
            }

            // Parse order items from notes field
            $items = [];
            if ($order->notes) {
                if (is_string($order->notes)) {
                    $parsedNotes = json_decode($order->notes, true);
                    if (is_array($parsedNotes) && isset($parsedNotes['items'])) {
                        $items = $parsedNotes['items'];
                    }
                } elseif (is_array($order->notes)) {
                    if (isset($order->notes['items'])) {
                        $items = $order->notes['items'];
                    } else {
                        $items = $order->notes;
                    }
                }
            }

            // Generate invoice HTML
            $invoiceHtml = view('invoices.template', [
                'order' => $order,
                'shipping_address' => $shippingAddress,
                'items' => $items
            ])->render();

            // Return HTML for PDF generation or direct download
            return response()->json([
                'success' => true,
                'html' => $invoiceHtml,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and download PDF invoice for an order
     */
    public function downloadPdfInvoice($id)
    {
        try {
            $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
            
            $order = Order::where('id', $id)
                         ->where('customer_id', $userId)
                         ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Parse shipping address
            $shippingAddress = [];
            if ($order->shipping_address) {
                if (is_string($order->shipping_address)) {
                    $shippingAddress = json_decode($order->shipping_address, true) ?? [];
                } elseif (is_array($order->shipping_address)) {
                    $shippingAddress = $order->shipping_address;
                }
            }

            // Parse order items from notes field
            $items = [];
            if ($order->notes) {
                if (is_string($order->notes)) {
                    $parsedNotes = json_decode($order->notes, true);
                    if (is_array($parsedNotes) && isset($parsedNotes['items'])) {
                        $items = $parsedNotes['items'];
                    }
                } elseif (is_array($order->notes)) {
                    if (isset($order->notes['items'])) {
                        $items = $order->notes['items'];
                    } else {
                        $items = $order->notes;
                    }
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('invoices.pdf', [
                'order' => $order,
                'shipping_address' => $shippingAddress,
                'items' => $items
            ]);

            // Set PDF options for A4 size
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 150,
                'defaultPaperSize' => 'A4',
                'isRemoteEnabled' => true
            ]);

            // Return PDF as download
            return $pdf->download('invoice-' . $order->order_number . '.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF invoice: ' . $e->getMessage()
            ], 500);
        }
    }
}
