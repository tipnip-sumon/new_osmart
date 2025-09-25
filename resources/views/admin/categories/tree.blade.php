@extends('admin.layouts.app')

@section('title', 'Category Tree')

@push('styles')
<style>
    .tree-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
    }
    .category-tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .category-tree ul {
        list-style: none;
        padding-left: 30px;
        margin: 10px 0;
    }
    .category-item {
        margin: 8px 0;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        background: #f8f9fa;
        position: relative;
    }
    .category-item:hover {
        background: #e3f2fd;
        border-color: #2196f3;
    }
    .category-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .category-details {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }
    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #007bff;
        color: white;
        font-weight: bold;
    }
    .category-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    .category-slug {
        font-size: 0.85rem;
        color: #6c757d;
        font-style: italic;
    }
    .category-badges {
        display: flex;
        gap: 5px;
        align-items: center;
    }
    .category-actions {
        display: flex;
        gap: 5px;
    }
    .tree-toggle {
        position: absolute;
        left: -15px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tree-toggle:hover {
        background: #0056b3;
    }
    .tree-line {
        position: absolute;
        left: -25px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }
    .tree-line:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 15px;
        height: 2px;
        background: #dee2e6;
    }
    .root-category {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }
    .root-category .category-name,
    .root-category .category-slug {
        color: white;
    }
    .empty-tree {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    .tree-stats {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .collapse-toggle {
        cursor: pointer;
        user-select: none;
    }
    .children-collapsed {
        display: none;
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Category Tree</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tree View</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tree Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="tree-stats">
                    <div class="row text-center">
                        <div class="col-md-2">
                            <div class="d-flex flex-column">
                                <span class="h4 mb-0 text-primary">{{ $stats['total'] }}</span>
                                <small class="text-muted">Total Categories</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-column">
                                <span class="h4 mb-0 text-success">{{ $stats['root_categories'] }}</span>
                                <small class="text-muted">Root Categories</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-column">
                                <span class="h4 mb-0 text-info">{{ $stats['subcategories'] }}</span>
                                <small class="text-muted">Subcategories</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-column">
                                <span class="h4 mb-0 text-warning">{{ $stats['active'] }}</span>
                                <small class="text-muted">Active</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-column">
                                <span class="h4 mb-0 text-secondary">{{ $stats['featured'] }}</span>
                                <small class="text-muted">Featured</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="expandAll()">
                                    <i class="ri-add-line me-1"></i> Expand All
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="collapseAll()">
                                    <i class="ri-subtract-line me-1"></i> Collapse All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Category Hierarchy Structure</h6>
                            <small class="text-muted">Manage your category tree structure</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="ri-list-check me-1"></i> List View
                            </a>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Add Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Tree -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tree-container">
                            @if($tree && count($tree) > 0)
                                <ul class="category-tree">
                                    @foreach($tree as $category)
                                        @include('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0])
                                    @endforeach
                                </ul>
                            @else
                                <div class="empty-tree">
                                    <i class="ri-folder-2-line" style="font-size: 4rem; color: #dee2e6;"></i>
                                    <h5 class="mt-3">No Categories Found</h5>
                                    <p class="mb-3">Start building your category structure by creating your first category.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i> Create First Category
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for tree toggles
    document.querySelectorAll('.tree-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const categoryItem = this.closest('.category-item');
            const children = categoryItem.querySelector('ul');
            
            if (children) {
                children.classList.toggle('children-collapsed');
                this.innerHTML = children.classList.contains('children-collapsed') ? '+' : '−';
            }
        });
    });

    // Add click handlers for category names (expand/collapse)
    document.querySelectorAll('.collapse-toggle').forEach(element => {
        element.addEventListener('click', function() {
            const categoryItem = this.closest('.category-item');
            const toggle = categoryItem.querySelector('.tree-toggle');
            if (toggle) {
                toggle.click();
            }
        });
    });
});

function expandAll() {
    document.querySelectorAll('.children-collapsed').forEach(children => {
        children.classList.remove('children-collapsed');
    });
    document.querySelectorAll('.tree-toggle').forEach(toggle => {
        toggle.innerHTML = '−';
    });
}

function collapseAll() {
    document.querySelectorAll('.category-tree ul').forEach(children => {
        children.classList.add('children-collapsed');
    });
    document.querySelectorAll('.tree-toggle').forEach(toggle => {
        toggle.innerHTML = '+';
    });
}

// Category actions
function editCategory(id) {
    window.location.href = `/admin/categories/${id}/edit`;
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category and all its subcategories?')) {
        // Add delete logic here
        console.log('Deleting category:', id);
    }
}

function toggleStatus(id, currentStatus) {
    // Add status toggle logic here
    console.log('Toggling status for category:', id, 'Current status:', currentStatus);
}
</script>
@endpush
