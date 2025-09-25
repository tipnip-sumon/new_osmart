@extends('admin.layouts.app')

@section('title', 'Products')

@push('styles')
<style>
    /* Enhanced table styling */
    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
        color: #6c757d;
        background-color: #f8f9fa;
    }
    
    .avatar img {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        width: 60px !important;
        height: 60px !important;
        object-fit: cover !important;
    }
    
    .avatar.avatar-md {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .badge.bg-purple-transparent {
        color: #6f42c1;
        background-color: rgba(111, 66, 193, 0.1);
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Stock quantity color coding */
    .stock-high { color: #28a745 !important; }
    .stock-medium { color: #ffc107 !important; }
    .stock-low { color: #dc3545 !important; }
    
    /* Price display styling */
    .price-container {
        min-width: 80px;
    }
    
    /* Action buttons hover effect */
    .btn-icon:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
    
    /* Featured badge animation */
    .badge.bg-warning-transparent {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Products</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Product Management
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-add-line"></i> Add Product
                            </a>
                            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportData('csv')"><i class="ri-file-text-line me-1"></i>Export CSV</a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportData('excel')"><i class="ri-file-excel-line me-1"></i>Export Excel</a></li>
                                <li><a class="dropdown-item" href="#" onclick="showBulkActions()"><i class="ri-checkbox-multiple-line me-1"></i>Bulk Actions</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select class="form-select" id="categoryFilter" name="category" onchange="submitFilters()">
                                        <option value="">All Categories</option>
                                        <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>Health & Wellness</option>
                                        <option value="beauty" {{ request('category') == 'beauty' ? 'selected' : '' }}>Beauty & Personal Care</option>
                                        <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                        <option value="food" {{ request('category') == 'food' ? 'selected' : '' }}>Food & Beverages</option>
                                        <option value="sports" {{ request('category') == 'sports' ? 'selected' : '' }}>Sports & Fitness</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="statusFilter" name="status" onchange="submitFilters()">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Search products..." id="searchInput" name="search" value="{{ request('search') }}">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-secondary w-100" type="button" onclick="clearFilters()" title="Clear Filters">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Products Table -->
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </th>
                                        <th scope="col">Product</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Points</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Updated</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="{{ $product['id'] }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <span class="avatar avatar-md rounded">
                                                        @php
                                                            // Comprehensive image URL handling (improved model)
                                                            $mainImageUrl = '/admin-assets/images/media/1.jpg'; // Default fallback
                                                            
                                                            // Convert array to object-like access for consistency
                                                            $productObj = (object) $product;
                                                            
                                                            // First try images array with comprehensive structure handling
                                                            if (isset($productObj->images) && $productObj->images) {
                                                                $images = is_string($productObj->images) ? json_decode($productObj->images, true) : $productObj->images;
                                                                if (is_array($images) && !empty($images)) {
                                                                    $image = $images[0]; // Get first image
                                                                    
                                                                    // Handle complex nested structure first
                                                                    if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                        // New complex structure - use medium size storage_url
                                                                        $mainImageUrl = $image['sizes']['medium']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                        // Fallback to original if medium not available
                                                                        $mainImageUrl = $image['sizes']['original']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                        // Fallback to large if original not available
                                                                        $mainImageUrl = $image['sizes']['large']['storage_url'];
                                                                    } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                        // Legacy complex URL structure - use medium size
                                                                        $mainImageUrl = $image['urls']['medium'];
                                                                    } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                        // Legacy fallback to original if medium not available
                                                                        $mainImageUrl = $image['urls']['original'];
                                                                    } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                        $mainImageUrl = $image['url'];
                                                                    } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                        $mainImageUrl = asset('storage/' . $image['path']);
                                                                    } elseif (is_string($image)) {
                                                                        // Simple string path
                                                                        $mainImageUrl = asset('storage/' . $image);
                                                                    }
                                                                }
                                                            }
                                                            
                                                            // Fallback to image field if images array didn't work
                                                            if ($mainImageUrl === '/admin-assets/images/media/1.jpg' && isset($product['image'])) {
                                                                $productImage = $product['image'];
                                                                if ($productImage && $productImage !== 'products/product1.jpg') {
                                                                    $mainImageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
                                                                }
                                                            }
                                                            
                                                            // Convert storage URLs to direct-storage for reliability
                                                            if (!str_starts_with($mainImageUrl, 'http') && !str_starts_with($mainImageUrl, '/admin-assets/')) {
                                                                if (str_starts_with($mainImageUrl, '/storage/')) {
                                                                    $path = str_replace('/storage/', '', $mainImageUrl);
                                                                    $mainImageUrl = '/direct-storage/' . $path;
                                                                } else {
                                                                    $mainImageUrl = '/direct-storage/' . ltrim($mainImageUrl, '/');
                                                                }
                                                            }
                                                            
                                                            // Debug information
                                                            $debugInfo = [];
                                                            $debugInfo[] = 'Images field: ' . (isset($product['images']) ? 'exists' : 'missing');
                                                            $debugInfo[] = 'Image field: ' . (isset($product['image']) ? 'exists' : 'missing');
                                                            $debugInfo[] = 'Final URL: ' . $mainImageUrl;
                                                        @endphp
                                                        <img src="{{ $mainImageUrl }}" alt="{{ $product['name'] }}" 
                                                             class="img-fluid" 
                                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                                             onerror="this.src='{{ asset('/admin-assets/images/media/1.jpg') }}'"
                                                             title="Debug: {{ implode(' | ', $debugInfo) }}">
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-truncate" style="max-width: 200px;" title="{{ $product['name'] }}">
                                                        {{ $product['name'] }}
                                                    </div>
                                                    <div class="text-muted fs-12">
                                                        ID: {{ $product['id'] }}
                                                        @if($product['is_featured'])
                                                            <span class="badge bg-warning-transparent ms-1">Featured</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ $product['sku'] }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $product['category'] }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                @if($product['sale_price'] && $product['sale_price'] < $product['price'])
                                                    <span class="fw-semibold text-success">৳{{ number_format($product['sale_price'], 2) }}</span>
                                                    <div class="text-muted text-decoration-line-through fs-12">৳{{ number_format($product['price'], 2) }}</div>
                                                @else
                                                    <span class="fw-semibold">৳{{ number_format($product['display_price'], 2) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge bg-primary-transparent fs-11">{{ $product['pv_points'] }} PV</span>
                                                <span class="badge bg-info-transparent fs-11">{{ $product['bv_points'] ?? 0 }} BV</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($product['stock'] > 10)
                                                <span class="text-success fw-semibold">{{ $product['stock'] }}</span>
                                            @elseif($product['stock'] > 0)
                                                <span class="text-warning fw-semibold">{{ $product['stock'] }}</span>
                                            @else
                                                <span class="text-danger fw-semibold">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product['is_starter_kit'])
                                                <span class="badge bg-purple-transparent">
                                                    Starter Kit
                                                    @if($product['starter_kit_tier'])
                                                        <br><small>{{ ucfirst($product['starter_kit_tier']) }}</small>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-transparent">Regular</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-truncate d-block" style="max-width: 120px;" title="{{ $product['vendor'] }}">
                                                {{ $product['vendor'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($product['status'] == 'Active')
                                                <span class="badge bg-success-transparent">{{ $product['status'] }}</span>
                                            @else
                                                <span class="badge bg-danger-transparent">{{ $product['status'] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fs-12 text-muted">{{ date('M d, Y', strtotime($product['updated_at'])) }}</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 fs-15">
                                                <a href="{{ route('admin.products.show', $product['id']) }}" class="btn btn-icon btn-sm btn-info-transparent rounded-pill" title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product['id']) }}" class="btn btn-icon btn-sm btn-primary-transparent rounded-pill" title="Edit Product">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <button class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" onclick="deleteProductAjax({{ $product['id'] }})" title="Delete Product">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <p class="text-muted mb-0">
                                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                                    of {{ $products->total() }} entries
                                </p>
                            </div>
                            <div>
                                {{ $products->links('vendor.pagination.admin-bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionsModalLabel">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Select an action to perform on selected products:</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="bulkAction('activate')">
                            <i class="ri-check-line me-1"></i>Activate Selected
                        </button>
                        <button type="button" class="btn btn-warning" onclick="bulkAction('deactivate')">
                            <i class="ri-close-line me-1"></i>Deactivate Selected
                        </button>
                        <button type="button" class="btn btn-info" onclick="bulkAction('feature')">
                            <i class="ri-star-line me-1"></i>Mark as Featured
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="bulkAction('unfeature')">
                            <i class="ri-star-fill me-1"></i>Remove from Featured
                        </button>
                        <button type="button" class="btn btn-danger" onclick="bulkAction('delete')">
                            <i class="ri-delete-bin-line me-1"></i>Delete Selected
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // Move JavaScript to be inline to ensure it loads
    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            // Show loading state
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="ri-loader-4-line"></i>';
            button.disabled = true;

            // Create form and submit via POST with DELETE method
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.products.destroy", ":id") }}'.replace(':id', id);
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add method spoofing for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // AJAX approach for delete
    function deleteProductAjax(id) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            // Show loading state
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="ri-loader-4-line"></i>';
            button.disabled = true;

            // Construct the URL properly using Laravel route helper
            const deleteUrl = '{{ route("admin.products.destroy", ":id") }}'.replace(':id', id);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the row from table
                    button.closest('tr').remove();
                    
                    // Show success message
                    showNotification('success', data.message || 'Product deleted successfully!');
                } else {
                    // Restore button
                    button.innerHTML = originalContent;
                    button.disabled = false;
                    
                    showNotification('error', data.message || 'Failed to delete product');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Restore button
                button.innerHTML = originalContent;
                button.disabled = false;
                
                showNotification('error', 'An error occurred while deleting the product: ' + error.message);
            });
        }
    }

    // Notification function
    function showNotification(type, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Check all functionality
    document.addEventListener('DOMContentLoaded', function() {
        const checkAllElement = document.getElementById('checkAll');
        if (checkAllElement) {
            checkAllElement.addEventListener('change', function() {
                let checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    });

    // Export functionality
    function exportData(format) {
        showNotification('info', `Preparing ${format.toUpperCase()} export...`);
        
        // Get current filters
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value;
        
        // Build export URL with filters
        let exportUrl = `{{ route('admin.products.export-download') }}?format=${format}`;
        if (category) exportUrl += `&category=${category}`;
        if (status) exportUrl += `&status=${status}`;
        if (search) exportUrl += `&search=${search}`;
        
        // Create temporary link and trigger download
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showNotification('success', `${format.toUpperCase()} export started!`);
    }

    // Show bulk actions modal
    function showBulkActions() {
        const selectedItems = getSelectedProducts();
        if (selectedItems.length === 0) {
            showNotification('warning', 'Please select at least one product to perform bulk actions.');
            return;
        }
        
        const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
        modal.show();
    }

    // Get selected product IDs
    function getSelectedProducts() {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    // Bulk actions
    function bulkAction(action) {
        const selectedIds = getSelectedProducts();
        if (selectedIds.length === 0) {
            showNotification('warning', 'No products selected.');
            return;
        }

        let confirmMessage = '';
        switch(action) {
            case 'activate':
                confirmMessage = `Are you sure you want to activate ${selectedIds.length} products?`;
                break;
            case 'deactivate':
                confirmMessage = `Are you sure you want to deactivate ${selectedIds.length} products?`;
                break;
            case 'feature':
                confirmMessage = `Are you sure you want to mark ${selectedIds.length} products as featured?`;
                break;
            case 'unfeature':
                confirmMessage = `Are you sure you want to remove ${selectedIds.length} products from featured?`;
                break;
            case 'delete':
                confirmMessage = `Are you sure you want to delete ${selectedIds.length} products? This action cannot be undone.`;
                break;
        }

        if (!confirm(confirmMessage)) {
            return;
        }

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal'));
        modal.hide();

        // Perform bulk action
        fetch('{{ route("admin.products.bulk-action") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                action: action,
                product_ids: selectedIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                // Reload page to see changes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification('error', data.message || 'Bulk action failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'An error occurred during bulk action');
        });
    }

    // Submit filters when dropdown changes
    function submitFilters() {
        document.getElementById('filterForm').submit();
    }

    // Clear all filters
    function clearFilters() {
        window.location.href = '{{ route("admin.products.index") }}';
    }
</script>

@section('scripts')
<script>
    // Additional scripts can go here if needed
</script>
@endsection
