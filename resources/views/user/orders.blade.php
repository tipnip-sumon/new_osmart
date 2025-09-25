@extends('layouts.app')

@section('title', 'My Orders - ' . config('app.name'))

@push('styles')
<style>
.orders-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.order-item {
    border: 1px solid #f1f3f4;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.order-item:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-id {
    font-weight: 600;
    color: #495057;
}

.order-date {
    color: #6c757d;
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
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

.order-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f3f4;
}

.order-total {
    font-weight: 600;
    font-size: 1.1rem;
    color: #495057;
}

.order-actions {
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .order-summary {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-actions {
        align-self: stretch;
    }
    
    .order-actions .btn {
        flex: 1;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary me-3">
                        <i class="ti ti-arrow-left me-1"></i>Back
                    </a>
                    <h2 class="h3 mb-0">My Orders</h2>
                </div>
                <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>Continue Shopping
                </a>
            </div>

            <!-- Orders List -->
            <div class="orders-card card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-package me-2"></i>Order History
                    </h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <div class="order-item">
                                <div class="order-header">
                                    <div>
                                        <div class="order-id">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="order-date">{{ $order->created_at->format('M d, Y - h:i A') }}</div>
                                    </div>
                                    <span class="status-badge status-{{ strtolower($order->status ?? 'pending') }}">
                                        {{ $order->status ?? 'Pending' }}
                                    </span>
                                </div>

                                <!-- Order Items -->
                                @if($order->items && $order->items->count() > 0)
                                    <div class="order-items mb-3">
                                        @foreach($order->items->take(3) as $item)
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('assets/img/bg-img/default-product.png') }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">Qty: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <small class="text-muted">+ {{ $order->items->count() - 3 }} more items</small>
                                        @endif
                                    </div>
                                @endif

                                <div class="order-summary">
                                    <div class="order-total">
                                        Total: ৳{{ number_format($order->total_amount ?? 0, 2) }}
                                    </div>
                                    <div class="order-actions">
                                        <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-eye me-1"></i>View Details
                                        </a>
                                        @if($order->status === 'delivered')
                                            <a href="{{ route('invoice.show', $order->id) }}" class="btn btn-outline-success btn-sm">
                                                <i class="ti ti-file-text me-1"></i>Invoice
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif

                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <i class="ti ti-package-off"></i>
                            <h4>No Orders Yet</h4>
                            <p class="mb-3">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                            <a href="{{ route('shop.grid') }}" class="btn btn-primary">
                                <i class="ti ti-shopping-cart me-1"></i>Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Orders page loaded successfully');
});
</script>
@endpush
