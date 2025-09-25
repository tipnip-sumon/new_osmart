@extends('admin.layouts.app')

@section('title', 'Invoice Management')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoice Management</h4>
                    <div>
                        <a href="{{ route('admin.invoices.analytics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Analytics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Order Date</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                    </td>
                                    <td>
                                        <strong>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $order->customer->name ?? 'Guest Customer' }}</h6>
                                                <small class="text-muted">{{ $order->customer->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $order->created_at->format('M d, Y') }}</span><br>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">Tk {{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @switch($order->payment_status)
                                            @case('paid')
                                                <span class="badge bg-success">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('admin.invoices.preview', $order->id) }}">
                                                    <i class="fas fa-eye"></i> Preview
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.invoices.professional', $order->id) }}" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Professional Invoice
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.invoices.professional.download', $order->id) }}">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.invoices.print', $order->id) }}" target="_blank">
                                                    <i class="fas fa-print"></i> Print
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-info" href="#" onclick="emailInvoice({{ $order->id }})">
                                                    <i class="fas fa-envelope"></i> Email Invoice
                                                </a></li>
                                                <li><a class="dropdown-item text-primary" href="#" onclick="sendReminder({{ $order->id }})">
                                                    <i class="fas fa-bell"></i> Send Reminder
                                                </a></li>
                                                <li><a class="dropdown-item text-success" href="#" onclick="markAsPaid({{ $order->id }})">
                                                    <i class="fas fa-check-circle"></i> Mark as Paid
                                                </a></li>
                                                <li><a class="dropdown-item text-secondary" href="{{ route('admin.invoices.duplicate', $order->id) }}">
                                                    <i class="fas fa-copy"></i> Duplicate
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No Invoices Found</h5>
                                            <p class="text-muted">No paid orders available for invoice generation.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
@if($orders->count() > 0)
<div class="card mt-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="mb-0">Bulk Actions</h6>
                <small class="text-muted">Select invoices above to perform bulk operations</small>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="bulkDownload()">
                    <i class="fas fa-download"></i> Bulk Download
                </button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="bulkEmail()">
                    <i class="fas fa-envelope"></i> Bulk Email
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Email Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (Optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Add a personal message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('Invoice page JavaScript loaded');

let currentOrderId = null;

// Ensure Bootstrap 5 is available, fallback to jQuery if needed
function initializeBootstrap() {
    if (typeof bootstrap === 'undefined' && typeof $ !== 'undefined') {
        console.warn('Bootstrap 5 not found, using jQuery fallback for modals');
        window.bootstrap = {
            Modal: function(element) {
                return {
                    show: function() { $(element).modal('show'); },
                    hide: function() { $(element).modal('hide'); }
                };
            }
        };
        bootstrap.Modal.getInstance = function(element) {
            return bootstrap.Modal(element);
        };
    }
    
    // Ensure Bootstrap is available globally
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap 5 is available');
        return true;
    }
    
    console.error('Bootstrap is not available');
    return false;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    initializeBootstrap();
    
    // Initialize Bootstrap 5 dropdowns
    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        console.log('Initializing Bootstrap 5 dropdowns...');
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => {
            try {
                return new bootstrap.Dropdown(dropdownToggleEl);
            } catch (error) {
                console.error('Error initializing dropdown:', error);
                return null;
            }
        });
        console.log(`Initialized ${dropdownList.filter(d => d !== null).length} dropdowns`);
    } else {
        console.log('Using fallback dropdown handling...');
        // Fallback for manual dropdown handling
        const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        dropdownElements.forEach(function(dropdown, index) {
            console.log(`Setting up dropdown ${index + 1}`);
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Dropdown clicked');
                
                // Close all other dropdowns first
                document.querySelectorAll('.dropdown-menu.show').forEach(function(otherMenu) {
                    otherMenu.classList.remove('show');
                });
                
                // Toggle current dropdown
                const menu = this.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    menu.classList.toggle('show');
                    console.log('Dropdown menu toggled');
                } else {
                    console.error('Dropdown menu not found');
                }
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-group')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                    menu.classList.remove('show');
                });
            }
        });
    }
    
    // Initialize select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

// Email invoice function
function emailInvoice(orderId) {
    currentOrderId = orderId;
    
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
        emailModal.show();
    } else if (typeof $ !== 'undefined') {
        $('#emailModal').modal('show');
    } else {
        // Fallback - show modal manually
        const modal = document.getElementById('emailModal');
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modal-backdrop';
        document.body.appendChild(backdrop);
    }
}

// Send reminder function
function sendReminder(orderId) {
    if (!confirm('Are you sure you want to send a payment reminder for this invoice?')) {
        return;
    }
    
    fetch(`{{ url('admin/invoices') }}/${orderId}/reminder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Payment reminder sent successfully!');
        } else {
            showAlert('error', 'Error: ' + (data.message || 'Failed to send reminder'));
        }
    })
    .catch(error => {
        console.error('Error sending reminder:', error);
        showAlert('error', 'Error sending reminder: ' + error.message);
    });
}

// Mark as paid function
function markAsPaid(orderId) {
    if (!confirm('Are you sure you want to mark this invoice as paid?')) {
        return;
    }
    
    fetch(`{{ url('admin/invoices') }}/${orderId}/mark-paid`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showAlert('success', 'Invoice marked as paid successfully!');
            // Reload the page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('error', 'Error: ' + (data.message || 'Failed to mark as paid'));
        }
    })
    .catch(error => {
        console.error('Error marking as paid:', error);
        showAlert('error', 'Error marking as paid: ' + error.message);
    });
}

// Email form submission
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Disable submit button to prevent double submission
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    console.log('Sending email to:', email, 'for order:', currentOrderId);
    
    fetch(`{{ url('admin/invoices') }}/${currentOrderId}/email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            email: email,
            message: message
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Check if response is actually JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('Response is not JSON, content-type:', contentType);
            // Try to get the response as text to see what we actually received
            return response.text().then(text => {
                console.error('Response body:', text);
                throw new Error('Server returned HTML instead of JSON. Check server logs for errors.');
            });
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Hide modal
            hideEmailModal();
            showAlert('success', 'Invoice sent successfully!');
            document.getElementById('emailForm').reset();
        } else {
            showAlert('error', 'Error: ' + (data.message || 'Failed to send invoice'));
        }
    })
    .catch(error => {
        console.error('Error sending invoice:', error);
        showAlert('error', 'Error sending invoice: ' + error.message);
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = 'Send Invoice';
    });
});

// Helper function to hide email modal
function hideEmailModal() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const emailModal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
        if (emailModal) emailModal.hide();
    } else if (typeof $ !== 'undefined') {
        $('#emailModal').modal('hide');
    } else {
        // Manual hide
        const modal = document.getElementById('emailModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        const backdrop = document.getElementById('modal-backdrop');
        if (backdrop) backdrop.remove();
    }
}

// Bulk download function
function bulkDownload() {
    const selectedOrders = getSelectedOrders();
    if (selectedOrders.length === 0) {
        showAlert('warning', 'Please select at least one invoice.');
        return;
    }
    
    fetch('{{ route("admin.invoices.bulk") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_ids: selectedOrders,
            action: 'download'
        })
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Network response was not ok');
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'bulk-invoices.zip';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        showAlert('success', 'Bulk invoices downloaded successfully!');
    })
    .catch(error => {
        showAlert('error', 'Error downloading invoices: ' + error.message);
    });
}

// Bulk email function
function bulkEmail() {
    const selectedOrders = getSelectedOrders();
    if (selectedOrders.length === 0) {
        showAlert('warning', 'Please select at least one invoice.');
        return;
    }
    
    const emails = prompt('Enter email addresses (comma separated):');
    if (!emails) return;
    
    const emailArray = emails.split(',').map(email => email.trim());
    
    fetch('{{ route("admin.invoices.bulk") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_ids: selectedOrders,
            action: 'email',
            email_addresses: emailArray
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('error', 'Error: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Error sending invoices: ' + error.message);
    });
}

// Get selected orders
function getSelectedOrders() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => parseInt(checkbox.value));
}

// Modern alert function using Bootstrap 5 toasts or fallback
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show alert-toast position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.empty-state {
    padding: 2rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.table th {
    border-top: none;
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.875rem;
    vertical-align: middle;
}

.table td {
    vertical-align: middle;
}

.btn-group {
    position: relative;
}

.btn-group .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
}

.btn-group .dropdown-menu.show {
    display: block;
}

.btn-group .dropdown-menu:not(.show) {
    display: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 6px 20px;
    clear: both;
    font-weight: 400;
    color: #212529;
    text-align: inherit;
    text-decoration: none;
    white-space: nowrap;
    background-color: transparent;
    border: 0;
    font-size: 0.875rem;
}

.dropdown-item:hover,
.dropdown-item:focus {
    color: #16181b;
    background-color: #f8f9fa;
    text-decoration: none;
}

.dropdown-item i {
    width: 16px;
    margin-right: 0.5rem;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
}

.alert-toast {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.text-end {
    text-align: right !important;
}

/* Bootstrap 5 compatibility */
.text-right {
    text-align: right !important;
}

.me-2 {
    margin-right: 0.5rem !important;
}

.ms-2 {
    margin-left: 0.5rem !important;
}

/* Ensure dropdown toggle works */
.dropdown-toggle::after {
    content: "";
    border-top: 0.3em solid;
    border-right: 0.3em solid transparent;
    border-bottom: 0;
    border-left: 0.3em solid transparent;
}

.dropdown-toggle:empty::after {
    margin-left: 0;
}

/* Loading states */
.btn[disabled] {
    opacity: 0.65;
    cursor: not-allowed;
}
</style>
@endpush
