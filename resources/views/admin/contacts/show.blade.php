@extends('admin.layouts.app')

@section('title', 'Contact Details')

@section('content')
<div class="container-fluid my-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Contacts</a></li>
                            <li class="breadcrumb-item active">{{ $contact->reference_id }}</li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0">Contact Details</h1>
                    <p class="text-muted">Reference ID: {{ $contact->reference_id }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    @if($contact->status !== 'replied')
                    <button type="button" class="btn btn-success" id="replyBtn">
                        <i class="fas fa-reply"></i> Reply
                    </button>
                    @endif
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" id="markReadBtn">
                                <i class="fas fa-eye"></i> {{ $contact->status === 'read' ? 'Mark as Unread' : 'Mark as Read' }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" id="deleteBtn">
                                <i class="fas fa-trash"></i> Delete Contact
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>Contact Information
                        @if($contact->status === 'new')
                        <span class="badge bg-warning ms-2">Unread</span>
                        @endif
                        @if($contact->status === 'replied')
                        <span class="badge bg-success ms-2">Replied</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Full Name</label>
                                <p class="h6">{{ $contact->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <p class="h6">
                                    <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                        {{ $contact->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        @if($contact->phone)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone Number</label>
                                <p class="h6">
                                    <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                        {{ $contact->phone }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Subject</label>
                                <p class="h6">
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($contact->subject) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Newsletter Subscription</label>
                                <p class="h6">
                                    @if($contact->newsletter_subscription)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Subscribed
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times me-1"></i>Not Subscribed
                                    </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Submitted Date</label>
                                <p class="h6">{{ $contact->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label text-muted">Message</label>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0" style="white-space: pre-line;">{{ $contact->message }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply History -->
            @if($contact->status === 'replied' && $contact->replied_at)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Reply History
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success rounded-circle text-white d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Admin Reply</h6>
                                <small class="text-muted">{{ $contact->replied_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                            @if($contact->reply_message)
                            <div class="border rounded p-3 bg-success bg-opacity-10">
                                <p class="mb-0" style="white-space: pre-line;">{{ $contact->reply_message }}</p>
                            </div>
                            @else
                            <p class="text-muted fst-italic">Reply was sent via email.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($contact->status !== 'replied')
                        <button type="button" class="btn btn-success" id="quickReplyBtn">
                            <i class="fas fa-reply me-2"></i>Send Reply
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-outline-primary" id="toggleReadBtn">
                            @if($contact->status === 'read')
                            <i class="fas fa-eye-slash me-2"></i>Mark as Unread
                            @else
                            <i class="fas fa-eye me-2"></i>Mark as Read
                            @endif
                        </button>
                        
                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>Send Direct Email
                        </a>
                        
                        <button type="button" class="btn btn-outline-danger" id="quickDeleteBtn">
                            <i class="fas fa-trash me-2"></i>Delete Contact
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contact Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-1">{{ $customerStats['total_contacts'] }}</h4>
                                <small class="text-muted">Total Contacts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-1">{{ $customerStats['replied_contacts'] }}</h4>
                            <small class="text-muted">Replied</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Customer since:</small><br>
                        <strong>{{ $customerStats['first_contact_date'] }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="replyForm">
                <div class="modal-header">
                    <h5 class="modal-title">Reply to {{ $contact->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your reply will be sent to <strong>{{ $contact->email }}</strong> and will be marked as replied.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Original Message</label>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0" style="white-space: pre-line;">{{ $contact->message }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Your Reply <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="replyMessage" rows="6" required 
                                  placeholder="Type your reply message here..."></textarea>
                        <div class="form-text">This message will be sent via email to the customer.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const contactId = {{ $contact->id }};
    
    // Mark contact as read when page loads (if unread)
    @if($contact->status === 'new')
    $.post(`/admin/contacts/${contactId}/status`, {
        status: 'read',
        _token: $('meta[name="csrf-token"]').attr('content')
    });
    @endif
    
    // Reply button clicks
    $('#replyBtn, #quickReplyBtn').on('click', function() {
        $('#replyModal').modal('show');
    });
    
    // Reply form submission
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();
        
        const replyMessage = $('#replyMessage').val();
        
        if (!replyMessage.trim()) {
            Swal.fire('Warning!', 'Please enter a reply message.', 'warning');
            return;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...').prop('disabled', true);
        
        $.post(`/admin/contacts/${contactId}/reply`, {
            reply_message: replyMessage,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(response) {
            if (response.success) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    location.reload();
                });
                $('#replyModal').modal('hide');
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        })
        .fail(function(xhr) {
            let errorMessage = 'Failed to send reply. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire('Error!', errorMessage, 'error');
        })
        .always(function() {
            submitBtn.html(originalText).prop('disabled', false);
        });
    });
    
    // Toggle read status
    $('#markReadBtn, #toggleReadBtn').on('click', function(e) {
        e.preventDefault();
        
        const isRead = {{ $contact->status === 'read' ? 'true' : 'false' }};
        const newStatus = isRead ? 'new' : 'read';
        const action = isRead ? 'Mark as Unread' : 'Mark as Read';
        
        $.post(`/admin/contacts/${contactId}/status`, {
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
        .done(function(response) {
            if (response.success) {
                Swal.fire('Success!', `Contact has been marked as ${newStatus}.`, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error!', response.message, 'error');
            }
        })
        .fail(function() {
            Swal.fire('Error!', 'Failed to update contact status.', 'error');
        });
    });
    
    // Delete contact
    $('#deleteBtn, #quickDeleteBtn').on('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this contact!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/contacts/${contactId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            window.location.href = '{{ route("admin.contacts.index") }}';
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                })
                .fail(function() {
                    Swal.fire('Error!', 'Failed to delete contact.', 'error');
                });
            }
        });
    });
});
</script>
@endpush