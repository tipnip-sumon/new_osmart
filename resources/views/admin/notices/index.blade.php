@extends('admin.layouts.app')

@section('title', 'Admin Notices Management')

@section('content')
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">Admin Notices</h4>
        <ol class="breadcrumb mb-2">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-home me-2"></i>Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Admin Notices</li>
        </ol>
    </div>
    <div class="page-rightheader">
        <div class="btn btn-list">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                <i class="fe fe-plus me-2"></i>Add New Notice
            </button>
            <button class="btn btn-danger d-none" id="bulkDeleteBtn">
                <i class="fe fe-trash-2 me-2"></i>Delete Selected
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Notice Management</div>
                <div class="card-options">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="noticesTable">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Notice Modal -->
<div class="modal fade" id="addNoticeModal" tabindex="-1" aria-labelledby="addNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoticeModalLabel">Add New Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNoticeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            
            <!-- Emoji Quick Access -->
            <div class="emoji-toolbar mb-2">
                <label class="form-label text-muted small">Quick Emojis:</label>
                <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Quick emojis">
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="📢">📢</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="�">�</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="⚠️">⚠️</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="✅">✅</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="❌">❌</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="ℹ️">ℹ️</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🎉">🎉</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🚨">🚨</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="💡">💡</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🔥">🔥</button>
                </div>
                <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Ecommerce emojis">
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🛍️" title="Shopping">🛍️</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🛒" title="Cart">🛒</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💰" title="Money">💰</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💳" title="Payment">💳</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="�️" title="Price Tag">�️</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💸" title="Sale">💸</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🎁" title="Gift">🎁</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="�" title="Package">�</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🚚" title="Delivery">🚚</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="⭐" title="Rating">⭐</button>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Fire & Trending emojis">
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="�" title="Hot Deal">�</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="⚡" title="Flash Sale">⚡</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="💥" title="Explosive Offer">💥</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🚀" title="Trending">🚀</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="�" title="Growing">�</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🎯" title="Target">🎯</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="💎" title="Premium">💎</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🏆" title="Winner">🏆</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🌟" title="Special">🌟</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🎊" title="Celebration">🎊</button>
                </div>
            </div>                            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Enter notice message" maxlength="500" required></textarea>
                            <small class="form-text text-muted">Maximum 500 characters</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="">Select Type</option>
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="priority" id="priority" min="1" max="10" value="5" required>
                            <small class="form-text text-muted">1 = Lowest, 10 = Highest</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date" id="start_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date" id="end_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNoticeBtn">
                    <i class="fe fe-save me-2"></i>Save Notice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Notice Modal -->
<div class="modal fade" id="editNoticeModal" tabindex="-1" aria-labelledby="editNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNoticeModalLabel">Edit Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editNoticeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_notice_id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            
            <!-- Emoji Quick Access -->
            <div class="emoji-toolbar mb-2">
                <label class="form-label text-muted small">Quick Emojis:</label>
                <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Quick emojis">
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="📢" data-target="#edit_message">📢</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="�" data-target="#edit_message">�</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="⚠️" data-target="#edit_message">⚠️</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="✅" data-target="#edit_message">✅</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="❌" data-target="#edit_message">❌</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="ℹ️" data-target="#edit_message">ℹ️</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🎉" data-target="#edit_message">🎉</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🚨" data-target="#edit_message">🚨</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="💡" data-target="#edit_message">💡</button>
                    <button type="button" class="btn btn-outline-secondary emoji-btn" data-emoji="🔥" data-target="#edit_message">🔥</button>
                </div>
                <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Ecommerce emojis">
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🛍️" data-target="#edit_message" title="Shopping">🛍️</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🛒" data-target="#edit_message" title="Cart">🛒</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💰" data-target="#edit_message" title="Money">💰</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💳" data-target="#edit_message" title="Payment">💳</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="�️" data-target="#edit_message" title="Price Tag">�️</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="💸" data-target="#edit_message" title="Sale">💸</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🎁" data-target="#edit_message" title="Gift">🎁</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="�" data-target="#edit_message" title="Package">�</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="🚚" data-target="#edit_message" title="Delivery">🚚</button>
                    <button type="button" class="btn btn-outline-primary emoji-btn" data-emoji="⭐" data-target="#edit_message" title="Rating">⭐</button>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Fire & Trending emojis">
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="�" data-target="#edit_message" title="Hot Deal">�</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="⚡" data-target="#edit_message" title="Flash Sale">⚡</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="💥" data-target="#edit_message" title="Explosive Offer">💥</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🚀" data-target="#edit_message" title="Trending">🚀</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="�" data-target="#edit_message" title="Growing">�</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🎯" data-target="#edit_message" title="Target">🎯</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="💎" data-target="#edit_message" title="Premium">💎</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🏆" data-target="#edit_message" title="Winner">🏆</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🌟" data-target="#edit_message" title="Special">🌟</button>
                    <button type="button" class="btn btn-outline-danger emoji-btn" data-emoji="🎊" data-target="#edit_message" title="Celebration">🎊</button>
                </div>
            </div>                            <textarea class="form-control" name="message" id="edit_message" rows="3" placeholder="Enter notice message" maxlength="500" required></textarea>
                            <small class="form-text text-muted">Maximum 500 characters</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="edit_type" required>
                                <option value="">Select Type</option>
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="priority" id="edit_priority" min="1" max="10" required>
                            <small class="form-text text-muted">1 = Lowest, 10 = Highest</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date" id="edit_start_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date" id="edit_end_date">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active">
                                <label class="form-check-label" for="edit_is_active">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateNoticeBtn">
                    <i class="fe fe-save me-2"></i>Update Notice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Notice Modal -->
<div class="modal fade" id="viewNoticeModal" tabindex="-1" aria-labelledby="viewNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNoticeModalLabel">Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewNoticeContent">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<style>
.notice-type-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.notice-priority {
    font-weight: bold;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    color: white;
}
.priority-high { background-color: #dc3545; }
.priority-medium { background-color: #ffc107; color: #000; }
.priority-low { background-color: #28a745; }
.character-count {
    float: right;
    font-size: 0.8rem;
    color: #6c757d;
}

/* Emoji Toolbar Styles */
.emoji-toolbar {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
}
.emoji-toolbar label {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}
.emoji-btn {
    font-size: 1.1rem;
    padding: 0.25rem 0.5rem;
    margin: 0.125rem;
    border-radius: 0.25rem;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    user-select: none;
}
.emoji-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.emoji-btn:active {
    transform: scale(0.95);
}
.emoji-btn.btn-success {
    animation: bounce 0.3s ease-in-out;
}
@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    
    // Initialize DataTable
    try {
    var table = $('#noticesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.notices.data") }}',
            type: 'GET',
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.type = $('#typeFilter').val();
            },
            error: function(xhr, error, thrown) {
                alert('Error loading notices data: ' + error + '\nStatus: ' + xhr.status);
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="form-check-input notice-checkbox" value="' + data + '">';
                }
            },
            { 
                data: 'message', 
                name: 'message',
                render: function(data, type, row) {
                    return data && data.length > 50 ? data.substring(0, 50) + '...' : (data || '');
                }
            },
            { 
                data: 'type', 
                name: 'type',
                render: function(data, type, row) {
                    var badgeClass = '';
                    switch(data) {
                        case 'info': badgeClass = 'bg-info'; break;
                        case 'success': badgeClass = 'bg-success'; break;
                        case 'warning': badgeClass = 'bg-warning text-dark'; break;
                        case 'danger': badgeClass = 'bg-danger'; break;
                        default: badgeClass = 'bg-secondary';
                    }
                    return '<span class="badge ' + badgeClass + ' notice-type-badge">' + (data ? data.toUpperCase() : 'UNKNOWN') + '</span>';
                }
            },
            { 
                data: 'priority', 
                name: 'priority',
                render: function(data, type, row) {
                    var priorityClass = '';
                    if (data >= 8) priorityClass = 'priority-high';
                    else if (data >= 5) priorityClass = 'priority-medium';
                    else priorityClass = 'priority-low';
                    return '<span class="notice-priority ' + priorityClass + '">' + (data || '0') + '</span>';
                }
            },
            { 
                data: 'is_active', 
                name: 'is_active',
                render: function(data, type, row) {
                    return data ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                }
            },
            { 
                data: 'start_date', 
                name: 'start_date',
                render: function(data, type, row) {
                    return data || 'Not set';
                }
            },
            { 
                data: 'end_date', 
                name: 'end_date',
                render: function(data, type, row) {
                    return data || 'Not set';
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at'
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return data || '<span class="text-muted">No actions</span>';
                }
            }
        ],
        order: [[3, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x"></i> Loading...',
            emptyTable: 'No notices found',
            zeroRecords: 'No matching notices found'
        }
    });

    } catch (error) {
        alert('Error initializing DataTable: ' + error.message);
    }

    // Filter functionality
    $('#statusFilter, #typeFilter').on('change', function() {
        table.draw();
    });

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.notice-checkbox').prop('checked', this.checked);
        toggleBulkDeleteButton();
    });

    // Individual checkbox change
    $(document).on('change', '.notice-checkbox', function() {
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        }
        toggleBulkDeleteButton();
    });

    function toggleBulkDeleteButton() {
        var checkedBoxes = $('.notice-checkbox:checked').length;
        if (checkedBoxes > 0) {
            $('#bulkDeleteBtn').removeClass('d-none');
        } else {
            $('#bulkDeleteBtn').addClass('d-none');
        }
    }

    // Character counter for message textarea
    $('#message, #edit_message').on('input', function() {
        var maxLength = 500;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        var counterId = $(this).attr('id') === 'message' ? '#messageCount' : '#editMessageCount';
        if ($(counterId).length === 0) {
            $(this).after('<div class="character-count" id="' + counterId.substring(1) + '"></div>');
        }
        $(counterId).text(remaining + ' characters remaining');
        
        if (remaining < 50) {
            $(counterId).addClass('text-warning');
        } else {
            $(counterId).removeClass('text-warning');
        }
    });

    // Add notice form submission
    $('#saveNoticeBtn').on('click', function() {
        var formData = new FormData($('#addNoticeForm')[0]);
        
        // Explicitly handle checkbox
        if ($('#is_active').is(':checked')) {
            formData.set('is_active', '1');
        } else {
            formData.set('is_active', '0');
        }
        
        $.ajax({
            url: '{{ route("admin.notices.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#saveNoticeBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                clearValidationErrors('#addNoticeForm');
            },
            success: function(response) {
                if (response.success) {
                    $('#addNoticeModal').modal('hide');
                    $('#addNoticeForm')[0].reset();
                    table.draw();
                    showToast('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    showValidationErrors(errors, '#addNoticeForm');
                }
                showToast('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#saveNoticeBtn').prop('disabled', false).html('<i class="fe fe-save me-2"></i>Save Notice');
            }
        });
    });

    // View notice
    $(document).on('click', '.view-notice', function() {
        var noticeId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.notices.show", ":id") }}'.replace(':id', noticeId),
            type: 'GET',
            beforeSend: function() {
                $('#viewNoticeContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
                $('#viewNoticeModal').modal('show');
            },
            success: function(response) {
                if (response.success) {
                    var notice = response.data;
                    var statusBadge = notice.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                    var typeBadge = '<span class="badge bg-' + (notice.type === 'warning' ? 'warning text-dark' : notice.type) + '">' + notice.type.toUpperCase() + '</span>';
                    
                    var content = `
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Message:</label>
                                <div class="p-3 bg-light border rounded">${notice.message}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Type:</label>
                                <div>${typeBadge}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Priority:</label>
                                <div><span class="badge bg-primary">${notice.priority}</span></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <div>${statusBadge}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Created:</label>
                                <div>${new Date(notice.created_at).toLocaleString()}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Start Date:</label>
                                <div>${notice.start_date || 'Not set'}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">End Date:</label>
                                <div>${notice.end_date || 'Not set'}</div>
                            </div>
                        </div>
                    `;
                    $('#viewNoticeContent').html(content);
                }
            },
            error: function(xhr) {
                $('#viewNoticeContent').html('<div class="alert alert-danger">Error loading notice details</div>');
            }
        });
    });

    // Edit notice
    $(document).on('click', '.edit-notice', function() {
        var noticeId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.notices.show", ":id") }}'.replace(':id', noticeId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var notice = response.data;
                    $('#edit_notice_id').val(notice.id);
                    $('#edit_message').val(notice.message);
                    $('#edit_type').val(notice.type);
                    $('#edit_priority').val(notice.priority);
                    $('#edit_is_active').prop('checked', notice.is_active);
                    
                    // Format dates for datetime-local input
                    if (notice.start_date) {
                        $('#edit_start_date').val(formatDateForInput(notice.start_date));
                    }
                    if (notice.end_date) {
                        $('#edit_end_date').val(formatDateForInput(notice.end_date));
                    }
                    
                    $('#editNoticeModal').modal('show');
                }
            },
            error: function(xhr) {
                showToast('Error', 'Failed to load notice details', 'error');
            }
        });
    });

    // Update notice form submission
    $('#updateNoticeBtn').on('click', function() {
        var noticeId = $('#edit_notice_id').val();
        var formData = new FormData($('#editNoticeForm')[0]);
        
        // Explicitly handle checkbox
        if ($('#edit_is_active').is(':checked')) {
            formData.set('is_active', '1');
        } else {
            formData.set('is_active', '0');
        }
        
        $.ajax({
            url: '{{ route("admin.notices.update", ":id") }}'.replace(':id', noticeId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#updateNoticeBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
                clearValidationErrors('#editNoticeForm');
            },
            success: function(response) {
                if (response.success) {
                    $('#editNoticeModal').modal('hide');
                    table.draw();
                    showToast('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    showValidationErrors(errors, '#editNoticeForm');
                }
                showToast('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            },
            complete: function() {
                $('#updateNoticeBtn').prop('disabled', false).html('<i class="fe fe-save me-2"></i>Update Notice');
            }
        });
    });

    // Toggle status
    $(document).on('click', '.toggle-status', function() {
        var noticeId = $(this).data('id');
        
        $.ajax({
            url: '{{ route("admin.notices.toggle-status", ":id") }}'.replace(':id', noticeId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.draw();
                    showToast('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                showToast('Error', 'Failed to update status', 'error');
            }
        });
    });

    // Delete notice
    $(document).on('click', '.delete-notice', function() {
        var noticeId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this notice?')) {
            $.ajax({
                url: '{{ route("admin.notices.destroy", ":id") }}'.replace(':id', noticeId),
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        table.draw();
                        showToast('Success', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete notice', 'error');
                }
            });
        }
    });

    // Bulk delete
    $('#bulkDeleteBtn').on('click', function() {
        var selectedIds = [];
        $('.notice-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showToast('Warning', 'Please select notices to delete', 'warning');
            return;
        }
        
        if (confirm('Are you sure you want to delete ' + selectedIds.length + ' selected notices?')) {
            $.ajax({
                url: '{{ route("admin.notices.bulk-delete") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        table.draw();
                        $('#selectAll').prop('checked', false);
                        $('#bulkDeleteBtn').addClass('d-none');
                        showToast('Success', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete notices', 'error');
                }
            });
        }
    });

    // Helper functions
    function formatDateForInput(dateString) {
        var date = new Date(dateString);
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        var hours = String(date.getHours()).padStart(2, '0');
        var minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    function showValidationErrors(errors, formSelector) {
        $.each(errors, function(field, messages) {
            var input = $(formSelector + ' [name="' + field + '"]');
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(messages[0]);
        });
    }

    function clearValidationErrors(formSelector) {
        $(formSelector + ' .is-invalid').removeClass('is-invalid');
        $(formSelector + ' .invalid-feedback').text('');
    }

    function showToast(title, message, type) {
        var bgClass = '';
        switch(type) {
            case 'success': bgClass = 'bg-success'; break;
            case 'error': bgClass = 'bg-danger'; break;
            case 'warning': bgClass = 'bg-warning'; break;
            default: bgClass = 'bg-info';
        }
        
        var toast = `
            <div class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        if ($('.toast-container').length === 0) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
        }
        
        $('.toast-container').append(toast);
        $('.toast').last().toast('show');
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.toast').last().toast('hide');
        }, 5000);
    }

    // Emoji button functionality
    $(document).on('click', '.emoji-btn', function(e) {
        e.preventDefault();
        var emoji = $(this).data('emoji');
        var target = $(this).data('target');
        
        // Determine target textarea (default to #message if no target specified)
        var targetTextarea = target ? $(target) : $('#message');
        
        if (targetTextarea.length) {
            var currentText = targetTextarea.val();
            var cursorPos = targetTextarea[0].selectionStart;
            
            // Insert emoji at cursor position
            var newText = currentText.substring(0, cursorPos) + emoji + currentText.substring(cursorPos);
            targetTextarea.val(newText);
            
            // Set cursor position after the emoji
            var newCursorPos = cursorPos + emoji.length;
            targetTextarea[0].setSelectionRange(newCursorPos, newCursorPos);
            targetTextarea.focus();
            
            // Trigger input event for character counter
            targetTextarea.trigger('input');
            
            // Add visual feedback
            $(this).addClass('btn-success').removeClass('btn-outline-secondary btn-outline-primary btn-outline-danger');
            setTimeout(() => {
                $(this).removeClass('btn-success').addClass(function() {
                    if ($(this).hasClass('btn-outline-primary') || $(this).parent().hasClass('btn-group')) {
                        return $(this).data('target') ? 'btn-outline-secondary' : 'btn-outline-secondary';
                    }
                    return 'btn-outline-secondary';
                });
            }, 300);
        }
    });

    // Reset forms when modals are hidden
    $('#addNoticeModal').on('hidden.bs.modal', function() {
        $('#addNoticeForm')[0].reset();
        clearValidationErrors('#addNoticeForm');
        $('#messageCount').remove();
    });

    $('#editNoticeModal').on('hidden.bs.modal', function() {
        $('#editNoticeForm')[0].reset();
        clearValidationErrors('#editNoticeForm');
        $('#editMessageCount').remove();
    });
});
</script>
@endpush
