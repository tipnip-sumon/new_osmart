@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="ti-package mr-2"></i>Inventory Management
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inventory</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="ti-plus mr-2"></i>Add New Item
            </a>
            <a href="{{ route('inventory.analytics') }}" class="btn btn-info">
                <i class="ti-chart-line mr-2"></i>Analytics
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="ti-filter mr-2"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form id="inventory-filter-form" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               placeholder="Product name, SKU, barcode..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="warehouse_id" class="form-label">Warehouse</label>
                        <select class="form-control" id="warehouse_id" name="warehouse_id">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                        {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="stock_status" class="form-label">Stock Status</label>
                        <select class="form-control" id="stock_status" name="stock_status">
                            <option value="">All Status</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="overstock" {{ request('stock_status') == 'overstock' ? 'selected' : '' }}>Overstock</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="condition" class="form-label">Condition</label>
                        <select class="form-control" id="condition" name="condition">
                            <option value="">All Conditions</option>
                            <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Used</option>
                            <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="expired" {{ request('condition') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sort_by" class="form-label">Sort By</label>
                        <select class="form-control" id="sort_by" name="sort_by">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="product_name" {{ request('sort_by') == 'product_name' ? 'selected' : '' }}>Product Name</option>
                            <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                            <option value="total_value" {{ request('sort_by') == 'total_value' ? 'selected' : '' }}>Total Value</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="sort_order" class="form-label">Order</label>
                        <select class="form-control" id="sort_order" name="sort_order">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti-search mr-2"></i>Search
                        </button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                            <i class="ti-refresh mr-2"></i>Reset
                        </a>
                        <button type="button" class="btn btn-success" id="export-btn">
                            <i class="ti-download mr-2"></i>Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($inventories->total()) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="ti-package text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Items
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inventories->where('quantity', '<=', DB::raw('min_stock_level'))->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="ti-alert-triangle text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Out of Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inventories->where('quantity', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="ti-x-circle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Value
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($inventories->sum(function($item) { return $item->quantity * $item->cost_per_unit; }), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="ti-currency-dollar text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="ti-list mr-2"></i>Inventory Items
            </h6>
        </div>
        <div class="card-body">
            <div id="inventory-table-container">
                @include('inventory.partials.inventory-table', ['inventories' => $inventories])
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bulk-action-form">
                    <div class="form-group">
                        <label for="bulk_action">Select Action</label>
                        <select class="form-control" id="bulk_action" name="action" required>
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Items</option>
                            <option value="deactivate">Deactivate Items</option>
                            <option value="export">Export Selected</option>
                            <option value="delete">Delete Items</option>
                        </select>
                    </div>
                    <input type="hidden" id="selected_items" name="selected_items">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="execute-bulk-action">Execute</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table td {
    vertical-align: middle;
}

.stock-badge {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
}

.condition-badge {
    font-size: 0.75rem;
}

.warehouse-info {
    font-size: 0.85rem;
    color: #6c757d;
}

.quantity-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.quantity-main {
    font-weight: bold;
    font-size: 1.1rem;
}

.quantity-details {
    font-size: 0.8rem;
    color: #6c757d;
}

.action-buttons .btn {
    margin: 0 2px;
    padding: 0.25rem 0.5rem;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.card {
    position: relative;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize AJAX manager for inventory
    const inventoryManager = new AjaxManager('/inventory');
    
    // Auto-submit form on filter change
    $('#inventory-filter-form select, #inventory-filter-form input').on('change keyup', debounce(function() {
        if ($(this).attr('id') === 'search' && $(this).val().length > 0 && $(this).val().length < 3) {
            return;
        }
        submitFilterForm();
    }, 500));

    function submitFilterForm() {
        const formData = $('#inventory-filter-form').serialize();
        
        inventoryManager.showLoading('#inventory-table-container');
        
        $.get(window.location.pathname + '?' + formData)
            .done(function(response) {
                if (response.success) {
                    $('#inventory-table-container').html(response.html);
                    updateUrl(formData);
                }
            })
            .fail(function() {
                inventoryManager.showError('Error loading inventory data');
            })
            .always(function() {
                inventoryManager.hideLoading('#inventory-table-container');
            });
    }

    function updateUrl(queryString) {
        const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
        window.history.replaceState({}, '', newUrl);
    }

    // Toggle inventory status
    $(document).on('click', '.toggle-status-btn', function() {
        const itemId = $(this).data('id');
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus ? 'deactivate' : 'activate';
        
        Swal.fire({
            title: `${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)} Item?`,
            text: `Are you sure you want to ${newStatus} this inventory item?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#dc3545' : '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${newStatus}!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                inventoryManager.post(`/${itemId}/toggle-status`)
                    .then(response => {
                        if (response.success) {
                            // Update button appearance
                            const btn = $(`.toggle-status-btn[data-id="${itemId}"]`);
                            if (response.is_active) {
                                btn.removeClass('btn-outline-success').addClass('btn-outline-danger')
                                   .html('<i class="ti-power-off"></i>')
                                   .attr('data-status', true)
                                   .attr('title', 'Deactivate');
                            } else {
                                btn.removeClass('btn-outline-danger').addClass('btn-outline-success')
                                   .html('<i class="ti-power-on"></i>')
                                   .attr('data-status', false)
                                   .attr('title', 'Activate');
                            }
                            
                            inventoryManager.showSuccess(response.message);
                        }
                    });
            }
        });
    });

    // Delete inventory item
    $(document).on('click', '.delete-btn', function() {
        const itemId = $(this).data('id');
        const productName = $(this).data('product');
        
        Swal.fire({
            title: 'Delete Inventory Item?',
            html: `Are you sure you want to delete the inventory for <strong>${productName}</strong>?<br><br>
                   <span class="text-danger">This action cannot be undone!</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                inventoryManager.delete(`/${itemId}`)
                    .then(response => {
                        if (response.success) {
                            $(`.inventory-row[data-id="${itemId}"]`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            inventoryManager.showSuccess(response.message);
                        }
                    });
            }
        });
    });

    // Adjust inventory
    $(document).on('click', '.adjust-btn', function() {
        const itemId = $(this).data('id');
        const productName = $(this).data('product');
        const currentQuantity = $(this).data('quantity');
        
        Swal.fire({
            title: `Adjust Inventory`,
            html: `
                <form id="adjust-form" class="text-left">
                    <div class="form-group">
                        <label><strong>Product:</strong> ${productName}</label>
                        <label><strong>Current Quantity:</strong> ${currentQuantity}</label>
                    </div>
                    <div class="form-group">
                        <label for="adjust_type">Adjustment Type:</label>
                        <select class="form-control" id="adjust_type" name="type" required>
                            <option value="">Select type...</option>
                            <option value="increase">Increase Stock</option>
                            <option value="decrease">Decrease Stock</option>
                            <option value="count_correction">Count Correction</option>
                            <option value="damage">Damaged Items</option>
                            <option value="expiry">Expired Items</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="adjust_quantity">Quantity:</label>
                        <input type="number" class="form-control" id="adjust_quantity" name="quantity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="adjust_reason">Reason:</label>
                        <input type="text" class="form-control" id="adjust_reason" name="reason" required>
                    </div>
                    <div class="form-group">
                        <label for="adjust_notes">Notes (optional):</label>
                        <textarea class="form-control" id="adjust_notes" name="notes" rows="2"></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Submit Adjustment',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const formData = new FormData(document.getElementById('adjust-form'));
                
                // Validate required fields
                if (!formData.get('type') || !formData.get('quantity') || !formData.get('reason')) {
                    Swal.showValidationMessage('Please fill in all required fields');
                    return false;
                }
                
                return Object.fromEntries(formData);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                inventoryManager.post(`/${itemId}/adjust`, result.value)
                    .then(response => {
                        if (response.success) {
                            inventoryManager.showSuccess(response.message);
                            // Refresh the table
                            submitFilterForm();
                        }
                    });
            }
        });
    });

    // Bulk actions
    let selectedItems = [];
    
    $(document).on('change', '.item-checkbox', function() {
        const itemId = $(this).val();
        if ($(this).is(':checked')) {
            selectedItems.push(itemId);
        } else {
            selectedItems = selectedItems.filter(id => id !== itemId);
        }
        
        $('.bulk-action-btn').prop('disabled', selectedItems.length === 0);
    });

    $(document).on('change', '.select-all-checkbox', function() {
        const isChecked = $(this).is(':checked');
        $('.item-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
            selectedItems = $('.item-checkbox').map(function() {
                return $(this).val();
            }).get();
        } else {
            selectedItems = [];
        }
        
        $('.bulk-action-btn').prop('disabled', selectedItems.length === 0);
    });

    $('.bulk-action-btn').on('click', function() {
        if (selectedItems.length === 0) {
            inventoryManager.showWarning('Please select items first');
            return;
        }
        
        $('#selected_items').val(JSON.stringify(selectedItems));
        $('#bulkActionModal').modal('show');
    });

    $('#execute-bulk-action').on('click', function() {
        const action = $('#bulk_action').val();
        if (!action) {
            inventoryManager.showWarning('Please select an action');
            return;
        }
        
        $('#bulkActionModal').modal('hide');
        
        const actionText = $('#bulk_action option:selected').text();
        
        Swal.fire({
            title: `${actionText}?`,
            text: `Are you sure you want to ${actionText.toLowerCase()} ${selectedItems.length} item(s)?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                inventoryManager.post('/bulk-action', {
                    action: action,
                    items: selectedItems
                }).then(response => {
                    if (response.success) {
                        inventoryManager.showSuccess(response.message);
                        submitFilterForm();
                        selectedItems = [];
                        $('.bulk-action-btn').prop('disabled', true);
                    }
                });
            }
        });
    });

    // Export functionality
    $('#export-btn').on('click', function() {
        const formData = $('#inventory-filter-form').serialize();
        window.open(`${window.location.pathname}/export?${formData}`, '_blank');
    });

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush
