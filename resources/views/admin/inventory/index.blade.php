@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Inventory Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Products</h6>
                                <h2 class="text-white mb-0">{{ number_format(2847) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-package text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Low Stock</h6>
                                <h2 class="text-white mb-0">{{ number_format(158) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-error text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-danger-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Out of Stock</h6>
                                <h2 class="text-white mb-0">{{ number_format(47) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-x-circle text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Value</h6>
                                <h2 class="text-white mb-0">${{ number_format(485720, 2) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-dollar text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
                                        <i class="bx bx-edit"></i> Bulk Stock Update
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-warning" onclick="generateLowStockReport()">
                                        <i class="bx bx-download"></i> Low Stock Report
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#stockAdjustmentModal">
                                        <i class="bx bx-transfer"></i> Stock Adjustment
                                    </button>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-success" onclick="exportInventory()">
                                        <i class="bx bx-export"></i> Export Inventory
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Inventory Overview</div>
                        <div class="d-flex">
                            <div class="me-3">
                                <input class="form-control form-control-sm" type="text" placeholder="Search products..." aria-label="search">
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Products</a></li>
                                    <li><a class="dropdown-item" href="#">In Stock</a></li>
                                    <li><a class="dropdown-item" href="#">Low Stock</a></li>
                                    <li><a class="dropdown-item" href="#">Out of Stock</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">By Category</a></li>
                                    <li><a class="dropdown-item" href="#">By Vendor</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-sort"></i> Sort
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Name A-Z</a></li>
                                    <li><a class="dropdown-item" href="#">Name Z-A</a></li>
                                    <li><a class="dropdown-item" href="#">Stock Low to High</a></li>
                                    <li><a class="dropdown-item" href="#">Stock High to Low</a></li>
                                    <li><a class="dropdown-item" href="#">Price Low to High</a></li>
                                    <li><a class="dropdown-item" href="#">Price High to Low</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select all">
                                        </th>
                                        <th scope="col">Product</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Product">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">iPhone 15 Pro</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">128GB Storage</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">IPH15P-128</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">Electronics</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">TechStore</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold text-success me-2">156</span>
                                                <div class="progress progress-xs flex-fill">
                                                    <div class="progress-bar bg-success" style="width: 78%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$999.00</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-transparent">In Stock</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Stock">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-success fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Stock">
                                                    <i class="bx bx-plus-circle"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Adjust">
                                                    <i class="bx bx-transfer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Product">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">Samsung Galaxy S24</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">256GB Storage</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">SGS24-256</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">Electronics</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">MobileHub</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold text-warning me-2">8</span>
                                                <div class="progress progress-xs flex-fill">
                                                    <div class="progress-bar bg-warning" style="width: 20%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$899.00</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-transparent">Low Stock</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Stock">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-success fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Stock">
                                                    <i class="bx bx-plus-circle"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Adjust">
                                                    <i class="bx bx-transfer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm me-2 avatar-rounded">
                                                    <img src="https://via.placeholder.com/40" alt="Product">
                                                </span>
                                                <div>
                                                    <div class="lh-1">
                                                        <span class="fw-semibold">MacBook Pro 14"</span>
                                                    </div>
                                                    <span class="lh-1 text-muted fs-11">M3 Chip</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">MBP14-M3</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-transparent">Computers</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">AppleStore</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-semibold text-danger me-2">0</span>
                                                <div class="progress progress-xs flex-fill">
                                                    <div class="progress-bar bg-danger" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$1,999.00</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-transparent">Out of Stock</span>
                                        </td>
                                        <td>
                                            <div class="hstack gap-2 flex-wrap">
                                                <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Stock">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="#" class="text-success fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Stock">
                                                    <i class="bx bx-plus-circle"></i>
                                                </a>
                                                <a href="#" class="text-warning fs-14 lh-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Adjust">
                                                    <i class="bx bx-transfer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                Showing <strong>1</strong> to <strong>3</strong> of <strong>2847</strong> entries
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="bulkUpdateModalLabel">Bulk Stock Update</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="updateType" class="form-label">Update Type</label>
                        <select class="form-select" id="updateType">
                            <option value="add">Add to existing stock</option>
                            <option value="subtract">Subtract from existing stock</option>
                            <option value="set">Set absolute stock value</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stockValue" class="form-label">Stock Value</label>
                        <input type="number" class="form-control" id="stockValue" placeholder="Enter stock value">
                    </div>
                    <div class="mb-3">
                        <label for="products" class="form-label">Select Products</label>
                        <select class="form-select" id="products" multiple>
                            <option value="1">iPhone 15 Pro - 128GB</option>
                            <option value="2">Samsung Galaxy S24 - 256GB</option>
                            <option value="3">MacBook Pro 14" - M3</option>
                        </select>
                        <div class="form-text">Hold Ctrl to select multiple products</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="stockAdjustmentModalLabel">Stock Adjustment</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="adjustmentProduct" class="form-label">Product</label>
                        <select class="form-select" id="adjustmentProduct">
                            <option value="">Select a product</option>
                            <option value="1">iPhone 15 Pro - 128GB</option>
                            <option value="2">Samsung Galaxy S24 - 256GB</option>
                            <option value="3">MacBook Pro 14" - M3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentType" class="form-label">Adjustment Type</label>
                        <select class="form-select" id="adjustmentType">
                            <option value="damaged">Damaged goods</option>
                            <option value="lost">Lost inventory</option>
                            <option value="returned">Customer returns</option>
                            <option value="correction">Inventory correction</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="adjustmentQuantity" placeholder="Enter quantity">
                    </div>
                    <div class="mb-3">
                        <label for="adjustmentReason" class="form-label">Reason</label>
                        <textarea class="form-control" id="adjustmentReason" rows="3" placeholder="Explain the reason for adjustment"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info">Apply Adjustment</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateLowStockReport() {
    // Generate low stock report
    window.open('{{ route("admin.inventory.low-stock-report") }}', '_blank');
}

function exportInventory() {
    // Export inventory data
    window.location.href = '{{ route("admin.inventory.export") }}';
}

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Real-time stock monitoring
    setInterval(function() {
        updateStockLevels();
    }, 30000); // Update every 30 seconds
    
    function updateStockLevels() {
        // AJAX call to get updated stock levels
        // This would be implemented based on your backend API
    }
});
</script>
@endpush
@endsection
