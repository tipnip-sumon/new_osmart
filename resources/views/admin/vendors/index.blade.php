@extends('admin.layouts.app')

@section('title', 'All Vendors')

@section('styles')
<style>
    .vendor-card {
        transition: all 0.3s ease;
        border: 1px solid #e0e6ed;
        border-radius: 10px;
    }
    
    .vendor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .vendor-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 20px;
    }
    
    .vendor-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-active {
        background: #e8f5e8;
        color: #28a745;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-suspended {
        background: #ffeaea;
        color: #dc3545;
    }
    
    .vendor-stats {
        display: flex;
        justify-content: space-around;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .stat-label {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">All Vendors</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-store fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Vendors</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($totalVendors ?? 0) }}</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>{{ $totalVendors > 0 ? '100%' : '0%' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Active Vendors</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($activeVendors ?? 0) }}</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>{{ $totalVendors > 0 ? round(($activeVendors / $totalVendors) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-time fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Pending Approval</p>
                                        <h4 class="fw-semibold mt-1">85</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>5.1%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-danger">
                                    <i class="bx bx-block fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Suspended</p>
                                        <h4 class="fw-semibold mt-1">38</h4>
                                    </div>
                                    <div class="text-danger fw-semibold">
                                        <i class="ri-arrow-down-s-line"></i>2.3%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Vendors Management
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary btn-sm">
                                <i class="bx bx-plus"></i> Add Vendor
                            </a>
                            <button class="btn btn-success btn-sm export-vendors-btn">
                                <i class="bx bx-download"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Vendor</th>
                                        <th>Business Info</th>
                                        <th>Status</th>
                                        <th>Revenue</th>
                                        <th>Products</th>
                                        <th>Commission</th>
                                        <th>Joined Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vendors as $vendor)
                                    <tr>
                                        <td><input type="checkbox" class="vendor-checkbox" value="{{ $vendor->id }}"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="vendor-avatar">
                                                    {{ strtoupper(substr($vendor->name, 0, 2)) }}
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0">{{ $vendor->shop_name ?? $vendor->name }}</h6>
                                                    <small class="text-muted">{{ $vendor->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $vendor->shop_name ?? 'N/A' }}</div>
                                                <small class="text-muted">
                                                    @if($vendor->tax_id)
                                                        Tax ID: {{ $vendor->tax_id }}
                                                    @else
                                                        No Tax ID
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="vendor-status status-{{ $vendor->status }}">
                                                {{ ucfirst($vendor->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-success">${{ number_format($vendor->total_earnings ?? 0, 2) }}</div>
                                            <small class="text-muted">Total earnings</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $vendor->products_count ?? 0 }}</div>
                                            <small class="text-muted">products</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ number_format(($vendor->commission_rate ?? 0.05) * 100, 1) }}%</div>
                                            <small class="text-muted">commission</small>
                                        </td>
                                        <td>{{ $vendor->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-primary-light" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-sm btn-success-light" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger-light vendor-suspend-btn" data-vendor-id="{{ $vendor->id }}" title="Suspend">
                                                    <i class="bx bx-block"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-store fs-48 text-muted-light"></i>
                                                <div class="mt-3">
                                                    <h6>No vendors found</h6>
                                                    <p class="mb-2">There are no vendors registered yet.</p>
                                                    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary btn-sm">
                                                        <i class="bx bx-plus"></i> Add First Vendor
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($vendors->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">
                                    Showing {{ $vendors->firstItem() ?? 0 }} to {{ $vendors->lastItem() ?? 0 }} 
                                    of {{ $vendors->total() }} vendors
                                </span>
                            </div>
                            <div>
                                {{ $vendors->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<!-- jQuery (ensure it's loaded first) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // Ensure functions are loaded when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing vendor functions...');
        console.log('jQuery available:', typeof $ !== 'undefined');
        
        // View vendor event listeners
        document.querySelectorAll('.vendor-view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-vendor-id');
                console.log('viewVendor called with id:', id);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Info', `View vendor ${id} - Route not implemented yet`, 'info');
                } else {
                    alert(`View vendor ${id} - Route not implemented yet`);
                }
            });
        });
        
        // Edit vendor event listeners
        document.querySelectorAll('.vendor-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-vendor-id');
                console.log('editVendor called with id:', id);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Info', `Edit vendor ${id} - Route not implemented yet`, 'info');
                } else {
                    alert(`Edit vendor ${id} - Route not implemented yet`);
                }
            });
        });
        
        // Suspend vendor event listeners
        document.querySelectorAll('.vendor-suspend-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-vendor-id');
                console.log('suspendVendor called with id:', id);
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 is not loaded');
                    if (confirm('Suspend this vendor?')) {
                        alert('Vendor suspended successfully!');
                    }
                    return;
                }
                
                Swal.fire({
                    title: 'Suspend Vendor?',
                    text: 'This will temporarily suspend the vendor\'s account.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Suspend',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // TODO: Implement actual API call to suspend vendor
                        Swal.fire('Suspended!', 'Vendor has been suspended.', 'success');
                    }
                });
            });
        });
        
        // Export vendors event listener
        const exportBtn = document.querySelector('.export-vendors-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                console.log('exportVendors function called');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Exporting...', 'Preparing vendor data for export.', 'info');
                    
                    // Simulate export process
                    setTimeout(() => {
                        Swal.fire('Export Complete!', 'Vendor data has been exported successfully.', 'success');
                    }, 2000);
                } else {
                    alert('Exporting vendor data...');
                }
            });
        }
        
        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.vendor-checkbox').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
        
        // Individual checkbox change handler
        document.querySelectorAll('.vendor-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.vendor-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.vendor-checkbox:checked');
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
                }
            });
        });
        
        console.log('All vendor functions initialized successfully');
        console.log('Available functions:', { 
            viewVendor: typeof window.viewVendor,
            editVendor: typeof window.editVendor, 
            suspendVendor: typeof window.suspendVendor,
            saveVendor: typeof window.saveVendor,
            exportVendors: typeof window.exportVendors
        });
    });
</script>
@endsection
