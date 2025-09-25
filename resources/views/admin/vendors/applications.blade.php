@extends('admin.layouts.app')

@section('title', 'Vendor Applications')

@push('styles')
<style>
.application-status-badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-weight: 600;
}
.status-pending { background-color: #fff3cd; color: #664d03; }
.status-approved { background-color: #d1e7dd; color: #0a3622; }
.status-rejected { background-color: #f8d7da; color: #58151c; }
.application-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Vendor Applications</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                <li class="breadcrumb-item active">Applications</li>
            </ul>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="dash-widget-header">
                    <span class="dash-widget-icon text-warning border-warning">
                        <i class="fe fe-clock"></i>
                    </span>
                    <div class="dash-count">
                        <h3>{{ $stats['pending'] }}</h3>
                    </div>
                </div>
                <div class="dash-widget-info">
                    <h6 class="text-muted">Pending Applications</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="dash-widget-header">
                    <span class="dash-widget-icon text-success border-success">
                        <i class="fe fe-check-circle"></i>
                    </span>
                    <div class="dash-count">
                        <h3>{{ $stats['approved'] }}</h3>
                    </div>
                </div>
                <div class="dash-widget-info">
                    <h6 class="text-muted">Approved Applications</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="dash-widget-header">
                    <span class="dash-widget-icon text-danger border-danger">
                        <i class="fe fe-x-circle"></i>
                    </span>
                    <div class="dash-count">
                        <h3>{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
                <div class="dash-widget-info">
                    <h6 class="text-muted">Rejected Applications</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="dash-widget-header">
                    <span class="dash-widget-icon text-primary border-primary">
                        <i class="fe fe-users"></i>
                    </span>
                    <div class="dash-count">
                        <h3>{{ $stats['total'] }}</h3>
                    </div>
                </div>
                <div class="dash-widget-info">
                    <h6 class="text-muted">Total Applications</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Applications List -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Vendor Applications</h4>
                <div class="card-options">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm filter-btn" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-filter="pending">Pending</button>
                        <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="approved">Approved</button>
                        <button type="button" class="btn btn-outline-danger btn-sm filter-btn" data-filter="rejected">Rejected</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="applicationsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Applicant</th>
                                    <th>Business Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                <tr class="application-row" data-status="{{ $application->status }}">
                                    <td><strong>#{{ $application->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded bg-primary">
                                                    {{ substr($application->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $application->user->name }}</h6>
                                                <small class="text-muted">{{ $application->contact_person }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $application->business_name }}</strong>
                                        @if($application->website)
                                            <br><small><a href="{{ $application->website }}" target="_blank" class="text-muted">{{ $application->website }}</a></small>
                                        @endif
                                    </td>
                                    <td>{{ $application->email }}</td>
                                    <td>{{ $application->phone }}</td>
                                    <td>
                                        <span class="badge application-status-badge status-{{ $application->status }}">
                                            {{ $application->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $application->created_at->format('M d, Y') }}</span>
                                        <br><small>{{ $application->created_at->format('H:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.vendors.applications.show', $application->id) }}" 
                                               class="btn btn-outline-primary btn-sm" title="View Details">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            @if($application->status === 'pending')
                                                <button type="button" class="btn btn-outline-success btn-sm" 
                                                        onclick="approveApplication({{ $application->id }})" title="Approve">
                                                    <i class="fe fe-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                        onclick="rejectApplication({{ $application->id }})" title="Reject">
                                                    <i class="fe fe-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fe fe-file-text" style="font-size: 48px; color: #dee2e6;"></i>
                        </div>
                        <h5 class="text-muted">No vendor applications found</h5>
                        <p class="text-muted">Applications will appear here when users apply to become vendors.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Vendor Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" name="admin_notes" rows="3" 
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fe fe-info me-2"></i>
                        Approving this application will mark it as approved. You can later create a vendor account for this user.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fe fe-check me-2"></i>Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Vendor Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" name="admin_notes" rows="3" required
                                  placeholder="Please provide a reason for rejecting this application..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle me-2"></i>
                        This action will permanently reject the application. The user can submit a new application later.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-x me-2"></i>Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveApplication(applicationId) {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    const form = document.getElementById('approveForm');
    form.action = '{{ route("admin.vendors.applications.approve", ":id") }}'.replace(':id', applicationId);
    modal.show();
}

function rejectApplication(applicationId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = '{{ route("admin.vendors.applications.reject", ":id") }}'.replace(':id', applicationId);
    modal.show();
}

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        const rows = document.querySelectorAll('.application-row');
        
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filter rows
        rows.forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

// Set 'All' as default active
document.querySelector('.filter-btn[data-filter="all"]').classList.add('active');
</script>
@endpush
