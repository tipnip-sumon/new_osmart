@extends('admin.layouts.app')

@section('title', 'Create Transaction Receipt')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Transaction Receipt</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.trans-receipts.index') }}">Transaction Receipts</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.trans-receipts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Transaction Receipt Information</h5>
                            <div class="flex-shrink-0">
                                <button type="submit" class="btn btn-primary">Create Receipt</button>
                                <a href="{{ route('admin.trans-receipts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Transaction Type <span class="text-danger">*</span></label>
                                    <select name="transaction_type" class="form-select @error('transaction_type') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        @foreach($transactionTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('transaction_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transaction_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Transaction ID <span class="text-danger">*</span></label>
                                    <input type="text" name="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" 
                                           value="{{ old('transaction_id') }}" placeholder="TXN_2025_001" required>
                                    @error('transaction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="amount" step="0.01" min="0.01" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               value="{{ old('amount') }}" placeholder="0.00" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                        @foreach($currencies as $code => $name)
                                            <option value="{{ $code }}" {{ old('currency', 'USD') == $code ? 'selected' : '' }}>
                                                {{ $code }} - {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Select Payment Method</option>
                                        @foreach($paymentMethods as $key => $method)
                                            <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
                                                {{ $method }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vendor/Customer Information -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Vendor</label>
                                    <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor['id'] }}" {{ old('vendor_id') == $vendor['id'] ? 'selected' : '' }}>
                                                {{ $vendor['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer</label>
                                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer['id'] }}" {{ old('customer_id') == $customer['id'] ? 'selected' : '' }}>
                                                {{ $customer['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Transaction Details -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Transaction Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="transaction_date" 
                                           class="form-control @error('transaction_date') is-invalid @enderror" 
                                           value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="datetime-local" name="due_date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" name="reference_number" 
                                           class="form-control @error('reference_number') is-invalid @enderror" 
                                           value="{{ old('reference_number') }}" placeholder="Will be auto-generated if empty">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Order ID</label>
                                    <input type="number" name="order_id" 
                                           class="form-control @error('order_id') is-invalid @enderror" 
                                           value="{{ old('order_id') }}" placeholder="Related order ID">
                                    @error('order_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Gateway Information -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gateway Transaction ID</label>
                                    <input type="text" name="gateway_transaction_id" 
                                           class="form-control @error('gateway_transaction_id') is-invalid @enderror" 
                                           value="{{ old('gateway_transaction_id') }}" placeholder="stripe_ch_1234567890">
                                    @error('gateway_transaction_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Gateway Response</label>
                                    <input type="text" name="gateway_response" 
                                           class="form-control @error('gateway_response') is-invalid @enderror" 
                                           value="{{ old('gateway_response') }}" placeholder="Payment successful">
                                    @error('gateway_response')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Transaction description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Attachments -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Receipt Attachment</label>
                                    <input type="file" name="receipt_attachment" 
                                           class="form-control @error('receipt_attachment') is-invalid @enderror" 
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Supported formats: PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('receipt_attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Invoice Attachment</label>
                                    <input type="file" name="invoice_attachment" 
                                           class="form-control @error('invoice_attachment') is-invalid @enderror" 
                                           accept=".pdf">
                                    <small class="text-muted">Supported formats: PDF (Max: 5MB)</small>
                                    @error('invoice_attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" rows="3" 
                                              class="form-control @error('notes') is-invalid @enderror" 
                                              placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line align-bottom me-1"></i> Create Receipt
                            </button>
                            <a href="{{ route('admin.trans-receipts.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-close-line align-bottom me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Transaction type change handler
    const transactionType = document.querySelector('select[name="transaction_type"]');
    const vendorField = document.querySelector('select[name="vendor_id"]').closest('.mb-3');
    const customerField = document.querySelector('select[name="customer_id"]').closest('.mb-3');
    
    transactionType.addEventListener('change', function() {
        const type = this.value;
        
        // Show/hide fields based on transaction type
        if (type === 'payout' || type === 'commission') {
            vendorField.style.display = 'block';
            customerField.style.display = 'none';
        } else if (type === 'payment' || type === 'refund') {
            vendorField.style.display = 'block';
            customerField.style.display = 'block';
        } else {
            vendorField.style.display = 'none';
            customerField.style.display = 'none';
        }
    });
    
    // Trigger on page load
    transactionType.dispatchEvent(new Event('change'));
});
</script>
@endpush
