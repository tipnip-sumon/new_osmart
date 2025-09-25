@extends('admin.layouts.app')

@section('title', 'KYC Management Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4 my-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    {{ $pageTitle ?? 'KYC Verifications Management' }}
                </h4>
                <div class="page-title-right">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportKycData()">
                            <i class="fas fa-download me-1"></i> Export CSV
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="alertContainer">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-4" id="statsCards">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-primary text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total KYC</p>
                            <h4 class="mb-2" id="total-kyc">{{ $stats['total'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">
                                <span class="text-success me-1">
                                    <i class="ri-arrow-up-line me-1 align-middle"></i>
                                    {{ $stats['this_month'] ?? 0 }}
                                </span>
                                This month
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-warning text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Pending</p>
                            <h4 class="mb-2" id="pending-kyc">{{ $stats['pending'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">
                                <span class="text-danger me-1">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $stats['pending_over_7_days'] ?? 0 }}
                                </span>
                                Over 7 days
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-info text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Under Review</p>
                            <h4 class="mb-2" id="under-review-kyc">{{ $stats['under_review'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">Being processed</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-eye fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-success text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Approved</p>
                            <h4 class="mb-2" id="approved-kyc">{{ $stats['approved'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">
                                <span class="text-light me-1">
                                    <i class="fas fa-chart-line me-1"></i>
                                    {{ $stats['avg_processing_days'] ?? 0 }}d
                                </span>
                                Avg. time
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-danger text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Rejected</p>
                            <h4 class="mb-2" id="rejected-kyc">{{ $stats['rejected'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">Need resubmission</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card bg-secondary text-white mini-stat">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">High Risk</p>
                            <h4 class="mb-2" id="high-risk-kyc">{{ $stats['high_risk'] ?? 0 }}</h4>
                            <p class="text-truncate mb-0">Requires attention</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-filter me-2"></i>
                                Advanced Filters & Search
                            </h4>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                                <i class="fas fa-chevron-down me-1"></i>
                                Toggle Filters
                            </button>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="filtersCollapse">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" id="searchInput" 
                                       placeholder="Name, email, document no..." 
                                       value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ ($filters['status'] ?? '') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="verified" {{ ($filters['status'] ?? '') == 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Risk Level</label>
                                <select class="form-select" name="risk_level" id="riskLevelFilter">
                                    <option value="">All Risk Levels</option>
                                    <option value="low" {{ ($filters['risk_level'] ?? '') == 'low' ? 'selected' : '' }}>Low Risk</option>
                                    <option value="medium" {{ ($filters['risk_level'] ?? '') == 'medium' ? 'selected' : '' }}>Medium Risk</option>
                                    <option value="high" {{ ($filters['risk_level'] ?? '') == 'high' ? 'selected' : '' }}>High Risk</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Document Type</label>
                                <select class="form-select" name="document_type" id="documentTypeFilter">
                                    <option value="">All Documents</option>
                                    <option value="nid" {{ ($filters['document_type'] ?? '') == 'nid' ? 'selected' : '' }}>National ID</option>
                                    <option value="passport" {{ ($filters['document_type'] ?? '') == 'passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="driving_license" {{ ($filters['document_type'] ?? '') == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                </select>
                            </div>
                            <div class="col-md-1-5">
                                <label class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from" id="dateFromFilter" 
                                       value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                            <div class="col-md-1-5">
                                <label class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to" id="dateToFilter" 
                                       value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                    <i class="fas fa-search me-1"></i> Apply Filters
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i> Clear Filters
                                </button>
                                <div class="float-end">
                                    <label class="form-label me-2">Per Page:</label>
                                    <select class="form-select d-inline-block w-auto" name="per_page" onchange="applyFilters()">
                                        <option value="10" {{ ($filters['per_page'] ?? 20) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="20" {{ ($filters['per_page'] ?? 20) == 20 ? 'selected' : '' }}>20</option>
                                        <option value="50" {{ ($filters['per_page'] ?? 20) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ ($filters['per_page'] ?? 20) == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main KYC Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">KYC Verification List</h4>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning btn-sm" onclick="bulkAction('under_review')">
                                    <i class="fas fa-eye me-1"></i> Mark Selected as Under Review
                                </button>
                                <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('verified')">>
                                    <i class="fas fa-check me-1"></i> Bulk Approve
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('rejected')">
                                    <i class="fas fa-times me-1"></i> Bulk Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-4" style="display: none;">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">Loading KYC data...</p>
                    </div>
                    
                    <div id="tableContainer">
                        @include('admin.kyc.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Action Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to <span id="actionType"></span> the selected <span id="selectedCount"></span> KYC verification(s)?</p>
                <div class="mb-3">
                    <label class="form-label">Admin Remarks (Optional)</label>
                    <textarea class="form-control" id="bulkAdminRemarks" rows="3" 
                              placeholder="Add remarks for this bulk action..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmBulkAction">Confirm Action</button>
            </div>
        </div>
    </div>
</div>

<!-- Generic Modal for Activity Log and other content -->
<div class="modal fade" id="genericModal" tabindex="-1" aria-labelledby="genericModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="genericModalLabel">Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="genericModalBody">
                <!-- Content will be loaded dynamically -->
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
let selectedKycs = [];
let currentFilters = {};
let autoRefreshInterval;

$(document).ready(function() {
    // Initialize
    initializeEventListeners();
    startAutoRefresh();
    
    // Real-time search
    $('#searchInput').on('input', debounce(function() {
        applyFilters();
    }, 500));
});

function initializeEventListeners() {
    // Filter change events
    $('#statusFilter, #riskLevelFilter, #documentTypeFilter, #dateFromFilter, #dateToFilter').on('change', function() {
        applyFilters();
    });
    
    // Bulk checkbox events
    $(document).on('change', '#selectAll', function() {
        const isChecked = $(this).is(':checked');
        $('.kyc-checkbox').prop('checked', isChecked);
        updateSelectedKycs();
    });
    
    $(document).on('change', '.kyc-checkbox', function() {
        updateSelectedKycs();
    });
}

function applyFilters() {
    const formData = $('#filterForm').serialize();
    const perPage = $('select[name="per_page"]').val();
    
    $('#loading').show();
    $('#tableContainer').hide();
    
    currentFilters = {
        search: $('#searchInput').val(),
        status: $('#statusFilter').val(),
        risk_level: $('#riskLevelFilter').val(),
        document_type: $('#documentTypeFilter').val(),
        date_from: $('#dateFromFilter').val(),
        date_to: $('#dateToFilter').val(),
        per_page: perPage
    };
    
    $.ajax({
        url: '{{ route("admin.kyc.index") }}',
        method: 'GET',
        data: currentFilters,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            $('#tableContainer').html(response.html);
            updateStats(response.stats);
            $('#loading').hide();
            $('#tableContainer').show();
        },
        error: function(xhr) {
            showAlert('danger', 'Failed to load KYC data');
            $('#loading').hide();
            $('#tableContainer').show();
        }
    });
}

function clearFilters() {
    $('#filterForm')[0].reset();
    $('select[name="per_page"]').val(20);
    applyFilters();
}

function refreshData() {
    applyFilters();
    showAlert('success', 'Data refreshed successfully');
}

function updateStats(stats) {
    $('#total-kyc').text(stats.total || 0);
    $('#pending-kyc').text(stats.pending || 0);
    $('#under-review-kyc').text(stats.under_review || 0);
    $('#approved-kyc').text(stats.verified || 0);
    $('#rejected-kyc').text(stats.rejected || 0);
    $('#high-risk-kyc').text(stats.high_risk || 0);
}

function updateSelectedKycs() {
    selectedKycs = [];
    $('.kyc-checkbox:checked').each(function() {
        selectedKycs.push($(this).val());
    });
    
    // Update select all checkbox
    const totalCheckboxes = $('.kyc-checkbox').length;
    const checkedCheckboxes = $('.kyc-checkbox:checked').length;
    
    if (checkedCheckboxes === 0) {
        $('#selectAll').prop('indeterminate', false).prop('checked', false);
    } else if (checkedCheckboxes === totalCheckboxes) {
        $('#selectAll').prop('indeterminate', false).prop('checked', true);
    } else {
        $('#selectAll').prop('indeterminate', true);
    }
}

function bulkAction(action) {
    if (selectedKycs.length === 0) {
        showAlert('warning', 'Please select at least one KYC verification');
        return;
    }
    
    $('#actionType').text(action.replace('_', ' '));
    $('#selectedCount').text(selectedKycs.length);
    $('#bulkAdminRemarks').val('');
    
    $('#confirmBulkAction').off('click').on('click', function() {
        performBulkAction(action);
    });
    
    $('#bulkActionModal').modal('show');
}

function performBulkAction(action) {
    const remarks = $('#bulkAdminRemarks').val();
    
    $.ajax({
        url: '{{ route("admin.kyc.bulk-change-status") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            kyc_ids: selectedKycs,
            status: action,
            admin_remarks: remarks
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                $('#bulkActionModal').modal('hide');
                applyFilters();
                selectedKycs = [];
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            showAlert('danger', 'Failed to perform bulk action');
        }
    });
}

function exportKycData() {
    const params = new URLSearchParams(currentFilters);
    window.location.href = '{{ route("admin.kyc.export.csv") }}?' + params.toString();
}

function startAutoRefresh() {
    // Refresh stats every 30 seconds
    autoRefreshInterval = setInterval(function() {
        $.ajax({
            url: '{{ route("admin.kyc.dashboard.stats") }}',
            method: 'GET',
            success: function(stats) {
                updateStats(stats);
            }
        });
    }, 30000);
}

function updateKycStatus(kycId, status) {
    const remarks = prompt(`Please enter remarks for ${status} this KYC:`);
    if (remarks === null) return;
    
    $.ajax({
        url: `/admin/kyc/${kycId}/update-status`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status,
            admin_remarks: remarks
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                applyFilters();
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            showAlert('danger', 'Failed to update KYC status');
        }
    });
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#alertContainer').html(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

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

// Cleanup on page unload
$(window).on('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});

// Missing functions for table actions
function showActivityLog(kycId) {
    $.ajax({
        url: `/admin/kyc/${kycId}/activity-log`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const activities = response.data;
                let activityHtml = '<div class="list-group">';
                
                activities.forEach(activity => {
                    const date = new Date(activity.date).toLocaleString();
                    activityHtml += `
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${activity.action}</h6>
                                <small>${date}</small>
                            </div>
                            <p class="mb-1">${activity.notes}</p>
                            <small>By: ${activity.admin || 'System'}</small>
                        </div>
                    `;
                });
                
                activityHtml += '</div>';
                
                // Show in modal
                $('#genericModalLabel').text('Activity Log');
                $('#genericModalBody').html(activityHtml);
                $('#genericModal').modal('show');
            }
        },
        error: function(xhr) {
            showAlert('danger', 'Failed to load activity log');
        }
    });
}

function updateRiskLevel(kycId) {
    const currentLevel = prompt('Enter new risk level (low, medium, high):');
    if (!currentLevel || !['low', 'medium', 'high'].includes(currentLevel.toLowerCase())) {
        if (currentLevel !== null) {
            showAlert('danger', 'Invalid risk level. Please enter: low, medium, or high');
        }
        return;
    }
    
    const notes = prompt('Risk assessment notes (optional):') || '';
    
    $.ajax({
        url: `/admin/kyc/${kycId}/update-risk-level`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            risk_level: currentLevel.toLowerCase(),
            risk_notes: notes
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                applyFilters();
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            showAlert('danger', 'Failed to update risk level');
        }
    });
}

function downloadAllDocuments(kycId) {
    // First try to download as ZIP
    $.ajax({
        url: `/admin/kyc/${kycId}/documents/download-all`,
        type: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            // Check if response is JSON (indicating ZIP not available)
            const contentType = xhr.getResponseHeader('Content-Type');
            if (contentType && contentType.includes('application/json')) {
                // Convert blob to text and parse JSON
                const reader = new FileReader();
                reader.onload = function() {
                    try {
                        const response = JSON.parse(reader.result);
                        if (response.documents) {
                            // Show modal with individual download links
                            showIndividualDownloadModal(kycId, response.documents);
                        } else if (response.error) {
                            showAlert('danger', response.error);
                        }
                    } catch (e) {
                        showAlert('danger', 'Failed to process download response');
                    }
                };
                reader.readAsText(data);
            } else {
                // Handle binary file download (ZIP)
                const blob = new Blob([data], { type: 'application/zip' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `kyc_documents_${kycId}.zip`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                showAlert('success', 'Documents downloaded successfully');
            }
        },
        error: function(xhr) {
            try {
                const response = JSON.parse(xhr.responseText);
                showAlert('danger', response.error || 'Failed to download documents');
            } catch (e) {
                showAlert('danger', 'Failed to download documents');
            }
        }
    });
}

function showIndividualDownloadModal(kycId, documents) {
    let modalHtml = `
        <div class="modal fade" id="downloadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Documents</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-info mb-3">ZIP functionality is not available. Please download documents individually:</p>
                        <div class="list-group">
    `;
    
    documents.forEach(doc => {
        const documentTypeMap = {
            'document_front_image': 'front',
            'document_back_image': 'back', 
            'user_photo': 'selfie',
            'user_signature': 'signature',
            'utility_bill': 'utility'
        };
        
        const downloadType = documentTypeMap[doc.field] || doc.field;
        const downloadUrl = `/admin/kyc/${kycId}/document/download/${downloadType}`;
        
        modalHtml += `
            <a href="${downloadUrl}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" target="_blank">
                <div>
                    <strong>${doc.name}</strong>
                    <br>
                    <small class="text-muted">${(doc.size/1024).toFixed(1)} KB • ${doc.extension.toUpperCase()}</small>
                </div>
                <i class="fas fa-download text-primary"></i>
            </a>
        `;
    });
    
    modalHtml += `
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#downloadModal').remove();
    
    // Add new modal to body and show
    $('body').append(modalHtml);
    $('#downloadModal').modal('show');
    
    // Clean up when modal is hidden
    $('#downloadModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}
</script>
@endpush

@push('styles')
<style>
.mini-stat {
    transition: transform 0.2s ease-in-out;
}

.mini-stat:hover {
    transform: translateY(-2px);
}

.kyc-table .table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

.risk-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.action-buttons .btn {
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}

.col-md-1-5 {
    flex: 0 0 auto;
    width: 12.5%;
}

@media (max-width: 768px) {
    .col-md-1-5 {
        width: 50%;
    }
    
    .mini-stat {
        margin-bottom: 1rem;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-right: 0;
    }
}

.table-responsive {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.loading-overlay {
    position: relative;
}

.loading-overlay::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1000;
}
</style>
@endpush