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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Sponsor ID (Optional)</label>
                                        <input type="text" class="form-control" name="sponsor_id" id="sponsor-id" placeholder="Enter sponsor username">
                                        <small class="text-muted">Leave blank if no sponsor</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Username *</label>
                                        <input type="text" class="form-control" name="username" id="registration-username" required>
                                        <small class="text-muted">Choose a unique username</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Password *</label>
                                        <input type="password" class="form-control" name="password" id="registration-password" required>
                                        <small class="text-muted">Minimum 8 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password *</label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="subscribe_newsletter" checked>
                                        <label class="form-check-label">Subscribe to newsletter for exclusive offers</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agree_terms" required>
                                        <label class="form-check-label">I agree to <a href="#">Terms & Conditions</a> *</label>
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
                        
                        <div class="box grid-2">
                            <fieldset class="fieldset">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="First Name" 
                                       value="{{ old('first_name', auth()->user()->first_name ?? '') }}" required>
                                @error('first_name')
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
                        </fieldset>
                        
                        <fieldset class="box fieldset">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" placeholder="Phone Number" 
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                            @error('phone')
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
                        
                        <!-- Dynamic District Selection -->
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
                                    <li class="checkout-product-item">
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
                                    <input type="text" name="coupon_code" id="coupon-input" placeholder="Discount code" value="{{ old('coupon_code') }}">
                                    <button type="button" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" onclick="applyCoupon()">Apply</button>
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
                                        <input type="radio" name="payment_method" id="mobile-banking" class="tf-check" value="mobile_banking">
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
                                                        <input type="radio" name="mobile_provider" value="bkash" style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mobile-option border rounded p-2 text-center" data-provider="nagad">
                                                        <img src="{{ asset('assets/images/nagad.png') }}" alt="Nagad" class="mb-1" style="height: 30px;">
                                                        <br><small>Nagad</small>
                                                        <input type="radio" name="mobile_provider" value="nagad" style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mobile-option border rounded p-2 text-center" data-provider="rocket">
                                                        <img src="{{ asset('assets/images/rocket.png') }}" alt="Rocket" class="mb-1" style="height: 30px;">
                                                        <br><small>Rocket</small>
                                                        <input type="radio" name="mobile_provider" value="rocket" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-instructions mt-3" id="payment-instructions" style="display: none;">
                                                <!-- Payment instructions will be shown here -->
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
                                        </div>
                                    </div>
                                    
                                    @error('payment_method')
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
                                <button type="submit" class="tf-btn radius-3 btn-fill btn-icon animate-hover-btn justify-content-center w-100">
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
    </style>

    <script>
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDistricts();
            initializePaymentMethods();
            
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

        // Load districts from delivery_charges table
        function loadDistricts() {
            fetch('/api/districts')
                .then(response => response.json())
                .then(data => {
                    const districtSelect = document.getElementById('district-select');
                    districtSelect.innerHTML = '<option value="">Select District</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.district}">${district.district}</option>`;
                    });
                })
                .catch(error => console.error('Error loading districts:', error));
        }

        // Load upazilas based on selected district
        function loadUpazilas() {
            const district = document.getElementById('district-select').value;
            if (!district) return;

            fetch(`/api/upazilas/${district}`)
                .then(response => response.json())
                .then(data => {
                    const upazilaSelect = document.getElementById('upazila-select');
                    upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
                    data.forEach(upazila => {
                        upazilaSelect.innerHTML += `<option value="${upazila.upazila}">${upazila.upazila}</option>`;
                    });
                    // Reset ward selection
                    document.getElementById('ward-select').innerHTML = '<option value="">Select Ward/Union</option>';
                    calculateShipping();
                })
                .catch(error => console.error('Error loading upazilas:', error));
        }

        // Load wards based on selected upazila
        function loadWards() {
            const district = document.getElementById('district-select').value;
            const upazila = document.getElementById('upazila-select').value;
            if (!district || !upazila) return;

            fetch(`/api/wards/${district}/${upazila}`)
                .then(response => response.json())
                .then(data => {
                    const wardSelect = document.getElementById('ward-select');
                    wardSelect.innerHTML = '<option value="">Select Ward/Union</option>';
                    data.forEach(ward => {
                        wardSelect.innerHTML += `<option value="${ward.ward}">${ward.ward}</option>`;
                    });
                    calculateShipping();
                })
                .catch(error => console.error('Error loading wards:', error));
        }

        // Calculate shipping charges
        function calculateShipping() {
            const district = document.getElementById('district-select').value;
            const upazila = document.getElementById('upazila-select').value;
            if (!district || !upazila) return;

            fetch('/api/shipping-cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    district: district,
                    upazila: upazila,
                    subtotal: {{ $subtotal }}
                })
            })
            .then(response => response.json())
            .then(data => {
                updateShippingDisplay(data.shipping_cost, data.is_free_shipping);
                updateOrderTotal();
            })
            .catch(error => console.error('Error calculating shipping:', error));
        }

        // Update shipping display
        function updateShippingDisplay(cost, isFree) {
            const shippingRow = document.getElementById('shipping-row');
            const shippingAmount = document.getElementById('shipping-amount');
            
            if (cost > 0 && !isFree) {
                shippingAmount.textContent = `৳${cost}`;
                shippingRow.style.display = 'flex';
            } else if (isFree) {
                shippingAmount.innerHTML = '<span class="text-success">FREE</span>';
                shippingRow.style.display = 'flex';
            } else {
                shippingRow.style.display = 'none';
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
                    if (this.value === 'mobile_banking') {
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
        function applyCoupon() {
            const couponCode = document.getElementById('coupon-input').value;
            if (!couponCode) {
                alert('Please enter a coupon code');
                return;
            }

            fetch('/api/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    code: couponCode,
                    total_amount: {{ $subtotal }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const discount = data.coupon.discount_amount;
                    updateDiscountDisplay(discount);
                    updateOrderTotal();
                    
                    // Store coupon info in session
                    sessionStorage.setItem('applied_coupon', JSON.stringify({
                        code: couponCode,
                        discount: discount
                    }));
                    
                    alert('Coupon applied successfully!');
                } else {
                    alert(data.message || 'Invalid coupon code');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while applying the coupon');
            });
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
            const shipping = parseFloat(document.getElementById('shipping-amount')?.textContent?.replace(/[৳,]/g, '') || 0);
            const discount = parseFloat(document.getElementById('discount-amount')?.textContent?.replace(/[-৳,]/g, '') || 0);
            const total = subtotal + shipping - discount;
            
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
    </script>
@endsection
                