@extends('member.layouts.app')

@section('title', 'Link Sharing History')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Link Sharing History</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.link-sharing.dashboard') }}">Link Sharing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to Dashboard
            </a>
            <a href="{{ route('member.link-sharing.stats') }}" class="btn btn-info">
                <i class="bx bx-bar-chart-alt-2"></i> Performance Stats
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Filter & Search</div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('member.link-sharing.history') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Date Range</label>
                                <select name="date_range" class="form-control">
                                    <option value="">All Time</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="last_week" {{ request('date_range') == 'last_week' ? 'selected' : '' }}>Last Week</option>
                                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Platform</label>
                                <select name="platform" class="form-control">
                                    <option value="">All Platforms</option>
                                    <option value="facebook" {{ request('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="whatsapp" {{ request('platform') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="telegram" {{ request('platform') == 'telegram' ? 'selected' : '' }}>Telegram</option>
                                    <option value="twitter" {{ request('platform') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                    <option value="email" {{ request('platform') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="manual" {{ request('platform') == 'manual' ? 'selected' : '' }}>Manual/Copy</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Search Product</label>
                                <input type="text" name="search" class="form-control" placeholder="Product name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-search"></i> Filter
                                    </button>
                                    <a href="{{ route('member.link-sharing.history') }}" class="btn btn-secondary">
                                        <i class="bx bx-refresh"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-primary-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-share-alt fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">Total Shares</h6>
                            <h4 class="fw-semibold mb-0">{{ $shares->total() ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-mouse fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">Total Clicks</h6>
                            <h4 class="fw-semibold mb-0">{{ $shares->sum('clicks_count') ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-warning-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-money fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">Total Earned</h6>
                            <h4 class="fw-semibold mb-0">৳ {{ number_format($shares->sum('earnings_amount') ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card bg-info-gradient text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bx bx-trending-up fs-24"></i>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1 text-white-50">Avg Click Rate</h6>
                            <h4 class="fw-semibold mb-0">
                                @php
                                    $totalShares = $shares->count();
                                    $totalClicks = $shares->sum('clicks_count');
                                    $avgRate = $totalShares > 0 ? round(($totalClicks / $totalShares), 1) : 0;
                                @endphp
                                {{ $avgRate }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sharing History Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Your Sharing History</div>
                </div>
                <div class="card-body">
                    @if($shares->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Platform</th>
                                        <th>Share Date</th>
                                        <th>Clicks</th>
                                        <th>Unique Clicks</th>
                                        <th>Earnings</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shares as $share)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($share->product)
                                                    @php
                                                        // Dynamic image handling for link sharing history products
                                                        $legacyImageUrl = '';
                                                        
                                                        // First try images array
                                                        if (isset($share->product->images) && $share->product->images) {
                                                            $images = is_string($share->product->images) ? json_decode($share->product->images, true) : $share->product->images;
                                                            if (is_array($images) && !empty($images)) {
                                                                $image = $images[0]; // Get first image
                                                                
                                                                // Handle complex nested structure first
                                                                if (is_array($image) && isset($image['sizes']['medium']['storage_url'])) {
                                                                    // New complex structure - use medium size storage_url
                                                                    $legacyImageUrl = $image['sizes']['medium']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['sizes']['original']['storage_url'])) {
                                                                    // Fallback to original if medium not available
                                                                    $legacyImageUrl = $image['sizes']['original']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['sizes']['large']['storage_url'])) {
                                                                    // Fallback to large if original not available
                                                                    $legacyImageUrl = $image['sizes']['large']['storage_url'];
                                                                } elseif (is_array($image) && isset($image['urls']['medium'])) {
                                                                    // Legacy complex URL structure - use medium size
                                                                    $legacyImageUrl = $image['urls']['medium'];
                                                                } elseif (is_array($image) && isset($image['urls']['original'])) {
                                                                    // Legacy fallback to original if medium not available
                                                                    $legacyImageUrl = $image['urls']['original'];
                                                                } elseif (is_array($image) && isset($image['url']) && is_string($image['url'])) {
                                                                    $legacyImageUrl = $image['url'];
                                                                } elseif (is_array($image) && isset($image['path']) && is_string($image['path'])) {
                                                                    $legacyImageUrl = asset('storage/' . $image['path']);
                                                                } elseif (is_string($image)) {
                                                                    // Simple string path
                                                                    $legacyImageUrl = asset('storage/' . $image);
                                                                }
                                                            }
                                                        }
                                                        
                                                        // Fallback to image accessor
                                                        if (empty($legacyImageUrl)) {
                                                            $productImage = $share->product->image;
                                                            if ($productImage && $productImage !== 'products/product1.jpg') {
                                                                $legacyImageUrl = str_starts_with($productImage, 'http') ? $productImage : asset('storage/' . $productImage);
                                                            } else {
                                                                $legacyImageUrl = asset('assets/img/product/default.png'); // Default for link sharing
                                                            }
                                                        }
                                                    @endphp
                                                    <img src="{{ $legacyImageUrl }}" 
                                                         alt="{{ $share->product->name }}" 
                                                         class="avatar avatar-sm rounded me-2"
                                                         onerror="this.src='{{ asset('assets/img/product/default.png') }}'; this.onerror=null;">
                                                    <div>
                                                        <span class="fw-semibold">{{ Str::limit($share->product->name ?? 'Unknown Product', 30) }}</span>
                                                        <br><small class="text-muted">{{ $share->product_slug }}</small>
                                                    </div>
                                                @else
                                                    <img src="{{ asset('assets/img/product/default.png') }}" 
                                                         alt="Product Not Found" 
                                                         class="avatar avatar-sm rounded me-2">
                                                    <div>
                                                        <span class="fw-semibold text-warning">Product Not Found</span>
                                                        <br><small class="text-muted">{{ $share->product_slug }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $platformIcons = [
                                                    'facebook' => 'bxl-facebook text-primary',
                                                    'whatsapp' => 'bxl-whatsapp text-success',
                                                    'telegram' => 'bxl-telegram text-info',
                                                    'twitter' => 'bxl-twitter text-primary',
                                                    'email' => 'bx-envelope text-warning',
                                                    'manual' => 'bx-copy text-secondary'
                                                ];
                                                $icon = $platformIcons[$share->shared_platform] ?? 'bx-share-alt text-muted';
                                            @endphp
                                            <i class="bx {{ $icon }}"></i>
                                            {{ ucfirst($share->shared_platform) }}
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $share->share_date->format('M d, Y') }}</span>
                                            <br><small class="text-muted">{{ $share->share_date->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $share->clicks_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $share->unique_clicks_count }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">৳ {{ number_format($share->earnings_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($share->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info copy-link-btn" 
                                                        data-link="{{ $share->shared_url }}" 
                                                        title="Copy Link">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success share-again-btn" 
                                                        data-slug="{{ $share->product_slug }}" 
                                                        data-name="{{ $share->product->name ?? 'Product' }}"
                                                        title="Share Again">
                                                    <i class="bx bx-share-alt"></i>
                                                </button>
                                                @if($share->product)
                                                    <a href="{{ route('products.show', $share->product_slug) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="View Product">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">
                                    Showing {{ $shares->firstItem() }} to {{ $shares->lastItem() }} of {{ $shares->total() }} results
                                </span>
                            </div>
                            <div>
                                {{ $shares->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bx bx-history" style="font-size: 4rem; color: #ccc;"></i>
                            </div>
                            <h5>No Sharing History Found</h5>
                            <p class="text-muted">You haven't shared any products yet. Start sharing to build your history!</p>
                            <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-primary">
                                <i class="bx bx-share-alt"></i> Start Sharing Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Again Modal -->
<div class="modal fade" id="shareAgainModal" tabindex="-1" aria-labelledby="shareAgainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareAgainModalLabel">Share Product Again</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="shareAgainContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Modern clipboard copy function
    async function copyToClipboard(text) {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            } else {
                // Fallback for older browsers or non-secure contexts
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                const result = document.execCommand('copy');
                textArea.remove();
                return result;
            }
        } catch (err) {
            return false;
        }
    }

    // Show toast notification
    function showToast(message, type = 'success') {
        // Remove existing toasts
        $('.custom-toast').remove();
        
        const toastHtml = `
            <div class="position-fixed top-0 end-0 p-3 custom-toast" style="z-index: 9999;">
                <div class="toast show align-items-center text-white bg-${type} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(toastHtml);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            $('.custom-toast').fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }

    // Enhanced copy link functionality
    $('.copy-link-btn').on('click', async function() {
        const link = $(this).data('link');
        const button = $(this);
        const originalHtml = button.html();
        
        if (!link) {
            showToast('No link available to copy', 'danger');
            return;
        }
        
        // Show loading state
        button.html('<i class="bx bx-loader-alt bx-spin"></i>');
        button.prop('disabled', true);
        
        try {
            const success = await copyToClipboard(link);
            
            if (success) {
                button.html('<i class="bx bx-check"></i>');
                button.removeClass('btn-info').addClass('btn-success');
                showToast('Affiliate link copied to clipboard!', 'success');
            } else {
                throw new Error('Copy failed');
            }
        } catch (error) {
            button.html('<i class="bx bx-error"></i>');
            button.removeClass('btn-info').addClass('btn-danger');
            showToast('Failed to copy link. Please try again.', 'danger');
        }
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.html(originalHtml);
            button.removeClass('btn-success btn-danger').addClass('btn-info');
            button.prop('disabled', false);
        }, 2000);
    });
    
    // Enhanced share again functionality
    $('.share-again-btn').on('click', function() {
        
        const slug = $(this).data('slug');
        const name = $(this).data('name');
        const button = $(this);
        
        if (!slug) {
            console.error('No product slug found');
            showToast('Product information not available', 'danger');
            return;
        }
        
        // Show loading state on button
        const originalHtml = button.html();
        button.html('<i class="bx bx-loader-alt bx-spin"></i>');
        button.prop('disabled', true);
        
        shareProductAgain(slug, name).finally(() => {
            // Reset button
            setTimeout(() => {
                button.html(originalHtml);
                button.prop('disabled', false);
            }, 1000);
        });
    });
    
    // Enhanced share product again function
    async function shareProductAgain(slug, name) {
        
        // Get CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
        
        $('#shareAgainModalLabel').html(`
            <i class="bx bx-share-alt me-2"></i>Share Again: <span class="text-primary">${name}</span>
        `);
        
        $('#shareAgainContent').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="text-muted">Generating your affiliate link...</h5>
                <p class="text-muted small">Please wait while we create a new trackable link for you.</p>
            </div>
        `);
        
        $('#shareAgainModal').modal('show');
        
        const requestData = {
            product_slug: slug,
            platform: 'manual',
            _token: csrfToken
        };
        
        try {
            const response = await $.ajax({
                url: '{{ route("member.link-sharing.share") }}',
                method: 'POST',
                data: requestData,
                timeout: 15000, // 15 second timeout
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                }
            });
            
            if (response.success) {
                displayShareOptions(response.affiliate_link, name, response);
                showToast('New affiliate link generated successfully!', 'success');
            } else {
                throw new Error(response.message || 'Server returned success=false');
            }
        } catch (error) {
            console.error('AJAX Error:', error);
            
            let errorMessage = 'Failed to generate affiliate link.';
            let errorType = 'general';
            let debugInfo = '';
            let customIcon = 'bx-error-circle text-danger';
            let showRetry = true;
            
            // Handle different types of errors
            if (error.message) {
                errorMessage = error.message;
                
                // Special handling for daily limit
                if (error.message.includes('Daily share limit reached')) {
                    errorType = 'limit_reached';
                    customIcon = 'bx-time text-warning';
                    showRetry = false;
                    errorMessage = 'Daily Sharing Limit Reached';
                }
                // Handle session expiry
                else if (error.message.includes('CSRF')) {
                    errorType = 'session';
                    customIcon = 'bx-shield-x text-info';
                    errorMessage = 'Session expired. Please refresh the page.';
                }
            } else if (error.responseJSON) {
                // Laravel validation or application error
                if (error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                    
                    if (errorMessage.includes('Daily share limit reached')) {
                        errorType = 'limit_reached';
                        customIcon = 'bx-time text-warning';
                        showRetry = false;
                        errorMessage = 'Daily Sharing Limit Reached';
                    }
                }
                if (error.responseJSON.errors) {
                    const validationErrors = Object.values(error.responseJSON.errors).flat();
                    errorMessage = validationErrors.join(', ');
                }
                debugInfo = `Status: ${error.status}, Response: ${JSON.stringify(error.responseJSON)}`;
            } else if (error.responseText) {
                // Parse HTML error response
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(error.responseText, 'text/html');
                    const title = doc.querySelector('title')?.textContent || '';
                    if (title.includes('419')) {
                        errorType = 'session';
                        errorMessage = 'Session expired. Please refresh the page and try again.';
                        customIcon = 'bx-shield-x text-info';
                    } else if (title.includes('500')) {
                        errorMessage = 'Server error occurred. Please try again later.';
                    } else if (title.includes('404')) {
                        errorMessage = 'Service not found. Please contact support.';
                        showRetry = false;
                    }
                    debugInfo = `Status: ${error.status}, Title: ${title}`;
                } catch (parseError) {
                    debugInfo = `Status: ${error.status}, Raw response length: ${error.responseText.length}`;
                }
            } else if (error.status === 0) {
                errorMessage = 'Network error. Please check your internet connection.';
                debugInfo = 'Network unreachable';
                customIcon = 'bx-wifi-off text-secondary';
            } else {
                errorMessage += ` (${error.statusText || 'Unknown error'})`;
                debugInfo = `Status: ${error.status}, Message: ${error.statusText}`;
            }
            
            // Generate appropriate error content based on error type
            let errorContent = '';
            
            if (errorType === 'limit_reached') {
                errorContent = `
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="bx bx-time text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-warning">Daily Sharing Limit Reached</h5>
                        <p class="text-muted mb-3">You've reached your maximum of 5 shares per day for your current package.</p>
                        
                        <div class="alert alert-info border-0 mb-4">
                            <h6 class="mb-2"><i class="bx bx-info-circle me-1"></i>What you can do:</h6>
                            <ul class="text-start mb-0 small">
                                <li>Wait until tomorrow to share more products</li>
                                <li>Use your existing affiliate links from today</li>
                                <li>Upgrade your package for higher daily limits</li>
                                <li>Focus on promoting your current shared links</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('member.link-sharing.dashboard') }}" class="btn btn-primary">
                                <i class="bx bx-dashboard me-1"></i> View Dashboard
                            </a>
                            <a href="{{ route('member.link-sharing.upgrade') }}" class="btn btn-success">
                                <i class="bx bx-trending-up me-1"></i> Upgrade Package
                            </a>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Close
                            </button>
                        </div>
                    </div>
                `;
            } else {
                errorContent = `
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="bx ${customIcon}" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">${errorType === 'session' ? 'Session Issue' : 'Oops! Something went wrong'}</h5>
                        <p class="text-muted mb-3">${errorMessage}</p>
                        
                        ${debugInfo ? `
                            <div class="alert alert-light small text-start mb-3">
                                <strong>Debug Info:</strong><br>
                                Product Slug: <code>${slug}</code><br>
                                ${debugInfo}
                            </div>
                        ` : ''}
                        
                        <div class="d-flex gap-2 justify-content-center">
                            ${showRetry ? `
                                <button class="btn btn-primary retry-btn" data-slug="${slug}" data-name="${name}">
                                    <i class="bx bx-refresh me-1"></i> Try Again
                                </button>
                            ` : ''}
                            ${errorType === 'session' ? `
                                <button class="btn btn-info" onclick="window.location.reload()">
                                    <i class="bx bx-refresh me-1"></i> Refresh Page
                                </button>
                            ` : ''}
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Close
                            </button>
                        </div>
                    </div>
                `;
            }
            
            $('#shareAgainContent').html(errorContent);
            
            // Show appropriate toast message
            if (errorType === 'limit_reached') {
                showToast('Daily sharing limit reached. Try again tomorrow!', 'warning');
            } else {
                showToast(errorMessage, 'danger');
            }
        }
    }
    
    // Retry functionality
    $(document).on('click', '.retry-btn', function() {
        const slug = $(this).data('slug');
        const name = $(this).data('name');
        shareProductAgain(slug, name);
    });
    
    // Enhanced display share options
    function displayShareOptions(affiliateLink, productName, response) {
        const shareText = `🎉 Check out this amazing product: ${productName} 🛍️`;
        const encodedText = encodeURIComponent(shareText);
        const encodedLink = encodeURIComponent(affiliateLink);
        
        $('#shareAgainContent').html(`
            <div class="row">
                <!-- Success Alert -->
                <div class="col-12 mb-4">
                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle fs-4 me-2"></i>
                            <div>
                                <h6 class="mb-1">Link Generated Successfully!</h6>
                                <small>${response.message || 'Your new affiliate link is ready to share.'}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Affiliate Link Section -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">
                        <i class="bx bx-link-alt me-1"></i>Your New Affiliate Link:
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-light" id="newAffiliateLink" value="${affiliateLink}" readonly>
                        <button class="btn btn-outline-primary copy-new-link-btn" type="button">
                            <i class="bx bx-copy me-1"></i> Copy Link
                        </button>
                    </div>
                    <small class="text-muted">This link will track all clicks and earnings back to your account.</small>
                </div>
                
                <!-- Social Sharing Section -->
                <div class="col-12">
                    <h6 class="mb-3 fw-bold">
                        <i class="bx bx-share-alt me-1"></i>Share on Social Platforms:
                    </h6>
                    <div class="row g-2">
                        <div class="col-md-4 col-6">
                            <a href="https://api.whatsapp.com/send?text=${encodedText}%20${encodedLink}" 
                               target="_blank" 
                               class="btn btn-success w-100 social-share-btn">
                                <i class="bx bxl-whatsapp me-1"></i> WhatsApp
                            </a>
                        </div>
                        <div class="col-md-4 col-6">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodedLink}" 
                               target="_blank" 
                               class="btn btn-primary w-100 social-share-btn">
                                <i class="bx bxl-facebook me-1"></i> Facebook
                            </a>
                        </div>
                        <div class="col-md-4 col-6">
                            <a href="https://t.me/share/url?url=${encodedLink}&text=${encodedText}" 
                               target="_blank" 
                               class="btn btn-info w-100 social-share-btn">
                                <i class="bx bxl-telegram me-1"></i> Telegram
                            </a>
                        </div>
                        <div class="col-md-4 col-6">
                            <a href="https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedLink}" 
                               target="_blank" 
                               class="btn btn-dark w-100 social-share-btn">
                                <i class="bx bxl-twitter me-1"></i> Twitter
                            </a>
                        </div>
                        <div class="col-md-4 col-6">
                            <a href="mailto:?subject=${encodeURIComponent('Check out this product!')}&body=${encodedText}%20${encodedLink}" 
                               class="btn btn-warning w-100 social-share-btn">
                                <i class="bx bx-envelope me-1"></i> Email
                            </a>
                        </div>
                        <div class="col-md-4 col-6">
                            <button class="btn btn-secondary w-100 share-more-btn" data-link="${affiliateLink}" data-text="${shareText}">
                                <i class="bx bx-share-alt me-1"></i> More Options
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Tips Section -->
                <div class="col-12 mt-4">
                    <div class="alert alert-info border-0">
                        <h6 class="mb-2"><i class="bx bx-bulb me-1"></i> Pro Tips for Better Results:</h6>
                        <ul class="mb-0 small">
                            <li>Add a personal message when sharing to increase click rates</li>
                            <li>Share during peak hours when your audience is most active</li>
                            <li>Use relevant hashtags to reach more people</li>
                        </ul>
                    </div>
                </div>
            </div>
        `);
    }
    
    // Enhanced copy new link functionality
    $(document).on('click', '.copy-new-link-btn', async function() {
        const linkInput = $('#newAffiliateLink');
        const button = $(this);
        const originalHtml = button.html();
        
        button.html('<i class="bx bx-loader-alt bx-spin"></i>');
        button.prop('disabled', true);
        
        try {
            const success = await copyToClipboard(linkInput.val());
            
            if (success) {
                button.html('<i class="bx bx-check me-1"></i> Copied!');
                button.removeClass('btn-outline-primary').addClass('btn-success');
                showToast('Affiliate link copied to clipboard!', 'success');
            } else {
                throw new Error('Copy failed');
            }
        } catch (error) {
            button.html('<i class="bx bx-error me-1"></i> Failed');
            button.removeClass('btn-outline-primary').addClass('btn-danger');
            showToast('Failed to copy link', 'danger');
        }
        
        setTimeout(() => {
            button.html(originalHtml);
            button.removeClass('btn-success btn-danger').addClass('btn-outline-primary');
            button.prop('disabled', false);
        }, 2000);
    });
    
    // Track social share clicks
    $(document).on('click', '.social-share-btn', function() {
        const platform = $(this).text().trim().toLowerCase();
        showToast(`Opening ${platform} to share your link...`, 'info');
    });
    
    // Native share API for "More Options"
    $(document).on('click', '.share-more-btn', async function() {
        const link = $(this).data('link');
        const text = $(this).data('text');
        
        if (navigator.share) {
            try {
                await navigator.share({
                    title: 'Check out this amazing product!',
                    text: text,
                    url: link
                });
                showToast('Thanks for sharing!', 'success');
            } catch (error) {
                if (error.name !== 'AbortError') {
                    // Fallback to copy
                    const success = await copyToClipboard(link);
                    if (success) {
                        showToast('Link copied to clipboard for manual sharing', 'info');
                    }
                }
            }
        } else {
            // Fallback to copy
            const success = await copyToClipboard(link);
            if (success) {
                showToast('Link copied to clipboard for manual sharing', 'info');
            }
        }
    });
    
    // Add keyboard shortcut for copy (Ctrl+C when modal is open)
    $('#shareAgainModal').on('shown.bs.modal', function() {
        $(document).on('keydown.shareModal', function(e) {
            if (e.ctrlKey && e.key === 'c' && $('#newAffiliateLink').length) {
                e.preventDefault();
                $('.copy-new-link-btn').click();
            }
        });
    });
    
    $('#shareAgainModal').on('hidden.bs.modal', function() {
        $(document).off('keydown.shareModal');
    });
});
</script>
@endpush
@push('styles')
<style>
.social-share-btn {
    transition: all 0.3s ease;
    border: none;
}

.social-share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.copy-new-link-btn:disabled {
    opacity: 0.6;
}

.toast {
    transition: all 0.3s ease;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: scale(1.05);
}
</style>
@endpush
