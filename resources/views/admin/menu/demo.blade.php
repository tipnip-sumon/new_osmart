@extends('admin.layouts.app')

@section('title', 'Menu System Demo')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-xl-12">
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h1 class="mb-0">Admin Menu System Demo</h1>
                        <p class="text-muted">Demonstration of the new dynamic admin menu system</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.menu.builder') }}" class="btn btn-primary">
                            <i class="bx bx-customize"></i> Open Menu Builder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Menu Statistics -->
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Statistics</h5>
                </div>
                <div class="card-body">
                    @php
                        use App\Helpers\AdminMenuHelper;
                        $stats = AdminMenuHelper::getMenuStats();
                    @endphp
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-2">
                                        <i class="bx bx-menu fs-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                    <small class="text-muted">Total Menus</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-success-subtle text-success rounded-circle p-2">
                                        <i class="bx bx-check-circle fs-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $stats['active'] }}</h4>
                                    <small class="text-muted">Active</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-info-subtle text-info rounded-circle p-2">
                                        <i class="bx bx-sitemap fs-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $stats['parents'] }}</h4>
                                    <small class="text-muted">Parent Items</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-warning-subtle text-warning rounded-circle p-2">
                                        <i class="bx bx-subdirectory-right fs-5"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $stats['children'] }}</h4>
                                    <small class="text-muted">Child Items</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Preview -->
        <div class="col-xl-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Preview</h5>
                </div>
                <div class="card-body">
                    <div class="menu-preview-container">
                        <div class="sidebar-preview">
                            <h6 class="text-muted mb-3">Current Menu Structure:</h6>
                            <div class="menu-items">
                                {!! AdminMenuHelper::generate('sidebar') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu Management Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bx bx-list-ul me-2"></i>
                                View All Menus
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.menu.create') }}" class="btn btn-outline-success w-100">
                                <i class="bx bx-plus me-2"></i>
                                Add Menu Item
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.menu.builder') }}" class="btn btn-outline-info w-100">
                                <i class="bx bx-customize me-2"></i>
                                Menu Builder
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-warning w-100" onclick="clearMenuCache()">
                                <i class="bx bx-refresh me-2"></i>
                                Clear Cache
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Menu System Features</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">✅ Implemented Features</h6>
                            <ul class="list-unstyled">
                                <li><i class="bx bx-check text-success me-2"></i>Hierarchical menu structure</li>
                                <li><i class="bx bx-check text-success me-2"></i>Drag & drop menu builder</li>
                                <li><i class="bx bx-check text-success me-2"></i>Icon picker with 1000+ icons</li>
                                <li><i class="bx bx-check text-success me-2"></i>Permission-based access control</li>
                                <li><i class="bx bx-check text-success me-2"></i>Badge system for notifications</li>
                                <li><i class="bx bx-check text-success me-2"></i>Route-based active states</li>
                                <li><i class="bx bx-check text-success me-2"></i>Multiple menu templates</li>
                                <li><i class="bx bx-check text-success me-2"></i>Import/Export functionality</li>
                                <li><i class="bx bx-check text-success me-2"></i>Caching for performance</li>
                                <li><i class="bx bx-check text-success me-2"></i>Live preview while editing</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">🔮 Future Enhancements</h6>
                            <ul class="list-unstyled">
                                <li><i class="bx bx-time text-muted me-2"></i>Multi-language menu support</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Conditional menu visibility</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Menu analytics & usage tracking</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Custom CSS styling per menu</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Menu versioning & rollback</li>
                                <li><i class="bx bx-time text-muted me-2"></i>A/B testing for menu layouts</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Vendor-specific menu items</li>
                                <li><i class="bx bx-time text-muted me-2"></i>Menu API for third-party integrations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.menu-preview-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    min-height: 300px;
}

.sidebar-preview {
    background: white;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.menu-items .main-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-items .slide {
    margin-bottom: 2px;
}

.menu-items .side-menu__item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    text-decoration: none;
    color: #6c757d;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.menu-items .side-menu__item:hover {
    background-color: #e9ecef;
    color: #495057;
}

.menu-items .side-menu__item.active {
    background-color: #007bff;
    color: white;
}

.menu-items .side-menu__icon {
    margin-right: 8px;
    font-size: 16px;
}

.menu-items .slide-menu {
    list-style: none;
    padding-left: 20px;
    margin: 0;
}

.menu-items .slide__category {
    margin: 15px 0 5px 0;
    padding: 0 12px;
}

.menu-items .category-name {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #adb5bd;
    letter-spacing: 0.5px;
}
</style>

<script>
function clearMenuCache() {
    // AJAX call to clear menu cache
    fetch('{{ route("admin.menu.clear-cache") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Menu cache cleared successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error clearing cache. Please try again.');
    });
}
</script>
@endsection
