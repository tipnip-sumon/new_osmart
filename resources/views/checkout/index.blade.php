@extends('layouts.ecomus')

@section('title', 'Checkout')

@section('content')
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Check Out</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- page-cart -->
    <section class="flat-spacing-11">
        <div class="container">
            <div class="tf-page-cart-wrap layout-2">
                <div class="tf-page-cart-item">
                    @auth
                    <!-- Logged In User Section -->
                    <div class="logged-in-user-section mb-4">
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="icon-check-circle fs-4 me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="fw-6 mb-1">Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h6>
                                <p class="mb-0 text-muted">You're logged in as <strong>{{ ucfirst(auth()->user()->role) }}</strong> 
                                    ({{ auth()->user()->email }})</p>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <small class="text-muted">Account Status: 
                                    <span class="badge bg-success">{{ ucfirst(auth()->user()->status) }}</span>
                                </small>
                                <div class="mt-1">
                                    <a href="{{ route('affiliate.logout') }}" class="btn btn-outline-secondary btn-sm" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-logout me-1"></i>Switch Account
                                    </a>
                                    <form id="logout-form" action="{{ route('affiliate.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        @if(auth()->user()->role === 'affiliate' || auth()->user()->role === 'customer')
                        <div class="affiliate-benefits-section mt-3">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <h6 class="fw-6 text-primary mb-2">
                                                <i class="icon-gift me-2"></i>Member Benefits Active
                                            </h6>
                                            <div class="row g-2 small">
                                                <div class="col-6">✓ Commission earnings</div>
                                                <div class="col-6">✓ Order history tracking</div>
                                                <div class="col-6">✓ Referral bonuses</div>
                                                <div class="col-6">✓ Member discounts</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="icon-user-check fs-1 text-success"></i>
                                        </div>
                                        <small class="text-muted">Authenticated Member</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endauth
                    
                    @guest
                    <!-- Checkout Options for Guest Users -->
                    <div class="checkout-options-section mb-4">
                        <h5 class="fw-5 mb_20">Checkout Options</h5>
                        <div class="row g-3">
                            <!-- Guest Checkout Option -->
                            <div class="col-md-6">
                                <div class="checkout-option-card active border rounded p-3" id="guest-checkout-option" onclick="selectCheckoutType('guest')">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="icon-user fs-2 text-primary"></i>
                                        </div>
                                        <h6 class="fw-6 mb-2">Guest Checkout</h6>
                                        <p class="text-muted mb-3">Quick checkout without account</p>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="radio" name="checkout_type" value="guest" checked class="me-2">
                                            <span class="badge bg-success">Faster</span>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                ✓ No registration required<br>
                                                ✓ Quick order process<br>
                                                ✓ Email order tracking
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Create Account Option -->
                            <div class="col-md-6">
                                <div class="checkout-option-card border rounded p-3" id="create-account-option" onclick="selectCheckoutType('register')">
                                    <div class="text-center">
                                        <div class="mb-2">
                                            <i class="icon-user-plus fs-2 text-success"></i>
                                        </div>
                                        <h6 class="fw-6 mb-2">Create Account</h6>
                                        <p class="text-muted mb-3">Join affiliate program & earn</p>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <input type="radio" name="checkout_type" value="register" class="me-2">
                                            <span class="badge bg-primary">Earn Money</span>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                ✓ Affiliate commissions<br>
                                                ✓ Order history<br>
                                                ✓ Exclusive discounts<br>
                                                ✓ Referral bonuses
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Existing User Login -->
                        <div class="text-center mt-3">
                            <p class="mb-2">Already have an account?</p>
                            <button type="button" class="tf-btn btn-outline animate-hover-btn" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="storeCheckoutUrl()">
                                <i class="icon-login me-2"></i>Login to Your Account
                            </button>
                        </div>
                        
                        <!-- Account Creation Form (Hidden by default) -->
                        <div id="account-creation-form" class="mt-4" style="display: none;">
                            <div class="alert-success p-3 rounded mb-3">
                                <h6 class="fw-6 mb-2">
                                    <i class="icon-user-plus me-2"></i>Create Your Affiliate Account
                                </h6>
                                <p class="mb-0">Join our affiliate program and start earning commissions on every sale!</p>
                            </div>
                            
                            <div class="row g-3">
                                <!-- Sponsor ID -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Sponsor ID (Required) *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="sponsor_id" id="sponsor-id" 
                                                   placeholder="Enter sponsor username or referral code" required
                                                   onblur="validateSponsor()" oninput="clearSponsorValidation()">
                                            <div id="sponsor-validation-spinner" class="validation-spinner" style="display: none;">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </div>
                                            <div id="sponsor-validation-icon" class="validation-icon" style="display: none;">
                                                <i class="icon-check text-success" id="sponsor-success-icon" style="display: none;"></i>
                                                <i class="icon-close text-danger" id="sponsor-error-icon" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <div id="sponsor-validation-message" class="validation-message" style="display: none;"></div>
                                        <div id="sponsor-info" class="sponsor-info mt-2" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Username -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Username *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="username" id="registration-username" required
                                                   placeholder="Choose a unique username"
                                                   onblur="validateUsername()" oninput="clearUsernameValidation()">
                                            <div id="username-validation-spinner" class="validation-spinner" style="display: none;">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </div>
                                            <div id="username-validation-icon" class="validation-icon" style="display: none;">
                                                <i class="icon-check text-success" id="username-success-icon" style="display: none;"></i>
                                                <i class="icon-close text-danger" id="username-error-icon" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <div id="username-validation-message" class="validation-message" style="display: none;"></div>
                                        <small class="text-muted">Letters, numbers, and underscores only</small>
                                    </div>
                                </div>
                                
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First Name *</label>
                                        <input type="text" class="form-control" name="firstname" id="registration-firstname" required
                                               placeholder="Enter your first name">
                                    </div>
                                </div>
                                
                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" name="lastname" id="registration-lastname" required
                                               placeholder="Enter your last name">
                                    </div>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Email Address *</label>
                                        <div class="position-relative">
                                            <input type="email" class="form-control" name="email" id="registration-email" required
                                                   placeholder="Enter your email address"
                                                   onblur="validateRegistrationEmail()" oninput="clearRegistrationEmailValidation()">
                                            <div id="registration-email-validation-spinner" class="validation-spinner" style="display: none;">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </div>
                                            <div id="registration-email-validation-icon" class="validation-icon" style="display: none;">
                                                <i class="icon-check text-success" id="registration-email-success-icon" style="display: none;"></i>
                                                <i class="icon-close text-danger" id="registration-email-error-icon" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <div id="registration-email-validation-message" class="validation-message" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number *</label>
                                        <div class="position-relative">
                                            <input type="tel" class="form-control" name="phone" id="registration-phone" required
                                                   placeholder="Enter your phone number"
                                                   onblur="validatePhone()" oninput="clearPhoneValidation()">
                                            <div id="phone-validation-spinner" class="validation-spinner" style="display: none;">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </div>
                                            <div id="phone-validation-icon" class="validation-icon" style="display: none;">
                                                <i class="icon-check text-success" id="phone-success-icon" style="display: none;"></i>
                                                <i class="icon-close text-danger" id="phone-error-icon" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <div id="phone-validation-message" class="validation-message" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Country -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="form-control" name="country" id="registration-country">
                                            <option value="">Select Country</option>
                                            <option value="BD" selected>Bangladesh</option>
                                            <option value="IN">India</option>
                                            <option value="PK">Pakistan</option>
                                            <option value="US">United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="AU">Australia</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Password *</label>
                                        <input type="password" class="form-control" name="password" id="registration-password" required
                                               placeholder="Enter password" minlength="8">
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" name="password_confirmation" required
                                               placeholder="Confirm password">
                                    </div>
                                </div>
                                
                                <!-- Address -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" id="registration-address" rows="2"
                                                  placeholder="Enter your complete address"></textarea>
                                    </div>
                                </div>
                                
                                <!-- Position Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Preferred Position *</label>
                                        <div class="position-selection mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="position" value="left" id="position-left" checked>
                                                <label class="form-check-label" for="position-left">Left</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="position" value="right" id="position-right">
                                                <label class="form-check-label" for="position-right">Right</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Placement Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Placement Type *</label>
                                        <div class="placement-selection mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="placement" value="auto" id="placement-auto" checked
                                                       onchange="toggleManualPlacement()">
                                                <label class="form-check-label" for="placement-auto">
                                                    Auto Placement <small class="text-muted">(Recommended)</small>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="placement" value="manual" id="placement-manual"
                                                       onchange="toggleManualPlacement()">
                                                <label class="form-check-label" for="placement-manual">
                                                    Manual Placement
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Manual Placement Username (Hidden by default) -->
                                <div class="col-md-12" id="manual-placement-section" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Upline Username *</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="upline_username" id="upline-username"
                                                   placeholder="Enter upline username for manual placement"
                                                   onblur="validateUplineUsername()" oninput="clearUplineValidation()">
                                            <div id="upline-validation-spinner" class="validation-spinner" style="display: none;">
                                                <i class="spinner-border spinner-border-sm"></i>
                                            </div>
                                            <div id="upline-validation-icon" class="validation-icon" style="display: none;">
                                                <i class="icon-check text-success" id="upline-success-icon" style="display: none;"></i>
                                                <i class="icon-close text-danger" id="upline-error-icon" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <div id="upline-validation-message" class="validation-message" style="display: none;"></div>
                                        <div id="upline-info" class="upline-info mt-2" style="display: none;"></div>
                                    </div>
                                </div>
                                
                                <!-- Marketing Consent -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="marketing" id="marketing-consent">
                                        <label class="form-check-label" for="marketing-consent">
                                            I agree to receive marketing communications and newsletters
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Terms & Conditions -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="agree-terms" required>
                                        <label class="form-check-label" for="agree-terms">
                                            I agree to the <a href="{{ route('page.terms-conditions') }}" target="_blank">Terms & Conditions</a> *
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest

                    <h5 class="fw-5 mb_20">Billing details</h5>
                    <form class="form-checkout" method="POST" action="{{ auth()->check() && auth()->user()->role === 'affiliate' ? route('member.orders.store') : route('orders.store') }}">
                        @csrf
                        <input type="hidden" name="checkout_type" id="checkout-type-input" value="{{ auth()->check() ? 'authenticated' : 'guest' }}">
                        
        <!-- Hidden fields for backend compatibility -->
        <input type="hidden" name="city" id="city-hidden">
        <input type="hidden" name="zip_code" id="zip-code-hidden">
        <input type="hidden" name="state" id="state-hidden">
        <input type="hidden" name="area" id="area-hidden">
        <input type="hidden" name="shipping_method" id="shipping-method-hidden">
        <input type="hidden" name="subtotal" id="subtotal-hidden">
        <input type="hidden" name="shipping_cost" id="shipping-cost-hidden">
        <input type="hidden" name="tax_amount" id="tax-amount-hidden">
        <input type="hidden" name="total" id="total-hidden">
        <input type="hidden" name="discount_amount" id="discount-amount-hidden">
        <input type="hidden" name="order_notes" id="order-notes-hidden">
        <!-- Cart items will be added dynamically as individual fields -->                        <div class="box grid-2">
                            <fieldset class="fieldset">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="First Name" 
                                       value="{{ old('first_name', auth()->user()->first_name ?? '') }}" required>
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('customer_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </fieldset>
                            <fieldset class="fieldset">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Last Name" 
                                       value="{{ old('last_name', auth()->user()->last_name ?? '') }}" required>
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </fieldset>
                        </div>
                        
                        <fieldset class="box fieldset">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Email" 
                                   value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('customer_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        
                        <fieldset class="box fieldset">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" placeholder="Phone Number" 
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('customer_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        
        <fieldset class="box fieldset">
            <label for="address">Address</label>
            <input type="text" name="address" id="address" placeholder="Street address" 
                   value="{{ old('address', auth()->user()->address ?? '') }}" required>
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </fieldset>
        
        <fieldset class="box fieldset">
            <label for="zip_code">ZIP Code (Optional)</label>
            <input type="text" name="zip_code" id="zip_code" placeholder="ZIP Code" 
                   value="{{ old('zip_code') }}">
            @error('zip_code')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </fieldset>                        <!-- Dynamic District Selection -->
                        <fieldset class="box fieldset">
                            <label for="district">District *</label>
                            <div class="select-custom">
                                <select class="tf-select w-100" name="district" id="district-select" required onchange="loadUpazilas()">
                                    <option value="">Select District</option>
                                    <!-- Districts will be loaded dynamically -->
                                </select>
                            </div>
                            @error('district')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('shipping_address.city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        
                        <!-- Dynamic Upazila Selection -->
                        <fieldset class="box fieldset">
                            <label for="upazila">Upazila *</label>
                            <div class="select-custom">
                                <select class="tf-select w-100" name="upazila" id="upazila-select" required onchange="loadWards()">
                                    <option value="">Select Upazila</option>
                                </select>
                            </div>
                            @error('upazila')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        
                        <!-- Dynamic Ward Selection -->
                        <fieldset class="box fieldset">
                            <label for="ward">Ward/Union</label>
                            <div class="select-custom">
                                <select class="tf-select w-100" name="ward" id="ward-select" onchange="calculateShipping()">
                                    <option value="">Select Ward/Union</option>
                                </select>
                            </div>
                        </fieldset>
                        
                        <fieldset class="box fieldset">
                            <label for="note">Order notes (optional)</label>
                            <textarea name="note" id="note" placeholder="Notes about your order, e.g. special notes for delivery">{{ old('note') }}</textarea>
                        </fieldset>
                </div>
                
                <div class="tf-page-cart-footer">
                    <div class="tf-cart-footer-inner">
                        <h5 class="fw-5 mb_20">Your order</h5>
                        <div class="tf-page-cart-checkout widget-wrap-checkout">
                            <!-- Order Items -->
                            <ul class="wrap-checkout-product">
                                @if(session('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $id => $item)
                                    <li class="checkout-product-item" data-product-id="{{ $id }}" data-id="{{ $id }}">
                                        <figure class="img-product">
                                            <img src="{{ 
                                                isset($item['image']) && $item['image'] 
                                                    ? (str_starts_with($item['image'], 'http') 
                                                        ? $item['image'] 
                                                        : asset('storage/' . $item['image'])) 
                                                    : asset('storage/default-product.jpg') 
                                            }}" alt="product">
                                            <span class="quantity">{{ $item['quantity'] }}</span>
                                        </figure>
                                        <div class="content">
                                            <div class="info">
                                                <p class="name">{{ $item['name'] }}</p>
                                                @if(isset($item['size']) || isset($item['color']))
                                                    <span class="variant">
                                                        {{ isset($item['color']) ? $item['color'] : '' }}
                                                        {{ isset($item['color']) && isset($item['size']) ? ' / ' : '' }}
                                                        {{ isset($item['size']) ? $item['size'] : '' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="price">৳{{ number_format($item['price'] * $item['quantity']) }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="text-center py-4">
                                        <p class="text-muted">Your cart is empty</p>
                                    </li>
                                @endif
                            </ul>
                            
                            @if(session('cart') && count(session('cart')) > 0)
                                <!-- Coupon Section -->
                                <div class="coupon-box">
                                    <input type="text" name="coupon_code" id="coupon-code-input" placeholder="Discount code" value="{{ old('coupon_code') }}">
                                    <button type="button" id="apply-coupon-btn" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" onclick="applyCoupon()">Apply</button>
                                </div>
                                
                                @php
                                    $subtotal = 0;
                                    if(session('cart')) {
                                        foreach(session('cart') as $item) {
                                            $subtotal += $item['price'] * $item['quantity'];
                                        }
                                    }
                                    $shipping = session('shipping_cost', 0);
                                    $discount = session('discount', 0);
                                    $total = $subtotal + $shipping - $discount;
                                @endphp
                                
                                <!-- Order Summary -->
                                <div class="checkout-summary">
                                    <div class="d-flex justify-content-between line pb_10">
                                        <span>Subtotal</span>
                                        <span id="subtotal-amount">৳{{ number_format($subtotal) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between line pb_10" id="shipping-row" style="{{ $shipping > 0 ? '' : 'display: none;' }}">
                                        <span>Shipping</span>
                                        <span id="shipping-amount">৳{{ number_format($shipping) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between line pb_10" id="discount-row" style="{{ $discount > 0 ? '' : 'display: none;' }}">
                                        <span>Discount</span>
                                        <span class="text-success" id="discount-amount">-৳{{ number_format($discount) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between line pb_20">
                                        <h6 class="fw-5">Total</h6>
                                        <h6 class="total fw-5" id="total-amount">৳{{ number_format($total) }}</h6>
                                    </div>
                                </div>
                                
                                <!-- Payment Methods -->
                                <div class="wd-check-payment">
                                    <h6 class="fw-5 mb_15">Payment Method</h6>
                                    
                                    <!-- Cash on Delivery -->
                                    <div class="fieldset-radio mb_15 payment-method" data-method="cash_on_delivery">
                                        <input type="radio" name="payment_method" id="cod" class="tf-check" value="cash_on_delivery" checked>
                                        <label for="cod">
                                            <i class="icon-dollar me-2"></i>Cash on Delivery
                                            <small class="d-block text-muted">Pay when you receive your order</small>
                                        </label>
                                    </div>
                                    
                                    <!-- Mobile Banking -->
                                    <div class="fieldset-radio mb_15 payment-method" data-method="mobile_banking">
                                        <input type="radio" name="payment_method" id="mobile-banking" class="tf-check" value="online_payment">
                                        <label for="mobile-banking">
                                            <i class="icon-phone me-2"></i>Mobile Banking
                                            <small class="d-block text-muted">bKash, Nagad, Rocket</small>
                                        </label>
                                        
                                        <!-- Mobile Banking Options -->
                                        <div class="mobile-banking-options mt-3" style="display: none;">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <div class="mobile-option border rounded p-2 text-center" data-provider="bkash">
                                                        <img src="{{ asset('assets/images/bkash.png') }}" alt="bKash" class="mb-1" style="height: 30px;">
                                                        <br><small>bKash</small>
                                                        <input type="radio" name="online_payment_type" value="bkash" style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mobile-option border rounded p-2 text-center" data-provider="nagad">
                                                        <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad" class="mb-1" style="height: 30px;">
                                                        <br><small>Nagad</small>
                                                        <input type="radio" name="online_payment_type" value="nagad" style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mobile-option border rounded p-2 text-center" data-provider="rocket">
                                                        <img src="{{ asset('assets/images/rocket.png') }}" alt="Rocket" class="mb-1" style="height: 30px;">
                                                        <br><small>Rocket</small>
                                                        <input type="radio" name="online_payment_type" value="rocket" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-instructions mt-3" id="payment-instructions" style="display: none;">
                                                <!-- Payment instructions will be shown here -->
                                            </div>
                                            <div class="transaction-id-field mt-3" style="display: none;">
                                                <input type="text" name="transaction_id" class="form-control" placeholder="Enter Transaction ID after payment">
                                                <small class="text-muted">Please enter the transaction ID you received after making the payment</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Bank Transfer -->
                                    <div class="fieldset-radio mb_20 payment-method" data-method="bank_transfer">
                                        <input type="radio" name="payment_method" id="bank-transfer" class="tf-check" value="bank_transfer">
                                        <label for="bank-transfer">
                                            <i class="icon-credit-card me-2"></i>Bank Transfer
                                            <small class="d-block text-muted">Direct bank transfer</small>
                                        </label>
                                        
                                        <!-- Bank Transfer Details -->
                                        <div class="bank-transfer-options mt-3" style="display: none;">
                                            <div class="alert-info p-3 rounded">
                                                <h6 class="fw-6">Bank Details</h6>
                                                <p class="mb-1"><strong>Bank:</strong> Dutch-Bangla Bank Limited</p>
                                                <p class="mb-1"><strong>Account:</strong> 123-456-789-012</p>
                                                <p class="mb-1"><strong>Account Name:</strong> OSmart Bangladesh</p>
                                                <p class="mb-0"><strong>Branch:</strong> Dhaka Main Branch</p>
                                            </div>
                                            <div class="mt-3">
                                                <input type="text" name="bank_transaction_ref" class="form-control" placeholder="Bank Transaction Reference">
                                                <small class="text-muted">Please enter your bank transaction reference number</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('shipping_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('cart_items')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('subtotal')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('shipping_cost')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('tax_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('total')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('discount_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('coupon_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('online_payment_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('transaction_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('bank_transaction_ref')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Terms & Conditions -->
                                <p class="text_black-2 mb_20">Your personal data will be used to process your order and for other purposes described in our <a href="#" class="text-decoration-underline">privacy policy</a>.</p>
                                
                                <div class="box-checkbox fieldset-radio mb_20">
                                    <input type="checkbox" id="check-agree" class="tf-check" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                                    <label for="check-agree" class="text_black-2">I have read and agree to the website <a href="#" class="text-decoration-underline">terms and conditions</a>.</label>
                                    @error('terms')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Place Order Button -->
                                <button type="button" class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center w-100" onclick="placeOrder()">
                                    Place Order
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                    </form>
            </div>
        </div>
    </section>
    <!-- /page-cart -->

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login to Your Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Display Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form id="loginForm" action="{{ route('affiliate.login.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                        <input type="hidden" name="from_checkout" value="1">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <button type="submit" class="tf-btn btn-fill w-100">Login</button>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('affiliate.register') }}" class="text-decoration-none">Don't have an account? Register here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .checkout-option-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .checkout-option-card.active {
            border-color: var(--primary-color) !important;
            background-color: rgba(var(--primary-color-rgb), 0.05);
        }
        .checkout-option-card:hover {
            border-color: var(--primary-color) !important;
        }
        .payment-method {
            transition: all 0.3s ease;
        }
        .mobile-option {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .mobile-option.selected {
            border-color: var(--primary-color) !important;
            background-color: rgba(var(--primary-color-rgb), 0.05);
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        
        .free-shipping-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .tf-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(var(--primary-color-rgb), 0.25);
        }
        
        .checkout-summary .line {
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
            margin-bottom: 8px;
        }
        
        .checkout-summary .line:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .tf-select option:disabled {
            color: #999;
            font-style: italic;
        }
        
        .tf-select.loading {
            background-image: url('data:image/svg+xml;charset=utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .auto-selected {
            background-color: #f0f8f0;
            border-color: #28a745;
        }
        
        .shipping-calculating {
            color: #6c757d;
            font-style: italic;
        }
        
        /* Real-time Validation Styles */
        .position-relative {
            position: relative;
        }
        
        .validation-spinner,
        .validation-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
        }
        
        .validation-message {
            font-size: 13px;
            margin-top: 5px;
        }
        
        .validation-message.success {
            color: #28a745;
        }
        
        .validation-message.error {
            color: #dc3545;
        }
        
        .validation-message.warning {
            color: #ffc107;
        }
        
        .form-control.valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .form-control.invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .form-control.checking {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        
        .sponsor-info,
        .upline-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 8px 12px;
        }
        
        .sponsor-info .sponsor-details,
        .upline-info .upline-details {
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .sponsor-info .sponsor-avatar,
        .upline-info .upline-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .position-selection .form-check-inline {
            margin-right: 1rem;
        }
        
        .placement-selection .form-check {
            margin-bottom: 0.5rem;
        }
    </style>
@endpush
@push('scripts')
    <script>
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDistricts();
            initializePaymentMethods();
            initializeRegistrationValidation();
            
            // Show login modal if there are login errors
            @if ($errors->has('email') || $errors->has('password'))
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif
            
            // Hide login modal and show success message if user just logged in
            @if (session('success') && auth()->check())
                var loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                if (loginModal) {
                    loginModal.hide();
                }
                // Show success notification
                setTimeout(function() {
                    alert('{{ session('success') }}');
                }, 500);
            @endif
        });
        
        // Initialize registration form validation
        function initializeRegistrationValidation() {
            // Auto-sync billing form with registration data
            setupRegistrationSync();
        }
        
        // Setup sync between registration and billing forms
        function setupRegistrationSync() {
            const registrationFields = [
                { reg: 'registration-firstname', bill: 'first_name' },
                { reg: 'registration-lastname', bill: 'last_name' },
                { reg: 'registration-email', bill: 'email' },
                { reg: 'registration-phone', bill: 'phone' },
                { reg: 'registration-address', bill: 'address' }
            ];
            
            registrationFields.forEach(field => {
                const regInput = document.getElementById(field.reg);
                const billInput = document.getElementById(field.bill);
                
                if (regInput && billInput) {
                    regInput.addEventListener('input', function() {
                        billInput.value = this.value;
                    });
                }
            });
        }
        
        // Toggle manual placement section
        function toggleManualPlacement() {
            const manualPlacement = document.getElementById('placement-manual');
            const manualSection = document.getElementById('manual-placement-section');
            const uplineInput = document.getElementById('upline-username');
            
            if (manualPlacement.checked) {
                manualSection.style.display = 'block';
                uplineInput.setAttribute('required', 'required');
            } else {
                manualSection.style.display = 'none';
                uplineInput.removeAttribute('required');
                clearUplineValidation();
            }
        }
        
        // Validation functions for each field
        
        // Sponsor validation
        function validateSponsor() {
            const sponsorInput = document.getElementById('sponsor-id');
            const sponsorId = sponsorInput.value.trim();
            
            if (!sponsorId) {
                showSponsorValidation('error', 'Sponsor ID is required');
                return;
            }
            
            showSponsorChecking();
            
            fetch(`/api/validate-sponsor?sponsor_id=${encodeURIComponent(sponsorId)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        showSponsorValidation('success', 'Valid sponsor found!');
                        showSponsorInfo(data.sponsor);
                    } else {
                        showSponsorValidation('error', data.message || 'Sponsor not found');
                        hideSponsorInfo();
                    }
                })
                .catch(error => {
                    console.error('Sponsor validation error:', error);
                    showSponsorValidation('error', 'Unable to validate sponsor');
                });
        }
        
        function clearSponsorValidation() {
            clearValidationState('sponsor');
        }
        
        function showSponsorChecking() {
            showValidationChecking('sponsor', 'Validating sponsor...');
        }
        
        function showSponsorValidation(type, message) {
            showValidationResult('sponsor', type, message);
        }
        
        function showSponsorInfo(sponsor) {
            const infoDiv = document.getElementById('sponsor-info');
            infoDiv.innerHTML = `
                <div class="sponsor-details">
                    <img src="${sponsor.avatar}" alt="${sponsor.name}" class="sponsor-avatar">
                    <div>
                        <strong>${sponsor.name}</strong> (@${sponsor.username})<br>
                        <small class="text-muted">Status: ${sponsor.status} • Role: ${sponsor.role}</small>
                    </div>
                </div>
            `;
            infoDiv.style.display = 'block';
        }
        
        function hideSponsorInfo() {
            document.getElementById('sponsor-info').style.display = 'none';
        }
        
        // Username validation
        function validateUsername() {
            const usernameInput = document.getElementById('registration-username');
            const username = usernameInput.value.trim();
            
            if (!username) {
                showUsernameValidation('error', 'Username is required');
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                showUsernameValidation('error', 'Username can only contain letters, numbers, and underscores');
                return;
            }
            
            showUsernameChecking();
            
            fetch(`/api/check-username?username=${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        showUsernameValidation('success', 'Username is available!');
                    } else {
                        showUsernameValidation('error', data.message || 'Username is not available');
                    }
                })
                .catch(error => {
                    console.error('Username validation error:', error);
                    showUsernameValidation('error', 'Unable to validate username');
                });
        }
        
        function clearUsernameValidation() {
            clearValidationState('username');
        }
        
        function showUsernameChecking() {
            showValidationChecking('username', 'Checking username availability...');
        }
        
        function showUsernameValidation(type, message) {
            showValidationResult('username', type, message);
        }
        
        // Email validation
        function validateRegistrationEmail() {
            const emailInput = document.getElementById('registration-email');
            const email = emailInput.value.trim();
            
            if (!email) {
                showRegistrationEmailValidation('error', 'Email is required');
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showRegistrationEmailValidation('error', 'Please enter a valid email address');
                return;
            }
            
            showRegistrationEmailChecking();
            
            fetch(`/api/check-email?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        showRegistrationEmailValidation('success', 'Email is available!');
                    } else {
                        showRegistrationEmailValidation('error', data.message || 'Email is not available');
                    }
                })
                .catch(error => {
                    console.error('Email validation error:', error);
                    showRegistrationEmailValidation('error', 'Unable to validate email');
                });
        }
        
        function clearRegistrationEmailValidation() {
            clearValidationState('registration-email');
        }
        
        function showRegistrationEmailChecking() {
            showValidationChecking('registration-email', 'Checking email availability...');
        }
        
        function showRegistrationEmailValidation(type, message) {
            showValidationResult('registration-email', type, message);
        }
        
        // Phone validation
        function validatePhone() {
            const phoneInput = document.getElementById('registration-phone');
            const phone = phoneInput.value.trim();
            
            if (!phone) {
                showPhoneValidation('error', 'Phone number is required');
                return;
            }
            
            showPhoneChecking();
            
            fetch(`/api/check-phone?phone=${encodeURIComponent(phone)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        showPhoneValidation('success', 'Phone number is available!');
                    } else {
                        showPhoneValidation('error', data.message || 'Phone number is not available');
                    }
                })
                .catch(error => {
                    console.error('Phone validation error:', error);
                    showPhoneValidation('error', 'Unable to validate phone number');
                });
        }
        
        function clearPhoneValidation() {
            clearValidationState('phone');
        }
        
        function showPhoneChecking() {
            showValidationChecking('phone', 'Checking phone availability...');
        }
        
        function showPhoneValidation(type, message) {
            showValidationResult('phone', type, message);
        }
        
        // Upline username validation (for manual placement)
        function validateUplineUsername() {
            const uplineInput = document.getElementById('upline-username');
            const username = uplineInput.value.trim();
            
            if (!username) {
                showUplineValidation('error', 'Upline username is required for manual placement');
                return;
            }
            
            showUplineChecking();
            
            fetch(`/api/validate-upline-username`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username: username })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        showUplineValidation('success', 'Valid upline user found!');
                        showUplineInfo(data.upline);
                    } else {
                        showUplineValidation('error', data.message || 'Upline user not found');
                        hideUplineInfo();
                    }
                })
                .catch(error => {
                    console.error('Upline validation error:', error);
                    showUplineValidation('error', 'Unable to validate upline username');
                });
        }
        
        function clearUplineValidation() {
            clearValidationState('upline');
            hideUplineInfo();
        }
        
        function showUplineChecking() {
            showValidationChecking('upline', 'Validating upline user...');
        }
        
        function showUplineValidation(type, message) {
            showValidationResult('upline', type, message);
        }
        
        function showUplineInfo(upline) {
            const infoDiv = document.getElementById('upline-info');
            infoDiv.innerHTML = `
                <div class="upline-details">
                    <div>
                        <strong>${upline.name}</strong> (@${upline.username})<br>
                        <small class="text-muted">Status: ${upline.status}</small>
                    </div>
                </div>
            `;
            infoDiv.style.display = 'block';
        }
        
        function hideUplineInfo() {
            document.getElementById('upline-info').style.display = 'none';
        }
        
        // Generic validation helper functions
        function clearValidationState(field) {
            const input = document.getElementById(field === 'registration-email' ? 'registration-email' : 
                                              field === 'phone' ? 'registration-phone' :
                                              field === 'username' ? 'registration-username' :
                                              field === 'sponsor' ? 'sponsor-id' :
                                              field === 'upline' ? 'upline-username' : field);
            const spinner = document.getElementById(`${field}-validation-spinner`);
            const icon = document.getElementById(`${field}-validation-icon`);
            const message = document.getElementById(`${field}-validation-message`);
            
            if (input) input.classList.remove('valid', 'invalid', 'checking');
            if (spinner) spinner.style.display = 'none';
            if (icon) icon.style.display = 'none';
            if (message) message.style.display = 'none';
        }
        
        function showValidationChecking(field, message) {
            const input = document.getElementById(field === 'registration-email' ? 'registration-email' : 
                                              field === 'phone' ? 'registration-phone' :
                                              field === 'username' ? 'registration-username' :
                                              field === 'sponsor' ? 'sponsor-id' :
                                              field === 'upline' ? 'upline-username' : field);
            const spinner = document.getElementById(`${field}-validation-spinner`);
            const icon = document.getElementById(`${field}-validation-icon`);
            const messageDiv = document.getElementById(`${field}-validation-message`);
            
            if (input) {
                input.classList.remove('valid', 'invalid');
                input.classList.add('checking');
            }
            
            if (spinner) spinner.style.display = 'block';
            if (icon) icon.style.display = 'none';
            
            if (messageDiv) {
                messageDiv.textContent = message;
                messageDiv.className = 'validation-message warning';
                messageDiv.style.display = 'block';
            }
        }
        
        function showValidationResult(field, type, message) {
            const input = document.getElementById(field === 'registration-email' ? 'registration-email' : 
                                              field === 'phone' ? 'registration-phone' :
                                              field === 'username' ? 'registration-username' :
                                              field === 'sponsor' ? 'sponsor-id' :
                                              field === 'upline' ? 'upline-username' : field);
            const spinner = document.getElementById(`${field}-validation-spinner`);
            const icon = document.getElementById(`${field}-validation-icon`);
            const messageDiv = document.getElementById(`${field}-validation-message`);
            const successIcon = document.getElementById(`${field}-success-icon`);
            const errorIcon = document.getElementById(`${field}-error-icon`);
            
            // Clear checking state
            if (input) input.classList.remove('checking');
            if (spinner) spinner.style.display = 'none';
            
            // Set validation state
            if (type === 'success') {
                if (input) {
                    input.classList.remove('invalid');
                    input.classList.add('valid');
                }
                if (successIcon) successIcon.style.display = 'block';
                if (errorIcon) errorIcon.style.display = 'none';
            } else {
                if (input) {
                    input.classList.remove('valid');
                    input.classList.add('invalid');
                }
                if (successIcon) successIcon.style.display = 'none';
                if (errorIcon) errorIcon.style.display = 'block';
            }
            
            // Show icon and message
            if (icon) icon.style.display = 'block';
            if (messageDiv) {
                messageDiv.innerHTML = message;
                messageDiv.className = `validation-message ${type}`;
                messageDiv.style.display = 'block';
            }
        }

        // Checkout type selection
        function selectCheckoutType(type) {
            document.querySelectorAll('.checkout-option-card').forEach(card => {
                card.classList.remove('active');
            });
            
            if (type === 'guest') {
                document.getElementById('guest-checkout-option').classList.add('active');
                document.getElementById('account-creation-form').style.display = 'none';
                document.querySelector('input[name="checkout_type"][value="guest"]').checked = true;
            } else {
                document.getElementById('create-account-option').classList.add('active');
                document.getElementById('account-creation-form').style.display = 'block';
                document.querySelector('input[name="checkout_type"][value="register"]').checked = true;
            }
            
            document.getElementById('checkout-type-input').value = type;
        }

        // Delivery charges data from database
        @php
            $deliveryCharges = \Illuminate\Support\Facades\DB::table('delivery_charges')
                ->where('is_active', 1)
                ->orderBy('district')
                ->orderBy('upazila')
                ->orderBy('ward')
                ->get()
                ->groupBy('district');
        @endphp
        
        const deliveryCharges = @json($deliveryCharges->toArray());
        
        // Cart data from server session for reliable access
        const serverCartData = @json(session('cart', []));
        
        // Shipping config from config/shipping.php
        const shippingConfig = {
            freeShippingEnabled: {{ config('shipping.free_shipping.enabled') ? 'true' : 'false' }},
            freeShippingThreshold: {{ config('shipping.free_shipping.minimum_order', 1000) }},
            currency: '{{ config('shipping.currency', '৳') }}'
        };
        
        // Load districts from delivery_charges table
        function loadDistricts() {
            const districtSelect = document.getElementById('district-select');
            districtSelect.innerHTML = '<option value="">Loading districts...</option>';
            
            // Use delivery charges data from database
            if (deliveryCharges && Object.keys(deliveryCharges).length > 0) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                
                // Sort districts alphabetically
                const sortedDistricts = Object.keys(deliveryCharges).sort();
                sortedDistricts.forEach(district => {
                    districtSelect.innerHTML += `<option value="${district}">${district}</option>`;
                });
                
                console.log('Loaded', sortedDistricts.length, 'districts');
            } else {
                console.error('No delivery charges data found');
                districtSelect.innerHTML = '<option value="">No districts available</option>';
            }
        }

        // Load upazilas based on selected district
        function loadUpazilas() {
            const district = document.getElementById('district-select').value;
            const upazilaSelect = document.getElementById('upazila-select');
            const wardSelect = document.getElementById('ward-select');
            
            // Show loading state
            upazilaSelect.classList.add('loading');
            upazilaSelect.innerHTML = '<option value="">Loading upazilas...</option>';
            wardSelect.innerHTML = '<option value="">Select Ward/Union</option>';
            
            // Hide shipping info when district changes
            hideShippingDisplay();
            
            upazilaSelect.classList.remove('loading');
            
            if (!district || !deliveryCharges[district]) {
                console.log('No district selected or no delivery charges found for:', district);
                upazilaSelect.innerHTML = '<option value="">Select District First</option>';
                return;
            }
                
                console.log('Loading upazilas for district:', district);
                console.log('Available data:', deliveryCharges[district]);
                
                // Get unique upazilas for the selected district
                const upazilas = [...new Set(deliveryCharges[district]
                    .map(item => item.upazila)
                    .filter(upazila => upazila && upazila.trim() !== '' && upazila !== null)
                )];
                
                console.log('Found upazilas:', upazilas);
                
                upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
                
                if (upazilas.length > 0) {
                    // Add upazilas to dropdown with proper sorting
                    upazilas.sort((a, b) => a.localeCompare(b, 'en', { numeric: true, sensitivity: 'base' }))
                           .forEach(upazila => {
                        upazilaSelect.innerHTML += `<option value="${upazila}">${upazila}</option>`;
                    });
                    
                    // Auto-select first upazila if only one available
                    if (upazilas.length === 1) {
                        upazilaSelect.value = upazilas[0];
                        upazilaSelect.classList.add('auto-selected');
                        console.log('Auto-selected upazila:', upazilas[0]);
                        upazilaSelect.classList.remove('auto-selected');
                        loadWards();
                    }
                } else {
                    // District level delivery only
                    upazilaSelect.innerHTML += '<option value="District Level">District Level Delivery</option>';
                    upazilaSelect.value = "District Level";
                    upazilaSelect.classList.add('auto-selected');
                    wardSelect.innerHTML += '<option value="District Level">District Level</option>';
                    wardSelect.value = "District Level";
                    wardSelect.classList.add('auto-selected');
                    
                    console.log('Auto-selected District Level delivery');
                    
                    upazilaSelect.classList.remove('auto-selected');
                    wardSelect.classList.remove('auto-selected');
                    calculateShipping();
                }
        }

        // Load wards based on selected upazila
        function loadWards() {
            const district = document.getElementById('district-select').value;
            const upazila = document.getElementById('upazila-select').value;
            const wardSelect = document.getElementById('ward-select');
            
            // Show loading state
            wardSelect.classList.add('loading');
            wardSelect.innerHTML = '<option value="">Loading wards...</option>';
            
            // Hide shipping info when upazila changes
            hideShippingDisplay();
            
            wardSelect.classList.remove('loading');
            
            if (!district || !upazila || !deliveryCharges[district]) {
                console.log('Missing required selections:', {district, upazila});
                wardSelect.innerHTML = '<option value="">Select Upazila First</option>';
                return;
            }
                
                console.log('Loading wards for:', {district, upazila});
                
                wardSelect.innerHTML = '<option value="">Select Ward/Union</option>';
                
                if (upazila === 'District Level') {
                    wardSelect.innerHTML += '<option value="District Level">District Level</option>';
                    wardSelect.value = "District Level";
                    wardSelect.classList.add('auto-selected');
                    wardSelect.classList.remove('auto-selected');
                    calculateShipping();
                    return;
                }
                
                // Get wards for the selected upazila
                const wards = deliveryCharges[district]
                    .filter(item => item.upazila === upazila)
                    .map(item => item.ward)
                    .filter(ward => ward && ward.trim() !== '' && ward !== null)
                    .filter((ward, index, self) => self.indexOf(ward) === index) // unique wards
                    .sort((a, b) => {
                        // Custom sorting to handle ward names with numbers and text properly
                        // Extract numbers if present (e.g., "Ward 1", "Ward 10", etc.)
                        const aMatch = a.match(/Ward (\d+)/i);
                        const bMatch = b.match(/Ward (\d+)/i);
                        
                        if (aMatch && bMatch) {
                            // Both are numbered wards, sort numerically
                            return parseInt(aMatch[1]) - parseInt(bMatch[1]);
                        } else if (aMatch && !bMatch) {
                            // a is numbered ward, b is not - numbered wards first
                            return -1;
                        } else if (!aMatch && bMatch) {
                            // b is numbered ward, a is not - numbered wards first
                            return 1;
                        } else {
                            // Neither is numbered ward, sort alphabetically
                            return a.localeCompare(b, 'en', { numeric: true, sensitivity: 'base' });
                        }
                    });
                
                console.log('Found wards:', wards);
                
                if (wards.length > 0) {
                    // Add wards to dropdown
                    wards.forEach(ward => {
                        wardSelect.innerHTML += `<option value="${ward}">${ward}</option>`;
                    });
                    
                    // Auto-select first ward if only one available
                    if (wards.length === 1) {
                        wardSelect.value = wards[0];
                        wardSelect.classList.add('auto-selected');
                        console.log('Auto-selected ward:', wards[0]);
                        wardSelect.classList.remove('auto-selected');
                        calculateShipping();
                    }
                } else {
                    // Upazila level delivery
                    wardSelect.innerHTML += '<option value="Upazila Level">Upazila Level Delivery</option>';
                    wardSelect.value = "Upazila Level";
                    wardSelect.classList.add('auto-selected');
                    console.log('Auto-selected Upazila Level delivery');
                    wardSelect.classList.remove('auto-selected');
                    calculateShipping();
                }
        }

        // Calculate shipping charges
        function calculateShipping() {
            const district = document.getElementById('district-select').value;
            const upazila = document.getElementById('upazila-select').value;
            const ward = document.getElementById('ward-select').value;
            
            console.log('Calculating shipping for:', {district, upazila, ward});
            
            if (!district) {
                console.log('No district selected, hiding shipping');
                hideShippingDisplay();
                return;
            }
            
            let deliveryCharge = null;
            
            // Find matching delivery charge
            if (deliveryCharges && deliveryCharges[district]) {
                console.log('Searching in delivery charges for district:', district);
                
                // Handle different selection levels
                if (upazila === 'District Level' || !upazila) {
                    // District level delivery
                    deliveryCharge = deliveryCharges[district].find(item => 
                        !item.upazila || item.upazila === null || item.upazila === ''
                    );
                    console.log('Looking for district level charge:', deliveryCharge);
                } else if (ward && ward !== 'Upazila Level') {
                    // Try to find exact ward match
                    deliveryCharge = deliveryCharges[district].find(item => 
                        item.upazila === upazila && item.ward === ward
                    );
                    console.log('Looking for ward level charge:', deliveryCharge);
                }
                
                // Fallback to upazila level
                if (!deliveryCharge && upazila && upazila !== 'District Level') {
                    deliveryCharge = deliveryCharges[district].find(item => 
                        item.upazila === upazila && (!item.ward || item.ward === null || item.ward === '')
                    );
                    console.log('Fallback to upazila level charge:', deliveryCharge);
                }
                
                // Final fallback to district level
                if (!deliveryCharge) {
                    deliveryCharge = deliveryCharges[district].find(item => 
                        !item.upazila || item.upazila === null || item.upazila === ''
                    );
                    console.log('Final fallback to district charge:', deliveryCharge);
                }
            }
            
            if (deliveryCharge) {
                const charge = parseFloat(deliveryCharge.charge) || 0;
                const deliveryTime = deliveryCharge.estimated_delivery_time || '3-5 business days';
                
                console.log('Found delivery charge:', charge, 'Delivery time:', deliveryTime);
                
                // Get current cart total
                const cartTotal = {{ $subtotal }};
                
                // Check for free shipping
                let finalCharge = charge;
                let isFreeShipping = false;
                
                if (shippingConfig.freeShippingEnabled && cartTotal >= shippingConfig.freeShippingThreshold) {
                    finalCharge = 0;
                    isFreeShipping = true;
                    console.log('Free shipping applied!');
                }
                
                // Update display
                updateShippingDisplay(finalCharge, isFreeShipping, deliveryTime);
                updateOrderTotal();
            } else {
                console.log('No delivery charge found, using default');
                updateShippingDisplay(100, false, '3-5 business days'); // Default shipping
                updateOrderTotal();
            }
        }
        
        // Hide shipping display
        function hideShippingDisplay() {
            const shippingRow = document.getElementById('shipping-row');
            const freeShippingMessage = document.querySelector('.free-shipping-message');
            const shippingAmount = document.getElementById('shipping-amount');
            
            if (shippingRow) {
                shippingRow.style.display = 'none';
            }
            
            if (freeShippingMessage) {
                freeShippingMessage.style.display = 'none';
            }
            
            // Reset shipping cost data attribute
            if (shippingAmount) {
                shippingAmount.setAttribute('data-cost', '0');
            }
            
            // Reset total to subtotal only
            const subtotal = {{ $subtotal }};
            document.getElementById('total-amount').textContent = `৳${subtotal.toLocaleString()}`;
        }

        // Update shipping display
        function updateShippingDisplay(cost, isFree, deliveryTime = '3-5 business days') {
            const shippingRow = document.getElementById('shipping-row');
            const shippingAmount = document.getElementById('shipping-amount');
            
            // Store the actual cost as a data attribute for proper parsing
            const finalCost = isFree ? 0 : cost;
            shippingAmount.setAttribute('data-cost', finalCost);
            
            if (cost > 0 && !isFree) {
                shippingAmount.textContent = `৳${cost}`;
                shippingRow.style.display = 'flex';
            } else if (isFree) {
                shippingAmount.innerHTML = '<span class="text-success">FREE</span>';
                shippingRow.style.display = 'flex';
                
                // Show free shipping message
                showFreeShippingMessage();
            } else {
                shippingAmount.setAttribute('data-cost', '0');
                shippingRow.style.display = 'none';
            }
            
            // Show delivery time if element exists (you may want to add this to your template)
            console.log(`Estimated delivery: ${deliveryTime}`);
        }
        
        // Show free shipping message
        function showFreeShippingMessage() {
            // Find or create free shipping message element
            let messageElement = document.querySelector('.free-shipping-message');
            if (!messageElement) {
                messageElement = document.createElement('div');
                messageElement.className = 'free-shipping-message alert alert-success mt-2';
                
                // Insert after shipping row
                const shippingRow = document.getElementById('shipping-row');
                if (shippingRow) {
                    shippingRow.insertAdjacentElement('afterend', messageElement);
                }
            }
            
            if (messageElement) {
                messageElement.innerHTML = '🎉 You qualify for FREE shipping!';
                messageElement.style.display = 'block';
            }
        }

        // Initialize payment methods
        function initializePaymentMethods() {
            // Payment method selection
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide all payment options
                    document.querySelectorAll('.mobile-banking-options, .bank-transfer-options').forEach(el => {
                        el.style.display = 'none';
                    });

                    // Show selected payment options
                    if (this.value === 'online_payment') {
                        this.closest('.payment-method').querySelector('.mobile-banking-options').style.display = 'block';
                    } else if (this.value === 'bank_transfer') {
                        this.closest('.payment-method').querySelector('.bank-transfer-options').style.display = 'block';
                    }
                });
            });

            // Mobile banking provider selection
            document.querySelectorAll('.mobile-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.mobile-option').forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;
                    showPaymentInstructions(this.dataset.provider);
                    
                    // Show transaction ID field
                    const transactionField = document.querySelector('.transaction-id-field');
                    if (transactionField) {
                        transactionField.style.display = 'block';
                    }
                });
            });
        }

        // Show payment instructions for selected provider
        function showPaymentInstructions(provider) {
            const instructionsDiv = document.getElementById('payment-instructions');
            const instructions = {
                bkash: {
                    title: 'bKash Payment Instructions',
                    steps: [
                        'Dial *247# or use bKash app',
                        'Select "Send Money"',
                        'Enter merchant number: 01700000000',
                        'Enter total amount',
                        'Enter PIN to confirm',
                        'Save the transaction ID'
                    ]
                },
                nagad: {
                    title: 'Nagad Payment Instructions',
                    steps: [
                        'Dial *167# or use Nagad app',
                        'Select "Send Money"',
                        'Enter merchant number: 01800000000',
                        'Enter total amount',
                        'Enter PIN to confirm',
                        'Save the transaction ID'
                    ]
                },
                rocket: {
                    title: 'Rocket Payment Instructions',
                    steps: [
                        'Dial *322# or use Rocket app',
                        'Select "Send Money"',
                        'Enter merchant number: 01900000000',
                        'Enter total amount',
                        'Enter PIN to confirm',
                        'Save the transaction ID'
                    ]
                }
            };

            const instruction = instructions[provider];
            if (instruction) {
                let html = `<h6 class="fw-6">${instruction.title}</h6><ol>`;
                instruction.steps.forEach(step => {
                    html += `<li>${step}</li>`;
                });
                html += '</ol><p class="text-warning mb-0">Please note down the transaction ID after payment completion.</p>';
                
                instructionsDiv.innerHTML = html;
                instructionsDiv.style.display = 'block';
            }
        }

        // Apply coupon
        // Toast notification system
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
            return container;
        }
        
        // Global variables for coupon management
        let appliedCoupon = null;
        let currentDiscountAmount = 0;
        let currentShippingCost = 0;
        const subtotal = {{ $subtotal }};
        
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
            
            // Get current shipping cost
            const shippingElement = document.getElementById('shipping-amount');
            currentShippingCost = shippingElement ? parseFloat(shippingElement.getAttribute('data-cost') || '0') : 0;
            
            // Prepare cart items data
            const cartItems = [];
            document.querySelectorAll('.checkout-product-item').forEach(item => {
                const productId = item.getAttribute('data-product-id');
                const quantity = item.querySelector('.quantity')?.textContent || '1';
                const price = item.querySelector('.price')?.textContent?.replace(/[^0-9.]/g, '') || '0';
                
                cartItems.push({
                    product_id: productId,
                    quantity: parseInt(quantity),
                    price: parseFloat(price)
                });
            });
            
            // Prepare request data
            const requestData = {
                coupon_code: couponCode,
                subtotal: subtotal,
                cart_items: cartItems,
                shipping_cost: currentShippingCost,
                user_id: {{ auth()->id() ?? 'null' }},
                country: 'BD'
            };
            
            console.log('Applying coupon:', requestData);
            
            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    coupon_code: couponCode,
                    subtotal: subtotal,
                    cart_items: cartItems,
                    shipping_cost: currentShippingCost
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Coupon validation response:', data);
                
                if (data.success) {
                    // Store applied coupon
                    appliedCoupon = data.coupon;
                    
                    // Handle different types of discounts
                    if (data.discount && data.discount.free_shipping) {
                        // For free shipping coupons, set shipping to 0 and discount amount to shipping saved
                        const shippingSaved = currentShippingCost;
                        currentShippingCost = 0;
                        currentDiscountAmount = data.discount.amount || 0;
                        
                        // Update shipping display to show free shipping
                        updateShippingDisplay(0, true, 'Free shipping applied!');
                        
                        showToast(`Free shipping applied! You saved ৳${shippingSaved + currentDiscountAmount}`, 'success');
                    } else {
                        // For regular discounts
                        currentDiscountAmount = parseFloat(data.discount.amount) || 0;
                        showToast(`Coupon "${couponCode}" applied successfully! You saved ৳${currentDiscountAmount}`, 'success');
                    }
                    
                    // Show applied coupon
                    showAppliedCoupon(data.coupon);
                    
                    // Update displays
                    updateDiscountDisplay(currentDiscountAmount);
                    updateOrderTotal();
                    
                    // Store coupon info in session
                    sessionStorage.setItem('applied_coupon', JSON.stringify({
                        code: couponCode,
                        discount: currentDiscountAmount,
                        coupon_id: data.coupon.id,
                        free_shipping: data.discount.free_shipping || false
                    }));
                    
                    // Update the form with coupon data
                    let couponIdInput = document.querySelector('input[name="coupon_id"]');
                    if (!couponIdInput) {
                        couponIdInput = document.createElement('input');
                        couponIdInput.type = 'hidden';
                        couponIdInput.name = 'coupon_id';
                        document.querySelector('.form-checkout').appendChild(couponIdInput);
                    }
                    couponIdInput.value = data.coupon.id;
                    
                    // Clear input
                    couponInput.value = '';
                } else {
                    console.error('Coupon validation failed:', data);
                    showToast(data.message || 'Invalid coupon code', 'error');
                }
            })
            .catch(error => {
                console.error('Coupon application error:', error);
                
                // Provide more specific error messages
                if (error.message.includes('Failed to fetch')) {
                    showToast('Network error. Please check your internet connection and try again.', 'error');
                } else if (error.message.includes('422')) {
                    showToast('Invalid request data. Please check the coupon code and try again.', 'error');
                } else if (error.message.includes('404')) {
                    showToast('Coupon not found. Please check the coupon code.', 'error');
                } else if (error.message.includes('500')) {
                    showToast('Server error. Please try again later or contact support.', 'error');
                } else {
                    showToast('Error applying coupon', 'error');
                }
            })
            .finally(() => {
                applyBtn.innerHTML = originalText;
                applyBtn.disabled = false;
            });
        }
        
        // Show applied coupon in UI
        function showAppliedCoupon(coupon) {
            const couponBox = document.querySelector('.coupon-box');
            
            // Remove existing applied coupon display
            const existingApplied = couponBox.querySelector('.applied-coupon');
            if (existingApplied) {
                existingApplied.remove();
            }
            
            // Create applied coupon display
            const appliedDiv = document.createElement('div');
            appliedDiv.className = 'applied-coupon mt-2 p-2 bg-success text-white rounded d-flex justify-content-between align-items-center';
            appliedDiv.innerHTML = `
                <div>
                    <i class="icon-check me-2"></i>
                    <strong>${coupon.code}</strong> - ${coupon.name || coupon.discount_text || 'Coupon Applied'}
                    ${coupon.free_shipping ? '<br><small>Free shipping included</small>' : ''}
                </div>
                <button type="button" class="btn btn-sm btn-outline-light" onclick="removeCoupon()">
                    <i class="icon-close"></i>
                </button>
            `;
            
            couponBox.appendChild(appliedDiv);
            
            // Hide the input and apply button
            couponBox.querySelector('input').style.display = 'none';
            couponBox.querySelector('button[onclick="applyCoupon()"]').style.display = 'none';
        }
        
        // Remove applied coupon
        function removeCoupon() {
            // Reset coupon variables
            appliedCoupon = null;
            currentDiscountAmount = 0;
            
            // Remove applied coupon display
            const appliedCoupon = document.querySelector('.applied-coupon');
            if (appliedCoupon) {
                appliedCoupon.remove();
            }
            
            // Show input and apply button again
            const couponBox = document.querySelector('.coupon-box');
            couponBox.querySelector('input').style.display = 'block';
            couponBox.querySelector('button[onclick="applyCoupon()"]').style.display = 'block';
            
            // Remove coupon data from form
            const couponIdInput = document.querySelector('input[name="coupon_id"]');
            if (couponIdInput) {
                couponIdInput.remove();
            }
            
            // Clear session storage
            sessionStorage.removeItem('applied_coupon');
            
            // Update displays
            updateDiscountDisplay(0);
            
            // Recalculate shipping if it was free shipping coupon
            calculateShipping();
            
            showToast('Coupon removed', 'info');
        }
        
        // Recalculate shipping (when coupon is removed)
        function calculateShipping() {
            // Get selected shipping method
            const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
            
            if (shippingMethod) {
                const shippingCost = parseFloat(shippingMethod.getAttribute('data-cost') || '0');
                updateShippingDisplay(shippingCost);
                updateOrderTotal();
            } else {
                // Default shipping calculation based on area/location
                updateShippingDisplay(100, false, '3-5 business days');
                updateOrderTotal();
            }
        }

        // Update discount display
        function updateDiscountDisplay(discount) {
            const discountRow = document.getElementById('discount-row');
            const discountAmount = document.getElementById('discount-amount');
            
            if (discount > 0) {
                discountAmount.textContent = `-৳${discount}`;
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }

        // Update order total
        function updateOrderTotal() {
            const subtotal = {{ $subtotal }};
            
            // Use data attribute for shipping cost to avoid parsing issues with "FREE" text
            const shippingElement = document.getElementById('shipping-amount');
            const shipping = shippingElement ? parseFloat(shippingElement.getAttribute('data-cost') || '0') : 0;
            
            const discount = parseFloat(document.getElementById('discount-amount')?.textContent?.replace(/[-৳,]/g, '') || 0);
            const total = subtotal + shipping - discount;
            
            console.log('Order total calculation:', { subtotal, shipping, discount, total });
            
            document.getElementById('total-amount').textContent = `৳${total.toLocaleString()}`;
        }

        // Store checkout URL for redirect after login
        function storeCheckoutUrl() {
            sessionStorage.setItem('checkout_redirect', window.location.href);
        }

        // Login form submission - use traditional form submission to AffiliateLoginController
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            // Allow default form submission to the affiliate login controller
            // The form will redirect back to checkout page after successful login
        });
        
        // Validate payment method specific fields
        function validatePaymentMethodFields(paymentMethod) {
            if (paymentMethod === 'online_payment') {
                // Check if online payment type is selected
                const onlinePaymentType = document.querySelector('input[name="online_payment_type"]:checked');
                if (!onlinePaymentType) {
                    alert('Please select a mobile banking provider (bKash, Nagad, or Rocket)');
                    return false;
                }
                
                // Check if transaction ID is provided
                const transactionId = document.querySelector('input[name="transaction_id"]').value.trim();
                if (!transactionId) {
                    alert('Please enter the transaction ID after making the payment');
                    const transactionField = document.querySelector('input[name="transaction_id"]');
                    transactionField.focus();
                    return false;
                }
            } else if (paymentMethod === 'bank_transfer') {
                // Check if bank transaction reference is provided
                const bankTransactionRef = document.querySelector('input[name="bank_transaction_ref"]').value.trim();
                if (!bankTransactionRef) {
                    alert('Please enter your bank transaction reference number');
                    const bankRefField = document.querySelector('input[name="bank_transaction_ref"]');
                    bankRefField.focus();
                    return false;
                }
            }
            
            return true;
        }
        
        // Helper function to get cart items from DOM with proper structure for backend validation
        function getCartItemsFromDOM() {
            const cartItems = [];
            
            // First, try to use server-side cart data (most reliable)
            if (serverCartData && Object.keys(serverCartData).length > 0) {
                Object.entries(serverCartData).forEach(([id, item]) => {
                    cartItems.push({
                        product_id: parseInt(id) || 1,
                        quantity: parseInt(item.quantity) || 1,
                        price: parseFloat(item.price) || 0
                    });
                });
            }
            // If no server data, try extracting from DOM
            else {
                document.querySelectorAll('.checkout-product-item').forEach((item, index) => {
                    const productName = item.querySelector('.info .name')?.textContent?.trim() || '';
                    const quantityText = item.querySelector('.quantity')?.textContent?.trim() || '1';
                    const priceText = item.querySelector('.price')?.textContent?.replace(/[৳,]/g, '') || '0';
                    
                    // Extract quantity number
                    const quantity = parseInt(quantityText) || 1;
                    
                    // Extract total price and calculate unit price
                    const totalPrice = parseFloat(priceText) || 0;
                    const unitPrice = totalPrice / quantity;
                    
                    // Try to get product ID from data attributes
                    let productId = item.getAttribute('data-product-id') || item.getAttribute('data-id') || (index + 1);
                    productId = parseInt(productId) || (index + 1);
                    
                    if (productName && quantity > 0) {
                        cartItems.push({
                            product_id: productId,
                            quantity: quantity,
                            price: parseFloat(unitPrice.toFixed(2))
                        });
                    }
                });
            }
            
            console.log('Cart items extracted:', cartItems);
            return cartItems;
        }
        // Place order
        function placeOrder() {
            const form = document.querySelector('.form-checkout');
            
            // Validate basic form fields
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const address = document.getElementById('address').value.trim();
            const district = document.getElementById('district-select').value;
            
            if (!firstName || !lastName || !email || !phone || !address || !district) {
                showToast('Please fill in all required fields', 'error');
                return;
            }
            
            // Get selected payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;
            if (!paymentMethod) {
                showToast('Please select a payment method', 'error');
                return;
            }
            
            // Validate payment method specific requirements
            if (paymentMethod === 'online_payment') {
                const onlinePaymentType = document.querySelector('input[name="online_payment_type"]:checked');
                const transactionId = document.querySelector('input[name="transaction_id"]').value.trim();
                
                if (!onlinePaymentType) {
                    showToast('Please select a mobile banking option', 'error');
                    return;
                }
                
                if (!transactionId) {
                    showToast('Please enter transaction ID', 'error');
                    document.querySelector('input[name="transaction_id"]').focus();
                    return;
                }
            } else if (paymentMethod === 'bank_transfer') {
                const bankRef = document.querySelector('input[name="bank_transaction_ref"]').value.trim();
                
                if (!bankRef) {
                    showToast('Please enter bank transaction reference', 'error');
                    document.querySelector('input[name="bank_transaction_ref"]').focus();
                    return;
                }
            }
            
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
            
            // Get location data
            const upazila = document.getElementById('upazila-select').value;
            const ward = document.getElementById('ward-select').value;
            const zipCode = document.getElementById('zip_code')?.value || '';
            const note = document.getElementById('note')?.value || '';
            
            // Get cart items with proper structure for backend validation
            const cartItems = getCartItemsFromDOM();
            
            if (cartItems.length === 0) {
                // Try alternative method: check session storage
                const sessionCart = sessionStorage.getItem('cart');
                if (sessionCart) {
                    try {
                        const parsedCart = JSON.parse(sessionCart);
                        if (Array.isArray(parsedCart) && parsedCart.length > 0) {
                            parsedCart.forEach((item, index) => {
                                cartItems.push({
                                    product_id: item.id || item.product_id || (index + 1),
                                    quantity: parseInt(item.quantity) || 1,
                                    price: parseFloat(item.price) || 0
                                });
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing session cart:', e);
                    }
                }
            }
            
            console.log('Final cart items for order:', cartItems);
            
            if (cartItems.length === 0) {
                showToast('Your cart is empty. Please add items to your cart first.', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            }
            
            // Get financial calculations
            const subtotal = {{ $subtotal }};
            const shippingElement = document.getElementById('shipping-amount');
            const shipping = shippingElement ? parseFloat(shippingElement.getAttribute('data-cost') || '0') : 0;
            const taxAmount = 0; // No tax in current system
            const discountElement = document.getElementById('discount-amount');
            const discount = discountElement ? parseFloat(discountElement.textContent?.replace(/[-৳,]/g, '') || 0) : 0;
            const total = subtotal + shipping + taxAmount - discount;
            
            // Determine shipping method based on location and cart total
            let shippingMethod = 'across_country'; // default for outside dhaka
            if (district === 'Dhaka') {
                shippingMethod = 'inside_dhaka';
            } else if (shippingConfig.freeShippingEnabled && subtotal >= shippingConfig.freeShippingThreshold) {
                shippingMethod = 'free';
            } else {
                shippingMethod = 'outside_dhaka';
            }
            
            // Determine checkout type
            let checkoutType = 'guest';
            @auth
                checkoutType = 'authenticated';
            @endauth
            @guest
                const checkoutTypeElement = document.querySelector('input[name="checkout_type"]:checked');
                if (checkoutTypeElement) {
                    checkoutType = checkoutTypeElement.value;
                }
            @endguest
            
            // Prepare order data to match backend validation structure
            const orderData = {
                customer_name: `${firstName} ${lastName}`.trim(),
                customer_email: email,
                customer_phone: phone,
                shipping_address: {
                    address: address,
                    city: district,
                    state: upazila || '',
                    postal_code: zipCode,
                    country: 'Bangladesh'
                },
                billing_address: {
                    address: address,
                    city: district,
                    state: upazila || '',
                    postal_code: zipCode,
                    country: 'Bangladesh'
                },
                payment_method: paymentMethod,
                shipping_method: shippingMethod,
                cart_items: cartItems,
                subtotal: subtotal,
                shipping_cost: shipping,
                tax_amount: taxAmount,
                discount_amount: discount,
                total_amount: total,
                checkout_type: checkoutType
            };
            
            // Add optional order notes
            if (note) {
                orderData.order_notes = note;
            }
            
            // Add coupon code if discount applied
            if (discount > 0) {
                const appliedCoupon = JSON.parse(sessionStorage.getItem('applied_coupon') || '{}');
                if (appliedCoupon.code) {
                    orderData.coupon_code = appliedCoupon.code;
                }
            }
            
            // Add payment method specific data
            if (paymentMethod === 'online_payment') {
                const onlinePaymentType = document.querySelector('input[name="online_payment_type"]:checked').value;
                const transactionId = document.querySelector('input[name="transaction_id"]').value;
                orderData.online_payment_type = onlinePaymentType;
                orderData.transaction_id = transactionId;
            } else if (paymentMethod === 'bank_transfer') {
                const bankTransactionRef = document.querySelector('input[name="bank_transaction_ref"]').value;
                orderData.bank_transaction_ref = bankTransactionRef;
            }
            
            // Add account creation data if register checkout type
            if (checkoutType === 'register') {
                // Get registration form data
                const sponsorId = document.getElementById('sponsor-id')?.value?.trim() || '';
                const username = document.getElementById('registration-username')?.value?.trim() || '';
                const firstname = document.getElementById('registration-firstname')?.value?.trim() || '';
                const lastname = document.getElementById('registration-lastname')?.value?.trim() || '';
                const registrationEmail = document.getElementById('registration-email')?.value?.trim() || '';
                const registrationPhone = document.getElementById('registration-phone')?.value?.trim() || '';
                const country = document.getElementById('registration-country')?.value || 'BD';
                const password = document.getElementById('registration-password')?.value || '';
                const passwordConfirmation = document.querySelector('input[name="password_confirmation"]')?.value || '';
                const registrationAddress = document.getElementById('registration-address')?.value?.trim() || '';
                const position = document.querySelector('input[name="position"]:checked')?.value || 'left';
                const placement = document.querySelector('input[name="placement"]:checked')?.value || 'auto';
                const uplineUsername = document.getElementById('upline-username')?.value?.trim() || '';
                const marketing = document.getElementById('marketing-consent')?.checked || false;
                const terms = document.getElementById('agree-terms')?.checked || false;
                
                // Validate required registration fields
                if (!sponsorId) {
                    showToast('Sponsor ID is required for account creation', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!username) {
                    showToast('Username is required for account creation', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!firstname || !lastname) {
                    showToast('First name and last name are required for account creation', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!registrationEmail) {
                    showToast('Email is required for account creation', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!registrationPhone) {
                    showToast('Phone number is required for account creation', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!password || password.length < 8) {
                    showToast('Password must be at least 8 characters long', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (password !== passwordConfirmation) {
                    showToast('Password confirmation does not match', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (!terms) {
                    showToast('You must agree to the terms and conditions', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                if (placement === 'manual' && !uplineUsername) {
                    showToast('Upline username is required for manual placement', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                    return;
                }
                
                // Add registration data to order - use field names expected by AffiliateLoginController
                Object.assign(orderData, {
                    // Registration fields expected by AffiliateLoginController::register()
                    sponsor_id: sponsorId,
                    username: username,
                    firstname: firstname,
                    lastname: lastname,
                    email: registrationEmail, // This will override customer_email for registration
                    phone: registrationPhone, // This will override customer_phone for registration
                    password: password,
                    password_confirmation: passwordConfirmation,
                    country: country,
                    address: registrationAddress,
                    position: position,
                    placement: placement,
                    upline_username: uplineUsername,
                    marketing: marketing,
                    terms: terms ? '1' : '0' // Convert boolean to string for Laravel's accepted validation
                });
            }
            
            console.log('Order data prepared for backend validation:', orderData);
            
            // Handle affiliate registration separately for better UX
            if (checkoutType === 'register') {
                // Step 1: Register affiliate account first
                registerAffiliateAccount(orderData, button, originalText);
                return;
            }
            
            // For guest or authenticated checkout, directly process order
            const actionUrl = form.action || '{{ route("orders.store") }}';
            
            console.log('Using action URL:', actionUrl, 'for checkout type:', checkoutType);
            
            // Submit order
            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear cart and coupon data
                    sessionStorage.removeItem('cart');
                    sessionStorage.removeItem('applied_coupon');
                    
                    showToast('Order placed successfully!', 'success');
                    
                    // Redirect to success page after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '{{ route("orders.success") }}';
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Order processing failed');
                }
            })
            .catch(error => {
                console.error('Order submission error:', error);
                showToast(error.message || 'Error processing order. Please try again.', 'error');
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Step 1: Register affiliate account
        function registerAffiliateAccount(orderData, button, originalText) {
            console.log('Starting affiliate registration...');
            
            // Prepare affiliate registration data
            const affiliateData = {
                sponsor_id: orderData.sponsor_id,
                username: orderData.username,
                firstname: orderData.firstname,
                lastname: orderData.lastname,
                email: orderData.email,
                phone: orderData.phone,
                password: orderData.password,
                password_confirmation: orderData.password_confirmation,
                country: orderData.country || 'BD',
                address: orderData.address,
                position: orderData.position,
                placement: orderData.placement,
                upline_username: orderData.upline_username,
                marketing: orderData.marketing,
                terms: orderData.terms,
                _token: document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
            };
            
            console.log('Sending affiliate registration request...');
            
            // Register affiliate
            fetch('{{ route("affiliate.register") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': affiliateData._token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(affiliateData)
            })
            .then(response => {
                console.log('Registration response status:', response.status);
                
                // Handle redirect responses (success case)
                if (response.redirected) {
                    console.log('Registration successful - redirected to:', response.url);
                    showToast('✅ Account created successfully!', 'success');
                    setupOrderFormAfterRegistration(orderData, button, originalText);
                    return null;
                }
                
                return response.json();
            })
            .then(data => {
                if (data) {
                    if (data.success) {
                        showToast('✅ Account created successfully!', 'success');
                        setupOrderFormAfterRegistration(orderData, button, originalText);
                    } else {
                        throw new Error(data.message || 'Registration failed');
                    }
                }
            })
            .catch(error => {
                console.error('Registration error:', error);
                showToast('Registration failed: ' + error.message, 'error');
                
                // Reset button
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Step 2: Setup order form after successful registration
        function setupOrderFormAfterRegistration(orderData, button, originalText) {
            console.log('Setting up order form after registration...');
            
            // Switch to guest checkout mode (user is now registered)
            selectCheckoutType('guest');
            
            // Pre-fill billing form with registered user data
            document.getElementById('first_name').value = orderData.firstname || '';
            document.getElementById('last_name').value = orderData.lastname || '';
            document.getElementById('email').value = orderData.email || '';
            document.getElementById('phone').value = orderData.phone || '';
            document.getElementById('address').value = orderData.address || '';
            
            // Auto-check marketing consent and terms checkboxes
            const marketingCheckbox = document.getElementById('marketing-consent'); // Registration form checkbox
            const termsCheckboxMain = document.getElementById('check-agree'); // Main checkout form checkbox
            const termsCheckboxRegistration = document.getElementById('agree-terms'); // Registration form checkbox
            
            if (marketingCheckbox) {
                marketingCheckbox.checked = true;
                console.log('✅ Marketing consent auto-checked');
            }
            
            if (termsCheckboxMain) {
                termsCheckboxMain.checked = true;
                console.log('✅ Main checkout terms & conditions auto-checked');
            }
            
            if (termsCheckboxRegistration) {
                termsCheckboxRegistration.checked = true;
                console.log('✅ Registration terms & conditions auto-checked');
            }
            
            // Reset and enable the place order button
            button.innerHTML = originalText;
            button.disabled = false;
            
            // Show success message
            showToast('✅ Your account has been created! Now automatically submitting your order...', 'success');
            
            // Automatically submit the order after a short delay
            setTimeout(() => {
                submitOrderAfterRegistration(orderData, button, originalText);
            }, 2000);
            
            // Scroll to billing section for better UX
            const billingSection = document.querySelector('.billing-info-section') || document.querySelector('.form-checkout');
            if (billingSection) {
                billingSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
            
            // Add visual indicator that registration is complete
            const accountCreationForm = document.getElementById('account-creation-form');
            if (accountCreationForm) {
                // Add success badge
                const successBadge = document.createElement('div');
                successBadge.className = 'alert alert-success mt-3';
                successBadge.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Account Created Successfully!</strong> You can now complete your order below.';
                accountCreationForm.style.display = 'none';
                accountCreationForm.parentNode.insertBefore(successBadge, accountCreationForm.nextSibling);
            }
        }

        // Step 3: Automatically submit order after registration
        function submitOrderAfterRegistration(orderData, button, originalText) {
            console.log('Auto-submitting order after registration...');
            
            // Show processing state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting Order...';
            button.disabled = true;
            
            // Prepare clean order data (remove registration fields)
            const cleanOrderData = {
                customer_name: orderData.customer_name,
                customer_email: orderData.customer_email,
                customer_phone: orderData.customer_phone,
                shipping_address: orderData.shipping_address,
                billing_address: orderData.billing_address,
                payment_method: orderData.payment_method,
                shipping_method: orderData.shipping_method,
                cart_items: orderData.cart_items,
                subtotal: orderData.subtotal,
                shipping_cost: orderData.shipping_cost,
                tax_amount: orderData.tax_amount,
                discount_amount: orderData.discount_amount,
                total_amount: orderData.total_amount,
                checkout_type: 'guest', // User is now registered but we process as guest
                order_notes: orderData.order_notes,
                coupon_code: orderData.coupon_code,
                online_payment_type: orderData.online_payment_type,
                transaction_id: orderData.transaction_id,
                bank_transaction_ref: orderData.bank_transaction_ref
            };
            
            console.log('Clean order data for submission:', cleanOrderData);
            
            // Submit the order
            fetch('{{ route("orders.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify(cleanOrderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear cart and coupon data
                    sessionStorage.removeItem('cart');
                    sessionStorage.removeItem('applied_coupon');
                    
                    showToast('🎉 Order placed successfully! Redirecting...', 'success');
                    
                    // Redirect to success page
                    setTimeout(() => {
                        window.location.href = data.redirect_url || '{{ route("orders.success") }}';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Order processing failed');
                }
            })
            .catch(error => {
                console.error('Auto order submission error:', error);
                showToast('Registration successful but order submission failed: ' + error.message, 'error');
                
                // Reset button for manual retry
                button.innerHTML = originalText;
                button.disabled = false;
                
                // Show manual instruction
                showToast('Please click "Place Order" to complete your purchase manually.', 'warning');
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
    </script>
@endpush
                