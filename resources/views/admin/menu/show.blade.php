@extends('admin.layouts.app')

@section('title', 'Menu Details')

@section('content')
<div class="main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Menu Details</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $menu->title }}</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                @if($menu->icon)
                                    <i class="{{ $menu->icon }} me-2"></i>
                                @endif
                                {{ $menu->title }}
                                @if($menu->badge_text)
                                    <span class="badge bg-{{ $menu->badge_color }} ms-2">{{ $menu->badge_text }}</span>
                                @endif
                            </h3>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-warning btn-sm" onclick="toggleStatus({{ $menu->id }})">
                                    <i class="bx bx-toggle-{{ $menu->is_active ? 'right' : 'left' }}"></i>
                                    {{ $menu->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="duplicateMenu({{ $menu->id }})">
                                            <i class="bx bx-copy me-2"></i>Duplicate
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteMenu({{ $menu->id }})">
                                            <i class="bx bx-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Basic Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold text-muted" style="width: 120px;">Title:</td>
                                            <td>{{ $menu->title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Icon:</td>
                                            <td>
                                                @if($menu->icon)
                                                    <i class="{{ $menu->icon }}"></i>
                                                    <code class="ms-2">{{ $menu->icon }}</code>
                                                @else
                                                    <span class="text-muted">No icon</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $menu->is_active ? 'success' : 'danger' }}">
                                                    {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Sort Order:</td>
                                            <td><span class="badge bg-info">{{ $menu->sort_order }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Menu Type:</td>
                                            <td>
                                                <span class="badge bg-{{ $menu->menu_type === 'both' ? 'success' : ($menu->menu_type === 'main' ? 'info' : 'secondary') }}">
                                                    {{ ucfirst($menu->menu_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Navigation & Settings -->
                                <div class="col-md-6">
                                    <h5 class="mb-3">Navigation & Settings</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold text-muted" style="width: 120px;">Route:</td>
                                            <td>
                                                @if($menu->route)
                                                    <code>{{ $menu->route }}</code>
                                                    @try
                                                        <a href="{{ route($menu->route) }}" target="_blank" class="ms-2">
                                                            <i class="bx bx-link-external"></i>
                                                        </a>
                                                    @catch(\Exception $e)
                                                        <small class="text-danger ms-2">(Route not found)</small>
                                                    @endtry
                                                @else
                                                    <span class="text-muted">No route</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">URL:</td>
                                            <td>
                                                @if($menu->url)
                                                    <code>{{ $menu->url }}</code>
                                                    <a href="{{ $menu->url }}" target="_blank" class="ms-2">
                                                        <i class="bx bx-link-external"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">No custom URL</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Target:</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $menu->target }}</span>
                                                @if($menu->target === '_blank')
                                                    <small class="text-muted ms-2">(Opens in new window)</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">External:</td>
                                            <td>
                                                <span class="badge bg-{{ $menu->is_external ? 'warning' : 'secondary' }}">
                                                    {{ $menu->is_external ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Permission:</td>
                                            <td>
                                                @if($menu->permission)
                                                    <code>{{ $menu->permission }}</code>
                                                @else
                                                    <span class="text-muted">No permission check</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($menu->description)
                                <div class="mt-4">
                                    <h5 class="mb-3">Description</h5>
                                    <div class="alert alert-info">
                                        {{ $menu->description }}
                                    </div>
                                </div>
                            @endif

                            <!-- Badge Information -->
                            @if($menu->badge_text)
                                <div class="mt-4">
                                    <h5 class="mb-3">Badge Configuration</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td class="fw-bold text-muted" style="width: 100px;">Badge Text:</td>
                                                    <td>{{ $menu->badge_text }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-muted">Badge Color:</td>
                                                    <td>
                                                        <span class="badge bg-{{ $menu->badge_color }}">{{ $menu->badge_color }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <h6>Preview:</h6>
                                                <span class="badge bg-{{ $menu->badge_color }} fs-6">{{ $menu->badge_text }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Hierarchy Information -->
                            <div class="mt-4">
                                <h5 class="mb-3">Menu Hierarchy</h5>
                                <div class="row">
                                    <!-- Parent Information -->
                                    <div class="col-md-6">
                                        <h6>Parent Menu</h6>
                                        @if($menu->parent)
                                            <div class="border rounded p-3">
                                                <div class="d-flex align-items-center">
                                                    @if($menu->parent->icon)
                                                        <i class="{{ $menu->parent->icon }} me-2"></i>
                                                    @endif
                                                    <strong>{{ $menu->parent->title }}</strong>
                                                    <a href="{{ route('admin.menu.show', $menu->parent) }}" class="ms-auto btn btn-sm btn-outline-primary">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-muted text-center py-3">
                                                <i class="bx bx-info-circle fs-3"></i>
                                                <p class="mb-0">This is a root menu item</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Children Information -->
                                    <div class="col-md-6">
                                        <h6>Child Menus ({{ $menu->children->count() }})</h6>
                                        @if($menu->children->count() > 0)
                                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                                @foreach($menu->children->sortBy('sort_order') as $child)
                                                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                                        @if($child->icon)
                                                            <i class="{{ $child->icon }} me-2"></i>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <strong>{{ $child->title }}</strong>
                                                            @if($child->badge_text)
                                                                <span class="badge bg-{{ $child->badge_color }} ms-2">{{ $child->badge_text }}</span>
                                                            @endif
                                                            <div class="small text-muted">Order: {{ $child->sort_order }}</div>
                                                        </div>
                                                        <div class="d-flex gap-1">
                                                            <span class="badge bg-{{ $child->is_active ? 'success' : 'danger' }}">
                                                                {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                            </span>
                                                            <a href="{{ route('admin.menu.show', $child) }}" class="btn btn-xs btn-outline-primary">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-muted text-center py-3">
                                                <i class="bx bx-info-circle fs-3"></i>
                                                <p class="mb-0">No child menu items</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="mt-4">
                                <h5 class="mb-3">Timeline</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold text-muted" style="width: 100px;">Created:</td>
                                                <td>
                                                    {{ $menu->created_at->format('F d, Y \a\t g:i A') }}
                                                    <small class="text-muted">({{ $menu->created_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold text-muted">Updated:</td>
                                                <td>
                                                    {{ $menu->updated_at->format('F d, Y \a\t g:i A') }}
                                                    <small class="text-muted">({{ $menu->updated_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Back to List
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-primary">
                                        <i class="bx bx-edit"></i> Edit Menu
                                    </a>
                                    <a href="{{ route('admin.menu.builder') }}" class="btn btn-info">
                                        <i class="bx bx-sitemap"></i> Menu Builder
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="testMenuLink()">
                                    <i class="bx bx-link"></i> Test Menu Link
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="viewBreadcrumb()">
                                    <i class="bx bx-sitemap"></i> View Breadcrumb
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="copyMenuData()">
                                    <i class="bx bx-copy"></i> Copy Menu Data
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="exportSingleMenu()">
                                    <i class="bx bx-export"></i> Export Menu
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Preview -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Menu Preview</h5>
                        </div>
                        <div class="card-body">
                            <div class="menu-preview">
                                <div class="menu-item-preview {{ $menu->is_active ? '' : 'disabled' }}">
                                    @if($menu->icon)
                                        <i class="{{ $menu->icon }} me-2"></i>
                                    @endif
                                    <span>{{ $menu->title }}</span>
                                    @if($menu->badge_text)
                                        <span class="badge bg-{{ $menu->badge_color }} ms-2">{{ $menu->badge_text }}</span>
                                    @endif
                                </div>
                                @if($menu->children->count() > 0)
                                    <div class="child-preview mt-2">
                                        @foreach($menu->children->sortBy('sort_order')->take(3) as $child)
                                            <div class="child-menu-preview {{ $child->is_active ? '' : 'disabled' }}">
                                                @if($child->icon)
                                                    <i class="{{ $child->icon }} me-2"></i>
                                                @endif
                                                <span>{{ $child->title }}</span>
                                            </div>
                                        @endforeach
                                        @if($menu->children->count() > 3)
                                            <div class="text-muted small ms-3">... and {{ $menu->children->count() - 3 }} more</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="mb-1 text-primary">{{ $menu->children->count() }}</h4>
                                        <small class="text-muted">Children</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="mb-1 text-success">{{ $menu->children->where('is_active', true)->count() }}</h4>
                                    <small class="text-muted">Active</small>
                                </div>
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

@endsection

@push('scripts')
<script>
function testMenuLink() {
    @if($menu->route)
        try {
            window.open('{{ route($menu->route) }}', '{{ $menu->target }}');
        } catch (e) {
            showToast('Route not found or invalid', 'error');
        }
    @elseif($menu->url)
        window.open('{{ $menu->url }}', '{{ $menu->target }}');
    @else
        showToast('No link configured for this menu item', 'warning');
    @endif
}

function viewBreadcrumb() {
    const breadcrumb = [
        @foreach($menu->getBreadcrumb() as $item)
            '{{ $item->title }}',
        @endforeach
    ];
    
    Swal.fire({
        title: 'Menu Breadcrumb',
        html: '<div class="text-start"><strong>Path:</strong><br>' + breadcrumb.join(' → ') + '</div>',
        icon: 'info'
    });
}

function copyMenuData() {
    const menuData = {
        id: {{ $menu->id }},
        title: '{{ $menu->title }}',
        icon: '{{ $menu->icon }}',
        route: '{{ $menu->route }}',
        url: '{{ $menu->url }}',
        parent_id: {{ $menu->parent_id ?? 'null' }},
        sort_order: {{ $menu->sort_order }},
        is_active: {{ $menu->is_active ? 'true' : 'false' }},
        menu_type: '{{ $menu->menu_type }}',
        badge_text: '{{ $menu->badge_text }}',
        badge_color: '{{ $menu->badge_color }}'
    };
    
    navigator.clipboard.writeText(JSON.stringify(menuData, null, 2)).then(() => {
        showToast('Menu data copied to clipboard', 'success');
    });
}

function exportSingleMenu() {
    window.location.href = '{{ route("admin.menu.export") }}?menu_id={{ $menu->id }}';
}

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
                location.reload();
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error updating status', 'error');
        }
    });
}

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
                setTimeout(() => {
                    window.location.href = '{{ route("admin.menu.index") }}';
                }, 1500);
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function() {
            showToast('Error duplicating menu', 'error');
        }
    });
}

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
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.menu.index") }}';
                        }, 1500);
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

<style>
.menu-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #e9ecef;
}

.menu-item-preview {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 4px;
    border-left: 3px solid #007bff;
    margin-bottom: 5px;
}

.menu-item-preview.disabled {
    opacity: 0.5;
    background: #f8f9fa;
}

.child-menu-preview {
    display: flex;
    align-items: center;
    padding: 6px 12px 6px 24px;
    background: #f8f9fa;
    border-radius: 3px;
    margin-bottom: 3px;
    font-size: 0.9em;
}

.child-menu-preview.disabled {
    opacity: 0.5;
}
</style>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
@endpush
