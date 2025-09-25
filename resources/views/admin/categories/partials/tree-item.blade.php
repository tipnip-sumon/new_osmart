<li>
    <div class="category-item {{ $level === 0 ? 'root-category' : '' }}" data-category-id="{{ $category->id }}">
        @if(isset($category->children_tree) && count($category->children_tree) > 0)
            <button class="tree-toggle" type="button">−</button>
        @endif
        
        <div class="category-info">
            <div class="category-details">
                <div class="category-icon">
                    @if($category->image)
                        <img src="{{ $category->image }}" 
                             alt="{{ $category->name }}" 
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        {{ strtoupper(substr($category->name, 0, 1)) }}
                    @endif
                </div>
                <div class="collapse-toggle" style="flex-grow: 1;">
                    <div class="category-name">{{ $category->name }}</div>
                    <div class="category-slug">{{ $category->slug }}</div>
                </div>
            </div>
            
            <div class="category-badges">
                <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($category->status) }}
                </span>
                @if($category->is_featured)
                    <span class="badge bg-warning">Featured</span>
                @endif
                @if(isset($category->children_tree) && count($category->children_tree) > 0)
                    <span class="badge bg-info">{{ count($category->children_tree) }} child{{ count($category->children_tree) > 1 ? 'ren' : '' }}</span>
                @endif
            </div>
            
            <div class="category-actions">
                <a href="{{ route('admin.categories.show', $category->id) }}" 
                   class="btn btn-sm btn-outline-primary" title="View">
                    <i class="ri-eye-line"></i>
                </a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                   class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="ri-edit-line"></i>
                </a>
                <button type="button" 
                        class="btn btn-sm btn-outline-danger" 
                        onclick="deleteCategory({{ $category->id }})" 
                        title="Delete">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    </div>
    
    @if(isset($category->children_tree) && count($category->children_tree) > 0)
        <ul>
            @foreach($category->children_tree as $child)
                @include('admin.categories.partials.tree-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
