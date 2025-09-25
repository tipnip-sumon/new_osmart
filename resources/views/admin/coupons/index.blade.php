@extends('admin.layouts.app')

@section('title', 'Coupon Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    .coupon-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 12px;
        opacity: 0.9;
    }
    .coupon-card {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .coupon-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .coupon-type {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .type-percentage { background: #e3f2fd; color: #1976d2; }
    .type-fixed { background: #f3e5f5; color: #7b1fa2; }
    .type-free_shipping { background: #e8f5e8; color: #388e3c; }
    .type-buy_x_get_y { background: #fff3e0; color: #f57c00; }
    .type-bulk_discount { background: #fce4ec; color: #c2185b; }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-active { background: #d4edda; color: #155724; }
    .status-expired { background: #f8d7da; color: #721c24; }
    .status-scheduled { background: #d1ecf1; color: #0c5460; }
    .status-inactive { background: #f6f6f6; color: #6c757d; }
    
    .filter-section {
        background: #f8f9fc;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .action-buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }
    
    .bulk-actions {
        background: #fff;
        padding: 15px;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
    }
    
    .progress-thin {
        height: 4px;
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags"></i> Coupon Management
        </h1>
        <div class="d-sm-flex">
            <button type="button" class="btn btn-success mr-2" id="exportBtn">
                <i class="fas fa-download"></i> Export
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                <i class="fas fa-plus"></i> Create Coupon
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="coupon-stats">
        <div class="row">
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Coupons</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['active'] }}</div>
                    <div class="stat-label">Active Coupons</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['expired'] }}</div>
                    <div class="stat-label">Expired</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['scheduled'] }}</div>
                    <div class="stat-label">Scheduled</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($stats['total_usage']) }}</div>
                    <div class="stat-label">Total Usage</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-number">${{ number_format($stats['total_discount'], 2) }}</div>
                    <div class="stat-label">Total Discount</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form id="filterForm" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by code or name...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="free_shipping" {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        <option value="buy_x_get_y" {{ request('type') == 'buy_x_get_y' ? 'selected' : '' }}>Buy X Get Y</option>
                        <option value="bulk_discount" {{ request('type') == 'bulk_discount' ? 'selected' : '' }}>Bulk Discount</option>
                    </select>
                </div>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="col-md-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-select" id="vendor_id" name="vendor_id">
                        <option value="">All Vendors</option>
                        <option value="0" {{ request('vendor_id') === '0' ? 'selected' : '' }}>Global Coupons</option>
                        <!-- Vendor options will be populated by JavaScript -->
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span id="selectedCount">0</span> coupon(s) selected
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                        <i class="fas fa-check"></i> Activate
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause"></i> Deactivate
                    </button>
                    <button type="button" class="btn btn-sm btn-info" onclick="bulkAction('duplicate')">
                        <i class="fas fa-copy"></i> Duplicate
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="showExtendModal()">
                        <i class="fas fa-calendar-plus"></i> Extend
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="couponsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="30">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th>Vendor</th>
                            <th>Valid Until</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input coupon-checkbox" 
                                       value="{{ $coupon->id }}">
                            </td>
                            <td>
                                <strong>{{ $coupon->code }}</strong>
                                @if($coupon->auto_apply)
                                    <span class="badge bg-info ms-1" title="Auto Apply">AUTO</span>
                                @endif
                                @if($coupon->stackable)
                                    <span class="badge bg-success ms-1" title="Stackable">STACK</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $coupon->name }}</div>
                                @if($coupon->description)
                                    <small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="coupon-type type-{{ $coupon->type }}">
                                    {{ $coupon->type_name }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $coupon->discount_text }}</strong>
                                @if($coupon->minimum_amount)
                                    <br><small class="text-muted">Min: ${{ $coupon->minimum_amount }}</small>
                                @endif
                                @if($coupon->maximum_discount)
                                    <br><small class="text-muted">Max: ${{ $coupon->maximum_discount }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        {{ $coupon->used_count }}
                                        @if($coupon->usage_limit)
                                            / {{ $coupon->usage_limit }}
                                        @else
                                            / ∞
                                        @endif
                                    </div>
                                </div>
                                @if($coupon->usage_limit)
                                    <div class="progress progress-thin mt-1">
                                        <div class="progress-bar" style="width: {{ $coupon->usage_percentage }}%"></div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ $coupon->status_slug }}">
                                    {{ $coupon->status_name }}
                                </span>
                            </td>
                            <td>
                                @if($coupon->vendor)
                                    <div>{{ $coupon->vendor->name }}</div>
                                    <small class="text-muted">{{ $coupon->vendor->email }}</small>
                                @else
                                    <span class="badge bg-primary">Global</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->end_date)
                                    {{ $coupon->end_date->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $coupon->end_date->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">Never expires</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="viewCoupon({{ $coupon->id }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(auth()->check() && (auth()->user()->role === 'admin' || $coupon->vendor_id === auth()->id()))
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editCoupon({{ $coupon->id }})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}" 
                                                onclick="toggleStatus({{ $coupon->id }})" 
                                                title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="duplicateCoupon({{ $coupon->id }})" title="Duplicate">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteCoupon({{ $coupon->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-tags fa-3x mb-3"></i>
                                    <p>No coupons found. <a href="#" data-bs-toggle="modal" data-bs-target="#createCouponModal">Create your first coupon</a></p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($coupons->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $coupons->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create/Edit Coupon Modal -->
<div class="modal fade" id="createCouponModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Create New Coupon
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="couponForm">
                <div class="modal-body">
                    <!-- Form content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extend Modal -->
<div class="modal fade" id="extendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus"></i> Extend Coupons
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="extendForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="extend_days" class="form-label">Extend by Days</label>
                        <input type="number" class="form-control" id="extend_days" name="extend_days" 
                               min="1" max="365" required>
                        <div class="form-text">Extend the expiry date by the specified number of days.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Extend
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#couponsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[8, 'desc']], // Order by Valid Until column
        columnDefs: [
            { orderable: false, targets: [0, 9] } // Disable ordering for checkbox and actions columns
        ]
    });

    // Select all functionality
    $('#selectAll').change(function() {
        $('.coupon-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    // Individual checkbox change
    $(document).on('change', '.coupon-checkbox', function() {
        updateBulkActions();
    });

    // Filter form auto-submit
    $('#search, #status, #type, #vendor_id').on('change keyup', debounce(function() {
        $('#filterForm').submit();
    }, 500));

    // Load create coupon form
    $('#createCouponModal').on('show.bs.modal', function() {
        loadCouponForm();
    });

    // Handle coupon form submission
    $(document).on('submit', '#couponForm', function(e) {
        e.preventDefault();
        saveCoupon();
    });

    // Handle extend form submission
    $('#extendForm').on('submit', function(e) {
        e.preventDefault();
        const days = $('#extend_days').val();
        bulkAction('extend', { extend_days: days });
        $('#extendModal').modal('hide');
    });

    // Export functionality
    $('#exportBtn').click(function() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', '1');
        window.location.href = '{{ route("admin.coupons.index") }}?' + params.toString();
    });
});

function updateBulkActions() {
    const checked = $('.coupon-checkbox:checked').length;
    $('#selectedCount').text(checked);
    
    if (checked > 0) {
        $('#bulkActions').show();
    } else {
        $('#bulkActions').hide();
    }
}

function loadCouponForm(couponId = null) {
    const isEdit = couponId !== null;
    const url = isEdit ? `/admin/coupons/${couponId}/edit` : '{{ route("admin.coupons.create") }}';
    const title = isEdit ? 'Edit Coupon' : 'Create New Coupon';
    
    $('#createCouponModal .modal-title').html(`<i class="fas fa-${isEdit ? 'edit' : 'plus'}"></i> ${title}`);
    
    // Load form content via AJAX
    $.get(url)
        .done(function(response) {
            $('#createCouponModal .modal-body').html(response);
            $('#createCouponModal').modal('show');
        })
        .fail(function() {
            showAlert('Failed to load coupon form', 'error');
        });
}

function saveCoupon() {
    const form = $('#couponForm');
    const formData = new FormData(form[0]);
    const couponId = form.find('input[name="coupon_id"]').val();
    const isEdit = couponId !== undefined && couponId !== '';
    
    const url = isEdit ? `/admin/coupons/${couponId}` : '{{ route("admin.coupons.store") }}';
    const method = isEdit ? 'PUT' : 'POST';
    
    if (isEdit) {
        formData.append('_method', 'PUT');
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                $('#createCouponModal').modal('hide');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function(xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                let errorMessage = 'Validation errors:<ul>';
                Object.values(errors).forEach(error => {
                    errorMessage += `<li>${error[0]}</li>`;
                });
                errorMessage += '</ul>';
                showAlert(errorMessage, 'error');
            } else {
                showAlert(xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        },
        complete: function() {
            form.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Save Coupon');
        }
    });
}

function viewCoupon(id) {
    window.location.href = `/admin/coupons/${id}`;
}

function editCoupon(id) {
    loadCouponForm(id);
}

function toggleStatus(id) {
    $.post(`/admin/coupons/${id}/toggle-status`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            showAlert(response.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert(response.message, 'error');
        }
    })
    .fail(function() {
        showAlert('Failed to update coupon status', 'error');
    });
}

function duplicateCoupon(id) {
    Swal.fire({
        title: 'Duplicate Coupon?',
        text: 'This will create a copy of the coupon with a new code.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, duplicate it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`/admin/coupons/${id}/duplicate`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert(response.message, 'error');
                }
            })
            .fail(function() {
                showAlert('Failed to duplicate coupon', 'error');
            });
        }
    });
}

function deleteCoupon(id) {
    Swal.fire({
        title: 'Delete Coupon?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/coupons/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function() {
                    showAlert('Failed to delete coupon', 'error');
                }
            });
        }
    });
}

function bulkAction(action, additionalData = {}) {
    const selectedIds = $('.coupon-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (selectedIds.length === 0) {
        showAlert('Please select at least one coupon', 'warning');
        return;
    }

    let confirmText = `Are you sure you want to ${action} ${selectedIds.length} coupon(s)?`;
    if (action === 'delete') {
        confirmText = `Are you sure you want to delete ${selectedIds.length} coupon(s)? This action cannot be undone!`;
    }

    Swal.fire({
        title: 'Confirm Action',
        text: confirmText,
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
        confirmButtonText: `Yes, ${action}!`
    }).then((result) => {
        if (result.isConfirmed) {
            const data = {
                action: action,
                coupon_ids: selectedIds,
                _token: $('meta[name="csrf-token"]').attr('content'),
                ...additionalData
            };

            $.post('{{ route("admin.coupons.bulk-action") }}', data)
            .done(function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert(response.message, 'error');
                }
            })
            .fail(function() {
                showAlert(`Failed to ${action} coupons`, 'error');
            });
        }
    });
}

function showExtendModal() {
    const selectedIds = $('.coupon-checkbox:checked').map(function() {
        return this.value;
    }).get();

    if (selectedIds.length === 0) {
        showAlert('Please select at least one coupon', 'warning');
        return;
    }

    $('#extendModal').modal('show');
}

function showAlert(message, type) {
    const icon = type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'error';
    Swal.fire({
        icon: icon,
        title: type.charAt(0).toUpperCase() + type.slice(1),
        html: message,
        timer: type === 'success' ? 3000 : undefined,
        showConfirmButton: type !== 'success'
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush
