@extends('admin.layouts.app')

@section('title', 'Collection Details')

@push('styles')
<style>
    .collection-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .collection-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .collection-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        object-fit: cover;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .info-card h5 {
        color: #5a5c69;
        font-weight: 700;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fc;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fc;
    }

    .info-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 120px;
    }

    .info-value {
        color: #5a5c69;
        text-align: right;
        flex-grow: 1;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #1cc88a;
        color: white;
    }

    .status-inactive {
        background: #e74a3b;
        color: white;
    }

    .status-draft {
        background: #f6c23e;
        color: white;
    }

    .featured-badge {
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-buttons {
        gap: 10px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .product-card {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .product-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        background: #f8f9fc;
    }

    .product-info {
        padding: 15px;
    }

    .product-name {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.4;
    }

    .product-price {
        color: #1cc88a;
        font-weight: 700;
        font-size: 16px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .meta-preview {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .meta-title {
        color: #1a73e8;
        font-size: 18px;
        margin-bottom: 5px;
        text-decoration: none;
    }

    .meta-url {
        color: #1a73e8;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .meta-description {
        color: #5f6368;
        font-size: 14px;
        line-height: 1.4;
    }

    .seo-score {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .seo-good {
        background: #d4edda;
        color: #155724;
    }

    .seo-warning {
        background: #fff3cd;
        color: #856404;
    }

    .seo-poor {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Collection Details</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.collections.index') }}">Collections</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $collection->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Collection Header -->
    <div class="collection-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <h2 class="mb-0">{{ $collection->name }}</h2>
                    @if($collection->is_featured)
                        <span class="featured-badge">
                            <i class="bx bx-star me-1"></i>Featured
                        </span>
                    @endif
                </div>
                
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="status-badge status-{{ $collection->status }}">
                        {{ ucfirst($collection->status) }}
                    </span>
                    <span class="text-white-50">
                        <i class="bx bx-calendar me-1"></i>
                        Created {{ $collection->created_at->format('M d, Y') }}
                    </span>
                    <span class="text-white-50">
                        <i class="bx bx-edit me-1"></i>
                        Updated {{ $collection->updated_at->format('M d, Y') }}
                    </span>
                </div>
                
                @if($collection->description)
                    <p class="mb-0 text-white-75">{{ $collection->description }}</p>
                @endif
            </div>
            
            @if($collection->image)
                <div class="col-md-4 text-end">
                    <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="collection-image">
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex action-buttons">
            <a href="{{ route('admin.collections.edit', $collection) }}" class="btn btn-primary btn-action">
                <i class="bx bx-edit me-2"></i>Edit Collection
            </a>
            <button type="button" class="btn btn-outline-danger btn-action" onclick="deleteCollection()">
                <i class="bx bx-trash me-2"></i>Delete
            </button>
            <button type="button" class="btn btn-outline-secondary btn-action" onclick="toggleStatus()">
                <i class="bx {{ $collection->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }} me-2"></i>
                {{ $collection->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </div>
        
        <div class="d-flex action-buttons">
            <a href="{{ url('/collections/' . $collection->slug) }}" target="_blank" class="btn btn-outline-info btn-action">
                <i class="bx bx-external-link me-2"></i>View on Site
            </a>
            <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary btn-action">
                <i class="bx bx-arrow-back me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Collection Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="info-card">
                <h5><i class="bx bx-info-circle me-2 text-primary"></i>Basic Information</h5>
                
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $collection->name }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Slug:</span>
                    <span class="info-value">
                        <code>{{ $collection->slug }}</code>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Sort Order:</span>
                    <span class="info-value">{{ $collection->sort_order }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $collection->status }}">
                            {{ ucfirst($collection->status) }}
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Active:</span>
                    <span class="info-value">
                        <i class="bx {{ $collection->is_active ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }}"></i>
                        {{ $collection->is_active ? 'Yes' : 'No' }}
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Featured:</span>
                    <span class="info-value">
                        <i class="bx {{ $collection->is_featured ? 'bx-star text-warning' : 'bx-star-o text-muted' }}"></i>
                        {{ $collection->is_featured ? 'Yes' : 'No' }}
                    </span>
                </div>
                
                @if($collection->short_description)
                    <div class="info-item">
                        <span class="info-label">Short Description:</span>
                        <span class="info-value">{{ $collection->short_description }}</span>
                    </div>
                @endif
            </div>

            <!-- SEO Information -->
            <div class="info-card">
                <h5><i class="bx bx-search-alt me-2 text-success"></i>SEO Information</h5>
                
                @php
                    $seoScore = 0;
                    $maxScore = 4;
                    
                    if($collection->meta_title) $seoScore++;
                    if($collection->meta_description) $seoScore++;
                    if($collection->meta_keywords) $seoScore++;
                    if($collection->slug) $seoScore++;
                    
                    $scorePercentage = ($seoScore / $maxScore) * 100;
                    
                    if($scorePercentage >= 75) {
                        $scoreClass = 'seo-good';
                        $scoreText = 'Good';
                    } elseif($scorePercentage >= 50) {
                        $scoreClass = 'seo-warning';
                        $scoreText = 'Needs Improvement';
                    } else {
                        $scoreClass = 'seo-poor';
                        $scoreText = 'Poor';
                    }
                @endphp
                
                <div class="info-item">
                    <span class="info-label">SEO Score:</span>
                    <span class="info-value">
                        <span class="seo-score {{ $scoreClass }}">
                            <i class="bx bx-trending-up"></i>
                            {{ $scoreText }} ({{ $seoScore }}/{{ $maxScore }})
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Meta Title:</span>
                    <span class="info-value">{{ $collection->meta_title ?: 'Not set' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Meta Description:</span>
                    <span class="info-value">{{ $collection->meta_description ?: 'Not set' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Meta Keywords:</span>
                    <span class="info-value">{{ $collection->meta_keywords ?: 'Not set' }}</span>
                </div>
                
                @if($collection->meta_title || $collection->meta_description)
                    <div class="meta-preview">
                        <div class="meta-title">{{ $collection->meta_title ?: $collection->name }}</div>
                        <div class="meta-url">{{ url('/collections/' . $collection->slug) }}</div>
                        <div class="meta-description">{{ $collection->meta_description ?: $collection->description }}</div>
                    </div>
                @endif
            </div>

            <!-- Products in Collection -->
            <div class="info-card">
                <h5><i class="bx bx-package me-2 text-info"></i>Products in Collection ({{ $collection->products->count() }})</h5>
                
                @if($collection->products->count() > 0)
                    <div class="products-grid">
                        @foreach($collection->products as $product)
                            <div class="product-card">
                                @if($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image d-flex align-items-center justify-content-center">
                                        <i class="bx bx-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-decoration-line-through text-muted me-2">${{ number_format($product->price, 2) }}</span>
                                            ${{ number_format($product->sale_price, 2) }}
                                        @else
                                            ${{ number_format($product->price, 2) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bx bx-package"></i>
                        <h6 class="mt-3 mb-2">No Products Yet</h6>
                        <p class="text-muted">This collection doesn't have any products assigned to it yet.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-plus me-1"></i>Add Products
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="info-card">
                <h5><i class="bx bx-bar-chart me-2 text-warning"></i>Quick Stats</h5>
                
                <div class="info-item">
                    <span class="info-label">Total Products:</span>
                    <span class="info-value">
                        <strong>{{ $collection->products->count() }}</strong>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Active Products:</span>
                    <span class="info-value">
                        <strong>{{ $collection->products->where('is_active', true)->count() }}</strong>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Featured Products:</span>
                    <span class="info-value">
                        <strong>{{ $collection->products->where('is_featured', true)->count() }}</strong>
                    </span>
                </div>
                
                @php
                    $totalValue = $collection->products->sum('price');
                    $avgPrice = $collection->products->count() > 0 ? $collection->products->avg('price') : 0;
                @endphp
                
                <div class="info-item">
                    <span class="info-label">Total Value:</span>
                    <span class="info-value">
                        <strong>${{ number_format($totalValue, 2) }}</strong>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Avg. Price:</span>
                    <span class="info-value">
                        <strong>${{ number_format($avgPrice, 2) }}</strong>
                    </span>
                </div>
            </div>

            <!-- Collection Settings -->
            <div class="info-card">
                <h5><i class="bx bx-cog me-2 text-secondary"></i>Collection Settings</h5>
                
                <div class="info-item">
                    <span class="info-label">Show in Menu:</span>
                    <span class="info-value">
                        <i class="bx {{ $collection->show_in_menu ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }}"></i>
                        {{ $collection->show_in_menu ? 'Yes' : 'No' }}
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Show in Footer:</span>
                    <span class="info-value">
                        <i class="bx {{ $collection->show_in_footer ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }}"></i>
                        {{ $collection->show_in_footer ? 'Yes' : 'No' }}
                    </span>
                </div>
                
                @if($collection->commission_rate)
                    <div class="info-item">
                        <span class="info-label">Commission Rate:</span>
                        <span class="info-value">
                            <strong>{{ $collection->commission_rate }}%</strong>
                        </span>
                    </div>
                @endif
                
                @if($collection->color_code)
                    <div class="info-item">
                        <span class="info-label">Color Code:</span>
                        <span class="info-value">
                            <span style="display: inline-block; width: 20px; height: 20px; background: {{ $collection->color_code }}; border-radius: 50%; vertical-align: middle; margin-right: 8px;"></span>
                            {{ $collection->color_code }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="info-card">
                <h5><i class="bx bx-time me-2 text-info"></i>Timeline</h5>
                
                <div class="info-item">
                    <span class="info-label">Created:</span>
                    <span class="info-value">{{ $collection->created_at->format('M d, Y g:i A') }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value">{{ $collection->updated_at->format('M d, Y g:i A') }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Days Active:</span>
                    <span class="info-value">{{ $collection->created_at->diffInDays(now()) }} days</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the collection "<strong>{{ $collection->name }}</strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Collection</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCollection() {
    $('#deleteModal').modal('show');
}

function toggleStatus() {
    fetch(`{{ route('admin.collections.toggle-status', $collection) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the collection status.');
    });
}
</script>
@endpush
