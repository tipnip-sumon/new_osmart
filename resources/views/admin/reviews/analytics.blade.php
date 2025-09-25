@extends('admin.layouts.app')

@section('title', 'Reviews Analytics')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reviews Analytics</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                                <li class="breadcrumb-item active">Analytics</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Total Reviews</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-primary fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $analytics['total_reviews'] }}">{{ $analytics['total_reviews'] }}</span>
                                    </h4>
                                    <span class="badge bg-primary-subtle text-primary">All Time</span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-message-square-dots text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">This Month</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $analytics['reviews_this_month'] }}">{{ $analytics['reviews_this_month'] }}</span>
                                    </h4>
                                    <span class="badge bg-success-subtle text-success">{{ date('F Y') }}</span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-calendar text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">This Week</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-info fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $analytics['reviews_this_week'] }}">{{ $analytics['reviews_this_week'] }}</span>
                                    </h4>
                                    <span class="badge bg-info-subtle text-info">Past 7 Days</span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="bx bx-trending-up text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted mb-0">Today</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-warning fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="{{ $analytics['reviews_today'] }}">{{ $analytics['reviews_today'] }}</span>
                                    </h4>
                                    <span class="badge bg-warning-subtle text-warning">{{ date('M d') }}</span>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-time text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Average Rating -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Average Rating</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-4">
                                    <h2 class="fw-bold text-primary">{{ number_format($analytics['average_rating'], 1) }}</h2>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($analytics['average_rating']))
                                                <i class="ri-star-fill text-warning fs-20"></i>
                                            @else
                                                <i class="ri-star-line text-muted fs-20"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted">out of 5 stars</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Distribution -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Rating Distribution</h4>
                        </div>
                        <div class="card-body">
                            @php $totalRated = array_sum($analytics['rating_distribution']->toArray()); @endphp
                            @for($i = 5; $i >= 1; $i--)
                                @php 
                                    $count = $analytics['rating_distribution'][$i] ?? 0;
                                    $percentage = $totalRated > 0 ? round(($count / $totalRated) * 100) : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-light text-dark">{{ $i }} ⭐</span>
                                    </div>
                                    <div class="flex-grow-1 mx-3">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="text-muted">{{ $count }} ({{ $percentage }}%)</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Approval Status -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Approval Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="text-success">{{ $analytics['approval_distribution']['approved'] }}</h5>
                                        <p class="text-muted mb-0">Approved</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h5 class="text-warning">{{ $analytics['approval_distribution']['pending'] }}</h5>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                </div>
                            </div>
                            
                            @php $totalReviews = $analytics['approval_distribution']['approved'] + $analytics['approval_distribution']['pending']; @endphp
                            @if($totalReviews > 0)
                            <div class="mt-3">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ round(($analytics['approval_distribution']['approved'] / $totalReviews) * 100) }}%"></div>
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ round(($analytics['approval_distribution']['pending'] / $totalReviews) * 100) }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-success">{{ round(($analytics['approval_distribution']['approved'] / $totalReviews) * 100) }}% Approved</small>
                                    <small class="text-warning">{{ round(($analytics['approval_distribution']['pending'] / $totalReviews) * 100) }}% Pending</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quick Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.reviews.pending') }}" class="btn btn-warning">
                                    <i class="ri-time-line me-2"></i>
                                    Review Pending ({{ $analytics['approval_distribution']['pending'] }})
                                </a>
                                <a href="{{ route('admin.reviews.featured') }}" class="btn btn-info">
                                    <i class="ri-star-line me-2"></i>
                                    View Featured Reviews
                                </a>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary">
                                    <i class="ri-list-check me-2"></i>
                                    Manage All Reviews
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Top Rated Products</h4>
                        </div>
                        <div class="card-body">
                            @forelse($analytics['top_rated_products'] as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded p-1 me-3">
                                    <img src="{{ $product->image ?? '/assets/images/default-product.png' }}" 
                                         alt="" class="img-fluid d-block rounded">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->name ?? 'Unknown Product', 40) }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success-subtle text-success me-2">
                                            {{ number_format($product->reviews_avg_rating, 1) }} ⭐
                                        </span>
                                        <small class="text-muted">{{ $product->reviews_count ?? 0 }} reviews</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No highly rated products yet.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Most Reviewed Products</h4>
                        </div>
                        <div class="card-body">
                            @forelse($analytics['most_reviewed_products'] as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded p-1 me-3">
                                    <img src="{{ $product->image ?? '/assets/images/default-product.png' }}" 
                                         alt="" class="img-fluid d-block rounded">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($product->name ?? 'Unknown Product', 40) }}</h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary-subtle text-primary me-2">
                                            {{ $product->reviews_count }} reviews
                                        </span>
                                        @if($product->reviews_avg_rating)
                                        <small class="text-muted">{{ number_format($product->reviews_avg_rating, 1) }} ⭐ avg</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-3">
                                <p class="text-muted mb-0">No reviewed products yet.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
