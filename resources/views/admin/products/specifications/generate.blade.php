@extends('admin.layouts.app')

@section('title', 'Auto Generate Product Specifications')

@push('styles')
<style>
    .generation-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #f9f9f9;
    }
    .preview-section {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
    .generation-options {
        background: #e3f2fd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .progress-section {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Auto Generate Product Specifications</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.specifications.index') }}">Product Specifications</a></li>
                        <li class="breadcrumb-item active">Auto Generate</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>Generate Specifications</h5>
                    <p class="text-muted mb-0">Automatically generate specifications for products based on their category and name</p>
                </div>
                <div class="card-body">
                    <form id="generateForm" action="{{ route('admin.products.specifications.generate.post') }}" method="POST">
                        @csrf
                        
                        <!-- Product Selection -->
                        <div class="generation-card">
                            <h6>Product Selection</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Category Filter</label>
                                    <select name="category_id" class="form-select" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Brand Filter</label>
                                    <select name="brand_id" class="form-select" id="brandFilter">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="missing_only" value="1" id="missingOnly" checked>
                                        <label class="form-check-label" for="missingOnly">
                                            Only products with missing specifications
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-primary" onclick="loadProducts()">
                                        <i class="bx bx-refresh"></i> Load Products
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Generation Options -->
                        <div class="generation-options">
                            <h6>What to Generate</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="generate_features" value="1" id="generateFeatures" checked>
                                        <label class="form-check-label" for="generateFeatures">
                                            <strong>Product Features</strong><br>
                                            <small class="text-muted">Key selling points and benefits</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="generate_specifications" value="1" id="generateSpecs" checked>
                                        <label class="form-check-label" for="generateSpecs">
                                            <strong>Technical Specifications</strong><br>
                                            <small class="text-muted">Detailed technical details</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="generate_warranty" value="1" id="generateWarranty" checked>
                                        <label class="form-check-label" for="generateWarranty">
                                            <strong>Warranty Information</strong><br>
                                            <small class="text-muted">Standard warranty periods</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product List -->
                        <div class="generation-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Products to Process (<span id="productCount">0</span>)</h6>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectAllProducts()">Select All</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="selectNone()">Select None</button>
                                </div>
                            </div>
                            
                            <div id="productList" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-center text-muted">
                                    <i class="bx bx-package" style="font-size: 48px;"></i>
                                    <p>Click "Load Products" to see available products</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="progress-section" id="progressSection">
                            <h6>Generation Progress</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                            </div>
                            <div id="progressText">Preparing...</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="previewGeneration()">
                                <i class="bx bx-show"></i> Preview
                            </button>
                            <button type="submit" class="btn btn-success" id="generateBtn">
                                <i class="bx bx-magic-wand"></i> Generate Specifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Generation Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5>Generation Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1" id="totalProducts">0</h4>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1" id="missingSpecs">0</h4>
                                <small class="text-muted">Missing Specs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generation Preview -->
            <div class="card">
                <div class="card-header">
                    <h5>What Will Be Generated</h5>
                </div>
                <div class="card-body">
                    <div class="preview-section" id="generationPreview">
                        <h6>Sample Features:</h6>
                        <ul>
                            <li>High-quality construction</li>
                            <li>Durable materials</li>
                            <li>Easy to use</li>
                            <li>Compact design</li>
                        </ul>
                        
                        <h6>Sample Specifications:</h6>
                        <ul>
                            <li>Material: Based on category</li>
                            <li>Weight: Estimated</li>
                            <li>Dimensions: Standard sizes</li>
                            <li>Origin: Bangladesh (default)</li>
                        </ul>
                        
                        <h6>Warranty:</h6>
                        <ul>
                            <li>Electronics: 1 Year</li>
                            <li>Clothing: 30 Days</li>
                            <li>Others: 90 Days</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5>Recent Generations</h5>
                </div>
                <div class="card-body">
                    <div class="text-muted text-center">
                        <i class="bx bx-history"></i>
                        <p>Recent generation history will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generation Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="startGeneration()">Start Generation</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productData = [];

$(document).ready(function() {
    loadProducts();
});

function loadProducts() {
    const categoryId = $('#categoryFilter').val();
    const brandId = $('#brandFilter').val();
    const missingOnly = $('#missingOnly').is(':checked');
    
    // Show loading
    $('#productList').html('<div class="text-center"><div class="spinner-border"></div><p>Loading products...</p></div>');
    
    // Simulate API call (replace with actual endpoint)
    setTimeout(() => {
        // Mock data - replace with actual API call
        productData = [
            {id: 1, name: 'Sample Product 1', category: 'Electronics', missingSpecs: ['features', 'specifications']},
            {id: 2, name: 'Sample Product 2', category: 'Clothing', missingSpecs: ['warranty', 'material']},
            {id: 3, name: 'Sample Product 3', category: 'Home & Garden', missingSpecs: ['features']}
        ];
        
        renderProductList();
        updateStatistics();
    }, 1000);
}

function renderProductList() {
    if (productData.length === 0) {
        $('#productList').html('<div class="text-center text-muted"><i class="bx bx-package" style="font-size: 48px;"></i><p>No products found with missing specifications</p></div>');
        return;
    }
    
    let html = '';
    productData.forEach(product => {
        html += `
            <div class="form-check mb-2 p-2 border rounded">
                <input class="form-check-input product-checkbox" type="checkbox" value="${product.id}" id="product${product.id}" checked>
                <label class="form-check-label w-100" for="product${product.id}">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${product.name}</strong><br>
                            <small class="text-muted">${product.category}</small>
                        </div>
                        <div>
                            ${product.missingSpecs.map(spec => `<span class="badge bg-warning me-1">${spec}</span>`).join('')}
                        </div>
                    </div>
                </label>
            </div>
        `;
    });
    
    $('#productList').html(html);
    $('#productCount').text(productData.length);
}

function updateStatistics() {
    $('#totalProducts').text(productData.length);
    $('#missingSpecs').text(productData.reduce((sum, product) => sum + product.missingSpecs.length, 0));
}

function selectAllProducts() {
    $('.product-checkbox').prop('checked', true);
}

function selectNone() {
    $('.product-checkbox').prop('checked', false);
}

function previewGeneration() {
    const selectedProducts = $('.product-checkbox:checked').length;
    const generateFeatures = $('#generateFeatures').is(':checked');
    const generateSpecs = $('#generateSpecs').is(':checked');
    const generateWarranty = $('#generateWarranty').is(':checked');
    
    if (selectedProducts === 0) {
        alert('Please select at least one product');
        return;
    }
    
    let previewHtml = `
        <h6>Generation Summary:</h6>
        <ul>
            <li><strong>Products to process:</strong> ${selectedProducts}</li>
            <li><strong>Generate Features:</strong> ${generateFeatures ? 'Yes' : 'No'}</li>
            <li><strong>Generate Specifications:</strong> ${generateSpecs ? 'Yes' : 'No'}</li>
            <li><strong>Generate Warranty:</strong> ${generateWarranty ? 'Yes' : 'No'}</li>
        </ul>
        
        <h6>Estimated Time:</h6>
        <p>Approximately ${Math.ceil(selectedProducts / 10)} minutes</p>
        
        <div class="alert alert-info">
            <i class="bx bx-info-circle"></i>
            This will only update products with missing specifications. Existing data will not be overwritten.
        </div>
    `;
    
    $('#previewContent').html(previewHtml);
    $('#previewModal').modal('show');
}

function startGeneration() {
    $('#previewModal').modal('hide');
    $('#generateForm').submit();
}

// Handle form submission with progress
$('#generateForm').submit(function(e) {
    e.preventDefault();
    
    const selectedProducts = [];
    $('.product-checkbox:checked').each(function() {
        selectedProducts.push($(this).val());
    });
    
    if (selectedProducts.length === 0) {
        alert('Please select at least one product');
        return;
    }
    
    // Show progress
    $('#progressSection').show();
    $('#generateBtn').prop('disabled', true);
    
    // Add selected products to form data
    const formData = new FormData(this);
    selectedProducts.forEach(id => {
        formData.append('products[]', id);
    });
    
    // Submit with progress tracking
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#progressBar').css('width', '100%');
            $('#progressText').text('Generation completed successfully!');
            
            setTimeout(() => {
                window.location.href = '{{ route("admin.products.specifications.index") }}';
            }, 2000);
        } else {
            alert('Error: ' + (data.message || 'Generation failed'));
            $('#generateBtn').prop('disabled', false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during generation');
        $('#generateBtn').prop('disabled', false);
    });
});

// Simulate progress updates
function updateProgress(percent, text) {
    $('#progressBar').css('width', percent + '%');
    $('#progressText').text(text);
}
</script>
@endpush
