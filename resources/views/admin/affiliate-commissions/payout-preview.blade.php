@extends('admin.layouts.app')

@section('title', 'Affiliate Commission Payout Preview')

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
                        <a href="{{ route('admin.affiliate-commissions.index') }}">Affiliate Commissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payout Preview</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Affiliate Commission Payout Preview</h1>
        </div>
        <div class="btn-list">
            <a href="{{ route('admin.affiliate-commissions.index') }}" class="btn btn-outline-secondary btn-wave me-2">
                <i class="bx bx-arrow-back me-1"></i> Back to Commissions
            </a>
            @if($totalCommissionsCount > 0)
            <button class="btn btn-primary btn-wave" onclick="processAllPayouts()">
                <i class="fas fa-money-bill-wave me-1"></i> Process All Payouts
            </button>
            @endif
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Payout Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="card-text mb-1 text-muted">Total Payout Amount</p>
                            <h4 class="fw-semibold mb-0 text-primary">${{ number_format($totalPayoutAmount, 2) }}</h4>
                        </div>
                        <div class="avatar avatar-md bg-primary-transparent">
                            <i class="fas fa-dollar-sign fs-18"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="card-text mb-1 text-muted">Total Commissions</p>
                            <h4 class="fw-semibold mb-0 text-success">{{ number_format($totalCommissionsCount) }}</h4>
                        </div>
                        <div class="avatar avatar-md bg-success-transparent">
                            <i class="fas fa-list fs-18"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card border-info">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="card-text mb-1 text-muted">Total Affiliates</p>
                            <h4 class="fw-semibold mb-0 text-info">{{ number_format($totalAffiliatesCount) }}</h4>
                        </div>
                        <div class="avatar avatar-md bg-info-transparent">
                            <i class="fas fa-users fs-18"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card custom-card border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="card-text mb-1 text-muted">Average Per Affiliate</p>
                            <h4 class="fw-semibold mb-0 text-warning">
                                ${{ $totalAffiliatesCount > 0 ? number_format($totalPayoutAmount / $totalAffiliatesCount, 2) : '0.00' }}
                            </h4>
                        </div>
                        <div class="avatar avatar-md bg-warning-transparent">
                            <i class="fas fa-chart-line fs-18"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Filter Commissions</div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.affiliate-commissions.payout.preview') }}">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Affiliate</label>
                                <select name="affiliate_id" class="form-control">
                                    <option value="">All Affiliates</option>
                                    @foreach($affiliates as $affiliate)
                                        <option value="{{ $affiliate->id }}" {{ request('affiliate_id') == $affiliate->id ? 'selected' : '' }}>
                                            {{ $affiliate->name }} ({{ $affiliate->username }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($payoutSummary->count() > 0)
    <!-- Payout Summary by Affiliate -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Payout Summary by Affiliate</div>
                    <div>
                        <button class="btn btn-sm btn-success" onclick="selectAllAffiliates()">
                            <i class="fas fa-check-double me-1"></i> Select All
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="clearAllSelections()">
                            <i class="fas fa-times me-1"></i> Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" onchange="toggleAllAffiliates()">
                                    </th>
                                    <th>Affiliate</th>
                                    <th>Username</th>
                                    <th>Commission Count</th>
                                    <th>Total Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payoutSummary as $summary)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="affiliate-checkbox" value="{{ $summary['user']->id }}" data-amount="{{ $summary['total_amount'] }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                                                {{ substr($summary['user']->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <span class="fw-semibold">{{ $summary['user']->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $summary['user']->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $summary['user']->username }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $summary['commission_count'] }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($summary['total_amount'], 2) }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="processAffiliatePayout({{ $summary['user']->id }}, '{{ $summary['user']->name }}', {{ $summary['total_amount'] }})">
                                            <i class="fas fa-money-bill-wave me-1"></i> Pay Now
                                        </button>
                                        <button class="btn btn-sm btn-info" onclick="viewAffiliateCommissions({{ $summary['user']->id }})">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($payoutSummary->count() > 0)
                    <div class="mt-3">
                        <button class="btn btn-success" onclick="processSelectedPayouts()">
                            <i class="fas fa-credit-card me-1"></i> Process Selected Payouts
                        </button>
                        <span class="ms-3 text-muted">
                            Selected: <span id="selectedCount">0</span> affiliates, 
                            Total: $<span id="selectedAmount">0.00</span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Commissions -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Individual Commission Details</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="commissionsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Affiliate</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Order ID</th>
                                    <th>Commission</th>
                                    <th>Earned Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $commission)
                                <tr id="commission-{{ $commission->id }}">
                                    <td>{{ $commission->id }}</td>
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $commission->user->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $commission->user->username }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $commission->referredUser->name ?? 'N/A' }}</td>
                                    <td>{{ $commission->product->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($commission->order_id)
                                            <a href="{{ route('admin.orders.show', $commission->order_id) }}" class="text-primary">
                                                #{{ $commission->order_id }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">${{ number_format($commission->commission_amount, 2) }}</span>
                                    </td>
                                    <td>{{ $commission->earned_at ? $commission->earned_at->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="processIndividualPayout({{ $commission->id }})">
                                            <i class="fas fa-dollar-sign me-1"></i> Pay
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Commissions Message -->
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="avatar avatar-xxl bg-light rounded-circle mx-auto mb-4">
                        <i class="fas fa-money-bill-wave fs-24 text-muted"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No Commissions Ready for Payout</h5>
                    <p class="text-muted mb-4">There are currently no approved affiliate commissions ready for payout.</p>
                    <a href="{{ route('admin.affiliate-commissions.index') }}" class="btn btn-primary">
                        <i class="bx bx-arrow-back me-1"></i> Back to Commissions
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Payout Confirmation Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="payoutDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPayoutBtn">Confirm Payout</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#commissionsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }

    // Update selected count and amount
    $('.affiliate-checkbox').on('change', updateSelectedInfo);
});

function updateSelectedInfo() {
    const checkboxes = document.querySelectorAll('.affiliate-checkbox:checked');
    const count = checkboxes.length;
    let totalAmount = 0;

    checkboxes.forEach(checkbox => {
        totalAmount += parseFloat(checkbox.dataset.amount) || 0;
    });

    document.getElementById('selectedCount').textContent = count;
    document.getElementById('selectedAmount').textContent = totalAmount.toFixed(2);
}

function toggleAllAffiliates() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.affiliate-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedInfo();
}

function selectAllAffiliates() {
    document.getElementById('selectAll').checked = true;
    toggleAllAffiliates();
}

function clearAllSelections() {
    document.getElementById('selectAll').checked = false;
    toggleAllAffiliates();
}

function processSelectedPayouts() {
    const checkboxes = document.querySelectorAll('.affiliate-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one affiliate for payout.');
        return;
    }

    const affiliateIds = Array.from(checkboxes).map(cb => cb.value);
    let totalAmount = 0;
    
    checkboxes.forEach(checkbox => {
        totalAmount += parseFloat(checkbox.dataset.amount) || 0;
    });

    const details = `
        <p><strong>Selected Affiliates:</strong> ${checkboxes.length}</p>
        <p><strong>Total Payout Amount:</strong> $${totalAmount.toFixed(2)}</p>
        <p class="text-warning">This action will mark all approved commissions for these affiliates as paid. This cannot be undone.</p>
    `;

    document.getElementById('payoutDetails').innerHTML = details;
    
    const modal = new bootstrap.Modal(document.getElementById('payoutModal'));
    modal.show();

    document.getElementById('confirmPayoutBtn').onclick = function() {
        processPayoutForAffiliates(affiliateIds);
        modal.hide();
    };
}

function processAffiliatePayout(affiliateId, affiliateName, amount) {
    const details = `
        <p><strong>Affiliate:</strong> ${affiliateName}</p>
        <p><strong>Payout Amount:</strong> $${amount.toFixed(2)}</p>
        <p class="text-warning">This action will mark all approved commissions for this affiliate as paid. This cannot be undone.</p>
    `;

    document.getElementById('payoutDetails').innerHTML = details;
    
    const modal = new bootstrap.Modal(document.getElementById('payoutModal'));
    modal.show();

    document.getElementById('confirmPayoutBtn').onclick = function() {
        processPayoutForAffiliates([affiliateId]);
        modal.hide();
    };
}

function processPayoutForAffiliates(affiliateIds) {
    // Get all commission IDs for these affiliates
    const commissionIds = [];
    
    @foreach($commissions as $commission)
        if (affiliateIds.includes('{{ $commission->user_id }}')) {
            commissionIds.push({{ $commission->id }});
        }
    @endforeach

    if (commissionIds.length === 0) {
        alert('No commissions found for selected affiliates.');
        return;
    }

    fetch('{{ route("admin.affiliate-commissions.payout.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            commission_ids: commissionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error processing payout: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the payout.');
    });
}

function processIndividualPayout(commissionId) {
    if (!confirm('Are you sure you want to process payout for this commission?')) {
        return;
    }

    fetch('{{ route("admin.affiliate-commissions.payout.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            commission_ids: [commissionId]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('commission-' + commissionId).style.display = 'none';
        } else {
            alert('Error processing payout: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the payout.');
    });
}

function processAllPayouts() {
    if (!confirm('Are you sure you want to process payout for ALL approved commissions? This action cannot be undone.')) {
        return;
    }

    const allCommissionIds = [
        @foreach($commissions as $commission)
            {{ $commission->id }},
        @endforeach
    ];

    fetch('{{ route("admin.affiliate-commissions.payout.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            commission_ids: allCommissionIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error processing payout: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the payout.');
    });
}

function viewAffiliateCommissions(affiliateId) {
    window.open('{{ route("admin.affiliate-commissions.index") }}?affiliate_id=' + affiliateId, '_blank');
}
</script>
@endpush
