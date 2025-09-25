@extends('admin.layouts.app')

@section('title', 'Edit Invoice - INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Edit Invoice - INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.invoices.show', $order->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View Invoice
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Invoices
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.invoices.update', $order->id) }}" method="POST" id="editInvoiceForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Customer Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Customer Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                                            <select class="form-select" id="customer_id" name="customer_id" required>
                                                <option value="">Choose a customer...</option>
                                                @foreach(\App\Models\User::where('role', 'customer')->get() as $customer)
                                                    <option value="{{ $customer->id }}" {{ ($order->customer_id == $customer->id) ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_email" class="form-label">Customer Email</label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ $order->customer->email ?? '' }}" placeholder="Customer email">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">Customer Phone</label>
                                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ $order->customer->phone ?? '' }}" placeholder="Customer phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Invoice Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ $order->created_at->format('Y-m-d') }}" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $order->created_at->addDays(30)->format('Y-m-d') }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Payment Method</label>
                                            <select class="form-select" id="payment_method" name="payment_method">
                                                <option value="cash" {{ ($order->payment_method == 'cash') ? 'selected' : '' }}>Cash</option>
                                                <option value="card" {{ ($order->payment_method == 'card') ? 'selected' : '' }}>Credit/Debit Card</option>
                                                <option value="bank_transfer" {{ ($order->payment_method == 'bank_transfer') ? 'selected' : '' }}>Bank Transfer</option>
                                                <option value="bkash" {{ ($order->payment_method == 'bkash') ? 'selected' : '' }}>bKash</option>
                                                <option value="nagad" {{ ($order->payment_method == 'nagad') ? 'selected' : '' }}>Nagad</option>
                                                <option value="rocket" {{ ($order->payment_method == 'rocket') ? 'selected' : '' }}>Rocket</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="payment_status" class="form-label">Payment Status</label>
                                            <select class="form-select" id="payment_status" name="payment_status">
                                                <option value="pending" {{ ($order->payment_status == 'pending') ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ ($order->payment_status == 'paid') ? 'selected' : '' }}>Paid</option>
                                                <option value="failed" {{ ($order->payment_status == 'failed') ? 'selected' : '' }}>Failed</option>
                                                <option value="refunded" {{ ($order->payment_status == 'refunded') ? 'selected' : '' }}>Refunded</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="vendor_id" class="form-label">Vendor</label>
                                            <select class="form-select" id="vendor_id" name="vendor_id">
                                                <option value="">No vendor</option>
                                                @foreach(\App\Models\User::where('role', 'vendor')->get() as $vendor)
                                                    <option value="{{ $vendor->id }}" {{ ($order->vendor_id == $vendor->id) ? 'selected' : '' }}>
                                                        {{ $vendor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Shipping Address</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $address = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                    $address = $address ?? [];
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_street" class="form-label">Street Address</label>
                                            <input type="text" class="form-control" id="shipping_street" name="shipping_address[street]" value="{{ $address['street'] ?? '' }}" placeholder="Enter street address">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_street2" class="form-label">Street Address 2</label>
                                            <input type="text" class="form-control" id="shipping_street2" name="shipping_address[street2]" value="{{ $address['street2'] ?? '' }}" placeholder="Apartment, suite, etc.">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="shipping_city" name="shipping_address[city]" value="{{ $address['city'] ?? '' }}" placeholder="Enter city">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_state" class="form-label">State/Province</label>
                                            <input type="text" class="form-control" id="shipping_state" name="shipping_address[state]" value="{{ $address['state'] ?? '' }}" placeholder="Enter state or province">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_zip" class="form-label">ZIP/Postal Code</label>
                                            <input type="text" class="form-control" id="shipping_zip" name="shipping_address[zip]" value="{{ $address['zip'] ?? '' }}" placeholder="Enter ZIP or postal code">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_country" class="form-label">Country</label>
                                            <input type="text" class="form-control" id="shipping_country" name="shipping_address[country]" value="{{ $address['country'] ?? 'Bangladesh' }}" placeholder="Enter country">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Invoice Items</h5>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addInvoiceItem()">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="invoiceItemsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 40%">Product/Service</th>
                                                <th style="width: 15%">Quantity</th>
                                                <th style="width: 20%">Unit Price (Tk)</th>
                                                <th style="width: 20%">Total (Tk)</th>
                                                <th style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invoiceItemsBody">
                                            @foreach($order->items as $index => $item)
                                            <tr id="item-{{ $index + 1 }}">
                                                <td>
                                                    <select class="form-select" name="items[{{ $index + 1 }}][product_id]" onchange="updateProductDetails({{ $index + 1 }})">
                                                        <option value="">Select product...</option>
                                                        @foreach(\App\Models\Product::take(50)->get() as $product)
                                                            <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}" {{ ($item->product_id == $product->id) ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control mt-2" name="items[{{ $index + 1 }}][description]" value="{{ $item->product->short_description ?? '' }}" placeholder="Item description (optional)">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control text-center" name="items[{{ $index + 1 }}][quantity]" value="{{ $item->quantity }}" min="1" onchange="calculateItemTotal({{ $index + 1 }})" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control text-end" name="items[{{ $index + 1 }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01" onchange="calculateItemTotal({{ $index + 1 }})" required>
                                                </td>
                                                <td class="text-end">
                                                    <span class="item-total" id="item-total-{{ $index + 1 }}">{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeInvoiceItem({{ $index + 1 }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                <td class="text-end"><strong>Tk <span id="subtotal">{{ number_format($order->subtotal ?? ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount), 2) }}</span></strong></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Tax (%):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="tax_percentage" name="tax_percentage" value="{{ $order->tax_amount > 0 ? round(($order->tax_amount / ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount)) * 100, 2) : 0 }}" min="0" max="100" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Shipping (Tk):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="shipping_amount" name="shipping_amount" value="{{ $order->shipping_amount }}" min="0" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Discount (Tk):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="discount_amount" name="discount_amount" value="{{ $order->discount_amount }}" min="0" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-dark">
                                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                                <td class="text-end"><strong>Tk <span id="totalAmount">{{ number_format($order->total_amount, 2) }}</span></strong></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Additional Notes</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes or terms...">{{ $order->notes ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.invoices.show', $order->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Update Invoice
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs for calculated values -->
                        <input type="hidden" id="subtotal_amount" name="subtotal_amount" value="{{ $order->subtotal ?? ($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount) }}">
                        <input type="hidden" id="tax_amount" name="tax_amount" value="{{ $order->tax_amount }}">
                        <input type="hidden" id="total_amount" name="total_amount" value="{{ $order->total_amount }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCounter = {{ count($order->items) }};

// Add invoice item
function addInvoiceItem() {
    itemCounter++;
    const row = `
        <tr id="item-${itemCounter}">
            <td>
                <select class="form-select" name="items[${itemCounter}][product_id]" onchange="updateProductDetails(${itemCounter})">
                    <option value="">Select product...</option>
                    @foreach(\App\Models\Product::take(50)->get() as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="text" class="form-control mt-2" name="items[${itemCounter}][description]" placeholder="Item description (optional)">
            </td>
            <td>
                <input type="number" class="form-control text-center" name="items[${itemCounter}][quantity]" value="1" min="1" onchange="calculateItemTotal(${itemCounter})" required>
            </td>
            <td>
                <input type="number" class="form-control text-end" name="items[${itemCounter}][unit_price]" value="0" min="0" step="0.01" onchange="calculateItemTotal(${itemCounter})" required>
            </td>
            <td class="text-end">
                <span class="item-total" id="item-total-${itemCounter}">0.00</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeInvoiceItem(${itemCounter})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    document.getElementById('invoiceItemsBody').insertAdjacentHTML('beforeend', row);
}

// Remove invoice item
function removeInvoiceItem(itemId) {
    document.getElementById(`item-${itemId}`).remove();
    calculateTotals();
}

// Update product details when product is selected
function updateProductDetails(itemId) {
    const select = document.querySelector(`select[name="items[${itemId}][product_id]"]`);
    const priceInput = document.querySelector(`input[name="items[${itemId}][unit_price]"]`);
    
    if (select.selectedOptions[0]) {
        const price = select.selectedOptions[0].getAttribute('data-price') || 0;
        priceInput.value = parseFloat(price).toFixed(2);
        calculateItemTotal(itemId);
    }
}

// Calculate individual item total
function calculateItemTotal(itemId) {
    const quantity = parseFloat(document.querySelector(`input[name="items[${itemId}][quantity]"]`).value) || 0;
    const unitPrice = parseFloat(document.querySelector(`input[name="items[${itemId}][unit_price]"]`).value) || 0;
    const total = quantity * unitPrice;
    
    document.getElementById(`item-total-${itemId}`).textContent = total.toFixed(2);
    calculateTotals();
}

// Calculate all totals
function calculateTotals() {
    let subtotal = 0;
    
    // Calculate subtotal from all items
    document.querySelectorAll('.item-total').forEach(function(element) {
        subtotal += parseFloat(element.textContent) || 0;
    });
    
    const taxPercentage = parseFloat(document.getElementById('tax_percentage').value) || 0;
    const shippingAmount = parseFloat(document.getElementById('shipping_amount').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
    
    const taxAmount = (subtotal * taxPercentage) / 100;
    const totalAmount = subtotal + taxAmount + shippingAmount - discountAmount;
    
    // Update display
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
    
    // Update hidden inputs
    document.getElementById('subtotal_amount').value = subtotal.toFixed(2);
    document.getElementById('tax_amount').value = taxAmount.toFixed(2);
    document.getElementById('total_amount').value = totalAmount.toFixed(2);
}

// Customer selection change
document.getElementById('customer_id').addEventListener('change', function() {
    const customerId = this.value;
    if (customerId) {
        // Find customer email from the selected option
        const selectedOption = this.selectedOptions[0];
        const email = selectedOption.textContent.match(/\(([^)]+)\)/);
        if (email) {
            document.getElementById('customer_email').value = email[1];
        }
    } else {
        document.getElementById('customer_email').value = '';
    }
});

// Calculate totals on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});

// Form validation
document.getElementById('editInvoiceForm').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('#invoiceItemsBody tr');
    if (items.length === 0) {
        e.preventDefault();
        alert('Please add at least one item to the invoice.');
        return false;
    }
    
    const customerId = document.getElementById('customer_id').value;
    if (!customerId) {
        e.preventDefault();
        alert('Please select a customer.');
        return false;
    }
});
</script>
@endpush
