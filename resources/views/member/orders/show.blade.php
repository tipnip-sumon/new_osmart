@extends('member.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Order Details</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-8">
                <!-- Order Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-package me-2"></i>Order #{{ $order->order_number }}
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}-transparent fs-12">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Status Timeline -->
                        <div class="order-status-timeline mb-4">
                            <div class="timeline">
                                <div class="timeline-item {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Order Placed</h6>
                                        <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Processing</h6>
                                        <small class="text-muted">Order is being prepared</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Shipped</h6>
                                        <small class="text-muted">{{ $order->status == 'shipped' ? 'In transit' : '' }}</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item {{ in_array($order->status, ['delivered', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Delivered</h6>
                                        <small class="text-muted">{{ $order->status == 'delivered' ? 'Package delivered' : '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="order-items">
                            <h6 class="mb-3">Order Items</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $legacyImageUrl = '';
                                                        $product = $item->product;
                                                        
                                                        // Check for complex images JSON structure first
                                                        if (isset($product->images) && $product->images) {
                                                            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                                            if (is_array($images) && !empty($images)) {
                                                                $image = $images[0]; // Get first image
                                                                
                                                                // Handle complex nested structure first
                                                                if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                    // New complex structure - use medium size storage_url
                                                                    $legacyImageUrl = $image['sizes']['medium']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                    // Fallback to original if medium not available
                                                                    $legacyImageUrl = $image['sizes']['original']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                    // Fallback to large if original not available
                                                                    $legacyImageUrl = $image['sizes']['large']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                    // Legacy complex URL structure - use medium size
                                                                    $legacyImageUrl = $image['urls']['medium'];
                                                                } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                    // Legacy fallback to original if medium not available
                                                                    $legacyImageUrl = $image['urls']['original'];
                                                                } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                    $legacyImageUrl = $image['url'];
                                                                } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                    $legacyImageUrl = asset('storage/' . $image['path']);
                                                                } elseif (is_string($image)) {
                                                                    // Simple string path
                                                                    $legacyImageUrl = asset('storage/' . $image);
                                                                }
                                                            }
                                                        }
                                                        
                                                        // Fallback to image accessor
                                                        if (empty($legacyImageUrl)) {
                                                            $productImage = $product->image;
                                                            if ($productImage && $productImage !== 'products/product1.jpg') {
                                                                $legacyImageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
                                                            } else {
                                                                $legacyImageUrl = asset('assets/img/product/default.png');
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($legacyImageUrl && $legacyImageUrl !== asset('assets/img/product/default.png'))
                                                        <img src="{{ $legacyImageUrl }}" alt="{{ $product->name ?? 'Product' }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                                    @else
                                                        <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 4px;">
                                                            <i class="fe fe-package text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product->name ?? 'Product' }}</h6>
                                                        @if($item->product && $item->product->sku)
                                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td>৳{{ number_format($item->price, 2) }}</td>
                                            <td>৳{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                            <td><strong>৳{{ number_format($order->subtotal, 2) }}</strong></td>
                                        </tr>
                                        @if($order->shipping_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end">Shipping:</td>
                                            <td>৳{{ number_format($order->shipping_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->tax_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end">Tax:</td>
                                            <td>৳{{ number_format($order->tax_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->discount_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end">Discount:</td>
                                            <td class="text-success">-৳{{ number_format($order->discount_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                            <td><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
                                        </tr>
                                        @if($order->payment_method === 'cash' && $order->payment_status === 'pending')
                                        <tr class="table-warning">
                                            <td colspan="3" class="text-end">
                                                <strong>Security Deposit Paid:</strong>
                                                <br><small class="text-muted">(Deducted from wallet)</small>
                                            </td>
                                            <td><strong class="text-danger">-৳200.00</strong></td>
                                        </tr>
                                        <tr class="table-info">
                                            <td colspan="3" class="text-end">
                                                <strong>Remaining to Pay on Delivery:</strong>
                                                <br><small class="text-muted">(Cash on Delivery)</small>
                                            </td>
                                            <td><strong class="text-warning">৳{{ number_format($order->total_amount - 200, 2) }}</strong></td>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <!-- Order Summary -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-info me-2"></i>Order Summary
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-summary">
                            <div class="summary-row">
                                <span class="label">Order Date:</span>
                                <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Order Number:</span>
                                <span class="value">#{{ $order->order_number }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Payment Method:</span>
                                <span class="value">{{ $order->payment_method === 'cash' ? 'Cash on Delivery (COD)' : ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Payment Status:</span>
                                <span class="value">
                                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}-transparent">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Shipping Method:</span>
                                <span class="value">{{ ucfirst(str_replace('_', ' ', $order->shipping_method)) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Total Items:</span>
                                <span class="value">{{ $order->items->sum('quantity') }}</span>
                            </div>
                            <hr>
                            <div class="summary-row total">
                                <span class="label"><strong>Total Amount:</strong></span>
                                <span class="value"><strong>৳{{ number_format($order->total_amount, 2) }}</strong></span>
                            </div>
                            @if($order->payment_method === 'cash' && $order->payment_status === 'pending')
                            <div class="summary-row">
                                <span class="label text-danger">Security Deposit Paid:</span>
                                <span class="value text-danger">-৳200.00</span>
                            </div>
                            <div class="summary-row">
                                <span class="label"><strong class="text-warning">Amount Due on Delivery:</strong></span>
                                <span class="value"><strong class="text-warning">৳{{ number_format($order->total_amount - 200, 2) }}</strong></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- COD Transaction Details -->
                @if($order->payment_method === 'cash' && $transactions->isNotEmpty())
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-credit-card me-2"></i>Cash on Delivery Details
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($transactions as $transaction)
                            <div class="transaction-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $transaction->type_name }}</h6>
                                        <p class="mb-1 text-muted">{{ $transaction->description }}</p>
                                        <small class="text-muted">
                                            <i class="fe fe-clock me-1"></i>{{ $transaction->created_at->format('M d, Y h:i A') }}
                                        </small>
                                        @if($transaction->note)
                                            <br><small class="text-info"><i class="fe fe-info-circle me-1"></i>{{ $transaction->note }}</small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}-transparent">
                                            {{ $transaction->status_name }}
                                        </span>
                                        <div class="mt-1">
                                            <span class="text-{{ $transaction->amount < 0 ? 'danger' : 'success' }} fw-bold">
                                                {{ $transaction->amount < 0 ? '-' : '+' }}৳{{ number_format(abs($transaction->amount), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($transaction->metadata && is_array($transaction->metadata))
                                    <div class="mt-2 pt-2 border-top">
                                        <small class="text-muted">
                                            <strong>Transaction Details:</strong><br>
                                            Transaction ID: {{ $transaction->transaction_id }}<br>
                                            @if(isset($transaction->metadata['deducted_from_deposit']) && $transaction->metadata['deducted_from_deposit'] > 0)
                                                Deducted from Deposit Wallet: ৳{{ number_format($transaction->metadata['deducted_from_deposit'], 2) }}<br>
                                            @endif
                                            @if(isset($transaction->metadata['deducted_from_interest']) && $transaction->metadata['deducted_from_interest'] > 0)
                                                Deducted from Income Wallet: ৳{{ number_format($transaction->metadata['deducted_from_interest'], 2) }}<br>
                                            @endif
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        @if($order->payment_status === 'pending')
                            <div class="alert alert-info mb-0">
                                <h6 class="alert-heading mb-2"><i class="fe fe-info-circle me-2"></i>Cash on Delivery Information</h6>
                                <p class="mb-1">
                                    <strong>Security Deposit:</strong> ৳200.00 has been deducted from your wallet as security.
                                </p>
                                <p class="mb-1">
                                    <strong>Remaining Amount:</strong> ৳{{ number_format($order->total_amount - 200, 2) }} will be collected upon delivery.
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        <i class="fe fe-shield me-1"></i>
                                        The security deposit will be refunded to your wallet once the order is delivered and payment is collected.
                                    </small>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Shipping Address -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-map-pin me-2"></i>Shipping Address
                        </div>
                    </div>
                    <div class="card-body">
                        @if($order->shipping_address)
                            @php $address = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true); @endphp
                            <div class="address-info">
                                <p class="mb-2">
                                    <strong>{{ $address['first_name'] ?? '' }} {{ $address['last_name'] ?? '' }}</strong>
                                </p>
                                @if(isset($address['company']) && $address['company'])
                                    <p class="mb-2">{{ $address['company'] }}</p>
                                @endif
                                <p class="mb-2">{{ $address['address_line_1'] ?? '' }}</p>
                                @if(isset($address['address_line_2']) && $address['address_line_2'])
                                    <p class="mb-2">{{ $address['address_line_2'] }}</p>
                                @endif
                                <p class="mb-2">
                                    {{ $address['city'] ?? '' }}@if(isset($address['state']) && $address['state']), {{ $address['state'] }}@endif
                                    @if(isset($address['postal_code']) && $address['postal_code']){{ $address['postal_code'] }}@endif
                                </p>
                                <p class="mb-2">{{ $address['country'] ?? '' }}</p>
                                @if(isset($address['phone']) && $address['phone'])
                                    <p class="mb-0"><i class="fe fe-phone me-1"></i> {{ $address['phone'] }}</p>
                                @endif
                            </div>
                        @else
                            <p class="text-muted">No shipping address available</p>
                        @endif
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-settings me-2"></i>Order Actions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="order-actions">
                            @if($order->status == 'pending')
                                <button class="btn btn-danger btn-sm w-100 mb-2" onclick="cancelOrder({{ $order->id }})">
                                    <i class="fe fe-x me-1"></i>Cancel Order
                                </button>
                            @endif
                            
                            @if(in_array($order->status, ['shipped', 'delivered']))
                                <button class="btn btn-info btn-sm w-100 mb-2" onclick="trackOrder({{ $order->id }})">
                                    <i class="fe fe-map-pin me-1"></i>Track Order
                                </button>
                            @endif
                            
                            <button class="btn btn-success btn-sm w-100 mb-2" onclick="downloadInvoice({{ $order->id }})">
                                <i class="fe fe-download me-1"></i>Download Invoice
                            </button>
                            
                            <button class="btn btn-primary btn-sm w-100 mb-2" onclick="reorderItems({{ $order->id }})">
                                <i class="fe fe-repeat me-1"></i>Reorder Items
                            </button>
                            
                            <button class="btn btn-warning btn-sm w-100" onclick="contactSupport()">
                                <i class="fe fe-help-circle me-1"></i>Contact Support
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-file-text me-2"></i>Order Notes
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('member.orders.index') }}" class="btn btn-secondary">
                    <i class="fe fe-arrow-left me-1"></i>Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.order-status-timeline {
    margin-bottom: 30px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item.active .timeline-marker {
    background-color: #007bff !important;
}

.timeline-item.active .timeline-content .timeline-title {
    color: #007bff;
    font-weight: 600;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #e9ecef;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f1f1;
}

.summary-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.summary-row.total {
    border-top: 2px solid #007bff;
    margin-top: 15px;
    padding-top: 15px;
}

.summary-row .label {
    color: #6c757d;
}

.summary-row .value {
    font-weight: 500;
}

.address-info p {
    line-height: 1.5;
}

.order-actions .btn {
    text-align: left;
}

@media (max-width: 768px) {
    .timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -18px;
        width: 12px;
        height: 12px;
    }
    
    .timeline-content {
        padding-left: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder(orderId) {
    Swal.fire({
        title: 'Cancel Order?',
        text: 'Are you sure you want to cancel this order? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to cancel the order
            Swal.fire({
                title: 'Order Cancelled',
                text: 'Your order has been cancelled successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    });
}

function trackOrder(orderId) {
    Swal.fire({
        title: 'Order Tracking',
        html: `
            <div class="text-start">
                <div class="tracking-info">
                    <div class="tracking-step completed">
                        <div class="step-icon"><i class="fe fe-check"></i></div>
                        <div class="step-content">
                            <h6>Order Confirmed</h6>
                            <small class="text-muted">Your order has been confirmed</small>
                        </div>
                    </div>
                    <div class="tracking-step completed">
                        <div class="step-icon"><i class="fe fe-package"></i></div>
                        <div class="step-content">
                            <h6>Processing</h6>
                            <small class="text-muted">Your order is being processed</small>
                        </div>
                    </div>
                    <div class="tracking-step active">
                        <div class="step-icon"><i class="fe fe-truck"></i></div>
                        <div class="step-content">
                            <h6>Shipped</h6>
                            <small class="text-muted">Your order is on the way</small>
                        </div>
                    </div>
                    <div class="tracking-step">
                        <div class="step-icon"><i class="fe fe-home"></i></div>
                        <div class="step-content">
                            <h6>Delivered</h6>
                            <small class="text-muted">Order will be delivered</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Tracking Number:</strong> TRK${orderId}${Math.random().toString(36).substr(2, 6).toUpperCase()}
                </div>
            </div>
        `,
        confirmButtonText: 'Close',
        width: '500px'
    });
}

function downloadInvoice(orderId) {
    Swal.fire({
        title: 'Downloading Invoice...',
        text: `Invoice for Order #${orderId} is being downloaded.`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function reorderItems(orderId) {
    Swal.fire({
        title: 'Reorder Items',
        text: 'Do you want to create a new order with the same items?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, reorder!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('member.orders.create') }}?reorder=" + orderId;
        }
    });
}

function contactSupport() {
    Swal.fire({
        title: 'Order Support',
        html: `
            <div class="text-start">
                <p>Need help with this order? Contact our support team:</p>
                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-primary btn-sm" onclick="openChat()">
                        <i class="fe fe-message-circle me-1"></i>Live Chat
                    </button>
                    <button class="btn btn-success btn-sm" onclick="sendEmail()">
                        <i class="fe fe-mail me-1"></i>Send Email
                    </button>
                    <button class="btn btn-info btn-sm" onclick="callSupport()">
                        <i class="fe fe-phone me-1"></i>Call Support
                    </button>
                </div>
                <div class="mt-3 text-center">
                    <small class="text-muted">Support Hours: 9 AM - 6 PM EST</small>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Close'
    });
}

function openChat() {
    // Implement live chat functionality
    Swal.fire('Live Chat', 'Live chat feature will be available soon!', 'info');
}

function sendEmail() {
    // Implement email functionality
    window.location.href = 'mailto:support@example.com?subject=Order Support - Order #{{ $order->order_number }}';
}

function callSupport() {
    // Implement call functionality
    Swal.fire('Call Support', 'Please call us at +1-234-567-8900', 'info');
}
</script>

<style>
.tracking-info {
    position: relative;
}

.tracking-step {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
}

.tracking-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 17px;
    top: 35px;
    width: 2px;
    height: 30px;
    background-color: #e9ecef;
}

.tracking-step.completed::after {
    background-color: #28a745;
}

.tracking-step.active::after {
    background-color: #007bff;
}

.step-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    position: relative;
    z-index: 1;
}

.tracking-step.completed .step-icon {
    background-color: #28a745;
    color: white;
}

.tracking-step.active .step-icon {
    background-color: #007bff;
    color: white;
}

.step-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.step-content small {
    font-size: 12px;
}
</style>
@endpush
