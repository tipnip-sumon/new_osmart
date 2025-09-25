@extends('admin.layouts.app')

@section('title', 'System Logs')

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
                    <li class="breadcrumb-item active" aria-current="page">System Logs</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">System Logs</h1>
        </div>
        <div class="btn-list">
            <button class="btn btn-success-light btn-wave me-2" onclick="downloadLogs()">
                <i class="bx bx-download me-1"></i> Download Logs
            </button>
            <button class="btn btn-primary-light btn-wave me-0" onclick="refreshLogs()">
                <i class="bx bx-refresh me-1"></i> Refresh Logs
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- Log Statistics -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Log Overview</div>
                </div>
                <div class="card-body log-stats">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden error-logs">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-danger">
                                                <i class="bx bx-error fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Error Logs</p>
                                                    <h4 class="fw-semibold mt-1">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden warning-logs">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-warning">
                                                <i class="bx bx-error-alt fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Warning Logs</p>
                                                    <h4 class="fw-semibold mt-1">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden info-logs">
                                <div class="card-body">
                                    <div class="d-flex align-items-top justify-content-between">
                                        <div>
                                            <span class="avatar avatar-md avatar-rounded bg-info">
                                                <i class="bx bx-info-circle fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Info Logs</p>
                                                    <h4 class="fw-semibold mt-1">0</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden debug-logs">
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
                                                    <p class="text-muted mb-0">Debug Logs</p>
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

        <!-- Recent Logs -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Recent System Logs</div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" id="logLevel">
                            <option value="all">All Levels</option>
                            <option value="error">Error</option>
                            <option value="warning">Warning</option>
                            <option value="info">Info</option>
                            <option value="debug">Debug</option>
                        </select>
                        <button class="btn btn-danger btn-sm" onclick="clearLogs()">
                            <i class="bx bx-trash me-1"></i> Clear Logs
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Time</th>
                                    <th>Message</th>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="logs-table-body">
                                <tr>
                                    <td><span class="badge bg-danger">ERROR</span></td>
                                    <td>2025-09-03 10:30:15</td>
                                    <td>Database connection failed</td>
                                    <td>/var/www/html/database/connection.php:45</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails(1)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning">WARNING</span></td>
                                    <td>2025-09-03 10:25:32</td>
                                    <td>Cache miss for product data</td>
                                    <td>/var/www/html/app/Services/ProductService.php:123</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails(2)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">INFO</span></td>
                                    <td>2025-09-03 10:20:18</td>
                                    <td>User login successful</td>
                                    <td>/var/www/html/app/Http/Controllers/Auth/LoginController.php:67</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails(3)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">DEBUG</span></td>
                                    <td>2025-09-03 10:15:44</td>
                                    <td>API request processed</td>
                                    <td>/var/www/html/app/Http/Controllers/Api/ProductController.php:234</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails(4)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning">WARNING</span></td>
                                    <td>2025-09-03 10:10:29</td>
                                    <td>Image upload size exceeded</td>
                                    <td>/var/www/html/app/Http/Controllers/ImageController.php:89</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewLogDetails(5)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div id="log-actions" class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="logsPerPage" style="width: auto;">
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100" selected>100 per page</option>
                                <option value="200">200 per page</option>
                            </select>
                        </div>
                        <nav aria-label="Page navigation" id="pagination-nav" style="display: none;">
                            <ul class="pagination pagination-sm mb-0" id="pagination-controls">
                                <!-- Pagination will be generated dynamically -->
                            </ul>
                        </nav>
                        <div id="pagination-info" class="text-end">
                            Showing 0 logs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="log-details-content">
                    <!-- Log details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load logs on page load
document.addEventListener('DOMContentLoaded', function() {
    loadLogs();
    
    // Auto-refresh logs every 30 seconds
    setInterval(() => {
        const level = document.getElementById('logLevel').value;
        const limit = document.getElementById('logsPerPage').value;
        loadLogs(level, limit, currentPage);
    }, 30000);
    
    // Handle logs per page change
    document.getElementById('logsPerPage').addEventListener('change', function() {
        const level = document.getElementById('logLevel').value;
        const limit = this.value;
        currentPage = 1; // Reset to first page
        loadLogs(level, limit, currentPage);
    });
});

let currentPage = 1;

function loadLogs(level = 'all', limit = 100, page = 1) {
    // Show loading state
    const tbody = document.getElementById('logs-table-body');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><i class="spinner-border spinner-border-sm me-2"></i>Loading logs...</td></tr>';
    
    fetch(`{{ route('admin.tools.logs.data') }}?level=${level}&limit=${limit}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateLogStats(data.data.stats);
                updateLogsTable(data.data.logs);
                updatePagination(data.data.pagination, level, limit);
                
                // Update file size info if available
                if (data.data.file_size) {
                    updatePaginationInfo(data.data.pagination, data.data.file_size);
                }
                
                currentPage = page;
            } else {
                showToast(data.message, 'error');
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger">Error loading logs</td></tr>';
            }
        })
        .catch(error => {
            showToast('Error loading logs: ' + error.message, 'error');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger">Error loading logs</td></tr>';
        });
}

function updateLogStats(stats) {
    // Update the statistics cards with proper selectors
    document.querySelector('.log-stats .error-logs h4').textContent = stats.error || 0;
    document.querySelector('.log-stats .warning-logs h4').textContent = stats.warning || 0;
    document.querySelector('.log-stats .info-logs h4').textContent = stats.info || 0;
    document.querySelector('.log-stats .debug-logs h4').textContent = stats.debug || 0;
}

function updateLogsTable(logs) {
    const tbody = document.getElementById('logs-table-body');
    
    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">No logs available</td></tr>';
        return;
    }
    
    tbody.innerHTML = logs.map(log => `
        <tr>
            <td><span class="badge bg-${getBadgeColor(log.level)}">${log.level.toUpperCase()}</span></td>
            <td>${log.timestamp}</td>
            <td>${truncateText(log.message, 80)}</td>
            <td><code>Laravel Log</code></td>
            <td>
                <button class="btn btn-sm btn-info" onclick="viewLogDetails('${encodeURIComponent(JSON.stringify(log))}')">
                    <i class="bx bx-eye"></i> View
                </button>
            </td>
        </tr>
    `).join('');
}

function updatePagination(pagination, level, limit) {
    const paginationNav = document.getElementById('pagination-nav');
    const paginationControls = document.getElementById('pagination-controls');
    
    if (pagination.total_pages <= 1) {
        paginationNav.style.display = 'none';
        return;
    }
    
    paginationNav.style.display = 'block';
    
    let paginationHTML = '';
    
    // Previous button
    paginationHTML += `
        <li class="page-item ${!pagination.has_previous ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0);" onclick="${pagination.has_previous ? `changePage(${pagination.current_page - 1}, '${level}', ${limit})` : ''}">Previous</a>
        </li>
    `;
    
    // Page numbers
    const startPage = Math.max(1, pagination.current_page - 2);
    const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);
    
    if (startPage > 1) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(1, '${level}', ${limit})">1</a></li>`;
        if (startPage > 2) {
            paginationHTML += `<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">...</a></li>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        paginationHTML += `
            <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="javascript:void(0);" onclick="changePage(${i}, '${level}', ${limit})">${i}</a>
            </li>
        `;
    }
    
    if (endPage < pagination.total_pages) {
        if (endPage < pagination.total_pages - 1) {
            paginationHTML += `<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">...</a></li>`;
        }
        paginationHTML += `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(${pagination.total_pages}, '${level}', ${limit})">${pagination.total_pages}</a></li>`;
    }
    
    // Next button
    paginationHTML += `
        <li class="page-item ${!pagination.has_next ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0);" onclick="${pagination.has_next ? `changePage(${pagination.current_page + 1}, '${level}', ${limit})` : ''}">Next</a>
        </li>
    `;
    
    paginationControls.innerHTML = paginationHTML;
}

function updatePaginationInfo(pagination, fileSize) {
    const paginationInfo = document.getElementById('pagination-info');
    const start = ((pagination.current_page - 1) * pagination.per_page) + 1;
    const end = Math.min(pagination.current_page * pagination.per_page, pagination.total_logs);
    
    paginationInfo.innerHTML = `
        Showing ${start}-${end} of ${pagination.total_logs} logs 
        <small class="text-muted">(Log file: ${fileSize})</small>
    `;
}

function changePage(page, level, limit) {
    loadLogs(level, limit, page);
}

function getBadgeColor(level) {
    const colors = {
        'error': 'danger',
        'warning': 'warning', 
        'info': 'info',
        'debug': 'secondary'
    };
    return colors[level.toLowerCase()] || 'secondary';
}

function truncateText(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
}

function refreshLogs() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Refreshing...';
    button.disabled = true;
    
    const level = document.getElementById('logLevel').value;
    const limit = document.getElementById('logsPerPage').value;
    loadLogs(level, limit, currentPage);
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        showToast('Logs refreshed successfully!', 'success');
    }, 1000);
}

function downloadLogs() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Downloading...';
    button.disabled = true;
    
    showToast('Preparing log file download...', 'info');
    
    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = `{{ route('admin.tools.logs.download') }}`;
    link.download = `laravel_logs_${new Date().toISOString().slice(0,19).replace(/:/g, '-')}.log`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button after a short delay
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        showToast('Log file download started', 'success');
    }, 1000);
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Clearing...';
        button.disabled = true;
        
        fetch(`{{ route('admin.tools.logs.clear') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            button.innerHTML = originalText;
            button.disabled = false;
            
            if (data.success) {
                // Clear the table and update statistics
                document.getElementById('logs-table-body').innerHTML = '<tr><td colspan="5" class="text-center py-4">No logs available</td></tr>';
                updateLogStats({error: 0, warning: 0, info: 0, debug: 0});
                
                // Hide pagination and update info
                document.getElementById('pagination-nav').style.display = 'none';
                document.getElementById('pagination-info').textContent = 'Showing 0 logs';
                currentPage = 1;
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            button.innerHTML = originalText;
            button.disabled = false;
            showToast('Error clearing logs: ' + error.message, 'error');
        });
    }
}

function viewLogDetails(logData) {
    try {
        const log = JSON.parse(decodeURIComponent(logData));
        
        document.getElementById('log-details-content').innerHTML = `
            <div class="row">
                <div class="col-md-3"><strong>Level:</strong></div>
                <div class="col-md-9"><span class="badge bg-${getBadgeColor(log.level)}">${log.level.toUpperCase()}</span></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3"><strong>Time:</strong></div>
                <div class="col-md-9">${log.timestamp}</div>
            </div>
            <div class="row mt-3">
                <div class="col-12"><strong>Message:</strong></div>
                <div class="col-12 mt-1">${log.message}</div>
            </div>
            <div class="row mt-3">
                <div class="col-12"><strong>Full Log Line:</strong></div>
                <div class="col-12 mt-1"><pre class="bg-light p-3 rounded" style="font-size: 12px; white-space: pre-wrap;">${log.full_line}</pre></div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
        modal.show();
    } catch (error) {
        showToast('Error viewing log details', 'error');
    }
}

function showToast(message, type) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }

    // Create toast element with proper styling
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white bg-${type} border-0 mb-2`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    // Set icon based on type
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<i class="bx bx-check-circle me-2"></i>';
            break;
        case 'error':
        case 'danger':
            icon = '<i class="bx bx-error-circle me-2"></i>';
            break;
        case 'warning':
            icon = '<i class="bx bx-error-alt me-2"></i>';
            break;
        case 'info':
            icon = '<i class="bx bx-info-circle me-2"></i>';
            break;
        default:
            icon = '<i class="bx bx-bell me-2"></i>';
    }
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                ${icon}${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to toast container
    toastContainer.appendChild(toast);
    
    // Initialize and show toast with Bootstrap
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: type === 'error' || type === 'danger' ? 5000 : 3000 // Error messages stay longer
    });
    bsToast.show();
    
    // Remove from DOM after hidden
    toast.addEventListener('hidden.bs.toast', () => {
        if (toastContainer.contains(toast)) {
            toastContainer.removeChild(toast);
        }
        // Remove container if no more toasts
        if (toastContainer.children.length === 0) {
            document.body.removeChild(toastContainer);
        }
    });
}

// Filter logs by level
document.getElementById('logLevel').addEventListener('change', function() {
    const level = this.value;
    const limit = document.getElementById('logsPerPage').value;
    currentPage = 1; // Reset to first page when filtering
    loadLogs(level, limit, currentPage);
    showToast(`Filtering by ${level === 'all' ? 'all levels' : level}`, 'info');
});
</script>
@endpush
