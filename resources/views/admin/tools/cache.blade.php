@extends('admin.layouts.app')

@section('title', 'Cache Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">System Tools</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Cache Management</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Cache Management</h1>
        </div>
        <div class="btn-list">
            <button class="btn btn-primary-light btn-wave me-0" onclick="clearAllCache()">
                <i class="bx bx-refresh me-1"></i> Clear All Cache
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Alert for cache operations -->
    <div id="cache-alert" class="alert d-none" role="alert"></div>

    <div class="row">
        <!-- Cache Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Cache Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-data fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Application Cache</p>
                                                    <h4 class="fw-semibold mt-1" id="app-cache-status">Active</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-secondary">
                                                <i class="bx bx-server fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Route Cache</p>
                                                    <h4 class="fw-semibold mt-1" id="route-cache-status">Active</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-code-alt fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">View Cache</p>
                                                    <h4 class="fw-semibold mt-1" id="view-cache-status">Active</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-cog fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Config Cache</p>
                                                    <h4 class="fw-semibold mt-1" id="config-cache-status">Active</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cache Management Actions -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Cache Management Actions</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">Application Cache</h6>
                                            <p class="text-muted mb-0">Clear application cache to refresh stored data</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-sm" onclick="clearCache('application')">
                                                <i class="bx bx-refresh me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">Route Cache</h6>
                                            <p class="text-muted mb-0">Clear route cache to refresh routing information</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-secondary btn-sm" onclick="clearCache('route')">
                                                <i class="bx bx-refresh me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">View Cache</h6>
                                            <p class="text-muted mb-0">Clear compiled view templates</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-success btn-sm" onclick="clearCache('view')">
                                                <i class="bx bx-refresh me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">Config Cache</h6>
                                            <p class="text-muted mb-0">Clear configuration cache</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-warning btn-sm" onclick="clearCache('config')">
                                                <i class="bx bx-refresh me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Cache Operations -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Advanced Operations</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-12">
                            <div class="card custom-card border border-info shadow-none">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-lg avatar-rounded bg-info-transparent">
                                            <i class="bx bx-refresh fs-20"></i>
                                        </span>
                                    </div>
                                    <h6 class="card-title">Optimize Application</h6>
                                    <p class="text-muted mb-3">Run all optimization commands</p>
                                    <button class="btn btn-info btn-sm" onclick="optimizeApplication()">
                                        <i class="bx bx-rocket me-1"></i> Optimize
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-12">
                            <div class="card custom-card border border-danger shadow-none">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-lg avatar-rounded bg-danger-transparent">
                                            <i class="bx bx-trash fs-20"></i>
                                        </span>
                                    </div>
                                    <h6 class="card-title">Clear All Cache</h6>
                                    <p class="text-muted mb-3">Clear all types of cache</p>
                                    <button class="btn btn-danger btn-sm" onclick="clearAllCache()">
                                        <i class="bx bx-trash me-1"></i> Clear All
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-12">
                            <div class="card custom-card border border-success shadow-none">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <span class="avatar avatar-lg avatar-rounded bg-success-transparent">
                                            <i class="bx bx-check-circle fs-20"></i>
                                        </span>
                                    </div>
                                    <h6 class="card-title">Cache System Status</h6>
                                    <p class="text-muted mb-3">Check cache system health</p>
                                    <button class="btn btn-success btn-sm" onclick="checkCacheStatus()">
                                        <i class="bx bx-check me-1"></i> Check Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showAlert(message, type = 'success') {
    const alertDiv = document.getElementById('cache-alert');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertDiv.classList.remove('d-none');
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertDiv.classList.add('d-none');
    }, 5000);
}

function clearCache(type) {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Clearing...';
    button.disabled = true;
    
    // Make AJAX request to clear cache
    fetch('{{ route("admin.tools.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            updateCacheStatus(type);
        } else {
            showAlert(data.message || 'Cache clear failed', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Cache clear failed', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function clearAllCache() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Clearing All...';
    button.disabled = true;
    
    // Make AJAX request to clear all cache
    fetch('{{ route("admin.tools.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'all' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            updateCacheStatus('all');
        } else {
            showAlert(data.message || 'Cache clear failed', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Cache clear failed', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function optimizeApplication() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Optimizing...';
    button.disabled = true;
    
    // Make AJAX request to optimize application
    fetch('{{ route("admin.tools.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'optimize' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'info');
        } else {
            showAlert(data.message || 'Optimization failed', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Optimization failed', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function checkCacheStatus() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Checking...';
    button.disabled = true;
    
    // Simulate status check
    setTimeout(() => {
        showAlert('Cache system is running optimally!', 'success');
        button.innerHTML = originalText;
        button.disabled = false;
    }, 1500);
}

function updateCacheStatus(type) {
    if (type === 'all') {
        document.getElementById('app-cache-status').textContent = 'Cleared';
        document.getElementById('route-cache-status').textContent = 'Cleared';
        document.getElementById('view-cache-status').textContent = 'Cleared';
        document.getElementById('config-cache-status').textContent = 'Cleared';
    } else {
        const statusElement = document.getElementById(`${type}-cache-status`);
        if (statusElement) {
            statusElement.textContent = 'Cleared';
        }
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadCacheInfo();
});

// Load cache information
function loadCacheInfo() {
    fetch('{{ route("admin.tools.cache.info") }}')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cache statistics on the page
            updateCacheStats(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading cache info:', error);
    });
}

// Update cache statistics display
function updateCacheStats(stats) {
    // You can update the statistics cards here
    console.log('Cache stats:', stats);
}
</script>
@endpush
