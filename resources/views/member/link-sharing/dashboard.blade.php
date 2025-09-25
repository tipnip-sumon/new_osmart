@extends('member.layouts.app')

@section('title', 'Link Sharing Dashboard')

@push('styles')
<style>
    /* Real-time animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Product cards */
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.06);
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        border-color: rgba(13, 110, 253, 0.25);
    }
    
    /* Button loading states */
    .share-product-btn.loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-loading .spinner-border {
        width: 0.8rem;
        height: 0.8rem;
    }
    
    /* Search enhancements */
    #productSearch {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    #productSearch:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Floating messages */
    .floating-message {
        animation: slideInRight 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    /* Progress animations */
    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }
    
    @keyframes progress-bar-stripes {
        0% { background-position: 40px 0; }
        100% { background-position: 0 0; }
    }
    
    /* Social share buttons */
    .social-share-btn {
        transition: all 0.2s ease;
        font-weight: 600;
    }
    
    .social-share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Search results counter */
    #searchResults {
        padding: 0.5rem;
        border-radius: 8px;
        background: rgba(13, 110, 253, 0.05);
        border: 1px solid rgba(13, 110, 253, 0.1);
    }
    
    /* Stats card styling */
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        display: block;
    }
    
    .stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    
    /* Copy Protection Styles */
    .no-select {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
        -webkit-touch-callout: none !important;
        -webkit-tap-highlight-color: transparent !important;
        pointer-events: none !important;
    }
    
    .copy-shield {
        position: relative;
        overflow: hidden;
    }
    
    .copy-shield::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        z-index: 10;
        cursor: not-allowed;
    }
    
    .blur-text {
        filter: blur(2px);
        transition: filter 0.3s ease;
    }
    
    .blur-text:hover {
        filter: blur(3px);
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Link Sharing Dashboard</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Link Sharing</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="{{ route('member.link-sharing.history') }}" class="btn btn-secondary">
                <i class="bx bx-history"></i> Sharing History
            </a>
            <a href="{{ route('member.link-sharing.upgrade') }}" class="btn btn-success">
                <i class="bx bx-trending-up"></i> Upgrade Package
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Package Info Card -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Your Package Information</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <i class="bx bx-package" style="font-size: 3rem; color: #6c5ce7;"></i>
                                <h5 class="mt-2">{{ $dashboardData['package_settings']->display_name ?? 'N/A' }}</h5>
                                <p class="text-muted">Current Package</p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Daily Share Limit</small>
                                    <h4 class="text-primary">{{ $dashboardData['package_settings']->daily_share_limit ?? 0 }}</h4>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Reward Per Click</small>
                                    <h4 class="text-success">৳ {{ number_format($dashboardData['package_settings']->click_reward_amount ?? 0, 2) }}</h4>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Daily Earning Limit</small>
                                    <h4 class="text-info">৳ {{ number_format($dashboardData['package_settings']->daily_earning_limit ?? 0, 2) }}</h4>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Package Price</small>
                                    <h4 class="text-warning">৳ {{ number_format($dashboardData['package_settings']->package_price ?? 0, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Stats -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md bg-primary-gradient">
                                <i class="bx bx-share-alt fs-18"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1">Today's Shares</h6>
                            <h4 class="fw-semibold mb-1">{{ $dashboardData['today_stats']->shares_count ?? 0 }}</h4>
                            <small class="text-muted">
                                {{ ($dashboardData['package_settings']->daily_share_limit ?? 0) - ($dashboardData['today_stats']->shares_count ?? 0) }} remaining
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md bg-success-gradient">
                                <i class="bx bx-mouse fs-18"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1">Total Clicks</h6>
                            <h4 class="fw-semibold mb-1">{{ $dashboardData['today_stats']->clicks_count ?? 0 }}</h4>
                            <small class="text-success">
                                {{ $dashboardData['today_stats']->unique_clicks_count ?? 0 }} unique clicks
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md bg-warning-gradient">
                                <i class="bx bx-money fs-18"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1">Today's Earnings</h6>
                            <h4 class="fw-semibold mb-1">৳ {{ number_format($dashboardData['today_stats']->earnings_amount ?? 0, 2) }}</h4>
                            <small class="text-warning">
                                ৳ {{ number_format(($dashboardData['package_settings']->daily_earning_limit ?? 0) - ($dashboardData['today_stats']->earnings_amount ?? 0), 2) }} remaining
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="avatar avatar-md bg-info-gradient">
                                <i class="bx bx-percentage fs-18"></i>
                            </span>
                        </div>
                        <div class="flex-fill">
                            <h6 class="mb-1">Click Rate</h6>
                            <h4 class="fw-semibold mb-1">
                                @php
                                    $clickRate = ($dashboardData['today_stats']->shares_count ?? 0) > 0 
                                        ? round(($dashboardData['today_stats']->clicks_count ?? 0) / ($dashboardData['today_stats']->shares_count ?? 1) * 100, 1) 
                                        : 0;
                                @endphp
                                {{ $clickRate }}%
                            </h4>
                            <small class="text-info">Avg clicks per share</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Product Sharing -->
    <div class="row">
        <!-- Product Selection -->
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Share Products & Earn</div>
                </div>
                <div class="card-body">
                    <!-- Search Products -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="productSearch" placeholder="Search products to share..." autocomplete="off">
                            <button class="btn btn-primary" type="button" id="searchBtn">
                                <i class="bx bx-search"></i> Search
                            </button>
                            <button class="btn btn-outline-secondary" type="button" id="loadPopularBtn">
                                <i class="bx bx-star"></i> Popular
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="bx bx-info-circle"></i> 
                            Search by product name, category, or keywords. Click on any product to instantly generate your affiliate link!
                        </div>
                    </div>

                    <!-- Live Search Results Counter -->
                    <div id="searchResults" class="mb-2" style="display: none;">
                        <small class="text-muted">
                            <i class="bx bx-search-alt"></i> 
                            Found <span id="resultsCount">0</span> products
                        </small>
                    </div>

                    <!-- Products Grid -->
                    <div id="productsContainer" class="row">
                        <div class="col-12 text-center py-4">
                            <i class="bx bx-search" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted">Search for products to share and start earning!</p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="productsPagination" class="d-flex justify-content-center mt-3" style="display: none !important;">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Tips -->
        <div class="col-xl-4">
            <!-- Progress Card -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">Today's Progress</div>
                </div>
                <div class="card-body">
                    @php
                        $shareProgress = ($dashboardData['package_settings']->daily_share_limit ?? 1) > 0 
                            ? (($dashboardData['today_stats']->shares_count ?? 0) / ($dashboardData['package_settings']->daily_share_limit ?? 1)) * 100 
                            : 0;
                        $earningProgress = ($dashboardData['package_settings']->daily_earning_limit ?? 1) > 0 
                            ? (($dashboardData['today_stats']->earnings_amount ?? 0) / ($dashboardData['package_settings']->daily_earning_limit ?? 1)) * 100 
                            : 0;
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Shares Used</span>
                            <span class="fw-semibold">{{ number_format($shareProgress, 1) }}%</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" style="width: {{ $shareProgress }}%"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Earnings Progress</span>
                            <span class="fw-semibold">{{ number_format($earningProgress, 1) }}%</span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: {{ $earningProgress }}%"></div>
                        </div>
                    </div>
                    
                    @if($shareProgress < 100 && $earningProgress < 100)
                        <div class="alert alert-info alert-sm">
                            <i class="bx bx-info-circle"></i>
                            You can still share {{ ($dashboardData['package_settings']->daily_share_limit ?? 0) - ($dashboardData['today_stats']->shares_count ?? 0) }} more products today!
                        </div>
                    @else
                        <div class="alert alert-warning alert-sm">
                            <i class="bx bx-time"></i>
                            Daily limit reached. Come back tomorrow!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">💡 Sharing Tips</div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            Share popular products for more clicks
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            Use multiple platforms (WhatsApp, Facebook, Telegram)
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            Add personal recommendations
                        </li>
                        <li class="mb-2">
                            <i class="bx bx-check text-success"></i>
                            Share in relevant groups/communities
                        </li>
                        <li class="mb-0">
                            <i class="bx bx-check text-success"></i>
                            Start sharing early in the day
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Product Modal -->
<div class="modal fade" id="shareProductModal" tabindex="-1" aria-labelledby="shareProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareProductModalLabel">Share Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="shareProductContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentProducts = [];
    
    // Enhanced copy protection system
    function initializeCopyProtection() {
        // Disable right-click context menu
        $(document).on('contextmenu', function(e) {
            e.preventDefault();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'Right-click disabled for security',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return false;
        });
        
        // Disable text selection
        $(document).on('selectstart dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Disable common keyboard shortcuts for copying
        $(document).on('keydown', function(e) {
            // Disable Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+S, Ctrl+U, F12
            if ((e.ctrlKey || e.metaKey) && (
                e.key === 'a' || e.key === 'c' || e.key === 'v' || 
                e.key === 's' || e.key === 'u' || e.key === 'A' || 
                e.key === 'C' || e.key === 'V' || e.key === 'S' || e.key === 'U'
            )) {
                // Allow Ctrl+K for search
                if (e.key === 'k' || e.key === 'K') {
                    e.preventDefault();
                    $('#productSearch').focus();
                    return false;
                }
                
                e.preventDefault();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Copy/paste disabled for fair earning system',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
                return false;
            }
            
            // Disable F12 (Developer Tools)
            if (e.key === 'F12') {
                e.preventDefault();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Developer tools disabled for security',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return false;
            }
            
            // Disable Ctrl+Shift+I (Developer Tools)
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
                e.preventDefault();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning', 
                    title: 'Developer tools disabled for security',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return false;
            }
            
            // Disable Ctrl+Shift+J (Console)
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'J' || e.key === 'j')) {
                e.preventDefault();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Console access disabled for security',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                return false;
            }
        });
        
        // Clear clipboard periodically
        setInterval(function() {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText('').catch(() => {});
            }
        }, 5000);
        
        // Monitor for developer tools
        let devtools = {open: false, orientation: null};
        setInterval(function() {
            if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
                if (!devtools.open) {
                    devtools.open = true;
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Please close developer tools for security',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                }
            } else {
                devtools.open = false;
            }
        }, 500);
    }
    
    // Initialize copy protection
    initializeCopyProtection();
    
    // Enhanced image URL handler to match PHP logic
    function getProductImageUrl(product) {
        // First try the processed image field from backend (already processed by PHP)
        if (product.image && product.image !== '/img/default-product.jpg' && product.image !== 'products/product1.jpg') {
            return product.image;
        }
        
        // Fallback to default image
        return '/assets/img/product/default.png';
    }
    
    // Real-time search as user types
    $('#productSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                searchProducts(query);
            }, 500); // Debounce for 500ms
        } else if (query.length === 0) {
            loadPopularProducts();
        }
    });
    
    // Search button click
    $('#searchBtn').click(function() {
        const query = $('#productSearch').val().trim();
        if (query) {
            searchProducts(query);
        } else {
            loadPopularProducts();
        }
    });
    
    // Load popular products button
    $('#loadPopularBtn').click(function() {
        loadPopularProducts();
        $('#productSearch').val('');
    });
    
    // Enter key search
    $('#productSearch').keypress(function(e) {
        if (e.which === 13) {
            const query = $(this).val().trim();
            if (query) {
                searchProducts(query);
            } else {
                loadPopularProducts();
            }
        }
    });
    
    // Inline load popular products link
    $(document).on('click', '#loadPopularInline', function(e) {
        e.preventDefault();
        loadPopularProducts();
        $('#productSearch').val('');
    });
    
    function searchProducts(query = '') {
        // Show loading with search indication
        showSearchLoading('Searching for products...');
        
        $.ajax({
            url: '{{ route("member.link-sharing.products") }}',
            method: 'GET',
            data: { 
                search: query,
                limit: 12,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    currentProducts = response.products;
                    if (response.products.length > 0) {
                        displayProducts(response.products);
                        updateSearchResults(response.products.length, query);
                    } else {
                        showNoResults(query);
                    }
                } else {
                    showNoResults(query);
                }
            },
            error: function(xhr) {
                console.error('Search error:', xhr);
                showSearchError();
            }
        });
    }
    
    function loadPopularProducts() {
        showSearchLoading('Loading popular products...');
        
        $.ajax({
            url: '{{ route("member.link-sharing.products") }}',
            method: 'GET',
            data: { 
                popular: true,
                limit: 12,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.products.length > 0) {
                    currentProducts = response.products;
                    displayProducts(response.products);
                    $('#searchResults').html(`
                        <small class="text-muted">
                            <i class="bx bx-star"></i> 
                            Showing popular products
                        </small>
                    `).show();
                } else {
                    showNoResults('popular products');
                }
            },
            error: function(xhr) {
                console.error('Load popular error:', xhr);
                showSearchError();
            }
        });
    }
    
    function showSearchLoading(message = 'Loading...') {
        $('#productsContainer').html(`
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">${message}</p>
                <div class="progress mx-auto" style="width: 200px; height: 4px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                </div>
            </div>
        `);
        $('#searchResults').hide();
    }
    
    function updateSearchResults(count, query) {
        if (count > 0) {
            $('#searchResults').html(`
                <small class="text-success">
                    <i class="bx bx-check-circle"></i> 
                    Found ${count} products${query ? ' for "' + query + '"' : ''}
                </small>
            `).show();
        } else {
            $('#searchResults').hide();
        }
    }
    
    function showNoResults(query) {
        $('#productsContainer').html(`
            <div class="col-12 text-center py-5">
                <i class="bx bx-search-alt-2" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h5 class="text-muted">No products found</h5>
                <p class="text-muted mb-3">
                    ${query !== 'popular products' ? 'No products found for "' + query + '"' : 'No popular products available'}
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-primary btn-sm" id="loadPopularInline">
                        <i class="bx bx-star"></i> Browse Popular
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="$('#productSearch').val('').focus()">
                        <i class="bx bx-search"></i> New Search
                    </button>
                </div>
            </div>
        `);
        $('#searchResults').hide();
    }
    
    function showSearchError() {
        $('#productsContainer').html(`
            <div class="col-12 text-center py-5">
                <i class="bx bx-error-circle" style="font-size: 4rem; color: #dc3545; margin-bottom: 1rem;"></i>
                <h5 class="text-danger">Oops! Something went wrong</h5>
                <p class="text-muted mb-3">Unable to load products right now</p>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-danger btn-sm" onclick="location.reload()">
                        <i class="bx bx-refresh"></i> Refresh Page
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="loadPopularInline">
                        <i class="bx bx-star"></i> Try Popular
                    </button>
                </div>
            </div>
        `);
        $('#searchResults').hide();
    }
    
    function displayProducts(products) {
        if (!products || products.length === 0) {
            showNoResults('your search');
            return;
        }
        

        let html = '';
        products.forEach((product, index) => {
            // Stagger animation
            const delay = index * 100;
            
            html += `
                <div class="col-lg-4 col-md-6 mb-4" style="animation: fadeInUp 0.6s ease forwards ${delay}ms; opacity: 0;">
                    <div class="card product-card h-100 shadow-sm border-0" style="transition: all 0.3s ease;">
                        <div class="position-relative overflow-hidden">
                            <img src="${getProductImageUrl(product)}" 
                                 class="card-img-top" 
                                 alt="${product.name}" 
                                 style="height: 220px; object-fit: cover; transition: transform 0.3s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onerror="this.src='/assets/img/product/default.png'; this.onerror=null;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success fs-6 px-2 py-1">৳${parseFloat(product.sale_price || product.price || 0).toLocaleString()}</span>
                            </div>
                            ${product.stock_quantity <= 10 ? '<div class="position-absolute top-0 start-0 m-2"><span class="badge bg-warning text-dark">Low Stock</span></div>' : ''}
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-truncate mb-2" title="${product.name}">${product.name}</h6>
                            <p class="card-text text-muted small mb-2" style="font-size: 0.85rem;">
                                <i class="bx bx-category"></i> ${product.category || 'General'}
                            </p>
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm share-product-btn position-relative" 
                                            data-slug="${product.slug}" 
                                            data-name="${product.name}"
                                            data-price="${product.sale_price || product.price || 0}">
                                        <span class="btn-text">
                                            <i class="bx bx-share-alt me-1"></i>Generate Link & Earn
                                        </span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                            Generating...
                                        </span>
                                    </button>
                                    <div class="text-center small text-success fw-bold">
                                        <i class="bx bx-money"></i> Earn {{ $dashboardData['package_settings']->reward_per_click ?? 2 }} TK per click!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        $('#productsContainer').html(html);
    }
    
    // Enhanced share product with real-time feedback
    $(document).on('click', '.share-product-btn', function() {
        const $btn = $(this);
        const slug = $btn.data('slug');
        const name = $btn.data('name');
        const price = $btn.data('price');
        
        // Prevent double clicks
        if ($btn.hasClass('loading')) return;
        
        // Show loading state
        $btn.addClass('loading');
        $btn.find('.btn-text').addClass('d-none');
        $btn.find('.btn-loading').removeClass('d-none');
        
        // Generate affiliate link instantly
        generateAffiliateLink(slug, name, price, $btn);
    });
    
    function generateAffiliateLink(slug, name, price, $btn) {
        $.ajax({
            url: '{{ route("member.link-sharing.share") }}',
            method: 'POST',
            data: {
                product_slug: slug,
                platform: 'instant_share',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Reset button state
                resetButton($btn);
                
                if (response.success) {
                    // Show immediate success feedback
                    showInstantLinkSuccess($btn, name, price);
                    
                    // Display full share modal with link
                    displayShareModal(response.affiliate_link, name, price, response);
                    
                    // Update dashboard stats if provided
                    if (response.stats) {
                        updateDashboardStats(response.stats);
                    }
                } else {
                    showInstantError($btn, response.message || 'Failed to generate link');
                }
            },
            error: function(xhr) {
                resetButton($btn);
                
                let errorMessage = 'Network error. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                    
                    // Enhanced error message handling
                    if (errorMessage.toLowerCase().includes('share limit') || 
                        errorMessage.toLowerCase().includes('daily limit') ||
                        errorMessage.toLowerCase().includes('earning limit')) {
                        errorMessage = 'Daily sharing/earning limit reached. Try again tomorrow or upgrade your package for higher limits.';
                    } else if (errorMessage.toLowerCase().includes('already shared')) {
                        errorMessage = 'You have already shared this product today. Try sharing different products.';
                    } else if (xhr.status === 429) {
                        errorMessage = 'Too many requests. Please wait a moment before trying again.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Access denied. Please check your account status or contact support.';
                    }
                }
                
                showInstantError($btn, errorMessage);
            }
        });
    }
    
    function resetButton($btn) {
        $btn.removeClass('loading');
        $btn.find('.btn-text').removeClass('d-none');
        $btn.find('.btn-loading').addClass('d-none');
    }
    
    function showInstantLinkSuccess($btn, name, price) {
        const originalHtml = $btn.html();
        $btn.html(`
            <i class="bx bx-check-circle me-1"></i>Link Generated!
        `).removeClass('btn-primary').addClass('btn-success');
        
        setTimeout(() => {
            $btn.html(originalHtml).removeClass('btn-success').addClass('btn-primary');
        }, 2000);
        
        // Show floating success message
        showFloatingMessage('success', `Affiliate link generated for "${name}"!`);
    }
    
    function showInstantError($btn, message) {
        const originalHtml = $btn.html();
        $btn.html(`
            <i class="bx bx-error-circle me-1"></i>Try Again
        `).removeClass('btn-primary').addClass('btn-danger');
        
        setTimeout(() => {
            $btn.html(originalHtml).removeClass('btn-danger').addClass('btn-primary');
        }, 3000);
        
        // Show floating error message
        showFloatingMessage('error', message);
    }
    
    function showFloatingMessage(type, message) {
        // Use SweetAlert2 instead of basic floating messages
        if (type === 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else if (type === 'error') {
            // Check if it's a daily limit error for special handling
            if (message.toLowerCase().includes('daily limit') || message.toLowerCase().includes('limit reached')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Daily Limit Reached!',
                    html: `
                        <div class="text-center">
                            <i class="bx bx-time-five" style="font-size: 3rem; color: #f39c12; margin-bottom: 1rem;"></i>
                            <p style="font-size: 1.1rem; margin-bottom: 1rem;">${message}</p>
                            <div class="alert alert-light border" style="background-color: #f8f9fa;">
                                <strong>💡 What you can do:</strong>
                                <ul class="list-unstyled mt-2 mb-0" style="line-height: 1.6;">
                                    <li>✅ Come back tomorrow for fresh limits</li>
                                    <li>✅ Check your sharing history</li>
                                    <li>✅ Upgrade your package for higher limits</li>
                                    <li>✅ Focus on existing shared links performance</li>
                                </ul>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Check History',
                    cancelButtonText: 'Upgrade Package',
                    showCancelButton: true,
                    confirmButtonColor: '#6c757d',
                    cancelButtonColor: '#28a745',
                    customClass: {
                        popup: 'swal-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to sharing history
                        window.location.href = '{{ route("member.link-sharing.history") }}';
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Redirect to upgrade page
                        window.location.href = '{{ route("member.link-sharing.upgrade") }}';
                    }
                });
            } else {
                // Regular error toast
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'colored-toast'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            }
        }
    }
    
    function displayShareModal(affiliateLink, productName, price, response) {
        $('#shareProductModalLabel').text(`Share: ${productName}`);
        
        const shareText = `🔥 Amazing Deal Alert! Check out "${productName}" for just ৳${parseFloat(price).toLocaleString()}! 💯`;
        const encodedText = encodeURIComponent(shareText);
        const encodedLink = encodeURIComponent(affiliateLink);
        
        $('#shareProductContent').html(`
            <div class="row g-3">
                <!-- Success Alert -->
                <div class="col-12">
                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle fs-4 me-2"></i>
                            <div>
                                <strong>Success!</strong> Your affiliate link has been generated
                                <small class="d-block text-muted">Earn {{ $dashboardData['package_settings']->reward_per_click ?? 2 }} TK for each unique click!</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Affiliate Link Display (No Copy Option) -->
                <div class="col-12">
                    <div class="alert alert-info border-0">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-info-circle fs-5 me-2"></i>
                            <div>
                                <strong>Share Only Through Messaging Platforms</strong>
                                <small class="d-block text-muted">Use the buttons below to share directly. Manual copying is disabled to ensure fair earning.</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Message (Copy-Protected) -->
                <div class="col-12">
                    <label class="form-label fw-semibold">
                        <i class="bx bx-eye me-1"></i>Message Preview:
                    </label>
                    <div class="bg-light rounded p-3 border copy-shield no-select" 
                         style="font-size: 0.9rem;">
                        <div class="mb-2">${shareText}</div>
                        <div class="text-primary small d-flex align-items-center blur-text">
                            <i class="bx bx-link-external me-1"></i> 
                            <span class="text-truncate" style="max-width: 300px;">[Protected Link - Use Share Buttons]</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <i class="bx bx-shield-check text-success me-2"></i>
                        <small class="text-success">
                            <strong>Security Active:</strong> Link is protected to ensure fair affiliate earnings.
                        </small>
                    </div>
                </div>
                
                <!-- Messaging Platforms -->
                <div class="col-12">
                    <label class="form-label fw-semibold mb-3">
                        <i class="bx bx-message-rounded-dots me-1"></i>Share on Messaging Platforms:
                    </label>
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <a href="https://api.whatsapp.com/send?text=${encodedText}%20${encodedLink}" 
                               target="_blank" 
                               class="btn btn-success w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="whatsapp"
                               style="padding: 12px;">
                                <i class="bx bxl-whatsapp fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">WhatsApp</div>
                                    <small class="opacity-75">Personal & Groups</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://t.me/share/url?url=${encodedLink}&text=${encodedText}" 
                               target="_blank" 
                               class="btn btn-info w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="telegram"
                               style="padding: 12px;">
                                <i class="bx bxl-telegram fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">Telegram</div>
                                    <small class="opacity-75">Channels & Groups</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodedLink}&quote=${encodedText}" 
                               target="_blank" 
                               class="btn btn-primary w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="facebook"
                               style="padding: 12px;">
                                <i class="bx bxl-facebook fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">Facebook</div>
                                    <small class="opacity-75">Messenger & Posts</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://x.com/intent/tweet?text=${encodedText}&url=${encodedLink}" 
                               target="_blank" 
                               class="btn btn-dark w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="x"
                               style="padding: 12px;">
                                <i class="bx bxl-twitter fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">X (Twitter)</div>
                                    <small class="opacity-75">Posts & DMs</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodedLink}" 
                               target="_blank" 
                               class="btn btn-outline-primary w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="linkedin"
                               style="padding: 12px;">
                                <i class="bx bxl-linkedin fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">LinkedIn</div>
                                    <small class="opacity-75">Professional Network</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://discord.com/channels/@me" 
                               target="_blank" 
                               class="btn btn-secondary w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="discord"
                               onclick="copyToDiscord('${shareText}', '${affiliateLink}')"
                               style="padding: 12px;">
                                <i class="bx bxl-discord-alt fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">Discord</div>
                                    <small class="opacity-75">Servers & DMs</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="viber://forward?text=${encodedText}%20${encodedLink}" 
                               class="btn btn-purple w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="viber"
                               style="padding: 12px; background-color: #665CAC;">
                                <i class="bx bx-message-dots fs-5 me-2"></i> 
                                <div class="text-start text-white">
                                    <div class="fw-bold">Viber</div>
                                    <small class="opacity-75">Mobile Messaging</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="https://line.me/R/msg/text/?${encodedText}%20${encodedLink}" 
                               target="_blank"
                               class="btn w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="line"
                               style="padding: 12px; background-color: #00C300; color: white;">
                                <i class="bx bx-chat fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">LINE</div>
                                    <small class="opacity-75">Asian Messaging</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-4">
                            <a href="mailto:?subject=${encodeURIComponent('Check out ' + productName)}&body=${encodedText}%20${encodedLink}" 
                               class="btn btn-warning w-100 social-share-btn d-flex align-items-center justify-content-center"
                               data-platform="email"
                               style="padding: 12px;">
                                <i class="bx bx-envelope fs-5 me-2"></i> 
                                <div class="text-start">
                                    <div class="fw-bold">Email</div>
                                    <small class="opacity-75">Personal & Business</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Row -->
                <div class="col-12 mt-4">
                    <div class="row text-center bg-light rounded p-3">
                        <div class="col-4">
                            <div class="fw-bold text-primary fs-5">${response.shares_remaining || 0}</div>
                            <small class="text-muted">Shares Left</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success fs-5">৳${parseFloat(response.earnings_today || 0).toFixed(2)}</div>
                            <small class="text-muted">Today's Earnings</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-info fs-5">৳${parseFloat(response.earnings_limit || 0).toFixed(2)}</div>
                            <small class="text-muted">Daily Limit</small>
                        </div>
                    </div>
                </div>
                
                <!-- Tips -->
                <div class="col-12">
                    <div class="alert alert-light border-0">
                        <h6 class="alert-heading">
                            <i class="bx bx-shield-check text-success"></i> Fair Earning System:
                        </h6>
                        <ul class="mb-0 small">
                            <li><strong>Share through messaging platforms only</strong> - prevents self-clicking abuse</li>
                            <li><strong>Each unique visitor earns you {{ $dashboardData['package_settings']->reward_per_click ?? 2 }} TK</strong> - tracked by device fingerprint</li>
                            <li><strong>Best platforms:</strong> WhatsApp groups, Telegram channels, Facebook Messenger</li>
                            <li><strong>Optimal sharing time:</strong> 6-9 PM for maximum engagement</li>
                            <li><strong>Personal touch:</strong> Add your own recommendation to increase trust</li>
                        </ul>
                    </div>
                </div>
            </div>
        `);
        
        $('#shareProductModal').modal('show');
        
        // Reinforce copy protection when modal opens
        setTimeout(function() {
            $('.copy-shield, .no-select').each(function() {
                $(this).on('mousedown selectstart dragstart contextmenu', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Content protected - Use share buttons only',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return false;
                });
            });
        }, 100);
    }
    
    // Copy functionality with enhanced feedback - REMOVED FOR FAIR EARNING
    // Copy link functionality disabled to prevent self-clicking abuse
    
    // Discord sharing function
    window.copyToDiscord = function(shareText, affiliateLink) {
        // Show instruction modal for Discord
        const discordText = shareText + '\n\n' + affiliateLink;
        
        // Try to use clipboard API if available
        if (navigator.clipboard) {
            navigator.clipboard.writeText(discordText).then(function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Message copied! Paste it in Discord and send.',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            }).catch(function() {
                showDiscordInstructions(discordText);
            });
        } else {
            showDiscordInstructions(discordText);
        }
        
        // Also open Discord
        setTimeout(() => {
            window.open('https://discord.com/channels/@me', '_blank');
        }, 1000);
    };
    
    function showDiscordInstructions(text) {
        const modal = `
            <div class="modal fade" id="discordModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Share on Discord</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Copy this message and paste it in your Discord chat:</p>
                            <textarea class="form-control" rows="4" readonly onclick="this.select()">${text}</textarea>
                            <div class="mt-2">
                                <button class="btn btn-primary btn-sm" onclick="this.previousElementSibling.previousElementSibling.select(); document.execCommand('copy'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy Text', 2000)">Copy Text</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modal);
        $('#discordModal').modal('show').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }
    
    // Copy entire message function - REMOVED
    // window.copyMessage function removed to prevent manual copying abuse
    
    // Track social media shares with platform-specific messages
    $(document).on('click', '.social-share-btn', function() {
        const platform = $(this).data('platform');
        const platformNames = {
            'whatsapp': 'WhatsApp',
            'telegram': 'Telegram', 
            'facebook': 'Facebook',
            'x': 'X (Twitter)',
            'linkedin': 'LinkedIn',
            'discord': 'Discord',
            'viber': 'Viber',
            'line': 'LINE',
            'email': 'Email'
        };
        
        const platformName = platformNames[platform] || platform.charAt(0).toUpperCase() + platform.slice(1);
        
        if (platform === 'discord') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: `Opening ${platformName}... Message ready to paste!`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: `Opening ${platformName} to share your link...`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
    
    // Update dashboard stats in real-time
    function updateDashboardStats(stats) {
        // Update progress bars and counters if they exist
        if (stats.shares_used !== undefined) {
            const shareProgress = (stats.shares_used / stats.share_limit) * 100;
            $('.progress-bar').first().css('width', shareProgress + '%');
        }
        
        if (stats.earnings_today !== undefined) {
            // Update any earnings display elements
            $('.earnings-today').text('৳' + parseFloat(stats.earnings_today).toFixed(2));
        }
    }
    
    // Auto-load popular products on page load
    setTimeout(() => {
        if ($('#productSearch').val().trim() === '') {
            loadPopularProducts();
        }
    }, 800);
    
    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Ctrl/Cmd + K for quick search focus
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('#productSearch').focus();
        }
        
        // Escape to close modal
        if (e.key === 'Escape') {
            $('#shareProductModal').modal('hide');
        }
    });
});
</script>
@endpush
