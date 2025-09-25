<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Parse items from order notes field
     */
    private function parseOrderItems($order)
    {
        $items = [];
        if ($order->notes) {
            if (is_string($order->notes)) {
                // Check if notes starts with "Items: " prefix
                if (strpos($order->notes, 'Items: ') === 0) {
                    $jsonString = substr($order->notes, 7); // Remove "Items: " prefix
                    $parsedItems = json_decode($jsonString, true);
                    if (is_array($parsedItems)) {
                        $items = $parsedItems;
                    }
                } else {
                    // Try to parse as direct JSON
                    $parsedNotes = json_decode($order->notes, true);
                    if (is_array($parsedNotes)) {
                        if (isset($parsedNotes['items'])) {
                            $items = $parsedNotes['items'];
                        } elseif (is_array($parsedNotes) && !empty($parsedNotes)) {
                            $items = $parsedNotes;
                        }
                    }
                }
            } elseif (is_array($order->notes)) {
                if (isset($order->notes['items'])) {
                    $items = $order->notes['items'];
                } else {
                    $items = $order->notes;
                }
            }
        }
        return $items;
    }

    /**
     * Display invoice page for printing/saving
     */
    public function show($orderId)
    {
        $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
        
        $order = Order::where('id', $orderId)
                     ->where('customer_id', $userId)
                     ->first();

        if (!$order) {
            abort(404, 'Order not found');
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
        $items = $this->parseOrderItems($order);

        return view('invoices.show', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'items' => $items
        ]);
    }

    /**
     * Download invoice as HTML (for PDF conversion)
     */
    public function download($orderId)
    {
        $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
        
        $order = Order::where('id', $orderId)
                     ->where('customer_id', $userId)
                     ->first();

        if (!$order) {
            abort(404, 'Order not found');
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
        $items = $this->parseOrderItems($order);

        $html = view('invoices.download', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'items' => $items
        ])->render();

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="invoice-' . $order->order_number . '.html"'
        ]);
    }

    /**
     * Download invoice as PDF
     */
    public function downloadPdf($orderId)
    {
        $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
        
        $order = Order::where('id', $orderId)
                     ->where('customer_id', $userId)
                     ->first();

        if (!$order) {
            abort(404, 'Order not found');
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
        $items = $this->parseOrderItems($order);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'items' => $items
        ]);

        // Simple PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true
        ]);

        // Download the PDF
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Stream/View PDF in browser
     */
    public function viewPdf($orderId)
    {
        $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
        
        $order = Order::where('id', $orderId)
                     ->where('customer_id', $userId)
                     ->first();

        if (!$order) {
            abort(404, 'Order not found');
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
        $items = $this->parseOrderItems($order);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'items' => $items
        ]);

        // Simple PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true
        ]);

        // Stream the PDF to browser
        return $pdf->stream('invoice-' . $order->order_number . '.pdf');
    }
    
    /**
     * Show responsive invoice view
     */
    public function showResponsive($orderId)
    {
        $userId = Auth::id() ?? 1; // Use 1 for testing if not authenticated
        
        $order = Order::where('id', $orderId)
                     ->where('customer_id', $userId)
                     ->first();

        if (!$order) {
            abort(404, 'Order not found');
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
        $items = $this->parseOrderItems($order);

        return view('invoices.responsive', [
            'order' => $order,
            'shipping_address' => $shippingAddress,
            'items' => $items
        ]);
    }
}
