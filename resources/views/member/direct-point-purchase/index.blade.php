@extends('member.layouts.app')

@section('title', 'Direct Point Purchase')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-primary fw-bold">
                                <i class="fas fa-coins me-2"></i>Direct Point Purchase
                            </h4>
                            <p class="text-muted mb-0">Purchase 100+ point products to earn commission bonuses</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-success fs-6 px-3 py-2">
                                Wallet Balance: ৳{{ number_format($user->deposit_wallet, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available 100+ Point Products Section -->
    @if($activationProducts->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-box-open me-2"></i>{{ $categoryName }}
                            </h5>
                            <small class="d-block mt-1 opacity-90">Choose a product to purchase and earn 100+ points</small>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <form method="GET" class="d-flex gap-2">
                                <select name="vendor_id" class="form-select form-select-sm text-dark" onchange="this.form.submit()">
                                    <option value="all" {{ $vendorFilter === 'all' || !$vendorFilter ? 'selected' : '' }}>All Vendors</option>
                                    <option value="direct" {{ $vendorFilter === 'direct' ? 'selected' : '' }}>Direct Sales Only</option>
                                    @foreach($vendorsWithProducts as $vendor)
                                        <option value="{{ $vendor->id }}" {{ $vendorFilter == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->shop_name ?? $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($vendorFilter && $vendorFilter !== 'all')
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-filter me-2"></i>
                            @if($vendorFilter === 'direct')
                                Showing products from <strong>Direct Sales</strong> only.
                            @else
                                @php $selectedVendor = $vendorsWithProducts->find($vendorFilter); @endphp
                                Showing products from <strong>{{ $selectedVendor ? ($selectedVendor->shop_name ?? $selectedVendor->name) : 'Selected Vendor' }}</strong> only.
                            @endif
                            <a href="{{ route('member.direct-point-purchase.index') }}" class="text-decoration-none ms-2">
                                <small>Show All Products</small>
                            </a>
                        </div>
                    @endif
                    <div class="row">
                        @foreach($activationProducts as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm product-card {{ $affordableProducts->contains('id', $product->id) ? 'affordable' : 'not-affordable' }}">
                                @php
                                    // Comprehensive image URL handling for packages (same as home.blade.php)
                                    $mainImageUrl = '/admin-assets/images/media/1.jpg'; // Default fallback
                                    
                                    // Convert array to object-like access for consistency
                                    $packageObj = (object) $product->toArray();
                                    
                                    // First try images array (NOT image_data)
                                    if (isset($packageObj->images) && $packageObj->images) {
                                        $images = is_string($packageObj->images) ? json_decode($packageObj->images, true) : $packageObj->images;
                                        if (is_array($images) && !empty($images)) {
                                            $image = $images[0]; // Get first image
                                            
                                            // Handle complex nested structure first
                                            if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                // New complex structure - use medium size storage_url
                                                $mainImageUrl = $image['sizes']['medium']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                // Fallback to original if medium not available
                                                $mainImageUrl = $image['sizes']['original']['storage_url'];
                                            } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                // Fallback to large if original not available
                                                $mainImageUrl = $image['sizes']['large']['storage_url'];
                                            } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                // Legacy complex URL structure - use medium size
                                                $mainImageUrl = $image['urls']['medium'];
                                            } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                // Legacy fallback to original if medium not available
                                                $mainImageUrl = $image['urls']['original'];
                                            } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                $mainImageUrl = $image['url'];
                                            } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                $mainImageUrl = asset('storage/' . $image['path']);
                                            }
                                        }
                                    }
                                    
                                    // Fallback to image field if images array didn't work
                                    if ($mainImageUrl === '/admin-assets/images/media/1.jpg' && isset($packageObj->image) && $packageObj->image) {
                                        $packageImage = $packageObj->image;
                                        if ($packageImage) {
                                            $mainImageUrl = str_starts_with($packageImage, 'http') ? $packageImage : asset('storage/' . $packageImage);
                                        }
                                    }
                                    
                                    // Convert storage URLs to direct-storage for reliability (removed - using storage_url directly)
                                    // The image structure already provides storage_url, so we use it directly
                                @endphp
                                
                                <img src="{{ $mainImageUrl }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='/admin-assets/images/media/1.jpg'">
                                
                                <!-- Product Badge -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-success">100+ Points</span>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-auto">
                                        <h6 class="card-title fw-bold text-dark">{{ $product->name }}</h6>
                                        
                                        @if($product->vendor)
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-store me-1"></i>
                                                    <strong>{{ $product->vendor->shop_name ?? $product->vendor->name }}</strong>
                                                </small>
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                <small class="text-primary">
                                                    <i class="fas fa-certificate me-1"></i>
                                                    <strong>Direct Sales</strong>
                                                </small>
                                            </div>
                                        @endif
                                        
                                        @if($product->short_description)
                                            <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
                                        @endif
                                        
                                        <!-- Package Details -->
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <div class="border rounded p-2 bg-light">
                                                    <div class="fw-bold text-primary fs-6">{{ number_format($product->pv_points ?? 0) }}</div>
                                                    <small class="text-muted">Points</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-2 bg-light">
                                                    <div class="fw-bold text-success fs-6">৳{{ number_format($product->price ?? 0, 2) }}</div>
                                                    <small class="text-muted">Price</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-2 bg-light">
                                                    <div class="fw-bold text-info fs-6">{{ $product->pv_points ? number_format(($product->pv_points * 6) / ($product->price > 0 ? $product->price : 1), 1) : '0' }}x</div>
                                                    <small class="text-muted">Value</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Package Benefits -->
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-secondary mb-2">Package Benefits:</h6>
                                            <ul class="list-unstyled small">
                                                <li class="mb-1">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    Product Purchase
                                                </li>
                                                <li class="mb-1">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    {{ number_format($product->pv_points ?? 0) }} Instant Points
                                                </li>
                                                <li class="mb-1">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    MLM Commission Eligibility
                                                </li>
                                                @if(($product->pv_points ?? 0) >= 100)
                                                <li class="mb-1">
                                                    <i class="fas fa-check text-success me-1"></i>
                                                    Binary Matching Eligible
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <!-- Purchase Button -->
                                    <div class="mt-auto">
                                        @if($affordableProducts->contains('id', $product->id))
                                            <!-- Product Purchase Form -->
                                            <form action="{{ route('member.direct-point-purchase.purchase-product') }}" method="POST" class="product-purchase-form" id="productForm_{{ $product->id }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                
                                                <!-- Shipping Information (Hidden Fields) -->
                                                <input type="hidden" name="shipping_address[full_name]" id="shipping_full_name_{{ $product->id }}">
                                                <input type="hidden" name="shipping_address[phone]" id="shipping_phone_{{ $product->id }}">
                                                <input type="hidden" name="shipping_address[address]" id="shipping_address_{{ $product->id }}">
                                                <input type="hidden" name="shipping_address[city]" id="shipping_city_{{ $product->id }}">
                                                <input type="hidden" name="shipping_address[postal_code]" id="shipping_postal_{{ $product->id }}">
                                                <input type="hidden" name="shipping_address[district]" id="shipping_district_{{ $product->id }}">
                                                
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input product-confirm" type="checkbox" name="confirm_purchase" value="1" id="confirm_product_{{ $product->id }}">
                                                    <label class="form-check-label small" for="confirm_product_{{ $product->id }}">
                                                        I confirm this product purchase
                                                    </label>
                                                </div>
                                                
                                                @if(true) {{-- Temporarily force shipping for testing --}}
                                                {{-- @if(!$product->is_digital) --}}
                                                <!-- Physical Product - Show Shipping Button -->
                                                <button type="button" 
                                                        class="btn btn-warning w-100 product-shipping-btn fw-semibold mb-2" 
                                                        data-product-id="{{ $product->id }}"
                                                        data-product-name="{{ $product->name }}"
                                                        data-product-price="{{ $product->price }}">
                                                    <i class="fas fa-shipping-fast me-1"></i>Add Shipping & Purchase
                                                </button>
                                                @else
                                                <!-- Digital Product - Direct Purchase -->
                                                <button type="submit" class="btn btn-warning w-100 product-submit-btn fw-semibold" disabled>
                                                    <i class="fas fa-download me-1"></i>Purchase for ৳{{ number_format($product->price, 2) }}
                                                </button>
                                                @endif
                                            </form>
                                        @else
                                            <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                                <i class="fas fa-wallet me-1"></i>Insufficient Balance - Need ৳{{ number_format($product->price, 2) }}
                                            </button>
                                            <small class="text-muted d-block mt-1 text-center">
                                                Available: ৳{{ number_format($user->deposit_wallet, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($activationProducts->count() == 0)
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-box-open text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="text-muted">No 100+ point products available</h6>
                            <p class="text-muted small">Please contact support for available products.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Commission Info -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Commission Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Sponsor Bonus</h6>
                            <p class="text-muted mb-3">Your direct sponsor will receive commission based on your point purchase.</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success">Generation Bonus</h6>
                            <p class="text-muted mb-3">Your upline generations will receive bonuses according to commission settings.</p>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Notice:</strong> You can purchase multiple products to earn more points and commissions. Each purchase generates bonuses for your upline.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Information Modal -->
<div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning text-white">
                <h5 class="modal-title" id="shippingModalLabel">
                    <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Product:</strong> <span id="modal-product-name">-</span><br>
                            <strong>Price:</strong> ৳<span id="modal-product-price">0.00</span><br>
                            <strong>Opened At:</strong> <span id="modal-opened-time">-</span><br>
                            <strong>Delivery:</strong> This product will be shipped to your address within 3-7 business days.
                        </div>
                    </div>
                </div>
                
                <form id="shippingForm">
                    <!-- Personal Information Section -->
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="modal_first_name" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_first_name" name="first_name" value="{{ explode(' ', $user->name)[0] ?? $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control" id="modal_last_name" name="last_name" value="{{ explode(' ', $user->name)[1] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="modal_email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="modal_email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="modal_phone" name="phone" value="{{ $user->phone }}" required>
                        </div>
                    </div>

                    <!-- Shipping Address Section -->
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>Delivery Address
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="modal_address" class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="modal_address" name="address" rows="3" placeholder="House/Apartment, Road, Area, Landmark" required></textarea>
                            <div class="form-text">Include house number, street name, area, and any landmarks</div>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_city" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_city" name="city" placeholder="e.g., Dhaka, Chittagong" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_postal_code" class="form-label fw-semibold">ZIP/Postal Code</label>
                            <input type="text" class="form-control" id="modal_postal_code" name="postal_code" placeholder="e.g., 1000">
                        </div>
                        <div class="col-12">
                            <label for="modal_district" class="form-label fw-semibold">District <span class="text-danger">*</span></label>
                            <select class="form-control" id="modal_district" name="district" required>
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="col-12" id="upazilla_container" style="display: none;">
                            <label for="modal_upazilla" class="form-label fw-semibold">Upazilla/Thana</label>
                            <select class="form-control" id="modal_upazilla" name="upazilla">
                                <option value="">Select Upazilla</option>
                            </select>
                        </div>
                    </div>

                    <!-- Shipping Method Section -->
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                        <i class="fas fa-truck me-2"></i>Shipping Method
                    </h6>
                    <div class="shipping-options mb-4">
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="shipping_method" id="standard_shipping" value="standard" checked>
                            <label class="form-check-label w-100" for="standard_shipping">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark">Standard Shipping</strong>
                                        <div class="text-muted small mt-1" id="standard_delivery_time">Select district to view delivery time</div>
                                        <div class="text-primary small">
                                            <i class="fas fa-clock me-1"></i><span id="standard_delivery_estimate">Delivery estimate</span>
                                        </div>
                                    </div>
                                    <div class="text-success fw-bold fs-5" id="standard_price">Select District</div>
                                </div>
                            </label>
                        </div>
                        <div class="form-check p-3 border rounded" id="express_shipping_option" style="display: none;">
                            <input class="form-check-input" type="radio" name="shipping_method" id="express_shipping" value="express" disabled>
                            <label class="form-check-label w-100" for="express_shipping">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark">Express Shipping</strong>
                                        <div class="text-muted small mt-1" id="express_delivery_time">1-2 business days (Dhaka area only)</div>
                                        <div class="text-warning small">
                                            <i class="fas fa-bolt me-1"></i>Fast delivery
                                        </div>
                                    </div>
                                    <div class="text-warning fw-bold fs-5" id="express_price">Calculate</div>
                                </div>
                            </label>
                        </div>
                        <div class="alert alert-info mt-3" id="delivery_info" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="delivery_charge_info">Select district to calculate delivery charges</span>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> Please provide accurate shipping information. Delivery times may vary during holidays. We'll send tracking information once your order ships.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmPurchaseBtn">
                    <i class="fas fa-shopping-cart me-1"></i>Confirm Purchase
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Make wallet balance available to JavaScript
window.userWalletBalance = {{ $user->deposit_wallet }};
</script>

<script>
// Service Worker Cache Management
document.addEventListener('DOMContentLoaded', function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(function(registration) {
            if (registration.active) {
                // Send message to clear matching page cache
                registration.active.postMessage({
                    type: 'CLEAR_MATCHING_CACHE'
                });
            }
        });
        
        // Also force a hard refresh if page was loaded from cache
        if (performance.navigation.type === 2) {
            // Page was loaded from back/forward cache
            window.location.reload(true);
        }
    }
});

$(document).ready(function() {
    let currentProductId = null;
    
    // Debug: Check if libraries are loaded
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('Bootstrap Modal available:', typeof bootstrap.Modal !== 'undefined');
    
    // Product confirm checkbox handler
    $('.product-confirm').on('change', function() {
        const productCard = $(this).closest('.product-card');
        const submitBtn = productCard.find('.product-submit-btn');
        const shippingBtn = productCard.find('.product-shipping-btn');
        
        const isChecked = $(this).is(':checked');
        
        // Enable/disable submit button only (shipping button stays enabled for modal access)
        submitBtn.prop('disabled', !isChecked);
        
        // Add visual feedback
        if (isChecked) {
            productCard.addClass('confirmed');
            submitBtn.removeClass('btn-warning').addClass('btn-success');
            shippingBtn.removeClass('btn-warning').addClass('btn-success');
        } else {
            productCard.removeClass('confirmed');
            submitBtn.removeClass('btn-success').addClass('btn-warning');
            shippingBtn.removeClass('btn-success').addClass('btn-warning');
        }
    });

    // Shipping button click handler (using event delegation)
    $(document).on('click', '.product-shipping-btn', function(e) {
        e.preventDefault();
        console.log('Shipping button clicked');
        
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = $(this).data('product-price');
        
        console.log('Product data:', { productId, productName, productPrice });
        
        currentProductId = productId;
        
        // Update modal content with timestamp
        $('#modal-product-name').text(productName);
        $('#modal-product-price').text(parseFloat(productPrice).toLocaleString('en-BD', {minimumFractionDigits: 2}));
        
        // Set modal opened time
        const now = new Date();
        const timeString = now.toLocaleString('en-BD', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
        $('#modal-opened-time').text(timeString);
        
        // Check if modal element exists
        const modalElement = document.getElementById('shippingModal');
        console.log('Modal element found:', modalElement);
        
        if (!modalElement) {
            console.error('Shipping modal element not found');
            alert('Error: Modal not found. Please refresh the page.');
            return false;
        }
        
        // Show modal
        try {
            const modal = new bootstrap.Modal(modalElement);
            console.log('Bootstrap modal created:', modal);
            
            // Reset delivery charge state when modal opens
            $('#standard_price').text('Select District');
            $('#express_price').text('Calculate');
            $('#delivery_info').hide();
            $('#confirmPurchaseBtn').prop('disabled', false).removeClass('btn-danger').addClass('btn-warning').html('<i class="fas fa-shopping-cart me-1"></i>Confirm Purchase');
            
            modal.show();
            console.log('Modal show() called');
        } catch (error) {
            console.error('Error showing modal:', error);
            alert('Error opening modal: ' + error.message);
        }
    });

    // Confirm purchase button in modal
    $('#confirmPurchaseBtn').on('click', function() {
        if (!currentProductId) return;
        
        // Check if confirmation checkbox is checked
        const confirmCheckbox = $(`#confirm_product_${currentProductId}`);
        if (!confirmCheckbox.is(':checked')) {
            alert('Please check the confirmation checkbox to proceed with the purchase.');
            return false;
        }
        
        // Validate shipping form
        const form = document.getElementById('shippingForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Get shipping data
        const shippingData = {
            full_name: $('#modal_first_name').val() + ' ' + $('#modal_last_name').val(),
            phone: $('#modal_phone').val(),
            address: $('#modal_address').val(),
            city: $('#modal_city').val(),
            district: $('#modal_district').val(),
            postal_code: $('#modal_postal_code').val()
        };
        
        // Populate hidden fields in the form
        const productForm = $(`#productForm_${currentProductId}`);
        productForm.find('#shipping_full_name_' + currentProductId).val(shippingData.full_name);
        productForm.find('#shipping_phone_' + currentProductId).val(shippingData.phone);
        productForm.find('#shipping_address_' + currentProductId).val(shippingData.address);
        productForm.find('#shipping_city_' + currentProductId).val(shippingData.city);
        productForm.find('#shipping_district_' + currentProductId).val(shippingData.district);
        productForm.find('#shipping_postal_' + currentProductId).val(shippingData.postal_code);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('shippingModal'));
        modal.hide();
        
        // Show confirmation and submit
        const productName = $('#modal-product-name').text();
        const productPrice = '৳' + $('#modal-product-price').text();
        
        const confirmed = confirm(
            `Confirm Purchase:\n\n` +
            `Product: ${productName}\n` +
            `Price: ${productPrice}\n` +
            `Shipping to: ${shippingData.full_name}\n` +
            `Address: ${shippingData.address}, ${shippingData.city}, ${shippingData.district}\n\n` +
            'This will:\n' +
            '• Activate your account\n' +
            '• Credit points to your account\n' +
            '• Distribute commissions to your upline\n' +
            '• Ship product to your address\n' +
            '• Deduct amount from your deposit wallet'
        );
        
        if (confirmed) {
            // Show loading state
            const shippingBtn = $(`.product-shipping-btn[data-product-id="${currentProductId}"]`);
            const originalText = shippingBtn.html();
            shippingBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
            shippingBtn.prop('disabled', true);
            
            // Refresh CSRF token before submission
            refreshCSRFToken().then(() => {
                // Submit the form
                productForm.submit();
            }).catch((error) => {
                console.error('CSRF token refresh failed:', error);
                alert('Session expired. Please refresh the page and try again.');
                // Restore button
                shippingBtn.html(originalText);
                shippingBtn.prop('disabled', false);
            });
        }
    });

    // Digital product purchase form submission with confirmation
    $('.product-submit-btn').on('click', function(e) {
        e.preventDefault();
        
        const productCard = $(this).closest('.product-card');
        const productName = productCard.find('.card-title').text();
        const productPrice = $(this).text().match(/৳[\d,]+\.\d{2}/)[0];
        
        const confirmed = confirm(
            `Are you sure you want to purchase "${productName}" for ${productPrice}?\n\n` +
            'This will:\n' +
            '• Activate your account\n' +
            '• Credit points to your account\n' +
            '• Distribute commissions to your upline\n' +
            '• Instantly deliver digital product\n' +
            '• Deduct amount from your deposit wallet'
        );
        
        if (!confirmed) {
            return false;
        }
        
        // Show loading state
        const originalText = $(this).html();
        $(this).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
        $(this).prop('disabled', true);
        
        // Refresh CSRF token before submission
        refreshCSRFToken().then(() => {
            // Submit the form
            $(this).closest('form').submit();
        }).catch((error) => {
            console.error('CSRF token refresh failed:', error);
            alert('Session expired. Please refresh the page and try again.');
            // Restore button
            $(this).html(originalText);
            $(this).prop('disabled', false);
        });
    });
    
    // Function to refresh CSRF token
    function refreshCSRFToken() {
        return new Promise((resolve, reject) => {
            $.get('/member/csrf-token')
                .done(function(data) {
                    if (data.token) {
                        // Update all CSRF token fields
                        $('input[name="_token"]').val(data.token);
                        $('meta[name="csrf-token"]').attr('content', data.token);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': data.token
                            }
                        });
                        resolve(data.token);
                    } else {
                        reject('No token received');
                    }
                })
                .fail(function() {
                    // If endpoint doesn't exist, resolve anyway (fallback)
                    resolve();
                });
        });
    }
    
    // Load Bangladesh location data
    let bangladeshData = [];
    let deliveryRates = {}; // Will be loaded dynamically from database
    
    // Load delivery charges from database
    function loadDeliveryCharges() {
        $.getJSON('/member/orders/delivery-charges/all', function(response) {
            if (response.success && response.charges) {
                // Convert database structure to our expected format
                deliveryRates = {
                    standard: {}
                };
                
                // Process each district
                for (const district in response.charges) {
                    const districtCharges = response.charges[district];
                    
                    // Find district default (no upazila/ward specified)
                    if (districtCharges.district_default) {
                        deliveryRates.standard[district] = parseFloat(districtCharges.district_default.charge);
                    } else {
                        // If no district default, use first available charge or default
                        const firstCharge = Object.values(districtCharges)[0];
                        deliveryRates.standard[district] = firstCharge ? parseFloat(firstCharge.charge) : 100;
                    }
                }
                
                // Set default fallback
                deliveryRates.standard.default = 100; // Default if district not found
                
                console.log('Dynamic delivery rates loaded:', deliveryRates);
                
                // Debug specific districts
                console.log('Rangpur charge:', deliveryRates.standard['Rangpur']);
                console.log('Dhaka charge:', deliveryRates.standard['Dhaka']);
            } else {
                console.error('Failed to load delivery charges, using fallback rates');
                setFallbackDeliveryRates();
            }
        }).fail(function() {
            console.error('Error loading delivery charges from database, using fallback rates');
            setFallbackDeliveryRates();
        });
    }
    
    // Fallback delivery rates (original hardcoded values)
    function setFallbackDeliveryRates() {
        deliveryRates = {
            standard: {
                'Dhaka': 60,
                'Gazipur': 80,
                'Narayanganj': 70,
                'Tangail': 100,
                'Manikganj': 90,
                'Munshiganj': 80,
                'Faridpur': 120,
                'Rajbari': 130,
                'Gopalganj': 140,
                'Madaripur': 130,
                'Shariatpur': 140,
                'Kishoreganj': 110,
                'Netrokona': 130,
                'Chittagong': 120,
                'Sylhet': 140,
                'Rajshahi': 130,
                'Rangpur': 150,
                'Khulna': 130,
                'Barisal': 140,
                'Mymensingh': 120,
                'default': 100
            }
        };
    }
    
    // Load Bangladesh location data from JSON file
    $.getJSON('/data/bangladesh-locations.json', function(data) {
        bangladeshData = data;
        
        // Populate district dropdown
        const districtSelect = $('#modal_district');
        districtSelect.empty().append('<option value="">Select District</option>');
        
        data.forEach(function(division) {
            districtSelect.append(`<option value="${division.name}">${division.name}</option>`);
        });
        
        console.log('Bangladesh location data loaded:', data.length + ' divisions');
    }).fail(function() {
        console.error('Failed to load Bangladesh location data');
        // Fallback to basic districts
        const basicDistricts = ['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Barisal', 'Sylhet', 'Rangpur', 'Mymensingh'];
        const districtSelect = $('#modal_district');
        basicDistricts.forEach(function(district) {
            districtSelect.append(`<option value="${district}">${district}</option>`);
        });
    });
    
    // Load delivery charges from database
    loadDeliveryCharges();
    
    // Handle district change
    $('#modal_district').on('change', function() {
        const selectedDistrict = $(this).val();
        const upazillaContainer = $('#upazilla_container');
        const upazillaSelect = $('#modal_upazilla');
        
        // Reset upazilla
        upazillaSelect.empty().append('<option value="">Select Upazilla</option>');
        upazillaContainer.hide();
        
        if (selectedDistrict && bangladeshData.length > 0) {
            // Find the division containing this district
            const division = bangladeshData.find(div => div.name === selectedDistrict);
            
            if (division && division.upazilas) {
                division.upazilas.forEach(function(upazila) {
                    upazillaSelect.append(`<option value="${upazila.name}">${upazila.name}</option>`);
                });
                upazillaContainer.show();
            }
            
            // Update shipping options and calculate delivery charges
            updateShippingOptions(selectedDistrict);
        }
        
        // Calculate delivery charges
        calculateDeliveryCharges();
    });
    
    // Handle upazilla change to recalculate delivery charges
    $('#modal_upazilla').on('change', function() {
        calculateDeliveryCharges();
    });
    
    // Handle shipping method change
    $('input[name="shipping_method"]').on('change', function() {
        calculateDeliveryCharges();
        
        // Also check affordability when shipping method changes
        const standardPrice = $('#standard_price').text();
        const expressPrice = $('#express_price').text();
        
        if (standardPrice !== 'Select District' && standardPrice !== 'Calculate') {
            const standardCharge = standardPrice === 'FREE' ? 0 : parseFloat(standardPrice.replace('৳', ''));
            const expressCharge = parseFloat(expressPrice.replace('৳', ''));
            
            if (!isNaN(standardCharge) && !isNaN(expressCharge)) {
                checkAffordabilityWithDelivery(standardCharge, expressCharge);
            }
        }
    });
    
    function updateShippingOptions(district) {
        const expressOption = $('#express_shipping_option');
        const expressRadio = $('#express_shipping');
        
        // Express shipping only available for Dhaka area
        const dhakaAreas = ['Dhaka', 'Gazipur', 'Narayanganj'];
        
        if (dhakaAreas.includes(district)) {
            expressOption.show();
            expressRadio.prop('disabled', false);
        } else {
            expressOption.hide();
            expressRadio.prop('disabled', true);
            // Switch to standard if express was selected
            if (expressRadio.is(':checked')) {
                $('#standard_shipping').prop('checked', true);
            }
        }
    }
    
    function calculateDeliveryCharges() {
        const district = $('#modal_district').val();
        const upazila = $('#modal_upazilla').val();
        const shippingMethod = $('input[name="shipping_method"]:checked').val();
        
        if (!district) {
            $('#standard_price').text('Select District');
            $('#express_price').text('Calculate');
            $('#standard_delivery_time').text('Select district to view delivery time');
            $('#standard_delivery_estimate').text('Delivery estimate');
            $('#delivery_info').hide();
            return;
        }
        
        // If we have upazila, try to get specific charges from API
        if (upazila) {
            $.ajax({
                url: '/member/orders/delivery-charge',
                method: 'GET',
                data: {
                    district: district,
                    upazila: upazila
                },
                success: function(response) {
                    if (response.charge) {
                        const standardCharge = parseFloat(response.charge);
                        const expressCharge = standardCharge + 100;
                        const deliveryTime = response.estimated_delivery_time || '3-5 days';
                        
                        // Update prices
                        $('#standard_price').text(standardCharge === 0 ? 'FREE' : '৳' + standardCharge.toFixed(0));
                        $('#express_price').text('৳' + expressCharge.toFixed(0));
                        
                        // Update dynamic delivery time text from database
                        $('#standard_delivery_time').text(`Delivery time: ${deliveryTime} for ${district}${upazila ? ' > ' + upazila : ''}`);
                        $('#standard_delivery_estimate').text(`Est. ${deliveryTime}`);
                        
                        // Show delivery info
                        const selectedCharge = shippingMethod === 'express' ? expressCharge : standardCharge;
                        const methodText = shippingMethod === 'express' ? 'Express' : 'Standard';
                        
                        $('#delivery_charge_info').html(`
                            ${methodText} delivery to ${district}${upazila ? ' > ' + upazila : ''}: ৳${selectedCharge.toFixed(0)}<br>
                            <small class="text-muted">Estimated delivery: ${deliveryTime}</small>
                        `);
                        $('#delivery_info').show();
                        
                        // Check affordability with delivery charges
                        checkAffordabilityWithDelivery(standardCharge, expressCharge);
                        
                        console.log('API delivery charge:', response);
                        return;
                    }
                },
                error: function() {
                    console.log('API failed, falling back to cached rates');
                    // Continue to fallback code below
                }
            });
        }
        
        // Fallback to cached delivery rates (also try API without upazila)
        $.ajax({
            url: '/member/orders/delivery-charge',
            method: 'GET',
            data: {
                district: district
            },
            success: function(response) {
                if (response.charge) {
                    const standardCharge = parseFloat(response.charge);
                    const expressCharge = standardCharge + 100;
                    const deliveryTime = response.estimated_delivery_time || '3-5 days';
                    
                    // Update prices
                    $('#standard_price').text(standardCharge === 0 ? 'FREE' : '৳' + standardCharge.toFixed(0));
                    $('#express_price').text('৳' + expressCharge.toFixed(0));
                    
                    // Update dynamic delivery time text from database
                    $('#standard_delivery_time').text(`Delivery time: ${deliveryTime} for ${district}`);
                    $('#standard_delivery_estimate').text(`Est. ${deliveryTime}`);
                    
                    // Show delivery info
                    const selectedCharge = shippingMethod === 'express' ? expressCharge : standardCharge;
                    const methodText = shippingMethod === 'express' ? 'Express' : 'Standard';
                    
                    $('#delivery_charge_info').html(`
                        ${methodText} delivery to ${district}: ৳${selectedCharge.toFixed(0)}<br>
                        <small class="text-muted">Estimated delivery: ${deliveryTime}</small>
                    `);
                    $('#delivery_info').show();
                    
                    // Check affordability with delivery charges
                    checkAffordabilityWithDelivery(standardCharge, expressCharge);
                    
                    console.log('API delivery charge (district only):', response);
                }
            },
            error: function() {
                console.log('All API calls failed, using hardcoded fallback');
                
                // Final fallback to cached delivery rates
                const standardCharge = deliveryRates.standard[district] !== undefined ? deliveryRates.standard[district] : deliveryRates.standard.default || 100;
                const expressCharge = standardCharge + 100;
                
                console.log(`Fallback calculation for ${district}:`, {
                    rawCharge: deliveryRates.standard[district],
                    finalCharge: standardCharge,
                    isZero: standardCharge === 0
                });
                
                // Update prices - handle ৳0 as FREE
                $('#standard_price').text(standardCharge === 0 ? 'FREE' : '৳' + standardCharge);
                $('#express_price').text('৳' + expressCharge);
                
                // Update with fallback delivery time
                $('#standard_delivery_time').text(`Estimated delivery time for ${district}`);
                $('#standard_delivery_estimate').text('Est. 3-7 days');
                
                // Show delivery info
                const selectedCharge = shippingMethod === 'express' ? expressCharge : standardCharge;
                const methodText = shippingMethod === 'express' ? 'Express' : 'Standard';
                
                $('#delivery_charge_info').html(`
                    ${methodText} delivery to ${district}: ৳${selectedCharge}<br>
                    <small class="text-muted">Estimated delivery: 3-7 business days</small>
                `);
                $('#delivery_info').show();
                
                // Check affordability with delivery charges
                checkAffordabilityWithDelivery(standardCharge, expressCharge);
            }
        });
    }
    
    // Function to check if user can afford product + delivery charges
    function checkAffordabilityWithDelivery(standardCharge, expressCharge) {
        if (!currentProductId) return;
        
        // Get product price from modal
        const productPrice = parseFloat($('#modal-product-price').text().replace(/,/g, ''));
        if (isNaN(productPrice)) return;
        
        // Get selected shipping method
        const shippingMethod = $('input[name="shipping_method"]:checked').val();
        const deliveryCharge = shippingMethod === 'express' ? expressCharge : standardCharge;
        const totalRequired = productPrice + deliveryCharge;
        
        const canAfford = window.userWalletBalance >= totalRequired;
        
        // Update confirm purchase button state
        const confirmBtn = $('#confirmPurchaseBtn');
        const deliveryInfo = $('#delivery_info');
        
        if (!canAfford) {
            // Disable purchase and show warning
            confirmBtn.prop('disabled', true);
            confirmBtn.removeClass('btn-warning').addClass('btn-danger');
            confirmBtn.html('<i class="fas fa-wallet me-1"></i>Insufficient Balance');
            
            // Add affordability warning to delivery info
            const deficit = totalRequired - window.userWalletBalance;
            const warningHtml = `
                <div class="alert alert-danger mt-2 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Insufficient Balance!</strong><br>
                    Required: ৳${totalRequired.toFixed(2)} (Product: ৳${productPrice.toFixed(2)} + Delivery: ৳${deliveryCharge.toFixed(0)})<br>
                    Available: ৳${window.userWalletBalance.toFixed(2)}<br>
                    <strong>Need ৳${deficit.toFixed(2)} more</strong>
                </div>
            `;
            deliveryInfo.find('.alert-danger').remove(); // Remove existing warning
            deliveryInfo.append(warningHtml);
            
        } else {
            // Enable purchase
            confirmBtn.prop('disabled', false);
            confirmBtn.removeClass('btn-danger').addClass('btn-warning');
            confirmBtn.html('<i class="fas fa-shopping-cart me-1"></i>Confirm Purchase');
            
            // Remove affordability warning
            deliveryInfo.find('.alert-danger').remove();
        }
        
        console.log('Affordability check:', {
            productPrice,
            deliveryCharge,
            totalRequired,
            walletBalance: window.userWalletBalance,
            canAfford
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* Product Card Styles */
.product-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.product-card.affordable {
    border: 2px solid #ffc107 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3) !important;
}

.product-card.not-affordable {
    opacity: 0.75;
    border: 1px solid #dee2e6;
}

.product-card.confirmed {
    border: 2px solid #198754 !important;
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3) !important;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}

.product-card .position-absolute .badge {
    font-size: 0.7rem;
}

.product-card .card-img-top {
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.product-submit-btn:disabled,
.product-shipping-btn:disabled {
    opacity: 0.6;
}

.product-confirm:checked ~ .product-submit-btn,
.product-confirm:checked ~ .product-shipping-btn {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754, #146c43);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800);
}
</style>
@endpush

