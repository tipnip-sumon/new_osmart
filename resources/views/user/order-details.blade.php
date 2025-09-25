@extends('layouts.app')

@section('title', 'Order Details - ' . config('app.name'))

@push('styles')
<style>
.order-details-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.order-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px 15px 0 0;
    color: white;
    padding: 2rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cce7ff;
    color: #004085;
}

.status-shipped {
    background: #d4edda;
    color: #155724;
}

.status-delivered {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.order-item:last-child {
    border-bottom: none;
}

.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.product-details {
    flex-grow: 1;
}

.product-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.product-price {
    color: #6c757d;
    font-size: 0.875rem;
}

.order-summary-table {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.summary-row:last-child {
    margin-bottom: 0;
    font-weight: 600;
    font-size: 1.1rem;
    padding-top: 0.5rem;
    border-top: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        margin-right: 0;
        align-self: center;
    }
    
    .order-header {
        padding: 1.5rem;
    }
    
    .order-header h3 {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary me-3">
                    <i class="ti ti-arrow-left me-1"></i>Back to Orders
                </a>
                <h2 class="h3 mb-0">Order Details</h2>
            </div>

            <!-- Order Header -->
            <div class="order-details-card card">
                <div class="order-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-2">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                            <p class="mb-0 opacity-75">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <span class="status-badge status-{{ strtolower($order->status ?? 'pending') }}">
                                {{ $order->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="ti ti-package me-2"></i>Order Items
                    </h5>
                    
                    @if($order->items && $order->items->count() > 0)
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('assets/img/bg-img/default-product.png') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="product-image">
                                <div class="product-details">
                                    <div class="product-name">{{ $item->product->name }}</div>
                                    <div class="product-price">
                                        Quantity: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}
                                    </div>
                                    @if($item->product->description)
                                        <div class="text-muted small">{{ Str::limit($item->product->description, 100) }}</div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">৳{{ number_format($item->quantity * $item->price, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-package-off" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="text-muted mt-2">No items found for this order.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-details-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-calculator me-2"></i>Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="order-summary-table">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>৳{{ number_format($order->subtotal ?? $order->total_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>৳{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>৳{{ number_format($order->tax_amount ?? 0, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="summary-row text-success">
                                <span>Discount:</span>
                                <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row">
                            <span>Total:</span>
                            <span>৳{{ number_format($order->total_amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping & Billing Info -->
            <div class="row">
                <!-- Shipping Address -->
                <div class="col-md-6">
                    <div class="order-details-card card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ti ti-truck me-2"></i>Shipping Address
                            </h6>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->shipping_name ?? $order->customer->first_name . ' ' . $order->customer->last_name }}</strong><br>
                                {{ $order->shipping_address ?? $order->customer->address ?? 'Address not provided' }}<br>
                                {{ $order->shipping_city ?? 'City not provided' }}<br>
                                {{ $order->shipping_phone ?? $order->customer->phone ?? 'Phone not provided' }}
                            </address>
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="col-md-6">
                    <div class="order-details-card card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ti ti-file-invoice me-2"></i>Billing Address
                            </h6>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->billing_name ?? $order->customer->first_name . ' ' . $order->customer->last_name }}</strong><br>
                                {{ $order->billing_address ?? $order->shipping_address ?? $order->customer->address ?? 'Address not provided' }}<br>
                                {{ $order->billing_city ?? $order->shipping_city ?? 'City not provided' }}<br>
                                {{ $order->billing_phone ?? $order->shipping_phone ?? $order->customer->phone ?? 'Phone not provided' }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="text-center mt-4">
                @if($order->status === 'delivered')
                    <a href="{{ route('invoice.show', $order->id) }}" class="btn btn-primary me-2">
                        <i class="ti ti-file-text me-1"></i>Download Invoice
                    </a>
                @endif
                
                @if(in_array($order->status, ['pending', 'processing']))
                    <button type="button" class="btn btn-outline-danger" onclick="confirmCancelOrder()">
                        <i class="ti ti-x me-1"></i>Cancel Order
                    </button>
                @endif
                
                <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-list me-1"></i>View All Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCancelOrder() {
    if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        // Here you would implement the cancel order functionality
        alert('Order cancellation functionality will be implemented here.');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Order details page loaded successfully');
});
</script>
@endpush
