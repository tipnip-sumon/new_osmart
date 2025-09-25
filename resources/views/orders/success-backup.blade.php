@extends('layouts.app')

@section('title', 'Order Success - ' . config('app.name'))

@push('styles')
<style>
.success-container {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    padding: 3rem 1rem;
}

.success-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(45deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    animation: successPulse 2s ease-in-out infinite;
}

.success-icon i {
    font-size: 3rem;
    color: white;
}

@keyframes successPulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 20px rgba(16, 185, 129, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

.success-title {
    color: #10b981;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.success-message {
    font-size: 1.125rem;
    color: #6b7280;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.order-details {
    background: #f8f9fa;
    border-radius: 0.75rem;
    padding: 2rem;
    margin: 2rem 0;
    border: 1px solid #e5e7eb;
}

.order-number {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.order-info {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.order-items {
    background: #f9fafb;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
}

.order-items h6 {
    color: #111827;
    margin-bottom: 1rem;
    font-weight: 600;
}

.items-list {
    background: white;
    border-radius: 0.5rem;
    overflow: hidden;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f3f4f6;
}

.item-row:last-child {
    border-bottom: none;
}

.item-details {
    display: flex;
    flex-direction: column;
}

.item-name {
    font-weight: 500;
    color: #111827;
}

.item-quantity {
    font-size: 0.875rem;
    color: #6b7280;
}

.item-price {
    font-weight: 600;
    color: #059669;
}

.total-row {
    padding: 1rem;
    background: #f9fafb;
    text-align: right;
    border-top: 2px solid #e5e7eb;
    font-size: 1.125rem;
    color: #059669;
}

.next-steps {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin: 2rem 0;
}

.next-steps h6 {
    color: #1e40af;
    margin-bottom: 1rem;
}

.next-steps ul {
    text-align: left;
    color: #374151;
    margin: 0;
    padding-left: 1.5rem;
}

.next-steps li {
    margin-bottom: 0.5rem;
}

.action-buttons {
    margin-top: 2rem;
}

.btn-primary {
    background: linear-gradient(45deg, #6366f1, #8b5cf6);
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
}

.btn-outline-secondary {
    border: 1px solid #d1d5db;
    color: #6b7280;
    padding: 0.875rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

@media (max-width: 767.98px) {
    .success-container {
        padding: 2rem 1rem;
    }
    
    .success-title {
        font-size: 2rem;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
    }
    
    .success-icon i {
        font-size: 2.5rem;
    }
    
    .order-details {
        padding: 1.5rem;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-xl py-4">
    <div class="success-container">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="ti ti-check"></i>
        </div>

        <!-- Success Message -->
        <h1 class="success-title">Order Placed Successfully!</h1>
        <p class="success-message">
            Thank you for your order! We've received your order and will start processing it right away.
            You'll receive a confirmation email shortly.
        </p>

        <!-- Order Details -->
        @if($orderNumber)
            <div class="order-details">
                <div class="order-number">Order Number: {{ $orderNumber }}</div>
                @if($order)
                    <div class="order-info">
                        <i class="ti ti-calendar me-2"></i>
                        Order Date: {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
                    <div class="order-info">
                        <i class="ti ti-currency-taka me-2"></i>
                        Total Amount: ৳{{ number_format($order->total_amount, 2) }}
                    </div>
                    <div class="order-info">
                        <i class="ti ti-credit-card me-2"></i>
                        Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                    </div>
                    <div class="order-info">
                        <i class="ti ti-truck me-2"></i>
                        Estimated Delivery: {{ $order->created_at->addDays(3)->format('F j, Y') }} - {{ $order->created_at->addDays(7)->format('F j, Y') }}
                    </div>
                @else
                    <div class="order-info">
                        <i class="ti ti-calendar me-2"></i>
                        Order Date: {{ date('F j, Y \a\t g:i A') }}
                    </div>
                    <div class="order-info">
                        <i class="ti ti-truck me-2"></i>
                        Estimated Delivery: {{ date('F j, Y', strtotime('+3 days')) }} - {{ date('F j, Y', strtotime('+7 days')) }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Order Items -->
        @if($order && $order->items->count() > 0)
            <div class="order-items mt-4">
                <h6><i class="ti ti-package me-2"></i>Order Items</h6>
                <div class="items-list">
                    @foreach($order->items as $item)
                        <div class="item-row">
                            <div class="item-details">
                                <span class="item-name">{{ $item->product->name ?? 'Product #' . $item->product_id }}</span>
                                <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                            </div>
                            <div class="item-price">
                                ৳{{ number_format($item->total, 2) }}
                            </div>
                        </div>
                    @endforeach
                    <div class="total-row">
                        <strong>Total: ৳{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        @endif

        <!-- Next Steps -->
        <div class="next-steps">
            <h6><i class="ti ti-info-circle me-2"></i>What happens next?</h6>
            <ul>
                <li>You'll receive an order confirmation email shortly</li>
                <li>We'll prepare your order and notify you when it's ready to ship</li>
                <li>Track your order status in your account</li>
                <li>Enjoy your purchase!</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('shop.grid') }}" class="btn btn-primary me-3">
                <i class="ti ti-shopping-bag me-2"></i>Continue Shopping
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="ti ti-home me-2"></i>Back to Home
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-4">
            <small class="text-muted">
                Need help? Contact us at 
                <a href="mailto:support@example.com" class="text-decoration-none">support@example.com</a>
                or call 
                <a href="tel:+8801234567890" class="text-decoration-none">+880 123-456-7890</a>
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count to 0 since order is placed
    if (window.updateCartCount) {
        setTimeout(() => {
            window.updateCartCount();
        }, 1000);
    }
    
    // Add some celebration effect
    setTimeout(() => {
        // You could add confetti or other celebration effects here
        console.log('🎉 Order placed successfully!');
    }, 500);
});
</script>
@endpush
