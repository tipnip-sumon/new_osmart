@extends('admin.layouts.app')

@section('title', 'Create Order')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-18 mb-0">Create New Order</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i>Back to Orders
                </a>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Order Information
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

                        <!-- Currency and Payment Info Banner -->
                        <div class="alert alert-info d-flex align-items-center mb-3">
                            <i class="ri-information-line me-2 fs-4"></i>
                            <div>
                                <strong>Order Currency:</strong> Bangladeshi Taka (BDT) ৳ | 
                                <strong>Cash Payments:</strong> Automatically marked as PAID | 
                                <strong>Other Payments:</strong> Remain PENDING until confirmation
                            </div>
                        </div>

                        <form action="{{ route('admin.orders.store') }}" method="POST" id="createOrderForm">
                            @csrf
                            
                            <!-- Hidden fields -->
                            <input type="hidden" name="currency" value="BDT">
                            
                            <!-- Customer Selection with Search -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="customer_search" class="form-label">Select User <span class="text-danger">*</span></label>
                                    <div class="position-relative" id="customer_search_container">
                                        <input type="text" class="form-control" id="customer_search" placeholder="Search users by name, username, email, or phone...">
                                        <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                            <i class="ri-search-line text-muted"></i>
                                        </div>
                                        <div id="customer_dropdown" class="dropdown-menu w-100 shadow-lg border-0" style="max-height: 400px; overflow-y: auto; display: none; border-radius: 12px; margin-top: 5px;">
                                            <!-- User search results will appear here -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="customer_id" id="customer_id" required>
                                    <div id="selected_customer" class="mt-2" style="display: none;">
                                        <div class="card border-success shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 45px; height: 45px; font-size: 18px; font-weight: bold;" 
                                                         id="customer_avatar">
                                                        <!-- Customer initial will be set by JS -->
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6 class="mb-1 text-success fw-bold" id="customer_name">
                                                                    <!-- Customer name will be set by JS -->
                                                                </h6>
                                                                <div class="small text-muted" id="customer_details">
                                                                    <!-- Customer details will be set by JS -->
                                                                </div>
                                                                <div class="mt-2">
                                                                    <span class="badge bg-success rounded-pill" id="customer_orders_badge">
                                                                        <!-- Orders count will be set by JS -->
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.clearCustomerSelection()" title="Remove Customer">
                                                                <i class="ri-close-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="vendor_id" class="form-label">Vendor (Optional)</label>
                                    <select class="form-select" name="vendor_id" id="vendor_id">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }} @if($vendor->shop_name)({{ $vendor->shop_name }})@endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Payment & Shipping Method -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" name="payment_method" id="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash (Auto-Paid)</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                        <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="rocket" {{ old('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    <small class="text-muted" id="payment-method-info">Select a payment method to see details</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="shipping_method" class="form-label">Shipping Method</label>
                                    <select class="form-select" name="shipping_method" id="shipping_method">
                                        <option value="">Select Shipping Method</option>
                                        @foreach($shippingOptions as $key => $option)
                                            <option value="{{ $key }}" 
                                                    data-rate="{{ $option['rate'] }}" 
                                                    data-delivery-time="{{ $option['delivery_time'] }}"
                                                    {{ old('shipping_method') == $key ? 'selected' : '' }}>
                                                {{ $option['label'] }} - ৳{{ number_format($option['rate'], 2) }} ({{ $option['delivery_time'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" id="shipping-description"></small>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="mb-4 shipping-billing-section">
                                <h5 class="mb-3">
                                    <i class="ri-map-pin-line me-2"></i>Address Information
                                </h5>
                                
                                <!-- Shipping Address -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card border address-card">
                                            <div class="card-header">
                                                <h6 class="card-title mb-0">
                                                    <i class="ri-truck-line me-2"></i>Shipping Address <span class="required-field">*</span>
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="shipping_first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipping_address[first_name]" id="shipping_first_name" value="{{ old('shipping_address.first_name') }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="shipping_last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipping_address[last_name]" id="shipping_last_name" value="{{ old('shipping_address.last_name') }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="shipping_company" class="form-label">Company</label>
                                                    <input type="text" class="form-control" name="shipping_address[company]" id="shipping_company" value="{{ old('shipping_address.company') }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="shipping_address_line_1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="shipping_address[address_line_1]" id="shipping_address_line_1" value="{{ old('shipping_address.address_line_1') }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="shipping_address_line_2" class="form-label">Address Line 2</label>
                                                    <input type="text" class="form-control" name="shipping_address[address_line_2]" id="shipping_address_line_2" value="{{ old('shipping_address.address_line_2') }}">
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="shipping_city" class="form-label">City <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="shipping_address[city]" id="shipping_city" value="{{ old('shipping_address.city') }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="shipping_state" class="form-label">State/Province</label>
                                                        <input type="text" class="form-control" name="shipping_address[state]" id="shipping_state" value="{{ old('shipping_address.state') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                                        <input type="text" class="form-control" name="shipping_address[postal_code]" id="shipping_postal_code" value="{{ old('shipping_address.postal_code') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="shipping_country" class="form-label">Country <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="shipping_address[country]" id="shipping_country" required>
                                                            <option value="">Select Country</option>
                                                            <option value="BD" {{ old('shipping_address.country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                                            <option value="IN" {{ old('shipping_address.country') == 'IN' ? 'selected' : '' }}>India</option>
                                                            <option value="PK" {{ old('shipping_address.country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                                            <option value="US" {{ old('shipping_address.country') == 'US' ? 'selected' : '' }}>United States</option>
                                                            <option value="GB" {{ old('shipping_address.country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                            <option value="CA" {{ old('shipping_address.country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                            <option value="AU" {{ old('shipping_address.country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="shipping_phone" class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="shipping_address[phone]" id="shipping_phone" value="{{ old('shipping_address.phone') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Billing Address -->
                                    <div class="col-lg-6">
                                        <div class="card border address-card" id="billingCard">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="card-title mb-0">
                                                    <i class="ri-file-text-line me-2"></i>Billing Address
                                                </h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="sameAsShipping" checked>
                                                    <label class="form-check-label" for="sameAsShipping">
                                                        <i class="ri-refresh-line me-1"></i>Same as shipping
                                                        <small class="text-muted d-block">Auto-fills from shipping address</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body" id="billingAddressFields">
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="billing_first_name" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" name="billing_address[first_name]" id="billing_first_name" value="{{ old('billing_address.first_name') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="billing_last_name" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" name="billing_address[last_name]" id="billing_last_name" value="{{ old('billing_address.last_name') }}">
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="billing_company" class="form-label">Company</label>
                                                    <input type="text" class="form-control" name="billing_address[company]" id="billing_company" value="{{ old('billing_address.company') }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="billing_address_line_1" class="form-label">Address Line 1</label>
                                                    <input type="text" class="form-control" name="billing_address[address_line_1]" id="billing_address_line_1" value="{{ old('billing_address.address_line_1') }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="billing_address_line_2" class="form-label">Address Line 2</label>
                                                    <input type="text" class="form-control" name="billing_address[address_line_2]" id="billing_address_line_2" value="{{ old('billing_address.address_line_2') }}">
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="billing_city" class="form-label">City</label>
                                                        <input type="text" class="form-control" name="billing_address[city]" id="billing_city" value="{{ old('billing_address.city') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="billing_state" class="form-label">State/Province</label>
                                                        <input type="text" class="form-control" name="billing_address[state]" id="billing_state" value="{{ old('billing_address.state') }}">
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <label for="billing_postal_code" class="form-label">Postal Code</label>
                                                        <input type="text" class="form-control" name="billing_address[postal_code]" id="billing_postal_code" value="{{ old('billing_address.postal_code') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="billing_country" class="form-label">Country</label>
                                                        <select class="form-select" name="billing_address[country]" id="billing_country">
                                                            <option value="">Select Country</option>
                                                            <option value="BD" {{ old('billing_address.country') == 'BD' ? 'selected' : '' }}>Bangladesh</option>
                                                            <option value="IN" {{ old('billing_address.country') == 'IN' ? 'selected' : '' }}>India</option>
                                                            <option value="PK" {{ old('billing_address.country') == 'PK' ? 'selected' : '' }}>Pakistan</option>
                                                            <option value="US" {{ old('billing_address.country') == 'US' ? 'selected' : '' }}>United States</option>
                                                            <option value="GB" {{ old('billing_address.country') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                            <option value="CA" {{ old('billing_address.country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                            <option value="AU" {{ old('billing_address.country') == 'AU' ? 'selected' : '' }}>Australia</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="billing_phone" class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="billing_address[phone]" id="billing_phone" value="{{ old('billing_address.phone') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items with Product Search -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0">Order Items <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                                            <i class="ri-add-line me-1"></i>Add Item
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#productSearchModal">
                                            <i class="ri-search-line me-1"></i>Advanced Search
                                        </button>
                                    </div>
                                </div>
                                <div id="orderItems">
                                    <div class="order-item-row border rounded p-3 mb-2">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Product</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." data-index="0">
                                                    <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                                        <i class="ri-search-line text-muted"></i>
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
                                                                <div>
                                                                    <strong class="product-name"></strong><br>
                                                                    <small class="text-muted product-details"></small>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-danger clear-product-btn">
                                                                    <i class="ri-close-line"></i>
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
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </button>
                            </div>

                            <!-- Order Totals -->
                            <div class="mb-4 calculations-section">
                                <h5 class="mb-3">
                                    <i class="ri-calculator-line me-2"></i>Order Calculations
                                </h5>
                                
                                <!-- Info Alert -->
                                <div class="alert alert-light alert-dismissible fade show mb-3" style="background-color: rgba(255,255,255,0.95); border: 1px solid #dee2e6;">
                                    <i class="ri-information-line me-2 text-primary"></i>
                                    <strong style="color: #495057;">Smart Calculations:</strong>
                                    <ul class="mb-0 mt-2" style="color: #495057;">
                                        <li>Shipping costs are automatically calculated based on your selected method</li>
                                        <li>Tax is automatically applied based on shipping location (Bangladesh is tax-free!)</li>
                                        <li>Free shipping may be available for orders over certain amounts</li>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="shipping_amount" class="form-label">Shipping Amount (BDT)</label>
                                        <input type="number" class="form-control" name="shipping_amount" id="shipping_amount" step="0.01" min="0" value="{{ old('shipping_amount', 0) }}" readonly>
                                        <small class="text-muted">Auto-calculated based on shipping method</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tax_amount" class="form-label">
                                            {{ $taxConfig['label'] }} Amount (BDT)
                                            @if($taxConfig['default_rate'] == 0)
                                                <span class="badge bg-success ms-1">Tax-Free!</span>
                                            @endif
                                        </label>
                                        <input type="number" class="form-control" name="tax_amount" id="tax_amount" step="0.01" min="0" value="{{ old('tax_amount', 0) }}" 
                                               @if($taxConfig['default_rate'] == 0) readonly @endif>
                                        @if($taxConfig['default_rate'] == 0)
                                            <small class="text-success">{{ config('tax.display.tax_free_message', 'Tax-free shopping!') }}</small>
                                        @else
                                            <small class="text-muted">Auto-calculated based on location</small>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label for="discount_amount" class="form-label">Discount Amount (BDT)</label>
                                        <input type="number" class="form-control" name="discount_amount" id="discount_amount" step="0.01" min="0" value="{{ old('discount_amount', 0) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Total Amount (BDT)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="text" class="form-control" id="totalAmount" readonly>
                                        </div>
                                        <small class="text-muted">Bangladeshi Taka (BDT)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Add any notes about this order...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-end">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Create Order
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
                        <i class="ri-search-line me-2"></i>Advanced Product Search
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="modal_product_search" class="form-label">Search Products</label>
                            <input type="text" class="form-control" id="modal_product_search" placeholder="Search by name, SKU, barcode...">
                        </div>
                        <div class="col-md-3">
                            <label for="modal_category_filter" class="form-label">Category</label>
                            <select class="form-select" id="modal_category_filter">
                                <option value="">All Categories</option>
                                <!-- Categories will be loaded dynamically -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="modal_vendor_filter" class="form-label">Vendor</label>
                            <select class="form-select" id="modal_vendor_filter">
                                <option value="">All Vendors</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="modal_stock_filter" class="form-label">Stock Status</label>
                            <select class="form-select" id="modal_stock_filter">
                                <option value="">All Stock</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Search Results -->
                    <div class="row">
                        <div class="col-12">
                            <div id="modal_product_results" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-search me-2"></i>
                                    <p>Enter search criteria to find products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="clear_modal_filters">Clear Filters</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.address-card {
    transition: all 0.3s ease;
}

.address-card.disabled {
    opacity: 0.6;
    pointer-events: none;
}

.address-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.address-card .card-header h6 {
    color: #495057;
    font-weight: 600;
}

.required-field {
    color: #dc3545;
    font-weight: bold;
}

.form-check-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.order-item-row {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.order-item-row:hover {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
}

#totalAmount {
    font-weight: bold;
    font-size: 1.1rem;
    background-color: #e7f3ff;
    border-color: #3085d6;
}

.shipping-billing-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.shipping-billing-section h5 {
    color: #495057;
    text-shadow: none;
    font-weight: 600;
}

.shipping-billing-section .form-label {
    color: #495057;
    font-weight: 500;
}

.shipping-billing-section .form-control {
    background-color: #ffffff;
    border: 1px solid #ced4da;
    color: #495057;
}

.shipping-billing-section .text-muted {
    color: #6c757d !important;
}

.calculations-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 1rem;
    margin: 1rem 0;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.calculations-section h5 {
    color: #495057;
    text-shadow: none;
    font-weight: 600;
}

.calculations-section .form-label {
    color: #495057;
    font-weight: 500;
}

.calculations-section .form-control {
    background-color: #ffffff;
    border: 1px solid #ced4da;
    color: #495057;
}

.calculations-section .form-control:focus {
    background-color: #ffffff;
    border-color: #80bdff;
    color: #495057;
}

.calculations-section .text-muted {
    color: #6c757d !important;
}

.calculations-section .text-success {
    color: #28a745 !important;
}

.calculations-section .badge {
    background-color: #28a745 !important;
    color: white !important;
}

.free-shipping-notification {
    animation: slideIn 0.5s ease;
}

.payment-status-notification {
    animation: slideIn 0.5s ease;
}

#totalAmount {
    font-weight: bold;
    font-size: 1.2rem;
    background-color: #e7f3ff;
    border-color: #3085d6;
    text-align: right;
}

.input-group-text {
    font-weight: bold;
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}

#payment-method-info {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Address copy notification animation */
.address-copy-notification {
    animation: slideIn 0.3s ease;
}

/* Billing card transition when disabled */
.address-card.disabled {
    opacity: 0.6;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

/* Enhanced checkbox styling */
#sameAsShipping {
    transform: scale(1.1);
    margin-right: 0.5rem;
}

#sameAsShipping:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* Customer and Product Search Styles */
.dropdown-menu {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.375rem;
}

.dropdown-item {
    border-bottom: 1px solid #f8f9fa;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #495057;
}

.dropdown-item.cursor-pointer {
    cursor: pointer;
}

/* Enhanced Customer Dropdown Styling */
#customer_dropdown {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    border: 1px solid #e9ecef !important;
}

#customer_dropdown .dropdown-header {
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    margin: 0;
}

#customer_dropdown .dropdown-item {
    padding: 0.75rem 1rem;
    border: none;
    transition: all 0.2s ease;
}

#customer_dropdown .dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(3px);
}

#customer_dropdown .dropdown-item:last-child {
    border-bottom: none !important;
}

/* Customer Avatar Styling */
.customer-avatar {
    width: 35px;
    height: 35px;
    font-size: 14px;
    font-weight: bold;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Selected Customer Card Enhancement */
#selected_customer .card {
    border: 2px solid #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #f1f8f2 100%);
}

#selected_customer .bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.selected-product .card,
#selected_customer .card {
    transition: all 0.2s ease;
}

.selected-product .card:hover,
#selected_customer .card:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.product-card {
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-color: #6366f1;
}

.stock-info .badge {
    font-size: 0.75rem;
}

/* Search input styling */
.position-relative input[type="text"]:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

/* Loading spinner */
.spinner-border {
    width: 1.5rem;
    height: 1.5rem;
}

/* Modal product grid */
#modal_search_results .card {
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

#modal_search_results .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

/* Badge improvements */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Search result highlighting */
.dropdown-item strong {
    color: #495057;
}

.dropdown-item .text-muted {
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dropdown-menu {
        font-size: 0.875rem;
    }
    
    .product-card .card-body {
        padding: 0.75rem;
    }
    
    #modal_search_results .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Configuration from backend
    const shippingOptions = @json($shippingOptions);
    const taxConfig = @json($taxConfig);

    // Address functionality
    const sameAsShippingCheckbox = document.getElementById('sameAsShipping');
    const billingAddressFields = document.getElementById('billingAddressFields');
    const billingCard = document.getElementById('billingCard');
    
    // Handle "Same as shipping" checkbox with enhanced functionality
    sameAsShippingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // Copy current shipping data to billing
            copyShippingToBilling();
            billingCard.classList.add('disabled');
            
            // Show notification
            showAddressCopyNotification('Billing address will be automatically copied from shipping address');
        } else {
            // Enable billing address editing
            billingCard.classList.remove('disabled');
            
            // Show notification
            showAddressCopyNotification('You can now edit billing address separately', 'info');
        }
    });

    // Copy shipping address to billing address with enhanced functionality
    function copyShippingToBilling() {
        const shippingFields = [
            'first_name', 'last_name', 'company', 'address_line_1', 'address_line_2',
            'city', 'state', 'postal_code', 'country', 'phone'
        ];

        let copiedFields = 0;
        shippingFields.forEach(field => {
            const shippingField = document.getElementById('shipping_' + field);
            const billingField = document.getElementById('billing_' + field);
            
            if (shippingField && billingField) {
                billingField.value = shippingField.value;
                if (shippingField.value) {
                    copiedFields++;
                }
                
                // Trigger change event on billing field to ensure any dependent functionality works
                billingField.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Add visual feedback when copying
        const billingCard = document.getElementById('billingCard');
        if (billingCard) {
            billingCard.style.opacity = '0.8';
            setTimeout(() => {
                billingCard.style.opacity = '1';
            }, 200);
        }
    }

    // Auto-copy when shipping fields change (enhanced for real-time copying)
    document.querySelectorAll('[id^="shipping_"]').forEach(field => {
        field.addEventListener('input', function() {
            // Always copy to billing if "Same as shipping" is checked
            if (sameAsShippingCheckbox.checked) {
                copyShippingToBilling();
            }
            
            // Recalculate shipping and tax when location changes
            if (field.id === 'shipping_country' || field.id === 'shipping_city') {
                calculateShippingAndTax();
            }
        });
        
        // Also listen for change events (for select dropdowns)
        field.addEventListener('change', function() {
            if (sameAsShippingCheckbox.checked) {
                copyShippingToBilling();
            }
            
            if (field.id === 'shipping_country' || field.id === 'shipping_city') {
                calculateShippingAndTax();
            }
        });
        
        // Listen for paste events
        field.addEventListener('paste', function() {
            // Use setTimeout to ensure the pasted value is available
            setTimeout(() => {
                if (sameAsShippingCheckbox.checked) {
                    copyShippingToBilling();
                }
            }, 10);
        });
    });

    // Initialize billing address state and ensure checkbox is always checked by default
    sameAsShippingCheckbox.checked = true;
    copyShippingToBilling();
    billingCard.classList.add('disabled');

    // Shipping method change handler
    document.getElementById('shipping_method').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const rate = selectedOption.dataset.rate || 0;
        const description = selectedOption.dataset.deliveryTime || '';
        
        // Update shipping amount field
        document.getElementById('shipping_amount').value = rate;
        
        // Update description
        const descElement = document.getElementById('shipping-description');
        if (descElement) {
            descElement.textContent = description ? `Delivery: ${description}` : '';
        }
        
        updateTotals();
    });

    // Payment method change handler
    document.getElementById('payment_method').addEventListener('change', function() {
        const selectedMethod = this.value;
        const paymentInfo = document.getElementById('payment-method-info');
        
        if (selectedMethod === 'cash') {
            paymentInfo.innerHTML = '<i class="ri-check-circle-line text-success me-1"></i><span class="text-success">Cash payment will be marked as PAID automatically</span>';
            showPaymentStatusNotification('Cash payment selected - Order will be marked as PAID upon creation');
        } else if (selectedMethod === 'bkash' || selectedMethod === 'nagad' || selectedMethod === 'rocket') {
            paymentInfo.innerHTML = '<i class="ri-smartphone-line text-info me-1"></i><span class="text-info">Mobile payment - Status will remain PENDING until payment confirmation</span>';
        } else if (selectedMethod === 'card') {
            paymentInfo.innerHTML = '<i class="ri-bank-card-line text-warning me-1"></i><span class="text-warning">Card payment - Status will remain PENDING until payment processing</span>';
        } else if (selectedMethod === 'bank_transfer') {
            paymentInfo.innerHTML = '<i class="ri-bank-line text-primary me-1"></i><span class="text-primary">Bank transfer - Status will remain PENDING until transfer confirmation</span>';
        } else {
            paymentInfo.textContent = 'Select a payment method to see details';
        }
    });

    function showPaymentStatusNotification(message) {
        // Create a temporary notification for payment status
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show mt-2';
        notification.innerHTML = `
            <i class="ri-money-dollar-circle-line me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after payment method field
        const paymentGroup = document.getElementById('payment_method').closest('.col-md-6');
        paymentGroup.insertAdjacentElement('afterend', notification);
        
        // Auto-remove after 4 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 4000);
    }
    
    function showAddressCopyNotification(message, type = 'success') {
        // Create a temporary notification for address copying
        const notification = document.createElement('div');
        const alertClass = type === 'info' ? 'alert-info' : 'alert-success';
        const iconClass = type === 'info' ? 'ri-information-line' : 'ri-check-circle-line';
        
        notification.className = `alert ${alertClass} alert-dismissible fade show mt-2 address-copy-notification`;
        notification.innerHTML = `
            <i class="${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after the billing address card
        const billingCard = document.getElementById('billingCard');
        billingCard.insertAdjacentElement('afterend', notification);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Function to calculate shipping and tax based on location
    function calculateShippingAndTax() {
        const country = document.getElementById('shipping_country').value;
        const city = document.getElementById('shipping_city').value.toLowerCase();
        
        // Auto-calculate tax based on location
        if (taxConfig.rates_by_location[country]) {
            const locationRates = taxConfig.rates_by_location[country];
            let taxRate = locationRates.default || 0;
            
            // Check for city-specific rates
            if (locationRates[city]) {
                taxRate = locationRates[city];
            }
            
            // Calculate tax amount
            const subtotal = calculateSubtotal();
            const taxAmount = (subtotal * taxRate) / 100;
            document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        }
        
        // Auto-select shipping method based on location (for Bangladesh)
        if (country === 'BD' && city) {
            const shippingSelect = document.getElementById('shipping_method');
            let suggestedMethod = 'outside_dhaka'; // default
            
            if (city.includes('dhaka')) {
                suggestedMethod = 'inside_dhaka';
            } else if (city.includes('chittagong') || city.includes('sylhet') || city.includes('rajshahi')) {
                suggestedMethod = 'outside_dhaka';
            } else {
                suggestedMethod = 'across_country';
            }
            
            // Set the suggested shipping method
            if (shippingOptions[suggestedMethod]) {
                shippingSelect.value = suggestedMethod;
                shippingSelect.dispatchEvent(new Event('change'));
            }
        }
        
        updateTotals();
    }

    // Add event listeners to existing rows
    document.querySelectorAll('.order-item-row').forEach(row => {
        addRowEventListeners(row);
    });

    function addRowEventListeners(row) {
        // Product selection change
        const productSelect = row.querySelector('select[name*="product_id"]');
        const priceInput = row.querySelector('.price-input');
        
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.price) {
                priceInput.value = selectedOption.dataset.price;
                updateRowTotal(row);
            }
        });

        // Quantity and price changes
        row.querySelectorAll('.quantity-input, .price-input').forEach(input => {
            input.addEventListener('input', function() {
                updateRowTotal(row);
            });
        });
    }

    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        
        row.querySelector('.total-display').value = total.toFixed(2);
        calculateShippingAndTax(); // Recalculate shipping and tax when items change
        updateTotals();
    }

    function calculateSubtotal() {
        let subtotal = 0;
        document.querySelectorAll('.total-display').forEach(totalInput => {
            subtotal += parseFloat(totalInput.value) || 0;
        });
        return subtotal;
    }
    
    // Make calculateSubtotal globally accessible
    window.calculateSubtotal = calculateSubtotal;

    function updateTotals() {
        const subtotal = calculateSubtotal();
        const shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
        const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        
        const grandTotal = subtotal + shipping + tax - discount;
        document.getElementById('totalAmount').value = grandTotal.toFixed(2);
        
        // Update shipping cost display with free shipping logic
        updateFreeShippingStatus(subtotal, shipping);
    }
    
    // Make updateTotals globally accessible
    window.updateTotals = updateTotals;
    
    function updateFreeShippingStatus(subtotal, currentShipping) {
        const country = document.getElementById('shipping_country').value;
        const city = document.getElementById('shipping_city').value.toLowerCase();
        const shippingMethodSelect = document.getElementById('shipping_method');
        
        // Check for free shipping eligibility (Bangladesh specific)
        if (country === 'BD') {
            let freeShippingThreshold = 0;
            let qualifiesForFree = false;
            
            if (city.includes('dhaka') && subtotal >= 500) {
                freeShippingThreshold = 500;
                qualifiesForFree = true;
            } else if (!city.includes('dhaka') && subtotal >= 1500) {
                freeShippingThreshold = 1500;
                qualifiesForFree = true;
            }
            
            // Add free shipping option if qualified
            if (qualifiesForFree && currentShipping > 0) {
                addFreeShippingOption(shippingMethodSelect, freeShippingThreshold);
            } else {
                removeFreeShippingOption(shippingMethodSelect);
            }
        }
    }
    
    // Make updateFreeShippingStatus globally accessible
    window.updateFreeShippingStatus = updateFreeShippingStatus;
    
    function addFreeShippingOption(selectElement, threshold) {
        // Check if free shipping option already exists
        if (!selectElement.querySelector('option[value="free"]')) {
            const freeOption = document.createElement('option');
            freeOption.value = 'free';
            freeOption.dataset.rate = '0';
            freeOption.dataset.deliveryTime = '3-5 days';
            freeOption.textContent = `Free Shipping - ৳0.00 (Qualified - Order over ৳${threshold})`;
            freeOption.style.backgroundColor = '#d4edda';
            freeOption.style.color = '#155724';
            selectElement.appendChild(freeOption);
            
            // Show notification
            showFreeShippingNotification(threshold);
        }
    }
    
    function removeFreeShippingOption(selectElement) {
        const freeOption = selectElement.querySelector('option[value="free"]');
        if (freeOption) {
            freeOption.remove();
        }
    }
    
    function showFreeShippingNotification(threshold) {
        // Create a temporary notification
        const notification = document.createElement('div');
        notification.className = 'alert alert-success alert-dismissible fade show mt-2';
        notification.innerHTML = `
            <i class="ri-truck-line me-2"></i>
            Congratulations! Your order qualifies for free shipping (order over ৳${threshold})
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after shipping method field
        const shippingGroup = document.getElementById('shipping_method').closest('.col-md-6');
        shippingGroup.insertAdjacentElement('afterend', notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Update totals when shipping, tax, or discount changes
    document.querySelectorAll('#shipping_amount, #tax_amount, #discount_amount').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    // Initial calculation
    updateTotals();

    // Customer Search Implementation
    let customerSearchTimeout;
    const customerSearchInput = document.getElementById('customer_search');
    const customerDropdown = document.getElementById('customer_dropdown');
    const customerIdInput = document.getElementById('customer_id');
    const selectedCustomerDiv = document.getElementById('selected_customer');

    // Check if elements exist
    if (!customerSearchInput) {
        return;
    }

    customerSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(customerSearchTimeout);
        
        if (query.length < 2) {
            customerDropdown.style.display = 'none';
            return;
        }

        customerSearchTimeout = setTimeout(() => {
            window.searchCustomers(query);
        }, 300);
    });

    // Define search functions on window object for global access using jQuery
    window.searchCustomers = function(query) {
        // Function implementation is in the jQuery section
    };

    window.displayCustomerResults = function(customers) {
        if (customers.length === 0) {
            customerDropdown.innerHTML = '<div class="dropdown-item text-muted">No customers found</div>';
        } else {
            customerDropdown.innerHTML = customers.map(customer => `
                <div class="dropdown-item cursor-pointer" onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.email}', '${customer.phone || ''}', ${customer.orders_count})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${customer.name}</strong><br>
                            <small class="text-muted">${customer.email}</small>
                            ${customer.phone ? `<br><small class="text-muted">${customer.phone}</small>` : ''}
                        </div>
                        <div class="text-end">
                            <small class="badge bg-primary">${customer.orders_count} orders</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        customerDropdown.style.display = 'block';
    };

    window.selectCustomer = function(id, name, email, phone, ordersCount) {
        customerIdInput.value = id;
        customerSearchInput.value = name;
        customerDropdown.style.display = 'none';
        
        // Show selected customer info
        document.getElementById('customer_name').textContent = name;
        document.getElementById('customer_details').innerHTML = `${email}${phone ? ' • ' + phone : ''} • ${ordersCount} previous orders`;
        selectedCustomerDiv.style.display = 'block';
        customerSearchInput.style.display = 'none';
    };

    window.clearCustomerSelection = function() {
        customerIdInput.value = '';
        customerSearchInput.value = '';
        customerSearchInput.style.display = 'block';
        selectedCustomerDiv.style.display = 'none';
        customerDropdown.style.display = 'none';
    };

    // Hide customer dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#customer_search') && !e.target.closest('#customer_dropdown')) {
            customerDropdown.style.display = 'none';
        }
    });

    // Product Search Implementation
    let productSearchTimeout;
    
    function initializeProductSearch() {
        document.querySelectorAll('.product-search').forEach(input => {
            input.addEventListener('input', function() {
                const query = this.value.trim();
                const dropdown = this.parentNode.querySelector('.product-dropdown');
                
                clearTimeout(productSearchTimeout);
                
                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }

                productSearchTimeout = setTimeout(() => {
                    searchProducts(query, this);
                }, 300);
            });
        });
    }

    window.searchProducts = function(query, inputElement) {
        // Function implementation is in the jQuery section
    };

    window.displayProductResults = function(products, dropdown, inputElement) {
        if (products.length === 0) {
            dropdown.innerHTML = '<div class="dropdown-item text-muted">No products found</div>';
        } else {
            dropdown.innerHTML = products.map(product => {
                const stockBadge = getStockBadge(product.stock_quantity);
                const vendorInfo = product.vendor ? ` • Vendor: ${product.vendor.name}` : '';
                
                return `
                    <div class="dropdown-item cursor-pointer" onclick="selectProduct(this, ${product.id}, '${product.name}', '${product.sku || ''}', ${product.price}, ${product.stock_quantity}, '${product.vendor?.name || ''}')">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${product.name}</strong>
                                ${product.sku ? `<small class="text-muted"> (${product.sku})</small>` : ''}
                                <br>
                                <small class="text-muted">৳${product.price}${vendorInfo}</small>
                            </div>
                            <div class="text-end">
                                ${stockBadge}
                                <br>
                                <small class="text-muted">Stock: ${product.stock_quantity}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        dropdown.style.display = 'block';
    };

    window.getStockBadge = function(stock) {
        if (stock <= 0) {
            return '<span class="badge bg-danger">Out of Stock</span>';
        } else if (stock <= 10) {
            return '<span class="badge bg-warning">Low Stock</span>';
        } else {
            return '<span class="badge bg-success">In Stock</span>';
        }
    };

    window.selectProduct = function(element, id, name, sku, price, stock, vendor) {
        const row = element.closest('.order-item-row');
        const productIdInput = row.querySelector('.product-id-input');
        const productSearchInput = row.querySelector('.product-search');
        const priceInput = row.querySelector('.price-input');
        const selectedProductDiv = row.querySelector('.selected-product');
        const dropdown = row.querySelector('.product-dropdown');
        const stockInfo = row.querySelector('.stock-info');
        
        // Set values
        productIdInput.value = id;
        productSearchInput.value = name;
        priceInput.value = price;
        dropdown.style.display = 'none';
        
        // Show selected product info
        selectedProductDiv.querySelector('.product-name').textContent = name;
        selectedProductDiv.querySelector('.product-details').innerHTML = 
            `${sku ? `SKU: ${sku} • ` : ''}৳${price}${vendor ? ` • ${vendor}` : ''}`;
        selectedProductDiv.style.display = 'block';
        productSearchInput.style.display = 'none';
        
        // Update stock info
        stockInfo.innerHTML = `Stock: ${stock} ${getStockBadge(stock)}`;
        
        // Update row total
        updateRowTotal(row);
    };

    // Clear product selection

    // Clear product selection
    document.addEventListener('click', function(e) {
        if (e.target.closest('.clear-product-btn')) {
            const row = e.target.closest('.order-item-row');
            clearProductSelection(row);
        }
    });

    window.clearProductSelection = function(row) {
        const productIdInput = row.querySelector('.product-id-input');
        const productSearchInput = row.querySelector('.product-search');
        const priceInput = row.querySelector('.price-input');
        const selectedProductDiv = row.querySelector('.selected-product');
        const dropdown = row.querySelector('.product-dropdown');
        const stockInfo = row.querySelector('.stock-info');
        
        productIdInput.value = '';
        productSearchInput.value = '';
        priceInput.value = '';
        productSearchInput.style.display = 'block';
        selectedProductDiv.style.display = 'none';
        dropdown.style.display = 'none';
        stockInfo.innerHTML = '';
        
        updateRowTotal(row);
    };

    // Hide product dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.product-search') && !e.target.closest('.product-dropdown')) {
            document.querySelectorAll('.product-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });

    // Initialize product search for existing rows
    initializeProductSearch();
});
</script>

<!-- Separate jQuery Section for Search Functions -->
<script>
$(document).ready(function() {
    let customerSearchTimeout;
    let productSearchTimeout;
    
    // jQuery-based Customer Search Function
    window.searchCustomers = function(query) {
        if (!query || query.length < 2) {
            $('#customer_dropdown').hide();
            return;
        }
        
        // Use jQuery AJAX - much more reliable
        $.ajax({
            url: `{{ route('admin.orders.searchCustomers') }}`,
            method: 'GET',
            data: { q: query },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                $('#customer_dropdown').html(`
                    <div class="dropdown-item text-center py-4">
                        <div class="text-muted">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Searching customers...
                        </div>
                    </div>
                `).show();
            },
            success: function(data) {
                if (data && Array.isArray(data)) {
                    displayCustomerResults(data);
                } else {
                    $('#customer_dropdown').html('<div class="dropdown-item text-danger">Invalid response format</div>').show();
                }
            },
            error: function(xhr, status, error) {
                $('#customer_dropdown').html('<div class="dropdown-item text-danger">Error: ' + error + '</div>').show();
            }
        });
    };
    
    // Display customer results using jQuery with enhanced organization
    function displayCustomerResults(customers) {
        if (customers.length === 0) {
            $('#customer_dropdown').html(`
                <div class="dropdown-item text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-search mb-2" style="font-size: 24px;"></i>
                        <div>No users found</div>
                        <small>Try different search terms</small>
                    </div>
                </div>
            `).show();
        } else {
            // Add header with results count
            let resultsHtml = `
                <div class="dropdown-header bg-light">
                    <strong>Found ${customers.length} user${customers.length > 1 ? 's' : ''}</strong>
                </div>
            `;
            
            // Sort customers by order count (most active first), then by role
            const sortedCustomers = customers.sort((a, b) => {
                if (b.orders_count !== a.orders_count) {
                    return b.orders_count - a.orders_count;
                }
                // Secondary sort by role priority (customer > vendor > affiliate > admin)
                const rolePriority = {customer: 1, vendor: 2, affiliate: 3, admin: 4};
                return (rolePriority[a.role] || 5) - (rolePriority[b.role] || 5);
            });
            
            resultsHtml += sortedCustomers.map((customer, index) => {
                const isActive = customer.orders_count > 0;
                const badgeClass = isActive ? 'bg-success' : 'bg-secondary';
                const userStatus = isActive ? 'Active User' : 'New User';
                
                // Role badge styling
                const roleColors = {
                    customer: 'bg-primary',
                    vendor: 'bg-warning text-dark',
                    affiliate: 'bg-info',
                    admin: 'bg-danger',
                    default: 'bg-secondary'
                };
                const roleColor = roleColors[customer.role?.toLowerCase()] || roleColors.default;
                
                return `
                    <div class="dropdown-item cursor-pointer border-bottom py-3 customer-select-item" 
                         data-customer-id="${customer.id}"
                         data-customer-name="${customer.name}"
                         data-customer-email="${customer.email}"
                         data-customer-phone="${customer.phone || ''}"
                         data-customer-username="${customer.username || ''}"
                         data-customer-orders="${customer.orders_count}"
                         data-customer-role="${customer.role || 'user'}"
                         onmouseover="this.style.backgroundColor='#f8f9fa'" 
                         onmouseout="this.style.backgroundColor=''"
                         style="transition: background-color 0.2s;">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 35px; height: 35px; font-size: 14px; font-weight: bold;">
                                        ${customer.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-dark">${customer.name}</span>
                                            <span class="badge ${roleColor} badge-sm">${customer.role || 'user'}</span>
                                        </div>
                                        ${customer.username ? `
                                            <div class="small text-muted">
                                                <i class="fas fa-user me-1"></i>@${customer.username}
                                            </div>
                                        ` : ''}
                                        <div class="small text-muted">
                                            <i class="fas fa-envelope me-1"></i>${customer.email}
                                        </div>
                                        ${customer.phone ? `
                                            <div class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>${customer.phone}
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="mb-1">
                                    <span class="badge ${badgeClass} rounded-pill">
                                        ${customer.orders_count} order${customer.orders_count !== 1 ? 's' : ''}
                                    </span>
                                </div>
                                <div class="small text-muted">${userStatus}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            $('#customer_dropdown').html(resultsHtml).show();
        }
    }
    
    // Make display function globally accessible
    window.displayCustomerResults = displayCustomerResults;
    
    // Customer selection function using jQuery with enhanced display
    window.selectCustomer = function(id, name, email, phone, username, ordersCount, role) {
        // Set form values
        $('#customer_id').val(id);
        $('#customer_dropdown').hide();
        
        // Update enhanced customer display
        $('#customer_avatar').text(name.charAt(0).toUpperCase());
        $('#customer_name').text(name);
        
        // Build detailed customer info with role
        let details = '';
        if (role) {
            const roleColors = {
                customer: 'text-primary',
                vendor: 'text-warning',
                affiliate: 'text-info',
                admin: 'text-danger',
                default: 'text-secondary'
            };
            const roleColor = roleColors[role?.toLowerCase()] || roleColors.default;
            details += `<div class="mb-1"><i class="fas fa-user-tag me-1 ${roleColor}"></i><span class="${roleColor}">${role.charAt(0).toUpperCase() + role.slice(1)}</span></div>`;
        }
        if (username) {
            details += `<div class="mb-1"><i class="fas fa-user me-1"></i>@${username}</div>`;
        }
        details += `<div class="mb-1"><i class="fas fa-envelope me-1"></i>${email}</div>`;
        if (phone) {
            details += `<div><i class="fas fa-phone me-1"></i>${phone}</div>`;
        }
        $('#customer_details').html(details);
        
        // Update orders badge
        const badgeText = ordersCount > 0 ? `${ordersCount} Previous Order${ordersCount !== 1 ? 's' : ''}` : `New ${role || 'User'}`;
        $('#customer_orders_badge').text(badgeText);
        
        // Hide entire search container and show selected customer section
        $('#customer_search_container').hide();
        $('#selected_customer').show();
    };
    
    // Clear customer selection using jQuery
    window.clearCustomerSelection = function() {
        $('#customer_id').val('');
        $('#customer_search').val('');
        $('#customer_search_container').show();
        $('#selected_customer').hide();
        $('#customer_dropdown').hide();
    };
    
    // jQuery-based Product Search Function
    window.searchProducts = function(query, inputElement) {
        const dropdown = $(inputElement).parent().find('.product-dropdown')[0];
        const vendorId = $('#vendor_id').val();
        
        if (!query || query.length < 2) {
            $(dropdown).hide();
            return;
        }
        
        // Prepare data for jQuery
        const searchData = { q: query };
        if (vendorId) {
            searchData.vendor_id = vendorId;
        }

        // Use jQuery AJAX - much more reliable
        $.ajax({
            url: `{{ route('admin.orders.searchProducts') }}`,
            method: 'GET',
            data: searchData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                $(dropdown).html('<div class="dropdown-item text-muted">Searching products...</div>').show();
            },
            success: function(data) {
                displayProductResultsJQuery(data, dropdown, inputElement);
            },
            error: function(xhr, status, error) {
                $(dropdown).html('<div class="dropdown-item text-danger">Error: ' + error + '</div>').show();
            }
        });
    };
    
    // jQuery-compatible version of displayProductResults
    function displayProductResultsJQuery(products, dropdown, inputElement) {
        if (!Array.isArray(products) || products.length === 0) {
            $(dropdown).html('<div class="dropdown-item text-muted">No products found</div>').show();
        } else {
            const resultsHtml = products.map(product => {
                const stockBadge = getStockBadgeJQuery(product.stock_quantity);
                const vendorInfo = product.vendor ? ` • Vendor: ${product.vendor.name}` : '';
                
                return `
                    <div class="dropdown-item cursor-pointer" onclick="selectProductJQuery(this, ${product.id}, '${product.name.replace(/'/g, "\\'")}', '${product.sku || ''}', ${product.price}, ${product.stock_quantity}, '${product.vendor?.name || ''}')">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${product.name}</strong>
                                ${product.sku ? `<small class="text-muted"> (${product.sku})</small>` : ''}
                                <br>
                                <small class="text-muted">৳${product.price}${vendorInfo}</small>
                            </div>
                            <div class="text-end">
                                ${stockBadge}
                                <br>
                                <small class="text-muted">Stock: ${product.stock_quantity}</small>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            $(dropdown).html(resultsHtml).show();
        }
    }
    
    // jQuery-compatible version of getStockBadge
    function getStockBadgeJQuery(stock) {
        if (stock <= 0) {
            return '<span class="badge bg-danger">Out of Stock</span>';
        } else if (stock <= 10) {
            return '<span class="badge bg-warning">Low Stock</span>';
        } else {
            return '<span class="badge bg-success">In Stock</span>';
        }
    }
    
    // jQuery-compatible version of selectProduct
    window.selectProductJQuery = function(element, id, name, sku, price, stock, vendor) {
        const $row = $(element).closest('.order-item-row');
        const $productIdInput = $row.find('.product-id-input');
        const $productSearchInput = $row.find('.product-search');
        const $productSearchContainer = $row.find('.position-relative').first(); // The search container
        const $priceInput = $row.find('.price-input');
        const $selectedProductDiv = $row.find('.selected-product');
        const $dropdown = $row.find('.product-dropdown');
        const $stockInfo = $row.find('.stock-info');
        
        // Set values
        $productIdInput.val(id);
        $priceInput.val(price);
        $dropdown.hide();
        
        // Hide the entire search container and show selected product
        $productSearchContainer.hide();
        $selectedProductDiv.show();
        
        // Show selected product info
        $selectedProductDiv.find('.product-name').text(name);
        $selectedProductDiv.find('.product-details').html(
            `${sku ? `SKU: ${sku} • ` : ''}৳${price}${vendor ? ` • ${vendor}` : ''}`
        );
        
        // Update stock info
        $stockInfo.html(`Stock: ${stock} ${getStockBadgeJQuery(stock)}`);
        
        // Update row total using jQuery
        updateRowTotalJQuery($row);
        
        // Update overall totals
        if (typeof window.updateTotals === 'function') {
            window.updateTotals();
        }
    };
    
    // jQuery-compatible version of updateRowTotal
    function updateRowTotalJQuery($row) {
        const quantity = parseFloat($row.find('.quantity-input').val()) || 0;
        const price = parseFloat($row.find('.price-input').val()) || 0;
        const total = quantity * price;
        
        $row.find('.total-display').val(total.toFixed(2));
    }
    
    // Customer search with multiple event types and enhanced feedback
    $('#customer_search').on('input keyup paste change', function() {
        const query = $(this).val().trim();
        
        clearTimeout(customerSearchTimeout);
        
        if (query.length === 0) {
            $('#customer_dropdown').hide();
            return;
        }
        
        if (query.length === 1) {
            $('#customer_dropdown').html(`
                <div class="dropdown-item text-center py-3">
                    <div class="text-muted">
                        <i class="fas fa-keyboard me-2"></i>
                        Type at least 2 characters to search
                    </div>
                </div>
            `).show();
            return;
        }

        customerSearchTimeout = setTimeout(() => {
            window.searchCustomers(query);
        }, 300);
    });
    
    // Product search event handlers - use delegation for dynamic elements
    $(document).on('input keyup paste change', '.product-search', function() {
        const query = $(this).val().trim();
        
        clearTimeout(productSearchTimeout);
        
        if (query.length < 2) {
            $(this).parent().find('.product-dropdown').hide();
            return;
        }

        const inputElement = this;
        productSearchTimeout = setTimeout(() => {
            window.searchProducts(query, inputElement);
        }, 300);
    });
    
    // Event delegation for customer selection
    $(document).on('click', '.customer-select-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).data('customer-id');
        const name = $(this).data('customer-name');
        const email = $(this).data('customer-email');
        const phone = $(this).data('customer-phone');
        const username = $(this).data('customer-username');
        const ordersCount = $(this).data('customer-orders');
        const role = $(this).data('customer-role');
        
        window.selectCustomer(id, name, email, phone, username, ordersCount, role);
    });
    
    // Event handlers for quantity and price changes to update totals
    $(document).on('input change', '.quantity-input, .price-input', function() {
        const $row = $(this).closest('.order-item-row');
        updateRowTotalJQuery($row);
        
        // Update overall totals
        if (typeof window.updateTotals === 'function') {
            window.updateTotals();
        }
    });
    
    // Event delegation for product removal
    $(document).on('click', '.clear-product-btn', function() {
        const $row = $(this).closest('.order-item-row');
        const $productIdInput = $row.find('.product-id-input');
        const $productSearchInput = $row.find('.product-search');
        const $productSearchContainer = $row.find('.position-relative').first(); // The search container
        const $priceInput = $row.find('.price-input');
        const $selectedProductDiv = $row.find('.selected-product');
        const $stockInfo = $row.find('.stock-info');
        
        // Clear values
        $productIdInput.val('');
        $productSearchInput.val('');
        $priceInput.val('');
        $stockInfo.html('');
        
        // Show search container and hide selected product
        $productSearchContainer.show();
        $selectedProductDiv.hide();
        
        // Update totals
        updateRowTotalJQuery($row);
        if (typeof window.updateTotals === 'function') {
            window.updateTotals();
        }
    });
    
    // jQuery-based Add Item functionality - creates empty rows
    let itemIndex = 1; // Start from 1 since the first row uses index 0
    
    // Remove existing event listeners and add jQuery-based one
    $('#addItemBtn').off('click').on('click', function() {
        const newRowHtml = `
            <div class="order-item-row border rounded p-3 mb-2">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <div class="position-relative">
                            <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." data-index="${itemIndex}">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                <i class="ri-search-line text-muted"></i>
                            </div>
                            <div class="product-dropdown dropdown-menu w-100" style="max-height: 300px; overflow-y: auto; display: none;">
                                <!-- Product search results will appear here -->
                            </div>
                        </div>
                        <input type="hidden" name="items[${itemIndex}][product_id]" class="product-id-input" required>
                        <div class="selected-product mt-2" style="display: none;">
                            <div class="card border-success">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="product-name"></strong><br>
                                            <small class="text-muted product-details"></small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger clear-product-btn">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control quantity-input" name="items[${itemIndex}][quantity]" min="1" value="1" required>
                        <small class="text-muted stock-info"></small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control price-input" name="items[${itemIndex}][price]" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control total-display" readonly>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add the new row to the container
        $('#orderItems').append(newRowHtml);
        
        // Increment the index for next row
        itemIndex++;
        
        // Update totals
        if (typeof window.updateTotals === 'function') {
            window.updateTotals();
        }
    });
    
    // Event delegation for remove item functionality
    $(document).on('click', '.remove-item-btn', function() {
        const $row = $(this).closest('.order-item-row');
        
        // Don't remove if it's the only row
        if ($('.order-item-row').length <= 1) {
            alert('At least one item is required for the order.');
            return;
        }
        
        // Remove the row
        $row.remove();
        
        // Update totals after removal
        if (typeof window.updateTotals === 'function') {
            window.updateTotals();
        }
    });
    
    // Hide dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#customer_search, #customer_dropdown').length) {
            $('#customer_dropdown').hide();
        }
        
        if (!$(e.target).closest('.product-search, .product-dropdown').length) {
            $('.product-dropdown').hide();
        }
    });
    
    // ===== MODAL PRODUCT SEARCH FUNCTIONS =====
    
    // Modal search function with jQuery
    function performModalSearch() {
        const query = $('#modal_product_search').val().trim();
        const category = $('#modal_category_filter').val();
        const vendor = $('#modal_vendor_filter').val();
        const stock = $('#modal_stock_filter').val();
        
        if (query.length < 2 && !category && !vendor && !stock) {
            $('#modal_product_results').html(`
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-search me-2"></i>
                        Enter at least 2 characters to search
                    </div>
                </div>
            `);
            return;
        }
        
        const searchData = {};
        if (query) searchData.q = query;
        if (category) searchData.category_id = category;
        if (vendor) searchData.vendor_id = vendor;
        if (stock) searchData.stock_status = stock;
        
        $.ajax({
            url: `{{ route('admin.orders.searchProducts') }}`,
            method: 'GET',
            data: searchData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                $('#modal_product_results').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">Searching products...</div>
                    </div>
                `);
            },
            success: function(data) {
                displayModalProductResults(data);
            },
            error: function(xhr, status, error) {
                $('#modal_product_results').html(`
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error searching products: ${error}
                    </div>
                `);
            }
        });
    }
    
    // Display modal product results with jQuery
    function displayModalProductResults(products) {
        if (!Array.isArray(products) || products.length === 0) {
            $('#modal_product_results').html(`
                <div class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-box-open me-2"></i>
                        No products found
                    </div>
                </div>
            `);
            return;
        }
        
        const resultsHtml = products.map(product => {
            const stockBadge = getStockBadge(product.stock_quantity);
            const vendorInfo = product.vendor ? ` • Vendor: ${product.vendor.name}` : '';
            const categoryInfo = product.category ? ` • Category: ${product.category.name}` : '';
            const productName = (product.name || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
            const vendorName = product.vendor ? (product.vendor.name || '').replace(/'/g, "\\'").replace(/"/g, '\\"') : '';
            
            return `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 product-card" style="cursor: pointer;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-1">${product.name}</h6>
                                ${stockBadge}
                            </div>
                            <p class="card-text small text-muted mb-2">
                                SKU: ${product.sku || 'N/A'}${vendorInfo}${categoryInfo}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0 text-primary">$${parseFloat(product.price).toFixed(2)}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="addProductFromModal(${product.id}, '${productName}', '${product.sku || ''}', ${product.price}, ${product.stock_quantity}, '${vendorName}')">
                                    <i class="fas fa-plus me-1"></i>Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        $('#modal_product_results').html(`<div class="row">${resultsHtml}</div>`);
    }
    
    // Helper function for stock badge
    function getStockBadge(stockQuantity) {
        if (stockQuantity <= 0) {
            return '<span class="badge bg-danger">Out of Stock</span>';
        } else if (stockQuantity <= 5) {
            return '<span class="badge bg-warning">Low Stock</span>';
        } else {
            return '<span class="badge bg-success">In Stock</span>';
        }
    }
    
    // jQuery version of updateTotals function
    function updateTotals() {
        let subtotal = 0;
        $('.total-display').each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });
        
        const shipping = parseFloat($('#shipping_amount').val()) || 0;
        const tax = parseFloat($('#tax_amount').val()) || 0;
        const discount = parseFloat($('#discount_amount').val()) || 0;
        
        const grandTotal = subtotal + shipping + tax - discount;
        $('#totalAmount').val(grandTotal.toFixed(2));
        
        // Update shipping cost display with free shipping logic if the vanilla JS function exists
        if (typeof window.updateFreeShippingStatus === 'function') {
            window.updateFreeShippingStatus(subtotal, shipping);
        }
    }
    
    // Make updateTotals globally accessible
    window.updateTotals = updateTotals;
    
    // jQuery version of removeProduct function
    function removeProduct(button) {
        const orderItem = $(button).closest('.order-item-row');
        orderItem.remove();
        updateTotals();
        
        // Re-index remaining items
        $('.order-item-row').each(function(index) {
            $(this).find('input[name*="["]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    $(this).attr('name', newName);
                }
            });
            $(this).find('.product-search').attr('data-index', index);
        });
    }
    
    // Make removeProduct globally accessible
    window.removeProduct = removeProduct;
    
    // Add product from modal with jQuery
    window.addProductFromModal = function(productId, productName, productSku, productPrice, stockQuantity, vendorName) {
        // Check if product already exists in any order item
        let existingProduct = null;
        $('.order-item-row').each(function() {
            const hiddenInput = $(this).find('input[name*="[product_id]"]');
            if (hiddenInput.val() == productId) {
                existingProduct = $(this);
                return false; // break the loop
            }
        });
        
        if (existingProduct) {
            // Update quantity instead of adding duplicate
            const qtyInput = existingProduct.find('.quantity-input');
            const currentQty = parseInt(qtyInput.val()) || 0;
            qtyInput.val(currentQty + 1).trigger('change');
            
            // Show success message
            $('#productSearchModal').modal('hide');
            
            // Highlight the updated row briefly
            existingProduct.addClass('bg-warning bg-opacity-25');
            setTimeout(() => {
                existingProduct.removeClass('bg-warning bg-opacity-25');
            }, 2000);
            
            return;
        }
        
        // Get the next item index
        const itemCount = $('.order-item-row').length;
        const nextIndex = itemCount;
        
        // Add new product row using the same structure as existing order items
        const newRowHtml = `
            <div class="order-item-row border rounded p-3 mb-2" data-product-id="${productId}">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Product</label>
                        <div class="position-relative">
                            <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." data-index="${nextIndex}" value="${productName}" readonly style="background-color: #f8f9fa;">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                <i class="ri-check-line text-success"></i>
                            </div>
                        </div>
                        <input type="hidden" name="items[${nextIndex}][product_id]" class="product-id-input" value="${productId}" required>
                        <div class="selected-product mt-2" style="display: block;">
                            <div class="card border-success">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="product-name">${productName}</strong><br>
                                            <small class="text-muted product-details">SKU: ${productSku}${vendorName ? ` | Vendor: ${vendorName}` : ''}</small>
                                        </div>
                                        <span class="badge bg-success">Selected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control quantity-input" name="items[${nextIndex}][quantity]" min="1" value="1" max="${stockQuantity}" required>
                        <small class="text-muted stock-info">Stock: ${stockQuantity}</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control price-input" name="items[${nextIndex}][price]" step="0.01" min="0" value="${productPrice}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control total-display" readonly value="${parseFloat(productPrice).toFixed(2)}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-item-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#orderItems').append(newRowHtml);
        updateTotals();
        $('#productSearchModal').modal('hide');
        
        // Show success message
        setTimeout(() => {
            const newRow = $('#orderItems .order-item-row').last();
            newRow.addClass('bg-success bg-opacity-25');
            setTimeout(() => {
                newRow.removeClass('bg-success bg-opacity-25');
            }, 2000);
        }, 100);
    };
    
    // Modal event handlers
    let modalSearchTimeout;
    
    // Search input handler
    $(document).on('input', '#modal_product_search', function() {
        clearTimeout(modalSearchTimeout);
        modalSearchTimeout = setTimeout(performModalSearch, 300);
    });
    
    // Filter change handlers
    $(document).on('change', '#modal_category_filter, #modal_vendor_filter, #modal_stock_filter', function() {
        performModalSearch();
    });
    
    // Clear filters button
    $(document).on('click', '#clear_modal_filters', function() {
        $('#modal_product_search').val('');
        $('#modal_category_filter').val('');
        $('#modal_vendor_filter').val('');
        $('#modal_stock_filter').val('');
        $('#modal_product_results').html(`
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-search me-2"></i>
                    Enter search criteria to find products
                </div>
            </div>
        `);
    });
    
    // Order item quantity and price change handlers
    $(document).on('change', '.quantity-input, .price-input', function() {
        const orderItem = $(this).closest('.order-item-row');
        const quantity = parseFloat(orderItem.find('.quantity-input').val()) || 0;
        const price = parseFloat(orderItem.find('.price-input').val()) || 0;
        const total = quantity * price;
        
        orderItem.find('.total-display').val(total.toFixed(2));
        updateTotals();
    });
    
    // Remove item button handler
    $(document).on('click', '.remove-item-btn', function() {
        removeProduct(this);
    });
    
    // Modal reset on close
    $('#productSearchModal').on('hidden.bs.modal', function() {
        $('#modal_product_search').val('');
        $('#modal_category_filter').val('');
        $('#modal_vendor_filter').val('');
        $('#modal_stock_filter').val('');
        $('#modal_product_results').html(`
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-search me-2"></i>
                    Enter search criteria to find products
                </div>
            </div>
        `);
    });
    
    // Load categories when modal is opened
    $('#productSearchModal').on('shown.bs.modal', function() {
        loadCategories();
    });
    
    // Function to load categories
    function loadCategories() {
        if ($('#modal_category_filter option').length <= 1) { // Only load if not already loaded
            $.ajax({
                url: '{{ route("api.categories.parent-categories") }}',
                method: 'GET',
                success: function(response) {
                    $('#modal_category_filter').empty().append('<option value="">All Categories</option>');
                    if (response.success && response.data && response.data.categories) {
                        response.data.categories.forEach(function(category) {
                            $('#modal_category_filter').append(
                                `<option value="${category.id}">${category.name}</option>`
                            );
                        });
                    }
                },
                error: function() {
                    // Silently fail if categories can't be loaded
                    $('#modal_category_filter').empty().append('<option value="">All Categories</option>');
                }
            });
        }
    }
});
</script>
@endpush
