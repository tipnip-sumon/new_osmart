@extends('admin.layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Create New Invoice</h4>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.invoices.store') }}" method="POST" id="createInvoiceForm">
                        @csrf
                        
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
                                                @forelse(\App\Models\User::where('role', 'customer')->get() as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                                @empty
                                                    <option value="">No customers available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_email" class="form-label">Customer Email</label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Will auto-fill when customer is selected">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">Customer Phone</label>
                                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Optional">
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
                                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Payment Method</label>
                                            <select class="form-select" id="payment_method" name="payment_method">
                                                <option value="cash">Cash</option>
                                                <option value="card">Credit/Debit Card</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="bkash">bKash</option>
                                                <option value="nagad">Nagad</option>
                                                <option value="rocket">Rocket</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="vendor_id" class="form-label">Vendor</label>
                                            <select class="form-select" id="vendor_id" name="vendor_id">
                                                <option value="">Select vendor (optional)</option>
                                                @forelse(\App\Models\User::where('role', 'vendor')->get() as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                @empty
                                                    <option value="">No vendors available</option>
                                                @endforelse
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_street" class="form-label">Street Address</label>
                                            <input type="text" class="form-control" id="shipping_street" name="shipping_address[street]" placeholder="Enter street address">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="shipping_city" name="shipping_address[city]" placeholder="Enter city">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_state" class="form-label">State/Province</label>
                                            <input type="text" class="form-control" id="shipping_state" name="shipping_address[state]" placeholder="Enter state or province">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_zip" class="form-label">ZIP/Postal Code</label>
                                            <input type="text" class="form-control" id="shipping_zip" name="shipping_address[zip]" placeholder="Enter ZIP or postal code">
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
                                            <!-- Items will be added dynamically -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                <td class="text-end"><strong>Tk <span id="subtotal">0.00</span></strong></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Tax (%):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="tax_percentage" name="tax_percentage" value="0" min="0" max="100" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Shipping (Tk):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="shipping_amount" name="shipping_amount" value="0" min="0" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Discount (Tk):</td>
                                                <td class="text-end">
                                                    <input type="number" class="form-control form-control-sm text-end" id="discount_amount" name="discount_amount" value="0" min="0" step="0.01" onchange="calculateTotals()">
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="table-dark">
                                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                                <td class="text-end"><strong>Tk <span id="totalAmount">0.00</span></strong></td>
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
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes or terms..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Create Invoice
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs for calculated values -->
                        <input type="hidden" id="subtotal_amount" name="subtotal_amount" value="0">
                        <input type="hidden" id="tax_amount" name="tax_amount" value="0">
                        <input type="hidden" id="total_amount" name="total_amount" value="0">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCounter = 0;

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

// Add first item on page load
document.addEventListener('DOMContentLoaded', function() {
    addInvoiceItem();
});

// Form validation
document.getElementById('createInvoiceForm').addEventListener('submit', function(e) {
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
