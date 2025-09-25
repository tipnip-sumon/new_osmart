@extends('layouts.admin')

@section('title', 'Invoice Preview')

@section('styles')
<style>
    .invoice-preview-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    
    .preview-header {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .preview-actions {
        display: flex;
        gap: 15px;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .invoice-settings {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .setting-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .setting-group label {
        font-weight: 600;
        color: #555;
        margin: 0;
    }
    
    .setting-group select {
        padding: 6px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: white;
    }
    
    .invoice-frame {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
    }
    
    .frame-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: between;
    }
    
    .frame-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    
    .frame-tools {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }
    
    .frame-tool {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        cursor: pointer;
    }
    
    .tool-close {
        background: #ff5f56;
    }
    
    .tool-minimize {
        background: #ffbd2e;
    }
    
    .tool-expand {
        background: #27ca3f;
    }
    
    .invoice-content {
        padding: 0;
        height: 80vh;
        overflow-y: auto;
    }
    
    .invoice-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
    }
    
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .preview-sidebar {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: fit-content;
    }
    
    .sidebar-section {
        margin-bottom: 25px;
    }
    
    .sidebar-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .quick-action {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        text-decoration: none;
        color: #555;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .quick-action:hover {
        background: #e9ecef;
        color: #333;
        text-decoration: none;
        transform: translateX(5px);
    }
    
    .quick-action-icon {
        margin-right: 10px;
        font-size: 16px;
    }
    
    .invoice-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 12px;
        opacity: 0.9;
    }
    
    .responsive-toggle {
        display: flex;
        background: #f8f9fa;
        border-radius: 25px;
        padding: 4px;
        border: 1px solid #e9ecef;
    }
    
    .toggle-option {
        padding: 8px 16px;
        border-radius: 20px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 12px;
        font-weight: 600;
    }
    
    .toggle-option.active {
        background: #667eea;
        color: white;
    }
    
    @media (max-width: 768px) {
        .preview-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .invoice-settings {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .invoice-content {
            height: 60vh;
        }
    }
</style>
@endsection

@section('content')
<div class="invoice-preview-container">
    <div class="row">
        <!-- Main Preview Area -->
        <div class="col-lg-9">
            <!-- Preview Header -->
            <div class="preview-header">
                <div class="preview-actions">
                    <div class="action-buttons">
                        <button class="action-btn btn-primary" onclick="generatePDF()">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                        <button class="action-btn btn-success" onclick="printInvoice()">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button class="action-btn btn-warning" onclick="emailInvoice()">
                            <i class="fas fa-envelope"></i> Email
                        </button>
                        <button class="action-btn btn-info" onclick="customizeInvoice()">
                            <i class="fas fa-palette"></i> Customize
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="action-btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                    
                    <div class="invoice-settings">
                        <div class="setting-group">
                            <label>Template:</label>
                            <select id="templateSelect" onchange="changeTemplate()">
                                <option value="default">Default</option>
                                <option value="modern">Modern</option>
                                <option value="classic">Classic</option>
                                <option value="minimal">Minimal</option>
                            </select>
                        </div>
                        
                        <div class="responsive-toggle">
                            <button class="toggle-option active" onclick="toggleView('desktop')">
                                <i class="fas fa-desktop"></i> Desktop
                            </button>
                            <button class="toggle-option" onclick="toggleView('tablet')">
                                <i class="fas fa-tablet-alt"></i> Tablet
                            </button>
                            <button class="toggle-option" onclick="toggleView('mobile')">
                                <i class="fas fa-mobile-alt"></i> Mobile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Frame -->
            <div class="invoice-frame" id="invoiceFrame">
                <div class="frame-header">
                    <h3 class="frame-title">📄 Invoice Preview - {{ $order['invoice_number'] }}</h3>
                    <div class="frame-tools">
                        <div class="frame-tool tool-close"></div>
                        <div class="frame-tool tool-minimize"></div>
                        <div class="frame-tool tool-expand" onclick="toggleFullscreen()"></div>
                    </div>
                </div>
                <div class="invoice-content" id="invoiceContent">
                    <div class="loading-overlay" id="loadingOverlay">
                        <div class="loading-spinner"></div>
                    </div>
                    <iframe 
                        id="invoiceIframe" 
                        class="invoice-iframe" 
                        src="data:text/html;charset=utf-8,{{ urlencode($invoiceHtml) }}"
                        onload="hideLoading()">
                    </iframe>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="preview-sidebar">
                <!-- Quick Actions -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h4>
                    <div class="quick-actions">
                        <a href="#" class="quick-action" onclick="duplicateInvoice()">
                            <i class="fas fa-copy quick-action-icon"></i>
                            Duplicate Invoice
                        </a>
                        <a href="#" class="quick-action" onclick="editOrder()">
                            <i class="fas fa-edit quick-action-icon"></i>
                            Edit Order
                        </a>
                        <a href="#" class="quick-action" onclick="viewOrder()">
                            <i class="fas fa-eye quick-action-icon"></i>
                            View Order Details
                        </a>
                        <a href="#" class="quick-action" onclick="refundOrder()">
                            <i class="fas fa-undo quick-action-icon"></i>
                            Process Refund
                        </a>
                    </div>
                </div>
                
                <!-- Invoice Stats -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">
                        <i class="fas fa-chart-bar"></i> Invoice Statistics
                    </h4>
                    <div class="invoice-stats">
                        <div class="stat-card">
                            <div class="stat-value">{{ $order['currency_symbol'] }}{{ number_format($order['total'], 2) }}</div>
                            <div class="stat-label">Total Amount</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $order['pv_points'] }}</div>
                            <div class="stat-label">PV Points</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ count($order['items']) }}</div>
                            <div class="stat-label">Items</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $order['status'] }}</div>
                            <div class="stat-label">Status</div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Information -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">
                        <i class="fas fa-info-circle"></i> Order Information
                    </h4>
                    <div class="order-info">
                        <div class="info-row">
                            <strong>Customer:</strong> {{ $order['customer'] }}
                        </div>
                        <div class="info-row">
                            <strong>Order Date:</strong> {{ date('M d, Y', strtotime($order['order_date'])) }}
                        </div>
                        <div class="info-row">
                            <strong>Payment Method:</strong> {{ $order['payment_method'] }}
                        </div>
                        <div class="info-row">
                            <strong>Payment Status:</strong> 
                            <span class="badge badge-{{ strtolower($order['payment_status']) == 'paid' ? 'success' : 'warning' }}">
                                {{ $order['payment_status'] }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="sidebar-section">
                    <h4 class="sidebar-title">
                        <i class="fas fa-history"></i> Recent Activity
                    </h4>
                    <div class="activity-list">
                        <div class="activity-item">
                            <small class="text-muted">{{ date('M d, Y h:i A') }}</small><br>
                            Invoice generated and ready for download
                        </div>
                        <div class="activity-item">
                            <small class="text-muted">{{ date('M d, Y h:i A', strtotime($order['order_date'])) }}</small><br>
                            Order placed and payment {{ strtolower($order['payment_status']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Invoice</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="emailForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>To Email:</label>
                        <input type="email" class="form-control" id="emailTo" value="{{ $order['customer_email'] }}" required>
                    </div>
                    <div class="form-group">
                        <label>Subject:</label>
                        <input type="text" class="form-control" id="emailSubject" value="Invoice {{ $order['invoice_number'] }} from MultiVendor Marketplace" required>
                    </div>
                    <div class="form-group">
                        <label>Message:</label>
                        <textarea class="form-control" id="emailMessage" rows="4">Dear {{ $order['customer'] }},

Please find attached your invoice {{ $order['invoice_number'] }} for your recent order.

Thank you for your business!

Best regards,
MultiVendor Marketplace Team</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentView = 'desktop';
    let currentTemplate = 'default';
    
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }
    
    function generatePDF() {
        showLoading();
        window.open(`{{ route('admin.invoices.download', ':id') }}`.replace(':id', '{{ $order["id"] }}'), '_blank');
        hideLoading();
    }
    
    function printInvoice() {
        showLoading();
        const printWindow = window.open(`{{ route('admin.invoices.print', ':id') }}`.replace(':id', '{{ $order["id"] }}'), '_blank');
        printWindow.onload = function() {
            hideLoading();
        };
    }
    
    function emailInvoice() {
        $('#emailModal').modal('show');
    }
    
    function customizeInvoice() {
        window.open(`{{ route('admin.invoices.customize', ':id') }}`.replace(':id', '{{ $order["id"] }}'), '_blank');
    }
    
    function changeTemplate() {
        const template = document.getElementById('templateSelect').value;
        currentTemplate = template;
        showLoading();
        
        // Simulate template change - in real implementation, this would reload the iframe with different template
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                icon: 'success',
                title: 'Template Changed!',
                text: `Switched to ${template} template`,
                timer: 2000,
                showConfirmButton: false
            });
        }, 1000);
    }
    
    function toggleView(view) {
        currentView = view;
        
        // Update active toggle
        document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        const iframe = document.getElementById('invoiceIframe');
        const frame = document.getElementById('invoiceFrame');
        
        // Adjust frame size based on view
        switch(view) {
            case 'desktop':
                frame.style.maxWidth = '100%';
                iframe.style.width = '100%';
                break;
            case 'tablet':
                frame.style.maxWidth = '768px';
                frame.style.margin = '0 auto';
                iframe.style.width = '100%';
                break;
            case 'mobile':
                frame.style.maxWidth = '375px';
                frame.style.margin = '0 auto';
                iframe.style.width = '100%';
                break;
        }
        
        Swal.fire({
            icon: 'info',
            title: 'View Changed!',
            text: `Switched to ${view} view`,
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    function toggleFullscreen() {
        const frame = document.getElementById('invoiceFrame');
        
        if (frame.classList.contains('fullscreen')) {
            frame.classList.remove('fullscreen');
            frame.style.position = 'relative';
            frame.style.top = 'auto';
            frame.style.left = 'auto';
            frame.style.width = 'auto';
            frame.style.height = 'auto';
            frame.style.zIndex = 'auto';
        } else {
            frame.classList.add('fullscreen');
            frame.style.position = 'fixed';
            frame.style.top = '0';
            frame.style.left = '0';
            frame.style.width = '100vw';
            frame.style.height = '100vh';
            frame.style.zIndex = '9999';
        }
    }
    
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }
    
    function duplicateInvoice() {
        Swal.fire({
            title: 'Duplicate Invoice?',
            text: 'This will create a new invoice with the same details.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Duplicate'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement duplication logic
                Swal.fire('Duplicated!', 'Invoice has been duplicated successfully.', 'success');
            }
        });
    }
    
    function editOrder() {
        window.open(`{{ route('admin.orders.edit', ':id') }}`.replace(':id', '{{ $order["id"] }}'), '_blank');
    }
    
    function viewOrder() {
        window.open(`{{ route('admin.orders.show', ':id') }}`.replace(':id', '{{ $order["id"] }}'), '_blank');
    }
    
    function refundOrder() {
        Swal.fire({
            title: 'Process Refund?',
            text: 'This will initiate a refund for this order.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Process Refund',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement refund logic
                Swal.fire('Refund Initiated!', 'The refund has been processed successfully.', 'success');
            }
        });
    }
    
    // Email Form Submission
    document.getElementById('emailForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            to: document.getElementById('emailTo').value,
            subject: document.getElementById('emailSubject').value,
            message: document.getElementById('emailMessage').value,
            invoice_id: '{{ $order["id"] }}'
        };
        
        // Show loading
        Swal.fire({
            title: 'Sending Email...',
            text: 'Please wait while we send your invoice.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate email sending
        setTimeout(() => {
            $('#emailModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Email Sent!',
                text: 'Invoice has been emailed successfully.',
                timer: 3000,
                showConfirmButton: false
            });
        }, 2000);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'p':
                    e.preventDefault();
                    printInvoice();
                    break;
                case 's':
                    e.preventDefault();
                    generatePDF();
                    break;
                case 'e':
                    e.preventDefault();
                    emailInvoice();
                    break;
            }
        }
    });
</script>
@endsection
