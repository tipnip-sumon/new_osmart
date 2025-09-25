@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="container">
    <div class="section-heading d-flex align-items-center justify-content-between">
        <h6>Checkout</h6>
        <a class="btn p-0" href="{{ route('cart.index') }}">Back to Cart<i class="ms-1 ti ti-arrow-left"></i></a>
    </div>
    
    <!-- Checkout Form -->
    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        
        <!-- Hidden inputs for cart data -->
        <input type="hidden" name="cart_items" id="cartItemsInput">
        <input type="hidden" name="subtotal" id="subtotalInput">
        <input type="hidden" name="shipping_cost" id="shippingCostInput" value="60">
        <input type="hidden" name="shipping_method" id="shippingMethodInput" value="{{ config('shipping.default_method', 'inside_dhaka') }}">
        <input type="hidden" name="total" id="totalInput">
        <input type="hidden" name="coupon_code" id="couponCodeInput">
        <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">
        
        <!-- Shipping Information -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Shipping Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" name="postal_code" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Method -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Shipping Method</h6>
            </div>
            <div class="card-body">
                <div class="shipping-method-area">
                    @foreach(config('shipping.options') as $key => $option)
                        <div class="form-check mb-3 shipping-option" data-method="{{ $key }}">
                            <input class="form-check-input shipping-method" 
                                   type="radio" 
                                   name="shipping_method" 
                                   id="shipping_{{ $key }}" 
                                   value="{{ $key }}"
                                   data-rate="{{ $option['rate'] }}"
                                   {{ $key === config('shipping.default_method', 'inside_dhaka') ? 'checked' : '' }}>
                            <label class="form-check-label w-100" for="shipping_{{ $key }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $option['label'] }}</strong>
                                        <div class="text-muted small">{{ $option['description'] }}</div>
                                        <div class="text-primary small">
                                            <i class="ti ti-clock me-1"></i>{{ $option['delivery_time'] }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong class="shipping-rate">
                                            @if($option['rate'] > 0)
                                                {{ config('shipping.currency', '৳') }}{{ number_format($option['rate'], 0) }}
                                            @else
                                                FREE
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="shipping-note mt-3">
                    <small class="text-muted">
                        <i class="ti ti-info-circle me-1"></i>
                        <span id="freeShippingNote">
                            @if(config('shipping.free_shipping.enabled'))
                                Free shipping available on orders over {{ config('shipping.currency', '৳') }}{{ number_format(config('shipping.free_shipping.minimum_order', 1000), 0) }}
                            @endif
                        </span>
                    </small>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Payment Method</h6>
            </div>
            <div class="card-body">
                <div class="payment-method-area">
                    <!-- Online Payment -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="online_payment" value="online_payment" checked>
                        <label class="form-check-label" for="online_payment">
                            <i class="ti ti-credit-card me-2"></i>Online Payment
                        </label>
                    </div>
                    
                    <!-- Online Payment Options -->
                    <div id="onlinePaymentOptions" class="ms-4 mb-3" style="display: block;">
                        <div class="card border-light">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3">Select Payment Option</h6>
                                
                                <!-- bKash -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="online_payment_type" id="bkash" value="bkash" checked>
                                    <label class="form-check-label d-flex align-items-center" for="bkash">
                                        <span class="payment-logo bkash-logo me-2">bKash</span>
                                        Mobile Banking
                                    </label>
                                </div>
                                
                                <!-- bKash Instructions -->
                                <div id="bkashInstructions" class="alert alert-info mt-2 mb-3" style="font-size: 14px;">
                                    <strong>bKash Payment Instructions:</strong><br>
                                    1. Dial *247# from your mobile<br>
                                    2. Select "Send Money"<br>
                                    3. Enter Merchant Number: <strong>01XXXXXXXXX</strong><br>
                                    4. Enter Amount: <span id="bkashAmount">৳0.00</span><br>
                                    5. Enter Reference: Your Order Number<br>
                                    6. Confirm payment with your bKash PIN<br>
                                    <small class="text-muted">Please keep the transaction ID for verification.</small>
                                </div>
                                
                                <!-- Rocket -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="online_payment_type" id="rocket" value="rocket">
                                    <label class="form-check-label d-flex align-items-center" for="rocket">
                                        <span class="payment-logo rocket-logo me-2">Rocket</span>
                                        Mobile Banking
                                    </label>
                                </div>
                                
                                <!-- Rocket Instructions -->
                                <div id="rocketInstructions" class="alert alert-info mt-2 mb-3" style="font-size: 14px; display: none;">
                                    <strong>Rocket Payment Instructions:</strong><br>
                                    1. Dial *322# from your mobile<br>
                                    2. Select "Send Money"<br>
                                    3. Enter Agent Number: <strong>01XXXXXXXXX</strong><br>
                                    4. Enter Amount: <span id="rocketAmount">৳0.00</span><br>
                                    5. Enter Reference: Your Order Number<br>
                                    6. Confirm payment with your Rocket PIN<br>
                                    <small class="text-muted">Please keep the transaction ID for verification.</small>
                                </div>
                                
                                <!-- Nagad -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="online_payment_type" id="nagad" value="nagad">
                                    <label class="form-check-label d-flex align-items-center" for="nagad">
                                        <span class="payment-logo nagad-logo me-2">Nagad</span>
                                        Mobile Banking
                                    </label>
                                </div>
                                
                                <!-- Nagad Instructions -->
                                <div id="nagadInstructions" class="alert alert-info mt-2 mb-3" style="font-size: 14px; display: none;">
                                    <strong>Nagad Payment Instructions:</strong><br>
                                    1. Dial *167# from your mobile<br>
                                    2. Select "Send Money"<br>
                                    3. Enter Agent Number: <strong>01XXXXXXXXX</strong><br>
                                    4. Enter Amount: <span id="nagadAmount">৳0.00</span><br>
                                    5. Enter Reference: Your Order Number<br>
                                    6. Confirm payment with your Nagad PIN<br>
                                    <small class="text-muted">Please keep the transaction ID for verification.</small>
                                </div>
                                
                                <!-- Transaction ID Input -->
                                <div class="mt-3">
                                    <label class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                    <input type="text" name="transaction_id" id="transactionId" class="form-control" placeholder="Enter your transaction ID">
                                    <small class="text-muted">Please enter the transaction ID you received after payment.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bank Transfer -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                        <label class="form-check-label" for="bank_transfer">
                            <i class="ti ti-building-bank me-2"></i>Bank Transfer
                        </label>
                    </div>
                    
                    <!-- Bank Transfer Information -->
                    <div id="bankTransferInfo" class="ms-4 mb-3" style="display: none;">
                        <div class="card border-light">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-3">Bank Account Information</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>Bank Name:</strong><br>
                                            Dutch-Bangla Bank Limited (DBBL)
                                        </div>
                                        <div class="mb-3">
                                            <strong>Account Name:</strong><br>
                                            Your Company Name
                                        </div>
                                        <div class="mb-3">
                                            <strong>Account Number:</strong><br>
                                            <span class="badge bg-primary">123-456-789012</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong>Branch:</strong><br>
                                            Dhanmondi Branch, Dhaka
                                        </div>
                                        <div class="mb-3">
                                            <strong>Routing Number:</strong><br>
                                            <span class="badge bg-secondary">090260724</span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Amount to Transfer:</strong><br>
                                            <span id="bankAmount" class="text-success fw-bold">৳0.00</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Important:</strong> Please mention your order number in the transfer reference and send us the transaction slip via WhatsApp: +8801XXXXXXXXX
                                </div>
                                
                                <!-- Bank Transaction Details -->
                                <div class="mt-3">
                                    <label class="form-label">Bank Transaction Reference <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_transaction_ref" id="bankTransactionRef" class="form-control" placeholder="Enter bank transaction reference">
                                    <small class="text-muted">Please enter the transaction reference from your bank.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cash on Delivery -->
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                        <label class="form-check-label" for="cash_on_delivery">
                            <i class="ti ti-cash me-2"></i>Cash on Delivery
                        </label>
                    </div>
                    
                    <!-- COD Information -->
                    <div id="codInfo" class="ms-4 mb-3" style="display: none;">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Pay with cash when your order is delivered to your doorstep. Available for orders within Dhaka city.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Coupon Code -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Promo Code</h6>
            </div>
            <div class="card-body">
                <div id="couponSection">
                    <div class="input-group">
                        <input type="text" id="couponCode" class="form-control" placeholder="Enter coupon code" autocomplete="off">
                        <button type="button" id="applyCouponBtn" class="btn btn-outline-primary">
                            <span class="btn-text">Apply</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                    <small class="text-muted mt-1 d-block">Have a promo code? Enter it above to get a discount.</small>
                </div>
                
                <!-- Applied Coupon Display -->
                <div id="appliedCoupon" class="alert alert-success mt-3" style="display: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong id="appliedCouponCode"></strong>
                            <span id="appliedCouponName" class="text-muted"></span>
                            <div class="small text-success mt-1">
                                <i class="ti ti-check me-1"></i>
                                <span id="couponDiscountText"></span>
                            </div>
                        </div>
                        <button type="button" id="removeCouponBtn" class="btn btn-sm btn-outline-danger">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Coupon Error -->
                <div id="couponError" class="alert alert-danger mt-3" style="display: none;">
                    <span id="couponErrorMessage"></span>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body">
                <!-- Cart Status Message -->
                <div id="cartStatusMessage" class="alert alert-info text-center" style="display: none;">
                    <i class="ti ti-info-circle me-2"></i>
                    <span id="cartStatusText">Loading cart...</span>
                </div>
                
                <div class="checkout-total-amount-area" id="orderSummaryDetails">
                    <div class="d-flex align-items-center justify-content-between">
                        <span>Subtotal (<span id="itemCount">0</span> items)</span>
                        <span id="checkoutSubtotal">$0.00</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>Shipping</span>
                        <span id="checkoutShipping">$5.99</span>
                    </div>
                    <div id="couponDiscountRow" class="d-flex align-items-center justify-content-between text-success" style="display: none;">
                        <span>
                            <i class="ti ti-tag me-1"></i>
                            Coupon Discount (<span id="appliedCouponCodeSummary"></span>)
                        </span>
                        <span id="checkoutCouponDiscount">-$0.00</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>{{ config('tax.label', 'Tax') }} ({{ config('tax.default_rate', 8) }}%)</span>
                        <span id="checkoutTax">$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" id="checkoutTotal">$0.00</span>
                    </div>
                </div>
                
                <!-- Add to Cart Button (shown when cart is empty) -->
                <div id="emptyCartActions" class="text-center mt-3" style="display: none;">
                    <p class="text-muted mb-3">Your cart is empty. Add some items to continue with checkout.</p>
                    <a href="/cart-test" class="btn btn-outline-primary me-2">Add Test Item</a>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary">Go to Cart</a>
                </div>
            </div>
        </div>
        
        <!-- Place Order Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg w-100">
                Place Order <i class="ms-1 ti ti-check"></i>
            </button>
        </div>
    </form>
</div>

<style>
.alert {
    border: none;
    border-radius: 8px;
}

.input-group .btn {
    border-left: none;
}

.coupon-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.coupon-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

#appliedCoupon {
    background-color: #d1f2eb;
    border-color: #a3e4d7;
    color: #0c5460;
}

.shipping-option {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px !important;
    transition: all 0.2s ease;
    cursor: pointer;
}

.shipping-option:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.shipping-option.selected {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.shipping-method:checked + label .shipping-option {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.shipping-rate {
    font-size: 1.1rem;
    color: #28a745;
}

.shipping-note {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    border-left: 4px solid #007bff;
}

.payment-logo {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 12px;
    text-align: center;
    min-width: 50px;
    color: white;
}

.bkash-logo {
    background-color: #e2136e;
}

.rocket-logo {
    background-color: #8e44ad;
}

.nagad-logo {
    background-color: #f39c12;
}

.payment-method-area .form-check {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s ease;
}

.payment-method-area .form-check:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.payment-method-area .form-check input:checked + label {
    color: #007bff;
    font-weight: 500;
}

.payment-method-area .card {
    background-color: #f8f9fc;
    border: 1px solid #e3e6f0;
}

.alert-info {
    background-color: #e8f4f8;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let appliedCoupon = null;
    
    // Load cart data on page load
    loadCheckoutData();
    
    // Handle shipping method change
    $('.shipping-method').on('change', function() {
        updateShippingSelection();
        calculateTotals();
    });
    
    // Update shipping selection UI
    function updateShippingSelection() {
        $('.shipping-option').removeClass('selected');
        $('.shipping-method:checked').closest('.shipping-option').addClass('selected');
    }
    
    // Initialize shipping selection
    updateShippingSelection();
    
    // Apply coupon button click
    $('#applyCouponBtn').on('click', function() {
        const couponCode = $('#couponCode').val().trim();
        
        if (!couponCode) {
            showCouponError('Please enter a coupon code');
            return;
        }
        
        applyCoupon(couponCode);
    });
    
    // Remove coupon button click
    $('#removeCouponBtn').on('click', function() {
        removeCoupon();
    });
    
    // Apply coupon on Enter key
    $('#couponCode').on('keypress', function(e) {
        if (e.which === 13) {
            $('#applyCouponBtn').click();
        }
    });
    
    // Shipping method change handler
    $('input[name="shipping_method"]').on('change', function() {
        updateShippingSelection();
        calculateTotals(); // Recalculate totals when shipping method changes
        
        // Update shipping cost and method in hidden fields
        const selectedMethod = $(this).val();
        const shippingConfig = @json(config('shipping'));
        const shippingCost = shippingConfig.options[selectedMethod]?.rate || 60;
        $('#shippingCostInput').val(shippingCost);
        $('#shippingMethodInput').val(selectedMethod);
    });
    
    // Payment method change handler
    $('input[name="payment_method"]').on('change', function() {
        const selectedMethod = $(this).val();
        
        // Hide all payment option details
        $('#onlinePaymentOptions').hide();
        $('#bankTransferInfo').hide();
        $('#codInfo').hide();
        
        // Reset all form field states
        $('#transactionId').prop('disabled', true).val('');
        $('#bankTransactionRef').prop('disabled', true).val('');
        $('input[name="online_payment_type"]').prop('disabled', true);
        
        // Show relevant payment details and set requirements
        if (selectedMethod === 'online_payment') {
            $('#onlinePaymentOptions').show();
            $('#transactionId').prop('disabled', false);
            $('input[name="online_payment_type"]').prop('disabled', false);
            updatePaymentAmounts(); // Update amounts in payment instructions
        } else if (selectedMethod === 'bank_transfer') {
            $('#bankTransferInfo').show();
            $('#bankTransactionRef').prop('disabled', false);
            updatePaymentAmounts(); // Update amounts in bank transfer info
        } else if (selectedMethod === 'cash_on_delivery') {
            $('#codInfo').show();
        }
    });
    
    // Online payment type change handler
    $('input[name="online_payment_type"]').on('change', function() {
        const selectedType = $(this).val();
        
        // Hide all instruction divs
        $('#bkashInstructions, #rocketInstructions, #nagadInstructions').hide();
        
        // Show selected payment instructions
        if (selectedType === 'bkash') {
            $('#bkashInstructions').show();
        } else if (selectedType === 'rocket') {
            $('#rocketInstructions').show();
        } else if (selectedType === 'nagad') {
            $('#nagadInstructions').show();
        }
        
        updatePaymentAmounts();
    });
    
    // Function to update payment amounts
    function updatePaymentAmounts() {
        const totalAmount = $('#checkoutTotal').text().replace('৳', '').replace(',', '');
        const formattedAmount = '৳' + parseFloat(totalAmount || 0).toFixed(2);
        
        // Update all payment amount displays
        $('#bkashAmount').text(formattedAmount);
        $('#rocketAmount').text(formattedAmount);
        $('#nagadAmount').text(formattedAmount);
        $('#bankAmount').text(formattedAmount);
    }
    
    // Initialize payment method display
    $('input[name="payment_method"]:checked').trigger('change');
    $('input[name="online_payment_type"]:checked').trigger('change');
    
    // Load checkout data from cart
    function loadCheckoutData() {
        // Show loading message
        showCartStatus('Loading cart...', 'info');
        
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        // Debug: Log cart contents
        console.log('Cart contents:', cart);
        console.log('Cart length:', cart.length);
        
        if (cart.length === 0) {
            console.log('Cart is empty');
            showEmptyCartState();
            return;
        }
        
        // Hide status message and show order details
        hideCartStatus();
        showOrderSummary();
        
        // Update cart prices with current database prices
        updateCartPrices(cart);
    }
    
    function showCartStatus(message, type = 'info') {
        $('#cartStatusText').text(message);
        $('#cartStatusMessage').removeClass('alert-info alert-warning alert-danger alert-success')
                               .addClass(`alert-${type}`)
                               .show();
        $('#orderSummaryDetails').hide();
        $('#emptyCartActions').hide();
    }
    
    function hideCartStatus() {
        $('#cartStatusMessage').hide();
    }
    
    function showOrderSummary() {
        $('#orderSummaryDetails').show();
        $('#emptyCartActions').hide();
    }
    
    function showEmptyCartState() {
        $('#cartStatusMessage').hide();
        $('#orderSummaryDetails').hide();
        $('#emptyCartActions').show();
        
        // Reset values to zero with proper currency
        const currency = '{{ config("shipping.currency", "৳") }}';
        $('#itemCount').text('0');
        $('#checkoutSubtotal').text(`${currency}0.00`);
        $('#checkoutShipping').text(`${currency}60`);
        $('#checkoutTax').text(`${currency}0.00`);
        $('#checkoutTotal').text(`${currency}60.00`);
    }
    
    // Update cart prices from database
    function updateCartPrices(cart) {
        showCartStatus('Updating prices...', 'info');
        
        // Send cart items to get updated prices
        $.ajax({
            url: '/api/cart/update-prices',
            method: 'POST',
            data: {
                cart_items: cart
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                hideCartStatus();
                showOrderSummary();
                
                if (response.success) {
                    if (response.has_changes) {
                        // Show price change notification
                        let changeMessage = 'Some prices in your cart have been updated:\n\n';
                        response.price_changes.forEach(change => {
                            const oldPrice = parseFloat(change.old_price) || 0;
                            const newPrice = parseFloat(change.new_price) || 0;
                            const diff = parseFloat(change.difference) || 0;
                            const symbol = diff > 0 ? '+' : '';
                            changeMessage += `${change.product_name}: $${oldPrice.toFixed(2)} → $${newPrice.toFixed(2)} (${symbol}$${diff.toFixed(2)})\n`;
                        });
                        changeMessage += '\nYour cart has been updated with current prices.';
                        
                        // Update localStorage with new prices
                        localStorage.setItem('cart', JSON.stringify(response.updated_cart));
                        
                        // Show notification using status message instead of alert
                        showCartStatus(changeMessage.replace(/\n/g, '<br>'), 'warning');
                        setTimeout(() => hideCartStatus(), 5000);
                    }
                    
                    // Calculate totals with updated prices
                    calculateTotals();
                } else {
                    console.error('Failed to update cart prices:', response.message);
                    showCartStatus('Failed to load cart prices. Using cached prices.', 'warning');
                    // Continue with existing prices after a delay
                    setTimeout(() => {
                        hideCartStatus();
                        showOrderSummary();
                        calculateTotals();
                    }, 2000);
                }
            },
            error: function(xhr) {
                console.error('Error updating cart prices:', xhr.responseJSON?.message || 'Unknown error');
                showCartStatus('Unable to verify current prices. Using cached prices.', 'warning');
                // Continue with existing prices after a delay
                setTimeout(() => {
                    hideCartStatus();
                    showOrderSummary();
                    calculateTotals();
                }, 2000);
            }
        });
    }
    
    // Calculate totals with dynamic shipping
    function calculateTotals() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let subtotal = 0;
        let itemCount = 0;
        
        console.log('calculateTotals called, cart:', cart);
        
        cart.forEach(item => {
            const itemPrice = parseFloat(item.price) || 0;
            const itemQuantity = parseInt(item.quantity) || 0;
            const itemTotal = itemPrice * itemQuantity;
            
            subtotal += itemTotal;
            itemCount += itemQuantity;
        });
        
        console.log('Calculated subtotal:', subtotal, 'itemCount:', itemCount);
        
        // Get selected shipping method
        const selectedShippingMethod = $('input[name="shipping_method"]:checked').val() || '{{ config("shipping.default_method", "inside_dhaka") }}';
        const currency = '{{ config("shipping.currency", "৳") }}';
        
        console.log('Selected shipping method:', selectedShippingMethod);
        
        // Calculate shipping cost using API
        calculateShippingCost(cart, subtotal, selectedShippingMethod, function(shippingCost, freeShippingMessage) {
            let couponDiscount = 0;
            
            // Apply coupon discount
            if (appliedCoupon) {
                couponDiscount = appliedCoupon.discount_amount || 0;
                
                // If free shipping coupon
                if (appliedCoupon.free_shipping) {
                    shippingCost = 0;
                }
            }
            
            const taxRate = {{ config('tax.default_rate', 0) }} / 100;
            const tax = subtotal * taxRate;
            const total = subtotal + shippingCost + tax - couponDiscount;
            
            console.log('Final calculation - Subtotal:', subtotal, 'Shipping:', shippingCost, 'Tax:', tax, 'Discount:', couponDiscount, 'Total:', total);
            
            // Update UI
            $('#itemCount').text(itemCount);
            $('#checkoutSubtotal').text(`${currency}${subtotal.toFixed(2)}`);
            $('#checkoutShipping').text(shippingCost === 0 ? 'FREE' : `${currency}${shippingCost.toFixed(0)}`);
            $('#checkoutTax').text(`${currency}${tax.toFixed(2)}`);
            $('#checkoutTotal').text(`${currency}${total.toFixed(2)}`);
            
            // Update free shipping note
            if (freeShippingMessage) {
                $('#freeShippingNote').html(freeShippingMessage);
            }
            
            // Update hidden form fields
            $('#subtotalInput').val(subtotal.toFixed(2));
            $('#shippingCostInput').val(shippingCost.toFixed(2));
            $('#shippingMethodInput').val(selectedShippingMethod);
            
            // Update payment amounts
            updatePaymentAmounts();
            
            // Show/hide coupon discount
            if (appliedCoupon && couponDiscount > 0) {
                $('#checkoutCouponDiscount').text(`-${currency}${couponDiscount.toFixed(2)}`);
                $('#appliedCouponCodeSummary').text(appliedCoupon.code);
                $('#couponDiscountRow').show();
            } else {
                $('#couponDiscountRow').hide();
            }
        });
        
        // Fallback: If no cart items, show basic calculation
        if (cart.length === 0) {
            console.log('No cart items, showing empty state');
            $('#itemCount').text('0');
            $('#checkoutSubtotal').text(`${currency}0.00`);
            $('#checkoutShipping').text(`${currency}60`);
            $('#checkoutTax').text(`${currency}0.00`);
            $('#checkoutTotal').text(`${currency}60.00`);
            $('#couponDiscountRow').hide();
        }
    }
    
    // Calculate shipping cost via API
    function calculateShippingCost(cartItems, subtotal, shippingMethod, callback) {
        // Prepare cart data for API
        const cartData = cartItems.map(item => ({
            product_id: item.id,
            quantity: item.quantity
        }));
        
        $.ajax({
            url: '/api/shipping/check-free-shipping',
            method: 'POST',
            data: {
                shipping_method: shippingMethod,
                cart_items: cartData,
                subtotal: subtotal,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Get shipping cost from configuration
                    const shippingConfig = @json(config('shipping'));
                    let shippingCost = shippingConfig.options[shippingMethod]?.rate || 60;
                    
                    // If eligible for free shipping, cost is 0
                    if (response.is_eligible) {
                        shippingCost = 0;
                    }
                    
                    callback(shippingCost, response.message);
                } else {
                    // Fallback to static calculation
                    const shippingConfig = @json(config('shipping'));
                    const shippingCost = shippingConfig.options[shippingMethod]?.rate || 60;
                    callback(shippingCost, null);
                }
            },
            error: function() {
                // Fallback to static calculation
                const shippingConfig = @json(config('shipping'));
                const shippingCost = shippingConfig.options[shippingMethod]?.rate || 60;
                callback(shippingCost, null);
            }
        });
    }
    
    // Apply coupon
    function applyCoupon(couponCode) {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let subtotal = 0;
        
        cart.forEach(item => {
            subtotal += parseFloat(item.price) * parseInt(item.quantity);
        });
        
        // Show loading state
        const btnText = $('#applyCouponBtn .btn-text');
        const spinner = $('#applyCouponBtn .spinner-border');
        btnText.addClass('d-none');
        spinner.removeClass('d-none');
        $('#applyCouponBtn').prop('disabled', true);
        
        // Hide any existing messages
        hideCouponMessages();
        
        // Make API call
        $.ajax({
            url: '/api/coupons/validate',
            method: 'POST',
            data: {
                code: couponCode,
                cart_total: subtotal
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    appliedCoupon = response.coupon;
                    showAppliedCoupon(response.coupon);
                    calculateTotals();
                    $('#couponCode').val('');
                } else {
                    showCouponError(response.message);
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Failed to apply coupon';
                showCouponError(errorMessage);
            },
            complete: function() {
                // Reset loading state
                btnText.removeClass('d-none');
                spinner.addClass('d-none');
                $('#applyCouponBtn').prop('disabled', false);
            }
        });
    }
    
    // Remove coupon
    function removeCoupon() {
        appliedCoupon = null;
        hideAppliedCoupon();
        calculateTotals();
    }
    
    // Show applied coupon
    function showAppliedCoupon(coupon) {
        $('#appliedCouponCode').text(coupon.code.toUpperCase());
        $('#appliedCouponName').text(coupon.name ? ` - ${coupon.name}` : '');
        
        let discountText = '';
        if (coupon.type === 'percentage') {
            discountText = `${coupon.value}% discount applied`;
        } else if (coupon.type === 'fixed') {
            discountText = `$${coupon.value} discount applied`;
        } else if (coupon.type === 'free_shipping') {
            discountText = 'Free shipping applied';
        }
        
        if (coupon.discount_amount > 0 && coupon.type !== 'free_shipping') {
            discountText += ` (-৳${coupon.discount_amount.toFixed(2)})`;
        }
        
        $('#couponDiscountText').text(discountText);
        $('#appliedCoupon').show();
        $('#couponSection').hide();
    }
    
    // Hide applied coupon
    function hideAppliedCoupon() {
        $('#appliedCoupon').hide();
        $('#couponSection').show();
        hideCouponMessages();
    }
    
    // Show coupon error
    function showCouponError(message) {
        $('#couponErrorMessage').text(message);
        $('#couponError').show();
        $('#appliedCoupon').hide();
    }
    
    // Hide all coupon messages
    function hideCouponMessages() {
        $('#couponError').hide();
        $('#appliedCoupon').hide();
    }
    
    // Handle form submission
    $('#checkoutForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate payment fields before submission
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        let validationError = '';
        
        if (paymentMethod === 'online_payment') {
            const transactionId = $('#transactionId').val().trim();
            if (!transactionId) {
                validationError = 'Please enter your transaction ID for online payment.';
                $('#transactionId').focus();
            }
        } else if (paymentMethod === 'bank_transfer') {
            const bankRef = $('#bankTransactionRef').val().trim();
            if (!bankRef) {
                validationError = 'Please enter your bank transaction reference.';
                $('#bankTransactionRef').focus();
            }
        }
        
        if (validationError) {
            alert(validationError);
            return;
        }
        
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        if (cart.length === 0) {
            showCartStatus('Your cart is empty. Please add items to your cart first.', 'warning');
            setTimeout(() => {
                window.location.href = "{{ route('cart.index') }}";
            }, 2000);
            return;
        }
        
        // Calculate totals for submission using dynamic shipping
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += parseFloat(item.price) * parseInt(item.quantity);
        });
        
        // Get selected shipping method
        const selectedShippingMethod = $('input[name="shipping_method"]:checked').val() || '{{ config("shipping.default_method", "inside_dhaka") }}';
        
        // Calculate shipping using the same method as display
        calculateShippingCost(cart, subtotal, selectedShippingMethod, function(shippingCost, freeShippingMessage) {
            let couponDiscount = 0;
            
            if (appliedCoupon) {
                couponDiscount = appliedCoupon.discount_amount || 0;
                if (appliedCoupon.free_shipping) {
                    shippingCost = 0;
                }
            }
            
            const taxRate = {{ config('tax.default_rate', 0) }} / 100;
            const tax = subtotal * taxRate;
            const total = subtotal + shippingCost + tax - couponDiscount;
            
            // Populate hidden fields
            $('#cartItemsInput').val(JSON.stringify(cart));
            $('#subtotalInput').val(subtotal.toFixed(2));
            $('#shippingCostInput').val(shippingCost.toFixed(2));
            $('#totalInput').val(total.toFixed(2));
            $('#couponCodeInput').val(appliedCoupon ? appliedCoupon.code : '');
            $('#discountAmountInput').val(couponDiscount.toFixed(2));
            $('#shippingMethodInput').val(selectedShippingMethod);
            
            // Ensure payment fields are enabled for submission
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            if (paymentMethod === 'online_payment') {
                // Enable and ensure online payment fields have values
                $('#transactionId').prop('disabled', false);
                $('input[name="online_payment_type"]').prop('disabled', false);
            } else if (paymentMethod === 'bank_transfer') {
                // Enable bank transfer field
                $('#bankTransactionRef').prop('disabled', false);
            } else {
                // For cash on delivery, disable payment fields so they're not submitted
                $('#transactionId').prop('disabled', true);
                $('#bankTransactionRef').prop('disabled', true);
                $('input[name="online_payment_type"]').prop('disabled', true);
            }
            
            // Submit the form
            submitCheckoutForm();
        });
    });
    
    // Separate function to handle actual form submission
    function submitCheckoutForm() {
        const form = $('#checkoutForm');
        
        // Disable submit button and show loading
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        
        // Submit the form via AJAX
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Clear cart
                    localStorage.removeItem('cart');
                    
                    // Prepare success message
                    let successMessage = 'Order placed successfully! Order Number: ' + response.data.order_number;
                    
                    // Add price update notification if applicable
                    if (response.price_updated) {
                        successMessage += '\n\n' + response.price_update_message;
                    }
                    
                    // Show success message
                    alert(successMessage);
                    
                    // Redirect to orders page or home
                    window.location.href = "{{ route('orders.index') }}";
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.error_type === 'price_mismatch') {
                    // Handle price mismatch specifically
                    const details = xhr.responseJSON.details;
                    const difference = details.difference;
                    const updatedTotal = details.updated_total;
                    
                    errorMessage = `Cart prices have been updated!\n\n` +
                                 `Original total: $${details.original_total.toFixed(2)}\n` +
                                 `Updated total: $${updatedTotal.toFixed(2)}\n` +
                                 `Difference: $${difference.toFixed(2)}\n\n` +
                                 `This usually happens when product prices change after you add them to your cart.\n\n` +
                                 `Please refresh the page to see the updated prices.`;
                    
                    alert(errorMessage);
                    
                    // Optionally refresh the page after a delay
                    setTimeout(() => {
                        if (confirm('Would you like to refresh the page to see updated prices?')) {
                            window.location.reload();
                        }
                    }, 1000);
                    
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
                }
                
                if (xhr.responseJSON?.error_type !== 'price_mismatch') {
                    alert('Error: ' + errorMessage);
                }
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }
});
</script>
@endpush
@endsection
