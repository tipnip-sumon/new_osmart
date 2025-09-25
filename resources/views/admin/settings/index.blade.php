@extends('admin.layouts.app')

@section('title', 'Settings Overview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Settings Overview</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Settings Cards -->
    <div class="row">
        <!-- General Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-primary-transparent">
                                    <i class="ri-settings-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">General Settings</h5>
                            <p class="text-muted mb-3">Configure basic site information, company details, regional settings, and SEO options.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Configuration</span>
                                    <span class="badge bg-success">Complete</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-primary btn-sm">
                                <i class="ri-settings-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-success-transparent">
                                    <i class="ri-bank-card-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">Payment Settings</h5>
                            <p class="text-muted mb-3">Setup payment gateways, configure commission payouts, and manage transaction settings.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Payment Gateways</span>
                                    <span class="badge bg-warning">3 Active</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.payment') }}" class="btn btn-success btn-sm">
                                <i class="ri-bank-card-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-info-transparent">
                                    <i class="ri-truck-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">Shipping Settings</h5>
                            <p class="text-muted mb-3">Configure shipping methods, rates, zones, and delivery options for your store.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Shipping Methods</span>
                                    <span class="badge bg-info">5 Available</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.shipping') }}" class="btn btn-info btn-sm">
                                <i class="ri-truck-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-warning-transparent">
                                    <i class="ri-calculator-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">Tax Settings</h5>
                            <p class="text-muted mb-3">Setup tax rates, configure tax classes, and manage regional tax requirements.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Tax Classes</span>
                                    <span class="badge bg-warning">Standard Rate</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.tax') }}" class="btn btn-warning btn-sm">
                                <i class="ri-calculator-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-secondary-transparent">
                                    <i class="ri-mail-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">Email Settings</h5>
                            <p class="text-muted mb-3">Configure SMTP settings, email templates, and notification preferences.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Email Templates</span>
                                    <span class="badge bg-secondary">12 Active</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.email') }}" class="btn btn-secondary btn-sm">
                                <i class="ri-mail-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MLM Settings -->
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card settings-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="mb-3">
                                <span class="avatar avatar-lg bg-purple-transparent">
                                    <i class="ri-team-line fs-24"></i>
                                </span>
                            </div>
                            <h5 class="fw-semibold mb-2">MLM Settings</h5>
                            <p class="text-muted mb-3">Configure commission structure, genealogy settings, and MLM-specific features.</p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted fs-12">Commission Plan</span>
                                    <span class="badge bg-purple">Binary Tree</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-purple btn-sm">
                                <i class="ri-team-line me-1"></i> Configure
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" onclick="backupSettings()">
                                    <i class="ri-download-line me-2"></i> Backup Settings
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-success" onclick="restoreSettings()">
                                    <i class="ri-upload-line me-2"></i> Restore Settings
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info" onclick="exportSettings()">
                                    <i class="ri-file-export-line me-2"></i> Export Config
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-warning" onclick="resetToDefaults()">
                                    <i class="ri-refresh-line me-2"></i> Reset to Defaults
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Configuration Summary -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Current Configuration Summary</div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Site Information -->
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Site Information</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="text-muted" width="40%">Site Name:</td>
                                        <td>MLM Ecommerce Platform</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Default Currency:</td>
                                        <td><span class="badge bg-light text-dark">USD ($)</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Timezone:</td>
                                        <td>America/New_York (UTC-5)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Language:</td>
                                        <td>English (en)</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Features Status -->
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Features Status</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-success">User Registration</span>
                                <span class="badge bg-success">MLM System</span>
                                <span class="badge bg-success">Wishlist</span>
                                <span class="badge bg-success">Reviews</span>
                                <span class="badge bg-warning">Maintenance Mode</span>
                                <span class="badge bg-info">Newsletter</span>
                            </div>
                            
                            <h6 class="fw-semibold mb-3 mt-4">Payment Gateways</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-success">PayPal</span>
                                <span class="badge bg-success">Stripe</span>
                                <span class="badge bg-secondary">Razorpay</span>
                                <span class="badge bg-success">Bank Transfer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function backupSettings() {
        if (confirm('Create a backup of current settings?')) {
            // In a real application, this would create a settings backup
            alert('Settings backup created successfully!');
        }
    }

    function restoreSettings() {
        if (confirm('Restore settings from backup? This will overwrite current settings.')) {
            // In a real application, this would restore from backup
            alert('Settings restored successfully!');
        }
    }

    function exportSettings() {
        // In a real application, this would export settings as JSON/XML
        alert('Settings exported successfully!');
    }

    function resetToDefaults() {
        if (confirm('Reset all settings to default values? This action cannot be undone.')) {
            // In a real application, this would reset to default settings
            alert('Settings reset to defaults successfully!');
        }
    }
</script>

<style>
.settings-card {
    transition: transform 0.2s ease-in-out;
}

.settings-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}

.btn-purple:hover {
    background-color: #5a359b;
    border-color: #5a359b;
    color: white;
}

.bg-purple-transparent {
    background-color: rgba(111, 66, 193, 0.1);
    color: #6f42c1;
}

.badge.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
@endpush
@endsection
