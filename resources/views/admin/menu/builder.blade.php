@extends('admin.layouts.app')

@section('title', 'Menu Builder')

@section('content')
<div class="main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Menu Builder</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menu Builder</li>
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
                                <i class="bx bx-sitemap me-2"></i>
                                Drag & Drop Menu Builder
                            </h3>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success btn-sm" onclick="saveMenuOrder()">
                                    <i class="bx bx-save"></i> Save Order
                                </button>
                                <a href="{{ route('admin.menu.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-plus"></i> Add Menu
                                </a>
                                <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bx bx-list-ul"></i> List View
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Instructions:</strong> Drag and drop menu items to reorder them. You can also drag child items to different parents or make them root items.
                            </div>

                            <div id="menu-builder" class="menu-builder">
                                <div class="menu-items" id="menuItems">
                                    @foreach($menus as $menu)
                                        @include('admin.menu.partials.menu-builder-item', ['menu' => $menu])
                                    @endforeach
                                </div>

                                @if($menus->isEmpty())
                                    <div class="empty-state text-center py-5">
                                        <i class="bx bx-menu-alt-left display-1 text-muted"></i>
                                        <h4 class="text-muted mt-3">No Menu Items</h4>
                                        <p class="text-muted">Create your first menu item to get started.</p>
                                        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
                                            <i class="bx bx-plus"></i> Create Menu Item
                                        </a>
                                    </div>
                                @endif
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

<!-- Quick Edit Modal -->
<div class="modal fade" id="quickEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickEditForm">
                <div class="modal-body">
                    <input type="hidden" id="editMenuId">
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="editIcon" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="editIcon" placeholder="bx bx-home">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBadgeText" class="form-label">Badge Text</label>
                                <input type="text" class="form-control" id="editBadgeText">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBadgeColor" class="form-label">Badge Color</label>
                                <select class="form-select" id="editBadgeColor">
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="warning">Warning</option>
                                    <option value="info">Info</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="editIsActive">
                        <label class="form-check-label" for="editIsActive">
                            Active
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.css" rel="stylesheet">
<style>
.menu-builder {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    min-height: 400px;
}

.menu-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 8px;
    position: relative;
    transition: all 0.2s ease;
}

.menu-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.menu-item.sortable-ghost {
    opacity: 0.4;
}

.menu-item.sortable-drag {
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transform: rotate(5deg);
}

.menu-item-header {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    cursor: move;
    border-bottom: 1px solid #f1f3f4;
}

.menu-item-content {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-item-actions {
    display: flex;
    gap: 8px;
}

.menu-item-children {
    padding: 8px 16px 16px 32px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.child-menu-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-bottom: 6px;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    cursor: move;
}

.child-menu-item:hover {
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.menu-drag-handle {
    color: #6c757d;
    margin-right: 8px;
    cursor: move;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-indicator.active {
    background: #28a745;
}

.status-indicator.inactive {
    background: #dc3545;
}

.empty-state {
    background: white;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    margin: 20px 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
let sortableInstances = [];

$(document).ready(function() {
    initializeSortable();
    
    // Quick edit form
    $('#quickEditForm').on('submit', function(e) {
        e.preventDefault();
        updateMenuItem();
    });
});

function initializeSortable() {
    // Main menu sortable
    const menuItems = document.getElementById('menuItems');
    if (menuItems) {
        const sortable = Sortable.create(menuItems, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.menu-drag-handle',
            onEnd: function(evt) {
                // Menu order will be saved when Save Order is clicked
            }
        });
        sortableInstances.push(sortable);
    }

    // Child menu sortables
    document.querySelectorAll('.menu-item-children').forEach(function(element) {
        const sortable = Sortable.create(element, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.menu-drag-handle',
            group: 'shared',
            onEnd: function(evt) {
                // Child order will be saved when Save Order is clicked
            }
        });
        sortableInstances.push(sortable);
    });
}

function saveMenuOrder() {
    const menuOrder = [];
    
    document.querySelectorAll('#menuItems > .menu-item').forEach(function(menuElement, index) {
        const menuId = menuElement.dataset.menuId;
        const menuData = {
            id: menuId,
            parent_id: null,
            sort_order: index + 1,
            children: []
        };

        // Get children
        const childrenContainer = menuElement.querySelector('.menu-item-children');
        if (childrenContainer) {
            childrenContainer.querySelectorAll('.child-menu-item').forEach(function(childElement, childIndex) {
                const childId = childElement.dataset.menuId;
                menuData.children.push({
                    id: childId,
                    parent_id: menuId,
                    sort_order: childIndex + 1
                });
            });
        }

        menuOrder.push(menuData);
    });

    $.ajax({
        url: '{{ route("admin.menu.update-order") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            menu_order: menuOrder
        },
        success: function(response) {
            if (response.success) {
                showToast('Menu order saved successfully', 'success');
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error saving menu order', 'error');
        }
    });
}

function quickEdit(menuId) {
    const menuElement = document.querySelector(`[data-menu-id="${menuId}"]`);
    const title = menuElement.querySelector('.menu-title').textContent.trim();
    const icon = menuElement.dataset.icon || '';
    const badgeText = menuElement.dataset.badgeText || '';
    const badgeColor = menuElement.dataset.badgeColor || 'primary';
    const isActive = menuElement.dataset.isActive === '1';

    $('#editMenuId').val(menuId);
    $('#editTitle').val(title);
    $('#editIcon').val(icon);
    $('#editBadgeText').val(badgeText);
    $('#editBadgeColor').val(badgeColor);
    $('#editIsActive').prop('checked', isActive);

    $('#quickEditModal').modal('show');
}

function updateMenuItem() {
    const menuId = $('#editMenuId').val();
    const formData = {
        _token: '{{ csrf_token() }}',
        _method: 'PATCH',
        title: $('#editTitle').val(),
        icon: $('#editIcon').val(),
        badge_text: $('#editBadgeText').val(),
        badge_color: $('#editBadgeColor').val(),
        is_active: $('#editIsActive').is(':checked') ? 1 : 0
    };

    $.ajax({
        url: `/admin/menu/${menuId}`,
        method: 'POST',
        data: formData,
        success: function(response) {
            showToast('Menu item updated successfully', 'success');
            $('#quickEditModal').modal('hide');
            location.reload(); // Reload to show changes
        },
        error: function() {
            showToast('Error updating menu item', 'error');
        }
    });
}

function toggleMenuStatus(menuId) {
    $.ajax({
        url: `/admin/menu/${menuId}/toggle-status`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                const statusIndicator = document.querySelector(`[data-menu-id="${menuId}"] .status-indicator`);
                if (response.status) {
                    statusIndicator.classList.remove('inactive');
                    statusIndicator.classList.add('active');
                } else {
                    statusIndicator.classList.remove('active');
                    statusIndicator.classList.add('inactive');
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

function deleteMenuItem(menuId) {
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
                        document.querySelector(`[data-menu-id="${menuId}"]`).remove();
                    } else {
                        showToast(response.message, 'error');
                    }
                },
                error: function() {
                    showToast('Error deleting menu item', 'error');
                }
            });
        }
    });
}

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
