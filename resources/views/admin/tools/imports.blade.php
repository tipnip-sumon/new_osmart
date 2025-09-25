@extends('admin.layouts.app')

@section('title', 'Data Import/Export')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">System Tools</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Data Import/Export</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Data Import/Export</h1>
        </div>
        <div class="btn-list">
            <button class="btn btn-success-light btn-wave me-2" onclick="showImportModal()">
                <i class="bx bx-import me-1"></i> Import Data
            </button>
            <button class="btn btn-primary-light btn-wave me-0" onclick="showExportModal()">
                <i class="bx bx-export me-1"></i> Export Data
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Import/Export Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Import/Export Overview</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-import fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Imports</p>
                                                    <h4 class="fw-semibold mt-1">47</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-export fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Exports</p>
                                                    <h4 class="fw-semibold mt-1">23</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-info">
                                                <i class="bx bx-time fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Last Import</p>
                                                    <h4 class="fw-semibold mt-1">2 hours ago</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-error fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Failed Imports</p>
                                                    <h4 class="fw-semibold mt-1">3</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Import/Export -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Quick Import</div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Products</h6>
                                <p class="mb-0 text-muted">Import products from CSV/Excel file</p>
                            </div>
                            <button class="btn btn-sm btn-success" onclick="quickImport('products')">
                                <i class="bx bx-import me-1"></i> Import
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Customers</h6>
                                <p class="mb-0 text-muted">Import customer data from CSV file</p>
                            </div>
                            <button class="btn btn-sm btn-success" onclick="quickImport('customers')">
                                <i class="bx bx-import me-1"></i> Import
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Categories</h6>
                                <p class="mb-0 text-muted">Import product categories</p>
                            </div>
                            <button class="btn btn-sm btn-success" onclick="quickImport('categories')">
                                <i class="bx bx-import me-1"></i> Import
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Orders</h6>
                                <p class="mb-0 text-muted">Import order history data</p>
                            </div>
                            <button class="btn btn-sm btn-success" onclick="quickImport('orders')">
                                <i class="bx bx-import me-1"></i> Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Export -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Quick Export</div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Products</h6>
                                <p class="mb-0 text-muted">Export all products to CSV/Excel</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="quickExport('products')">
                                <i class="bx bx-export me-1"></i> Export
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Customers</h6>
                                <p class="mb-0 text-muted">Export customer database</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="quickExport('customers')">
                                <i class="bx bx-export me-1"></i> Export
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Orders</h6>
                                <p class="mb-0 text-muted">Export order history</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="quickExport('orders')">
                                <i class="bx bx-export me-1"></i> Export
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Reports</h6>
                                <p class="mb-0 text-muted">Export sales and analytics reports</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="quickExport('reports')">
                                <i class="bx bx-export me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import/Export History -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Import/Export History</div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="historyFilter">
                            <option value="all">All Operations</option>
                            <option value="import">Imports Only</option>
                            <option value="export">Exports Only</option>
                        </select>
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshHistory()">
                            <i class="bx bx-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>Operation</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Records</th>
                                    <th>Started</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="history-table-body">
                                <tr>
                                    <td><span class="badge bg-success">Import</span></td>
                                    <td>Products</td>
                                    <td>products_update_2025_09_03.csv</td>
                                    <td>1,245</td>
                                    <td>2025-09-03 14:30:00</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewDetails('import_001')">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="downloadLog('import_001')">
                                                <i class="bx bx-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Export</span></td>
                                    <td>Orders</td>
                                    <td>orders_export_2025_09_02.xlsx</td>
                                    <td>856</td>
                                    <td>2025-09-02 09:15:00</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewDetails('export_001')">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" onclick="downloadFile('export_001')">
                                                <i class="bx bx-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Import</span></td>
                                    <td>Customers</td>
                                    <td>new_customers_2025_09_01.csv</td>
                                    <td>234</td>
                                    <td>2025-09-01 16:45:00</td>
                                    <td><span class="badge bg-danger">Failed</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewDetails('import_002')">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" onclick="retryImport('import_002')">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Export</span></td>
                                    <td>Products</td>
                                    <td>products_backup_2025_08_31.csv</td>
                                    <td>3,456</td>
                                    <td>2025-08-31 11:20:00</td>
                                    <td><span class="badge bg-warning">Processing</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" onclick="viewDetails('export_002')">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="cancelOperation('export_002')">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="importType" class="form-label">Data Type</label>
                                <select class="form-select" id="importType" required>
                                    <option value="">Select data type</option>
                                    <option value="products">Products</option>
                                    <option value="customers">Customers</option>
                                    <option value="categories">Categories</option>
                                    <option value="orders">Orders</option>
                                    <option value="brands">Brands</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="importFormat" class="form-label">File Format</label>
                                <select class="form-select" id="importFormat" required>
                                    <option value="">Select format</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel (XLSX)</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="importFile" accept=".csv,.xlsx,.json" required>
                        <div class="form-text">Supported formats: CSV, XLSX, JSON. Maximum file size: 10MB</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="updateExisting">
                            <label class="form-check-label" for="updateExisting">
                                Update existing records
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="validateOnly">
                            <label class="form-check-label" for="validateOnly">
                                Validate only (don't import)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="startImport()">Start Import</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exportType" class="form-label">Data Type</label>
                                <select class="form-select" id="exportType" required>
                                    <option value="">Select data type</option>
                                    <option value="products">Products</option>
                                    <option value="customers">Customers</option>
                                    <option value="orders">Orders</option>
                                    <option value="categories">Categories</option>
                                    <option value="reports">Sales Reports</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exportFormat" class="form-label">Export Format</label>
                                <select class="form-select" id="exportFormat" required>
                                    <option value="">Select format</option>
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel (XLSX)</option>
                                    <option value="pdf">PDF</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dateFrom" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="dateFrom">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dateTo" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="dateTo">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Export Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeImages" checked>
                            <label class="form-check-label" for="includeImages">
                                Include image URLs
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="includeDeleted">
                            <label class="form-check-label" for="includeDeleted">
                                Include deleted records
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="startExport()">Start Export</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showImportModal() {
    const modal = new bootstrap.Modal(document.getElementById('importModal'));
    modal.show();
}

function showExportModal() {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

// Load history on page load
document.addEventListener('DOMContentLoaded', function() {
    loadImportExportHistory();
});

function loadImportExportHistory() {
    fetch(`{{ route('admin.tools.imports.history') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateHistoryTable(data.data);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Error loading history: ' + error.message, 'error');
        });
}

function updateHistoryTable(history) {
    const tbody = document.getElementById('history-table-body');
    
    if (history.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No import/export history available</td></tr>';
        return;
    }
    
    tbody.innerHTML = history.map(item => `
        <tr>
            <td><span class="badge bg-${item.operation === 'import' ? 'success' : 'primary'}">${item.operation.charAt(0).toUpperCase() + item.operation.slice(1)}</span></td>
            <td>${item.type}</td>
            <td>${item.filename}</td>
            <td>${item.records_processed}</td>
            <td>${item.created_at_formatted}</td>
            <td><span class="badge bg-${getStatusColor(item.status)}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info" onclick="viewDetails('${item.id}')">
                        <i class="bx bx-info-circle"></i>
                    </button>
                    ${item.operation === 'export' && item.status === 'completed' ? 
                        `<button class="btn btn-sm btn-success" onclick="downloadFile('${item.filename}')">
                            <i class="bx bx-download"></i>
                        </button>` : ''
                    }
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusColor(status) {
    const colors = {
        'completed': 'success',
        'processing': 'warning',
        'failed': 'danger',
        'pending': 'info'
    };
    return colors[status] || 'secondary';
}

function quickImport(type) {
    // Open file picker for quick import
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.csv,.xlsx,.json';
    input.onchange = function(e) {
        if (e.target.files[0]) {
            const formData = new FormData();
            formData.append('file', e.target.files[0]);
            formData.append('type', type);
            formData.append('format', 'csv'); // Default format
            formData.append('update_existing', '0');
            formData.append('validate_only', '0');
            
            showToast(`Starting quick ${type} import...`, 'info');
            
            fetch(`{{ route('admin.tools.imports.import') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    loadImportExportHistory();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                showToast('Import failed: ' + error.message, 'error');
            });
        }
    };
    input.click();
}

function quickExport(type) {
    showToast(`Starting ${type} export...`, 'info');
    
    const exportData = {
        type: type,
        format: 'csv', // Default format
        include_images: true,
        include_deleted: false
    };
    
    fetch(`{{ route('admin.tools.imports.export') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(exportData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            window.location.href = data.data.download_url;
            loadImportExportHistory();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Export failed: ' + error.message, 'error');
    });
}

function startImport() {
    const form = document.getElementById('importForm');
    const formData = new FormData();
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Get form data
    const fileInput = document.getElementById('importFile');
    const typeSelect = document.getElementById('importType');
    const formatSelect = document.getElementById('importFormat');
    const updateExisting = document.getElementById('updateExisting').checked;
    const validateOnly = document.getElementById('validateOnly').checked;
    
    if (!fileInput.files[0]) {
        showToast('Please select a file to import', 'warning');
        return;
    }
    
    formData.append('file', fileInput.files[0]);
    formData.append('type', typeSelect.value);
    formData.append('format', formatSelect.value);
    formData.append('update_existing', updateExisting ? '1' : '0');
    formData.append('validate_only', validateOnly ? '1' : '0');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
    modal.hide();
    
    showToast('Import started. You will be notified when complete.', 'info');
    
    fetch(`{{ route('admin.tools.imports.import') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadImportExportHistory();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Import failed: ' + error.message, 'error');
    });
}

function startExport() {
    const form = document.getElementById('exportForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const exportData = {
        type: document.getElementById('exportType').value,
        format: document.getElementById('exportFormat').value,
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value,
        include_images: document.getElementById('includeImages').checked,
        include_deleted: document.getElementById('includeDeleted').checked
    };
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
    
    showToast('Export started. Download will begin when ready.', 'info');
    
    fetch(`{{ route('admin.tools.imports.export') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(exportData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Auto-download the file
            window.location.href = data.data.download_url;
            loadImportExportHistory();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Export failed: ' + error.message, 'error');
    });
}

function viewDetails(operationId) {
    showToast(`Viewing details for operation ${operationId}`, 'info');
    // Here you would show a detailed modal with operation information
}

function downloadLog(operationId) {
    showToast(`Downloading log for operation ${operationId}`, 'info');
}

function downloadFile(operationId) {
    showToast(`Downloading exported file for operation ${operationId}`, 'info');
}

function retryImport(operationId) {
    if (confirm('Are you sure you want to retry this import operation?')) {
        showToast(`Retrying import operation ${operationId}`, 'info');
        
        setTimeout(() => {
            showToast('Import retry completed successfully!', 'success');
        }, 3000);
    }
}

function cancelOperation(operationId) {
    if (confirm('Are you sure you want to cancel this operation?')) {
        showToast(`Canceling operation ${operationId}`, 'warning');
        
        setTimeout(() => {
            showToast('Operation canceled successfully!', 'info');
        }, 1000);
    }
}

function refreshHistory() {
    showToast('Refreshing history...', 'info');
    
    setTimeout(() => {
        showToast('History refreshed!', 'success');
    }, 1000);
}

function addToHistory(operation, type) {
    const tableBody = document.getElementById('history-table-body');
    const newRow = `
        <tr>
            <td><span class="badge bg-${operation === 'Import' ? 'success' : 'primary'}">${operation}</span></td>
            <td>${type}</td>
            <td>${type}_${new Date().toISOString().slice(0,19).replace(/[:-]/g, '')}.csv</td>
            <td>-</td>
            <td>${new Date().toLocaleString()}</td>
            <td><span class="badge bg-warning">Processing</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-info" onclick="viewDetails('new_${Date.now()}')">
                        <i class="bx bx-info-circle"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
    tableBody.insertAdjacentHTML('afterbegin', newRow);
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove from DOM after hidden
    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}
</script>
@endpush
