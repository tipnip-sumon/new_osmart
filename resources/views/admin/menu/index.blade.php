@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
<div class="main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Menu Management</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menu Management</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="bx bx-menu me-2"></i>
                                Admin Menu Items
                            </h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.menu.builder') }}" class="btn btn-info btn-sm">
                                    <i class="bx bx-sitemap"></i> Menu Builder
                                </a>
                                <a href="{{ route('admin.menu.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus"></i> Add Menu
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="exportMenu()">
                                            <i class="bx bx-export me-2"></i>Export Menu
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                                            <i class="bx bx-import me-2"></i>Import Menu
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Filters -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Search menus..." id="searchInput">
                                </div>
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
                                        <option value="main">Main Menu</option>
                                        <option value="sidebar">Sidebar Only</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                        <i class="bx bx-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>

                            <!-- Menu Table -->
                            <div id="menu-table-container">
                                @include('admin.menu.partials.menu-table', ['menus' => $menus])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->

        </div>
        <!-- CONTAINER END -->
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Menu Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.menu.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="menu_file" class="form-label">Select JSON File</label>
                        <input type="file" class="form-control" id="menu_file" name="menu_file" accept=".json" required>
                        <div class="form-text">Upload a JSON file exported from menu management.</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="clear_existing" name="clear_existing">
                        <label class="form-check-label" for="clear_existing">
                            Clear existing menus before import
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;

    // Search functionality
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadMenus();
        }, 500);
    });

    // Filter functionality
    $('#statusFilter, #typeFilter').on('change', function() {
        loadMenus();
    });

    function loadMenus() {
        const searchQuery = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const type = $('#typeFilter').val();

        $.ajax({
            url: '{{ route("admin.menu.index") }}',
            method: 'GET',
            data: {
                search: searchQuery,
                status: status,
                type: type,
                ajax: 1
            },
            success: function(response) {
                $('#menu-table-container').html(response.html);
            },
            error: function() {
                showToast('Error loading menus', 'error');
            }
        });
    }

    function resetFilters() {
        $('#searchInput').val('');
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        loadMenus();
    }

    // Make resetFilters globally available
    window.resetFilters = resetFilters;
});

// Toggle status
function toggleStatus(menuId) {
    $.ajax({
        url: `/admin/menu/${menuId}/toggle-status`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                const statusBadge = $(`.status-badge[data-id="${menuId}"]`);
                if (response.status) {
                    statusBadge.removeClass('bg-danger').addClass('bg-success').text('Active');
                } else {
                    statusBadge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                }
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error updating status', 'error');
        }
    });
}

// Delete menu
function deleteMenu(menuId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/menu/${menuId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        $(`tr[data-id="${menuId}"]`).remove();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Error deleting menu', 'error');
                }
            });
        }
    });
}

// Duplicate menu
function duplicateMenu(menuId) {
    $.ajax({
        url: `/admin/menu/${menuId}/duplicate`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                location.reload();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error duplicating menu', 'error');
        }
    });
}

// Export menu
function exportMenu() {
    window.location.href = '{{ route("admin.menu.export") }}';
}

// Toast notification helper
function showToast(message, type = 'info') {
    const bgColor = type === 'success' ? 'bg-success' : 
                   type === 'error' ? 'bg-danger' : 
                   type === 'warning' ? 'bg-warning' : 'bg-info';
    
    const toast = $(`
        <div class="toast align-items-center text-white ${bgColor} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    $('.toast-container').append(toast);
    const bsToast = new bootstrap.Toast(toast[0]);
    bsToast.show();
    
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
@endpush
