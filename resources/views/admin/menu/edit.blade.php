@extends('admin.layouts.app')

@section('title', 'Edit Menu Item')

@section('content')
<div class="main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            
            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1 class="page-title">Edit Menu Item</h1>
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Menu</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bx bx-edit me-2"></i>
                                Edit Menu Item: {{ $menu->title }}
                            </h3>
                        </div>

                        <form action="{{ route('admin.menu.update', $menu) }}" method="POST" id="menuForm">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                   id="title" name="title" value="{{ old('title', $menu->title) }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                                   id="sort_order" name="sort_order" value="{{ old('sort_order', $menu->sort_order) }}" min="0">
                                            @error('sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                                       id="icon" name="icon" value="{{ old('icon', $menu->icon) }}" placeholder="bx bx-home">
                                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#iconModal">
                                                    <i class="bx bx-palette"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Use BoxIcons classes (e.g., bx bx-home)</div>
                                            @error('icon')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="iconPreview" class="mt-2">
                                                @if($menu->icon)
                                                    <i class="{{ $menu->icon }}"></i> Preview
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="parent_id" class="form-label">Parent Menu</label>
                                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                                <option value="">-- Root Menu --</option>
                                                @foreach($parentMenus as $parent)
                                                    <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                                        {{ $parent->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- URL/Route Configuration -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="route" class="form-label">Route Name</label>
                                            <div class="input-group">
                                                <select class="form-select @error('route') is-invalid @enderror" id="route" name="route">
                                                    <option value="">-- Select Route --</option>
                                                    @foreach($routes as $route)
                                                        <option value="{{ $route['name'] }}" {{ old('route', $menu->route) == $route['name'] ? 'selected' : '' }}>
                                                            {{ $route['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-outline-secondary" type="button" onclick="refreshRoutes()">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </div>
                                            @error('route')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="url" class="form-label">Custom URL</label>
                                            <input type="url" class="form-control @error('url') is-invalid @enderror" 
                                                   id="url" name="url" value="{{ old('url', $menu->url) }}" placeholder="https://example.com">
                                            <div class="form-text">Use when route is not available</div>
                                            @error('url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Settings -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="menu_type" class="form-label">Menu Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('menu_type') is-invalid @enderror" id="menu_type" name="menu_type" required>
                                                <option value="both" {{ old('menu_type', $menu->menu_type) == 'both' ? 'selected' : '' }}>Both</option>
                                                <option value="main" {{ old('menu_type', $menu->menu_type) == 'main' ? 'selected' : '' }}>Main Menu</option>
                                                <option value="sidebar" {{ old('menu_type', $menu->menu_type) == 'sidebar' ? 'selected' : '' }}>Sidebar Only</option>
                                            </select>
                                            @error('menu_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="target" class="form-label">Target</label>
                                            <select class="form-select @error('target') is-invalid @enderror" id="target" name="target">
                                                <option value="_self" {{ old('target', $menu->target) == '_self' ? 'selected' : '' }}>Same Window</option>
                                                <option value="_blank" {{ old('target', $menu->target) == '_blank' ? 'selected' : '' }}>New Window</option>
                                            </select>
                                            @error('target')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="permission" class="form-label">Permission</label>
                                            <input type="text" class="form-control @error('permission') is-invalid @enderror" 
                                                   id="permission" name="permission" value="{{ old('permission', $menu->permission) }}" 
                                                   placeholder="admin.users.index">
                                            <div class="form-text">Optional permission check</div>
                                            @error('permission')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Badge Configuration -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="badge_text" class="form-label">Badge Text</label>
                                            <input type="text" class="form-control @error('badge_text') is-invalid @enderror" 
                                                   id="badge_text" name="badge_text" value="{{ old('badge_text', $menu->badge_text) }}" 
                                                   placeholder="New">
                                            @error('badge_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="badge_color" class="form-label">Badge Color</label>
                                            <select class="form-select @error('badge_color') is-invalid @enderror" id="badge_color" name="badge_color">
                                                <option value="primary" {{ old('badge_color', $menu->badge_color) == 'primary' ? 'selected' : '' }}>Primary</option>
                                                <option value="secondary" {{ old('badge_color', $menu->badge_color) == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                <option value="success" {{ old('badge_color', $menu->badge_color) == 'success' ? 'selected' : '' }}>Success</option>
                                                <option value="danger" {{ old('badge_color', $menu->badge_color) == 'danger' ? 'selected' : '' }}>Danger</option>
                                                <option value="warning" {{ old('badge_color', $menu->badge_color) == 'warning' ? 'selected' : '' }}>Warning</option>
                                                <option value="info" {{ old('badge_color', $menu->badge_color) == 'info' ? 'selected' : '' }}>Info</option>
                                                <option value="light" {{ old('badge_color', $menu->badge_color) == 'light' ? 'selected' : '' }}>Light</option>
                                                <option value="dark" {{ old('badge_color', $menu->badge_color) == 'dark' ? 'selected' : '' }}>Dark</option>
                                            </select>
                                            @error('badge_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Checkboxes -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="is_external" name="is_external" 
                                                   {{ old('is_external', $menu->is_external) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_external">
                                                External Link
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                                        <i class="bx bx-arrow-back"></i> Back
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-secondary me-2">
                                            <i class="bx bx-refresh"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save"></i> Update Menu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Panel -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Menu Preview</h5>
                        </div>
                        <div class="card-body">
                            <div id="menuPreview" class="menu-preview">
                                <div class="menu-item-preview">
                                    <i id="previewIcon" class="{{ $menu->icon ?: 'bx bx-home' }} me-2"></i>
                                    <span id="previewTitle">{{ $menu->title }}</span>
                                    <span id="previewBadge" class="badge bg-{{ $menu->badge_color }} ms-2" 
                                          style="{{ $menu->badge_text ? 'display: inline;' : 'display: none;' }}">
                                        {{ $menu->badge_text }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h6>Menu Information:</h6>
                                <ul class="small text-muted">
                                    <li><strong>Created:</strong> {{ $menu->created_at->format('M d, Y') }}</li>
                                    <li><strong>Updated:</strong> {{ $menu->updated_at->format('M d, Y') }}</li>
                                    @if($menu->parent)
                                        <li><strong>Parent:</strong> {{ $menu->parent->title }}</li>
                                    @endif
                                    @if($menu->children->count() > 0)
                                        <li><strong>Children:</strong> {{ $menu->children->count() }} items</li>
                                    @endif
                                </ul>
                            </div>

                            @if($menu->children->count() > 0)
                                <div class="mt-3">
                                    <h6>Child Menu Items:</h6>
                                    <div class="list-group list-group-flush">
                                        @foreach($menu->children->sortBy('sort_order') as $child)
                                            <div class="list-group-item d-flex align-items-center">
                                                @if($child->icon)
                                                    <i class="{{ $child->icon }} me-2"></i>
                                                @endif
                                                <span>{{ $child->title }}</span>
                                                <span class="ms-auto badge bg-{{ $child->is_active ? 'success' : 'danger' }}">
                                                    {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->

        </div>
        <!-- CONTAINER END -->
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="iconGrid">
                    <!-- Icons will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Live preview
    updatePreview();
    
    $('#title, #icon, #badge_text, #badge_color').on('input change', function() {
        updatePreview();
    });

    // Icon preview
    $('#icon').on('input', function() {
        const icon = $(this).val();
        if (icon) {
            $('#iconPreview').html(`<i class="${icon}"></i> Preview`);
        } else {
            $('#iconPreview').html('');
        }
    });

    // Load common icons
    loadIcons();
});

function updatePreview() {
    const title = $('#title').val() || 'Menu Title';
    const icon = $('#icon').val() || 'bx bx-home';
    const badgeText = $('#badge_text').val();
    const badgeColor = $('#badge_color').val() || 'primary';

    $('#previewTitle').text(title);
    $('#previewIcon').attr('class', icon + ' me-2');
    
    if (badgeText) {
        $('#previewBadge').text(badgeText)
                         .attr('class', `badge bg-${badgeColor} ms-2`)
                         .show();
    } else {
        $('#previewBadge').hide();
    }
}

function loadIcons() {
    const commonIcons = [
        'bx bx-home', 'bx bx-user', 'bx bx-users', 'bx bx-cog', 'bx bx-package',
        'bx bx-shopping-bag', 'bx bx-store', 'bx bx-chart', 'bx bx-file',
        'bx bx-folder', 'bx bx-image', 'bx bx-video', 'bx bx-music',
        'bx bx-heart', 'bx bx-star', 'bx bx-message', 'bx bx-phone',
        'bx bx-mail-send', 'bx bx-calendar', 'bx bx-time', 'bx bx-map',
        'bx bx-search', 'bx bx-filter', 'bx bx-edit', 'bx bx-trash',
        'bx bx-plus', 'bx bx-minus', 'bx bx-check', 'bx bx-x',
        'bx bx-upload', 'bx bx-download', 'bx bx-share', 'bx bx-link',
        'bx bx-printer', 'bx bx-copy', 'bx bx-save', 'bx bx-refresh'
    ];

    let iconHtml = '';
    commonIcons.forEach(icon => {
        iconHtml += `
            <div class="col-2 text-center p-2">
                <div class="icon-item" data-icon="${icon}" style="cursor: pointer; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <i class="${icon}"></i>
                    <div class="small mt-1">${icon.replace('bx bx-', '')}</div>
                </div>
            </div>
        `;
    });

    $('#iconGrid').html(iconHtml);

    // Icon selection
    $('.icon-item').on('click', function() {
        const icon = $(this).data('icon');
        $('#icon').val(icon);
        $('#iconModal').modal('hide');
        updatePreview();
        $('#icon').trigger('input');
    });
}

function refreshRoutes() {
    // In a real implementation, this would reload routes via AJAX
    location.reload();
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
}

.icon-item:hover {
    background: #e9ecef !important;
}
</style>
@endpush
