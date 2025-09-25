@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-section {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }
    
    .toggle-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .toggle-section h6 {
        margin-bottom: 15px;
        color: #495057;
    }
    
    .form-check {
        margin-bottom: 10px;
    }
    
    .select2-container {
        width: 100% !important;
    }
    
    .input-group-text {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .preview-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .coupon-preview {
        background: rgba(255, 255, 255, 0.1);
        border: 2px dashed rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    
    .coupon-code-preview {
        font-size: 1.5rem;
        font-weight: bold;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }
    
    .coupon-desc-preview {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    /* Date picker styling */
    .flatpickr-input {
        cursor: pointer;
    }
    
    .clear-date {
        border-left: 0 !important;
    }
    
    .flatpickr-calendar {
        z-index: 9999 !important;
    }
    
    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('admin.coupons.index') }}" class="text-decoration-none">
                <i class="fas fa-tags"></i> Coupons
            </a>
            <span class="text-muted">/</span> Create New
        </h1>
        <div class="d-sm-flex">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Coupons
            </a>
        </div>
    </div>

    <form id="couponForm" action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="code" name="code" 
                                           value="{{ old('code') }}" required>
                                    <button type="button" class="btn btn-outline-secondary" id="generateCode">
                                        <i class="fas fa-random"></i> Generate
                                    </button>
                                </div>
                                @error('code')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Discount Settings -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-percent"></i> Discount Settings</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                    <option value="buy_x_get_y" {{ old('type') == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y</option>
                                    <option value="bulk_discount" {{ old('type') == 'bulk_discount' ? 'selected' : '' }}>Bulk Discount</option>
                                </select>
                                @error('type')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6" id="valueField">
                            <div class="form-group">
                                <label for="value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="valuePrefix">$</span>
                                    <input type="number" class="form-control" id="value" name="value" 
                                           value="{{ old('value') }}" step="0.01" min="0">
                                    <span class="input-group-text" id="valueSuffix" style="display: none;">%</span>
                                </div>
                                @error('value')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="minimum_amount" class="form-label">Minimum Order Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="minimum_amount" name="minimum_amount" 
                                           value="{{ old('minimum_amount') }}" step="0.01" min="0">
                                </div>
                                @error('minimum_amount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_discount" class="form-label">Maximum Discount Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="maximum_discount" name="maximum_discount" 
                                           value="{{ old('maximum_discount') }}" step="0.01" min="0">
                                </div>
                                @error('maximum_discount')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Limits -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-users"></i> Usage Limits</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage_limit" class="form-label">Total Usage Limit</label>
                                <input type="number" class="form-control" id="usage_limit" name="usage_limit" 
                                       value="{{ old('usage_limit') }}" min="0">
                                <div class="form-text">Leave empty for unlimited usage</div>
                                @error('usage_limit')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usage_limit_per_user" class="form-label">Usage Limit Per User</label>
                                <input type="number" class="form-control" id="usage_limit_per_user" name="usage_limit_per_user" 
                                       value="{{ old('usage_limit_per_user') }}" min="0">
                                <div class="form-text">Leave empty for unlimited per user</div>
                                @error('usage_limit_per_user')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-calendar"></i> Validity Period</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" class="form-control" id="start_date_display" 
                                           placeholder="Select start date and time" readonly required>
                                    <input type="hidden" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                </div>
                                <div class="form-text">Required field - select when coupon becomes active</div>
                                @error('start_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" class="form-control" id="end_date_display" 
                                           placeholder="Select end date and time" readonly>
                                    <input type="hidden" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                </div>
                                <div class="form-text">Leave empty for no expiry</div>
                                @error('end_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restrictions -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-filter"></i> Product & Category Restrictions</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="applicable_products" class="form-label">Applicable Products</label>
                                <select class="form-select" id="applicable_products" name="applicable_products[]" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Leave empty to apply to all products</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="exclude_products" class="form-label">Exclude Products</label>
                                <select class="form-select" id="exclude_products" name="exclude_products[]" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="applicable_categories" class="form-label">Applicable Categories</label>
                                <select class="form-select" id="applicable_categories" name="applicable_categories[]" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Leave empty to apply to all categories</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="exclude_categories" class="form-label">Exclude Categories</label>
                                <select class="form-select" id="exclude_categories" name="exclude_categories[]" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-cogs"></i> Advanced Settings</h5>
                    
                    <div class="toggle-section">
                        <h6>Coupon Behavior</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_apply" name="auto_apply" value="1" {{ old('auto_apply') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_apply">
                                        Auto Apply
                                        <small class="text-muted d-block">Automatically apply this coupon if conditions are met</small>
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stackable" name="stackable" value="1" {{ old('stackable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stackable">
                                        Stackable
                                        <small class="text-muted d-block">Can be combined with other coupons</small>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping" value="1" {{ old('free_shipping') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="free_shipping">
                                        Free Shipping
                                        <small class="text-muted d-block">Provides free shipping in addition to discount</small>
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="first_order_only" name="first_order_only" value="1" {{ old('first_order_only') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="first_order_only">
                                        First Order Only
                                        <small class="text-muted d-block">Only valid for customer's first order</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority" class="form-label">Priority</label>
                                <input type="number" class="form-control" id="priority" name="priority" 
                                       value="{{ old('priority', 0) }}" min="0">
                                <div class="form-text">Higher priority coupons are applied first</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Coupon Preview -->
                <div class="preview-section">
                    <h5 class="mb-3"><i class="fas fa-eye"></i> Coupon Preview</h5>
                    <div class="coupon-preview">
                        <div class="coupon-code-preview" id="previewCode">COUPON-CODE</div>
                        <div class="coupon-desc-preview" id="previewDesc">Coupon Description</div>
                        <div class="coupon-desc-preview mt-2" id="previewDiscount">Discount Amount</div>
                    </div>
                </div>

                <!-- Status & Publishing -->
                <div class="form-section">
                    <h5 class="section-title"><i class="fas fa-toggle-on"></i> Status</h5>
                    
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Inactive coupons cannot be used by customers</div>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="form-group">
                        <label for="vendor_id" class="form-label">Vendor</label>
                        <select class="form-select" id="vendor_id" name="vendor_id">
                            <option value="">Global Coupon</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Leave empty for global coupon</div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="form-section">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Create Coupon
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log('jQuery loaded, initializing components...');
    
    // Initialize Select2
    $('#applicable_products, #exclude_products, #applicable_categories, #exclude_categories').select2({
        placeholder: "Select items...",
        allowClear: true
    });

    // Initialize date pickers
    const startDatePicker = $("#start_date_display").flatpickr({
        enableTime: true,
        dateFormat: "M j, Y h:i K", // User-friendly display format
        altInput: false,
        time_24hr: false,
        minDate: "today",
        allowInput: false,
        clickOpens: true,
        onOpen: function() {
            console.log('Start date picker opened');
        },
        onChange: function(selectedDates, dateStr) {
            console.log('Start date changed:', selectedDates, dateStr);
            // Update end date minimum when start date changes
            if (selectedDates.length > 0) {
                endDatePicker.set('minDate', selectedDates[0]);
                // Set the hidden input with proper Laravel format
                const laravelFormat = selectedDates[0].toISOString().slice(0, 19).replace('T', ' ');
                $('#start_date').val(laravelFormat);
            } else {
                $('#start_date').val('');
                endDatePicker.set('minDate', 'today');
            }
        }
    });

    const endDatePicker = $("#end_date_display").flatpickr({
        enableTime: true,
        dateFormat: "M j, Y h:i K", // User-friendly display format
        altInput: false,
        time_24hr: false,
        minDate: "today",
        allowInput: false,
        clickOpens: true,
        onOpen: function() {
            console.log('End date picker opened');
        },
        onChange: function(selectedDates, dateStr) {
            console.log('End date changed:', selectedDates, dateStr);
            if (selectedDates.length > 0) {
                // Set the hidden input with proper Laravel format
                const laravelFormat = selectedDates[0].toISOString().slice(0, 19).replace('T', ' ');
                $('#end_date').val(laravelFormat);
            } else {
                $('#end_date').val('');
            }
        }
    });

    // Initialize date fields with old values if they exist
    if ($('#start_date').val()) {
        const startDate = new Date($('#start_date').val());
        startDatePicker.setDate(startDate);
    }
    
    if ($('#end_date').val()) {
        const endDate = new Date($('#end_date').val());
        endDatePicker.setDate(endDate);
    }

    // Add clear buttons for date fields
    $('.input-group').each(function() {
        const input = $(this).find('input[type="text"]');
        if (input.attr('id') === 'start_date_display' || input.attr('id') === 'end_date_display') {
            $(this).append('<button type="button" class="btn btn-outline-secondary clear-date" title="Clear date"><i class="fas fa-times"></i></button>');
        }
    });

    // Handle clear date buttons
    $(document).on('click', '.clear-date', function() {
        const inputGroup = $(this).closest('.input-group');
        const displayInput = inputGroup.find('input[type="text"]');
        const fieldId = displayInput.attr('id');
        
        if (fieldId === 'start_date_display') {
            startDatePicker.clear();
            $('#start_date').val('');
            endDatePicker.set('minDate', 'today');
        } else if (fieldId === 'end_date_display') {
            endDatePicker.clear();
            $('#end_date').val('');
        }
    });

    // Handle discount type changes
    $('#type').change(function() {
        const type = $(this).val();
        const valueField = $('#valueField');
        const valuePrefix = $('#valuePrefix');
        const valueSuffix = $('#valueSuffix');
        const valueInput = $('#value');

        if (type === 'percentage') {
            valuePrefix.hide();
            valueSuffix.show();
            valueInput.attr('max', 100);
        } else if (type === 'fixed') {
            valuePrefix.show();
            valueSuffix.hide();
            valueInput.removeAttr('max');
        } else if (type === 'free_shipping') {
            valueField.hide();
            valueInput.removeAttr('required');
        } else {
            valueField.show();
            valueInput.attr('required', true);
            valuePrefix.show();
            valueSuffix.hide();
            valueInput.removeAttr('max');
        }
        updatePreview();
    });

    // Generate random coupon code
    $('#generateCode').click(function() {
        const code = 'COUPON-' + Math.random().toString(36).substring(2, 8).toUpperCase();
        $('#code').val(code);
        updatePreview();
    });

    // Update preview on input changes
    $('#code, #name, #description, #type, #value').on('input change', updatePreview);

    function updatePreview() {
        const code = $('#code').val() || 'COUPON-CODE';
        const name = $('#name').val() || 'Coupon Name';
        const description = $('#description').val() || 'Coupon Description';
        const type = $('#type').val();
        const value = $('#value').val();

        $('#previewCode').text(code);
        $('#previewDesc').text(description);

        let discountText = '';
        if (type && value) {
            switch (type) {
                case 'percentage':
                    discountText = value + '% OFF';
                    break;
                case 'fixed':
                    discountText = '$' + parseFloat(value).toFixed(2) + ' OFF';
                    break;
                case 'free_shipping':
                    discountText = 'FREE SHIPPING';
                    break;
                default:
                    discountText = 'Special Discount';
            }
        } else {
            discountText = 'Discount Amount';
        }
        $('#previewDiscount').text(discountText);
    }

    // Initialize preview
    updatePreview();

    // Form validation
    $('#couponForm').on('submit', function(e) {
        const type = $('#type').val();
        const value = $('#value').val();
        const startDate = $('#start_date').val();

        // Check if discount type requires a value
        if (type && type !== 'free_shipping' && !value) {
            e.preventDefault();
            alert('Please enter a discount value for the selected type.');
            return false;
        }

        // Check if start date is provided
        if (!startDate) {
            e.preventDefault();
            alert('Please select a start date for the coupon.');
            $('#start_date_display').focus();
            return false;
        }
    });
    
    console.log('All components initialized successfully');
});
</script>
@endpush
