@extends('admin.layouts.app')

@section('title', 'Tax Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Tax Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tax</li>
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
                            <a class="nav-link" href="{{ route('admin.settings.shipping') }}">
                                <i class="ri-truck-line me-1"></i> Shipping
                            </a>
                            <a class="nav-link active" href="{{ route('admin.settings.tax') }}">
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

    <form action="{{ route('admin.settings.tax.update') }}" method="POST" id="taxSettingsForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Tax Settings -->
            <div class="col-xl-8">
                <!-- Tax Configuration -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Tax Configuration</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="tax_enabled" name="tax_enabled" value="1" 
                                            {{ old('tax_enabled', $taxSettings['tax_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tax_enabled">
                                        Enable Tax Calculation
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="prices_include_tax" name="prices_include_tax" value="1" 
                                            {{ old('prices_include_tax', $taxSettings['prices_include_tax']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="prices_include_tax">
                                        Prices Include Tax
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="default_tax_rate" class="form-label">Default Tax Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('default_tax_rate') is-invalid @enderror" 
                                            id="default_tax_rate" name="default_tax_rate" value="{{ old('default_tax_rate', $taxSettings['default_tax_rate']) }}" 
                                            placeholder="8.25" step="0.01" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                    @error('default_tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax_calculation_method" class="form-label">Tax Calculation Method</label>
                                <select class="form-select @error('tax_calculation_method') is-invalid @enderror" 
                                        id="tax_calculation_method" name="tax_calculation_method">
                                    <option value="per_line" {{ old('tax_calculation_method', $taxSettings['tax_calculation_method']) == 'per_line' ? 'selected' : '' }}>Per Line Item</option>
                                    <option value="total" {{ old('tax_calculation_method', $taxSettings['tax_calculation_method']) == 'total' ? 'selected' : '' }}>On Total</option>
                                </select>
                                @error('tax_calculation_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="tax_on_shipping" name="tax_on_shipping" value="1" 
                                            {{ old('tax_on_shipping', $taxSettings['tax_on_shipping']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tax_on_shipping">
                                        Apply Tax on Shipping
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="compound_tax" name="compound_tax" value="1" 
                                            {{ old('compound_tax', $taxSettings['compound_tax']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="compound_tax">
                                        Compound Tax
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="tax_rounding" name="tax_rounding" value="1" 
                                            {{ old('tax_rounding', $taxSettings['tax_rounding']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tax_rounding">
                                        Round Tax Calculations
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax_address_calculation" class="form-label">Tax Address Based On</label>
                                <select class="form-select @error('tax_address_calculation') is-invalid @enderror" 
                                        id="tax_address_calculation" name="tax_address_calculation">
                                    <option value="billing" {{ old('tax_address_calculation', $taxSettings['tax_address_calculation']) == 'billing' ? 'selected' : '' }}>Billing Address</option>
                                    <option value="shipping" {{ old('tax_address_calculation', $taxSettings['tax_address_calculation']) == 'shipping' ? 'selected' : '' }}>Shipping Address</option>
                                    <option value="store" {{ old('tax_address_calculation', $taxSettings['tax_address_calculation']) == 'store' ? 'selected' : '' }}>Store Address</option>
                                </select>
                                @error('tax_address_calculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Classes -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Tax Classes</div>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addTaxClass()">
                            <i class="ri-add-line me-1"></i> Add Tax Class
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tax-classes-container">
                            <!-- Standard Tax Class -->
                            <div class="tax-class-item border rounded p-3 mb-3" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Class Name</label>
                                            <input type="text" class="form-control" name="tax_classes[0][name]" 
                                                    value="{{ old('tax_classes.0.name', 'Standard') }}" placeholder="Standard">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Rate (%)</label>
                                            <input type="number" class="form-control" name="tax_classes[0][rate]" 
                                                    value="{{ old('tax_classes.0.rate', '8.25') }}" placeholder="8.25" step="0.01" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Priority</label>
                                            <input type="number" class="form-control" name="tax_classes[0][priority]" 
                                                    value="{{ old('tax_classes.0.priority', '1') }}" placeholder="1" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="tax_classes[0][enabled]" value="1" checked>
                                                <label class="form-check-label">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reduced Rate Tax Class -->
                            <div class="tax-class-item border rounded p-3 mb-3" data-index="1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Class Name</label>
                                            <input type="text" class="form-control" name="tax_classes[1][name]" 
                                                    value="{{ old('tax_classes.1.name', 'Reduced Rate') }}" placeholder="Reduced Rate">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Rate (%)</label>
                                            <input type="number" class="form-control" name="tax_classes[1][rate]" 
                                                    value="{{ old('tax_classes.1.rate', '5.50') }}" placeholder="5.50" step="0.01" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Priority</label>
                                            <input type="number" class="form-control" name="tax_classes[1][priority]" 
                                                    value="{{ old('tax_classes.1.priority', '2') }}" placeholder="2" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="tax_classes[1][enabled]" value="1" checked>
                                                    <label class="form-check-label">Active</label>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeTaxClass(this)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Zero Rate Tax Class -->
                            <div class="tax-class-item border rounded p-3 mb-3" data-index="2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Class Name</label>
                                            <input type="text" class="form-control" name="tax_classes[2][name]" 
                                                    value="{{ old('tax_classes.2.name', 'Zero Rate') }}" placeholder="Zero Rate">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Rate (%)</label>
                                            <input type="number" class="form-control" name="tax_classes[2][rate]" 
                                                    value="{{ old('tax_classes.2.rate', '0.00') }}" placeholder="0.00" step="0.01" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Priority</label>
                                            <input type="number" class="form-control" name="tax_classes[2][priority]" 
                                                    value="{{ old('tax_classes.2.priority', '3') }}" placeholder="3" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="tax_classes[2][enabled]" value="1" checked>
                                                    <label class="form-check-label">Active</label>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeTaxClass(this)">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Reports Configuration -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Tax Reports Configuration</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tax_report_frequency" class="form-label">Report Frequency</label>
                                <select class="form-select @error('tax_report_frequency') is-invalid @enderror" 
                                        id="tax_report_frequency" name="tax_report_frequency">
                                    <option value="monthly" {{ old('tax_report_frequency', $taxSettings['tax_report_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('tax_report_frequency', $taxSettings['tax_report_frequency']) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="annually" {{ old('tax_report_frequency', $taxSettings['tax_report_frequency']) == 'annually' ? 'selected' : '' }}>Annually</option>
                                </select>
                                @error('tax_report_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tax_fiscal_year_start" class="form-label">Fiscal Year Start</label>
                                <select class="form-select @error('tax_fiscal_year_start') is-invalid @enderror" 
                                        id="tax_fiscal_year_start" name="tax_fiscal_year_start">
                                    <option value="january" {{ old('tax_fiscal_year_start', $taxSettings['tax_fiscal_year_start']) == 'january' ? 'selected' : '' }}>January</option>
                                    <option value="april" {{ old('tax_fiscal_year_start', $taxSettings['tax_fiscal_year_start']) == 'april' ? 'selected' : '' }}>April</option>
                                    <option value="july" {{ old('tax_fiscal_year_start', $taxSettings['tax_fiscal_year_start']) == 'july' ? 'selected' : '' }}>July</option>
                                    <option value="october" {{ old('tax_fiscal_year_start', $taxSettings['tax_fiscal_year_start']) == 'october' ? 'selected' : '' }}>October</option>
                                </select>
                                @error('tax_fiscal_year_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_tax_reports" name="auto_tax_reports" value="1" 
                                            {{ old('auto_tax_reports', $taxSettings['auto_tax_reports']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_tax_reports">
                                        Auto Generate Reports
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="email_tax_reports" name="email_tax_reports" value="1" 
                                            {{ old('email_tax_reports', $taxSettings['email_tax_reports']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_tax_reports">
                                        Email Reports
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-xl-4">
                <!-- Tax Zones -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Tax Zones</div>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addTaxZone()">
                            <i class="ri-add-line me-1"></i> Add Zone
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="tax-zones-container">
                            <div class="tax-zone-item mb-3 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Zone Name</label>
                                    <input type="text" class="form-control" name="tax_zones[0][name]" value="US - California" placeholder="Zone Name">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Countries/States</label>
                                    <input type="text" class="form-control" name="tax_zones[0][locations]" value="US-CA" placeholder="US-CA, US-NY">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" name="tax_zones[0][rate]" value="8.25" step="0.01" min="0" max="100">
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="tax_zones[0][enabled]" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>

                            <div class="tax-zone-item mb-3 p-3 border rounded">
                                <div class="mb-2">
                                    <label class="form-label">Zone Name</label>
                                    <input type="text" class="form-control" name="tax_zones[1][name]" value="EU VAT" placeholder="Zone Name">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Countries/States</label>
                                    <input type="text" class="form-control" name="tax_zones[1][locations]" value="DE, FR, IT, ES" placeholder="DE, FR, IT">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" name="tax_zones[1][rate]" value="19.00" step="0.01" min="0" max="100">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="tax_zones[1][enabled]" value="1" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeTaxZone(this)">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MLM Tax Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Tax Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="commission_tax" name="commission_tax" value="1" 
                                    {{ old('commission_tax', $taxSettings['commission_tax']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="commission_tax">
                                Tax on Commissions
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="bonus_tax" name="bonus_tax" value="1" 
                                    {{ old('bonus_tax', $taxSettings['bonus_tax']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="bonus_tax">
                                Tax on Bonuses
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="vendor_tax_class" class="form-label">Vendor Tax Class</label>
                            <select class="form-select @error('vendor_tax_class') is-invalid @enderror" 
                                    id="vendor_tax_class" name="vendor_tax_class">
                                <option value="standard" {{ old('vendor_tax_class', $taxSettings['vendor_tax_class']) == 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="reduced" {{ old('vendor_tax_class', $taxSettings['vendor_tax_class']) == 'reduced' ? 'selected' : '' }}>Reduced Rate</option>
                                <option value="zero" {{ old('vendor_tax_class', $taxSettings['vendor_tax_class']) == 'zero' ? 'selected' : '' }}>Zero Rate</option>
                            </select>
                            @error('vendor_tax_class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="vendor_tax_exempt" name="vendor_tax_exempt" value="1" 
                                    {{ old('vendor_tax_exempt', $taxSettings['vendor_tax_exempt']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="vendor_tax_exempt">
                                Vendor Tax Exemption
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Digital Products Tax -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Digital Products Tax</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="digital_tax_enabled" name="digital_tax_enabled" value="1" 
                                    {{ old('digital_tax_enabled', $taxSettings['digital_tax_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="digital_tax_enabled">
                                Tax Digital Products
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="digital_tax_rate" class="form-label">Digital Tax Rate (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('digital_tax_rate') is-invalid @enderror" 
                                        id="digital_tax_rate" name="digital_tax_rate" value="{{ old('digital_tax_rate', $taxSettings['digital_tax_rate']) }}" 
                                        placeholder="15.0" step="0.01" min="0" max="100">
                                <span class="input-group-text">%</span>
                                @error('digital_tax_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="eu_vat_moss" name="eu_vat_moss" value="1" 
                                    {{ old('eu_vat_moss', $taxSettings['eu_vat_moss']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="eu_vat_moss">
                                EU VAT MOSS
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tax Calculation Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Tax System Status</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tax Engine:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tax Classes:</span>
                            <span class="badge bg-primary">3 Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tax Zones:</span>
                            <span class="badge bg-primary">2 Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Last Calculation:</span>
                            <span class="text-muted">2 min ago</span>
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
                            <button type="button" class="btn btn-info" onclick="generateTaxReport()">
                                <i class="ri-file-text-line me-1"></i> Generate Report
                            </button>
                            <button type="button" class="btn btn-warning" onclick="calculateTaxes()">
                                <i class="ri-calculator-line me-1"></i> Test Calculation
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Tax Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let taxClassIndex = 3;
    let taxZoneIndex = 2;

    // Add new tax class
    function addTaxClass() {
        const container = document.getElementById('tax-classes-container');
        const newTaxClass = `
            <div class="tax-class-item border rounded p-3 mb-3" data-index="${taxClassIndex}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" class="form-control" name="tax_classes[${taxClassIndex}][name]" placeholder="Class Name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Rate (%)</label>
                            <input type="number" class="form-control" name="tax_classes[${taxClassIndex}][rate]" placeholder="0.00" step="0.01" min="0" max="100">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" class="form-control" name="tax_classes[${taxClassIndex}][priority]" placeholder="1" min="1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="tax_classes[${taxClassIndex}][enabled]" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeTaxClass(this)">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newTaxClass);
        taxClassIndex++;
    }

    // Remove tax class
    function removeTaxClass(button) {
        button.closest('.tax-class-item').remove();
    }

    // Add new tax zone
    function addTaxZone() {
        const container = document.getElementById('tax-zones-container');
        const newTaxZone = `
            <div class="tax-zone-item mb-3 p-3 border rounded">
                <div class="mb-2">
                    <label class="form-label">Zone Name</label>
                    <input type="text" class="form-control" name="tax_zones[${taxZoneIndex}][name]" placeholder="Zone Name">
                </div>
                <div class="mb-2">
                    <label class="form-label">Countries/States</label>
                    <input type="text" class="form-control" name="tax_zones[${taxZoneIndex}][locations]" placeholder="US-CA, US-NY">
                </div>
                <div class="mb-2">
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" class="form-control" name="tax_zones[${taxZoneIndex}][rate]" step="0.01" min="0" max="100">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="tax_zones[${taxZoneIndex}][enabled]" value="1" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeTaxZone(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newTaxZone);
        taxZoneIndex++;
    }

    // Remove tax zone
    function removeTaxZone(button) {
        button.closest('.tax-zone-item').remove();
    }

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will reload the page with original values.')) {
            location.reload();
        }
    }

    // Generate tax report
    function generateTaxReport() {
        alert('Tax report generation functionality would create detailed tax reports based on current settings.');
    }

    // Test tax calculation
    function calculateTaxes() {
        alert('Tax calculation test would verify tax calculations using current rates and rules.');
    }
</script>
@endpush
@endsection
