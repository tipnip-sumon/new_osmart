@extends('admin.layouts.app')

@section('title', 'Invoice Details - INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Invoice Details - INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-pdf"></i> Generate PDF
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.invoices.professional', $order->id) }}" target="_blank">
                                    <i class="fas fa-crown"></i> Professional Invoice
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.invoices.invoice-template', $order->id) }}" target="_blank">
                                    <i class="fas fa-file-alt"></i> Template View
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.invoices.print', $order->id) }}" target="_blank">
                                    <i class="fas fa-print"></i> Print Version
                                </a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.invoices.professional.download', $order->id) }}">
                                    <i class="fas fa-file-pdf"></i> Professional PDF
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.invoices.download', $order->id) }}">
                                    <i class="fas fa-file-alt"></i> Standard PDF
                                </a></li>
                            </ul>
                        </div>
                        <a href="{{ route('admin.invoices.edit', $order->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Invoice Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Invoice Status</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Payment Status:</span>
                                        @switch($order->payment_status)
                                            @case('paid')
                                                <span class="badge bg-success fs-6">Paid</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning fs-6">Pending</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger fs-6">Failed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary fs-6">{{ ucfirst($order->payment_status) }}</span>
                                        @endswitch
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span>Order Status:</span>
                                        @switch($order->status)
                                            @case('completed')
                                                <span class="badge bg-success fs-6">Completed</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info fs-6">Processing</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge bg-primary fs-6">Shipped</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger fs-6">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary fs-6">{{ ucfirst($order->status) }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Quick Actions</h5>
                                    <div class="d-grid gap-2">
                                        @if($order->payment_status !== 'paid')
                                            <button type="button" class="btn btn-success btn-sm" onclick="markAsPaid()">
                                                <i class="fas fa-check-circle"></i> Mark as Paid
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#emailInvoiceModal">
                                            <i class="fas fa-envelope"></i> Send via Email
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="duplicateInvoice()">
                                            <i class="fas fa-copy"></i> Duplicate Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Invoice Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Invoice #:</strong></td>
                                            <td>INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order #:</strong></td>
                                            <td>{{ $order->order_number ?? $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date:</strong></td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Due Date:</strong></td>
                                            <td>{{ $order->created_at->addDays(30)->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Method:</strong></td>
                                            <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Customer Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $order->customer->name ?? 'Guest Customer' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $order->customer->email ?? 'N/A' }}</td>
                                        </tr>
                                        @if($order->customer->phone ?? false)
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $order->customer->phone }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Customer ID:</strong></td>
                                            <td>{{ $order->customer->id ?? 'N/A' }}</td>
                                        </tr>
                                        @if($order->customer)
                                        <tr>
                                            <td><strong>Joined:</strong></td>
                                            <td>{{ $order->customer->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Vendor Information</h5>
                                </div>
                                <div class="card-body">
                                    @if($order->vendor)
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Vendor:</strong></td>
                                                <td>{{ $order->vendor->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $order->vendor->email }}</td>
                                            </tr>
                                            @if($order->vendor->phone ?? false)
                                            <tr>
                                                <td><strong>Phone:</strong></td>
                                                <td>{{ $order->vendor->phone }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td><strong>Vendor ID:</strong></td>
                                                <td>{{ $order->vendor->id }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <p class="text-muted">No vendor assigned to this order</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if($order->shipping_address)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Shipping Address</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $address = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                    @endphp
                                    @if($address)
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if(isset($address['street']))
                                                    <p class="mb-1"><strong>Street:</strong> {{ $address['street'] }}</p>
                                                @endif
                                                @if(isset($address['street2']) && $address['street2'])
                                                    <p class="mb-1"><strong>Street 2:</strong> {{ $address['street2'] }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if(isset($address['city']))
                                                    <p class="mb-1"><strong>City:</strong> {{ $address['city'] }}</p>
                                                @endif
                                                @if(isset($address['state']))
                                                    <p class="mb-1"><strong>State:</strong> {{ $address['state'] }}</p>
                                                @endif
                                                @if(isset($address['zip']))
                                                    <p class="mb-1"><strong>ZIP:</strong> {{ $address['zip'] }}</p>
                                                @endif
                                                @if(isset($address['country']))
                                                    <p class="mb-1"><strong>Country:</strong> {{ $address['country'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Order Items -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Order Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-end">Unit Price</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($order->items as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                                            @if($item->product->short_description ?? false)
                                                                <br><small class="text-muted">{{ $item->product->short_description }}</small>
                                                            @endif
                                                            @if($item->product->sku ?? false)
                                                                <br><code class="small">SKU: {{ $item->product->sku }}</code>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">Tk {{ number_format($item->unit_price, 2) }}</td>
                                                    <td class="text-end">Tk {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No items found for this order</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>Subtotal:</td>
                                                <td class="text-end">Tk {{ number_format($order->subtotal ?? ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount), 2) }}</td>
                                            </tr>
                                            @if($order->discount_amount > 0)
                                            <tr>
                                                <td>Discount:</td>
                                                <td class="text-end text-success">-Tk {{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($order->tax_amount > 0)
                                            <tr>
                                                <td>Tax:</td>
                                                <td class="text-end">Tk {{ number_format($order->tax_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            @if($order->shipping_amount > 0)
                                            <tr>
                                                <td>Shipping:</td>
                                                <td class="text-end">Tk {{ number_format($order->shipping_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="table-dark">
                                                <td><strong>Total Amount:</strong></td>
                                                <td class="text-end"><strong>Tk {{ number_format($order->total_amount, 2) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Invoice Modal -->
<div class="modal fade" id="emailInvoiceModal" tabindex="-1" aria-labelledby="emailInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailInvoiceModalLabel">Email Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.invoices.email', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email_to" class="form-label">Send to Email</label>
                        <input type="email" class="form-control" id="email_to" name="email" value="{{ $order->customer->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="email_subject" name="subject" value="Invoice INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} from {{ config('app.name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email_message" class="form-label">Message</label>
                        <textarea class="form-control" id="email_message" name="message" rows="4">Dear {{ $order->customer->name ?? 'Customer' }},

Please find attached your invoice for recent purchase.

Thank you for your business!

Best regards,
{{ config('app.name') }} Team</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markAsPaid() {
    if (confirm('Are you sure you want to mark this invoice as paid?')) {
        fetch(`{{ route('admin.invoices.mark-paid', $order->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating payment status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating payment status');
        });
    }
}

function duplicateInvoice() {
    if (confirm('Are you sure you want to create a duplicate of this invoice?')) {
        window.location.href = `{{ route('admin.invoices.duplicate', $order->id) }}`;
    }
}
</script>
@endpush
