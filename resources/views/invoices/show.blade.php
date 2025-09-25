@extends('layouts.app')

@section('title', 'Invoice - ' . $order->order_number)

@section('content')
<div class="container-fluid">
    <!-- Print Controls -->
    <div class="row no-print mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Orders
                </a>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i>Print Page
                    </button>
                    <a href="{{ route('invoice.view-pdf', $order->id) }}" class="btn btn-outline-primary me-2" target="_blank">
                        <i class="ti ti-file-pdf me-1"></i>View PDF
                    </a>
                    <a href="{{ route('invoice.pdf', $order->id) }}" class="btn btn-primary me-2">
                        <i class="ti ti-download me-1"></i>Download PDF
                    </a>
                    <a href="{{ route('invoice.download', $order->id) }}" class="btn btn-outline-info">
                        <i class="ti ti-code me-1"></i>Download HTML
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Content -->
    <div class="invoice-wrapper">
        @include('invoices.template', [
            'order' => $order,
            'shipping_address' => $shipping_address,
            'items' => $items
        ])
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .invoice-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    body {
        font-size: 12px;
    }
}

.invoice-wrapper {
    background: #fff;
    margin: 0 auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-focus for printing
    if (window.location.hash === '#print') {
        setTimeout(() => {
            window.print();
        }, 500);
    }
});
</script>
@endpush
