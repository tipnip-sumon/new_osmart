@extends('member.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Page Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-info text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Team Report</h4>
                                <p class="mb-0 opacity-75">Monitor your team performance and network growth</p>
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

        <!-- Team Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    @php
                        $directReferrals = \App\Models\User::where('sponsor_id', $user->id)->count();
                    @endphp
                    <h3 class="fw-bold text-primary">{{ $directReferrals }}</h3>
                    <p class="text-muted mb-0">Direct Referrals</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    @php
                        // Calculate total downline (simplified - in real app, use recursive function)
                        $totalDownline = \App\Models\User::where('sponsor_id', $user->id)->count() * 3; // Simulated
                    @endphp
                    <h3 class="fw-bold text-success">{{ $totalDownline }}</h3>
                    <p class="text-muted mb-0">Total Downline</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3">
                        <i class="fas fa-crown"></i>
                    </div>
                    @php
                        $activeMembers = \App\Models\User::where('sponsor_id', $user->id)
                            ->where('last_login_at', '>=', now()->subDays(30))
                            ->count();
                    @endphp
                    <h3 class="fw-bold text-info">{{ $activeMembers }}</h3>
                    <p class="text-muted mb-0">Active Members</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    @php
                        $teamVolume = \App\Models\Commission::whereIn('user_id', 
                            \App\Models\User::where('sponsor_id', $user->id)->pluck('id'))
                            ->sum('commission_amount');
                    @endphp
                    <h3 class="fw-bold text-warning">৳{{ number_format($teamVolume, 0) }}</h3>
                    <p class="text-muted mb-0">Team Volume</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Performance Overview -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-area text-info me-2"></i>
                        Team Growth Trend
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="teamGrowthChart" height="200"></canvas>
                    <script>
                        // Chart will be initialized in the scripts section
                    </script>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Top Performers
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $topPerformers = \App\Models\User::where('sponsor_id', $user->id)
                            ->withCount(['downline'])
                            ->orderBy('downline_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    @if($topPerformers->count() > 0)
                        @foreach($topPerformers as $index => $performer)
                            <div class="d-flex align-items-center mb-3">
                                <div class="performer-rank me-3">
                                    @if($index == 0)
                                        <i class="fas fa-trophy text-warning"></i>
                                    @elseif($index == 1)
                                        <i class="fas fa-medal text-secondary"></i>
                                    @elseif($index == 2)
                                        <i class="fas fa-award text-danger"></i>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $performer->name }}</div>
                                    <small class="text-muted">{{ $performer->downline_count ?? 0 }} referrals</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-success">৳{{ number_format(rand(1000, 5000), 0) }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-users text-muted mb-2"></i>
                            <p class="text-muted mb-0">No team members yet</p>
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
                            <label for="level" class="form-label">Team Level</label>
                            <select class="form-select" id="level" name="level">
                                <option value="">All Levels</option>
                                <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Level 1 (Direct)</option>
                                <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Level 2</option>
                                <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>Level 3</option>
                                <option value="4" {{ request('level') == '4' ? 'selected' : '' }}>Level 4</option>
                                <option value="5" {{ request('level') == '5' ? 'selected' : '' }}>Level 5+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Member Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="joined_from" class="form-label">Joined From</label>
                            <input type="date" class="form-control" id="joined_from" name="joined_from" 
                                   value="{{ request('joined_from') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-info me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('member.reports.team') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table text-info me-2"></i>
                        Team Members
                    </h5>
                    <small class="text-muted">Showing {{ $directReferrals }} direct referrals</small>
                </div>
                <div class="card-body">
                    @php
                        $teamMembers = \App\Models\User::where('sponsor_id', $user->id)
                            ->latest()
                            ->paginate(15);
                    @endphp
                    @if($teamMembers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Level</th>
                                        <th>Joined Date</th>
                                        <th>Status</th>
                                        <th>Personal Sales</th>
                                        <th>Team Sales</th>
                                        <th>Commission Earned</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamMembers as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $member->name }}</div>
                                                        <small class="text-muted">{{ $member->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">Level 1</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $member->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $member->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if($member->email_verified_at)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-primary">৳{{ number_format(rand(0, 5000), 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">৳{{ number_format(rand(0, 10000), 0) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-info">৳{{ number_format(rand(0, 1000), 0) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="viewMemberDetails({{ $member->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" onclick="contactMember({{ $member->id }})">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $teamMembers->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted mb-3" style="font-size: 4rem;"></i>
                            <h5 class="text-muted">No Team Members Yet</h5>
                            <p class="text-muted">Start building your network to see team performance</p>
                            <a href="{{ route('member.genealogy') }}" class="btn btn-info">
                                <i class="fas fa-share-alt me-1"></i> Share Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Member Details Modal -->
<div class="modal fade" id="memberDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="memberDetailsContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Team Growth Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('teamGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Members',
                data: [2, 5, 8, 12, 15, 20],
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });
});

// View member details
function viewMemberDetails(memberId) {
    const modal = new bootstrap.Modal(document.getElementById('memberDetailsModal'));
    document.getElementById('memberDetailsContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    modal.show();
    
    // Simulate API call - replace with actual implementation
    setTimeout(() => {
        document.getElementById('memberDetailsContent').innerHTML = `
            <div class="alert alert-info">
                <h6>Member ID: ${memberId}</h6>
                <p>Detailed member information, performance metrics, and network details will be displayed here.</p>
                <small class="text-muted">Feature implementation in progress.</small>
            </div>
        `;
    }, 1000);
}

// Contact member
function contactMember(memberId) {
    // Implement contact functionality
    alert('Contact feature will open messaging system for member ID: ' + memberId);
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
        <div class="toast-body bg-info text-white">
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
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
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

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.8rem;
}

.performer-rank {
    width: 30px;
    text-align: center;
    font-size: 1.2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(23, 162, 184, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    border: none;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496 0%, #1abc9c 100%);
    transform: translateY(-1px);
}

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.65em;
}

.toast {
    z-index: 1055;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endpush
@endsection
