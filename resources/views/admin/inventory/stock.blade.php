@extends('admin.layouts.app')

@section('title', 'Stock Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Stock Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Stock Management</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Stock Overview Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-package fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Products</p>
                                        <h4 class="fw-semibold mt-1">1,247</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>12.3%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-check-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">In Stock</p>
                                        <h4 class="fw-semibold mt-1">1,089</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>8.7%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-error fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Low Stock</p>
                                        <h4 class="fw-semibold mt-1">126</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-down-s-line"></i>5.2%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-danger">
                                    <i class="bx bx-x-circle fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Out of Stock</p>
                                        <h4 class="fw-semibold mt-1">32</h4>
                                    </div>
                                    <div class="text-danger fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>2.1%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Management Tools -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Stock Management</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="bulkUpdateStock()">
                                <i class="bx bx-edit"></i> Bulk Update
                            </button>
                            <button class="btn btn-success btn-sm" onclick="exportStock()">
                                <i class="bx bx-download"></i> Export
                            </button>
                            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning btn-sm">
                                <i class="bx bx-error"></i> Low Stock
                            </a>
                            <a href="{{ route('admin.inventory.out-of-stock') }}" class="btn btn-danger btn-sm">
                                <i class="bx bx-x-circle"></i> Out of Stock
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search products..." id="searchProducts">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="books">Books</option>
                                    <option value="home">Home & Garden</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="stockFilter">
                                    <option value="">All Stock Status</option>
                                    <option value="in-stock">In Stock</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="out-of-stock">Out of Stock</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="bx bx-filter"></i> Filter
                                </button>
                            </div>
                        </div>

                        <!-- Stock Table -->
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Current Stock</th>
                                        <th>Reserved</th>
                                        <th>Available</th>
                                        <th>Min Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Product Data -->
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" value="1"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Wireless Headphones</h6>
                                                    <small class="text-muted">Premium Quality Audio</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>WH-001</td>
                                        <td>Electronics</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm stock-input" value="150" min="0" data-product-id="1">
                                        </td>
                                        <td>25</td>
                                        <td>125</td>
                                        <td>20</td>
                                        <td><span class="badge bg-success">In Stock</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="updateStock(1)">
                                                    <i class="bx bx-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewHistory(1)">
                                                    <i class="bx bx-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" value="2"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Cotton T-Shirt</h6>
                                                    <small class="text-muted">100% Organic Cotton</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>CT-002</td>
                                        <td>Clothing</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm stock-input" value="8" min="0" data-product-id="2">
                                        </td>
                                        <td>3</td>
                                        <td>5</td>
                                        <td>10</td>
                                        <td><span class="badge bg-warning">Low Stock</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="updateStock(2)">
                                                    <i class="bx bx-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewHistory(2)">
                                                    <i class="bx bx-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" value="3"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">JavaScript Book</h6>
                                                    <small class="text-muted">Learn Modern JS</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>JS-003</td>
                                        <td>Books</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm stock-input" value="0" min="0" data-product-id="3">
                                        </td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>5</td>
                                        <td><span class="badge bg-danger">Out of Stock</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="updateStock(3)">
                                                    <i class="bx bx-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewHistory(3)">
                                                    <i class="bx bx-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" value="4"></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Garden Tools Set</h6>
                                                    <small class="text-muted">Professional Quality</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>GT-004</td>
                                        <td>Home & Garden</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm stock-input" value="45" min="0" data-product-id="4">
                                        </td>
                                        <td>5</td>
                                        <td>40</td>
                                        <td>15</td>
                                        <td><span class="badge bg-success">In Stock</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="updateStock(4)">
                                                    <i class="bx bx-save"></i>
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="viewHistory(4)">
                                                    <i class="bx bx-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">Showing 1 to 4 of 1,247 products</span>
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">1</span>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">3</a>
                                    </li>
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
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Stock Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkUpdateForm">
                    <div class="mb-3">
                        <label class="form-label">Update Type</label>
                        <select class="form-control" name="update_type" id="updateType">
                            <option value="set">Set Stock Level</option>
                            <option value="add">Add to Current Stock</option>
                            <option value="subtract">Subtract from Current Stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" name="quantity" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Optional reason for stock update"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processBulkUpdate()">Update Stock</button>
            </div>
        </div>
    </div>
</div>

<!-- Stock History Modal -->
<div class="modal fade" id="stockHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Balance</th>
                                <th>Reason</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody id="stockHistoryBody">
                            <tr>
                                <td>July 25, 2025 10:30 AM</td>
                                <td><span class="badge bg-success">Inbound</span></td>
                                <td>+50</td>
                                <td>150</td>
                                <td>Stock replenishment</td>
                                <td>Admin</td>
                            </tr>
                            <tr>
                                <td>July 20, 2025 02:15 PM</td>
                                <td><span class="badge bg-danger">Outbound</span></td>
                                <td>-25</td>
                                <td>100</td>
                                <td>Order fulfillment</td>
                                <td>System</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Select All Functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Individual stock update
function updateStock(productId) {
    const stockInput = document.querySelector(`input[data-product-id="${productId}"]`);
    const newStock = stockInput.value;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Update Stock',
            text: `Set stock level to ${newStock}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate API call
                fetch(`/admin/inventory/${productId}/stock`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ stock: newStock })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Updated!', 'Stock level has been updated.', 'success');
                    // Update status badge based on new stock level
                    updateStatusBadge(productId, newStock);
                })
                .catch(error => {
                    Swal.fire('Error!', 'Failed to update stock level.', 'error');
                });
            }
        });
    } else {
        if (confirm(`Set stock level to ${newStock}?`)) {
            // Fallback for no SweetAlert
            alert('Stock updated successfully!');
            updateStatusBadge(productId, newStock);
        }
    }
}

// Update status badge based on stock level
function updateStatusBadge(productId, stock) {
    const row = document.querySelector(`input[data-product-id="${productId}"]`).closest('tr');
    const statusBadge = row.querySelector('.badge');
    
    if (stock == 0) {
        statusBadge.className = 'badge bg-danger';
        statusBadge.textContent = 'Out of Stock';
    } else if (stock <= 10) {
        statusBadge.className = 'badge bg-warning';
        statusBadge.textContent = 'Low Stock';
    } else {
        statusBadge.className = 'badge bg-success';
        statusBadge.textContent = 'In Stock';
    }
}

// Bulk update functionality
function bulkUpdateStock() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedProducts.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select products to update.', 'warning');
        } else {
            alert('Please select products to update.');
        }
        return;
    }
    
    $('#bulkUpdateModal').modal('show');
}

function processBulkUpdate() {
    const form = document.getElementById('bulkUpdateForm');
    const formData = new FormData(form);
    const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Bulk Update',
            text: `Update stock for ${selectedProducts.length} products?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate bulk update
                Swal.fire('Updated!', `Stock updated for ${selectedProducts.length} products.`, 'success');
                $('#bulkUpdateModal').modal('hide');
                form.reset();
            }
        });
    } else {
        if (confirm(`Update stock for ${selectedProducts.length} products?`)) {
            alert(`Stock updated for ${selectedProducts.length} products.`);
            $('#bulkUpdateModal').modal('hide');
            form.reset();
        }
    }
}

// View stock history
function viewHistory(productId) {
    $('#stockHistoryModal').modal('show');
}

// Apply filters
function applyFilters() {
    const search = document.getElementById('searchProducts').value;
    const category = document.getElementById('categoryFilter').value;
    const stock = document.getElementById('stockFilter').value;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Filters Applied', 'Products filtered successfully.', 'success');
    } else {
        alert('Filters applied successfully!');
    }
}

// Export stock data
function exportStock() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Export Started', 'Stock data export is being prepared.', 'info');
    } else {
        alert('Stock data export started.');
    }
}
</script>
@endsection
