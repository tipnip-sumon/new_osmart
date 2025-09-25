@extends('member.layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                @if(isset($reorderData))
                    Reorder Items - Order #{{ $reorderData['order']->order_number }}
                @else
                    Create New Order
                @endif
            </h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.orders.index') }}">Orders</a></li>
                        @if(isset($reorderData))
                            <li class="breadcrumb-item"><a href="{{ route('member.orders.show', $reorderData['order']->id) }}">Order #{{ $reorderData['order']->order_number }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reorder</li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-shopping-cart me-2"></i>Order Information
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('member.orders.index') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left me-1"></i>Back to Orders
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(isset($reorderData))
                            <div class="alert alert-info alert-dismissible fade show">
                                <i class="fe fe-info-circle me-2"></i>
                                <strong>Reordering Items:</strong> Products and shipping information from Order #{{ $reorderData['order']->order_number }} have been loaded. 
                                @if($reorderData['items']->where('available', false)->count() > 0)
                                    <br><small class="text-warning"><i class="fe fe-alert-triangle me-1"></i>Note: Some products may no longer be available and need to be updated.</small>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Currency and Payment Info Banner -->
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class="fe fe-info me-2 fs-4"></i>
                            <div>
                                <strong>Order Currency:</strong> Bangladeshi Taka (BDT) ৳ | 
                                <strong>Cash Payment:</strong> Cash on Delivery (COD) | 
                                <strong>Other Payments:</strong> Remain PENDING until confirmation
                            </div>
                        </div>

                        <form action="{{ route('member.orders.store') }}" method="POST" id="createOrderForm" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Hidden fields -->
                            <input type="hidden" name="currency" value="BDT">
                            
                            <!-- Vendor Selection - Hidden for members -->
                            <div class="row mb-3" style="display: none;">
                                <div class="col-md-6">
                                    <label for="vendor_id" class="form-label">Preferred Vendor (Optional)</label>
                                    <select class="form-select" name="vendor_id" id="vendor_id">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }} @if($vendor->shop_name)({{ $vendor->shop_name }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Choose a vendor to filter products or leave empty for all vendors</small>
                                </div>
                                <div class="col-md-6">
                                    <!-- Payment Method moved to its own row -->
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" name="payment_method" id="payment_method" required>>
                                        <option value="">Select Payment Method</option>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method->code }}" 
                                                    data-requires-verification="{{ $method->requires_verification ? 'true' : 'false' }}"
                                                    data-min-amount="{{ $method->min_amount }}"
                                                    data-max-amount="{{ $method->max_amount }}"
                                                    data-fee-percentage="{{ $method->fee_percentage }}"
                                                    data-fee-fixed="{{ $method->fee_fixed }}"
                                                    {{ old('payment_method') == $method->code ? 'selected' : '' }}>
                                                {{ $method->name }}
                                                @if($method->fee_percentage > 0 || $method->fee_fixed > 0)
                                                    (Fee: 
                                                    @if($method->fee_fixed > 0)৳{{ number_format($method->fee_fixed, 2) }}@endif
                                                    @if($method->fee_percentage > 0 && $method->fee_fixed > 0) + @endif
                                                    @if($method->fee_percentage > 0){{ $method->fee_percentage }}%@endif
                                                    )
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" id="payment-method-info">Select a payment method to see details</small>
                                </div>
                            </div>

                            <!-- Dynamic Payment Fields -->
                            <div id="payment-fields-container" style="display: none;">
                                <div class="card border-primary mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fe fe-credit-card me-2"></i>Payment Information</h6>
                                    </div>
                                    <div class="card-body" id="payment-fields-content">
                                        <!-- Dynamic payment fields will be loaded here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Charge Information -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="shipping_amount" class="form-label">Delivery Charge (BDT)</label>
                                    <input type="number" class="form-control" name="shipping_amount" id="shipping_amount" step="0.01" min="0" value="{{ old('shipping_amount', 0) }}" readonly>
                                    <small class="text-muted" id="delivery-info">Automatically calculated based on district selection</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="delivery-info-card p-3 bg-light rounded">
                                        <h6 class="mb-2"><i class="fe fe-truck me-2"></i>Delivery Information</h6>
                                        <div id="delivery-details">
                                            <small class="text-muted">Select district to see delivery charge and estimated time</small>
                                        </div>
                                    </div>
                                </div>
                            </div>                            <!-- Order Items with Product Search -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">Order Items <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                                            <i class="fe fe-plus me-1"></i>Add Item
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#productSearchModal">
                                            <i class="fe fe-search me-1"></i>Advanced Search
                                        </button>
                                    </div>
                                </div>
                                <div id="orderItems">
                                    <div class="order-item-row border rounded p-3 mb-2">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Product</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control product-search" placeholder="Search product by name, SKU..." data-index="0">
                                                    <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                        <i class="fe fe-search text-muted"></i>
                                                    </div>
                                                    <div class="product-dropdown dropdown-menu w-100" style="max-height: 300px; overflow-y: auto; display: none;">
                                                        <!-- Product search results will appear here -->
                                                    </div>
                                                </div>
                                                <input type="hidden" name="items[0][product_id]" class="product-id-input" required>
                                                <div class="selected-product mt-2" style="display: none;">
                                                    <div class="card border-success">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="product-image-placeholder me-2" style="width: 40px; height: 40px;">
                                                                        <img class="product-image rounded" src="" alt="Product" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
                                                                            <i class="fe fe-package text-muted"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="product-name"></strong><br>
                                                                        <small class="text-muted product-details"></small>
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-danger clear-product-btn">
                                                                    <i class="fe fe-x"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required>
                                                <small class="text-muted stock-info"></small>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Price</label>
                                                <input type="number" class="form-control price-input" name="items[0][price]" step="0.01" min="0" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Total</label>
                                                <input type="text" class="form-control total-display" readonly>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item-btn">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="mb-4 shipping-billing-section">
                                <h5 class="mb-3">
                                    <i class="fe fe-map-pin me-2"></i>Shipping Address <span class="text-danger">*</span>
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="shipping_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping_address[first_name]" id="shipping_first_name" value="{{ old('shipping_address.first_name', auth()->user()->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="shipping_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping_address[last_name]" id="shipping_last_name" value="{{ old('shipping_address.last_name') }}" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="shipping_address_line_1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping_address[address_line_1]" id="shipping_address_line_1" value="{{ old('shipping_address.address_line_1', auth()->user()->address) }}" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="shipping_address[city]" id="shipping_city" value="{{ old('shipping_address.city', auth()->user()->city) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="shipping_district" class="form-label">District <span class="text-danger">*</span></label>
                                        <select class="form-select" name="shipping_address[district]" id="shipping_district" required>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="shipping_upazila" class="form-label">Upazila/Thana</label>
                                        <select class="form-select" name="shipping_address[upazila]" id="shipping_upazila">
                                            <option value="">Select Upazila</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="shipping_ward" class="form-label">Ward/Union</label>
                                        <select class="form-select" name="shipping_address[ward]" id="shipping_ward">
                                            <option value="">Select Ward/Union</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="shipping_country" class="form-label">Country <span class="text-danger">*</span></label>
                                        <select class="form-select" name="shipping_address[country]" id="shipping_country" required>
                                            <option value="">Select Country</option>
                                            <option value="BD" {{ old('shipping_address.country', 'BD') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                            <option value="IN" {{ old('shipping_address.country') == 'IN' ? 'selected' : '' }}>India</option>
                                            <option value="PK" {{ old('shipping_address.country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="shipping_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" name="shipping_address[phone]" id="shipping_phone" value="{{ old('shipping_address.phone', auth()->user()->phone) }}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" name="shipping_address[postal_code]" id="shipping_postal_code" value="{{ old('shipping_address.postal_code') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Order Totals -->
                            <div class="mb-4 calculations-section">
                                <h5 class="mb-3">
                                    <i class="fe fe-calculator me-2"></i>Order Summary
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="tax_amount" class="form-label">
                                            Tax Amount (BDT)
                                            <span class="badge bg-success ms-1">Tax-Free!</span>
                                        </label>
                                        <input type="number" class="form-control" name="tax_amount" id="tax_amount" step="0.01" min="0" value="0" readonly>
                                        <small class="text-success">Tax-free shopping in Bangladesh!</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Subtotal (BDT)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="text" class="form-control" id="subtotalAmount" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Amount (BDT)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="text" class="form-control" id="totalAmount" readonly>
                                        </div>
                                        <small class="text-muted">Final amount to pay</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Add any special instructions for this order...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-end">
                                <a href="{{ route('member.orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-shopping-cart me-1"></i>Create Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Product Search Modal -->
    <div class="modal fade" id="productSearchModal" tabindex="-1" aria-labelledby="productSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSearchModalLabel">
                        <i class="fe fe-search me-2"></i>Advanced Product Search
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="modal_product_search" class="form-label">Search Products</label>
                            <input type="text" class="form-control" id="modal_product_search" placeholder="Search by name, SKU, description...">
                        </div>
                        <div class="col-md-4">
                            <label for="modal_category_filter" class="form-label">Category</label>
                            <select class="form-select" id="modal_category_filter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="display: none;">
                            <label for="modal_vendor_filter" class="form-label">Vendor</label>
                            <select class="form-select" id="modal_vendor_filter">
                                <option value="">All Vendors</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="modal_min_price" class="form-label">Min Price (৳)</label>
                            <input type="number" class="form-control" id="modal_min_price" placeholder="0" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label for="modal_max_price" class="form-label">Max Price (৳)</label>
                            <input type="number" class="form-control" id="modal_max_price" placeholder="10000" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" id="modal_search_btn">
                                <i class="fe fe-search me-1"></i>Search Products
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-secondary w-100" id="modal_clear_filters">
                                <i class="fe fe-x me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                    
                    <!-- Search Results -->
                    <div class="row">
                        <div class="col-12">
                            <div id="modal_product_results" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                <div class="text-center text-muted py-4">
                                    <i class="fe fe-search me-2"></i>
                                    Search for products above to see results
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.order-item-row {
    background-color: #f8f9fa;
}

.selected-product {
    margin-top: 10px;
}

.product-dropdown {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    z-index: 1000;
}

.product-dropdown .dropdown-item {
    border-bottom: 1px solid #f1f1f1;
    padding: 12px;
}

.product-dropdown .dropdown-item:last-child {
    border-bottom: none;
}

.product-dropdown .dropdown-item:hover {
    background-color: #f8f9fa;
}

.calculations-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #dee2e6;
}

.shipping-billing-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 1px solid #dee2e6;
}

#modal_product_results .product-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

#modal_product_results .product-item:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

#modal_product_results .product-item.selected {
    border-color: #28a745;
    background-color: #d4edda;
}

/* Smooth transition for product selection */
.position-relative {
    transition: all 0.3s ease;
}

.selected-product {
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize reorder data if available
    @if(isset($reorderData))
    const reorderData = {!! json_encode($reorderData) !!};
    console.log('Reorder data loaded:', reorderData);
    @endif
    
    // Load Bangladesh location data
    let bangladeshData = [];
    let deliveryRates = {
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
    };
    
    // Load Bangladesh location data from JSON file
    $.getJSON('/data/bangladesh-locations.json', function(data) {
        bangladeshData = data;
        
        // Populate district dropdown
        const districtSelect = $('#shipping_district');
        districtSelect.empty().append('<option value="">Select District</option>');
        
        data.forEach(function(division) {
            districtSelect.append(`<option value="${division.name}">${division.name}</option>`);
        });
        
        console.log('Bangladesh location data loaded:', data.length + ' divisions');
        
        // Populate reorder data if available
        @if(isset($reorderData))
        populateReorderData();
        @endif
    }).fail(function() {
        console.error('Failed to load Bangladesh location data');
        // Fallback to basic districts
        const basicDistricts = ['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Barisal', 'Sylhet', 'Rangpur', 'Mymensingh'];
        const districtSelect = $('#shipping_district');
        basicDistricts.forEach(function(district) {
            districtSelect.append(`<option value="${district}">${district}</option>`);
        });
    });
    
    // Handle district change
    $('#shipping_district').on('change', function() {
        const selectedDistrict = $(this).val();
        const upazillaSelect = $('#shipping_upazila');
        const wardSelect = $('#shipping_ward');
        
        // Reset dependent dropdowns
        upazillaSelect.empty().append('<option value="">Select Upazila</option>');
        wardSelect.empty().append('<option value="">Select Ward/Union</option>');
        
        if (selectedDistrict && bangladeshData.length > 0) {
            // Find the division containing this district
            const division = bangladeshData.find(div => div.name === selectedDistrict);
            
            if (division && division.upazilas) {
                division.upazilas.forEach(function(upazila) {
                    upazillaSelect.append(`<option value="${upazila.name}">${upazila.name}</option>`);
                });
            }
        }
        
        // Calculate delivery charges
        calculateDeliveryCharge();
    });
    
    // Handle upazila change
    $('#shipping_upazila').on('change', function() {
        const selectedDistrict = $('#shipping_district').val();
        const selectedUpazila = $(this).val();
        const wardSelect = $('#shipping_ward');
        
        // Reset ward dropdown
        wardSelect.empty().append('<option value="">Select Ward/Union</option>');
        
        if (selectedDistrict && selectedUpazila && bangladeshData.length > 0) {
            // Find the division and upazila
            const division = bangladeshData.find(div => div.name === selectedDistrict);
            
            if (division && division.upazilas) {
                const upazila = division.upazilas.find(uz => uz.name === selectedUpazila);
                
                if (upazila && upazila.unions) {
                    upazila.unions.forEach(function(union) {
                        wardSelect.append(`<option value="${union}">${union}</option>`);
                    });
                }
            }
        }
        
        // Recalculate delivery charges when upazila changes
        calculateDeliveryCharge();
    });
    
    // Handle ward change
    $('#shipping_ward').on('change', function() {
        // Recalculate delivery charges when ward changes
        calculateDeliveryCharge();
    });
    
    function calculateDeliveryCharge() {
        const district = $('#shipping_district').val();
        const upazila = $('#shipping_upazila').val();
        const ward = $('#shipping_ward').val();
        
        if (!district) {
            $('#shipping_amount').val('0.00');
            $('#delivery-info').text('Select district to see delivery charge');
            $('#delivery-details').html('<small class="text-muted">Select district to see delivery charge and estimated time</small>');
            calculateTotals();
            return;
        }
        
        // Show loading state
        $('#delivery-info').text('Calculating delivery charge...');
        $('#delivery-details').html('<small class="text-muted"><i class="fe fe-loader me-1"></i>Loading delivery information...</small>');
        
        // Make AJAX request to get dynamic delivery charge
        // Use client-side delivery charges instead of AJAX to avoid authentication issues
        const deliveryCharges = @json($deliveryCharges);
        
        function findDeliveryCharge(district, upazila, ward) {
            // Try exact match with ward
            if (district && upazila && ward) {
                const wardKey = `${district}|${upazila}|${ward}`;
                if (deliveryCharges[wardKey]) {
                    return deliveryCharges[wardKey];
                }
            }
            
            // Try match with upazila only
            if (district && upazila) {
                const upazilaKey = `${district}|${upazila}|`;
                if (deliveryCharges[upazilaKey]) {
                    return deliveryCharges[upazilaKey];
                }
            }
            
            // Try match with district only
            if (district) {
                const districtKey = `${district}||`;
                if (deliveryCharges[districtKey]) {
                    return deliveryCharges[districtKey];
                }
            }
            
            // Return default if no match found
            return {
                charge: 100.00,
                estimated_delivery_time: '3-5 business days',
                district: district,
                upazila: upazila,
                ward: ward
            };
        }
        
        const deliveryCharge = findDeliveryCharge(district, upazila, ward);
        
        console.log('Found delivery charge:', deliveryCharge);
        
        // Parse charge as float to ensure it's a number
        const chargeAmount = parseFloat(deliveryCharge.charge);
        
        // Update shipping amount
        $('#shipping_amount').val(chargeAmount.toFixed(2));
        
        // Update delivery information
        const formattedCharge = `৳${chargeAmount.toFixed(2)}`;
        $('#delivery-info').text(`${formattedCharge} for ${district}` + (upazila ? ` > ${upazila}` : '') + (ward ? ` > ${ward}` : ''));
        
        let locationDetails = district;
        if (upazila) locationDetails += ` > ${upazila}`;
        if (ward) locationDetails += ` > ${ward}`;
        
        $('#delivery-details').html(`
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small><strong>Location:</strong></small>
                <small class="text-muted">${locationDetails}</small>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small><strong>Charge:</strong></small>
                <small class="text-primary"><strong>${formattedCharge}</strong></small>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <small><strong>Estimated Time:</strong></small>
                <small class="text-success">${deliveryCharge.estimated_delivery_time}</small>
            </div>
        `);
        
        // Recalculate totals
        calculateTotals();
        
        console.log('Dynamic delivery charge loaded successfully:', deliveryCharge);
    }

    @if(isset($reorderData))
    // Populate form with reorder data
    function populateReorderData() {
        if (typeof reorderData === 'undefined') return;
        
        // Clear existing items first
        $('#orderItems').empty();
        itemIndex = 0;
        
        // Populate shipping address
        if (reorderData.shipping_address) {
            const addr = reorderData.shipping_address;
            $('#shipping_first_name').val(addr.first_name || '');
            $('#shipping_last_name').val(addr.last_name || '');
            $('#shipping_address_line_1').val(addr.address_line_1 || '');
            $('#shipping_city').val(addr.city || '');
            $('#shipping_country').val(addr.country || 'BD');
            $('#shipping_postal_code').val(addr.postal_code || '');
            
            // Set district and trigger change to populate delivery charge
            if (addr.district) {
                $('#shipping_district').val(addr.district).trigger('change');
            }
            
            // Set upazila and ward after a short delay to let district populate
            setTimeout(function() {
                if (addr.upazila) {
                    $('#shipping_upazila').val(addr.upazila);
                }
                if (addr.ward) {
                    $('#shipping_ward').val(addr.ward);
                }
            }, 500);
        }
        
        // Populate vendor selection
        if (reorderData.vendor_id) {
            $('#vendor_id').val(reorderData.vendor_id);
        }
        
        // Populate payment method
        if (reorderData.payment_method) {
            $('#payment_method').val(reorderData.payment_method).trigger('change');
        }
        
        // Populate order items
        reorderData.items.forEach(function(item, index) {
            const newRow = createItemRow(itemIndex);
            $('#orderItems').append(newRow);
            
            const $currentRow = $('#orderItems .order-item-row').last();
            
            // Hide the search input and show selected product
            $currentRow.find('.product-search').hide();
            $currentRow.find('.selected-product').show();
            
            // Set product data
            $currentRow.find('.product-id-input').val(item.product_id);
            $currentRow.find('.product-name').text(item.product_name);
            $currentRow.find('.product-details').text('SKU: ' + (item.product_sku || 'N/A'));
            
            // Handle product image
            if (item.product_image) {
                let imageSrc = item.product_image;
                
                // If it's not a full URL, add the storage prefix
                if (!imageSrc.startsWith('http')) {
                    imageSrc = '/storage/' + imageSrc;
                }
                
                $currentRow.find('.product-image').attr('src', imageSrc)
                    .on('error', function() {
                        // Fallback to default image on error
                        $(this).attr('src', '/assets/img/product/1.png');
                    }).show();
                $currentRow.find('.product-image-placeholder .d-flex').hide();
            } else {
                $currentRow.find('.product-image').hide();
                $currentRow.find('.product-image-placeholder .d-flex').show();
            }
            
            // Set quantity and price
            $currentRow.find('input[name^="items"][name$="[quantity]"]').val(item.quantity);
            $currentRow.find('input[name^="items"][name$="[price]"]').val(item.price);
            
            // Update total display
            $currentRow.find('.total-display').val('৳' + (item.quantity * item.price).toFixed(2));
            
            // Show availability warning for unavailable products
            if (!item.available) {
                $currentRow.find('.stock-info').html('<small class="text-warning"><i class="fe fe-alert-triangle me-1"></i>Product may no longer be available</small>');
                $currentRow.addClass('border-warning');
            }
            
            itemIndex++;
        });
        
        // Calculate totals after populating
        setTimeout(function() {
            calculateTotals();
        }, 1000);
        
        console.log('Reorder data populated successfully');
    }
    @endif

    let itemIndex = 1;

    // Add new item row
    $('#addItemBtn').click(function() {
        const newRow = createItemRow(itemIndex);
        $('#orderItems').append(newRow);
        itemIndex++;
    });

    // Remove item row
    $(document).on('click', '.remove-item-btn', function() {
        if ($('.order-item-row').length > 1) {
            $(this).closest('.order-item-row').remove();
            calculateTotals();
        } else {
            Swal.fire('Error', 'At least one item is required', 'error');
        }
    });

    // Product search functionality
    $(document).on('input', '.product-search', function() {
        const searchTerm = $(this).val();
        const row = $(this).closest('.order-item-row');
        const dropdown = row.find('.product-dropdown');
        const vendorId = $('#vendor_id').val();

        if (searchTerm.length >= 2) {
            searchProducts(searchTerm, dropdown, row, vendorId);
        } else {
            dropdown.hide();
        }
    });

    // Product selection
    $(document).on('click', '.product-option', function() {
        const productData = $(this).data();
        const row = $(this).closest('.order-item-row');
        
        selectProduct(row, productData);
        row.find('.product-dropdown').hide();
    });

    // Clear product selection
    $(document).on('click', '.clear-product-btn', function() {
        const row = $(this).closest('.order-item-row');
        clearProductSelection(row);
    });

    // Calculate totals on quantity/price change
    $(document).on('input', '.quantity-input, .price-input', function() {
        calculateTotals();
    });

    // Payment method info and dynamic fields
    $('#payment_method').change(function() {
        const method = $(this).val();
        const $container = $('#payment-fields-container');
        const $content = $('#payment-fields-content');
        
        if (!method) {
            $container.hide();
            $('#payment-method-info').text('Select a payment method to see details');
            return;
        }

        if (method === 'cash') {
            // For cash (COD), we want to show balance info and advance payment requirement
            // so we'll continue to load details but handle it specially in the response
        }

        if (method === 'app_balance') {
            // For app_balance, we want to show balance info, so we'll continue to load details
            // but handle it specially in the response
        }

        // Load payment method details
        const detailsUrl = '{{ route("member.orders.payment.method.details", ":code") }}'.replace(':code', method);
        $.get(detailsUrl)
            .done(function(data) {
                let fieldsHtml = '';
                let infoText = data.description || '';
                
                // Add instructions if available
                if (data.instructions) {
                    fieldsHtml += `
                        <div class="alert alert-info mb-3">
                            <i class="fe fe-info me-2"></i>
                            <strong>Instructions:</strong><br>
                            ${data.instructions}
                        </div>
                    `;
                }

                // Account details for reference
                if (data.account_number) {
                    fieldsHtml += `
                        <div class="alert alert-warning mb-3">
                            <h6 class="alert-heading mb-2"><i class="fe fe-credit-card me-2"></i>Payment Details</h6>
                    `;
                    
                    if (data.bank_name) {
                        fieldsHtml += `<p class="mb-1"><strong>Bank:</strong> ${data.bank_name}</p>`;
                        if (data.branch_name) fieldsHtml += `<p class="mb-1"><strong>Branch:</strong> ${data.branch_name}</p>`;
                        if (data.routing_number) fieldsHtml += `<p class="mb-1"><strong>Routing:</strong> ${data.routing_number}</p>`;
                    }
                    
                    fieldsHtml += `
                            <p class="mb-1"><strong>Account Number:</strong> <code class="text-primary">${data.account_number}</code></p>
                            <p class="mb-0"><strong>Account Name:</strong> ${data.account_name || 'N/A'}</p>
                        </div>
                    `;
                }

                // Dynamic form fields based on extra_fields
                if (data.extra_fields) {
                    fieldsHtml += '<div class="row">';

                    if (data.extra_fields.sender_number === 'required') {
                        fieldsHtml += `
                            <div class="col-md-6 mb-3">
                                <label for="sender_number" class="form-label">Your ${data.type === 'mobile' ? 'Mobile' : 'Phone'} Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sender_number" id="sender_number" 
                                       placeholder="Enter your ${data.type === 'mobile' ? 'mobile' : 'phone'} number" 
                                       value="{{ old('sender_number') }}" required>
                                <small class="text-muted">The number you're sending payment from</small>
                            </div>
                        `;
                    }

                    if (data.extra_fields.sender_bank === 'required') {
                        fieldsHtml += `
                            <div class="col-md-6 mb-3">
                                <label for="sender_bank" class="form-label">Your Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sender_bank" id="sender_bank" 
                                       placeholder="Enter your bank name" 
                                       value="{{ old('sender_bank') }}" required>
                            </div>
                        `;
                    }

                    if (data.extra_fields.sender_account === 'required') {
                        fieldsHtml += `
                            <div class="col-md-6 mb-3">
                                <label for="sender_account" class="form-label">Your Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sender_account" id="sender_account" 
                                       placeholder="Enter your account number" 
                                       value="{{ old('sender_account') }}" required>
                            </div>
                        `;
                    }

                    if (data.extra_fields.transaction_id === 'required') {
                        fieldsHtml += `
                            <div class="col-md-6 mb-3">
                                <label for="transaction_id" class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="transaction_id" id="transaction_id" 
                                       placeholder="Enter transaction/reference ID" 
                                       value="{{ old('transaction_id') }}" required>
                                <small class="text-muted">Transaction ID from your payment</small>
                            </div>
                        `;
                    }

                    if (data.extra_fields.payment_proof === 'required') {
                        fieldsHtml += `
                            <div class="col-md-12 mb-3">
                                <label for="payment_proof" class="form-label">Payment Proof <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="payment_proof" id="payment_proof" 
                                       accept="image/*" required>
                                <small class="text-muted">Upload screenshot or photo of payment confirmation (Max: 5MB)</small>
                            </div>
                        `;
                    }

                    // Payment notes (optional)
                    fieldsHtml += `
                        <div class="col-md-12 mb-3">
                            <label for="payment_notes" class="form-label">Payment Notes</label>
                            <textarea class="form-control" name="payment_notes" id="payment_notes" rows="2" 
                                      placeholder="Any additional notes about your payment (optional)">{{ old('payment_notes') }}</textarea>
                        </div>
                    `;

                    fieldsHtml += '</div>';
                }

                // Add fee information
                if (data.fee_percentage > 0 || data.fee_fixed > 0) {
                    infoText += ` Fee: `;
                    if (data.fee_fixed > 0) infoText += `৳${parseFloat(data.fee_fixed).toFixed(2)}`;
                    if (data.fee_percentage > 0 && data.fee_fixed > 0) infoText += ` + `;
                    if (data.fee_percentage > 0) infoText += `${data.fee_percentage}%`;
                }

                // Add balance information for app_balance method
                if (method === 'app_balance' && data.user_balance) {
                    fieldsHtml += `
                        <div class="alert alert-success mb-3">
                            <h6 class="alert-heading mb-2"><i class="fe fe-credit-card me-2"></i>Your Available Balance</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Deposit Wallet:</strong><br>
                                    <span class="text-primary fs-5">${data.user_balance.formatted.deposit_wallet}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Income Wallet:</strong><br>
                                    <span class="text-success fs-5">${data.user_balance.formatted.income_wallet}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Available:</strong><br>
                                    <span class="text-info fs-4 fw-bold">${data.user_balance.formatted.total_available}</span>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fe fe-info-circle me-1"></i>
                                Payment will be deducted from your deposit wallet first, then from income wallet if needed.
                            </small>
                        </div>
                    `;
                    
                    // Update info text to show instant payment
                    infoText = 'Instant payment using your app balance. Order will be automatically marked as PAID if you have sufficient funds.';
                }

                // Add balance information for cash on delivery method
                if (method === 'cash' && data.user_balance) {
                    const hasEnoughBalance = data.has_sufficient_balance || false;
                    const balanceAlertClass = hasEnoughBalance ? 'alert-warning' : 'alert-danger';
                    const balanceIcon = hasEnoughBalance ? 'fe-check-circle' : 'fe-alert-circle';
                    const balanceText = hasEnoughBalance ? 'You have sufficient balance for the security deposit' : 'Insufficient balance for security deposit';
                    
                    fieldsHtml += `
                        <div class="alert ${balanceAlertClass} mb-3">
                            <h6 class="alert-heading mb-2"><i class="fe fe-truck me-2"></i>Cash on Delivery - Security Deposit Required</h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Advance Payment Required:</strong><br>
                                    <span class="text-danger fs-5 fw-bold">${data.formatted.advance_payment}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Your Available Balance:</strong><br>
                                    <span class="text-info fs-5">${data.user_balance.formatted.total_available}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Status:</strong><br>
                                    <span class="${hasEnoughBalance ? 'text-success' : 'text-danger'} fs-6">
                                        <i class="fe ${balanceIcon} me-1"></i>${balanceText}
                                    </span>
                                </div>
                            </div>
                            <div class="border-top pt-2">
                                <small class="text-muted d-block">
                                    <i class="fe fe-info-circle me-1"></i>
                                    <strong>How it works:</strong> ৳200 will be deducted from your wallet as security deposit when you place the order. 
                                    The remaining amount will be collected when the product is delivered to you.
                                </small>
                                <small class="text-muted d-block mt-1">
                                    <i class="fe fe-shield me-1"></i>
                                    The security deposit will be refunded to your wallet once the order is successfully delivered and payment is collected.
                                </small>
                            </div>
                        </div>
                    `;
                    
                    // Update info text for cash on delivery
                    infoText = hasEnoughBalance 
                        ? 'Cash on Delivery with ৳200 security deposit. Order will remain PENDING until delivered and payment collected.'
                        : 'Insufficient balance for Cash on Delivery security deposit. Please add funds to your wallet or choose a different payment method.';
                }

                $content.html(fieldsHtml);
                $container.show();
                $('#payment-method-info').html(`<span class="text-info"><i class="fe fe-info-circle me-1"></i>${infoText}</span>`);
            })
            .fail(function() {
                $('#payment-method-info').html('<span class="text-danger"><i class="fe fe-alert-circle me-1"></i>Error loading payment method details</span>');
                $container.hide();
            });
    });

    // Advanced product search modal
    $('#modal_product_search').on('input', function() {
        const searchTerm = $(this).val();
        if (searchTerm.length >= 2) {
            performModalSearch();
        } else {
            $('#modal_product_results').html('<div class="text-center text-muted py-4"><i class="fe fe-search me-2"></i>Search for products above to see results</div>');
        }
    });

    $('#modal_category_filter, #modal_vendor_filter').change(function() {
        performModalSearch();
    });

    $('#modal_min_price, #modal_max_price').on('input', function() {
        clearTimeout($(this).data('timeout'));
        $(this).data('timeout', setTimeout(function() {
            performModalSearch();
        }, 500));
    });

    $('#modal_search_btn').click(function() {
        performModalSearch();
    });

    $('#modal_clear_filters').click(function() {
        $('#modal_product_search').val('');
        $('#modal_category_filter').val('');
        $('#modal_vendor_filter').val('');
        $('#modal_min_price').val('');
        $('#modal_max_price').val('');
        $('#modal_product_results').html('<div class="text-center text-muted py-4"><i class="fe fe-search me-2"></i>Search for products above to see results</div>');
    });

    function performModalSearch() {
        const searchTerm = $('#modal_product_search').val();
        const categoryId = $('#modal_category_filter').val();
        const vendorId = $('#modal_vendor_filter').val();
        const minPrice = $('#modal_min_price').val();
        const maxPrice = $('#modal_max_price').val();

        // Show loading
        $('#modal_product_results').html('<div class="text-center py-4"><i class="fe fe-loader me-2"></i>Searching products...</div>');

        searchProductsModal(searchTerm, categoryId, vendorId, minPrice, maxPrice);
    }

    function createItemRow(index) {
        return `
            <div class="order-item-row border rounded p-3 mb-2">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <div class="position-relative">
                            <input type="text" class="form-control product-search" placeholder="Search product by name, SKU..." data-index="${index}">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                <i class="fe fe-search text-muted"></i>
                            </div>
                            <div class="product-dropdown dropdown-menu w-100" style="max-height: 300px; overflow-y: auto; display: none;"></div>
                        </div>
                        <input type="hidden" name="items[${index}][product_id]" class="product-id-input" required>
                        <div class="selected-product mt-2" style="display: none;">
                            <div class="card border-success">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="product-image-placeholder me-2" style="width: 40px; height: 40px;">
                                                <img class="product-image rounded" src="" alt="Product" style="width: 100%; height: 100%; object-fit: cover; display: none;" 
                                                     onerror="this.src='/assets/img/product/1.png';">
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
                                                    <i class="fe fe-package text-muted"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <strong class="product-name"></strong><br>
                                                <small class="text-muted product-details"></small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger clear-product-btn">
                                            <i class="fe fe-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control quantity-input" name="items[${index}][quantity]" min="1" value="1" required>
                        <small class="text-muted stock-info"></small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control price-input" name="items[${index}][price]" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control total-display" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item-btn">
                            <i class="fe fe-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function searchProducts(searchTerm, dropdown, row, vendorId = '') {
        $.ajax({
            url: '{{ route("member.orders.search.products") }}',
            method: 'GET',
            data: { 
                q: searchTerm,
                vendor_id: vendorId
            },
            success: function(response) {
                let html = '';
                
                if (response.products.length > 0) {
                    response.products.forEach(function(product) {
                        html += `
                            <div class="dropdown-item product-option" 
                                 data-id="${product.id}"
                                 data-name="${product.name}"
                                 data-sku="${product.sku}"
                                 data-price="${product.sale_price || product.price}"
                                 data-stock="${product.stock_quantity}"
                                 data-vendor="${product.vendor_name}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${product.name}</strong><br>
                                        <small class="text-muted">SKU: ${product.sku} | Vendor: ${product.vendor_name}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">৳${parseFloat(product.sale_price || product.price).toFixed(2)}</span><br>
                                        <small class="text-muted">Stock: ${product.stock_quantity}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="dropdown-item text-muted">No products found</div>';
                }
                
                dropdown.html(html).show();
            },
            error: function() {
                dropdown.html('<div class="dropdown-item text-danger">Error searching products</div>').show();
            }
        });
    }

    function selectProduct(row, productData) {
        row.find('.product-id-input').val(productData.id);
        row.find('.product-search').val(productData.name);
        row.find('.price-input').val(productData.price);
        
        // Hide the search input and show selected product
        row.find('.product-search').parent().hide();
        row.find('.selected-product').show();
        row.find('.product-name').text(productData.name);
        row.find('.product-details').text(`SKU: ${productData.sku} | Vendor: ${productData.vendor} | Stock: ${productData.stock}`);
        row.find('.stock-info').text(`Available: ${productData.stock}`);
        
        // Handle product image
        if (productData.image || (productData.images && productData.images.length > 0)) {
            let imageSrc;
            
            // First try images array (similar to home page logic)
            if (productData.images && productData.images.length > 0) {
                const firstImage = productData.images[0];
                if (typeof firstImage === 'string') {
                    imageSrc = firstImage;
                } else if (firstImage.url) {
                    imageSrc = firstImage.url;
                } else if (firstImage.path) {
                    imageSrc = firstImage.path;
                }
            } else if (productData.image) {
                imageSrc = productData.image;
            }
            
            if (imageSrc) {
                let finalImageSrc;
                if (imageSrc.startsWith('http')) {
                    finalImageSrc = imageSrc;
                } else {
                    finalImageSrc = '/storage/' + imageSrc;
                }
                
                row.find('.product-image').attr('src', finalImageSrc)
                    .on('error', function() {
                        $(this).attr('src', '/assets/img/product/1.png');
                    }).show();
                row.find('.product-image-placeholder .d-flex').hide();
            } else {
                row.find('.product-image').hide();
                row.find('.product-image-placeholder .d-flex').show();
            }
        } else {
            row.find('.product-image').hide();
            row.find('.product-image-placeholder .d-flex').show();
        }
        
        calculateTotals();
    }

    function clearProductSelection(row) {
        row.find('.product-id-input').val('');
        row.find('.product-search').val('');
        row.find('.price-input').val('');
        
        // Show the search input and hide selected product
        row.find('.product-search').parent().show();
        row.find('.selected-product').hide();
        row.find('.stock-info').text('');
        
        // Reset product image
        row.find('.product-image').hide();
        row.find('.product-image-placeholder .d-flex').show();
        
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        
        $('.order-item-row').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const price = parseFloat($(this).find('.price-input').val()) || 0;
            const total = quantity * price;
            
            $(this).find('.total-display').val(total.toFixed(2));
            subtotal += total;
        });
        
        const shipping = parseFloat($('#shipping_amount').val()) || 0;
        const tax = parseFloat($('#tax_amount').val()) || 0;
        const grandTotal = subtotal + shipping + tax;
        
        $('#subtotalAmount').val(subtotal.toFixed(2));
        $('#totalAmount').val(grandTotal.toFixed(2));
    }

    function searchProductsModal(searchTerm, categoryId = '', vendorId = '', minPrice = '', maxPrice = '') {
        $.ajax({
            url: '{{ route("member.orders.search.products") }}',
            method: 'GET',
            data: { 
                q: searchTerm,
                category_id: categoryId,
                vendor_id: vendorId,
                min_price: minPrice,
                max_price: maxPrice
            },
            success: function(response) {
                let html = '';
                
                if (response.products.length > 0) {
                    response.products.forEach(function(product) {
                        html += `
                            <div class="product-item" data-product='${JSON.stringify(product)}'>
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">${product.name}</h6>
                                        <p class="text-muted mb-1">SKU: ${product.sku} | Category: ${product.category_name || 'N/A'} | Vendor: ${product.vendor_name}</p>
                                        <small class="text-success">Stock: ${product.stock_quantity} available</small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="h5 text-primary">৳${parseFloat(product.final_price || product.sale_price || product.price).toFixed(2)}</span><br>
                                        <button class="btn btn-sm btn-outline-primary add-product-btn" 
                                                data-id="${product.id}"
                                                data-name="${product.name}"
                                                data-sku="${product.sku}"
                                                data-price="${product.final_price || product.sale_price || product.price}"
                                                data-stock="${product.stock_quantity}"
                                                data-vendor="${product.vendor_name}">
                                            Add to Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted py-4">No products found</div>';
                }
                
                $('#modal_product_results').html(html);
            },
            error: function() {
                $('#modal_product_results').html('<div class="text-center text-danger py-4">Error searching products</div>');
            }
        });
    }

    // Add product from modal
    $(document).on('click', '.add-product-btn', function() {
        const productData = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            sku: $(this).data('sku'),
            price: $(this).data('price'),
            stock: $(this).data('stock'),
            vendor: $(this).data('vendor')
        };

        // Find first empty row or create new one
        let targetRow = $('.order-item-row').filter(function() {
            return $(this).find('.product-id-input').val() === '';
        }).first();

        if (targetRow.length === 0) {
            $('#addItemBtn').click();
            targetRow = $('.order-item-row').last();
        }

        selectProduct(targetRow, productData);
        $('#productSearchModal').modal('hide');
    });

    // Form validation
    $('#createOrderForm').submit(function(e) {
        let hasValidItems = false;
        
        $('.order-item-row').each(function() {
            const productId = $(this).find('.product-id-input').val();
            const quantity = $(this).find('.quantity-input').val();
            const price = $(this).find('.price-input').val();
            
            if (productId && quantity && price) {
                hasValidItems = true;
            }
        });
        
        if (!hasValidItems) {
            e.preventDefault();
            Swal.fire('Error', 'Please add at least one valid product to the order', 'error');
            return false;
        }
    });
});
</script>
@endpush
