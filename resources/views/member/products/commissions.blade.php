@extends('member.layouts.app')

@section('title', 'Product Commission                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">৳{{ number_format($stats['pending_commission'], 0) }}</h3>
                            <p class="stats-label mb-0">Pending Commission</p>
                        </div>

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="page-header-container">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <h4 class="page-title mb-1">
                        <i class="bx bx-line-chart me-2 text-primary"></i>
                        Product Commissions
                    </h4>
                    <nav aria-label="breadcrumb" class="d-none d-sm-block">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('member.products.index') }}">Products</a></li>
                            <li class="breadcrumb-item active">Commissions</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-12 text-end text-sm-start text-md-end mt-3 mt-md-0">
                    <a href="{{ route('member.products.index') }}" class="btn btn-outline-primary btn-responsive">
                        <i class="bx bx-arrow-back me-1"></i> <span class="d-none d-sm-inline">Back to</span> Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success-light text-success me-3">
                            <i class="bx bx-money"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">৳{{ number_format($stats['total_commission'], 0) }}</h3>
                            <p class="stats-label mb-0">Total Commission</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info-light text-info me-3">
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">৳{{ number_format($stats['this_month_commission'], 0) }}</h3>
                            <p class="stats-label mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning-light text-warning me-3">
                            <i class="bx bx-clock"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">${{ number_format($stats['pending_commission'], 2) }}</h3>
                            <p class="stats-label mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary-light text-primary me-3">
                            <i class="bx bx-check-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="stats-number mb-0">${{ number_format($stats['paid_commission'], 2) }}</h3>
                            <p class="stats-label mb-0">Paid</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission History -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pb-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="card-title mb-0 text-center text-md-start">
                    <i class="bx bx-list-ul me-2"></i>
                    Commission History
                </h5>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                    <select class="form-select form-select-sm flex-fill" style="min-width: 120px;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select class="form-select form-select-sm flex-fill" style="min-width: 120px;">
                        <option value="15">15 per page</option>
                        <option value="30">30 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($commissions instanceof \Illuminate\Pagination\LengthAwarePaginator && $commissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commissions as $commission)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $productImage = '';
                                                if ($commission->product_image && $commission->product_image !== 'products/product1.jpg') {
                                                    $productImage = str_starts_with($commission->product_image, 'http') ? $commission->product_image : asset('storage/' . $commission->product_image);
                                                } else {
                                                    $productImage = asset('assets/img/product/default.png');
                                                }
                                            @endphp
                                            <img src="{{ $productImage }}" 
                                                 alt="{{ $commission->product_name }}" 
                                                 class="product-thumb me-3"
                                                 onerror="this.src='{{ asset('assets/img/product/default.png') }}'">
                                            <div>
                                                <h6 class="mb-0">{{ Str::limit($commission->product_name, 40) }}</h6>
                                                <small class="text-muted">ID: #{{ $commission->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">
                                            ৳{{ number_format($commission->amount, 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($commission->status ?? 'pending') {
                                                'paid' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}-light text-{{ $statusClass }}">
                                            {{ ucfirst($commission->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ \Carbon\Carbon::parse($commission->created_at)->format('M d, Y') }}
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($commission->created_at)->format('h:i A') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('member.products.show', $commission->product_slug ?? $commission->product_id) }}" 
                                               class="btn btn-outline-primary btn-sm" 
                                               title="View Product">
                                                <i class="bx bx-eye"></i>
                                            </a>
                                            <button class="btn btn-outline-secondary btn-sm view-details" 
                                                    data-commission-id="{{ $commission->id }}"
                                                    title="View Details">
                                                <i class="bx bx-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($commissions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $commissions->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="bx bx-line-chart empty-state-icon"></i>
                        <h5 class="empty-state-title">No Commissions Yet</h5>
                        <p class="empty-state-text">You haven't earned any product commissions yet. Start sharing products and making sales to earn commissions!</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('member.products.index') }}" class="btn btn-primary">
                                <i class="bx bx-package me-1"></i>
                                Browse Products
                            </a>
                            <a href="{{ route('member.products.shared') }}" class="btn btn-outline-primary">
                                <i class="bx bx-share-alt me-1"></i>
                                Share Products
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Commission Details Modal -->
<div class="modal fade" id="commissionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="commissionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.page-header-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: -1.5rem -1.5rem 2rem -1.5rem;
    padding: 2rem 1.5rem;
}

.page-header {
    color: white;
}

.page-header .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.page-header .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.page-header .breadcrumb-item.active {
    color: white;
}

/* Responsive Header Styles */
@media (max-width: 767.98px) {
    .page-header-container {
        margin: -1rem -1rem 1.5rem -1rem;
        padding: 1.5rem 1rem;
    }
    
    .page-title {
        font-size: 1.25rem;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    
    .page-title i {
        font-size: 1.1rem;
    }
    
    .btn-responsive {
        width: 100%;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 575.98px) {
    .page-header-container {
        margin: -0.75rem -0.75rem 1rem -0.75rem;
        padding: 1rem 0.75rem;
    }
    
    .page-title {
        font-size: 1.1rem;
    }
    
    .btn-responsive {
        font-size: 0.85rem;
        padding: 0.45rem 0.9rem;
    }
}

.stats-card {
    transition: transform 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Responsive Stats Cards */
@media (max-width: 767.98px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .stats-icon {
        width: 45px;
        height: 45px;
        font-size: 1.3rem;
    }
    
    .stats-number {
        font-size: 1.5rem !important;
    }
    
    .stats-label {
        font-size: 0.8rem !important;
    }
}

@media (max-width: 575.98px) {
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .stats-number {
        font-size: 1.3rem !important;
    }
}

.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}

.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-info-light {
    background-color: rgba(13, 202, 240, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

.stats-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
}

.stats-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

/* Responsive Table */
@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .product-thumb {
        width: 40px;
        height: 40px;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.75rem 0.5rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.4rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 575.98px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .product-thumb {
        width: 35px;
        height: 35px;
    }
    
    .table > :not(caption) > * > * {
        padding: 0.5rem 0.3rem;
    }
    
    .table td .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .table td .product-thumb {
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #adb5bd;
    margin-bottom: 1.5rem;
}

/* Responsive Empty State */
@media (max-width: 575.98px) {
    .empty-state {
        max-width: 300px;
        padding: 0 1rem;
    }
    
    .empty-state-icon {
        font-size: 3rem;
    }
    
    .empty-state-title {
        font-size: 1.1rem;
    }
    
    .empty-state-text {
        font-size: 0.9rem;
    }
    
    .empty-state .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .empty-state .btn {
        width: 100%;
    }
}

.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1);
}

.text-success {
    color: #198754 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

/* Responsive Card Header */
@media (max-width: 767.98px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-header .form-select {
        width: 100% !important;
    }
    
    .card-title {
        text-align: center;
        font-size: 1.1rem;
    }
}

/* Responsive Modal */
@media (max-width: 575.98px) {
    .modal-dialog {
        margin: 1rem 0.5rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // View commission details
    $('.view-details').click(function() {
        const commissionId = $(this).data('commission-id');
        
        // For now, show a simple details modal
        // You can enhance this with actual AJAX call to get detailed commission info
        $('#commissionDetailsContent').html(`
            <div class="text-center">
                <i class="bx bx-info-circle text-primary" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Commission Details</h5>
                <p class="text-muted">Commission ID: #${commissionId}</p>
                <p>Detailed commission information would be loaded here via AJAX.</p>
            </div>
        `);
        
        $('#commissionDetailsModal').modal('show');
    });
    
    // Filter functionality
    $('.form-select').change(function() {
        // Implement filtering logic here
        console.log('Filter changed:', $(this).val());
    });
});
</script>
@endpush
