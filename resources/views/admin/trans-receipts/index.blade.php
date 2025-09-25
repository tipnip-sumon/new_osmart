@extends('admin.layouts.app')

@section('title', 'Transaction Receipts')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Transaction Receipts</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transaction Receipts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Receipts</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $stats['total_receipts'] ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="bx bx-receipt text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Amount</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">${{ number_format($stats['total_amount'] ?? 0, 2) }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle rounded fs-3">
                                <i class="bx bx-dollar-circle text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Verification</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $stats['verification_pending'] ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle rounded fs-3">
                                <i class="bx bx-time-five text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Confirmed Receipts</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $stats['confirmed_receipts'] ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class="bx bx-check-circle text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-3">
                        <div class="col-md-3">
                            <h5 class="card-title mb-0">Transaction Receipts</h5>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseFilter">
                                    <i class="ri-filter-line align-bottom"></i> Filters
                                </button>
                                <a href="{{ route('admin.trans-receipts.export') }}" class="btn btn-outline-success">
                                    <i class="ri-file-excel-line align-bottom"></i> Export
                                </a>
                                <a href="{{ route('admin.trans-receipts.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-bottom"></i> Add Receipt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="collapse" id="collapseFilter">
                    <div class="card-body border-bottom">
                        <form method="GET" action="{{ route('admin.trans-receipts.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        @foreach($statuses as $key => $status)
                                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Transaction Type</label>
                                    <select name="transaction_type" class="form-select">
                                        <option value="">All Types</option>
                                        @foreach($transactionTypes as $key => $type)
                                            <option value="{{ $key }}" {{ request('transaction_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="">All Methods</option>
                                        @foreach($paymentMethods as $key => $method)
                                            <option value="{{ $key }}" {{ request('payment_method') == $key ? 'selected' : '' }}>
                                                {{ $method }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control" placeholder="Transaction ID, Reference..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <a href="{{ route('admin.trans-receipts.index') }}" class="btn btn-outline-secondary">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th>
                                    <th>Transaction ID</th>
                                    <th>Type</th>
                                    <th>Vendor/Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Verification</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input receipt-checkbox" type="checkbox" value="{{ $receipt['id'] }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $receipt['transaction_id'] }}</h6>
                                            <small class="text-muted">{{ $receipt['reference_number'] }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($receipt['transaction_type']) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            @if($receipt['vendor_name'])
                                                <div><strong>Vendor:</strong> {{ $receipt['vendor_name'] }}</div>
                                            @endif
                                            @if($receipt['customer_name'])
                                                <div><strong>Customer:</strong> {{ $receipt['customer_name'] }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">${{ number_format($receipt['amount'], 2) }}</span>
                                        <small class="text-muted d-block">{{ $receipt['currency'] }}</small>
                                    </td>
                                    <td>{{ $receipt['payment_method'] }}</td>
                                    <td>
                                        <span class="badge {{ $receipt['status'] == 'confirmed' ? 'bg-success' : ($receipt['status'] == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($receipt['status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $receipt['verification_status'] == 'verified' ? 'bg-success' : ($receipt['verification_status'] == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($receipt['verification_status']) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($receipt['transaction_date'])->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.trans-receipts.show', $receipt['id']) }}">
                                                    <i class="ri-eye-line align-bottom me-2"></i> View
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.trans-receipts.edit', $receipt['id']) }}">
                                                    <i class="ri-pencil-line align-bottom me-2"></i> Edit
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.trans-receipts.generate-pdf', $receipt['id']) }}">
                                                    <i class="ri-download-line align-bottom me-2"></i> Download PDF
                                                </a></li>
                                                <li class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteReceipt({{ $receipt['id'] }})">
                                                    <i class="ri-delete-bin-line align-bottom me-2"></i> Delete
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-file-list-line fs-4 mb-2 d-block"></i>
                                            No transaction receipts found
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mt-3" id="bulkActions" style="display: none;">
                        <div class="col-12">
                            <div class="bg-light p-3 rounded">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <span id="selectedCount">0</span> receipt(s) selected
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('confirm')">Confirm</button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="bulkAction('verify')">Verify</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="bulkAction('cancel')">Cancel</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('delete')">Delete</button>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this transaction receipt? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle bulk selection
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.receipt-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    checkAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const selected = document.querySelectorAll('.receipt-checkbox:checked');
        if (selected.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = selected.length;
        } else {
            bulkActions.style.display = 'none';
        }
    }
});

function deleteReceipt(id) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/admin/trans-receipts/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function bulkAction(action) {
    const selected = Array.from(document.querySelectorAll('.receipt-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('Please select at least one receipt');
        return;
    }

    const reason = prompt(`Please provide a reason for ${action}:`);
    if (reason === null) return;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.trans-receipts.bulk-action") }}';
    
    form.innerHTML = `
        @csrf
        <input type="hidden" name="action" value="${action}">
        <input type="hidden" name="reason" value="${reason}">
        ${selected.map(id => `<input type="hidden" name="receipt_ids[]" value="${id}">`).join('')}
    `;
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
