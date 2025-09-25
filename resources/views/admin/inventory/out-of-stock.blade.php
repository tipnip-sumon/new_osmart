@extends('admin.layouts.app')

@section('title', 'Out of Stock Products')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Out of Stock Products</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.inventory.stock') }}">Inventory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Out of Stock</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Alert Banner -->
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bx bx-x-circle fs-20 me-2"></i>
            <div>
                <strong>Critical Alert!</strong> The following products are completely out of stock and unavailable for purchase.
            </div>
        </div>

        <!-- Out of Stock Overview -->
        <div class="row">
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
                                        <i class="ri-arrow-up-s-line"></i>18.2%
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
                                    <i class="bx bx-time fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Days Out of Stock</p>
                                        <h4 class="fw-semibold mt-1">5.2</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        Avg Days
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
                                        <p class="text-muted mb-0">Lost Revenue</p>
                                        <h4 class="fw-semibold mt-1">$12,456</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        This Week
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
                                <span class="avatar avatar-md avatar-rounded bg-secondary">
                                    <i class="bx bx-trending-down fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Pending Orders</p>
                                        <h4 class="fw-semibold mt-1">47</h4>
                                    </div>
                                    <div class="text-secondary fw-semibold">
                                        Awaiting Stock
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock Products -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Out of Stock Products</div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="bulkRestock()">
                                <i class="bx bx-plus"></i> Bulk Restock
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="hideFromCatalog()">
                                <i class="bx bx-hide"></i> Hide from Catalog
                            </button>
                            <button class="btn btn-info btn-sm" onclick="notifyCustomers()">
                                <i class="bx bx-bell"></i> Notify Customers
                            </button>
                            <a href="{{ route('admin.inventory.stock') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Back to Stock
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Controls -->
                        <div class="row mb-3">
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
                                <select class="form-control" id="daysOutFilter">
                                    <option value="">All Durations</option>
                                    <option value="1-3">1-3 Days</option>
                                    <option value="4-7">4-7 Days</option>
                                    <option value="8-14">1-2 Weeks</option>
                                    <option value="15+">2+ Weeks</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="priorityFilter">
                                    <option value="">All Priorities</option>
                                    <option value="high">High Priority</option>
                                    <option value="medium">Medium Priority</option>
                                    <option value="low">Low Priority</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                                        <th>Days Out</th>
                                        <th>Lost Sales</th>
                                        <th>Pending Orders</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- High Priority Items -->
                                    <tr class="table-danger-light">
                                        <td><input type="checkbox" class="product-checkbox" value="1"></td>
                                        <td><span class="badge bg-danger">High</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Gaming Laptop</h6>
                                                    <small class="text-muted">RTX 4060, 16GB RAM</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>GL-001</td>
                                        <td>Electronics</td>
                                        <td class="text-danger fw-bold">14 days</td>
                                        <td class="text-danger">$8,450</td>
                                        <td class="fw-bold">12</td>
                                        <td>TechWorld Inc.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="emergencyRestock(1)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(1)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="hideProduct(1)">
                                                    <i class="bx bx-hide"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="table-danger-light">
                                        <td><input type="checkbox" class="product-checkbox" value="2"></td>
                                        <td><span class="badge bg-danger">High</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Smart Watch</h6>
                                                    <small class="text-muted">Health Tracking Series</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>SW-002</td>
                                        <td>Electronics</td>
                                        <td class="text-danger fw-bold">9 days</td>
                                        <td class="text-danger">$2,890</td>
                                        <td class="fw-bold">8</td>
                                        <td>WearTech Solutions</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="emergencyRestock(2)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(2)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="hideProduct(2)">
                                                    <i class="bx bx-hide"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Medium Priority Items -->
                                    <tr class="table-warning-light">
                                        <td><input type="checkbox" class="product-checkbox" value="3"></td>
                                        <td><span class="badge bg-warning">Medium</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Designer Jeans</h6>
                                                    <small class="text-muted">Premium Denim Collection</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>DJ-003</td>
                                        <td>Clothing</td>
                                        <td class="text-warning fw-bold">6 days</td>
                                        <td class="text-warning">$1,230</td>
                                        <td class="fw-bold">5</td>
                                        <td>Fashion Forward Ltd.</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="emergencyRestock(3)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(3)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="hideProduct(3)">
                                                    <i class="bx bx-hide"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Low Priority Items -->
                                    <tr>
                                        <td><input type="checkbox" class="product-checkbox" value="4"></td>
                                        <td><span class="badge bg-secondary">Low</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://via.placeholder.com/40x40" alt="Product" class="me-2 rounded">
                                                <div>
                                                    <h6 class="mb-0">Recipe Cookbook</h6>
                                                    <small class="text-muted">International Cuisine</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>RC-004</td>
                                        <td>Books</td>
                                        <td class="fw-bold">3 days</td>
                                        <td>$180</td>
                                        <td class="fw-bold">2</td>
                                        <td>BookHouse Publishing</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success" onclick="emergencyRestock(4)">
                                                    <i class="bx bx-plus"></i> Restock
                                                </button>
                                                <button class="btn btn-sm btn-info-light" onclick="contactSupplier(4)">
                                                    <i class="bx bx-phone"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning-light" onclick="hideProduct(4)">
                                                    <i class="bx bx-hide"></i>
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
                                <span class="text-muted">Showing 1 to 4 of 32 out of stock products</span>
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

<!-- Emergency Restock Modal -->
<div class="modal fade" id="emergencyRestockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emergency Restock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bx bx-info-circle"></i>
                    <strong>Urgent Restock Required!</strong> This product is completely out of stock with pending customer orders.
                </div>
                
                <form id="emergencyRestockForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product</label>
                                <input type="text" class="form-control" id="productName" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Days Out of Stock</label>
                                <input type="text" class="form-control" id="daysOut" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pending Orders</label>
                                <input type="number" class="form-control" id="pendingOrders" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Emergency Restock Quantity *</label>
                                <input type="number" class="form-control" name="emergency_quantity" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority Level</label>
                                <select class="form-control" name="priority">
                                    <option value="urgent">Urgent (24 hours)</option>
                                    <option value="express">Express (48 hours)</option>
                                    <option value="standard">Standard (5-7 days)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expected Delivery</label>
                                <input type="date" class="form-control" name="delivery_date" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Emergency Contact</label>
                                <input type="text" class="form-control" name="emergency_contact" placeholder="Supplier emergency contact">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Special Instructions</label>
                                <textarea class="form-control" name="instructions" rows="3" placeholder="Any special delivery or handling instructions"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="processEmergencyRestock()">
                    <i class="bx bx-plus"></i> Process Emergency Restock
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

// Emergency restock function
function emergencyRestock(productId) {
    // Sample product data (in real app, fetch from API)
    const products = {
        1: { name: 'Gaming Laptop', daysOut: '14 days', pendingOrders: 12 },
        2: { name: 'Smart Watch', daysOut: '9 days', pendingOrders: 8 },
        3: { name: 'Designer Jeans', daysOut: '6 days', pendingOrders: 5 },
        4: { name: 'Recipe Cookbook', daysOut: '3 days', pendingOrders: 2 }
    };
    
    const product = products[productId];
    if (product) {
        document.getElementById('productName').value = product.name;
        document.getElementById('daysOut').value = product.daysOut;
        document.getElementById('pendingOrders').value = product.pendingOrders;
        $('#emergencyRestockModal').modal('show');
    }
}

function processEmergencyRestock() {
    const form = document.getElementById('emergencyRestockForm');
    const formData = new FormData(form);
    
    if (!formData.get('emergency_quantity') || !formData.get('delivery_date')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Emergency Restock Initiated!', 'Urgent restock order has been created and supplier has been notified.', 'success').then(() => {
            $('#emergencyRestockModal').modal('hide');
            form.reset();
            // Refresh the page or update the table
            location.reload();
        });
    } else {
        alert('Emergency restock order created successfully!');
        $('#emergencyRestockModal').modal('hide');
        form.reset();
        location.reload();
    }
}

// Contact supplier function
function contactSupplier(productId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Contact Supplier', 'Opening emergency supplier contact information...', 'info');
    } else {
        alert('Opening emergency supplier contact information...');
    }
}

// Hide product from catalog
function hideProduct(productId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hide Product',
            text: 'Hide this product from the catalog until restocked?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hide Product'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Product Hidden!', 'Product has been hidden from the catalog.', 'success');
            }
        });
    } else {
        if (confirm('Hide this product from the catalog until restocked?')) {
            alert('Product has been hidden from the catalog.');
        }
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
            title: 'Bulk Emergency Restock',
            text: `Create emergency restock orders for ${selectedProducts.length} products?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Create Emergency Orders'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Emergency Orders Created!', `${selectedProducts.length} emergency restock orders have been created.`, 'success');
            }
        });
    } else {
        if (confirm(`Create emergency restock orders for ${selectedProducts.length} products?`)) {
            alert(`${selectedProducts.length} emergency restock orders have been created.`);
        }
    }
}

// Hide from catalog function
function hideFromCatalog() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedProducts.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select products to hide.', 'warning');
        } else {
            alert('Please select products to hide.');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hide from Catalog',
            text: `Hide ${selectedProducts.length} products from catalog?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hide Products'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Products Hidden!', `${selectedProducts.length} products have been hidden from catalog.`, 'success');
            }
        });
    } else {
        if (confirm(`Hide ${selectedProducts.length} products from catalog?`)) {
            alert(`${selectedProducts.length} products have been hidden from catalog.`);
        }
    }
}

// Notify customers function
function notifyCustomers() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    
    if (selectedProducts.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('No Selection', 'Please select products to notify customers about.', 'warning');
        } else {
            alert('Please select products to notify customers about.');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Customer Notifications Sent', `Customers waiting for ${selectedProducts.length} products have been notified.`, 'info');
    } else {
        alert(`Customers waiting for ${selectedProducts.length} products have been notified.`);
    }
}

// Apply filters
function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const daysOut = document.getElementById('daysOutFilter').value;
    const priority = document.getElementById('priorityFilter').value;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Filters Applied', 'Out of stock products filtered successfully.', 'success');
    } else {
        alert('Out of stock products filtered successfully.');
    }
}
</script>
@endsection
