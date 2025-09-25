<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vendor Invoice - INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/icons/icon-180x180.svg') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/icon-152x152.svg') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/icons/icon-167x167.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/icon-180x180.svg') }}">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 11px;
                line-height: 1.3;
                margin: 0;
                padding: 0;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            /* A4 Print Optimization with Page Breaks */
            @page {
                size: A4;
                margin: 0.75in 0.75in 0.75in 0.75in;
            }
            
            .container-fluid {
                padding: 0 20px !important;
                max-width: 100% !important;
                margin: 0 auto !important;
            }
            
            .invoice-content {
                max-width: 100% !important;
                margin: 0 !important;
            }
            
            /* Page break controls */
            .page-break-before {
                page-break-before: always !important;
            }
            
            .page-break-after {
                page-break-after: always !important;
            }
            
            .page-break-avoid {
                page-break-inside: avoid !important;
            }
            
            /* Keep sections together */
            .invoice-header,
            .vendor-customer-info,
            .total-section {
                page-break-inside: avoid !important;
            }
            
            /* Break after large tables if needed */
            .items-table-section {
                page-break-after: auto !important;
            }
            
            /* Footer should stay at bottom of last page */
            .invoice-footer {
                page-break-inside: avoid !important;
                margin-top: auto !important;
            }
            
            /* Prevent orphan rows in tables */
            .table tbody tr {
                page-break-inside: avoid !important;
            }
            
            /* Header repeat on each page */
            thead {
                display: table-header-group !important;
            }
            
            tfoot {
                display: table-footer-group !important;
            }
            
            /* Compact spacing for print */
            .mb-1 { margin-bottom: 0.15rem !important; }
            .mb-2 { margin-bottom: 0.3rem !important; }
            .mb-3 { margin-bottom: 0.5rem !important; }
            .mb-4 { margin-bottom: 0.7rem !important; }
            .mb-5 { margin-bottom: 1rem !important; }
            
            .mt-3 { margin-top: 0.5rem !important; }
            .mt-4 { margin-top: 0.7rem !important; }
            .mt-5 { margin-top: 1rem !important; }
            
            /* Compact header */
            .invoice-header {
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            
            .invoice-title {
                font-size: 1.8rem !important;
            }
            
            /* Table optimization */
            .table th, .table td {
                padding: 0.4rem !important;
                font-size: 10px !important;
            }
            
            /* Footer compact */
            .border-top {
                margin-top: 1rem !important;
                padding-top: 0.5rem !important;
            }
            
            /* Single page optimization - responsive to content */
            .total-section {
                padding: 10px !important;
                margin-top: 0.5rem !important;
                page-break-inside: avoid !important;
            }
            
            /* Minimize timeline badges */
            .timeline-badge {
                padding: 4px 8px !important;
                font-size: 0.7rem !important;
                margin-right: 0.3rem !important;
                margin-bottom: 0.3rem !important;
            }
            
            /* Auto page breaks for long content */
            .items-section {
                page-break-after: auto !important;
            }
            
            /* Page numbers for multi-page invoices */
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9px;
                color: #666;
            }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        
        .invoice-header {
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-logo img {
            max-height: 60px;
        }
        
        .invoice-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: -0.5px;
        }
        
        .table th {
            background-color: #f8f9fa !important;
            font-weight: 600;
            border: 1px solid #dee2e6;
            padding: 0.75rem;
        }
        
        .table td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            vertical-align: middle;
        }
        
        .total-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e9ecef;
        }
        
        .text-primary {
            color: #0d6efd !important;
        }
        
        .text-success {
            color: #198754 !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .vendor-info {
            background-color: #e7f1ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            border-radius: 0 8px 8px 0;
        }
        
        .timeline-badge {
            background-color: #0d6efd;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .timeline-badge.bg-success {
            background-color: #198754 !important;
        }
        
        .timeline-badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
        
        .timeline-badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000 !important;
        }
        
        .timeline-badge.bg-danger {
            background-color: #dc3545 !important;
        }
        
        /* Bootstrap 5 Badge fixes */
        .badge {
            padding: 0.375em 0.75em;
            font-size: 0.75em;
            font-weight: 700;
            border-radius: 0.375rem;
        }
        
        .badge.bg-success {
            background-color: #198754 !important;
            color: #fff !important;
        }
        
        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
        
        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        
        .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000 !important;
        }
        
        .badge.bg-secondary {
            background-color: #6c757d !important;
            color: #fff !important;
        }
        
        /* Table styling fixes */
        .table-light th {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            color: #212529 !important;
            font-weight: 600;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: rgba(0, 0, 0, 0.025);
        }
        
        /* Print-friendly table */
        @media print {
            .table-light th {
                background-color: #e9ecef !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .table-striped > tbody > tr:nth-of-type(odd) > td {
                background-color: rgba(0, 0, 0, 0.05) !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
        
        /* Container and grid fixes */
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
            padding-right: 30px;
            padding-left: 30px;
        }
        
        /* Standard invoice margins */
        .invoice-content {
            max-width: 100%;
            margin: 0 auto;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-12, .col-md-6, .col-md-4, .col-md-8, .col-5, .col-6, .col-7 {
            padding-right: 15px;
            padding-left: 15px;
        }
        
        .col-12 { flex: 0 0 100%; max-width: 100%; }
        .col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
        .col-6 { flex: 0 0 50%; max-width: 50%; }
        .col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
        
        @media (min-width: 768px) {
            .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
            .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
        }
        
        /* Button styling */
        .btn {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-primary {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .btn-success {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }
        
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-group {
            display: inline-flex;
            vertical-align: middle;
        }
        
        .btn-group .btn:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .btn-group .btn:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            margin-left: -1px;
        }
        
        /* Fix responsive font sizes */
        @media (max-width: 768px) {
            .invoice-title {
                font-size: 2rem;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
            
            .vendor-info {
                margin-bottom: 1rem;
            }
        }
        
        /* A4 Responsive Design */
        @media (max-width: 1024px) {
            .invoice-title {
                font-size: 2.2rem;
            }
            
            .company-details, .customer-details {
                font-size: 0.9rem;
            }
            
            .timeline-badge {
                font-size: 0.8rem;
                padding: 6px 10px;
            }
            
            .container-fluid {
                padding-right: 20px;
                padding-left: 20px;
            }
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding-right: 15px;
                padding-left: 15px;
            }
            
            .invoice-title {
                font-size: 2rem;
            }
        }
        
        /* Compact layout for single page */
        .compact-section {
            margin-bottom: 1rem !important;
        }
        
        .compact-spacing .mb-4 {
            margin-bottom: 0.8rem !important;
        }
        
        .compact-spacing .mb-3 {
            margin-bottom: 0.6rem !important;
        }
        
        /* Ensure proper spacing */
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-5 { margin-bottom: 3rem !important; }
        
        .mt-3 { margin-top: 1rem !important; }
        .mt-4 { margin-top: 1.5rem !important; }
        .mt-5 { margin-top: 3rem !important; }
        
        /* Font weight fixes */
        .fw-bold { font-weight: 700 !important; }
        .fw-semibold { font-weight: 600 !important; }
        
        /* Border utilities */
        .border-top { border-top: 1px solid #dee2e6 !important; }
        
        /* Text alignment fixes */
        .text-center { text-align: center !important; }
        .text-end { text-align: right !important; }
        
        /* Display utilities */
        .d-block { display: block !important; }
        .d-flex { display: flex !important; }
        .d-inline-block { display: inline-block !important; }
        
        /* Flex utilities */
        .flex-wrap { flex-wrap: wrap !important; }
        .gap-2 { gap: 0.5rem !important; }
        .justify-content-between { justify-content: space-between !important; }
        .align-items-center { align-items: center !important; }
        
        /* Address styling */
        address {
            font-style: normal;
            line-height: 1.5;
        }
        
        /* Utility class fixes for better compatibility */
        .py-4 { 
            padding-top: 2rem !important; 
            padding-bottom: 2rem !important; 
        }
        
        .pt-3 { padding-top: 1rem !important; }
        .pt-4 { padding-top: 1.5rem !important; }
        .pb-3 { padding-bottom: 1rem !important; }
        
        /* Page break utilities for multi-page invoices */
        .page-break-before {
            page-break-before: always;
        }
        
        .page-break-after {
            page-break-after: always;
        }
        
        .page-break-avoid {
            page-break-inside: avoid;
        }
        
        /* Long content handling */
        .long-content-section {
            max-height: none;
            overflow: visible;
        }
        
        /* Multi-page table handling */
        .multi-page-table {
            page-break-after: auto;
        }
        
        .multi-page-table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa !important;
            z-index: 10;
        }
        
        /* Section spacing for page breaks */
        .section-spacing {
            margin-bottom: 2rem;
            page-break-inside: avoid;
        }
        
        /* Standard invoice spacing */
        .invoice-wrapper {
            margin: 0 auto;
            padding: 2rem 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Flexible content area */
        .invoice-content {
            flex: 1;
        }
        
        /* Icon sizing */
        .fs-1 { font-size: 2.5rem !important; }
        .fs-5 { font-size: 1.25rem !important; }
        
        /* Ensure proper line breaks in timeline badges */
        .timeline-badge small {
            font-size: 0.7rem;
            opacity: 0.9;
            display: block;
            margin-top: 0.25rem;
        }
        
        /* Fix for horizontal rules */
        hr {
            margin: 1rem 0;
            border: 0;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="invoice-wrapper">
            <!-- Print/Download Controls -->
            <div class="row mb-3 no-print">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line"></i> Back to Order
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" onclick="window.print()">
                                <i class="ri-printer-line"></i> Print Invoice
                            </button>
                            <button type="button" class="btn btn-success" onclick="downloadAsPDF()">
                                <i class="ri-download-line"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Invoice Content -->
        <div class="invoice-content compact-spacing">
            <!-- Header -->
            <div class="row invoice-header compact-section page-break-avoid">
                <div class="col-md-6">
                    <div class="company-logo">
                        @if(file_exists(public_path('assets/images/logo.png')))
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Company Logo" class="img-fluid">
                        @else
                            <h3 class="text-primary mb-0">{{ config('app.name', 'MultiVendor Marketplace') }}</h3>
                        @endif
                    </div>
                    <div class="company-details mt-3">
                        <p class="mb-1"><strong>Vendor Invoice</strong></p>
                        <p class="mb-1">{{ config('app.name', 'MultiVendor Marketplace') }}</p>
                        <p class="mb-1">support@osmartbd.com</p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <h1 class="invoice-title mb-3">INVOICE</h1>
                    <div class="invoice-info">
                        <p class="mb-1"><strong>Invoice #:</strong> VIN-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p class="mb-1"><strong>Order #:</strong> {{ $order->order_number ?? 'ORD-' . $order->id }}</p>
                        <p class="mb-0">
                            <strong>Status:</strong>
                            @php
                                $statusColors = [
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    'refunded' => 'info'
                                ];
                                $paymentColor = $statusColors[$order->payment_status ?? 'pending'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $paymentColor }}">{{ ucfirst($order->payment_status ?? 'Pending') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Vendor & Customer Info -->
            <div class="row mb-3 compact-section page-break-avoid vendor-customer-info">
                <div class="col-md-6">
                    <div class="vendor-info">
                        <h5 class="mb-3 text-primary">
                            <i class="ri-store-line"></i> Vendor Information
                        </h5>
                        <div class="vendor-details">
                            <p class="mb-1"><strong>{{ $vendor->shop_name ?? $vendor->name }}</strong></p>
                            <p class="mb-1">{{ $vendor->email }}</p>
                            @if($vendor->phone)
                                <p class="mb-1">{{ $vendor->phone }}</p>
                            @endif
                            @if($vendor->shop_address)
                                <p class="mb-0">{{ $vendor->shop_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Bill To:</h5>
                    <div class="customer-details">
                        <p class="mb-1"><strong>{{ $order->customer->name ?? ($order->shipping_name ?? 'Guest Customer') }}</strong></p>
                        <p class="mb-1">{{ $order->customer->email ?? ($order->shipping_email ?? 'N/A') }}</p>
                        @if($order->customer->phone ?? $order->shipping_phone ?? false)
                            <p class="mb-1">{{ $order->customer->phone ?? $order->shipping_phone }}</p>
                        @endif
                        
                        <!-- Shipping Address -->
                        @php
                            // Parse shipping address data - it might be JSON or individual fields
                            $shippingData = [];
                            
                            // Check if shipping_address contains JSON data
                            if ($order->shipping_address && (str_starts_with($order->shipping_address, '{') || str_starts_with($order->shipping_address, '['))) {
                                $shippingData = json_decode($order->shipping_address, true) ?? [];
                            }
                            
                            // Use JSON data if available, otherwise fall back to individual fields
                            $address = $shippingData['address'] ?? $order->shipping_address ?? '';
                            $city = $shippingData['city'] ?? $order->shipping_city ?? '';
                            $district = $shippingData['district'] ?? $order->shipping_state ?? '';
                            $postalCode = $shippingData['postal_code'] ?? $order->shipping_zip ?? '';
                            $country = $shippingData['country'] ?? $order->shipping_country ?? 'Bangladesh';
                            
                            // If address is JSON, don't show it as the address line
                            if (str_starts_with($address, '{')) {
                                $address = '';
                            }
                        @endphp
                        
                        @if($address)
                            <p class="mb-1">{{ $address }}</p>
                        @endif
                        @if($city || $district || $postalCode)
                            <p class="mb-1">
                                {{ $city }}@if($city && ($district || $postalCode)), @endif
                                {{ $district }}@if($district && $postalCode) - @endif
                                {{ $postalCode }}
                            </p>
                        @endif
                        @if($country && $country !== 'Bangladesh')
                            <p class="mb-0">{{ $country }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Status Timeline -->
            <div class="row mb-3 compact-section">
                <div class="col-12">
                    <h5 class="mb-3">Order Status</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="timeline-badge {{ $order->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                            @if($order->status == 'pending')
                                <i class="ri-time-line"></i> Pending
                            @else
                                <i class="ri-check-line"></i> Order Placed
                            @endif
                        </span>
                        
                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                            <span class="timeline-badge bg-info">
                                <i class="ri-settings-line"></i> Processing
                            </span>
                        @endif
                        
                        @if(in_array($order->status, ['shipped', 'delivered']))
                            <span class="timeline-badge bg-primary">
                                <i class="ri-truck-line"></i> Shipped
                                @if($order->tracking_number)
                                    <br><small>{{ $order->tracking_number }}</small>
                                @endif
                            </span>
                        @endif
                        
                        @if($order->status == 'delivered')
                            <span class="timeline-badge bg-success">
                                <i class="ri-checkbox-circle-line"></i> Delivered
                            </span>
                        @endif
                        
                        @if($order->status == 'cancelled')
                            <span class="timeline-badge bg-danger">
                                <i class="ri-close-circle-line"></i> Cancelled
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="row mb-3 compact-section items-section">
                <div class="col-12">
                    <h5 class="mb-3">Your Items in this Order</h5>
                    <div class="table-responsive long-content-section">
                        <table class="table table-bordered table-striped multi-page-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendorItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->product->name ?? 'Product Name' }}</strong>
                                            @if($item->product && $item->product->short_description)
                                                <br><small class="text-muted">{{ Str::limit($item->product->short_description, 100) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->product && $item->product->sku)
                                            <code>{{ $item->product->sku }}</code>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end fw-semibold">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="ri-inbox-line fs-1 mb-2 d-block"></i>
                                        No items found for your store in this order.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Total Section -->
            @if($vendorItems->count() > 0)
            <div class="row section-spacing">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="total-section page-break-avoid">
                        <h5 class="mb-3">Order Total</h5>
                        
                        <div class="row fw-bold text-primary fs-5">
                            <div class="col-7">Total Amount:</div>
                            <div class="col-5 text-end">৳{{ number_format($vendorTotal, 2) }}</div>
                        </div>
                        
                        @if($order->tax_amount > 0)
                            @php
                                $vendorTax = ($order->tax_amount / $order->total_amount) * $vendorTotal;
                            @endphp
                            <div class="row mt-2">
                                <div class="col-7 text-muted">Tax Included:</div>
                                <div class="col-5 text-end text-muted">৳{{ number_format($vendorTax, 2) }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="row mt-5 pt-4 border-top invoice-footer-section page-break-avoid">
                <div class="col-md-6">
                    <h6>Payment Information:</h6>
                    <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    <p class="mb-1"><strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Payment Date:</strong> {{ $order->paid_at ? $order->paid_at->format('M d, Y h:i A') : 'Pending' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6>Contact Support:</h6>
                    <p class="mb-1">Email: vendor-support@osmartbd.com</p>
                    <p class="mb-1">Phone: +880-1XXX-XXXXXX</p>
                    <p class="mb-0">Available: 9 AM - 6 PM (BST)</p>
                </div>
            </div>

            <div class="row mt-4 pt-3 border-top page-break-avoid">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        This is a vendor-specific invoice generated on {{ now()->format('M d, Y h:i A') }} (BST).
                        <br>For full order details, please check the complete order invoice.
                        @if($vendorItems->count() > 10)
                            <br><em>This invoice may span multiple pages due to the number of items.</em>
                        @endif
                    </small>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function downloadAsPDF() {
            // Simple print-to-PDF functionality
            window.print();
        }
        
        // Auto-focus print dialog if accessed via print parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>
