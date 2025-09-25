@extends('admin.layouts.app')

@section('title', 'System Maintenance')

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
                    <li class="breadcrumb-item active" aria-current="page">System Maintenance</li>
                </ol>
            </nav>
            <h1 class="page-title fw-medium fs-18 mb-0">System Maintenance</h1>
        </div>
        <div class="btn-list">
            <button class="btn btn-warning-light btn-wave me-0" onclick="toggleMaintenanceMode()" id="maintenanceToggle">
                <i class="bx bx-wrench me-1"></i> <span id="maintenanceToggleText">Enable Maintenance Mode</span>
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="row">
        <!-- System Status -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">System Status</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card custom-card overflow-hidden">
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
                                                    <p class="text-muted mb-0">System Status</p>
                                                    <h4 class="fw-semibold mt-1 text-success">Online</h4>
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
                                                    <p class="text-muted mb-0">Uptime</p>
                                                    <h4 class="fw-semibold mt-1" id="systemUptime">Loading...</h4>
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
                                                <i class="bx bx-wrench fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Maintenance Mode</p>
                                                    <h4 class="fw-semibold mt-1 text-danger" id="maintenanceStatus">Disabled</h4>
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
                                                <i class="bx bx-data fs-16"></i>
                                            </span>
                                        </div>
                                        <div class="flex-fill ms-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                <div>
                                                    <p class="text-muted mb-0">Disk Space</p>
                                                    <h4 class="fw-semibold mt-1" id="diskSpace">Loading...</h4>
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

        <!-- System Optimization -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">System Optimization</div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Clear System Cache</h6>
                                <p class="mb-0 text-muted">Clear application cache and temporary files</p>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="clearSystemCache()">
                                <i class="bx bx-refresh me-1"></i> Clear
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Optimize Database</h6>
                                <p class="mb-0 text-muted">Optimize database tables and indexes</p>
                            </div>
                            <button class="btn btn-sm btn-success" onclick="optimizeDatabase()">
                                <i class="bx bx-cog me-1"></i> Optimize
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Clean Log Files</h6>
                                <p class="mb-0 text-muted">Remove old log files and error logs</p>
                            </div>
                            <button class="btn btn-sm btn-warning" onclick="cleanLogFiles()">
                                <i class="bx bx-trash me-1"></i> Clean
                            </button>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Rebuild Search Index</h6>
                                <p class="mb-0 text-muted">Rebuild product search index</p>
                            </div>
                            <button class="btn btn-sm btn-info" onclick="rebuildSearchIndex()">
                                <i class="bx bx-search me-1"></i> Rebuild
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="col-xl-6">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">System Health Check</div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Database Connection</h6>
                                <p class="mb-0 text-muted">Check database connectivity</p>
                            </div>
                            <span class="badge bg-secondary" id="dbStatus">
                                <i class="bx bx-loader-alt bx-spin me-1"></i> Checking...
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">File Permissions</h6>
                                <p class="mb-0 text-muted">Check storage and cache permissions</p>
                            </div>
                            <span class="badge bg-secondary" id="fileStatus">
                                <i class="bx bx-loader-alt bx-spin me-1"></i> Checking...
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Cache System</h6>
                                <p class="mb-0 text-muted" id="cacheInfo">Check cache system status</p>
                            </div>
                            <span class="badge bg-secondary" id="cacheStatus">
                                <i class="bx bx-loader-alt bx-spin me-1"></i> Checking...
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1">Queue Jobs</h6>
                                <p class="mb-0 text-muted" id="queueInfo">Check queue system status</p>
                            </div>
                            <span class="badge bg-secondary" id="queueStatus">
                                <i class="bx bx-loader-alt bx-spin me-1"></i> Checking...
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm w-100" onclick="runHealthCheck()">
                            <i class="bx bx-check-double me-1"></i> Run Full Health Check
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduled Tasks -->
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Scheduled Maintenance Tasks</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>Task Name</th>
                                    <th>Schedule</th>
                                    <th>Last Run</th>
                                    <th>Next Run</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="scheduledTasksTable">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let maintenanceModeEnabled = false;

// Load initial data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadSystemStatus();
    loadHealthData();
    
    // Auto-refresh system status every 30 seconds
    setInterval(loadSystemStatus, 30000);
});

function loadSystemStatus() {
    // Add loading indicators
    const uptimeElement = document.getElementById('systemUptime');
    const diskSpaceElement = document.getElementById('diskSpace');
    
    if (uptimeElement && uptimeElement.textContent !== 'Loading...') {
        uptimeElement.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Updating...';
    }
    if (diskSpaceElement && diskSpaceElement.textContent !== 'Loading...') {
        diskSpaceElement.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Updating...';
    }
    
    fetch('{{ route("admin.tools.maintenance.health") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            updateSystemStatusCards(data.data);
        }
    })
    .catch(error => {
        console.error('Error loading system status:', error);
        // Reset loading states on error
        if (uptimeElement) {
            uptimeElement.textContent = 'Error';
        }
        if (diskSpaceElement) {
            diskSpaceElement.textContent = 'Error';
        }
    });
}

function updateSystemStatusCards(healthData) {
    console.log('Updating system status cards with:', healthData);
    
    // Update disk space
    if (healthData.disk_space) {
        const diskSpaceElement = document.getElementById('diskSpace');
        if (diskSpaceElement) {
            const freeSpace = healthData.disk_space.free_formatted || 'N/A';
            const usedPercent = healthData.disk_space.percentage_used || 0;
            diskSpaceElement.innerHTML = `${freeSpace} free (${usedPercent}% used)`;
            
            // Update color based on usage
            if (usedPercent > 90) {
                diskSpaceElement.className = 'fw-semibold mt-1 text-danger';
            } else if (usedPercent > 80) {
                diskSpaceElement.className = 'fw-semibold mt-1 text-warning';
            } else {
                diskSpaceElement.className = 'fw-semibold mt-1 text-success';
            }
        }
        
        // Update system uptime
        const uptimeElement = document.getElementById('systemUptime');
        if (uptimeElement && healthData.disk_space.uptime) {
            uptimeElement.textContent = healthData.disk_space.uptime;
        }
    }
    
    // Update database status
    if (healthData.database) {
        const dbStatusElement = document.getElementById('dbStatus');
        if (dbStatusElement) {
            if (healthData.database.status === 'healthy') {
                dbStatusElement.className = 'badge bg-success';
                dbStatusElement.innerHTML = '<i class="bx bx-check me-1"></i> Healthy';
            } else {
                dbStatusElement.className = 'badge bg-danger';
                dbStatusElement.innerHTML = '<i class="bx bx-x me-1"></i> Error';
            }
        }
    }
    
    // Update storage status
    if (healthData.storage) {
        const fileStatusElement = document.getElementById('fileStatus');
        if (fileStatusElement) {
            if (healthData.storage.status === 'healthy') {
                fileStatusElement.className = 'badge bg-success';
                fileStatusElement.innerHTML = '<i class="bx bx-check me-1"></i> OK';
            } else {
                fileStatusElement.className = 'badge bg-danger';
                fileStatusElement.innerHTML = '<i class="bx bx-x me-1"></i> Error';
            }
        }
    }
    
    // Update cache status
    if (healthData.cache) {
        const cacheStatusElement = document.getElementById('cacheStatus');
        const cacheInfoElement = document.getElementById('cacheInfo');
        if (cacheStatusElement) {
            if (healthData.cache.status === 'healthy') {
                cacheStatusElement.className = 'badge bg-success';
                cacheStatusElement.innerHTML = '<i class="bx bx-check me-1"></i> Active';
            } else {
                cacheStatusElement.className = 'badge bg-warning';
                cacheStatusElement.innerHTML = '<i class="bx bx-error me-1"></i> Issues';
            }
        }
        if (cacheInfoElement && healthData.cache.driver) {
            cacheInfoElement.textContent = `Cache driver: ${healthData.cache.driver}`;
        }
    }
    
    // Update queue status
    if (healthData.queue) {
        const queueStatusElement = document.getElementById('queueStatus');
        const queueInfoElement = document.getElementById('queueInfo');
        if (queueStatusElement) {
            if (healthData.queue.status === 'healthy') {
                queueStatusElement.className = 'badge bg-success';
                queueStatusElement.innerHTML = '<i class="bx bx-check me-1"></i> Running';
            } else {
                queueStatusElement.className = 'badge bg-warning';
                queueStatusElement.innerHTML = '<i class="bx bx-pause me-1"></i> Stopped';
            }
        }
        if (queueInfoElement && healthData.queue.pending_jobs !== undefined) {
            queueInfoElement.textContent = `Pending jobs: ${healthData.queue.pending_jobs}`;
        }
    }
    
    // Update scheduled tasks
    if (healthData.scheduled_tasks) {
        updateScheduledTasksTable(healthData.scheduled_tasks);
    }
}

function updateScheduledTasksTable(tasks) {
    const tableBody = document.getElementById('scheduledTasksTable');
    if (!tableBody) return;
    
    tableBody.innerHTML = '';
    
    tasks.forEach(task => {
        const row = document.createElement('tr');
        
        // Format last run and next run times
        const lastRun = new Date(task.last_run).toLocaleString();
        const nextRun = new Date(task.next_run).toLocaleString();
        
        // Determine status badge
        const statusClass = task.status === 'active' ? 'bg-success' : 'bg-warning';
        const statusText = task.status.charAt(0).toUpperCase() + task.status.slice(1);
        
        // Determine button set based on status
        let actionButtons = '';
        if (task.status === 'active') {
            actionButtons = `
                <button class="btn btn-sm btn-primary" onclick="runTask('${task.id}')" title="Run Now">
                    <i class="bx bx-play"></i>
                </button>
                <button class="btn btn-sm btn-secondary" onclick="pauseTask('${task.id}')" title="Pause">
                    <i class="bx bx-pause"></i>
                </button>
            `;
        } else {
            actionButtons = `
                <button class="btn btn-sm btn-primary" onclick="runTask('${task.id}')" title="Run Now">
                    <i class="bx bx-play"></i>
                </button>
                <button class="btn btn-sm btn-success" onclick="resumeTask('${task.id}')" title="Resume">
                    <i class="bx bx-play-circle"></i>
                </button>
            `;
        }
        
        row.innerHTML = `
            <td>
                <div>
                    <h6 class="mb-0">${task.name}</h6>
                    <small class="text-muted">${task.description}</small>
                </div>
            </td>
            <td>${task.schedule}</td>
            <td>${lastRun}</td>
            <td>${nextRun}</td>
            <td><span class="badge ${statusClass}" id="task-status-${task.id}">${statusText}</span></td>
            <td>
                <div class="btn-group" role="group">
                    ${actionButtons}
                </div>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

function loadHealthData() {
    const healthItems = document.querySelectorAll('.list-group-item');
    // This function can be expanded to load more dynamic health data
}

function toggleMaintenanceMode() {
    const button = document.getElementById('maintenanceToggle');
    
    if (!maintenanceModeEnabled) {
        if (confirm('Are you sure you want to enable maintenance mode? This will make the site unavailable to users.')) {
            button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Enabling...';
            button.disabled = true;
            
            fetch('{{ route("admin.tools.maintenance.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    maintenanceModeEnabled = data.status === 'enabled';
                    button.className = 'btn btn-danger-light btn-wave me-0';
                    button.innerHTML = '<i class="bx bx-wrench me-1"></i> <span id="maintenanceToggleText">Disable Maintenance Mode</span>';
                    
                    // Update status card
                    const maintenanceStatusElement = document.getElementById('maintenanceStatus');
                    if (maintenanceStatusElement) {
                        maintenanceStatusElement.textContent = 'Enabled';
                        maintenanceStatusElement.className = 'fw-semibold mt-1 text-warning';
                    }
                    
                    showToast(data.message, 'warning');
                } else {
                    showToast(data.message || 'Failed to enable maintenance mode', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to toggle maintenance mode', 'danger');
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    } else {
        if (confirm('Are you sure you want to disable maintenance mode? This will make the site available to users.')) {
            button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Disabling...';
            button.disabled = true;
            
            fetch('{{ route("admin.tools.maintenance.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    maintenanceModeEnabled = data.status === 'enabled';
                    button.className = 'btn btn-warning-light btn-wave me-0';
                    button.innerHTML = '<i class="bx bx-wrench me-1"></i> <span id="maintenanceToggleText">Enable Maintenance Mode</span>';
                    
                    // Update status card
                    const maintenanceStatusElement = document.getElementById('maintenanceStatus');
                    if (maintenanceStatusElement) {
                        maintenanceStatusElement.textContent = 'Disabled';
                        maintenanceStatusElement.className = 'fw-semibold mt-1 text-danger';
                    }
                    
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Failed to disable maintenance mode', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to toggle maintenance mode', 'danger');
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    }
}

function clearSystemCache() {
    console.log('clearSystemCache called');
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Clearing...';
    button.disabled = true;
    
    fetch('{{ route("admin.tools.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Cache clear response:', response);
        return response.json();
    })
    .then(data => {
        console.log('Cache clear data:', data);
        if (data.success) {
            showToast(data.message || 'Cache cleared successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to clear cache', 'danger');
        }
    })
    .catch(error => {
        console.error('Cache clear error:', error);
        showToast('Failed to clear cache: ' + error.message, 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function optimizeDatabase() {
    console.log('optimizeDatabase called');
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Optimizing...';
    button.disabled = true;
    
    fetch('{{ route("admin.tools.maintenance.optimize") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'database' })
    })
    .then(response => {
        console.log('Database optimize response:', response);
        return response.json();
    })
    .then(data => {
        console.log('Database optimize data:', data);
        if (data.success) {
            showToast(data.message || 'Database optimized successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to optimize database', 'danger');
        }
    })
    .catch(error => {
        console.error('Database optimize error:', error);
        showToast('Failed to optimize database: ' + error.message, 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function cleanLogFiles() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Cleaning...';
    button.disabled = true;
    
    fetch('{{ route("admin.tools.logs.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Failed to clean log files', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to clean log files', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function rebuildSearchIndex() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Rebuilding...';
    button.disabled = true;
    
    fetch('{{ route("admin.tools.maintenance.optimize") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'search' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Failed to rebuild search index', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to rebuild search index', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function runHealthCheck() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Checking...';
    button.disabled = true;
    
    fetch('{{ route("admin.tools.maintenance.health") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('System health check completed successfully!', 'success');
            updateSystemStatusCards(data.data);
        } else {
            showToast(data.message || 'Health check failed', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to run health check', 'danger');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function runTask(taskId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
    
    showToast(`Running ${taskId} task...`, 'info');
    
    fetch('{{ route("admin.tools.task.run") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            // Update the last run time in the table
            const now = new Date().toLocaleString();
            const row = document.querySelector(`#task-status-${taskId}`).closest('tr');
            if (row) {
                const lastRunCell = row.children[2];
                lastRunCell.textContent = now;
            }
            
            // Refresh the scheduled tasks table by reloading system status
            loadSystemStatus();
        } else {
            showToast(data.message || 'Failed to run task', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to run task', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function pauseTask(taskId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
    
    fetch('{{ route("admin.tools.task.pause") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'warning');
            
            // Update status in table
            const statusBadge = document.getElementById(`task-status-${taskId}`);
            if (statusBadge) {
                statusBadge.className = 'badge bg-warning';
                statusBadge.textContent = 'Paused';
                
                // Update action buttons
                const row = statusBadge.closest('tr');
                const actionCell = row.children[5];
                actionCell.innerHTML = `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="runTask('${taskId}')" title="Run Now">
                            <i class="bx bx-play"></i>
                        </button>
                        <button class="btn btn-sm btn-success" onclick="resumeTask('${taskId}')" title="Resume">
                            <i class="bx bx-play-circle"></i>
                        </button>
                    </div>
                `;
            }
            
            // Refresh the scheduled tasks table by reloading system status
            loadSystemStatus();
        } else {
            showToast(data.message || 'Failed to pause task', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to pause task', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function resumeTask(taskId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
    
    fetch('{{ route("admin.tools.task.resume") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            
            // Update status in table
            const statusBadge = document.getElementById(`task-status-${taskId}`);
            if (statusBadge) {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'Active';
                
                // Update action buttons
                const row = statusBadge.closest('tr');
                const actionCell = row.children[5];
                actionCell.innerHTML = `
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="runTask('${taskId}')" title="Run Now">
                            <i class="bx bx-play"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="pauseTask('${taskId}')" title="Pause">
                            <i class="bx bx-pause"></i>
                        </button>
                    </div>
                `;
            }
            
            // Refresh the scheduled tasks table by reloading system status
            loadSystemStatus();
        } else {
            showToast(data.message || 'Failed to resume task', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to resume task', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function showToast(message, type) {
    // Try different approaches for showing notifications
    
    // First try Bootstrap Toast
    try {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Add to body
        document.body.appendChild(toast);
        
        // Try to show with Bootstrap
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove after it's hidden
            toast.addEventListener('hidden.bs.toast', () => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            });
        } else {
            // Fallback: Show manually and remove after 5 seconds
            toast.style.display = 'block';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 5000);
        }
    } catch (error) {
        // Fallback to simple alert if toast fails
        console.error('Toast error:', error);
        alert(message);
    }
    
    // Also log to console for debugging
    console.log(`[${type.toUpperCase()}] ${message}`);
}
</script>
@endpush
