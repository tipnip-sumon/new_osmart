<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th style="width: 50px;">#</th>
                <th>Title</th>
                <th>Icon</th>
                <th>Route/URL</th>
                <th>Parent</th>
                <th>Type</th>
                <th>Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menus as $menu)
            <tr data-id="{{ $menu->id }}">
                <td>{{ $menu->id }}</td>
                <td>
                    <div class="d-flex align-items-center">
                        @if($menu->parent_id)
                            <i class="bx bx-subdirectory-right text-muted me-2"></i>
                        @endif
                        <strong>{{ $menu->title }}</strong>
                        @if($menu->badge_text)
                            <span class="badge bg-{{ $menu->badge_color }} ms-2">{{ $menu->badge_text }}</span>
                        @endif
                    </div>
                    @if($menu->description)
                        <small class="text-muted">{{ $menu->description }}</small>
                    @endif
                </td>
                <td>
                    @if($menu->icon)
                        <i class="{{ $menu->icon }} text-primary"></i>
                        <code class="ms-2">{{ $menu->icon }}</code>
                    @else
                        <span class="text-muted">No icon</span>
                    @endif
                </td>
                <td>
                    @if($menu->route)
                        <span class="badge bg-info">{{ $menu->route }}</span>
                    @elseif($menu->url)
                        <span class="badge bg-warning">{{ $menu->url }}</span>
                        @if($menu->is_external)
                            <i class="bx bx-link-external text-primary ms-1" title="External Link"></i>
                        @endif
                    @else
                        <span class="text-muted">No route/URL</span>
                    @endif
                    @if($menu->target === '_blank')
                        <i class="bx bx-window-open text-info ms-1" title="Opens in new window"></i>
                    @endif
                </td>
                <td>
                    @if($menu->parent)
                        <span class="text-muted">{{ $menu->parent->title }}</span>
                    @else
                        <span class="badge bg-primary">Root</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $menu->menu_type === 'both' ? 'success' : ($menu->menu_type === 'main' ? 'info' : 'secondary') }}">
                        {{ ucfirst($menu->menu_type) }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-light text-dark">{{ $menu->sort_order }}</span>
                </td>
                <td>
                    <span class="badge status-badge {{ $menu->is_active ? 'bg-success' : 'bg-danger' }}" data-id="{{ $menu->id }}">
                        {{ $menu->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.menu.show', $menu) }}" class="btn btn-sm btn-outline-info" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleStatus({{ $menu->id }})" title="Toggle Status">
                            <i class="bx bx-toggle-{{ $menu->is_active ? 'right' : 'left' }}"></i>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                </td>
            </tr>
            @if($menu->children->count() > 0)
                @foreach($menu->children as $child)
                <tr data-id="{{ $child->id }}" class="table-light">
                    <td>{{ $child->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-subdirectory-right text-muted me-2"></i>
                            <i class="bx bx-subdirectory-right text-muted me-2"></i>
                            {{ $child->title }}
                            @if($child->badge_text)
                                <span class="badge bg-{{ $child->badge_color }} ms-2">{{ $child->badge_text }}</span>
                            @endif
                        </div>
                        @if($child->description)
                            <small class="text-muted">{{ $child->description }}</small>
                        @endif
                    </td>
                    <td>
                        @if($child->icon)
                            <i class="{{ $child->icon }} text-primary"></i>
                            <code class="ms-2">{{ $child->icon }}</code>
                        @else
                            <span class="text-muted">No icon</span>
                        @endif
                    </td>
                    <td>
                        @if($child->route)
                            <span class="badge bg-info">{{ $child->route }}</span>
                        @elseif($child->url)
                            <span class="badge bg-warning">{{ $child->url }}</span>
                            @if($child->is_external)
                                <i class="bx bx-link-external text-primary ms-1" title="External Link"></i>
                            @endif
                        @else
                            <span class="text-muted">No route/URL</span>
                        @endif
                        @if($child->target === '_blank')
                            <i class="bx bx-window-open text-info ms-1" title="Opens in new window"></i>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted">{{ $menu->title }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $child->menu_type === 'both' ? 'success' : ($child->menu_type === 'main' ? 'info' : 'secondary') }}">
                            {{ ucfirst($child->menu_type) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $child->sort_order }}</span>
                    </td>
                    <td>
                        <span class="badge status-badge {{ $child->is_active ? 'bg-success' : 'bg-danger' }}" data-id="{{ $child->id }}">
                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.menu.show', $child) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('admin.menu.edit', $child) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleStatus({{ $child->id }})" title="Toggle Status">
                                <i class="bx bx-toggle-{{ $child->is_active ? 'right' : 'left' }}"></i>
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="duplicateMenu({{ $child->id }})">
                                        <i class="bx bx-copy me-2"></i>Duplicate
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteMenu({{ $child->id }})">
                                        <i class="bx bx-trash me-2"></i>Delete
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            @endif
            @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="bx bx-menu-alt-left fs-1 mb-3"></i>
                        <p class="mb-0">No menu items found</p>
                        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary btn-sm mt-2">
                            <i class="bx bx-plus"></i> Create First Menu
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($menus->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted">
        Showing {{ $menus->firstItem() }} to {{ $menus->lastItem() }} of {{ $menus->total() }} results
    </div>
    {{ $menus->links() }}
</div>
@endif
