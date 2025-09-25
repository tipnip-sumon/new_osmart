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
                            <i class="fas fa-money-bill-wave me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">My Commissions & Earnings</h4>
                                <p class="mb-0 opacity-75">Track your income and commission history</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="exportCommissions('pdf')">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                            <button class="btn btn-light btn-sm" onclick="exportCommissions('excel')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3 class="fw-bold text-success">৳{{ number_format($commissionsData['summary']['total_earnings'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Earned</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-calendar-month"></i>
                    </div>
                    <h3 class="fw-bold text-primary">৳{{ number_format($commissionsData['summary']['this_month_earnings'], 2) }}</h3>
                    <p class="text-muted mb-0">This Month</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <h3 class="fw-bold text-warning">৳{{ number_format($commissionsData['summary']['pending_amount'], 2) }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="fw-bold text-info">৳{{ number_format($commissionsData['summary']['this_week_earnings'], 2) }}</h3>
                    <p class="text-muted mb-0">This Week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Breakdown Cards - Enhanced Version -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 text-white">
                            <i class="fas fa-chart-line me-2"></i>
                            Complete Income Breakdown
                        </h5>
                        <small class="text-white opacity-75">Detailed view of all income sources</small>
                    </div>
                    <div>
                        <button class="btn btn-light btn-sm" onclick="downloadReport()">
                            <i class="fas fa-download me-1"></i> Download Report
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Primary Income Sources -->
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-primary-subtle border border-primary border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-primary text-white me-3">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-primary mb-1">৳{{ number_format($commissionsData['sponsor_bonus']['total'], 2) }}</h4>
                                        <h6 class="text-muted mb-0">Sponsor Bonus</h6>
                                        <small class="text-primary">{{ $commissionsData['sponsor_bonus']['count'] }} transactions</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? ($commissionsData['sponsor_bonus']['total'] / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-info-subtle border border-info border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-info text-white me-3">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-info mb-1">৳{{ number_format($commissionsData['generation_bonus']['total'], 2) }}</h4>
                                        <h6 class="text-muted mb-0">Generation Bonus</h6>
                                        <small class="text-info">{{ $commissionsData['generation_bonus']['count'] }} transactions</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-info" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? ($commissionsData['generation_bonus']['total'] / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-warning-subtle border border-warning border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-warning text-white me-3">
                                        <i class="fas fa-undo-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-warning mb-1">৳{{ number_format($cashBack ?? 0, 2) }}</h4>
                                        <h6 class="text-muted mb-0">Cash Back</h6>
                                        <small class="text-warning">Pending ৳{{ number_format($commissionsData['summary']['pending_amount'], 2) }}</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? (($cashBack ?? 0) / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-primary-subtle border border-primary border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-primary text-white me-3">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-primary mb-1">৳{{ number_format($commissionsData['sponsor_bonus']['total'], 2) }}</h4>
                                        <h6 class="text-muted mb-0">Sponsor Bonus</h6>
                                        <small class="text-primary">Direct referrals</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? ($commissionsData['sponsor_bonus']['total'] / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-info-subtle border border-info border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-info text-white me-3">
                                        <i class="fas fa-code-branch"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-info mb-1">৳{{ number_format($binaryMatching ?? 0, 2) }}</h4>
                                        <h6 class="text-muted mb-0">Binary Bonus</h6>
                                        <small class="text-info">Binary matching</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-info" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? (($binaryMatching ?? 0) / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-success-subtle border border-success border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-success text-white me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-success mb-1">৳{{ number_format($commissionsData['generation_bonus']['total'], 2) }}</h4>
                                        <h6 class="text-muted mb-0">Team Bonus</h6>
                                        <small class="text-success">Team volume</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? ($commissionsData['generation_bonus']['total'] / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        @if($commissionsData['rank_bonus']['total'] > 0 || ($rankSalary ?? 0) > 0)
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-danger-subtle border border-danger border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-danger text-white me-3">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-danger mb-1">৳{{ number_format($commissionsData['rank_bonus']['total'], 2) }}</h4>
                                        <h6 class="text-muted mb-0">Rank Bonus</h6>
                                        <small class="text-danger">Achievement rewards</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? ($commissionsData['rank_bonus']['total'] / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-dark-subtle border border-dark border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-dark text-white me-3">
                                        <i class="fas fa-money-check-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-dark fw-bold mb-1">৳{{ number_format($rankSalary ?? 0, 2) }}</h4>
                                        <h6 class="text-dark mb-0">Rank Salary</h6>
                                        <small class="text-dark opacity-75">Monthly salary</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-dark" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? (($rankSalary ?? 0) / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-teal-subtle border border-teal border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-teal text-white me-3">
                                        <i class="fas fa-share-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-teal-dark mb-1">৳{{ number_format($linkShareBonus ?? 0, 2) }}</h4>
                                        <h6 class="text-dark mb-0">Link Share Bonus</h6>
                                        <small class="text-teal-dark">৳2 per link share</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-teal" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? (($linkShareBonus ?? 0) / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($kycBonus) && $kycBonus > 0)
                        <div class="col-md-6 col-lg-4">
                            <div class="income-card bg-purple-subtle border border-purple border-opacity-25 rounded-3 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="income-icon bg-purple text-white me-3">
                                        <i class="fas fa-shield-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="text-purple-dark mb-1">৳{{ number_format($kycBonus ?? 0, 2) }}</h4>
                                        <h6 class="text-dark mb-0">KYC Bonus</h6>
                                        <small class="text-purple-dark">Verification reward</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar bg-purple" style="width: {{ $commissionsData['summary']['total_earnings'] > 0 ? (($kycBonus ?? 0) / $commissionsData['summary']['total_earnings']) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filter by Income Type
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="downloadReport()">
                            <i class="fas fa-download me-1"></i>Report
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions', ['type' => 'sponsor_bonus']) }}" 
                               class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center {{ $commissionType == 'sponsor_bonus' ? 'active' : '' }}">
                                <i class="fas fa-handshake me-2"></i> 
                                <div class="text-start">
                                    <div>Sponsor</div>
                                    <small class="opacity-75">৳{{ number_format($commissionsData['sponsor_bonus']['total'], 0) }}</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions', ['type' => 'binary_bonus']) }}" 
                               class="btn btn-outline-info btn-sm w-100 d-flex align-items-center {{ $commissionType == 'binary_bonus' ? 'active' : '' }}">
                                <i class="fas fa-code-branch me-2"></i> 
                                <div class="text-start">
                                    <div>Binary</div>
                                    <small class="opacity-75">৳{{ number_format($binaryMatching ?? 0, 0) }}</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions', ['type' => 'generation_bonus']) }}" 
                               class="btn btn-outline-success btn-sm w-100 d-flex align-items-center {{ $commissionType == 'generation_bonus' ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i> 
                                <div class="text-start">
                                    <div>Team</div>
                                    <small class="opacity-75">৳{{ number_format($commissionsData['generation_bonus']['total'], 0) }}</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions', ['type' => 'cashback']) }}" 
                               class="btn btn-outline-warning btn-sm w-100 d-flex align-items-center {{ $commissionType == 'cashback' ? 'active' : '' }}">
                                <i class="fas fa-undo-alt me-2"></i> 
                                <div class="text-start">
                                    <div>Cash Back</div>
                                    <small class="opacity-75">৳{{ number_format($cashBack ?? 0, 0) }}</small>
                                </div>
                            </a>
                        </div>
                        @if($commissionsData['rank_bonus']['total'] > 0)
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions', ['type' => 'rank_bonus']) }}" 
                               class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center {{ $commissionType == 'rank_bonus' ? 'active' : '' }}">
                                <i class="fas fa-medal me-2"></i> 
                                <div class="text-start">
                                    <div>Rank</div>
                                    <small class="opacity-75">৳{{ number_format($commissionsData['rank_bonus']['total'], 0) }}</small>
                                </div>
                            </a>
                        </div>
                        @endif
                        <div class="col-lg-2 col-md-3 col-6">
                            <a href="{{ route('member.commissions') }}" 
                               class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center {{ $commissionType == 'all' ? 'active' : '' }}">
                                <i class="fas fa-list me-2"></i> 
                                <div class="text-start">
                                    <div>All</div>
                                    <small class="opacity-75">Total</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Details -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-list text-primary me-2"></i>
                        Commission Details
                        @if($commissionType != 'all')
                            <span class="badge bg-primary ms-2">
                                {{ ucfirst(str_replace('_', ' ', $commissionType)) }}
                            </span>
                        @endif
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshCommissions()">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($commissionType == 'all')
                        @include('member.commissions.all-types')
                    @elseif($commissionType == 'sponsor_bonus')
                        @include('member.commissions.sponsor-bonus')
                    @elseif($commissionType == 'generation_bonus')
                        @include('member.commissions.generation-bonus')
                    @elseif($commissionType == 'club_bonus')
                        @include('member.commissions.club-bonus')
                    @elseif($commissionType == 'daily_pool')
                        @include('member.commissions.daily-pool')
                    @elseif($commissionType == 'rank_bonus')
                        @include('member.commissions.rank-bonus')
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
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function refreshCommissions() {
    location.reload();
}

function exportCommissions(format) {
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

function viewTransactionDetails(transactionId) {
    // Show transaction details modal
    alert('Transaction details for ID: ' + transactionId);
    // TODO: Implement modal with transaction details
}

function viewClubBonusDetails(bonusId) {
    // Show club bonus details modal
    alert('Club bonus details for ID: ' + bonusId);
    // TODO: Implement modal with club bonus details
}
</script>
<script>
// View commission details
function viewCommissionDetails(commissionId) {
    const modal = new bootstrap.Modal(document.getElementById('commissionDetailsModal'));
    document.getElementById('commissionDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // Simulate API call - replace with actual implementation
    setTimeout(() => {
        document.getElementById('commissionDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <h6>Commission ID: ${commissionId}</h6>
                <p>Detailed commission information including calculation breakdown and user details will be displayed here.</p>
                <small class="text-muted">Feature implementation in progress.</small>
            </div>
        `;
    }, 1000);
}

// Clear all filters
function clearFilters() {
    window.location.href = '{{ route("member.commissions") }}';
}

// Download income report
function downloadReport() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    btn.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Show success message
        showToast('success', 'Report generated successfully!');
        
        // TODO: Implement actual report download
        console.log('Downloading commission report...');
    }, 2000);
}

// Enhanced export functions
function exportCommissions(format) {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i> Exporting...`;
    btn.disabled = true;
    
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        showToast('success', `Commission data exported as ${format.toUpperCase()}`);
    }, 1500);
}

// Show toast notification
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to page
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove after hiding
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Initialize page enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars on page load
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Add hover effects to income cards
    const incomeCards = document.querySelectorAll('.income-card');
    incomeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
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

.commission-icon .icon-box {
    width: 45px;
    height: 45px;
    font-size: 1.2rem;
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
    background: linear-gradient(135deg, #218838 0%, #1ea87a 100%);
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.65em;
}

.avatar-sm img {
    object-fit: cover;
}

.toast {
    z-index: 1055;
}

/* Enhanced Income Cards Styles */
.income-card {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.income-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.income-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.income-icon-sm {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.income-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease;
}

.income-card:hover::before {
    left: 100%;
}

/* Custom color classes */
.text-teal { color: #14b8a6 !important; }
.text-teal-dark { color: #0f766e !important; }
.text-purple { color: #8b5cf6 !important; }
.text-purple-dark { color: #6d28d9 !important; }
.text-orange { color: #f97316 !important; }
.bg-teal { background-color: #14b8a6 !important; }
.bg-teal-subtle { background-color: rgba(20, 184, 166, 0.1) !important; }
.bg-purple { background-color: #8b5cf6 !important; }
.bg-purple-subtle { background-color: rgba(139, 92, 246, 0.1) !important; }
.bg-orange { background-color: #f97316 !important; }
.bg-dark-subtle { background-color: rgba(33, 37, 41, 0.05) !important; }
.border-teal { border-color: #14b8a6 !important; }
.border-purple { border-color: #8b5cf6 !important; }
.border-orange { border-color: #f97316 !important; }

/* Enhanced filter buttons */
.btn-outline-primary:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover,
.btn-outline-success:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Progress bars enhancement */
.progress {
    border-radius: 10px;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.1);
}

.progress-bar {
    border-radius: 10px;
    transition: width 1s ease-in-out;
}

.progress-bar.bg-teal {
    background-color: #14b8a6 !important;
}

.progress-bar.bg-purple {
    background-color: #8b5cf6 !important;
}
</style>
<style>
.icon-box {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.icon-box i {
    margin: 0;
}

.btn.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.commission-type-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endpush

