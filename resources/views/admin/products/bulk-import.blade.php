@extends('admin.layouts.app')

@section('title', 'Bulk Import Products')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Bulk Import Products</h1>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bulk Import</li>
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
                            Import Products from File
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Instructions -->
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading"><i class="ri-information-line me-2"></i>Import Instructions</h6>
                            <ul class="mb-0">
                                <li>Supported file formats: CSV, Excel (.xlsx, .xls)</li>
                                <li>Maximum file size: 10MB</li>
                                <li>Required columns: name, price, category, stock_quantity</li>
                                <li>Optional columns: description, sale_price, is_active, is_featured</li>
                                <li>Download the sample template below to ensure proper formatting</li>
                            </ul>
                        </div>

                        <!-- Sample Template Download -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Sample Template</label>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.export-download') }}?format=csv&sample=1" class="btn btn-outline-primary">
                                        <i class="ri-download-line me-2"></i>Download CSV Template
                                    </a>
                                    <a href="{{ route('admin.products.export-download') }}?format=excel&sample=1" class="btn btn-outline-success">
                                        <i class="ri-download-line me-2"></i>Download Excel Template
                                    </a>
                                </div>
                                <small class="text-muted">Download a sample template with the correct column structure</small>
                            </div>
                        </div>

                        <!-- Import Form -->
                        <form method="POST" action="{{ route('admin.products.bulk-import.process') }}" enctype="multipart/form-data" id="importForm">
                            @csrf
                            
                            <!-- File Upload -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Select Import File <span class="text-danger">*</span></label>
                                    <input type="file" 
                                           class="form-control @error('import_file') is-invalid @enderror" 
                                           name="import_file" 
                                           id="import_file"
                                           accept=".csv,.xlsx,.xls"
                                           required>
                                    @error('import_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Choose a CSV or Excel file containing product data</small>
                                </div>
                            </div>

                            <!-- Import Options -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Import Options</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="update_existing" id="updateExisting" value="1">
                                                <label class="form-check-label" for="updateExisting">
                                                    Update Existing Products
                                                </label>
                                                <div class="text-muted small">Update products if they already exist (match by name)</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="skip_errors" id="skipErrors" value="1" checked>
                                                <label class="form-check-label" for="skipErrors">
                                                    Skip Invalid Rows
                                                </label>
                                                <div class="text-muted small">Continue processing even if some rows have errors</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Preview (will be populated by JavaScript) -->
                            <div class="row mb-4" id="filePreview" style="display: none;">
                                <div class="col-md-12">
                                    <label class="form-label">File Preview</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="previewTable">
                                            <!-- Will be populated by JavaScript -->
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Actions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="importBtn">
                                            <i class="ri-upload-line me-2"></i>Import Products
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
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('import_file');
    const importBtn = document.getElementById('importBtn');
    const originalBtnText = importBtn.innerHTML;

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Show basic file info
            console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
        }
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        const file = fileInput.files[0];
        
        if (!file) {
            e.preventDefault();
            alert('Please select a file to import');
            return;
        }

        // Show loading state
        importBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Importing...';
        importBtn.disabled = true;

        // Re-enable button after 10 seconds (in case of error)
        setTimeout(() => {
            importBtn.innerHTML = originalBtnText;
            importBtn.disabled = false;
        }, 10000);
    });
});
</script>
@endsection
