@extends('admin.layouts.app')

@section('title', 'Export Products')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Export Products</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Export</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Export Products
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="exportForm" method="GET" action="{{ route('admin.products.export-download') }}">
                            <!-- Export Format -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Export Format</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv" checked>
                                                <label class="form-check-label" for="formatCsv">
                                                    <i class="ri-file-text-line me-2"></i>CSV Format (.csv)
                                                </label>
                                                <div class="text-muted small">Comma-separated values, universally compatible</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel">
                                                <label class="form-check-label" for="formatExcel">
                                                    <i class="ri-file-excel-line me-2"></i>Excel Format (.xls)
                                                </label>
                                                <div class="text-muted small">Excel-compatible format with formatting</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Export Filters -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Filters (Optional)</label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="">All Categories</option>
                                        @if($categories && $categories->count() > 0)
                                            @foreach($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">No categories available</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="out-of-stock">Out of Stock</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Search</label>
                                    <input type="text" class="form-control" name="search" placeholder="Product name...">
                                </div>
                            </div>

                            <!-- Export Options -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Export Options</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="include_images" id="includeImages" value="1">
                                                <label class="form-check-label" for="includeImages">
                                                    Include Image URLs
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="include_variants" id="includeVariants" value="1">
                                                <label class="form-check-label" for="includeVariants">
                                                    Include Product Variants
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Export Statistics -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="ri-information-line me-2"></i>Export Information</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Total Products:</strong><br>
                                                <span class="fs-4 text-primary">{{ $totalProducts }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Active Products:</strong><br>
                                                <span class="fs-4 text-success">{{ $activeProducts }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Inactive Products:</strong><br>
                                                <span class="fs-4 text-danger">{{ $inactiveProducts }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Out of Stock:</strong><br>
                                                <span class="fs-4 text-warning">{{ $outOfStockProducts }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Export Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-download-line me-2"></i>Export Products
                                        </button>
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                            <i class="ri-arrow-left-line me-2"></i>Back to Products
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('exportForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Exporting...';
        submitBtn.disabled = true;

        // Re-enable button after 3 seconds (assuming download starts)
        setTimeout(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }, 3000);
    });
});
</script>
@endsection
