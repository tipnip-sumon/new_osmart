@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-percentage me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Commission Report</h4>
                                <p class="mb-0 opacity-75">Track your commission earnings from various sources</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                            <button class="btn btn-light btn-sm" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3 class="fw-bold text-success">৳{{ number_format($commissionData->sum('total'), 2) }}</h3>
                    <p class="text-muted mb-0">Total Commission</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-calendar-month"></i>
                    </div>
                    @php
                        $thisMonthCommission = $commissionData->where('date', '>=', now()->startOfMonth()->format('Y-m-d'))->sum('total');
                    @endphp
                    <h3 class="fw-bold text-primary">৳{{ number_format($thisMonthCommission, 2) }}</h3>
                    <p class="text-muted mb-0">This Month</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="fw-bold text-info">৳{{ $commissionData->count() > 0 ? number_format($commissionData->sum('total') / $commissionData->count(), 2) : '0.00' }}</h3>
                    <p class="text-muted mb-0">Daily Average</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="fw-bold text-warning">{{ $commissionData->groupBy('commission_type')->count() }}</h3>
                    <p class="text-muted mb-0">Commission Types</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Types Breakdown -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-pie-chart text-primary me-2"></i>
                        Commission by Type
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $commissionTypes = $commissionData->groupBy('commission_type');
                    @endphp
                    @if($commissionTypes->count() > 0)
                        @foreach($commissionTypes as $type => $typeData)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="commission-type-icon me-3">
                                        @switch($type)
                                            @case('direct')
                                                <i class="fas fa-user text-primary"></i>
                                                @break
                                            @case('binary')
                                                <i class="fas fa-sitemap text-success"></i>
                                                @break
                                            @case('matching')
                                                <i class="fas fa-handshake text-info"></i>
                                                @break
                                            @case('level')
                                                <i class="fas fa-layer-group text-warning"></i>
                                                @break
                                            @default
                                                <i class="fas fa-star text-secondary"></i>
                                        @endswitch
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ ucfirst($type) }} Commission</div>
                                        <small class="text-muted">{{ $typeData->count() }} transactions</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">৳{{ number_format($typeData->sum('total'), 2) }}</div>
                                    <small class="text-muted">
                                        {{ $commissionData->sum('total') > 0 ? number_format(($typeData->sum('total') / $commissionData->sum('total')) * 100, 1) : '0' }}%
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-pie text-muted mb-2"></i>
                            <p class="text-muted mb-0">No commission data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-alt text-success me-2"></i>
                        Recent Performance
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $last7Days = $commissionData->take(7);
                    @endphp
                    @if($last7Days->count() > 0)
                        @foreach($last7Days as $dayData)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($dayData->date)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ ucfirst($dayData->commission_type) }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-success">৳{{ number_format($dayData->total, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent commission data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="commission_type" class="form-label">Commission Type</label>
                            <select class="form-select" id="commission_type" name="commission_type">
                                <option value="">All Types</option>
                                <option value="direct" {{ request('commission_type') == 'direct' ? 'selected' : '' }}>Direct</option>
                                <option value="binary" {{ request('commission_type') == 'binary' ? 'selected' : '' }}>Binary</option>
                                <option value="matching" {{ request('commission_type') == 'matching' ? 'selected' : '' }}>Matching</option>
                                <option value="level" {{ request('commission_type') == 'level' ? 'selected' : '' }}>Level</option>
                                <option value="bonus" {{ request('commission_type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-success me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('member.reports.commission') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-success me-2"></i>
                        Commission Details
                    </h5>
                    <small class="text-muted">Showing {{ $commissionData->count() }} entries</small>
                </div>
                <div class="card-body">
                    @if($commissionData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Commission Type</th>
                                        <th>Amount</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissionData as $commission)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($commission->date)->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($commission->date)->format('l') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge commission-type-{{ $commission->commission_type }}">
                                                    <i class="fas fa-{{ $commission->commission_type == 'direct' ? 'user' : ($commission->commission_type == 'binary' ? 'sitemap' : ($commission->commission_type == 'matching' ? 'handshake' : 'layer-group')) }} me-1"></i>
                                                    {{ ucfirst($commission->commission_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">৳{{ number_format($commission->total, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">MLM Network</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Paid</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-primary btn-sm" onclick="viewCommissionDetails('{{ $commission->date }}', '{{ $commission->commission_type }}')">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td class="fw-bold">Total</td>
                                        <td>-</td>
                                        <td><span class="fw-bold text-success">৳{{ number_format($commissionData->sum('total'), 2) }}</span></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $commissionData->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-percentage text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Commission Data Found</h5>
                            <p class="text-muted">Start building your network to earn commissions</p>
                            <a href="{{ route('member.reports.team') }}" class="btn btn-success">
                                <i class="fas fa-users me-1"></i> View Team
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commission Details Modal -->
<div class="modal fade" id="commissionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="commissionDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// View commission details
function viewCommissionDetails(date, type) {
    const modal = new bootstrap.Modal(document.getElementById('commissionDetailsModal'));
    document.getElementById('commissionDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // Simulate API call - replace with actual implementation
    setTimeout(() => {
        document.getElementById('commissionDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <h6>Commission Details for ${date}</h6>
                <p><strong>Type:</strong> ${type.charAt(0).toUpperCase() + type.slice(1)} Commission</p>
                <p>Detailed breakdown of commission calculation and source will be displayed here.</p>
                <small class="text-muted">Feature implementation in progress.</small>
            </div>
        `;
    }, 1000);
}

// Export report
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    // Create download link
    const url = window.location.pathname + '?' + params.toString();
    window.open(url, '_blank');
    
    // Show notification
    const toast = document.createElement('div');
    toast.className = 'toast position-fixed top-0 end-0 m-3';
    toast.innerHTML = `
        <div class="toast-body bg-success text-white">
            <i class="fas fa-download me-2"></i>
            Downloading ${format.toUpperCase()} report...
        </div>
    `;
    document.body.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.icon-box {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.commission-type-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 123, 255, 0.1);
    font-size: 1.2rem;
}

.commission-type-direct {
    background-color: #007bff;
    color: white;
}

.commission-type-binary {
    background-color: #28a745;
    color: white;
}

.commission-type-matching {
    background-color: #17a2b8;
    color: white;
}

.commission-type-level {
    background-color: #ffc107;
    color: #212529;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1abc9c 100%);
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.65em;
}

.toast {
    z-index: 1055;
}
</style>
@endpush
@endsection
