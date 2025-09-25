@extends('admin.layouts.app')

@section('title', 'Vendor Application Details')

@push('styles')
<style>
.application-detail-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.status-badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
}
.status-pending { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
.status-approved { background-color: #d1e7dd; color: #0a3622; border: 1px solid #badbcc; }
.status-rejected { background-color: #f8d7da; color: #58151c; border: 1px solid #f5c2c7; }
.info-item {
    border-bottom: 1px solid #f1f1f1;
    padding: 1rem 0;
}
.info-item:last-child {
    border-bottom: none;
}
.info-label {
    font-weight: 600;
    color: #666;
    margin-bottom: 0.5rem;
}
.info-value {
    color: #333;
    font-size: 1rem;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Vendor Application Details</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.vendors.applications') }}">Applications</a></li>
                <li class="breadcrumb-item active">Application #{{ $application->id }}</li>
            </ul>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.vendors.applications') }}" class="btn btn-outline-primary">
                <i class="fe fe-arrow-left me-2"></i>Back to Applications
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Application Details -->
    <div class="col-lg-8">
        <div class="card application-detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Application Information</h4>
                <span class="status-badge status-{{ $application->status }}">
                    <i class="fe fe-{{ $application->status === 'pending' ? 'clock' : ($application->status === 'approved' ? 'check-circle' : 'x-circle') }} me-1"></i>
                    {{ $application->status_text }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Business Name</div>
                            <div class="info-value">{{ $application->business_name }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Contact Person</div>
                            <div class="info-value">{{ $application->contact_person }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">
                                <a href="mailto:{{ $application->email }}">{{ $application->email }}</a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">
                                <a href="tel:{{ $application->phone }}">{{ $application->phone }}</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-item">
                            <div class="info-label">Website</div>
                            <div class="info-value">
                                @if($application->website)
                                    <a href="{{ $application->website }}" target="_blank">{{ $application->website }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Application Date</div>
                            <div class="info-value">{{ $application->created_at->format('F d, Y \a\t H:i A') }}</div>
                        </div>
                        
                        @if($application->reviewed_at)
                        <div class="info-item">
                            <div class="info-label">Review Date</div>
                            <div class="info-value">{{ $application->reviewed_at->format('F d, Y \a\t H:i A') }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Reviewed By</div>
                            <div class="info-value">
                                @if($application->reviewer)
                                    {{ $application->reviewer->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Business Description</div>
                    <div class="info-value">
                        <p class="mb-0">{{ $application->business_description }}</p>
                    </div>
                </div>
                
                @if($application->admin_notes)
                <div class="info-item">
                    <div class="info-label">Admin Notes</div>
                    <div class="info-value">
                        <div class="alert alert-info mb-0">
                            <i class="fe fe-message-square me-2"></i>
                            {{ $application->admin_notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- User Information & Actions -->
    <div class="col-lg-4">
        <!-- User Information -->
        <div class="card application-detail-card">
            <div class="card-header">
                <h4 class="card-title mb-0">User Information</h4>
            </div>
            <div class="card-body text-center">
                <div class="avatar avatar-lg mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-primary" style="font-size: 2rem;">
                        {{ substr($application->user->name, 0, 1) }}
                    </span>
                </div>
                <h5 class="mb-1">{{ $application->user->name }}</h5>
                <p class="text-muted mb-3">{{ $application->user->email }}</p>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="d-block">
                            <strong class="d-block">Member Since</strong>
                            <span class="text-muted">{{ $application->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-block">
                            <strong class="d-block">Status</strong>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('admin.users.show', $application->user->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fe fe-user me-1"></i>View User Profile
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        @if($application->status === 'pending')
        <div class="card application-detail-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="approveApplication({{ $application->id }})">
                        <i class="fe fe-check me-2"></i>Approve Application
                    </button>
                    <button type="button" class="btn btn-danger" onclick="rejectApplication({{ $application->id }})">
                        <i class="fe fe-x me-2"></i>Reject Application
                    </button>
                </div>
                
                <hr>
                
                <div class="alert alert-info mb-0">
                    <small>
                        <i class="fe fe-info me-1"></i>
                        Approving will mark this application as approved. You can then create a vendor account for this user.
                    </small>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Timeline -->
        <div class="card application-detail-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Timeline</h4>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Application Submitted</h6>
                            <p class="text-muted mb-0">{{ $application->created_at->format('M d, Y \a\t H:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($application->reviewed_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $application->status === 'approved' ? 'success' : 'danger' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Application {{ ucfirst($application->status) }}</h6>
                            <p class="text-muted mb-0">{{ $application->reviewed_at->format('M d, Y \a\t H:i A') }}</p>
                            @if($application->reviewer)
                                <small class="text-muted">by {{ $application->reviewer->name }}</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
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
                    <div class="alert alert-success">
                        <i class="fe fe-check-circle me-2"></i>
                        This will approve the vendor application for <strong>{{ $application->business_name }}</strong>.
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
                        <label class="form-label">Reason for Rejection *</label>
                        <textarea class="form-control" name="admin_notes" rows="3" required
                                  placeholder="Please provide a detailed reason for rejecting this application..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle me-2"></i>
                        This will permanently reject the application for <strong>{{ $application->business_name }}</strong>. The user can submit a new application later.
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
</script>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    bottom: 0.5rem;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -1.75rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 0.5rem;
}
</style>
@endpush
