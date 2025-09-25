@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@push('styles')
<style>
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
}

.section-card {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.section-header {
    background: #f8f9fa;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 0.75rem 0.75rem 0 0;
}

.section-content {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.order-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 0.5rem;
    margin-right: 1rem;
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}

.item-meta {
    color: #6b7280;
    font-size: 0.875rem;
}

.item-total {
    font-weight: 600;
    color: #111827;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.summary-row:last-child {
    border-bottom: none;
    font-size: 1.125rem;
    font-weight: 700;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid #e5e7eb;
}

.place-order-btn {
    background: linear-gradient(45deg, #10b981, #059669);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 1.125rem;
    width: 100%;
    transition: all 0.3s ease;
}

.place-order-btn:hover {
    background: linear-gradient(45deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}

.payment-method {
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #6366f1;
}

.payment-method.selected {
    border-color: #6366f1;
    background: #f0f9ff;
}

.payment-method input[type="radio"] {
    margin-right: 0.75rem;
}

.shipping-method {
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.shipping-method:hover {
    border-color: #10b981;
}

.shipping-method.selected {
    border-color: #10b981;
    background: #f0fdf4;
}

.shipping-method input[type="radio"] {
    margin-right: 0.75rem;
}

.mobile-payment-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-payment-option:hover .border {
    border-color: #6366f1 !important;
}

.mobile-payment-option.selected .border {
    border-color: #6366f1 !important;
    background: #f0f9ff;
}

.mobile-payment-option input[type="radio"] {
    display: none;
}

.coupon-card {
    transition: all 0.3s ease;
}

.coupon-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.coupon-card.border-success:hover {
    border-color: #198754 !important;
    background-color: #f8fff9;
}

#apply-coupon-btn {
    transition: all 0.3s ease;
}

#apply-coupon-btn:disabled {
    opacity: 0.6;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.checkout-option-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.checkout-option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.checkout-option-card.active {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.checkout-option-card.active .border {
    border-color: #6366f1 !important;
    background: #f0f9ff !important;
}

.checkout-option-card input[type="radio"] {
    display: none;
}

@media (max-width: 767.98px) {
    .checkout-container {
        padding: 0 1rem;
    }
    
    .section-content {
        padding: 1rem;
    }
    
    .section-header {
        padding: 1rem;
    }
    
    .item-image {
        width: 50px;
        height: 50px;
        margin-right: 0.75rem;
    }
    
    .form-control {
        padding: 0.625rem 0.75rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-xl py-4">
    <div class="checkout-container">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Checkout</h2>
                    <div class="page-pretitle">
                        Complete your order
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Back to Cart
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Checkout Form -->
            <div class="col-lg-8">
                <form id="checkoutForm">
                    @csrf
                    
                    @auth
                    <!-- User is logged in - Show welcome message -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-user-check me-2"></i>Welcome Back!
                            </h5>
                        </div>
                        <div class="section-content">
                            <div class="alert alert-success">
                                <i class="ti ti-check-circle me-2"></i>
                                You are logged in as: <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong> ({{ Auth::user()->email }})
                            </div>
                            <p class="text-muted mb-0">Proceed with your checkout below.</p>
                        </div>
                    </div>
                    @else
                    <!-- Guest Checkout vs Login Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-login me-2"></i>Checkout Options
                            </h5>
                        </div>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info mb-3">
                                        <i class="ti ti-info-circle me-2"></i>
                                        Choose how you'd like to checkout
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Guest Checkout Option -->
                                <div class="col-md-6">
                                    <div class="checkout-option-card active" id="guest-checkout-option" onclick="selectCheckoutType('guest')">
                                        <div class="text-center p-4 border rounded">
                                            <div class="mb-3">
                                                <i class="ti ti-user-check fs-1 text-primary"></i>
                                            </div>
                                            <h6 class="mb-2">Guest Checkout</h6>
                                            <p class="text-muted mb-3">Quick checkout without creating an account</p>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="radio" name="checkout_type" value="guest" checked class="me-2">
                                                <span class="badge bg-success">Faster</span>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    ✓ No account required<br>
                                                    ✓ Quick checkout process<br>
                                                    ✓ Order tracking via email
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User Registration Option -->
                                <div class="col-md-6">
                                    <div class="checkout-option-card" id="user-registration-option" onclick="selectCheckoutType('register')">
                                        <div class="text-center p-4 border rounded">
                                            <div class="mb-3">
                                                <i class="ti ti-user-plus fs-1 text-success"></i>
                                            </div>
                                            <h6 class="mb-2">Create Account</h6>
                                            <p class="text-muted mb-3">Register for better shopping experience</p>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="radio" name="checkout_type" value="register" class="me-2">
                                                <span class="badge bg-primary">Recommended</span>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    ✓ Order history tracking<br>
                                                    ✓ Faster future checkouts<br>
                                                    ✓ Exclusive member discounts<br>
                                                    ✓ Wishlist & favorites
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Existing User Login -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="text-center">
                                        <p class="mb-2">Already have an account?</p>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                            <i class="ti ti-login me-2"></i>Login to Your Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Account Creation Form (Hidden by default) -->
                            <div id="account-creation-form" class="mt-4" style="display: none;">
                                <div class="alert alert-success">
                                    <h6 class="mb-2">
                                        <i class="ti ti-user-plus me-2"></i>Create Your Account
                                    </h6>
                                    <p class="mb-0">Fill in your details below to create an account and complete your purchase.</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Username *</label>
                                            <input type="text" class="form-control" name="username" id="registration-username" required>
                                            <small class="text-muted">Choose a unique username (letters, numbers, underscore only)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Create Password *</label>
                                            <input type="password" class="form-control" name="password" id="registration-password" required>
                                            <small class="text-muted">Minimum 8 characters</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password *</label>
                                            <input type="password" class="form-control" name="password_confirmation" id="password-confirmation" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subscribe_newsletter" id="subscribe-newsletter" checked>
                                            <label class="form-check-label" for="subscribe-newsletter">
                                                Subscribe to our newsletter for exclusive offers and updates
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="agree_terms" id="agree-terms" required>
                                            <label class="form-check-label" for="agree-terms">
                                                I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a> *
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endauth
                    
                    <!-- Billing Information -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-user me-2"></i>Billing Information
                                @auth
                                    <small class="text-muted ms-2">(Auto-filled from your account)</small>
                                @endauth
                            </h5>
                        </div>
                        <div class="section-content">
                            @auth
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-2"></i>
                                Your billing information has been pre-filled from your account. You can update it if needed.
                            </div>
                            @endauth
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name @guest*@endguest</label>
                                        <input type="text" class="form-control" name="first_name" 
                                               value="@auth{{ Auth::user()->first_name ?? '' }}@endauth"
                                               @guest required @endguest>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name @guest*@endguest</label>
                                        <input type="text" class="form-control" name="last_name" 
                                               value="@auth{{ Auth::user()->last_name ?? '' }}@endauth"
                                               @guest required @endguest>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Email Address @guest*@endguest</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="@auth{{ Auth::user()->email ?? '' }}@endauth"
                                               @guest required @endguest
                                               @auth readonly @endauth>
                                        @auth
                                        <small class="text-muted">Email cannot be changed from checkout</small>
                                        @endauth
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number @guest*@endguest</label>
                                        <input type="tel" class="form-control" name="phone" 
                                               value="@auth{{ Auth::user()->phone ?? '' }}@endauth"
                                               @guest required @endguest>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Address @guest*@endguest</label>
                                        <input type="text" class="form-control" name="address" 
                                               value="@auth{{ Auth::user()->address ?? '' }}@endauth"
                                               @guest required @endguest>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">City @guest*@endguest</label>
                                        <input type="text" class="form-control" name="city" 
                                               value="@auth{{ Auth::user()->city ?? '' }}@endauth"
                                               @guest required @endguest>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Area/District @guest*@endguest</label>
                                        <select class="form-control" name="area" id="area-select" 
                                                @guest required @endguest onchange="updateShippingOptions()">
                                            <option value="">Select Area</option>
                                            <option value="dhaka" @auth @if(Auth::user()->state == 'dhaka') selected @endif @endauth>Dhaka Division</option>
                                            <option value="chittagong" @auth @if(Auth::user()->state == 'chittagong') selected @endif @endauth>Chittagong Division</option>
                                            <option value="rajshahi" @auth @if(Auth::user()->state == 'rajshahi') selected @endif @endauth>Rajshahi Division</option>
                                            <option value="khulna" @auth @if(Auth::user()->state == 'khulna') selected @endif @endauth>Khulna Division</option>
                                            <option value="barisal" @auth @if(Auth::user()->state == 'barisal') selected @endif @endauth>Barisal Division</option>
                                            <option value="sylhet" @auth @if(Auth::user()->state == 'sylhet') selected @endif @endauth>Sylhet Division</option>
                                            <option value="rangpur" @auth @if(Auth::user()->state == 'rangpur') selected @endif @endauth>Rangpur Division</option>
                                            <option value="mymensingh" @auth @if(Auth::user()->state == 'mymensingh') selected @endif @endauth>Mymensingh Division</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="state" 
                                               value="@auth{{ Auth::user()->state ?? '' }}@endauth">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" name="zip_code" 
                                               value="@auth{{ Auth::user()->postal_code ?? '' }}@endauth">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-truck me-2"></i>Shipping Method
                            </h5>
                        </div>
                        <div class="section-content">
                            <div id="shipping-notice" class="alert alert-info mb-3" style="display: none;">
                                <i class="ti ti-info-circle me-2"></i>
                                <span id="shipping-notice-text">Please select your area to see available shipping options.</span>
                            </div>
                            
                            <div id="shipping-options">
                                <div class="shipping-method" id="inside-dhaka-shipping" onclick="selectShipping('inside_dhaka', 60)" style="display: none;">
                                    <input type="radio" name="shipping_method" value="inside_dhaka">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Inside Dhaka/City</strong>
                                            <div class="text-muted mt-1">Fast delivery within Dhaka city (1-2 days)</div>
                                            <small class="text-info" id="dhaka-free-shipping-notice" style="display: none;">
                                                <i class="ti ti-gift me-1"></i>Free shipping on orders over ৳500
                                            </small>
                                        </div>
                                        <div class="fw-bold" id="dhaka-shipping-cost">৳60</div>
                                    </div>
                                </div>
                                
                                <div class="shipping-method" id="outside-dhaka-shipping" onclick="selectShipping('outside_dhaka', 120)" style="display: none;">
                                    <input type="radio" name="shipping_method" value="outside_dhaka">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Outside Dhaka/City</strong>
                                            <div class="text-muted mt-1">Delivery outside Dhaka city (3-5 days)</div>
                                            <small class="text-info" id="outside-dhaka-free-shipping-notice" style="display: none;">
                                                <i class="ti ti-gift me-1"></i>Free shipping on orders over ৳1,500
                                            </small>
                                        </div>
                                        <div class="fw-bold" id="outside-dhaka-shipping-cost">৳120</div>
                                    </div>
                                </div>
                                
                                <div class="shipping-method" id="across-country-shipping" onclick="selectShipping('across_country', 150)" style="display: none;">
                                    <input type="radio" name="shipping_method" value="across_country">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Across the Country</strong>
                                            <div class="text-muted mt-1">Nationwide delivery service (5-7 days)</div>
                                            <small class="text-warning">
                                                <i class="ti ti-alert-triangle me-1"></i>No free shipping available for this option
                                            </small>
                                        </div>
                                        <div class="fw-bold" id="country-shipping-cost">৳150</div>
                                    </div>
                                </div>
                                
                                <div class="shipping-method" id="free-shipping-option" onclick="selectShipping('free', 0)" style="display: none;">
                                    <input type="radio" name="shipping_method" value="free">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Free Shipping</strong>
                                            <div class="text-muted mt-1" id="free-shipping-description">Free shipping on eligible orders (3-5 days)</div>
                                            <small class="text-success">
                                                <i class="ti ti-check me-1"></i><span id="free-shipping-reason">Your order qualifies for free shipping!</span>
                                            </small>
                                        </div>
                                        <div class="fw-bold text-success">FREE</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-credit-card me-2"></i>Payment Method
                            </h5>
                        </div>
                        <div class="section-content">
                            <div class="payment-method selected" onclick="selectPayment('cash_on_delivery')">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-cash fs-4 me-3 text-success"></i>
                                    <div>
                                        <strong>Cash on Delivery</strong>
                                        <div class="text-muted mt-1">Pay when you receive your order</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Banking Options -->
                            <div class="payment-method" onclick="selectPayment('online_payment')">
                                <input type="radio" name="payment_method" value="online_payment">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-device-mobile fs-4 me-3 text-primary"></i>
                                    <div>
                                        <strong>Mobile Banking</strong>
                                        <div class="text-muted mt-1">Pay with bKash, Nagad, or Rocket</div>
                                    </div>
                                </div>
                                
                                <!-- Mobile Banking Sub-options -->
                                <div id="mobile-banking-options" class="mt-3" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <div class="mobile-payment-option" onclick="selectMobilePayment('bkash')">
                                                <input type="radio" name="online_payment_type" value="bkash">
                                                <div class="text-center p-3 border rounded">
                                                    <div class="mb-2" style="color: #E2136E;">
                                                        <i class="ti ti-device-mobile fs-3"></i>
                                                    </div>
                                                    <strong style="color: #E2136E;">bKash</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mobile-payment-option" onclick="selectMobilePayment('nagad')">
                                                <input type="radio" name="online_payment_type" value="nagad">
                                                <div class="text-center p-3 border rounded">
                                                    <div class="mb-2" style="color: #F47920;">
                                                        <i class="ti ti-device-mobile fs-3"></i>
                                                    </div>
                                                    <strong style="color: #F47920;">Nagad</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mobile-payment-option" onclick="selectMobilePayment('rocket')">
                                                <input type="radio" name="online_payment_type" value="rocket">
                                                <div class="text-center p-3 border rounded">
                                                    <div class="mb-2" style="color: #8B4A9C;">
                                                        <i class="ti ti-device-mobile fs-3"></i>
                                                    </div>
                                                    <strong style="color: #8B4A9C;">Rocket</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Instructions for selected method -->
                                    <div id="payment-instructions" class="mt-3" style="display: none;">
                                        <!-- bKash Instructions -->
                                        <div id="bkash-instructions" class="alert alert-info" style="display: none;">
                                            <h6 class="mb-2" style="color: #E2136E;">
                                                <i class="ti ti-device-mobile me-2"></i>bKash Payment Instructions
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Send Money to:</strong> <span class="text-primary">01XXXXXXXXX</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Account Type:</strong> Personal
                                            </div>
                                            <ol class="mb-2">
                                                <li>Go to your bKash app or dial *247#</li>
                                                <li>Select "Send Money"</li>
                                                <li>Enter the number: <strong>01XXXXXXXXX</strong></li>
                                                <li>Enter amount: <strong>৳<span id="bkash-amount">0.00</span></strong></li>
                                                <li>Enter your PIN and confirm</li>
                                                <li>Copy the Transaction ID (TrxID) from SMS</li>
                                                <li>Paste the TrxID in the field below</li>
                                            </ol>
                                            <small class="text-warning">
                                                <i class="ti ti-alert-triangle me-1"></i>
                                                Please double-check the number before sending money
                                            </small>
                                        </div>
                                        
                                        <!-- Nagad Instructions -->
                                        <div id="nagad-instructions" class="alert alert-info" style="display: none;">
                                            <h6 class="mb-2" style="color: #F47920;">
                                                <i class="ti ti-device-mobile me-2"></i>Nagad Payment Instructions
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Send Money to:</strong> <span class="text-primary">01XXXXXXXXX</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Account Type:</strong> Personal
                                            </div>
                                            <ol class="mb-2">
                                                <li>Go to your Nagad app or dial *167#</li>
                                                <li>Select "Send Money"</li>
                                                <li>Enter the number: <strong>01XXXXXXXXX</strong></li>
                                                <li>Enter amount: <strong>৳<span id="nagad-amount">0.00</span></strong></li>
                                                <li>Enter your PIN and confirm</li>
                                                <li>Copy the Transaction ID from SMS</li>
                                                <li>Paste the Transaction ID in the field below</li>
                                            </ol>
                                            <small class="text-warning">
                                                <i class="ti ti-alert-triangle me-1"></i>
                                                Please double-check the number before sending money
                                            </small>
                                        </div>
                                        
                                        <!-- Rocket Instructions -->
                                        <div id="rocket-instructions" class="alert alert-info" style="display: none;">
                                            <h6 class="mb-2" style="color: #8B4A9C;">
                                                <i class="ti ti-device-mobile me-2"></i>Rocket Payment Instructions
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Send Money to:</strong> <span class="text-primary">01XXXXXXXXX-X</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Account Type:</strong> Personal
                                            </div>
                                            <ol class="mb-2">
                                                <li>Go to your Rocket app or dial *322#</li>
                                                <li>Select "Send Money"</li>
                                                <li>Enter the wallet number: <strong>01XXXXXXXXX-X</strong></li>
                                                <li>Enter amount: <strong>৳<span id="rocket-amount">0.00</span></strong></li>
                                                <li>Enter your PIN and confirm</li>
                                                <li>Copy the Transaction ID from SMS</li>
                                                <li>Paste the Transaction ID in the field below</li>
                                            </ol>
                                            <small class="text-warning">
                                                <i class="ti ti-alert-triangle me-1"></i>
                                                Please double-check the number before sending money
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="form-label">Transaction ID *</label>
                                        <input type="text" class="form-control" name="transaction_id" 
                                               placeholder="Enter your transaction ID" 
                                               id="transaction-id-field">
                                        <small class="text-muted">
                                            Complete your payment first and enter the transaction ID here
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="payment-method" onclick="selectPayment('bank_transfer')">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-building-bank fs-4 me-3 text-info"></i>
                                    <div>
                                        <strong>Bank Transfer</strong>
                                        <div class="text-muted mt-1">Direct bank transfer</div>
                                    </div>
                                </div>
                                
                                <!-- Bank Transfer Details -->
                                <div id="bank-transfer-options" class="mt-3" style="display: none;">
                                    <div class="alert alert-info">
                                        <h6 class="mb-2">Bank Details:</h6>
                                        <p class="mb-1"><strong>Account Name:</strong> Your Company Name</p>
                                        <p class="mb-1"><strong>Account Number:</strong> 1234567890</p>
                                        <p class="mb-1"><strong>Bank:</strong> Your Bank Name</p>
                                        <p class="mb-0"><strong>Branch:</strong> Your Branch Name</p>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <label class="form-label">Bank Transaction Reference *</label>
                                        <input type="text" class="form-control" name="bank_transaction_ref" 
                                               placeholder="Enter bank transaction reference" 
                                               id="bank-ref-field">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-note me-2"></i>Order Notes (Optional)
                            </h5>
                        </div>
                        <div class="section-content">
                            <textarea class="form-control" name="order_notes" rows="4" 
                                      placeholder="Any special instructions for your order..."></textarea>
                        </div>
                    </div>

                    <!-- Discount Coupon -->
                    <div class="section-card">
                        <div class="section-header">
                            <h5 class="mb-0">
                                <i class="ti ti-ticket me-2"></i>Discount Coupon
                            </h5>
                        </div>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-8">
                                    <input type="text" 
                                           class="form-control" 
                                           id="coupon-code-input" 
                                           placeholder="Enter coupon code" 
                                           maxlength="50">
                                </div>
                                <div class="col-4">
                                    <button type="button" 
                                            class="btn btn-primary w-100" 
                                            id="apply-coupon-btn"
                                            onclick="applyCoupon()">
                                        Apply
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Applied Coupon Display -->
                            <div id="applied-coupon" class="mt-3" style="display: none;">
                                <div class="alert alert-success d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ti ti-check-circle me-2"></i>
                                        <strong id="applied-coupon-code"></strong> - 
                                        <span id="applied-coupon-description"></span>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="removeCoupon()">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Available Coupons -->
                            <div class="mt-3">
                                <button type="button" 
                                        class="btn btn-link btn-sm p-0" 
                                        onclick="showAvailableCoupons()">
                                    <i class="ti ti-list me-1"></i>
                                    View available coupons
                                </button>
                            </div>
                            
                            <!-- Available Coupons List -->
                            <div id="available-coupons" class="mt-3" style="display: none;">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-3">Available Coupons</h6>
                                        <div id="coupons-list" class="row g-2">
                                            <!-- Coupons will be loaded here -->
                                        </div>
                                        <div id="no-coupons" style="display: none;">
                                            <small class="text-muted">No coupons available for your order</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-4">
                <div class="section-card">
                    <div class="section-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="section-content">
                        <!-- Order Items -->
                        <div class="order-items mb-4">
                            @foreach($cartItems as $item)
                                <div class="order-item">
                                    <img src="{{ $item['image'] ?? asset('assets/img/no-image.png') }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="item-image">
                                    <div class="item-details">
                                        <div class="item-name">{{ $item['name'] }}</div>
                                        <div class="item-meta">
                                            Qty: {{ $item['quantity'] }} × ৳{{ number_format($item['price'], 2) }}
                                        </div>
                                    </div>
                                    <div class="item-total">
                                        ৳{{ number_format($item['total'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Subtotal ({{ count($cartItems) }} items)</span>
                                <span id="summary-subtotal">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span id="summary-shipping">৳0.00</span>
                            </div>
                            
                            <div class="summary-row" id="tax-row" style="display: none;">
                                <span>
                                    Tax (<span id="tax-rate-display">0</span>%)
                                    <small class="text-muted d-block" id="tax-description">Tax-free shopping in Bangladesh</small>
                                </span>
                                <span id="summary-tax">৳0.00</span>
                            </div>
                            
                            <div id="tax-notification" class="alert alert-success py-2 px-3 mb-2" style="font-size: 0.875rem;">
                                🇧🇩 Tax-free shopping in Bangladesh!
                            </div>
                            
                            <div class="summary-row" id="discount-row" style="display: none;">
                                <span>
                                    Discount
                                    <small class="text-muted d-block" id="discount-description">Coupon savings</small>
                                </span>
                                <span id="summary-discount" class="text-success">-৳0.00</span>
                            </div>
                            
                            <div class="summary-row">
                                <span><strong>Total</strong></span>
                                <span id="summary-total"><strong>৳{{ number_format($subtotal, 2) }}</strong></span>
                            </div>
                            
                            <!-- Tax Information (for tax-free system) -->
                            <div class="mt-3">
                                <button type="button" class="btn btn-link btn-sm p-0 text-muted" onclick="toggleTaxBreakdown()">
                                    <i class="ti ti-info-circle me-1"></i>
                                    <span id="tax-toggle-text">View tax information</span>
                                </button>
                                
                                <div id="tax-breakdown" class="mt-2" style="display: none;">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">Tax Information</h6>
                                            <div class="small">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Subtotal:</span>
                                                    <span id="breakdown-subtotal">৳{{ number_format($subtotal, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Shipping:</span>
                                                    <span id="breakdown-shipping">৳0.00</span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>Tax:</span>
                                                    <span id="breakdown-tax">৳0.00</span>
                                                </div>
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between fw-bold">
                                                    <span>Total:</span>
                                                    <span id="breakdown-total">৳{{ number_format($subtotal, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="ti ti-check-circle me-1"></i>
                                                    <span id="tax-policy-note">Tax-free shopping in Bangladesh - No taxes applied to any orders</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="button" class="place-order-btn mt-4" onclick="placeOrder()">
                            <i class="ti ti-shopping-cart me-2"></i>Place Order
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="ti ti-shield-check me-1"></i>
                                Your order is secured with SSL encryption
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="ti ti-login me-2"></i>Login to Your Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="login-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login-password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                        <label class="form-check-label" for="remember-me">
                            Remember me
                        </label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-login me-2"></i>Login
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <a href="#" class="text-muted">Forgot your password?</a>
                </div>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="text-muted mb-2">Don't have an account?</p>
                    <button type="button" class="btn btn-outline-primary" onclick="switchToRegistration()">
                        <i class="ti ti-user-plus me-2"></i>Create New Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let currentShippingCost = 0;
let subtotal = {{ $subtotal }};
let currentTaxRate = 0; // Default 0% for Bangladesh tax-free system
let currentTaxAmount = 0;
let currentDiscountAmount = 0;
let appliedCoupon = null;
let selectedArea = '';
let selectedShippingMethod = '';

// Tax configuration based on Bangladesh tax-free system
const taxConfig = {
    by_amount: [
        {min: 0, max: null, rate: 0, description: 'Tax-free shopping in Bangladesh'}
    ],
    by_location: {
        dhaka: 0,
        chittagong: 0,
        rajshahi: 0,
        khulna: 0,
        barisal: 0,
        sylhet: 0,
        rangpur: 0,
        mymensingh: 0
    },
    bulk_discount: {
        min_items: 10,
        discount_rate: 0 // No discount needed in tax-free system
    },
    default_rate: 0 // Tax-free by default
};

// Tax policy configuration for Bangladesh tax-free system
const taxPolicyConfig = {
    standard: {
        rate: 0,
        description: 'Tax-free',
        policy: 'All orders are tax-free in Bangladesh'
    },
    premium: {
        rate: 0,
        description: 'Tax-free',
        policy: 'All orders are tax-free in Bangladesh'
    },
    reduced: {
        rate: 0,
        description: 'Tax-free',
        policy: 'All orders are tax-free in Bangladesh'
    },
    exempt: {
        rate: 0,
        description: 'Tax-free',
        policy: 'Tax-free shopping in Bangladesh'
    }
};

// Calculate dynamic tax rate based on order conditions
function calculateDynamicTaxRate() {
    const totalBeforeTax = subtotal + currentShippingCost - currentDiscountAmount;
    
    // Tax rate logic based on order amount
    if (totalBeforeTax >= 5000) {
        // Premium tax for high-value orders
        return taxConfig.premium;
    } else if (totalBeforeTax >= 2000) {
        // Standard tax for medium orders
        return taxConfig.standard;
    } else if (totalBeforeTax >= 500) {
        // Standard tax for regular orders
        return taxConfig.standard;
    } else {
        // Reduced tax for small orders
        return taxConfig.reduced;
    }
}

// Calculate dynamic tax based on order amount and location
function calculateDynamicTax() {
    if (!selectedArea) {
        // Use default tax rate if no area selected
        currentTaxRate = taxConfig.default_rate;
        currentTaxAmount = subtotal * currentTaxRate;
        updateTaxDisplay();
        return;
    }
    
    // Prepare cart items for API call
    const cartItems = @json($cartItems);
    const requestData = {
        subtotal: subtotal,
        location: selectedArea,
        cart_items: cartItems.map(item => ({
            price: parseFloat(item.price || 0),
            quantity: parseInt(item.quantity || 1),
            category: item.category || 'other'
        })),
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Call TaxCalculationService via AJAX
    fetch('{{ route("checkout.calculate-tax") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Store service data for breakdown
            window.lastTaxServiceData = data;
            
            // Update tax values from service
            currentTaxRate = data.tax.rate / 100; // Convert percentage to decimal
            currentTaxAmount = data.tax.amount;
            
            // Update display with service data
            updateTaxDisplayFromService(data);
            updateOrderSummary();
        } else {
            // Fallback to local calculation
            fallbackTaxCalculation();
        }
    })
    .catch(error => {
        // Fallback to local calculation
        fallbackTaxCalculation();
    });
}

// Fallback tax calculation using local logic
function fallbackTaxCalculation() {
    // For Bangladesh tax-free system, always return 0
    if (isBangladeshTaxFree()) {
        currentTaxRate = 0;
        currentTaxAmount = 0;
        updateTaxDisplay();
        showTaxFreeMessage('Tax-free shopping in Bangladesh!');
        return;
    }

    let taxRate = getTaxRateByAmount(subtotal);
    let locationRate = getTaxRateByLocation(selectedArea);
    let totalItems = getTotalItemsInCart();
    
    // Use the highest applicable rate
    taxRate = Math.max(taxRate, locationRate);
    
    // Apply bulk discount if applicable
    if (totalItems >= taxConfig.bulk_discount.min_items) {
        taxRate = taxRate * (1 - taxConfig.bulk_discount.discount_rate);
    }
    
    currentTaxRate = taxRate;
    currentTaxAmount = subtotal * taxRate;
    
    updateTaxDisplay();
}

// Check if Bangladesh tax-free system applies
function isBangladeshTaxFree() {
    // Check if we're in Bangladesh and tax is disabled by default
    const bdAreas = ['dhaka', 'chittagong', 'rajshahi', 'khulna', 'barisal', 'sylhet', 'rangpur', 'mymensingh'];
    return !selectedArea || bdAreas.includes(selectedArea.toLowerCase());
}

// Get tax rate based on order amount
function getTaxRateByAmount(amount) {
    for (let tier of taxConfig.by_amount) {
        if (amount >= tier.min && (tier.max === null || amount < tier.max)) {
            return tier.rate;
        }
    }
    return taxConfig.default_rate;
}

// Get tax rate based on location
function getTaxRateByLocation(location) {
    if (!location || !taxConfig.by_location[location]) {
        return 0;
    }
    return taxConfig.by_location[location];
}

// Get total items in cart (placeholder - would get from actual cart data)
function getTotalItemsInCart() {
    let cartItems = @json($cartItems);
    return cartItems.reduce((total, item) => total + (item.quantity || 1), 0);
}

// Update tax display in the UI
function updateTaxDisplay() {
    const taxRatePercent = (currentTaxRate * 100).toFixed(1);
    const taxAmountFormatted = currentTaxAmount.toFixed(2);
    
    // Update tax rate display
    document.getElementById('tax-rate-display').textContent = taxRatePercent;
    
    // Update tax amount
    document.getElementById('summary-tax').textContent = `৳${taxAmountFormatted}`;
    
    // Update tax description
    const description = getTaxDescription();
    document.getElementById('tax-description').textContent = description;
    
    // Show/hide tax row based on amount
    const taxRow = document.querySelector('#summary-tax').closest('.summary-row');
    if (currentTaxAmount > 0) {
        taxRow.style.display = 'flex';
    } else {
        taxRow.style.display = 'none';
    }
}

// Update tax display from TaxCalculationService data
function updateTaxDisplayFromService(data) {
    const taxInfo = data.tax;
    const formatted = data.formatted;
    
    // Update tax rate display
    document.getElementById('tax-rate-display').textContent = formatted.rate_display;
    
    // Update tax amount
    document.getElementById('summary-tax').textContent = taxInfo.formatted_amount;
    
    // Update tax description
    document.getElementById('tax-description').textContent = formatted.description;
    
    // Show/hide tax row based on tax-free system
    const taxRow = document.querySelector('#summary-tax').closest('.summary-row');
    if (taxInfo.amount > 0) {
        taxRow.style.display = 'flex';
    } else {
        // Hide tax row completely for tax-free system
        taxRow.style.display = 'none';
    }
    
    // Show tax-free message if applicable
    if (taxInfo.is_tax_free && taxInfo.message) {
        showTaxFreeMessage(taxInfo.message);
    } else {
        showTaxSavingsFromService(taxInfo);
    }
}

// Show tax-free message for Bangladesh system
function showTaxFreeMessage(message) {
    const notificationArea = document.getElementById('tax-notification');
    if (notificationArea) {
        notificationArea.innerHTML = `🇧🇩 ${message}`;
        notificationArea.className = 'alert alert-success py-2 px-3 mb-2';
        notificationArea.style.display = 'block';
    }
}

// Get tax description based on current configuration
function getTaxDescription() {
    if (currentTaxAmount === 0) {
        return 'Tax-free order';
    }
    
    // Check if location-based tax is higher
    const amountRate = getTaxRateByAmount(subtotal);
    const locationRate = getTaxRateByLocation(selectedArea);
    
    if (locationRate > amountRate) {
        const locationName = selectedArea.charAt(0).toUpperCase() + selectedArea.slice(1);
        return `${locationName} area VAT`;
    }
    
    // Find amount-based description
    for (let tier of taxConfig.by_amount) {
        if (subtotal >= tier.min && (tier.max === null || subtotal < tier.max)) {
            return tier.description;
        }
    }
    
    return 'VAT on total amount';
}

// Shipping configuration
const shippingConfig = {
    inside_dhaka: {
        rate: 60,
        freeShippingThreshold: 500,
        areas: ['dhaka']
    },
    outside_dhaka: {
        rate: 120,
        freeShippingThreshold: 1500,
        areas: ['chittagong', 'rajshahi', 'khulna', 'barisal', 'sylhet', 'rangpur', 'mymensingh']
    },
    across_country: {
        rate: 150,
        freeShippingThreshold: null,
        areas: ['all']
    }
};

// Update shipping options based on selected area
function updateShippingOptions() {
    const areaSelect = document.getElementById('area-select');
    selectedArea = areaSelect.value;
    
    // Recalculate tax when area changes
    calculateDynamicTax();
    
    // Hide all shipping options first
    document.querySelectorAll('.shipping-method').forEach(el => {
        el.style.display = 'none';
        el.classList.remove('selected');
    });
    
    const shippingNotice = document.getElementById('shipping-notice');
    const shippingNoticeText = document.getElementById('shipping-notice-text');
    
    if (!selectedArea) {
        shippingNotice.style.display = 'block';
        shippingNoticeText.textContent = 'Please select your area to see available shipping options.';
        currentShippingCost = 0;
        updateOrderSummary();
        return;
    }
    
    shippingNotice.style.display = 'none';
    
    // Show relevant shipping options based on area
    let availableOptions = [];
    let defaultOption = null;
    
    if (selectedArea === 'dhaka') {
        // Show inside Dhaka option
        const insideDhakaOption = document.getElementById('inside-dhaka-shipping');
        insideDhakaOption.style.display = 'block';
        availableOptions.push('inside_dhaka');
        defaultOption = 'inside_dhaka';
        
        // Check if eligible for free shipping
        if (subtotal >= shippingConfig.inside_dhaka.freeShippingThreshold) {
            document.getElementById('dhaka-free-shipping-notice').style.display = 'block';
            document.getElementById('dhaka-shipping-cost').innerHTML = '<span class="text-success">FREE</span>';
            
            // Show free shipping option
            const freeShippingOption = document.getElementById('free-shipping-option');
            freeShippingOption.style.display = 'block';
            document.getElementById('free-shipping-reason').textContent = `Order over ৳${shippingConfig.inside_dhaka.freeShippingThreshold} qualifies for free shipping!`;
            availableOptions.push('free');
            defaultOption = 'free';
        } else {
            document.getElementById('dhaka-free-shipping-notice').style.display = 'block';
            document.getElementById('dhaka-shipping-cost').textContent = '৳60';
            
            const remaining = shippingConfig.inside_dhaka.freeShippingThreshold - subtotal;
            shippingNotice.style.display = 'block';
            shippingNotice.className = 'alert alert-warning mb-3';
            shippingNoticeText.innerHTML = `<i class="ti ti-info-circle me-2"></i>Add ৳${remaining.toFixed(2)} more to get free shipping!`;
        }
    } else {
        // Show outside Dhaka option for other areas
        const outsideDhakaOption = document.getElementById('outside-dhaka-shipping');
        outsideDhakaOption.style.display = 'block';
        availableOptions.push('outside_dhaka');
        defaultOption = 'outside_dhaka';
        
        // Check if eligible for free shipping
        if (subtotal >= shippingConfig.outside_dhaka.freeShippingThreshold) {
            document.getElementById('outside-dhaka-free-shipping-notice').style.display = 'block';
            document.getElementById('outside-dhaka-shipping-cost').innerHTML = '<span class="text-success">FREE</span>';
            
            // Show free shipping option
            const freeShippingOption = document.getElementById('free-shipping-option');
            freeShippingOption.style.display = 'block';
            document.getElementById('free-shipping-reason').textContent = `Order over ৳${shippingConfig.outside_dhaka.freeShippingThreshold} qualifies for free shipping!`;
            availableOptions.push('free');
            defaultOption = 'free';
        } else {
            document.getElementById('outside-dhaka-free-shipping-notice').style.display = 'block';
            document.getElementById('outside-dhaka-shipping-cost').textContent = '৳120';
            
            const remaining = shippingConfig.outside_dhaka.freeShippingThreshold - subtotal;
            shippingNotice.style.display = 'block';
            shippingNotice.className = 'alert alert-warning mb-3';
            shippingNoticeText.innerHTML = `<i class="ti ti-info-circle me-2"></i>Add ৳${remaining.toFixed(2)} more to get free shipping!`;
        }
    }
    
    // Always show across country option
    const acrossCountryOption = document.getElementById('across-country-shipping');
    acrossCountryOption.style.display = 'block';
    availableOptions.push('across_country');
    
    // Auto-select the best option
    if (defaultOption) {
        const defaultShippingElement = document.getElementById(defaultOption === 'inside_dhaka' ? 'inside-dhaka-shipping' : 
                                                             defaultOption === 'outside_dhaka' ? 'outside-dhaka-shipping' : 
                                                             defaultOption === 'free' ? 'free-shipping-option' : 'across-country-shipping');
        if (defaultShippingElement && defaultShippingElement.style.display !== 'none') {
            defaultShippingElement.classList.add('selected');
            const radioButton = defaultShippingElement.querySelector('input[type="radio"]');
            if (radioButton) {
                radioButton.checked = true;
                selectedShippingMethod = radioButton.value;
                currentShippingCost = defaultOption === 'free' ? 0 : 
                                    defaultOption === 'inside_dhaka' ? (subtotal >= 500 ? 0 : 60) :
                                    defaultOption === 'outside_dhaka' ? (subtotal >= 1500 ? 0 : 120) : 150;
                updateOrderSummary();
            }
        }
    }
}

// Select shipping method
function selectShipping(method, cost) {
    // Remove selected class from all shipping methods
    document.querySelectorAll('.shipping-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked method
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.querySelector(`input[name="shipping_method"][value="${method}"]`).checked = true;
    
    selectedShippingMethod = method;
    
    // Calculate actual cost based on free shipping eligibility
    if (method === 'free') {
        currentShippingCost = 0;
    } else if (method === 'inside_dhaka' && subtotal >= shippingConfig.inside_dhaka.freeShippingThreshold) {
        currentShippingCost = 0;
    } else if (method === 'outside_dhaka' && subtotal >= shippingConfig.outside_dhaka.freeShippingThreshold) {
        currentShippingCost = 0;
    } else {
        currentShippingCost = cost;
    }
    
    updateOrderSummary();
}

// Select payment method
function selectPayment(method) {
    // Remove selected class from all payment methods
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked method
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.querySelector(`input[name="payment_method"][value="${method}"]`).checked = true;
    
    // Show/hide relevant options
    const mobileBankingOptions = document.getElementById('mobile-banking-options');
    const bankTransferOptions = document.getElementById('bank-transfer-options');
    const paymentInstructions = document.getElementById('payment-instructions');
    
    if (method === 'online_payment') {
        mobileBankingOptions.style.display = 'block';
        bankTransferOptions.style.display = 'none';
        // Make transaction ID required
        document.getElementById('transaction-id-field').required = true;
        document.getElementById('bank-ref-field').required = false;
    } else if (method === 'bank_transfer') {
        mobileBankingOptions.style.display = 'none';
        bankTransferOptions.style.display = 'block';
        // Hide mobile banking payment instructions
        paymentInstructions.style.display = 'none';
        // Make bank reference required
        document.getElementById('bank-ref-field').required = true;
        document.getElementById('transaction-id-field').required = false;
    } else {
        mobileBankingOptions.style.display = 'none';
        bankTransferOptions.style.display = 'none';
        // Hide mobile banking payment instructions
        paymentInstructions.style.display = 'none';
        // Remove requirements
        document.getElementById('transaction-id-field').required = false;
        document.getElementById('bank-ref-field').required = false;
    }
}

// Select mobile payment type
function selectMobilePayment(type) {
    // Remove selected class from all mobile payment options
    document.querySelectorAll('.mobile-payment-option').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.querySelector(`input[name="online_payment_type"][value="${type}"]`).checked = true;
    
    // Show payment instructions
    showPaymentInstructions(type);
    
    // Update placeholder text based on selected payment type
    const transactionField = document.getElementById('transaction-id-field');
    const placeholders = {
        'bkash': 'Enter bKash TrxID (e.g., 9A52CZTP)',
        'nagad': 'Enter Nagad Transaction ID',
        'rocket': 'Enter Rocket Transaction ID'
    };
    
    transactionField.placeholder = placeholders[type] || 'Enter your transaction ID';
}

// Show payment instructions for selected mobile banking method
function showPaymentInstructions(type) {
    // Hide all instruction panels
    document.getElementById('bkash-instructions').style.display = 'none';
    document.getElementById('nagad-instructions').style.display = 'none';
    document.getElementById('rocket-instructions').style.display = 'none';
    
    // Show the payment instructions container
    document.getElementById('payment-instructions').style.display = 'block';
    
    // Show specific instructions for selected type
    if (type && document.getElementById(`${type}-instructions`)) {
        document.getElementById(`${type}-instructions`).style.display = 'block';
        
        // Update the amount in instructions
        updatePaymentAmount(type);
    }
}

// Update payment amount in instructions
function updatePaymentAmount(type) {
    const total = subtotal + currentShippingCost + currentTaxAmount - currentDiscountAmount;
    const formattedAmount = total.toFixed(2);
    
    // Update amount in the specific instruction panel
    const amountElement = document.getElementById(`${type}-amount`);
    if (amountElement) {
        amountElement.textContent = formattedAmount;
    }
}

// Update order summary
function updateOrderSummary() {
    // Recalculate tax dynamically (but it's 0 for Bangladesh)
    if (selectedArea) {
        calculateDynamicTax();
    }
    
    const total = subtotal + currentShippingCost + currentTaxAmount - currentDiscountAmount;
    
    // Update shipping display
    const shippingDisplay = currentShippingCost === 0 ? 'FREE' : `৳${currentShippingCost.toFixed(2)}`;
    document.getElementById('summary-shipping').innerHTML = shippingDisplay;
    
    // Update total
    document.getElementById('summary-total').innerHTML = `<strong>৳${total.toFixed(2)}</strong>`;
    
    // Show/hide discount row
    const discountRow = document.getElementById('discount-row');
    if (currentDiscountAmount > 0) {
        discountRow.style.display = 'flex';
        document.getElementById('summary-discount').textContent = `-৳${currentDiscountAmount.toFixed(2)}`;
    } else {
        discountRow.style.display = 'none';
    }
    
    // Show tax savings notification - prefer service data if available
    if (window.lastTaxServiceData) {
        showTaxSavingsFromService(window.lastTaxServiceData.tax);
    } else {
        showTaxSavingsNotification();
    }
    
    // Update payment amounts in mobile banking instructions
    updateAllPaymentAmounts();
}

// Update payment amounts in all mobile banking instructions
function updateAllPaymentAmounts() {
    const selectedPaymentType = document.querySelector('input[name="online_payment_type"]:checked');
    if (selectedPaymentType) {
        updatePaymentAmount(selectedPaymentType.value);
    }
}

// Show tax savings notification if applicable
function showTaxSavingsNotification() {
    const totalItems = getTotalItemsInCart();
    let notification = '';
    
    // Check for bulk discount eligibility
    if (totalItems >= taxConfig.bulk_discount.min_items) {
        const savings = subtotal * (getTaxRateByAmount(subtotal) * taxConfig.bulk_discount.discount_rate);
        notification += `🎉 Bulk order tax discount: You saved ৳${savings.toFixed(2)} on taxes! `;
    }
    
    // Check if order is tax-free
    if (currentTaxAmount === 0 && subtotal < 200) {
        notification += `✨ Your order is tax-free! `;
    }
    
    // Check for next tax tier threshold
    const nextTier = getNextTaxTier(subtotal);
    if (nextTier) {
        const remaining = nextTier.min - subtotal;
        if (remaining > 0 && remaining < 100) {
            notification += `⚠️ Add ৳${remaining.toFixed(2)} more to move to the next tax tier (${(nextTier.rate * 100).toFixed(1)}%). `;
        }
    }
    
    // Update notification area
    const notificationArea = document.getElementById('tax-notification');
    if (notification && notificationArea) {
        notificationArea.innerHTML = notification;
        notificationArea.style.display = 'block';
    } else if (notificationArea) {
        notificationArea.style.display = 'none';
    }
}

// Show tax savings from TaxCalculationService
function showTaxSavingsFromService(taxInfo) {
    let notification = '';
    
    // Check if order is tax-free
    if (taxInfo.amount === 0) {
        notification += `✨ Your order is tax-free! `;
    }
    
    // Show breakdown information if available
    if (taxInfo.breakdown && taxInfo.breakdown.length > 0) {
        const hasLocationTax = taxInfo.breakdown.some(item => item.type === 'Location-based');
        const hasAmountTax = taxInfo.breakdown.some(item => item.type === 'Amount-based');
        
        if (hasLocationTax && hasAmountTax) {
            notification += `📍 Multiple tax rates applied based on location and order amount. `;
        }
    }
    
    // Update notification area
    const notificationArea = document.getElementById('tax-notification');
    if (notification && notificationArea) {
        notificationArea.innerHTML = notification;
        notificationArea.style.display = 'block';
    } else if (notificationArea) {
        notificationArea.style.display = 'none';
    }
}

// Get next tax tier
function getNextTaxTier(amount) {
    for (let tier of taxConfig.by_amount) {
        if (amount < tier.min) {
            return tier;
        }
    }
    return null;
}

// Toggle tax breakdown display
function toggleTaxBreakdown() {
    const breakdown = document.getElementById('tax-breakdown');
    const toggleText = document.getElementById('tax-toggle-text');
    
    if (breakdown.style.display === 'none') {
        breakdown.style.display = 'block';
        toggleText.textContent = 'Hide tax information';
        
        // Populate tax breakdown content
        populateTaxBreakdown();
    } else {
        breakdown.style.display = 'none';
        toggleText.textContent = 'View tax information';
    }
}

// Populate tax breakdown with current information
function populateTaxBreakdown() {
    const breakdownContent = document.querySelector('#tax-breakdown .card-body');
    if (!breakdownContent) return;
    
    // Try to get breakdown from last service call
    if (window.lastTaxServiceData && window.lastTaxServiceData.tax.breakdown.length > 0) {
        populateTaxBreakdownFromService(window.lastTaxServiceData.tax);
        return;
    }
    
    // Fallback to local calculation
    populateTaxBreakdownLocal();
}

// Populate tax breakdown from service data
function populateTaxBreakdownFromService(taxInfo) {
    const breakdownContent = document.querySelector('#tax-breakdown .card-body');
    if (!breakdownContent) return;
    
    let content = `
        <h6 class="card-title mb-2">Tax Breakdown</h6>
        <div class="small">
            <div class="d-flex justify-content-between mb-1">
                <span>Subtotal:</span>
                <span>৳${subtotal.toFixed(2)}</span>
            </div>
    `;
    
    // Show breakdown from service
    if (taxInfo.breakdown && taxInfo.breakdown.length > 0) {
        taxInfo.breakdown.forEach(item => {
            content += `
                <div class="d-flex justify-content-between mb-1">
                    <span>${item.type} (${item.rate}%):</span>
                    <span>৳${((subtotal * item.rate) / 100).toFixed(2)}</span>
                </div>
            `;
        });
    }
    
    content += `
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold">
                <span>Total Tax:</span>
                <span>৳${taxInfo.amount.toFixed(2)}</span>
            </div>
        </div>
        
        <div class="mt-2">
            <small class="text-muted">
                <i class="ti ti-info-circle me-1"></i>
                <span>${taxInfo.label} calculated by TaxCalculationService</span>
            </small>
        </div>
    `;
    
    breakdownContent.innerHTML = content;
}

// Populate tax breakdown with local calculation (fallback)
function populateTaxBreakdownLocal() {
    const breakdownContent = document.querySelector('#tax-breakdown .card-body');
    if (!breakdownContent) return;
    
    const amountRate = getTaxRateByAmount(subtotal);
    const locationRate = getTaxRateByLocation(selectedArea);
    const totalItems = getTotalItemsInCart();
    
    let content = `
        <h6 class="card-title mb-2">Tax Breakdown</h6>
        <div class="small">
            <div class="d-flex justify-content-between mb-1">
                <span>Subtotal:</span>
                <span>৳${subtotal.toFixed(2)}</span>
            </div>
    `;
    
    // Amount-based tax
    if (amountRate > 0) {
        content += `
            <div class="d-flex justify-content-between mb-1">
                <span>Amount-based tax (${(amountRate * 100).toFixed(1)}%):</span>
                <span>৳${(subtotal * amountRate).toFixed(2)}</span>
            </div>
        `;
    }
    
    // Location-based tax
    if (locationRate > 0 && selectedArea) {
        const locationName = selectedArea.charAt(0).toUpperCase() + selectedArea.slice(1);
        content += `
            <div class="d-flex justify-content-between mb-1">
                <span>${locationName} area tax (${(locationRate * 100).toFixed(1)}%):</span>
                <span>৳${(subtotal * locationRate).toFixed(2)}</span>
            </div>
        `;
    }
    
    // Bulk discount
    if (totalItems >= taxConfig.bulk_discount.min_items) {
        const originalTax = subtotal * Math.max(amountRate, locationRate);
        const discountedTax = originalTax * (1 - taxConfig.bulk_discount.discount_rate);
        const savings = originalTax - discountedTax;
        
        content += `
            <div class="d-flex justify-content-between mb-1 text-success">
                <span>Bulk order discount (${totalItems} items):</span>
                <span>-৳${savings.toFixed(2)}</span>
            </div>
        `;
    }
    
    content += `
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold">
                <span>Total Tax:</span>
                <span>৳${currentTaxAmount.toFixed(2)}</span>
            </div>
        </div>
    `;
    
    breakdownContent.innerHTML = content;
}

// Place order
function placeOrder() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);
    
    // Validate area selection
    if (!selectedArea) {
        showToast('Please select your area first', 'error');
        document.getElementById('area-select').focus();
        return;
    }
    
    // Validate shipping method selection
    if (!selectedShippingMethod) {
        showToast('Please select a shipping method', 'error');
        return;
    }
    
    // Validate required fields
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            field.style.borderColor = '#d1d5db';
        }
    });
    
    // Validate payment method specific requirements
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod === 'online_payment') {
        const onlinePaymentType = document.querySelector('input[name="online_payment_type"]:checked');
        const transactionId = document.getElementById('transaction-id-field');
        
        if (!onlinePaymentType) {
            showToast('Please select a mobile banking option', 'error');
            return;
        }
        
        if (!transactionId.value.trim()) {
            transactionId.style.borderColor = '#ef4444';
            showToast('Please enter transaction ID', 'error');
            return;
        }
    } else if (paymentMethod === 'bank_transfer') {
        const bankRef = document.getElementById('bank-ref-field');
        
        if (!bankRef.value.trim()) {
            bankRef.style.borderColor = '#ef4444';
            showToast('Please enter bank transaction reference', 'error');
            return;
        }
    }
    
    if (!isValid) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="ti ti-loader-2 rotating me-2"></i>Processing...';
    button.disabled = true;
    
    // Convert FormData to JSON
    const orderData = {};
    formData.forEach((value, key) => {
        orderData[key] = value;
    });
    
    // Combine name fields for customer_name
    orderData.customer_name = (orderData.first_name + ' ' + orderData.last_name).trim();
    orderData.customer_email = orderData.email;
    orderData.customer_phone = orderData.phone;
    
    // Structure shipping address
    orderData.shipping_address = {
        address: orderData.address,
        city: orderData.city,
        state: orderData.state || orderData.area,
        postal_code: orderData.zip_code || '',
        country: 'Bangladesh'
    };
    
    // Parse cart items from JSON
    const cartItems = @json($cartItems);
    orderData.cart_items = cartItems.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price
    }));
    
    // Add calculated amounts
    orderData.subtotal = subtotal;
    orderData.shipping_cost = currentShippingCost;
    orderData.tax_amount = currentTaxAmount;
    orderData.discount_amount = currentDiscountAmount;
    orderData.total_amount = subtotal + currentShippingCost + currentTaxAmount - currentDiscountAmount;
    
    // Add coupon information if applied
    if (appliedCoupon) {
        orderData.coupon_code = appliedCoupon.code;
    }
    
    // Add notes
    orderData.notes = orderData.order_notes || null;
    
    // Add checkout type information
    const checkoutTypeElement = document.querySelector('input[name="checkout_type"]:checked');
    let checkoutType = 'guest'; // Default for authenticated users
    
    if (checkoutTypeElement) {
        checkoutType = checkoutTypeElement.value;
    } else {
        // User is authenticated - check if they're logged in via server-side data
        @auth
        checkoutType = 'authenticated';
        @endauth
    }
    
    orderData.checkout_type = checkoutType;
    
    // Add user registration data if creating account (only for non-authenticated users)
    if (checkoutType === 'register') {
        orderData.create_account = true;
        orderData.username = orderData.username;
        orderData.password = orderData.password;
        orderData.password_confirmation = orderData.password_confirmation;
        orderData.subscribe_newsletter = orderData.subscribe_newsletter === 'on';
        orderData.agree_terms = orderData.agree_terms === 'on';
    } else {
        orderData.create_account = false;
    }
    
    // Submit order
    fetch('{{ route("orders.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear cart
            fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            // Redirect to success page
            window.location.href = data.redirect_url || '{{ route("orders.success") }}';
        } else {
            throw new Error(data.message || 'Order processing failed');
        }
    })
    .catch(error => {
        showToast(error.message || 'Error processing order', 'error');
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>
            ${message}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 4000);
}

// CSS for rotating loader
const rotatingStyle = document.createElement('style');
rotatingStyle.textContent = `
    .rotating {
        animation: rotate 1s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(rotatingStyle);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tax-free system
    currentTaxRate = 0;
    currentTaxAmount = 0;
    
    // Show tax-free message
    showTaxFreeMessage('Tax-free shopping in Bangladesh!');
    
    // Update order summary without tax calculations
    const total = subtotal + currentShippingCost - currentDiscountAmount;
    document.getElementById('summary-total').innerHTML = `<strong>৳${total.toFixed(2)}</strong>`;
    
    // Show initial shipping notice
    const shippingNotice = document.getElementById('shipping-notice');
    shippingNotice.style.display = 'block';
    
    // Auto-update shipping options for logged-in users with pre-selected area
    @auth
    const areaSelect = document.getElementById('area-select');
    if (areaSelect && areaSelect.value) {
        console.log('Auto-updating shipping options for pre-selected area:', areaSelect.value);
        updateShippingOptions();
    }
    @endauth
    
    // Enable Enter key for coupon input
    document.getElementById('coupon-code-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyCoupon();
        }
    });
});

// ==================== COUPON FUNCTIONS ====================

// Apply coupon code
function applyCoupon() {
    const couponInput = document.getElementById('coupon-code-input');
    const couponCode = couponInput.value.trim();
    
    if (!couponCode) {
        showToast('Please enter a coupon code', 'error');
        return;
    }
    
    const applyBtn = document.getElementById('apply-coupon-btn');
    const originalText = applyBtn.innerHTML;
    applyBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Applying...';
    applyBtn.disabled = true;
    
    // Prepare request data
    const cartItems = @json($cartItems);
    const requestData = {
        coupon_code: couponCode,
        subtotal: subtotal,
        cart_items: cartItems,
        shipping_cost: currentShippingCost,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    fetch('{{ route("checkout.apply-coupon") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Store applied coupon
            appliedCoupon = data.coupon;
            
            // Handle different types of discounts
            if (data.discount.free_shipping) {
                // For free shipping coupons, set shipping to 0 and discount amount to shipping saved
                currentShippingCost = 0;
                currentDiscountAmount = data.discount.shipping_discount || 0;
            } else {
                // For regular discounts
                currentDiscountAmount = data.discount.amount;
            }
            
            // Show applied coupon
            showAppliedCoupon(data.coupon, data.discount);
            
            // Update order summary
            updateOrderSummary();
            
            // Clear input
            couponInput.value = '';
            
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Error applying coupon', 'error');
    })
    .finally(() => {
        applyBtn.innerHTML = originalText;
        applyBtn.disabled = false;
    });
}

// Remove applied coupon
function removeCoupon() {
    // Store the current coupon info before clearing
    const wasShippingFree = appliedCoupon && appliedCoupon.type === 'free_shipping';
    
    fetch('{{ route("checkout.remove-coupon") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear applied coupon
            appliedCoupon = null;
            currentDiscountAmount = 0;
            
            // If it was a free shipping coupon, restore original shipping cost
            if (wasShippingFree) {
                // Recalculate shipping cost based on selected method
                if (selectedShippingMethod === 'inside_dhaka') {
                    currentShippingCost = subtotal >= 500 ? 0 : 60;
                } else if (selectedShippingMethod === 'outside_dhaka') {
                    currentShippingCost = subtotal >= 1500 ? 0 : 120;
                } else if (selectedShippingMethod === 'across_country') {
                    currentShippingCost = 150;
                } else if (selectedShippingMethod === 'free') {
                    currentShippingCost = 0;
                }
            }
            
            // Hide applied coupon display
            document.getElementById('applied-coupon').style.display = 'none';
            
            // Update order summary
            updateOrderSummary();
            
            showToast(data.message, 'success');
        }
    })
    .catch(error => {
        showToast('Error removing coupon', 'error');
    });
}

// Show applied coupon in UI
function showAppliedCoupon(coupon, discount) {
    const appliedCouponDiv = document.getElementById('applied-coupon');
    const couponCode = document.getElementById('applied-coupon-code');
    const couponDescription = document.getElementById('applied-coupon-description');
    
    couponCode.textContent = coupon.code;
    couponDescription.textContent = discount.description;
    
    appliedCouponDiv.style.display = 'block';
    
    // Handle free shipping coupon
    if (discount.free_shipping) {
        // Set shipping cost to 0 for free shipping
        currentShippingCost = 0;
        // Update shipping display immediately
        document.getElementById('summary-shipping').innerHTML = 'FREE';
    }
    
    // Update discount row in order summary
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('summary-discount');
    const discountDesc = document.getElementById('discount-description');
    
    if (discount.amount > 0) {
        discountAmount.textContent = `-${discount.formatted_amount}`;
        discountDesc.textContent = discount.description;
        discountRow.style.display = 'flex';
    } else if (discount.free_shipping) {
        // For free shipping, show it as a discount if there was shipping cost
        const savedShipping = discount.amount || 0;
        if (savedShipping > 0) {
            discountAmount.textContent = `-৳${savedShipping.toFixed(2)}`;
            discountDesc.textContent = discount.description;
            discountRow.style.display = 'flex';
        }
    }
}

// Show available coupons
function showAvailableCoupons() {
    const availableCouponsDiv = document.getElementById('available-coupons');
    
    if (availableCouponsDiv.style.display === 'none') {
        // Load and show coupons
        loadAvailableCoupons();
        availableCouponsDiv.style.display = 'block';
    } else {
        availableCouponsDiv.style.display = 'none';
    }
}

// Load available coupons from server
function loadAvailableCoupons() {
    const couponsList = document.getElementById('coupons-list');
    const noCoupons = document.getElementById('no-coupons');
    
    couponsList.innerHTML = '<div class="col-12"><small class="text-muted">Loading coupons...</small></div>';
    
    fetch(`{{ route("checkout.available-coupons") }}?subtotal=${subtotal}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.coupons.length > 0) {
            couponsList.innerHTML = '';
            noCoupons.style.display = 'none';
            
            data.coupons.forEach(coupon => {
                const couponCard = createCouponCard(coupon);
                couponsList.appendChild(couponCard);
            });
        } else {
            couponsList.innerHTML = '';
            noCoupons.style.display = 'block';
        }
    })
    .catch(error => {
        couponsList.innerHTML = '<div class="col-12"><small class="text-danger">Error loading coupons</small></div>';
    });
}

// Create coupon card element
function createCouponCard(coupon) {
    const col = document.createElement('div');
    col.className = 'col-md-6 mb-2';
    
    const isEligible = !coupon.minimum_amount || subtotal >= coupon.minimum_amount;
    const cardClass = isEligible ? 'border-success' : 'border-secondary';
    const textClass = isEligible ? 'text-success' : 'text-muted';
    
    col.innerHTML = `
        <div class="card border ${cardClass} coupon-card" style="cursor: ${isEligible ? 'pointer' : 'default'};" 
             ${isEligible ? `onclick="applyCouponCode('${coupon.code}')"` : ''}>
            <div class="card-body p-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong class="${textClass}">${coupon.code}</strong>
                        <div class="small ${textClass}">${coupon.discount_text}</div>
                        ${coupon.minimum_amount ? `<div class="small text-muted">Min: ৳${coupon.minimum_amount}</div>` : ''}
                    </div>
                    <div class="text-end">
                        ${coupon.days_remaining !== null ? `<small class="text-muted">${coupon.days_remaining} days left</small>` : ''}
                    </div>
                </div>
                ${!isEligible ? '<div class="small text-danger mt-1">Minimum order amount not met</div>' : ''}
            </div>
        </div>
    `;
    
    return col;
}

// Apply coupon code from available coupons
function applyCouponCode(code) {
    document.getElementById('coupon-code-input').value = code;
    document.getElementById('available-coupons').style.display = 'none';
    applyCoupon();
}

// ==================== CHECKOUT TYPE FUNCTIONS ====================

// Select checkout type (guest or register)
function selectCheckoutType(type) {
    // Check if checkout options exist (user not logged in)
    const checkoutOptions = document.querySelectorAll('.checkout-option-card');
    if (checkoutOptions.length === 0) {
        // User is logged in, no checkout options to manage
        return;
    }
    
    // Remove active class from all options
    checkoutOptions.forEach(el => {
        el.classList.remove('active');
    });
    
    // Add active class to selected option
    const selectedOption = document.getElementById(type === 'guest' ? 'guest-checkout-option' : 'user-registration-option');
    if (selectedOption) {
        selectedOption.classList.add('selected');
    }
    
    // Check the appropriate radio button
    const radioButton = document.querySelector(`input[name="checkout_type"][value="${type}"]`);
    if (radioButton) {
        radioButton.checked = true;
    }
    
    // Show/hide account creation form
    const accountForm = document.getElementById('account-creation-form');
    if (accountForm && type === 'register') {
        accountForm.style.display = 'block';
        // Make registration fields required
        const usernameField = document.getElementById('registration-username');
        const passwordField = document.getElementById('registration-password');
        const confirmPasswordField = document.getElementById('password-confirmation');
        const agreeTermsField = document.getElementById('agree-terms');
        
        if (usernameField) usernameField.required = true;
        if (passwordField) passwordField.required = true;
        if (confirmPasswordField) confirmPasswordField.required = true;
        if (agreeTermsField) agreeTermsField.required = true;
    } else if (accountForm) {
        accountForm.style.display = 'none';
        // Remove requirements
        const usernameField = document.getElementById('registration-username');
        const passwordField = document.getElementById('registration-password');
        const confirmPasswordField = document.getElementById('password-confirmation');
        const agreeTermsField = document.getElementById('agree-terms');
        
        if (usernameField) usernameField.required = false;
        if (passwordField) passwordField.required = false;
        if (confirmPasswordField) confirmPasswordField.required = false;
        if (agreeTermsField) agreeTermsField.required = false;
    }
}

// Switch to registration from login modal
function switchToRegistration() {
    // Close login modal
    const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
    if (loginModal) {
        loginModal.hide();
    }
    
    // Select registration option
    selectCheckoutType('register');
    
    // Scroll to checkout options
    document.getElementById('user-registration-option').scrollIntoView({ 
        behavior: 'smooth',
        block: 'center'
    });
}

// Handle login form submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loginButton = this.querySelector('button[type="submit"]');
            const originalText = loginButton.innerHTML;
            
            // Show loading state
            loginButton.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Logging in...';
            loginButton.disabled = true;
            
            // Submit login request
            fetch('{{ route("login") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                    loginModal.hide();
                    
                    // Show success message
                    showToast('Login successful! Welcome back!', 'success');
                    
                    // Pre-fill user information if available
                    if (data.user) {
                        prefillUserData(data.user);
                    }
                    
                    // Switch to guest checkout since user is now logged in
                    selectCheckoutType('guest');
                } else {
                    showToast(data.message || 'Login failed. Please check your credentials.', 'error');
                }
            })
            .catch(error => {
                showToast('Login error. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button
                loginButton.innerHTML = originalText;
                loginButton.disabled = false;
            });
        });
    }
    
    // Validate password confirmation
    const passwordField = document.getElementById('registration-password');
    const confirmPasswordField = document.getElementById('password-confirmation');
    
    if (passwordField && confirmPasswordField) {
        confirmPasswordField.addEventListener('input', function() {
            if (this.value !== passwordField.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
        
        passwordField.addEventListener('input', function() {
            if (confirmPasswordField.value !== this.value) {
                confirmPasswordField.setCustomValidity('Passwords do not match');
            } else {
                confirmPasswordField.setCustomValidity('');
            }
        });
    }
    
    // Validate username format
    const usernameField = document.getElementById('registration-username');
    if (usernameField) {
        usernameField.addEventListener('input', function() {
            const username = this.value;
            const usernamePattern = /^[a-zA-Z0-9_]{3,20}$/;
            
            if (username && !usernamePattern.test(username)) {
                this.setCustomValidity('Username must be 3-20 characters long and contain only letters, numbers, and underscores');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Check username availability on blur
        usernameField.addEventListener('blur', function() {
            const username = this.value;
            if (username && username.length >= 3) {
                checkUsernameAvailability(username);
            }
        });
    }
});

// Pre-fill user data after login
function prefillUserData(user) {
    if (user.first_name) document.querySelector('input[name="first_name"]').value = user.first_name;
    if (user.last_name) document.querySelector('input[name="last_name"]').value = user.last_name;
    if (user.email) document.querySelector('input[name="email"]').value = user.email;
    if (user.phone) document.querySelector('input[name="phone"]').value = user.phone;
    if (user.address) document.querySelector('input[name="address"]').value = user.address;
    if (user.city) document.querySelector('input[name="city"]').value = user.city;
    if (user.zip_code) document.querySelector('input[name="zip_code"]').value = user.zip_code;
}

// Check username availability
function checkUsernameAvailability(username) {
    const usernameField = document.getElementById('registration-username');
    const originalBorder = usernameField.style.borderColor;
    
    // Show checking state
    usernameField.style.borderColor = '#fbbf24';
    
    fetch('{{ route("check.username") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ username: username })
    })
    .then(response => response.json())
    .then(data => {
        if (data.available) {
            usernameField.style.borderColor = '#10b981';
            usernameField.setCustomValidity('');
            showUsernameMessage('Username is available!', 'success');
        } else {
            usernameField.style.borderColor = '#ef4444';
            usernameField.setCustomValidity('Username is already taken');
            showUsernameMessage('Username is already taken', 'error');
        }
    })
    .catch(error => {
        usernameField.style.borderColor = originalBorder;
        showUsernameMessage('Unable to check username availability', 'warning');
    });
}

// Show username availability message
function showUsernameMessage(message, type) {
    const usernameField = document.getElementById('registration-username');
    const existingMessage = usernameField.parentNode.querySelector('.username-message');
    
    // Remove existing message
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('small');
    messageDiv.className = `username-message text-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} mt-1`;
    messageDiv.textContent = message;
    
    // Insert after the input field
    usernameField.insertAdjacentElement('afterend', messageDiv);
    
    // Remove message after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 3000);
}
</script>
@endpush
