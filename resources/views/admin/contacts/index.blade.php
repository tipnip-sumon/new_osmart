@extends('admin.layouts.app')

@section('title', 'Contact Management')

@section('content')
<div class="container-fluid my-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Contact Management</h1>
                    <p class="text-muted">Manage customer inquiries and send replies</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" id="exportBtn">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-tasks"></i> Bulk Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-action="mark_read">Mark as Read</a></li>
                            <li><a class="dropdown-item" href="#" data-action="mark_unread">Mark as Unread</a></li>
                            <li><a class="dropdown-item" href="#" data-action="mark_replied">Mark as Replied</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" data-action="delete">Delete Selected</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-inbox fa-2x"></i>
                    </div>
                    <h5 class="card-title">Total Contacts</h5>
                    <h3 class="text-primary">{{ $contacts->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <h5 class="card-title">Unread</h5>
                    <h3 class="text-warning">{{ $stats['unread'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-reply fa-2x"></i>
                    </div>
                    <h5 class="card-title">Replied</h5>
                    <h3 class="text-success">{{ $stats['replied'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h5 class="card-title">Newsletter</h5>
                    <h3 class="text-info">{{ $stats['newsletter'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, subject...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subject</label>
                    <select class="form-select" name="subject">
                        <option value="">All Subjects</option>
                        <option value="general" {{ request('subject') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="support" {{ request('subject') == 'support' ? 'selected' : '' }}>Support</option>
                        <option value="sales" {{ request('subject') == 'sales' ? 'selected' : '' }}>Sales</option>
                        <option value="partnership" {{ request('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Newsletter</label>
                    <select class="form-select" name="newsletter">
                        <option value="">All</option>
                        <option value="1" {{ request('newsletter') == '1' ? 'selected' : '' }}>Subscribed</option>
                        <option value="0" {{ request('newsletter') == '0' ? 'selected' : '' }}>Not Subscribed</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Contact Inquiries</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">Select All</label>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($contacts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllHeader" class="form-check-input">
                            </th>
                            <th>Contact Info</th>
                            <th>Subject</th>
                            <th>Message Preview</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr class="contact-row {{ $contact->status === 'new' ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input contact-checkbox" value="{{ $contact->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 14px;">
                                            {{ strtoupper(substr($contact->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $contact->name }}</h6>
                                        <small class="text-muted">{{ $contact->email }}</small><br>
                                        @if($contact->phone)
                                        <small class="text-muted">{{ $contact->phone }}</small>
                                        @endif
                                        @if($contact->newsletter_subscription)
                                        <span class="badge bg-info ms-1">Newsletter</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($contact->subject) }}</span>
                                <br>
                                <small class="text-muted">{{ $contact->reference_id }}</small>
                            </td>
                            <td>
                                <p class="mb-0 text-truncate" style="max-width: 200px;">
                                    {{ $contact->message }}
                                </p>
                            </td>
                            <td>
                                @if($contact->status === 'replied')
                                    <span class="badge bg-success">Replied</span>
                                @elseif($contact->status === 'read')
                                    <span class="badge bg-primary">Read</span>
                                @else
                                    <span class="badge bg-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $contact->created_at->format('M d, Y') }}</small><br>
                                <small class="text-muted">{{ $contact->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-success reply-btn" 
                                            data-contact-id="{{ $contact->id }}" title="Reply">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger delete-btn" 
                                            data-contact-id="{{ $contact->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3">
                <div class="text-muted">
                    Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} contacts
                </div>
                {{ $contacts->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Contacts Found</h5>
                <p class="text-muted">No contact inquiries match your current filters.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="replyForm">
                <div class="modal-header">
                    <h5 class="modal-title">Reply to Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customerName" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Customer Email</label>
                            <input type="text" class="form-control" id="customerEmail" readonly>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Original Message</label>
                        <div class="border rounded p-3 bg-light">
                            <p id="originalMessage" class="mb-0"></p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Your Reply <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="replyMessage" rows="6" required 
                                  placeholder="Type your reply message here..."></textarea>
                    </div>
                    <input type="hidden" id="contactId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Reply
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
    // Select All functionality
    $('#selectAll, #selectAllHeader').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.contact-checkbox').prop('checked', isChecked);
        $('#selectAll, #selectAllHeader').prop('checked', isChecked);
    });

    // Individual checkbox change
    $('.contact-checkbox').on('change', function() {
        const totalCheckboxes = $('.contact-checkbox').length;
        const checkedCheckboxes = $('.contact-checkbox:checked').length;
        const allChecked = totalCheckboxes === checkedCheckboxes;
        $('#selectAll, #selectAllHeader').prop('checked', allChecked);
    });

    // Reply button click
    $('.reply-btn').on('click', function() {
        const contactId = $(this).data('contact-id');
        
        // Fetch contact details
        $.get(`/admin/contacts/${contactId}`, function(response) {
            if (response.success) {
                const contact = response.data;
                $('#contactId').val(contact.id);
                $('#customerName').val(contact.name);
                $('#customerEmail').val(contact.email);
                $('#originalMessage').text(contact.message);
                $('#replyMessage').val('');
                $('#replyModal').modal('show');
            }
        }).fail(function() {
            Swal.fire('Error!', 'Failed to load contact details.', 'error');
        });
    });

    // Reply form submission
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();
        
        const contactId = $('#contactId').val();
        const replyMessage = $('#replyMessage').val();
        
        if (!replyMessage.trim()) {
            Swal.fire('Warning!', 'Please enter a reply message.', 'warning');
            return;
        }
        
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
        .fail(function() {
            Swal.fire('Error!', 'Failed to send reply. Please try again.', 'error');
        });
    });

    // Delete button click
    $('.delete-btn').on('click', function() {
        const contactId = $(this).data('contact-id');
        
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
                            location.reload();
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

    // Bulk actions
    $('.dropdown-menu a[data-action]').on('click', function(e) {
        e.preventDefault();
        const action = $(this).data('action');
        const selectedIds = $('.contact-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        if (selectedIds.length === 0) {
            Swal.fire('Warning!', 'Please select at least one contact.', 'warning');
            return;
        }
        
        let confirmText = 'Are you sure you want to perform this action?';
        if (action === 'delete') {
            confirmText = 'Are you sure you want to delete the selected contacts?';
        }
        
        Swal.fire({
            title: 'Confirm Action',
            text: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/admin/contacts/bulk-action', {
                    action: action,
                    contact_ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                })
                .done(function(response) {
                    if (response.success) {
                        Swal.fire('Success!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                })
                .fail(function() {
                    Swal.fire('Error!', 'Failed to perform bulk action.', 'error');
                });
            }
        });
    });

    // Export functionality
    $('#exportBtn').on('click', function() {
        window.location.href = '/admin/contacts/export/csv' + location.search;
    });
});
</script>
@endpush