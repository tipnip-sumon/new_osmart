@extends('admin.layouts.app')

@section('title', 'Featured Reviews')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Featured Reviews</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                                <li class="breadcrumb-item active">Featured</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="ri-information-line me-2"></i>
                        <strong>Note:</strong> Currently showing high-rated reviews (4+ stars) as featured reviews. 
                        The featured system can be enhanced with a dedicated column in the future.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>

            <!-- Featured Reviews -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-4 align-items-center">
                                <div class="col-sm">
                                    <div>
                                        <h5 class="card-title mb-0">High-Rated Reviews (4+ Stars)</h5>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex flex-wrap align-items-start gap-2">
                                        <!-- Search -->
                                        <form method="GET" class="d-flex gap-2">
                                            <div class="search-box">
                                                <input type="text" name="search" class="form-control" 
                                                       placeholder="Search reviews..." value="{{ request('search') }}">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-search-line align-bottom me-1"></i> Search
                                            </button>
                                            
                                            @if(request('search'))
                                                <a href="{{ route('admin.reviews.featured') }}" class="btn btn-soft-secondary">
                                                    <i class="ri-refresh-line align-bottom me-1"></i> Clear
                                                </a>
                                            @endif
                                        </form>
                                        
                                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-soft-info">
                                            <i class="ri-arrow-left-line align-bottom me-1"></i> All Reviews
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Rating</th>
                                            <th scope="col">Review</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reviews as $review)
                                        <tr>
                                            <td>#{{ $review->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded p-1 me-2">
                                                        <img src="{{ $review->user->avatar ?? '/assets/images/default-avatar.png' }}" 
                                                             alt="" class="img-fluid d-block rounded">
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-13 mb-0">{{ $review->user->name ?? 'Unknown User' }}</h5>
                                                        <p class="fs-12 mb-0 text-muted">{{ $review->user->email ?? 'No email' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-light rounded p-1 me-2">
                                                        <img src="{{ $review->product->image ?? '/assets/images/default-product.png' }}" 
                                                             alt="" class="img-fluid d-block rounded">
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-13 mb-0">{{ Str::limit($review->product->name ?? 'Unknown Product', 30) }}</h5>
                                                        <p class="fs-12 mb-0 text-muted">SKU: {{ $review->product->sku ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success-subtle text-success fs-12 me-1">
                                                        {{ $review->rating }}/5
                                                    </span>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="ri-star-fill text-warning fs-12"></i>
                                                            @else
                                                                <i class="ri-star-line text-muted fs-12"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ Str::limit($review->comment, 50) }}</p>
                                                @if($review->title)
                                                    <small class="text-muted">{{ Str::limit($review->title, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$review->is_approved)
                                                    <span class="badge bg-warning-subtle text-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success">Approved</span>
                                                @endif
                                            </td>
                                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="#" onclick="viewReview({{ $review->id }})">
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                                                        </a></li>
                                                        
                                                        @if(!$review->is_approved)
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $review->id }}, 'approved')">
                                                            <i class="ri-check-fill align-bottom me-2 text-success"></i> Approve
                                                        </a></li>
                                                        @endif
                                                        
                                                        @if($review->is_approved)
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus({{ $review->id }}, 'pending')">
                                                            <i class="ri-close-fill align-bottom me-2 text-warning"></i> Mark as Pending
                                                        </a></li>
                                                        @endif
                                                        
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteReview({{ $review->id }})">
                                                            <i class="ri-delete-bin-fill align-bottom me-2"></i> Delete
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ri-star-line fs-1 text-muted"></i>
                                                    <h5 class="mt-3">No High-Rated Reviews Found</h5>
                                                    <p>No reviews with 4+ stars match your current filters.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($reviews->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews
                                </div>
                                {{ $reviews->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Details Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewModalBody">
                <!-- Review details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Update review status
function updateStatus(reviewId, status) {
    if (confirm(`Are you sure you want to ${status} this review?`)) {
        fetch(`/admin/reviews/${reviewId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating review status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating review status');
        });
    }
}

// Delete review
function deleteReview(reviewId) {
    if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
        fetch(`/admin/reviews/${reviewId}`, {
            method: 'DELETE',
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
                alert('Error deleting review');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting review');
        });
    }
}

// View review details
function viewReview(reviewId) {
    // This would load review details in the modal
    // Implementation depends on your specific requirements
    $('#reviewModal').modal('show');
}
</script>
@endpush
