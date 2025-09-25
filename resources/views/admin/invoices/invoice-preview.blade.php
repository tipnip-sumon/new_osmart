@extends('admin.layouts.app')

@section('title', 'Invoice Preview - INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoice Preview - INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.invoices.professional', $order->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> View Professional PDF
                        </a>
                        <a href="{{ route('admin.invoices.professional.download', $order->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="{{ route('admin.invoices.print', $order->id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <button type="button" class="btn btn-warning btn-sm" onclick="emailInvoice({{ $order->id }})">
                            <i class="fas fa-envelope"></i> Email
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Invoice Preview Content -->
                    <div class="invoice-preview" id="invoice-content">
                        <div class="invoice-header bg-light p-4 border-bottom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="company-info">
                                        <h3 class="text-primary mb-2">{{ config('app.name', 'MultiVendor Marketplace') }}</h3>
                                        <p class="mb-1">789 Business Ave, Suite 100</p>
                                        <p class="mb-1">Business City, BC 12345</p>
                                        <p class="mb-1">Phone: +1 (555) 987-6543</p>
                                        <p class="mb-0">Email: invoices@multivendor.com</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="invoice-details">
                                        <h2 class="text-primary mb-3">INVOICE</h2>
                                        <table class="table table-borderless table-sm w-auto ms-auto">
                                            <tr>
                                                <td class="text-end fw-bold">Invoice #:</td>
                                                <td>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end fw-bold">Date:</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end fw-bold">Due Date:</td>
                                                <td>{{ $order->created_at->addDays(30)->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end fw-bold">Status:</td>
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
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-body p-4">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Bill To:</h5>
                                    <div class="customer-info">
                                        <p class="mb-1 fw-bold">{{ $order->customer->name ?? 'Guest Customer' }}</p>
                                        <p class="mb-1">{{ $order->customer->email ?? 'N/A' }}</p>
                                        @if($order->customer->phone ?? false)
                                            <p class="mb-1">{{ $order->customer->phone }}</p>
                                        @endif
                                        @if($order->shipping_address)
                                            @php
                                                $address = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                            @endphp
                                            @if($address)
                                                @if(isset($address['street']))
                                                    <p class="mb-1">{{ $address['street'] }}</p>
                                                @endif
                                                @if(isset($address['street2']) && $address['street2'])
                                                    <p class="mb-1">{{ $address['street2'] }}</p>
                                                @endif
                                                @if(isset($address['city']) || isset($address['state']) || isset($address['zip']))
                                                    <p class="mb-1">
                                                        {{ $address['city'] ?? '' }}
                                                        @if(isset($address['state']) && $address['state'])
                                                            , {{ $address['state'] }}
                                                        @endif
                                                        {{ $address['zip'] ?? '' }}
                                                    </p>
                                                @endif
                                                @if(isset($address['country']) && $address['country'])
                                                    <p class="mb-0">{{ $address['country'] }}</p>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Payment Information:</h5>
                                    <div class="payment-info">
                                        <p class="mb-1"><strong>Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                                        <p class="mb-1"><strong>Currency:</strong> BDT (Tk)</p>
                                        @if($order->vendor)
                                            <p class="mb-1"><strong>Vendor:</strong> {{ $order->vendor->name ?? 'N/A' }}</p>
                                        @endif
                                        <p class="mb-0"><strong>Order #:</strong> {{ $order->order_number ?? $order->id }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Items -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Item Description</th>
                                            <th class="text-center" width="100">Qty</th>
                                            <th class="text-end" width="120">Unit Price</th>
                                            <th class="text-end" width="120">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->items as $item)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                                    @if($item->product->short_description ?? false)
                                                        <br><small class="text-muted">{{ $item->product->short_description }}</small>
                                                    @endif
                                                    @if($item->product->sku ?? false)
                                                        <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">Tk {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">Tk {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Invoice Totals -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="notes">
                                        <h6>Notes:</h6>
                                        <p class="text-muted">Thank you for your business! We appreciate your trust in our marketplace.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="invoice-totals">
                                        <table class="table table-borderless w-auto ms-auto">
                                            <tr>
                                                <td class="text-end fw-bold">Subtotal:</td>
                                                <td class="text-end">Tk {{ number_format($order->subtotal ?? ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount), 2) }}</td>
                                            </tr>
                                            @if($order->discount_amount > 0)
                                            <tr>
                                                <td class="text-end fw-bold">Discount:</td>
                                                <td class="text-end text-success">-Tk {{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($order->tax_amount > 0)
                                            <tr>
                                                <td class="text-end fw-bold">Tax:</td>
                                                <td class="text-end">Tk {{ number_format($order->tax_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($order->shipping_amount > 0)
                                            <tr>
                                                <td class="text-end fw-bold">Shipping:</td>
                                                <td class="text-end">Tk {{ number_format($order->shipping_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="border-top">
                                                <td class="text-end fw-bold fs-5">Total:</td>
                                                <td class="text-end fw-bold fs-5 text-primary">Tk {{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invoice-footer bg-light p-4 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Terms & Conditions:</h6>
                                    <p class="small text-muted mb-0">
                                        Payment is due within 30 days of invoice date. Late payments may incur additional fees. 
                                        Please retain this invoice for your records.
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <h6>Contact Information:</h6>
                                    <p class="small text-muted mb-0">
                                        For questions about this invoice, please contact our billing department at 
                                        <a href="mailto:billing@multivendor.com">billing@multivendor.com</a> or call +1 (555) 987-6543.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ $order->customer->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (Optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3" 
                                  placeholder="Add a personal message...">Hello {{ $order->customer->name ?? 'Customer' }},

Please find attached your invoice for order #{{ $order->order_number ?? $order->id }}.

Thank you for your business!

Best regards,
{{ config('app.name') }} Team</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Email invoice function
function emailInvoice(orderId) {
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
        emailModal.show();
    } else if (typeof $ !== 'undefined') {
        $('#emailModal').modal('show');
    }
}

// Email form submission
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    submitBtn.disabled = true;
    
    fetch(`{{ url('admin/invoices') }}/{{ $order->id }}/email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: email,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const emailModal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
                if (emailModal) emailModal.hide();
            } else if (typeof $ !== 'undefined') {
                $('#emailModal').modal('hide');
            }
            
            showAlert('success', 'Invoice sent successfully to ' + email);
        } else {
            showAlert('error', 'Error: ' + data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Error sending invoice: ' + error.message);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Alert function
function showAlert(type, message) {
    const existingAlerts = document.querySelectorAll('.alert-toast');
    existingAlerts.forEach(alert => alert.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show alert-toast position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Print function
function printInvoice() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>
.invoice-preview {
    background: white;
    min-height: 600px;
}

.company-info h3 {
    font-weight: 700;
}

.invoice-details h2 {
    font-weight: 700;
    letter-spacing: 2px;
}

.table-borderless td {
    border: none !important;
    padding: 0.25rem 0.5rem;
}

.invoice-totals .table td {
    padding: 0.5rem;
    font-size: 0.95rem;
}

.invoice-totals .table .border-top td {
    border-top: 2px solid #dee2e6 !important;
    padding-top: 1rem;
}

.notes {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 4px solid #0d6efd;
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

@media print {
    .card-header,
    .btn-group,
    .modal {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .invoice-preview {
        padding: 0 !important;
    }
}

.fw-bold {
    font-weight: 600 !important;
}

.fs-5 {
    font-size: 1.25rem !important;
}
</style>
@endpush
