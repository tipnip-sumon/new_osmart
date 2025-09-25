@extends('layouts.ecomus')

@section('title', 'Order Placed Successfully - '.config('app.name'))

@section('content')
<div class="tf-page-title" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); padding: 80px 0;">
    <div class="container-full">
        <div class="heading text-center text-white">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✓</div>
            <h1 style="font-size: 2.5rem; font-weight: 600; margin-bottom: 0.5rem;">Order Confirmed!</h1>
            <p style="font-size: 1.2rem; opacity: 0.9;">Thank you for your purchase</p>
        </div>
    </div>
</div>

<!-- Order Success Section -->
<section class="flat-spacing-11">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="tf-page-cart-checkout">
                    <!-- Success Message -->
                    <div class="text-center mb-5">
                        <div class="success-icon mb-4" style="width: 80px; height: 80px; background: #4CAF50; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; animation: pulse 2s infinite;">
                            <i class="fas fa-check" style="font-size: 2rem; color: white;"></i>
                        </div>
                        <h3 class="fw-6 mb-3" style="color: #333;">Your order has been placed successfully!</h3>
                        <p class="text-muted mb-4">We've sent an order confirmation email to your registered email address.</p>
                    </div>

                    @if(isset($order))
                    <!-- Order Details Card -->
                    <div class="order-summary-card" style="background: #f8f9fa; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; border: 1px solid #e9ecef;">
                        <h5 class="fw-6 mb-4" style="color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 0.5rem;">Order Summary</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Order Number:</span>
                                        <span class="fw-6" style="color: #333; font-family: monospace; background: #fff; padding: 4px 8px; border-radius: 4px; border: 1px solid #ddd;">#{{ $order->order_number ?? 'ORD-' . date('Ymd') . '-' . str_pad(1, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Order Date:</span>
                                        <span class="fw-5" style="color: #333;">{{ isset($order) ? $order->created_at->format('M d, Y') : date('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Payment Method:</span>
                                        <span class="fw-5" style="color: #333;">
                                            @if(isset($order->payment_method))
                                                {{ ucfirst($order->payment_method) }}
                                            @else
                                                Cash on Delivery
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Customer Email:</span>
                                        <span class="fw-5" style="color: #333;">{{ Auth::check() ? Auth::user()->email : 'customer@example.com' }}</span>
                                    </div>
                                </div>
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Status:</span>
                                        <span class="badge" style="background: #4CAF50; color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">{{ isset($order->status) ? ucfirst($order->status) : 'Processing' }}</span>
                                    </div>
                                </div>
                                <div class="order-info-item mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-5" style="color: #666;">Delivery:</span>
                                        <span class="fw-5" style="color: #333;">5-7 Business Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown-card" style="background: white; border-radius: 12px; padding: 2rem; border: 1px solid #e9ecef; margin-bottom: 2rem;">
                        <h5 class="fw-6 mb-4" style="color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 0.5rem;">Price Breakdown</h5>
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fs-16" style="color: #666;">Subtotal</div>
                            <span class="fw-5" style="color: #333;">৳{{ isset($order->subtotal) ? number_format($order->subtotal, 2) : '0.00' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fs-16" style="color: #666;">Shipping Cost</div>
                            <span class="fw-5" style="color: #333;">৳{{ isset($order->shipping_cost) ? number_format($order->shipping_cost, 2) : '0.00' }}</span>
                        </div>
                        @if(isset($order->tax_amount) && $order->tax_amount > 0)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="fs-16" style="color: #666;">Tax</div>
                            <span class="fw-5" style="color: #333;">৳{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex align-items-center justify-content-between mb-4" style="border-top: 2px solid #e9ecef; padding-top: 1rem; margin-top: 1rem;">
                            <div class="fs-20 fw-6" style="color: #333;">Total Amount</div>
                            <span class="fw-6 fs-20" style="color: #4CAF50;">৳{{ isset($order->total_amount) ? number_format($order->total_amount, 2) : '0.00' }}</span>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if(isset($order->shipping_address))
                    <div class="shipping-address-card" style="background: #f8f9fa; border-radius: 12px; padding: 2rem; margin-bottom: 2rem; border: 1px solid #e9ecef;">
                        <h5 class="fw-6 mb-3" style="color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 0.5rem;">Shipping Address</h5>
                        <div style="color: #666; line-height: 1.6;">
                            <p class="mb-1 fw-5" style="color: #333;">{{ $order->shipping_name ?? (Auth::check() ? Auth::user()->name : 'Customer') }}</p>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                            <p class="mb-1">Phone: {{ $order->shipping_phone ?? (Auth::check() ? Auth::user()->phone : 'N/A') }}</p>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="{{ route('home') }}" class="tf-btn btn-outline animate-hover-btn rounded-0 justify-content-center flex-grow-1" style="border-color: #4CAF50; color: #4CAF50;">
                            <span>Continue Shopping</span>
                        </a>
                        @auth
                        <a href="#" class="tf-btn btn-fill animate-hover-btn radius-3 justify-content-center flex-grow-1" style="background: #4CAF50; border-color: #4CAF50;">
                            <span>View Order Details</span>
                        </a>
                        @endauth
                    </div>

                    <!-- What's Next Section -->
                    <div class="whats-next-section text-center" style="margin-top: 3rem; padding: 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px;">
                        <h5 class="fw-6 mb-3" style="color: #333;">What happens next?</h5>
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="step-icon" style="width: 60px; height: 60px; background: #4CAF50; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope" style="color: white; font-size: 1.5rem;"></i>
                                </div>
                                <h6 class="fw-5" style="color: #333;">Order Confirmation</h6>
                                <p class="text-muted small">You'll receive an email confirmation shortly</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="step-icon" style="width: 60px; height: 60px; background: #FF9800; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box" style="color: white; font-size: 1.5rem;"></i>
                                </div>
                                <h6 class="fw-5" style="color: #333;">Order Processing</h6>
                                <p class="text-muted small">We'll prepare your order within 24 hours</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="step-icon" style="width: 60px; height: 60px; background: #2196F3; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-truck" style="color: white; font-size: 1.5rem;"></i>
                                </div>
                                <h6 class="fw-5" style="color: #333;">On the Way</h6>
                                <p class="text-muted small">Your order will be delivered in 5-7 days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Support Section -->
<section class="customer-support-section" style="background: #4CAF50; color: white; padding: 4rem 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h4 class="fw-6 mb-3">Need Help with Your Order?</h4>
                <p class="mb-4" style="opacity: 0.9;">Our customer support team is here to assist you with any questions or concerns.</p>
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="tel:+8801XXXXXXXXX" class="tf-btn btn-outline text-white border-white animate-hover-btn">
                        <i class="fas fa-phone me-2"></i>
                        <span>Call Support</span>
                    </a>
                    <a href="mailto:support@tipnip.com" class="tf-btn btn-outline text-white border-white animate-hover-btn">
                        <i class="fas fa-envelope me-2"></i>
                        <span>Email Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
    }
    70% {
        box-shadow: 0 0 0 20px rgba(76, 175, 80, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
    }
}

.success-icon {
    animation: pulse 2s infinite;
}

.tf-btn {
    padding: 12px 24px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid transparent;
}

.tf-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.tf-btn.btn-fill {
    background-color: #4CAF50;
    color: white;
    border-color: #4CAF50;
}

.tf-btn.btn-outline {
    background-color: transparent;
    border-color: #4CAF50;
    color: #4CAF50;
}

.tf-btn.btn-outline:hover {
    background-color: #4CAF50;
    color: white;
}

.order-summary-card,
.price-breakdown-card,
.shipping-address-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}

.order-summary-card:hover,
.price-breakdown-card:hover,
.shipping-address-card:hover {
    transform: translateY(-2px);
}

.step-icon {
    transition: transform 0.3s ease;
}

.step-icon:hover {
    transform: scale(1.1);
}

.flat-spacing-11 {
    padding: 80px 0;
}

.container-full {
    width: 100%;
    padding: 0 15px;
}

@media (max-width: 768px) {
    .tf-page-title {
        padding: 60px 0 !important;
    }
    
    .tf-page-title .heading h1 {
        font-size: 2rem !important;
    }
    
    .flat-spacing-11 {
        padding: 60px 0;
    }
    
    .order-summary-card,
    .price-breakdown-card,
    .shipping-address-card {
        padding: 1.5rem !important;
    }
    
    .whats-next-section {
        padding: 1.5rem !important;
        margin-top: 2rem !important;
    }
    
    .customer-support-section {
        padding: 3rem 0 !important;
    }
}
</style>

<script>
// Add some interactive animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate the success icon on load
    const successIcon = document.querySelector('.success-icon');
    if (successIcon) {
        setTimeout(() => {
            successIcon.style.transform = 'scale(1.1)';
            setTimeout(() => {
                successIcon.style.transform = 'scale(1)';
            }, 200);
        }, 500);
    }
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.order-summary-card, .price-breakdown-card, .shipping-address-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
        });
    });
});
</script>
@endsection