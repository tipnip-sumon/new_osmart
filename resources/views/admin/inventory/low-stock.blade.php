@extends('admin.layouts.app')

@section('title', 'Low Stock Products')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Low Stock Products</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.inventory.stock') }}">Inventory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Low Stock</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alert Banner -->
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bx bx-error-circle fs-20 me-2"></i>
            <div>
                <strong>Attention!</strong> The following products are running low on stock and need immediate restocking.
            </div>
        </div>

        <!-- Low Stock Overview -->
        <div class="row">
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
                                        <p class="text-muted mb-0">Low Stock Items</p>
                                        <h4 class="fw-semibold mt-1">126</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>15.2%
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
                                    <i class="bx bx-trending-down fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Critical Level</p>
                                        <h4 class="fw-semibold mt-1">23</h4>
                                    </div>
                                    <div class="text-danger fw-semibold">
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
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="bx bx-dollar fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Restock Value</p>
                                        <h4 class="fw-semibold mt-1">$24,567</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>12.1%
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
                                    <i class="bx bx-trending-up fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Avg Days to Stock Out</p>
                                        <h4 class="fw-semibold mt-1">8.5</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-down-s-line"></i>2.3%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Low Stock Products</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="bulkRestock()">
                                <i class="bx bx-plus"></i> Bulk Restock
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="generateRestockReport()">
                                <i class="bx bx-file"></i> Restock Report
                            </button>
                            <a href="{{ route('admin.inventory.stock') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Back to Stock
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Priority Filter -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-control" id="priorityFilter">
                                    <option value="">All Priorities</option>
                                    <option value="critical">Critical (≤ 5 units)</option>
                                    <option value="low">Low (6-10 units)</option>
                                    <option value="moderate">Moderate (11-20 units)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="books">Books</option>
                                    <option value="home">Home & Garden</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="bx bx-filter"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Priority</th>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Current Stock</th>
                                        <th>Min Level</th>
                                        <th>Suggested Restock</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Critical Priority Items -->
                                    <tr class="table-danger-light">
                                        <td><input type="checkbox" class="product-checkbox" value="1"></td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Smartphone Case</h6>
                                                    <small class="text-muted">iPhone 15 Pro Max</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>SC-001</td>
                                        <td>Electronics</td>
                                        <td class="text-danger fw-bold">3</td>
                                        <td>20</td>
                                        <td class="fw-bold">50</td>
                                        <td>TechSupply Co.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="quickRestock(1)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(1)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="table-danger-light">
                                        <td><input type="checkbox" class="product-checkbox" value="2"></td>
                                        <td><span class="badge bg-danger">Critical</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Wireless Mouse</h6>
                                                    <small class="text-muted">Bluetooth Ergonomic</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>WM-002</td>
                                        <td>Electronics</td>
                                        <td class="text-danger fw-bold">2</td>
                                        <td>15</td>
                                        <td class="fw-bold">30</td>
                                        <td>Office Supplies Ltd.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="quickRestock(2)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(2)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Low Priority Items -->
                                    <tr class="table-warning-light">
                                        <td><input type="checkbox" class="product-checkbox" value="3"></td>
                                        <td><span class="badge bg-warning">Low</span></td>
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
                                        <td class="text-warning fw-bold">8</td>
                                        <td>10</td>
                                        <td class="fw-bold">25</td>
                                        <td>Fashion Trends Inc.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="quickRestock(3)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(3)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="table-warning-light">
                                        <td><input type="checkbox" class="product-checkbox" value="4"></td>
                                        <td><span class="badge bg-warning">Low</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Programming Guide</h6>
                                                    <small class="text-muted">Python for Beginners</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>PG-004</td>
                                        <td>Books</td>
                                        <td class="text-warning fw-bold">7</td>
                                        <td>12</td>
                                        <td class="fw-bold">20</td>
                                        <td>BookWorld Publishers</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="quickRestock(4)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(4)">
                                                    <i class="bx bx-phone"></i>
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
                                <span class="text-muted">Showing 1 to 4 of 126 low stock products</span>
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

<!-- Quick Restock Modal -->
<div class="modal fade" id="quickRestockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Restock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="restockForm">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="productName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="number" class="form-control" id="currentStock" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Restock Quantity</label>
                        <input type="number" class="form-control" name="restock_quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purchase Order Reference</label>
                        <input type="text" class="form-control" name="po_reference" placeholder="Optional PO number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="processRestock()">
                    <i class="bx bx-plus"></i> Process Restock
                </button>
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

// Quick restock function
function quickRestock(productId) {
    // Sample product data (in real app, fetch from API)
    const products = {
        1: { name: 'Smartphone Case', currentStock: 3 },
        2: { name: 'Wireless Mouse', currentStock: 2 },
        3: { name: 'Cotton T-Shirt', currentStock: 8 },
        4: { name: 'Programming Guide', currentStock: 7 }
    };
    
    const product = products[productId];
    if (product) {
        document.getElementById('productName').value = product.name;
        document.getElementById('currentStock').value = product.currentStock;
        $('#quickRestockModal').modal('show');
    }
}

function processRestock() {
    const form = document.getElementById('restockForm');
    const formData = new FormData(form);
    
    if (!formData.get('restock_quantity')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please enter restock quantity', 'error');
        } else {
            alert('Please enter restock quantity');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Restock Initiated!', 'Restock order has been created successfully.', 'success').then(() => {
            $('#quickRestockModal').modal('hide');
            form.reset();
            // Refresh the page or update the table
            location.reload();
        });
    } else {
        alert('Restock order created successfully!');
        $('#quickRestockModal').modal('hide');
        form.reset();
        location.reload();
    }
}

// Contact supplier function
function contactSupplier(productId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Contact Supplier', 'Opening supplier contact information...', 'info');
    } else {
        alert('Opening supplier contact information...');
    }
}

// Bulk restock function
function bulkRestock() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedProducts.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select products to restock.', 'warning');
        } else {
            alert('Please select products to restock.');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Bulk Restock',
            text: `Create restock orders for ${selectedProducts.length} products?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Create Orders'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Orders Created!', `${selectedProducts.length} restock orders have been created.`, 'success');
            }
        });
    } else {
        if (confirm(`Create restock orders for ${selectedProducts.length} products?`)) {
            alert(`${selectedProducts.length} restock orders have been created.`);
        }
    }
}

// Generate restock report
function generateRestockReport() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Report Generated', 'Restock report is being prepared for download.', 'info');
    } else {
        alert('Restock report is being prepared for download.');
    }
}

// Apply filters
function applyFilters() {
    const priority = document.getElementById('priorityFilter').value;
    const category = document.getElementById('categoryFilter').value;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Filters Applied', 'Low stock products filtered successfully.', 'success');
    } else {
        alert('Low stock products filtered successfully.');
    }
}
</script>
@endsection
