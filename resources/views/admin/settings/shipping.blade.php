@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Shipping Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shipping</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Settings Navigation -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body p-0">
                    <div class="d-flex">
                        <nav class="nav nav-tabs flex-nowrap" id="settingsTabs" role="tablist">
                            <a class="nav-link" href="{{ route('admin.settings.general') }}">
                                <i class="ri-settings-line me-1"></i> General
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.payment') }}">
                                <i class="ri-bank-card-line me-1"></i> Payment
                            </a>
                            <a class="nav-link active" href="{{ route('admin.settings.shipping') }}">
                                <i class="ri-truck-line me-1"></i> Shipping
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.tax') }}">
                                <i class="ri-calculator-line me-1"></i> Tax
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.email') }}">
                                <i class="ri-mail-line me-1"></i> Email
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.shipping.update') }}" method="POST" id="shippingSettingsForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Shipping Settings -->
            <div class="col-xl-8">
                <!-- Shipping Options -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Shipping Options</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="free_shipping_enabled" name="free_shipping_enabled" value="1" 
                                            {{ old('free_shipping_enabled', $shippingSettings['free_shipping_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="free_shipping_enabled">
                                        Enable Free Shipping
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="free_shipping_minimum" class="form-label">Free Shipping Minimum</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('free_shipping_minimum') is-invalid @enderror" 
                                            id="free_shipping_minimum" name="free_shipping_minimum" value="{{ old('free_shipping_minimum', $shippingSettings['free_shipping_minimum']) }}" 
                                            placeholder="75.00" step="0.01" min="0">
                                    @error('free_shipping_minimum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="local_pickup_enabled" name="local_pickup_enabled" value="1" 
                                            {{ old('local_pickup_enabled', $shippingSettings['local_pickup_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="local_pickup_enabled">
                                        Local Pickup
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="same_day_delivery" name="same_day_delivery" value="1" 
                                            {{ old('same_day_delivery', $shippingSettings['same_day_delivery']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="same_day_delivery">
                                        Same Day Delivery
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="express_shipping" name="express_shipping" value="1" 
                                            {{ old('express_shipping', $shippingSettings['express_shipping']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="express_shipping">
                                        Express Shipping
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="international_shipping" name="international_shipping" value="1" 
                                            {{ old('international_shipping', $shippingSettings['international_shipping']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="international_shipping">
                                        International Shipping
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="flat_rate_shipping" class="form-label">Flat Rate Shipping</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('flat_rate_shipping') is-invalid @enderror" 
                                            id="flat_rate_shipping" name="flat_rate_shipping" value="{{ old('flat_rate_shipping', $shippingSettings['flat_rate_shipping']) }}" 
                                            placeholder="9.99" step="0.01" min="0">
                                    @error('flat_rate_shipping')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Providers -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Shipping Providers</div>
                    </div>
                    <div class="card-body">
                        <!-- UPS -->
                        <div class="provider-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/UPS_logo.svg/320px-UPS_logo.svg.png" alt="UPS" style="height: 30px;" class="me-3">
                                    <h6 class="mb-0">UPS</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ups_enabled" name="ups_enabled" value="1" 
                                            {{ old('ups_enabled', $shippingSettings['ups_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ups_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="ups_settings" style="{{ old('ups_enabled', $shippingSettings['ups_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-12 mb-3">
                                    <label for="ups_api_key" class="form-label">UPS API Key</label>
                                    <input type="text" class="form-control @error('ups_api_key') is-invalid @enderror" 
                                            id="ups_api_key" name="ups_api_key" value="{{ old('ups_api_key', $shippingSettings['ups_api_key']) }}" 
                                            placeholder="Enter UPS API Key">
                                    @error('ups_api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- FedEx -->
                        <div class="provider-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/FedEx_Express.svg/320px-FedEx_Express.svg.png" alt="FedEx" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">FedEx</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="fedex_enabled" name="fedex_enabled" value="1" 
                                            {{ old('fedex_enabled', $shippingSettings['fedex_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fedex_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="fedex_settings" style="{{ old('fedex_enabled', $shippingSettings['fedex_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-12 mb-3">
                                    <label for="fedex_api_key" class="form-label">FedEx API Key</label>
                                    <input type="text" class="form-control @error('fedex_api_key') is-invalid @enderror" 
                                            id="fedex_api_key" name="fedex_api_key" value="{{ old('fedex_api_key', $shippingSettings['fedex_api_key']) }}" 
                                            placeholder="Enter FedEx API Key">
                                    @error('fedex_api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- USPS -->
                        <div class="provider-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/USPS_Logo.svg/320px-USPS_Logo.svg.png" alt="USPS" style="height: 30px;" class="me-3">
                                    <h6 class="mb-0">USPS</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="usps_enabled" name="usps_enabled" value="1" 
                                            {{ old('usps_enabled', $shippingSettings['usps_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="usps_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="usps_settings" style="{{ old('usps_enabled', $shippingSettings['usps_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-12 mb-3">
                                    <label for="usps_api_key" class="form-label">USPS API Key</label>
                                    <input type="text" class="form-control @error('usps_api_key') is-invalid @enderror" 
                                            id="usps_api_key" name="usps_api_key" value="{{ old('usps_api_key', $shippingSettings['usps_api_key']) }}" 
                                            placeholder="Enter USPS API Key">
                                    @error('usps_api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Processing Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Processing Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="processing_time" class="form-label">Processing Time</label>
                                <input type="text" class="form-control @error('processing_time') is-invalid @enderror" 
                                        id="processing_time" name="processing_time" value="{{ old('processing_time', $shippingSettings['processing_time']) }}" 
                                        placeholder="1-2 business days">
                                @error('processing_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cutoff_time" class="form-label">Daily Cutoff Time</label>
                                <input type="time" class="form-control @error('cutoff_time') is-invalid @enderror" 
                                        id="cutoff_time" name="cutoff_time" value="{{ old('cutoff_time', $shippingSettings['cutoff_time']) }}">
                                @error('cutoff_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Orders placed after this time will be processed the next business day</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="weekend_processing" name="weekend_processing" value="1" 
                                            {{ old('weekend_processing', $shippingSettings['weekend_processing']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="weekend_processing">
                                        Weekend Processing
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="holiday_processing" name="holiday_processing" value="1" 
                                            {{ old('holiday_processing', $shippingSettings['holiday_processing']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="holiday_processing">
                                        Holiday Processing
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-xl-4">
                <!-- Packaging Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Packaging Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="default_package_weight" class="form-label">Default Package Weight (lbs)</label>
                            <input type="number" class="form-control @error('default_package_weight') is-invalid @enderror" 
                                    id="default_package_weight" name="default_package_weight" value="{{ old('default_package_weight', $shippingSettings['default_package_weight']) }}" 
                                    placeholder="1.0" step="0.1" min="0">
                            @error('default_package_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="default_package_dimensions" class="form-label">Default Dimensions (L×W×H)</label>
                            <input type="text" class="form-control @error('default_package_dimensions') is-invalid @enderror" 
                                    id="default_package_dimensions" name="default_package_dimensions" value="{{ old('default_package_dimensions', $shippingSettings['default_package_dimensions']) }}" 
                                    placeholder="12x12x6 inches">
                            @error('default_package_dimensions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="signature_required" name="signature_required" value="1" 
                                    {{ old('signature_required', $shippingSettings['signature_required']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="signature_required">
                                Signature Required
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="insurance_enabled" name="insurance_enabled" value="1" 
                                    {{ old('insurance_enabled', $shippingSettings['insurance_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="insurance_enabled">
                                Shipping Insurance
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="tracking_enabled" name="tracking_enabled" value="1" 
                                    {{ old('tracking_enabled', $shippingSettings['tracking_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tracking_enabled">
                                Tracking Enabled
                            </label>
                        </div>
                    </div>
                </div>

                <!-- MLM Shipping Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Shipping Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="drop_shipping_enabled" name="drop_shipping_enabled" value="1" 
                                    {{ old('drop_shipping_enabled', $shippingSettings['drop_shipping_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="drop_shipping_enabled">
                                Drop Shipping Enabled
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="vendor_shipping_enabled" name="vendor_shipping_enabled" value="1" 
                                    {{ old('vendor_shipping_enabled', $shippingSettings['vendor_shipping_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="vendor_shipping_enabled">
                                Vendor Shipping
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="split_shipping" name="split_shipping" value="1" 
                                    {{ old('split_shipping', $shippingSettings['split_shipping']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="split_shipping">
                                Split Shipping
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_commission" class="form-label">Shipping Commission (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('shipping_commission') is-invalid @enderror" 
                                        id="shipping_commission" name="shipping_commission" value="{{ old('shipping_commission', $shippingSettings['shipping_commission']) }}" 
                                        placeholder="5.0" step="0.1" min="0" max="100">
                                <span class="input-group-text">%</span>
                                @error('shipping_commission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- International Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">International Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="customs_forms" name="customs_forms" value="1" 
                                    {{ old('customs_forms', $shippingSettings['customs_forms']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="customs_forms">
                                Generate Customs Forms
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="duty_taxes_notice" class="form-label">Duty & Taxes Notice</label>
                            <textarea class="form-control @error('duty_taxes_notice') is-invalid @enderror" 
                                        id="duty_taxes_notice" name="duty_taxes_notice" rows="3" 
                                        placeholder="Notice about duties and taxes">{{ old('duty_taxes_notice', $shippingSettings['duty_taxes_notice']) }}</textarea>
                            @error('duty_taxes_notice')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="restricted_countries" class="form-label">Restricted Countries</label>
                            <input type="text" class="form-control @error('restricted_countries') is-invalid @enderror" 
                                    id="restricted_countries" name="restricted_countries" value="{{ old('restricted_countries', implode(', ', $shippingSettings['restricted_countries'])) }}" 
                                    placeholder="US, CA, GB">
                            @error('restricted_countries')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Enter country codes separated by commas</div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Shipping Providers Status</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">UPS:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">FedEx:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">USPS:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">DHL:</span>
                            <span class="badge bg-secondary">Inactive</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body d-flex justify-content-between">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </button>
                            <button type="button" class="btn btn-info" onclick="testShippingRates()">
                                <i class="ri-test-tube-line me-1"></i> Test Rates
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Shipping Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle provider settings based on enable/disable
    document.addEventListener('DOMContentLoaded', function() {
        const providers = ['ups', 'fedex', 'usps'];
        
        providers.forEach(function(provider) {
            const enabledCheckbox = document.getElementById(provider + '_enabled');
            const settingsDiv = document.getElementById(provider + '_settings');
            
            if (enabledCheckbox && settingsDiv) {
                enabledCheckbox.addEventListener('change', function() {
                    settingsDiv.style.display = this.checked ? '' : 'none';
                });
            }
        });
    });

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will reload the page with original values.')) {
            location.reload();
        }
    }

    // Test shipping rates
    function testShippingRates() {
        alert('Shipping rate testing functionality would verify connections to all enabled providers.');
    }
</script>
@endpush
@endsection
