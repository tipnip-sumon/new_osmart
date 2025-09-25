@extends('admin.layouts.app')

@section('title', 'MLM Bonus Settings')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">MLM Bonus Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">MLM Bonus Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">MLM Bonus Settings Management</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#initializeModal">
                                    <i class="bx bx-refresh"></i> Initialize Defaults
                                </button>
                                <a href="{{ route('admin.mlm-bonus-settings.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus"></i> Add New Setting
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $key => $name)
                                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        @foreach($types as $key => $name)
                                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search settings..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form id="bulkActionForm" method="POST" action="{{ route('admin.mlm-bonus-settings.bulk-action') }}">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    <select name="action" class="form-select" style="width: auto;">
                                        <option value="">Bulk Actions</option>
                                        <option value="activate">Activate</option>
                                        <option value="deactivate">Deactivate</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-secondary" onclick="return confirmBulkAction()">Apply</button>
                                    <span class="text-muted">|</span>
                                    <span id="selectedCount">0 selected</span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Settings Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Setting Name</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $setting->id }}" class="form-check-input row-checkbox">
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $setting->setting_name }}</strong>
                                                <small class="d-block text-muted">{{ $setting->setting_key }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $setting->category_name }}</span>
                                            @if($setting->subcategory)
                                                <small class="d-block text-muted">{{ $setting->subcategory }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $setting->setting_type_name }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $setting->formatted_value }}</strong>
                                            @if($setting->threshold_amount)
                                                <small class="d-block text-muted">Min: {{ formatCurrency($setting->threshold_amount) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($setting->level)
                                                <span class="badge bg-primary">Level {{ $setting->level }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" 
                                                       type="checkbox" 
                                                       data-id="{{ $setting->id }}"
                                                       {{ $setting->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('admin.mlm-bonus-settings.show', $setting) }}">
                                                        <i class="bx bx-show"></i> View
                                                    </a></li>
                                                    @if($setting->is_editable)
                                                        <li><a class="dropdown-item" href="{{ route('admin.mlm-bonus-settings.edit', $setting) }}">
                                                            <i class="bx bx-edit"></i> Edit
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteSetting({{ $setting->id }})">
                                                            <i class="bx bx-trash"></i> Delete
                                                        </a></li>
                                                    @else
                                                        <li><span class="dropdown-item text-muted">
                                                            <i class="bx bx-lock"></i> Protected
                                                        </span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bx bx-data display-4 text-muted mb-2"></i>
                                                <p class="text-muted">No MLM bonus settings found.</p>
                                                <a href="{{ route('admin.mlm-bonus-settings.create') }}" class="btn btn-primary">
                                                    <i class="bx bx-plus"></i> Create First Setting
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($settings->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $settings->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Defaults Modal -->
<div class="modal fade" id="initializeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Initialize Default Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This will create default MLM bonus settings for all categories including:</p>
                <ul>
                    <li>Sponsor Commission</li>
                    <li>Binary Matching</li>
                    <li>Unilevel Commission (Multiple Levels)</li>
                    <li>Generation Commission</li>
                    <li>Rank Bonuses</li>
                    <li>Club Bonuses</li>
                    <li>Daily Cashback</li>
                </ul>
                <p class="text-warning"><strong>Note:</strong> Existing settings with the same key will be updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.mlm-bonus-settings.initialize-defaults') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Initialize Defaults</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const selectedCount = document.getElementById('selectedCount');

    selectAll.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.textContent = `${checked} selected`;
        selectAll.checked = checked === rowCheckboxes.length;
        selectAll.indeterminate = checked > 0 && checked < rowCheckboxes.length;
    }

    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const isActive = this.checked;
            
            fetch(`{{ url('admin/mlm-bonus-settings') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update label
                    const label = this.nextElementSibling;
                    label.textContent = data.is_active ? 'Active' : 'Inactive';
                    
                    // Show toast notification
                    showToast('success', data.message);
                } else {
                    // Revert toggle on error
                    this.checked = !isActive;
                    showToast('error', 'Failed to update status');
                }
            })
            .catch(error => {
                // Revert toggle on error
                this.checked = !isActive;
                showToast('error', 'An error occurred');
            });
        });
    });
});

function confirmBulkAction() {
    const selectedCount = document.querySelectorAll('.row-checkbox:checked').length;
    const action = document.querySelector('select[name="action"]').value;
    
    if (selectedCount === 0) {
        alert('Please select at least one setting.');
        return false;
    }
    
    if (!action) {
        alert('Please select an action.');
        return false;
    }
    
    return confirm(`Are you sure you want to ${action} ${selectedCount} setting(s)?`);
}

function deleteSetting(id) {
    if (confirm('Are you sure you want to delete this setting?')) {
        fetch(`{{ url('admin/mlm-bonus-settings') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Failed to delete setting');
            }
        })
        .catch(error => {
            alert('An error occurred');
        });
    }
}

function showToast(type, message) {
    // Implement toast notification
    // You can use your preferred toast library here
    console.log(`${type}: ${message}`);
}
</script>
@endpush
