@extends('admin.layouts.app')

@section('title', 'Payment Methods')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
<style>
.payment-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 6px;
}
.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.currency-badge {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.7rem;
}
.method-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}
.method-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}
.stats-icon {
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@section('content')
<div class="d-sm-flex d-block align-items-center justify-content-between page-header-breadcrumb">
    <h4 class="fw-medium mb-0">Payment Methods</h4>
    <div class="ms-sm-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment Methods</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-credit-card fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $totalMethods ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Total Methods</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-check-circle fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $activeMethods ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Active Methods</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-globe fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $supportedCurrencies ?? 0 }}</h3>
                        <p class="mb-0 opacity-8">Currencies</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon me-3">
                        <i class="ti ti-wallet fs-20"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">${{ number_format($totalProcessed ?? 0, 2) }}</h3>
                        <p class="mb-0 opacity-8">Total Processed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods Management -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Payment Methods Management</div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                        <i class="ti ti-plus me-1"></i>Add Payment Method
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}">All Methods</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">Active</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}">Inactive</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'gateway']) }}">Payment Gateways</a></li>
                            <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['type' => 'manual']) }}">Manual Methods</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap w-100" id="paymentMethodsTable">
                        <thead>
                            <tr>
                                <th>
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </th>
                                <th>Method</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Processing Fee</th>
                                <th>Currencies</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <input class="form-check-input row-checkbox" type="checkbox" value="{{ $payment->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->logo)
                                        <img src="{{ Storage::url($payment->logo) }}" alt="{{ $payment->name }}" class="payment-logo me-2">
                                        @else
                                        <div class="avatar avatar-sm avatar-rounded me-2">
                                            <i class="ti ti-credit-card fs-16"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $payment->name }}</h6>
                                            <small class="text-muted">{{ $payment->gateway_name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->type === 'automatic' ? 'success' : 'warning' }}-transparent">
                                        {{ ucfirst($payment->type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="{{ $payment->id }}" 
                                               {{ $payment->is_active ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    @if($payment->fixed_fee > 0 || $payment->percentage_fee > 0)
                                        <div class="d-flex flex-column">
                                            @if($payment->fixed_fee > 0)
                                            <small>${{ number_format($payment->fixed_fee, 2) }} fixed</small>
                                            @endif
                                            @if($payment->percentage_fee > 0)
                                            <small>{{ $payment->percentage_fee }}% rate</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Free</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->supported_currencies)
                                        @foreach(array_slice($payment->supported_currencies, 0, 3) as $currency)
                                        <span class="badge currency-badge me-1">{{ $currency }}</span>
                                        @endforeach
                                        @if(count($payment->supported_currencies) > 3)
                                        <span class="badge bg-light text-dark">+{{ count($payment->supported_currencies) - 3 }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">All</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $payment->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light" onclick="editPaymentMethod({{ $payment->id }})" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light" onclick="viewPaymentMethod({{ $payment->id }})" title="View">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger" onclick="deletePaymentMethod({{ $payment->id }})" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-credit-card fs-48 text-muted mb-3"></i>
                                        <h6 class="text-muted">No payment methods found</h6>
                                        <p class="text-muted mb-3">Get started by adding your first payment method</p>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                                            <i class="ti ti-plus me-1"></i>Add Payment Method
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payments->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <p class="text-muted mb-0">
                            Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
                        </p>
                    </div>
                    <div>
                        {{ $payments->withQueryString()->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Payment Method</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPaymentMethodForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Method Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Gateway Name</label>
                                <input type="text" class="form-control" name="gateway_name">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="automatic">Automatic (Gateway)</option>
                                    <option value="manual">Manual Method</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Fixed Fee ($)</label>
                                <input type="number" class="form-control" name="fixed_fee" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Percentage Fee (%)</label>
                                <input type="number" class="form-control" name="percentage_fee" step="0.01" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Supported Currencies</label>
                                <select class="form-select" name="supported_currencies[]" multiple>
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                    <option value="AUD">AUD - Australian Dollar</option>
                                    <option value="JPY">JPY - Japanese Yen</option>
                                    <option value="INR">INR - Indian Rupee</option>
                                </select>
                                <small class="text-muted">Leave empty to support all currencies</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Configuration (JSON)</label>
                                <textarea class="form-control" name="configuration" rows="4" placeholder='{"api_key": "", "secret_key": "", "sandbox": true}'></textarea>
                                <small class="text-muted">Gateway configuration in JSON format</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Instructions</label>
                                <textarea class="form-control" name="instructions" rows="3" placeholder="Payment instructions for customers..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Save Payment Method
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin-assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#paymentMethodsTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ],
        language: {
            search: "Search methods:",
            lengthMenu: "Show _MENU_ methods per page",
            info: "Showing _START_ to _END_ of _TOTAL_ methods",
            infoEmpty: "No methods available",
            emptyTable: "No payment methods found"
        }
    });

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const isActive = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/payments/${id}/toggle-status`,
            type: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                } else {
                    showToast('Error', response.message, 'error');
                }
            },
            error: function() {
                showToast('Error', 'Failed to update status', 'error');
                // Revert toggle
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Add payment method form
    $('#addPaymentMethodForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.payments.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#addPaymentMethodModal').modal('hide');
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.keys(errors).forEach(key => {
                        showToast('Validation Error', errors[key][0], 'error');
                    });
                } else {
                    showToast('Error', 'Failed to create payment method', 'error');
                }
            }
        });
    });
});

function editPaymentMethod(id) {
    // Implementation for edit modal
    window.location.href = `/admin/payments/${id}/edit`;
}

function viewPaymentMethod(id) {
    // Implementation for view modal
    window.location.href = `/admin/payments/${id}`;
}

function deletePaymentMethod(id) {
    if (confirm('Are you sure you want to delete this payment method?')) {
        $.ajax({
            url: `/admin/payments/${id}`,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showToast('Success', response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function() {
                showToast('Error', 'Failed to delete payment method', 'error');
            }
        });
    }
}

function showToast(title, message, type) {
    // Toast notification implementation
    const toast = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastContainer = $('.toast-container');
    if (toastContainer.length === 0) {
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
    }
    
    $('.toast-container').append(toast);
    $('.toast').last().toast('show');
}
</script>
@endpush
