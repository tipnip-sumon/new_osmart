<div class="menu-item" data-menu-id="{{ $menu->id }}" data-icon="{{ $menu->icon }}" 
     data-badge-text="{{ $menu->badge_text }}" data-badge-color="{{ $menu->badge_color }}" 
     data-is-active="{{ $menu->is_active ? '1' : '0' }}">
    
    <div class="menu-item-header">
        <div class="menu-drag-handle">
            <i class="bx bx-menu"></i>
        </div>
        
        <div class="status-indicator {{ $menu->is_active ? 'active' : 'inactive' }}"></div>
        
        <div class="menu-item-content">
            @if($menu->icon)
                <i class="{{ $menu->icon }} me-2"></i>
            @endif
            <span class="menu-title fw-bold">{{ $menu->title }}</span>
            @if($menu->badge_text)
                <span class="badge bg-{{ $menu->badge_color }} ms-2">{{ $menu->badge_text }}</span>
            @endif
            
            <div class="ms-auto d-flex align-items-center text-muted small">
                @if($menu->route)
                    <span class="badge bg-info me-2">{{ $menu->route }}</span>
                @elseif($menu->url)
                    <span class="badge bg-warning me-2">{{ Str::limit($menu->url, 30) }}</span>
                @endif
                
                @if($menu->children->count() > 0)
                    <span class="badge bg-secondary me-2">{{ $menu->children->count() }} children</span>
                @endif
                
                <span class="me-2">Order: {{ $menu->sort_order }}</span>
            </div>
        </div>
        
        <div class="menu-item-actions">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="quickEdit({{ $menu->id }})" title="Quick Edit">
                <i class="bx bx-edit"></i>
            </button>
            
            <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-sm btn-outline-info" title="Full Edit">
                <i class="bx bx-cog"></i>
            </a>
            
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="toggleMenuStatus({{ $menu->id }})" title="Toggle Status">
                <i class="bx bx-toggle-{{ $menu->is_active ? 'right' : 'left' }}"></i>
            </button>
            
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.menu.show', $menu) }}">
                        <i class="bx bx-show me-2"></i>View Details
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="duplicateMenu({{ $menu->id }})">
                        <i class="bx bx-copy me-2"></i>Duplicate
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteMenuItem({{ $menu->id }})">
                        <i class="bx bx-trash me-2"></i>Delete
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    
    @if($menu->children->count() > 0)
        <div class="menu-item-children">
            @foreach($menu->children->sortBy('sort_order') as $child)
                <div class="child-menu-item" data-menu-id="{{ $child->id }}" data-icon="{{ $child->icon }}" 
                     data-badge-text="{{ $child->badge_text }}" data-badge-color="{{ $child->badge_color }}" 
                     data-is-active="{{ $child->is_active ? '1' : '0' }}">
                    
                    <div class="menu-drag-handle">
                        <i class="bx bx-menu"></i>
                    </div>
                    
                    <div class="status-indicator {{ $child->is_active ? 'active' : 'inactive' }}"></div>
                    
                    <div class="flex-grow-1">
                        @if($child->icon)
                            <i class="{{ $child->icon }} me-2"></i>
                        @endif
                        <span class="menu-title">{{ $child->title }}</span>
                        @if($child->badge_text)
                            <span class="badge bg-{{ $child->badge_color }} ms-2">{{ $child->badge_text }}</span>
                        @endif
                    </div>
                    
                    <div class="ms-auto d-flex align-items-center text-muted small">
                        @if($child->route)
                            <span class="badge bg-info me-2">{{ Str::afterLast($child->route, '.') }}</span>
                        @elseif($child->url)
                            <span class="badge bg-warning me-2">URL</span>
                        @endif
                        <span class="me-2">{{ $child->sort_order }}</span>
                    </div>
                    
                    <div class="child-actions">
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="quickEdit({{ $child->id }})" title="Quick Edit">
                            <i class="bx bx-edit"></i>
                        </button>
                        
                        <a href="{{ route('admin.menu.edit', $child) }}" class="btn btn-xs btn-outline-info" title="Edit">
                            <i class="bx bx-cog"></i>
                        </a>
                        
                        <button type="button" class="btn btn-xs btn-outline-warning" onclick="toggleMenuStatus({{ $child->id }})" title="Toggle Status">
                            <i class="bx bx-toggle-{{ $child->is_active ? 'right' : 'left' }}"></i>
                        </button>
                        
                        <button type="button" class="btn btn-xs btn-outline-danger" onclick="deleteMenuItem({{ $child->id }})" title="Delete">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
