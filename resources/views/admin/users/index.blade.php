@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Users</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="ti ti-users fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Members</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['total_users']) }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        <span class="text-success me-1">+{{ $stats['new_this_month'] }}</span>
                                        <span class="text-muted op-7 fs-11">this month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="ti ti-user-check fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Active Members</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['active_users']) }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        <span class="text-success me-1">{{ round(($stats['active_users']/$stats['total_users'])*100, 1) }}%</span>
                                        <span class="text-muted op-7 fs-11">active rate</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="ti ti-user-plus fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">New This Month</p>
                                        <h4 class="fw-semibold mt-1">{{ number_format($stats['new_this_month']) }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        <span class="text-info me-1">{{ now()->format('M Y') }}</span>
                                        <span class="text-muted op-7 fs-11">current month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="ti ti-coins fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Commissions</p>
                                        <h4 class="fw-semibold mt-1">৳{{ number_format($stats['total_commissions'], 2) }}</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div>
                                        <span class="text-muted op-7 fs-11">all time earnings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            User Management
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-add-line"></i> Add User
                            </a>
                            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportUsers('csv')">Export CSV</a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportUsers('excel')">Export Excel</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bulkActionModal()">Bulk Actions</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- DataTable will load here -->
                        <div class="table-responsive">
                            <table id="usersDataTable" class="table text-nowrap table-striped table-hover" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Rank</th>
                                        <th>Status</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>PV Points</th>
                                        <th>Downline</th>
                                        <th>Commissions</th>
                                        <th>Joined</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" id="bulkAction">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Users</option>
                            <option value="deactivate">Deactivate Users</option>
                            <option value="suspend">Suspend Users</option>
                            <option value="delete">Delete Users</option>
                        </select>
                    </div>
                    <div id="selectedUsersCount" class="text-muted"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="executeBulkAction()">Execute</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                    <div id="deleteUserInfo"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteUser()">Delete User</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- FontAwesome Icons (Fallback) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Tabler Icons (Alternative) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons-sprite.svg">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/tabler-icons.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
/* Enhanced horizontal scrolling for mobile */
.table-responsive {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    scrollbar-width: thin; /* Firefox */
    scrollbar-color: #6c757d #f8f9fa; /* Firefox */
    max-width: 100%;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

/* Custom scrollbar for webkit browsers */
.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #495057;
}

/* Fixed table layout for consistent column widths */
#usersDataTable {
    table-layout: fixed;
    min-width: 1200px; /* Minimum table width */
    margin-bottom: 0;
}

/* Ensure table cells don't wrap */
#usersDataTable td,
#usersDataTable th {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-color: #dee2e6;
}

/* User name column can wrap since it's wider */
#usersDataTable td:nth-child(2) {
    white-space: normal;
    overflow: visible;
}

/* Action buttons styling */
.btn-group .btn-sm {
    padding: 0.25rem 0.4rem;
    font-size: 0.8rem;
}

/* Action column adjustments */
@media (max-width: 576px) {
    #usersDataTable td:last-child {
        min-width: 120px;
        white-space: normal;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.125rem;
    }
    
    .btn-group .btn-sm {
        padding: 0.2rem 0.3rem;
        font-size: 0.7rem;
        min-width: 35px;
        flex: 1;
    }
}

@media (min-width: 577px) and (max-width: 992px) {
    .btn-group .btn-sm {
        padding: 0.2rem 0.3rem;
        font-size: 0.75rem;
    }
}

/* Mobile specific optimizations */
@media (max-width: 768px) {
    .card-body {
        padding: 0.5rem;
    }
    
    .table-responsive {
        margin: -0.5rem;
        margin-top: 0;
        padding: 0.5rem;
        border-radius: 0.375rem;
        position: relative;
    }
    
    /* Show scroll hint on mobile */
    .table-responsive::before {
        content: "💡 Tip: Scroll horizontally to see all columns";
        display: block;
        text-align: center;
        font-size: 0.7rem;
        color: #6c757d;
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        padding: 0.4rem;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #e1f5fe;
        font-weight: 500;
    }
    
    /* Improve table container */
    #usersDataTable {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
}

/* Hide scroll hint on larger screens */
@media (min-width: 769px) {
    .table-responsive::before {
        display: none;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .table-responsive {
        margin: 0;
        padding: 0;
        border-radius: 0.375rem;
    }
}

/* Sticky first and last columns for better mobile experience */
@media (max-width: 768px) {
    /* Remove sticky positioning on mobile for better visibility */
    #usersDataTable th,
    #usersDataTable td {
        position: static !important;
        background: transparent !important;
        z-index: auto !important;
        border: 1px solid #dee2e6;
    }
    
    /* Ensure proper table layout on mobile */
    #usersDataTable {
        table-layout: auto;
        min-width: 1000px; /* Reduced minimum width for mobile */
    }
    
    /* Better mobile table styling */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background: white;
    }
    
    /* Action column mobile optimization */
    #usersDataTable td:last-child {
        min-width: 120px;
        white-space: normal;
        padding: 0.5rem 0.25rem;
    }
    
    /* Horizontal button group styling for mobile */
    .btn-group {
        width: 100%;
        display: flex;
        gap: 0.125rem;
        justify-content: center;
    }
    
    .btn-group .btn {
        text-align: center;
        font-size: 0.7rem;
        padding: 0.25rem 0.3rem;
        border-radius: 0.25rem !important;
        flex: 1;
        min-width: 32px;
    }
    
    /* Table header improvements */
    #usersDataTable thead th {
        background-color: #343a40 !important;
        color: white !important;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.75rem 0.5rem;
        border-color: #495057;
    }
    
    /* Table body improvements */
    #usersDataTable tbody td {
        padding: 0.6rem 0.4rem;
        font-size: 0.8rem;
        vertical-align: middle;
    }
    
    /* User column special handling */
    #usersDataTable td:nth-child(2) {
        min-width: 180px;
        white-space: normal;
    }
    
    /* Checkbox column */
    #usersDataTable th:first-child,
    #usersDataTable td:first-child {
        width: 40px;
        min-width: 40px;
        text-align: center;
        padding: 0.5rem 0.25rem;
    }
}
</style>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#usersDataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: false, // Disable responsive mode to use horizontal scroll
        scrollX: true, // Enable horizontal scrolling
        scrollCollapse: true,
        ajax: {
            url: '{{ route("admin.users.index") }}',
            type: 'GET',
            data: function(d) {
                // Add custom filters if needed
                d.role = $('#roleFilter').val();
                d.status = $('#statusFilter').val();
                d.rank = $('#rankFilter').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false, width: '50px', render: function(data, type, row) {
                return '<input type="checkbox" class="user-checkbox" value="' + data + '">';
            }},
            { data: 'name', name: 'name', orderable: true, width: '200px', render: function(data, type, row) {
                return '<div class="d-flex align-items-center">' +
                       '<div class="avatar-sm me-2">' +
                       '<img src="' + (row.avatar || '/assets/img/default-avatar.svg') + '" class="rounded-circle" width="32" height="32">' +
                       '</div>' +
                       '<div>' +
                       '<div class="fw-bold">' + (row.full_name || data || 'N/A') + '</div>' +
                       '<small class="text-muted">' + (row.email || 'N/A') + '</small>' +
                       '</div>' +
                       '</div>';
            }},
            { data: 'role', name: 'role', width: '80px' },
            { data: 'rank', name: 'rank', width: '80px' },
            { data: 'status', name: 'status', width: '90px' },
            { data: 'total_orders', name: 'total_orders', width: '80px' },
            { data: 'total_spent', name: 'total_spent', width: '100px' },
            { data: 'total_pv', name: 'total_pv', width: '80px' },
            { data: 'downline_count', name: 'downline_count', width: '80px' },
            { data: 'total_commissions', name: 'total_commissions', width: '120px' },
            { data: 'created_at', name: 'created_at', width: '100px' },
            { data: 'id', name: 'actions', orderable: false, searchable: false, width: '160px', className: 'text-center', render: function(data, type, row) {
                return '<div class="btn-group" role="group">' +
                       '<button class="btn btn-sm btn-outline-primary" onclick="viewUser(' + data + ')" title="View User">' +
                       '<i class="bi bi-eye ti ti-eye fas fa-eye d-lg-none"></i><span class="d-none d-lg-inline"><i class="bi bi-eye ti ti-eye fas fa-eye me-1"></i>View</span></button>' +
                       '<button class="btn btn-sm btn-outline-warning" onclick="editUser(' + data + ')" title="Edit User">' +
                       '<i class="bi bi-pencil ti ti-edit fas fa-edit d-lg-none"></i><span class="d-none d-lg-inline"><i class="bi bi-pencil ti ti-edit fas fa-edit me-1"></i>Edit</span></button>' +
                       '<button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' + data + ')" title="Delete User">' +
                       '<i class="bi bi-trash ti ti-trash fas fa-trash d-lg-none"></i><span class="d-none d-lg-inline"><i class="bi bi-trash ti ti-trash fas fa-trash me-1"></i>Delete</span></button>' +
                       '</div>';
            }}
        ],
        order: [[10, 'desc']], // Order by created_at descending
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            processing: '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>',
            emptyTable: "No users found",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "Showing 0 to 0 of 0 users",
            infoFiltered: "(filtered from _MAX_ total users)",
            lengthMenu: "Show _MENU_ users per page",
            search: "Search users:",
            zeroRecords: "No matching users found"
        },
        drawCallback: function() {
            // Reinitialize tooltips after table redraw
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Update select all checkbox
            updateSelectAllCheckbox();
        }
    });

    // Handle select all checkbox
    $('#checkAll').on('change', function() {
        var isChecked = this.checked;
        table.$('input[type="checkbox"].user-checkbox').prop('checked', isChecked);
        updateBulkActionButton();
    });

    // Handle individual checkboxes
    $('#usersDataTable').on('change', 'input[type="checkbox"].user-checkbox', function() {
        updateSelectAllCheckbox();
        updateBulkActionButton();
    });

    // Filter handlers
    $('#roleFilter, #statusFilter, #rankFilter').on('change', function() {
        table.ajax.reload();
    });

    // Custom search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});

// Update select all checkbox state
function updateSelectAllCheckbox() {
    var table = $('#usersDataTable').DataTable();
    var checkboxes = table.$('input[type="checkbox"].user-checkbox');
    var checkedBoxes = table.$('input[type="checkbox"].user-checkbox:checked');
    
    var selectAllCheckbox = $('#checkAll')[0];
    if (!selectAllCheckbox) return; // Safety check
    
    if (checkboxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    } else if (checkedBoxes.length === checkboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
    } else if (checkedBoxes.length > 0) {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
    } else {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
    }
}

// Update bulk action button
function updateBulkActionButton() {
    var table = $('#usersDataTable').DataTable();
    var checkedBoxes = table.$('input[type="checkbox"].user-checkbox:checked');
    
    if (checkedBoxes.length > 0) {
        $('#selectedUsersCount').text(`${checkedBoxes.length} user(s) selected`);
    } else {
        $('#selectedUsersCount').text('No users selected');
    }
}

// Delete user function
var userToDelete = null;
function deleteUser(userId) {
    userToDelete = userId;
    $('#deleteUserModal').modal('show');
}

function confirmDeleteUser() {
    if (userToDelete) {
        $.ajax({
            url: `/admin/users/${userToDelete}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteUserModal').modal('hide');
                $('#usersDataTable').DataTable().ajax.reload();
                
                // Show success message
                showNotification('success', 'User deleted successfully');
            },
            error: function(xhr) {
                showNotification('error', 'Error deleting user: ' + xhr.responseJSON.message);
            }
        });
    }
}

// Bulk actions
function bulkActionModal() {
    var table = $('#usersDataTable').DataTable();
    var checkedBoxes = table.$('input[type="checkbox"].user-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        showNotification('warning', 'Please select at least one user');
        return;
    }
    
    updateBulkActionButton();
    $('#bulkActionModal').modal('show');
}

function executeBulkAction() {
    var action = $('#bulkAction').val();
    if (!action) {
        showNotification('warning', 'Please select an action');
        return;
    }
    
    var table = $('#usersDataTable').DataTable();
    var checkedBoxes = table.$('input[type="checkbox"].user-checkbox:checked');
    var userIds = [];
    
    checkedBoxes.each(function() {
        userIds.push($(this).val());
    });
    
    $.ajax({
        url: '{{ route("admin.users.bulk-action") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            action: action,
            user_ids: userIds
        },
        success: function(response) {
            $('#bulkActionModal').modal('hide');
            $('#usersDataTable').DataTable().ajax.reload();
            
            showNotification('success', response.message);
        },
        error: function(xhr) {
            showNotification('error', 'Error: ' + xhr.responseJSON.message);
        }
    });
}

// View user function
function viewUser(userId) {
    // Open user details in modal or redirect to user profile
    window.open(`/admin/users/${userId}`, '_blank');
}

// Edit user function  
function editUser(userId) {
    // Redirect to user edit page
    window.location.href = `/admin/users/${userId}/edit`;
}

// Export functions
function exportUsers(format) {
    var url = '{{ route("admin.users.export") }}?format=' + format;
    window.open(url, '_blank');
}

// Notification function
function showNotification(type, message) {
    // Using basic alert for now - can be replaced with a proper notification system
    var alertClass = type === 'success' ? 'alert-success' : 
                    type === 'error' ? 'alert-danger' : 
                    type === 'warning' ? 'alert-warning' : 'alert-info';
    
    var notification = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing notifications
    $('.alert').remove();
    
    // Add new notification at the top
    $('main').prepend(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush
