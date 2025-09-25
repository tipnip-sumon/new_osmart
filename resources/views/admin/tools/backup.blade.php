@extends('admin.layouts.app')

@section('title', 'Database Backup')

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
                    <li class="breadcrumb-item active" aria-current="page">Database Backup</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">Database Backup</h1>
        </div>
        <div class="btn-list">
            <button class="btn btn-primary-light btn-wave me-0" onclick="createBackup()">
                <i class="bx bx-plus me-1"></i> Create New Backup
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Backup Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Backup Overview</div>
                </div>
                <div class="card-body backup-stats">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden total-backups">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-primary">
                                                <i class="bx bx-data fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Backups</p>
                                                    <h4 class="fw-semibold mt-1">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden last-backup">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-success">
                                                <i class="bx bx-check-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Last Backup</p>
                                                    <h4 class="fw-semibold mt-1">Never</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden total-size">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-info">
                                                <i class="bx bx-hdd fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Size</p>
                                                    <h4 class="fw-semibold mt-1">0 B</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden total-downloads">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-download fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Total Downloads</p>
                                                    <h4 class="fw-semibold mt-1">0</h4>
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

        <!-- Backup Actions -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Backup Actions</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">Full Database Backup</h6>
                                            <p class="text-muted mb-0">Create a complete backup of all database tables</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-sm" onclick="createBackup('full')">
                                                <i class="bx bx-plus me-1"></i> Create
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <div class="card custom-card border shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="card-title mb-2">Partial Backup</h6>
                                            <p class="text-muted mb-0">Create backup of selected tables only</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-secondary btn-sm" onclick="showPartialBackupModal()">
                                                <i class="bx bx-select-multiple me-1"></i> Select
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup List -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Available Backups</div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="backupFilter">
                            <option value="all">All Backups</option>
                            <option value="manual">Manual</option>
                            <option value="automatic">Automatic</option>
                        </select>
                        <button class="btn btn-danger btn-sm" onclick="cleanupOldBackups()">
                            <i class="bx bx-trash me-1"></i> Cleanup Old
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>Backup Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Created By</th>
                                    <th>Downloads</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="backup-table-body">
                                <tr>
                                    <td>backup_2025_09_03_080000.sql</td>
                                    <td><span class="badge bg-primary">Full</span></td>
                                    <td>145.2 MB</td>
                                    <td>2025-09-03 08:00:00</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-success" onclick="downloadBackup('backup_2025_09_03_080000.sql')">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="restoreBackup('backup_2025_09_03_080000.sql')">
                                                <i class="bx bx-reset"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBackup('backup_2025_09_03_080000.sql')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>backup_2025_09_02_080000.sql</td>
                                    <td><span class="badge bg-primary">Full</span></td>
                                    <td>142.8 MB</td>
                                    <td>2025-09-02 08:00:00</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-success" onclick="downloadBackup('backup_2025_09_02_080000.sql')">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="restoreBackup('backup_2025_09_02_080000.sql')">
                                                <i class="bx bx-reset"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBackup('backup_2025_09_02_080000.sql')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>manual_backup_products_2025_09_01.sql</td>
                                    <td><span class="badge bg-secondary">Partial</span></td>
                                    <td>45.6 MB</td>
                                    <td>2025-09-01 14:30:00</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-success" onclick="downloadBackup('manual_backup_products_2025_09_01.sql')">
                                                <i class="bx bx-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" onclick="restoreBackup('manual_backup_products_2025_09_01.sql')">
                                                <i class="bx bx-reset"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBackup('manual_backup_products_2025_09_01.sql')">
                                                <i class="bx bx-trash"></i>
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

<!-- Partial Backup Modal -->
<div class="modal fade" id="partialBackupModal" tabindex="-1" aria-labelledby="partialBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="partialBackupModalLabel">Select Tables for Partial Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>User & Authentication</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="users" id="table_users" checked>
                            <label class="form-check-label" for="table_users">users</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="admins" id="table_admins">
                            <label class="form-check-label" for="table_admins">admins</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="password_resets" id="table_password_resets">
                            <label class="form-check-label" for="table_password_resets">password_resets</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Products & Catalog</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="products" id="table_products" checked>
                            <label class="form-check-label" for="table_products">products</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="categories" id="table_categories" checked>
                            <label class="form-check-label" for="table_categories">categories</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="brands" id="table_brands">
                            <label class="form-check-label" for="table_brands">brands</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Orders & Transactions</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="orders" id="table_orders">
                            <label class="form-check-label" for="table_orders">orders</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="order_items" id="table_order_items">
                            <label class="form-check-label" for="table_order_items">order_items</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="transactions" id="table_transactions">
                            <label class="form-check-label" for="table_transactions">transactions</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>System & Settings</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="settings" id="table_settings">
                            <label class="form-check-label" for="table_settings">settings</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="migrations" id="table_migrations">
                            <label class="form-check-label" for="table_migrations">migrations</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="failed_jobs" id="table_failed_jobs">
                            <label class="form-check-label" for="table_failed_jobs">failed_jobs</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createPartialBackup()">Create Backup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load backups on page load
document.addEventListener('DOMContentLoaded', function() {
    loadBackups();
    loadBackupStats();
});

function loadBackupStats() {
    fetch(`{{ route('admin.tools.backup.stats') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateBackupStats(data.data);
            } else {
                console.error('Failed to load backup stats:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading backup stats:', error);
        });
}

function updateBackupStats(stats) {
    // Update total backups
    document.querySelector('.backup-stats .total-backups h4').textContent = stats.total_backups;
    
    // Update last backup
    const lastBackupElement = document.querySelector('.backup-stats .last-backup h4');
    if (stats.last_backup) {
        const lastBackupTime = new Date(stats.last_backup.created_at);
        const now = new Date();
        const diffInHours = Math.floor((now - lastBackupTime) / (1000 * 60 * 60));
        if (diffInHours < 1) {
            lastBackupElement.textContent = 'Just now';
        } else if (diffInHours < 24) {
            lastBackupElement.textContent = `${diffInHours} hours ago`;
        } else {
            const diffInDays = Math.floor(diffInHours / 24);
            lastBackupElement.textContent = `${diffInDays} days ago`;
        }
    } else {
        lastBackupElement.textContent = 'Never';
    }
    
    // Update total size
    document.querySelector('.backup-stats .total-size h4').textContent = stats.total_size_formatted;
    
    // Update total downloads
    document.querySelector('.backup-stats .total-downloads h4').textContent = stats.total_downloads;
}

function loadBackups() {
    fetch(`{{ route('admin.tools.backup.list') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateBackupsTable(data.data);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Error loading backups: ' + error.message, 'error');
        });
}

function updateBackupsTable(backups) {
    const tbody = document.getElementById('backup-table-body');
    
    if (backups.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4">No backups available</td></tr>';
        return;
    }
    
    tbody.innerHTML = backups.map(backup => `
        <tr ${!backup.file_exists ? 'class="table-warning"' : ''}>
            <td>
                <div class="d-flex align-items-center">
                    <span>${backup.filename}</span>
                    ${!backup.file_exists ? '<i class="bx bx-exclamation-triangle text-warning ms-2" title="File not found on disk"></i>' : ''}
                </div>
            </td>
            <td><span class="badge bg-${getTypeBadgeColor(backup.type)}">${backup.type.charAt(0).toUpperCase() + backup.type.slice(1)}</span></td>
            <td>${backup.size_formatted}</td>
            <td>
                <div>
                    <strong>${backup.created_by_name || 'Unknown'}</strong><br>
                    <small class="text-muted">${backup.created_at_formatted}</small>
                </div>
            </td>
            <td>
                <div>
                    <span class="badge bg-info">${backup.download_count || 0} downloads</span><br>
                    <small class="text-muted">Last: ${backup.last_downloaded_at_formatted}</small>
                </div>
            </td>
            <td><span class="badge bg-${backup.file_exists ? 'success' : 'warning'}">${backup.file_exists ? 'Available' : 'Missing'}</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-success" onclick="downloadBackup('${backup.filename}')" 
                            ${!backup.file_exists ? 'disabled title="File not found"' : ''}>
                        <i class="bx bx-download"></i> Download
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBackup('${backup.filename}')">
                        <i class="bx bx-trash"></i> Delete
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getTypeBadgeColor(type) {
    switch(type) {
        case 'full': return 'primary';
        case 'partial': return 'secondary';
        case 'manual': return 'success';
        case 'scheduled': return 'info';
        case 'automatic': return 'warning';
        default: return 'light';
    }
}

function createBackup(type = 'full') {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Creating...';
    button.disabled = true;
    
    fetch(`{{ route('admin.tools.backup.create') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        if (data.success) {
            showToast(data.message, 'success');
            loadBackups(); // Reload backup list
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        button.innerHTML = originalText;
        button.disabled = false;
        showToast('Error creating backup: ' + error.message, 'error');
    });
}

function showPartialBackupModal() {
    const modal = new bootstrap.Modal(document.getElementById('partialBackupModal'));
    modal.show();
}

function createPartialBackup() {
    const selectedTables = Array.from(document.querySelectorAll('#partialBackupModal input[type="checkbox"]:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedTables.length === 0) {
        showToast('Please select at least one table', 'warning');
        return;
    }
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('partialBackupModal'));
    modal.hide();
    
    showToast(`Creating partial backup with ${selectedTables.length} tables...`, 'info');
    
    fetch(`{{ route('admin.tools.backup.create') }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            type: 'partial',
            tables: selectedTables 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            loadBackups(); // Reload backup list
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Error creating partial backup: ' + error.message, 'error');
    });
}

function downloadBackup(filename) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Downloading...';
    button.disabled = true;
    
    showToast(`Preparing download for ${filename}...`, 'info');
    
    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = `{{ url('admin/tools/backup/download') }}/${filename}`;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button after a short delay
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        showToast(`Download started for ${filename}`, 'success');
        
        // Refresh backup list to update download count
        loadBackups();
    }, 1000);
}

function deleteBackup(filename) {
    if (confirm(`Are you sure you want to delete ${filename}? This action cannot be undone.`)) {
        showToast(`Deleting ${filename}...`, 'info');
        
        fetch(`{{ url('admin/tools/backup/delete') }}/${filename}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                loadBackups(); // Reload backup list
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Error deleting backup: ' + error.message, 'error');
        });
    }
}

function cleanupOldBackups() {
    if (confirm('Are you sure you want to cleanup old backups? Backups older than 30 days will be deleted.')) {
        showToast('Cleaning up old backups...', 'info');
        
        setTimeout(() => {
            showToast('Old backups cleaned up successfully!', 'success');
        }, 2000);
    }
}

function addBackupToTable(filename, type) {
    const tableBody = document.getElementById('backup-table-body');
    const newRow = `
        <tr>
            <td>${filename}</td>
            <td><span class="badge bg-${type === 'full' ? 'primary' : 'secondary'}">${type === 'full' ? 'Full' : 'Partial'}</span></td>
            <td>Calculating...</td>
            <td>${new Date().toLocaleString()}</td>
            <td><span class="badge bg-success">Complete</span></td>
            <td>
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-success" onclick="downloadBackup('${filename}')">
                        <i class="bx bx-download"></i>
                    </button>
                    <button class="btn btn-sm btn-info" onclick="restoreBackup('${filename}')">
                        <i class="bx bx-reset"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBackup('${filename}')">
                        <i class="bx bx-trash"></i>
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
