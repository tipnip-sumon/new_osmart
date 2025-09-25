@extends('admin.layouts.app')

@section('title', 'Sidebar Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-xl-12">
            <div class="page-titles">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h1 class="mb-0">Sidebar Settings</h1>
                        <p class="text-muted">Customize your admin sidebar appearance and behavior</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="previewChanges()">
                            <i class="bx bx-show"></i> Preview Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Settings Panel -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sidebar Configuration</h5>
                </div>
                <div class="card-body">
                    <form id="sidebarSettingsForm">
                        @csrf
                        
                        <!-- General Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">General Settings</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Sidebar Theme</label>
                                    <select class="form-select" name="sidebar_theme" id="sidebar_theme">
                                        <option value="default">Default Theme</option>
                                        <option value="dark">Dark Theme</option>
                                        <option value="compact">Compact Theme</option>
                                        <option value="modern">Modern Theme</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Menu Template</label>
                                    <select class="form-select" name="menu_template" id="menu_template">
                                        <option value="default">Default Template</option>
                                        <option value="modern">Modern Template</option>
                                        <option value="compact">Compact Template</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Behavior Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Behavior Settings</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_collapse" name="auto_collapse" checked>
                                    <label class="form-check-label" for="auto_collapse">
                                        Auto-collapse other menus
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="remember_state" name="remember_state" checked>
                                    <label class="form-check-label" for="remember_state">
                                        Remember menu states
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="smooth_animations" name="smooth_animations" checked>
                                    <label class="form-check-label" for="smooth_animations">
                                        Smooth animations
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="show_badges" name="show_badges" checked>
                                    <label class="form-check-label" for="show_badges">
                                        Show notification badges
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="show_icons" name="show_icons" checked>
                                    <label class="form-check-label" for="show_icons">
                                        Show menu icons
                                    </label>
                                </div>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="compact_mode" name="compact_mode">
                                    <label class="form-check-label" for="compact_mode">
                                        Compact mode
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Cache Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">Cache Settings</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Cache Duration (minutes)</label>
                                    <input type="number" class="form-control" name="cache_duration" value="60" min="5" max="1440">
                                    <small class="text-muted">How long to cache menu data</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3 mt-4">
                                    <input class="form-check-input" type="checkbox" id="auto_clear_cache" name="auto_clear_cache">
                                    <label class="form-check-label" for="auto_clear_cache">
                                        Auto-clear cache when menus change
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="resetToDefaults()">
                                        <i class="bx bx-reset"></i> Reset to Defaults
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="exportSettings()">
                                        <i class="bx bx-export"></i> Export Settings
                                    </button>
                                    <button type="button" class="btn btn-warning" onclick="clearSidebarCache()">
                                        <i class="bx bx-refresh"></i> Clear Cache
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Live Preview</h5>
                </div>
                <div class="card-body">
                    <div class="sidebar-preview-container">
                        <div class="preview-sidebar" id="preview-sidebar">
                            <div class="preview-header">
                                <h6>Admin Panel</h6>
                            </div>
                            <div class="preview-menu">
                                <div class="preview-category">Main</div>
                                <div class="preview-item active">
                                    <i class="bx bx-home"></i> Dashboard
                                </div>
                                <div class="preview-item">
                                    <i class="bx bx-package"></i> Products
                                </div>
                                <div class="preview-submenu">
                                    <div class="preview-subitem">All Products</div>
                                    <div class="preview-subitem">Add Product</div>
                                </div>
                                <div class="preview-item">
                                    <i class="bx bx-shopping-bag"></i> Orders
                                    <span class="preview-badge">5</span>
                                </div>
                                <div class="preview-category">System</div>
                                <div class="preview-item">
                                    <i class="bx bx-menu"></i> Menu Management
                                    <span class="preview-badge new">New</span>
                                </div>
                                <div class="preview-item">
                                    <i class="bx bx-cog"></i> Settings
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bx bx-info-circle"></i>
                            Changes will be reflected here in real-time
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    @php
                        use App\Helpers\AdminMenuHelper;
                        $stats = AdminMenuHelper::getMenuStats();
                    @endphp
                    
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary mb-0">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total Menus</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success mb-0">{{ $stats['active'] }}</h4>
                                <small class="text-muted">Active</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-info mb-0">{{ $stats['parents'] }}</h4>
                                <small class="text-muted">Parents</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning mb-0">{{ $stats['children'] }}</h4>
                                <small class="text-muted">Children</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar-preview-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    min-height: 400px;
}

.preview-sidebar {
    background: white;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-height: 350px;
}

.preview-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.preview-header h6 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.preview-category {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #adb5bd;
    margin: 15px 0 5px 0;
    letter-spacing: 0.5px;
}

.preview-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    margin: 2px 0;
    border-radius: 4px;
    color: #6c757d;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.preview-item:hover {
    background-color: #e9ecef;
    color: #495057;
}

.preview-item.active {
    background-color: #007bff;
    color: white;
}

.preview-item i {
    margin-right: 8px;
    font-size: 16px;
}

.preview-submenu {
    margin-left: 20px;
    border-left: 2px solid #e9ecef;
    padding-left: 10px;
}

.preview-subitem {
    padding: 5px 12px;
    font-size: 13px;
    color: #6c757d;
    cursor: pointer;
    border-radius: 3px;
    margin: 1px 0;
}

.preview-subitem:hover {
    background-color: #f8f9fa;
}

.preview-badge {
    margin-left: auto;
    background: #dc3545;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.preview-badge.new {
    background: #28a745;
}

/* Theme variations */
.preview-sidebar.dark {
    background: #2c3e50;
    color: white;
}

.preview-sidebar.dark .preview-item {
    color: #bdc3c7;
}

.preview-sidebar.dark .preview-item:hover {
    background-color: #34495e;
    color: white;
}

.preview-sidebar.compact .preview-item {
    padding: 4px 8px;
    font-size: 13px;
}

.preview-sidebar.modern .preview-item {
    border-radius: 8px;
    margin: 3px 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load saved settings
    loadSavedSettings();
    
    // Watch for changes and update preview
    const form = document.getElementById('sidebarSettingsForm');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', updatePreview);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings();
    });
});

function updatePreview() {
    const theme = document.getElementById('sidebar_theme').value;
    const showIcons = document.getElementById('show_icons').checked;
    const showBadges = document.getElementById('show_badges').checked;
    const compactMode = document.getElementById('compact_mode').checked;
    
    const previewSidebar = document.getElementById('preview-sidebar');
    
    // Reset classes
    previewSidebar.className = 'preview-sidebar';
    
    // Apply theme
    if (theme !== 'default') {
        previewSidebar.classList.add(theme);
    }
    
    // Apply compact mode
    if (compactMode) {
        previewSidebar.classList.add('compact');
    }
    
    // Toggle icons
    const icons = previewSidebar.querySelectorAll('.preview-item i');
    icons.forEach(icon => {
        icon.style.display = showIcons ? 'inline' : 'none';
    });
    
    // Toggle badges
    const badges = previewSidebar.querySelectorAll('.preview-badge');
    badges.forEach(badge => {
        badge.style.display = showBadges ? 'inline' : 'none';
    });
}

function saveSettings() {
    const formData = new FormData(document.getElementById('sidebarSettingsForm'));
    const settings = {};
    
    for (let [key, value] of formData.entries()) {
        settings[key] = value;
    }
    
    // Add checkbox values
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        settings[checkbox.name] = checkbox.checked;
    });
    
    // Save to localStorage
    localStorage.setItem('sidebar_settings', JSON.stringify(settings));
    
    // Show success message
    showNotification('Sidebar settings saved successfully!', 'success');
    
    // Apply settings immediately
    applySidebarSettings(settings);
}

function loadSavedSettings() {
    const saved = localStorage.getItem('sidebar_settings');
    if (saved) {
        const settings = JSON.parse(saved);
        
        Object.keys(settings).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = settings[key];
                } else {
                    input.value = settings[key];
                }
            }
        });
        
        updatePreview();
    }
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to defaults?')) {
        localStorage.removeItem('sidebar_settings');
        location.reload();
    }
}

function exportSettings() {
    const settings = localStorage.getItem('sidebar_settings') || '{}';
    const blob = new Blob([settings], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sidebar-settings.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    showNotification('Settings exported successfully!', 'success');
}

function clearSidebarCache() {
    if (confirm('Clear sidebar cache?')) {
        fetch('{{ route("admin.menu.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Cache cleared successfully!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error clearing cache', 'error');
        });
    }
}

function applySidebarSettings(settings) {
    // Apply settings to the actual sidebar
    // This would integrate with your main sidebar
    console.log('Applying settings:', settings);
}

function previewChanges() {
    updatePreview();
    showNotification('Preview updated!', 'info');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection
