@extends('admin.layouts.app')

@section('title', 'Affiliate Click Tracking')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="card-title mb-0">
                                <i class="fas fa-mouse-pointer text-info me-2"></i>
                                Affiliate Click Tracking
                            </h2>
                            <p class="text-muted mb-0">Monitor and analyze affiliate link clicks and user behavior</p>
                        </div>
                        <div class="col-auto">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.affiliate-clicks.analytics') }}" class="btn btn-outline-info">
                                    <i class="fas fa-chart-bar me-1"></i>Analytics
                                </a>
                                <a href="{{ route('admin.affiliate-clicks.export') }}" class="btn btn-primary">
                                    <i class="fas fa-download me-1"></i>Export
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-mouse-pointer fa-2x text-primary mb-3"></i>
                    <h4 class="card-title">{{ number_format($totalClicks ?? 0) }}</h4>
                    <p class="card-text text-muted">Total Clicks</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i> {{ $clicksGrowth ?? 0 }}% this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-success mb-3"></i>
                    <h4 class="card-title">{{ number_format($uniqueAffiliates ?? 0) }}</h4>
                    <p class="card-text text-muted">Active Affiliates</p>
                    <small class="text-info">
                        <i class="fas fa-clock"></i> Last 30 days
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-2x text-warning mb-3"></i>
                    <h4 class="card-title">{{ number_format($conversions ?? 0) }}</h4>
                    <p class="card-text text-muted">Conversions</p>
                    <small class="text-success">
                        {{ $conversionRate ?? 0 }}% rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-box fa-2x text-info mb-3"></i>
                    <h4 class="card-title">{{ number_format($uniqueProducts ?? 0) }}</h4>
                    <p class="card-text text-muted">Products Clicked</p>
                    <small class="text-muted">
                        Unique products
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Click Trends Over Time</h5>
                </div>
                <div class="card-body">
                    <canvas id="clickTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Browser Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="browserChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Clicks Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Recent Affiliate Clicks</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="searchClicks" placeholder="Search clicks...">
                                <select class="form-select form-select-sm" id="filterAffiliate">
                                    <option value="">All Affiliates</option>
                                    @if(isset($affiliatesList))
                                        @foreach($affiliatesList as $affiliate)
                                            <option value="{{ $affiliate->id }}">{{ $affiliate->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($clicks) && $clicks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped" id="clicksTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Affiliate</th>
                                        <th>Product</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Platform</th>
                                        <th>Country</th>
                                        <th>Converted</th>
                                        <th>Clicked At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clicks as $click)
                                    <tr>
                                        <td>{{ $click->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $click->user->name ?? 'Unknown' }}</strong>
                                                <br><small class="text-muted">{{ $click->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $click->product->name ?? 'Unknown Product' }}</strong>
                                                @if($click->product && $click->product->image)
                                                    <br><img src="{{ $click->product->image }}" alt="Product" class="img-thumbnail" style="width: 30px; height: 30px;">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $click->ip_address }}</code>
                                        </td>
                                        <td>
                                            <i class="fab fa-{{ strtolower($click->browser_name ?? 'question') }}"></i>
                                            {{ $click->browser_name ?? 'Unknown' }}
                                            <br><small class="text-muted">v{{ $click->browser_version ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <i class="fab fa-{{ strtolower($click->platform ?? 'desktop') }}"></i>
                                            {{ ucfirst($click->platform ?? 'Unknown') }}
                                        </td>
                                        <td>
                                            @if($click->country)
                                                <span class="fi fi-{{ strtolower($click->country_code ?? '') }}"></span>
                                                {{ $click->country }}
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($click->converted_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Yes
                                                </span>
                                                <br><small class="text-muted">{{ $click->converted_at->diffForHumans() }}</small>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times"></i> No
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $click->clicked_at ? $click->clicked_at->format('M d, Y H:i') : 'N/A' }}
                                            <br><small class="text-muted">{{ $click->clicked_at ? $click->clicked_at->diffForHumans() : '' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.affiliate-clicks.show', $click) }}" class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" onclick="deleteClick({{ $click->id }})" title="Delete Click">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($clicks, 'links'))
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        Showing {{ $clicks->firstItem() }} to {{ $clicks->lastItem() }} of {{ $clicks->total() }} results
                                    </small>
                                </div>
                                <div>
                                    {{ $clicks->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-mouse-pointer fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No clicks found</h5>
                            <p class="text-muted">Affiliate clicks will appear here as they start promoting your products.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Actions Modal --}}
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionsForm">
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select class="form-select" name="action" required>
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="export">Export Selected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selected Clicks</label>
                        <input type="text" class="form-control" id="selectedCount" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css">
<style>
.fi {
    width: 16px;
    height: 12px;
    margin-right: 4px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Click Trends Chart
const clickTrendsCtx = document.getElementById('clickTrendsChart').getContext('2d');
new Chart(clickTrendsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($trendLabels ?? []) !!},
        datasets: [{
            label: 'Clicks',
            data: {!! json_encode($trendData ?? []) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'Conversions',
            data: {!! json_encode($conversionTrendData ?? []) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Browser Distribution Chart
const browserCtx = document.getElementById('browserChart').getContext('2d');
new Chart(browserCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($browserLabels ?? ['Chrome', 'Firefox', 'Safari', 'Edge', 'Other']) !!},
        datasets: [{
            data: {!! json_encode($browserData ?? [0, 0, 0, 0, 0]) !!},
            backgroundColor: [
                '#4285f4',
                '#ff7139',
                '#00d4ff',
                '#0078d4',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Delete click function
function deleteClick(clickId) {
    if (confirm('Are you sure you want to delete this click record?')) {
        fetch(`/admin/affiliate-clicks/${clickId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting click record');
            }
        })
        .catch(error => {
            alert('Error deleting click record');
        });
    }
}

// Bulk actions
let selectedClicks = [];

function toggleClick(clickId) {
    const index = selectedClicks.indexOf(clickId);
    if (index > -1) {
        selectedClicks.splice(index, 1);
    } else {
        selectedClicks.push(clickId);
    }
    updateBulkActions();
}

function updateBulkActions() {
    document.getElementById('selectedCount').value = `${selectedClicks.length} clicks selected`;
}

function executeBulkAction() {
    const action = document.querySelector('select[name="action"]').value;
    if (!action || selectedClicks.length === 0) {
        alert('Please select an action and some clicks');
        return;
    }

    if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedClicks.length} click records?`)) {
        return;
    }

    fetch('/admin/affiliate-clicks/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            action: action,
            clicks: selectedClicks
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error executing bulk action');
        }
    });
}

// Initialize DataTables
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#clicksTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            columnDefs: [{
                targets: [9], // Actions column
                orderable: false
            }]
        });
    }

    // Search functionality
    $('#searchClicks').on('keyup', function() {
        $('#clicksTable').DataTable().search(this.value).draw();
    });

    // Filter by affiliate
    $('#filterAffiliate').on('change', function() {
        $('#clicksTable').DataTable().column(1).search(this.value).draw();
    });
});
</script>
@endpush
