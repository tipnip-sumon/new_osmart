@extends('admin.layouts.app')

@section('title', 'Invoice Template - INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoice Template - INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.invoices.professional', $order->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> Professional PDF
                        </a>
                        <a href="{{ route('admin.invoices.professional.download', $order->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Simple Invoice Template -->
                    <div class="invoice-template" id="invoice-content">
                        <!-- Header -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="company-logo">
                                    @if(file_exists(public_path('assets/images/logo.png')))
                                        <img src="{{ asset('assets/images/logo.png') }}" alt="Company Logo" class="img-fluid" style="max-height: 80px;">
                                    @else
                                        <h2 class="text-primary">{{ config('app.name', 'MultiVendor Marketplace') }}</h2>
                                    @endif
                                </div>
                                <div class="company-details mt-3">
                                    <p class="mb-1">789 Business Ave, Suite 100</p>
                                    <p class="mb-1">Business City, BC 12345</p>
                                    <p class="mb-1">Phone: +1 (555) 987-6543</p>
                                    <p class="mb-0">Email: invoices@multivendor.com</p>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <h1 class="text-primary mb-3">INVOICE</h1>
                                <div class="invoice-info">
                                    <p class="mb-1"><strong>Invoice #:</strong> INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                                    <p class="mb-1"><strong>Due Date:</strong> {{ $order->created_at->addDays(30)->format('M d, Y') }}</p>
                                    <p class="mb-0">
                                        <strong>Status:</strong>
                                        @switch($order->payment_status)
                                            @case('paid')
                                                <span class="badge bg-success">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Customer & Order Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Bill To:</h5>
                                <div class="customer-details">
                                    <p class="mb-1"><strong>{{ $order->customer->name ?? 'Guest Customer' }}</strong></p>
                                    <p class="mb-1">{{ $order->customer->email ?? 'N/A' }}</p>
                                    @if($order->customer->phone ?? false)
                                        <p class="mb-1">{{ $order->customer->phone }}</p>
                                    @endif
                                    
                                    @if($order->shipping_address)
                                        @php
                                            $address = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                        @endphp
                                        @if($address)
                                            @if(isset($address['street']))
                                                <p class="mb-1">{{ $address['street'] }}</p>
                                            @endif
                                            @if(isset($address['street2']) && $address['street2'])
                                                <p class="mb-1">{{ $address['street2'] }}</p>
                                            @endif
                                            @if(isset($address['city']) || isset($address['state']) || isset($address['zip']))
                                                <p class="mb-1">
                                                    {{ $address['city'] ?? '' }}
                                                    @if(isset($address['state']) && $address['state'])
                                                        , {{ $address['state'] }}
                                                    @endif
                                                    {{ $address['zip'] ?? '' }}
                                                </p>
                                            @endif
                                            @if(isset($address['country']) && $address['country'])
                                                <p class="mb-0">{{ $address['country'] }}</p>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Order Information:</h5>
                                <div class="order-details">
                                    <p class="mb-1"><strong>Order #:</strong> {{ $order->order_number ?? $order->id }}</p>
                                    <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="mb-1"><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                                    @if($order->vendor)
                                        <p class="mb-1"><strong>Vendor:</strong> {{ $order->vendor->name }}</p>
                                    @endif
                                    <p class="mb-0"><strong>Currency:</strong> BDT (Tk)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Item Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                                @if($item->product->short_description ?? false)
                                                    <br><small class="text-muted">{{ $item->product->short_description }}</small>
                                                @endif
                                                @if($item->product->sku ?? false)
                                                    <br><code class="small">SKU: {{ $item->product->sku }}</code>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Tk {{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">Tk {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No items found for this order</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Payment Instructions</h6>
                                        <p class="card-text small">
                                            Thank you for your business! This invoice reflects your recent purchase from our marketplace. 
                                            If you have any questions about this invoice, please contact our customer service team.
                                        </p>
                                        <p class="card-text small mb-0">
                                            <strong>Terms:</strong> Payment is due within 30 days. Late payments may incur additional fees.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Invoice Summary</h6>
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Subtotal:</td>
                                                    <td class="text-end">Tk {{ number_format($order->subtotal ?? ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount), 2) }}</td>
                                                </tr>
                                                @if($order->discount_amount > 0)
                                                <tr>
                                                    <td>Discount:</td>
                                                    <td class="text-end text-success">-Tk {{ number_format($order->discount_amount, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($order->tax_amount > 0)
                                                <tr>
                                                    <td>Tax:</td>
                                                    <td class="text-end">Tk {{ number_format($order->tax_amount, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($order->shipping_amount > 0)
                                                <tr>
                                                    <td>Shipping:</td>
                                                    <td class="text-end">Tk {{ number_format($order->shipping_amount, 2) }}</td>
                                                </tr>
                                                @endif
                                                <tr class="table-dark">
                                                    <td><strong>Total Amount:</strong></td>
                                                    <td class="text-end"><strong>Tk {{ number_format($order->total_amount, 2) }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="text-center text-muted small">
                                    <p class="mb-1">{{ config('app.name', 'MultiVendor Marketplace') }} - Invoice Generated on {{ now()->format('M d, Y h:i A') }}</p>
                                    <p class="mb-0">For support, contact us at <a href="mailto:support@multivendor.com">support@multivendor.com</a> or +1 (555) 987-6543</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.invoice-template {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.company-logo img {
    max-width: 200px;
    height: auto;
}

.invoice-info p,
.customer-details p,
.order-details p {
    margin-bottom: 0.5rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.table-borderless td {
    border: none !important;
    padding: 0.25rem 0.5rem;
}

@media print {
    .card-header,
    .btn-group {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
    
    .invoice-template {
        font-size: 12px;
    }
    
    .table {
        font-size: 11px;
    }
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.card.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
}

.text-primary {
    color: #0d6efd !important;
}

.table-dark {
    background-color: #212529;
    color: #fff;
}
</style>
@endpush

@push('scripts')
<script>
// Print functionality
function printInvoice() {
    window.print();
}

// Copy invoice URL
function copyInvoiceURL() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        alert('Invoice URL copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush
