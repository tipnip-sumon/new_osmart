@extends('layouts.app')

@section('title', 'Cart - ' . config('app.name'))

@push('styles')
<style>
.cart-item {
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    background: white;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.cart-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.cart-item.updating {
    opacity: 0.7;
    pointer-events: none;
}

.cart-item.removing {
    animation: slideOut 0.3s ease-out forwards;
}

@keyframes slideOut {
    0% {
        opacity: 1;
        transform: translateX(0);
    }
    100% {
        opacity: 0;
        transform: translateX(-100%);
    }
}

@keyframes scaleUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.cart-item.updated {
    animation: scaleUpdate 0.3s ease-in-out;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    overflow: hidden;
}

.quantity-btn {
    background: #f8f9fa;
    border: none;
    padding: 0.5rem 0.75rem;
    color: #6b7280;
    transition: all 0.3s ease;
    cursor: pointer;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn i {
    pointer-events: none;
    font-size: 14px;
}

.quantity-btn:hover:not(:disabled) {
    background: #e5e7eb;
    color: #374151;
}

.quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity-input {
    border: none;
    text-align: center;
    width: 60px;
    padding: 0.5rem 0.25rem;
    transition: all 0.3s ease;
}

.quantity-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

.quantity-input:disabled {
    background: #f9fafb;
    opacity: 0.7;
}

.remove-btn {
    color: #ef4444;
    background: none;
    border: none;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.3s ease;
}

.remove-btn:hover:not(:disabled) {
    background: #fef2f2;
    color: #dc2626;
    transform: scale(1.1);
}

.remove-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.cart-summary {
    background: #f8f9fa;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.cart-summary.updating {
    opacity: 0.7;
}

.total-row {
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
    margin-top: 1rem;
    font-weight: 600;
    font-size: 1.125rem;
}

.checkout-btn {
    background: linear-gradient(45deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    padding: 0.875rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s ease;
}

.checkout-btn:hover:not(:disabled) {
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
}

.checkout-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.empty-cart-icon {
    font-size: 5rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.custom-toast {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Loading states */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    z-index: 10;
}

.spinner {
    width: 24px;
    height: 24px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #6366f1;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (max-width: 767.98px) {
    .cart-item-image {
        width: 60px;
        height: 60px;
    }
    
    .quantity-controls {
        width: 100px;
    }
    
    .quantity-input {
        width: 40px;
    }
    
    .cart-summary {
        padding: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-xl py-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Shopping Cart</h2>
                <div class="page-pretitle">
                    @if(count($cartItems) > 0)
                        {{ count($cartItems) }} {{ Str::plural('item', count($cartItems)) }} in your cart
                    @else
                        Your cart is empty
                    @endif
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('shop.grid') }}" class="btn btn-outline-primary">
                    <i class="ti ti-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>

    @if(count($cartItems) > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item" data-product-id="{{ $item['id'] }}">
                            <div class="row g-3 p-3 align-items-center">
                                <!-- Product Image -->
                                <div class="col-auto">
                                    @if(!empty($item['image']))
                                        @php
                                            // Debug the actual image data
                                            $imageUrl = '';
                                            
                                            // Since cart stores simple string URLs, handle accordingly
                                            if (is_string($item['image'])) {
                                                // Simple string path - check if it already has proper prefix
                                                if (str_starts_with($item['image'], 'http')) {
                                                    $imageUrl = $item['image'];
                                                } elseif (str_starts_with($item['image'], '/storage/')) {
                                                    $imageUrl = asset($item['image']);
                                                } elseif (str_starts_with($item['image'], 'storage/')) {
                                                    $imageUrl = asset($item['image']);
                                                } else {
                                                    $imageUrl = asset('storage/' . $item['image']);
                                                }
                                            } elseif (is_array($item['image'])) {
                                                // Handle complex nested structure (unlikely for cart but keep for safety)
                                                $image = $item['image'];
                                                if (isset($image['sizes']['medium']['storage_url'])) {
                                                    $imageUrl = $image['sizes']['medium']['storage_url'];
                                                } elseif (isset($image['sizes']['original']['storage_url'])) {
                                                    $imageUrl = $image['sizes']['original']['storage_url'];
                                                } elseif (isset($image['sizes']['large']['storage_url'])) {
                                                    $imageUrl = $image['sizes']['large']['storage_url'];
                                                } elseif (isset($image['urls']['medium'])) {
                                                    $imageUrl = $image['urls']['medium'];
                                                } elseif (isset($image['urls']['original'])) {
                                                    $imageUrl = $image['urls']['original'];
                                                } elseif (isset($image['url']) && is_string($image['url'])) {
                                                    $imageUrl = $image['url'];
                                                } elseif (isset($image['path']) && is_string($image['path'])) {
                                                    $imageUrl = asset('storage/' . $image['path']);
                                                }
                                            }
                                            
                                            // Fallback to default if still empty
                                            if (empty($imageUrl)) {
                                                $imageUrl = asset('assets/img/product/1.png');
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="cart-item-image"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/img/product/1.png') }}';">
                                    @else
                                        <div class="cart-item-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="ti ti-photo text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Details -->
                                <div class="col">
                                    <h6 class="mb-1 fw-semibold">{{ $item['name'] }}</h6>
                                    <div class="text-muted small mb-2">
                                        Price: ৳{{ number_format($item['price'], 2) }}
                                    </div>
                                    
                                    <!-- Mobile: Quantity and Remove -->
                                    <div class="d-md-none">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="quantity-controls">
                                                <button type="button" class="quantity-btn decrease-btn" data-product-id="{{ $item['id'] }}">
                                                    <i class="ti ti-minus"></i>
                                                </button>
                                                <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="99"
                                                       data-product-id="{{ $item['id'] }}">
                                                <button type="button" class="quantity-btn increase-btn" data-product-id="{{ $item['id'] }}">
                                                    <i class="ti ti-plus"></i>
                                                </button>
                                            </div>
                                            <button type="button" class="remove-btn" data-product-id="{{ $item['id'] }}" title="Remove item">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2 fw-semibold text-primary">
                                            Total: ৳{{ number_format($item['total'], 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Desktop: Quantity -->
                                <div class="col-auto d-none d-md-block">
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn decrease-btn" data-product-id="{{ $item['id'] }}">
                                            <i class="ti ti-minus"></i>
                                        </button>
                                        <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="99"
                                               data-product-id="{{ $item['id'] }}">
                                        <button type="button" class="quantity-btn increase-btn" data-product-id="{{ $item['id'] }}">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Desktop: Total Price -->
                                <div class="col-auto d-none d-md-block">
                                    <div class="fw-semibold text-primary">
                                        ৳{{ number_format($item['total'], 2) }}
                                    </div>
                                </div>
                                
                                <!-- Desktop: Remove Button -->
                                <div class="col-auto d-none d-md-block">
                                    <button type="button" class="remove-btn" data-product-id="{{ $item['id'] }}" title="Remove item">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Cart Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn btn-outline-danger" onclick="clearCart()">
                        <i class="ti ti-trash me-2"></i>Clear Cart
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="refreshCart()">
                        <i class="ti ti-refresh me-2"></i>Update Cart
                    </button>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary sticky-top" style="top: 20px;">
                    <h5 class="mb-3">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ count($cartItems) }} items)</span>
                        <span>৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>৳{{ number_format(60, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (5%)</span>
                        <span>৳{{ number_format($subtotal * 0.05, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between total-row">
                        <span>Total</span>
                        <span>৳{{ number_format($subtotal + 60 + ($subtotal * 0.05), 2) }}</span>
                    </div>
                    
                    <button type="button" class="checkout-btn mt-4" onclick="proceedToCheckout()">
                        <i class="ti ti-credit-card me-2"></i>Proceed to Checkout
                    </button>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="ti ti-shield-check me-1"></i>
                            Secure checkout with SSL encryption
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="ti ti-shopping-cart"></i>
            </div>
            <h3 class="mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('shop.grid') }}" class="btn btn-primary btn-lg">
                <i class="ti ti-shopping-bag me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Global cart data
let cartData = @json($cartItems);
let cartSubtotal = {{ $subtotal ?? 0 }};

// Update quantity without page reload
function updateQuantity(productId, newQuantity) {
    console.log('updateQuantity called with:', productId, newQuantity);
    
    if (newQuantity < 1) {
        removeFromCart(productId);
        return;
    }
    
    // Show loading state
    const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
    if (!cartItem) {
        console.error('Cart item not found:', productId);
        return;
    }
    
    console.log('Cart item found:', cartItem);
    
    const quantityInput = cartItem.querySelector('.quantity-input');
    const quantityButtons = cartItem.querySelectorAll('.quantity-btn');
    
    if (!quantityInput) {
        console.error('Quantity input not found for product:', productId);
        return;
    }
    
    console.log('Quantity input found:', quantityInput, 'Current value:', quantityInput.value);
    
    const originalValue = quantityInput.value;
    
    // Disable controls
    quantityInput.disabled = true;
    quantityButtons.forEach(btn => btn.disabled = true);
    cartItem.classList.add('updating');
    
    // Add loading overlay
    addLoadingOverlay(cartItem);
    
    console.log('Sending AJAX request to update quantity...');
    
    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => {
        console.log('Response received:', response);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            console.log('Update successful, item_total:', data.item_total);
            
            // Update cart data
            updateCartItemData(productId, newQuantity, data.item_total);
            
            // Update DOM without reload
            updateCartItemDisplay(productId, newQuantity, data.item_total);
            updateCartSummary();
            updateGlobalCartCount();
            
            // Add success animation
            cartItem.classList.add('updated');
            setTimeout(() => cartItem.classList.remove('updated'), 300);
            
            showToast('Cart updated successfully', 'success');
        } else {
            console.error('Update failed:', data.message);
            // Revert quantity on error
            quantityInput.value = originalValue;
            showToast(data.message || 'Error updating quantity', 'error');
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        // Revert quantity on error
        quantityInput.value = originalValue;
        showToast('Error updating quantity', 'error');
    })
    .finally(() => {
        console.log('Re-enabling controls...');
        // Re-enable controls
        quantityInput.disabled = false;
        quantityButtons.forEach(btn => btn.disabled = false);
        cartItem.classList.remove('updating');
        removeLoadingOverlay(cartItem);
    });
}

// Remove from cart without page reload
function removeFromCart(productId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        if (!cartItem) {
            console.error('Cart item not found:', productId);
            return;
        }
        
        const removeBtn = cartItem.querySelector('.remove-btn');
        
        // Disable remove button and add loading state
        if (removeBtn) {
            removeBtn.disabled = true;
        }
        addLoadingOverlay(cartItem);
        
        // Add removing animation
        cartItem.classList.add('removing');
        
        fetch('{{ route("cart.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove item from cart data
                cartData = cartData.filter(item => item.id != productId);
                
                // Remove item from DOM after animation
                setTimeout(() => {
                    cartItem.remove();
                    updateCartSummary();
                    updateGlobalCartCount();
                    
                    // Check if cart is empty
                    if (cartData.length === 0) {
                        showEmptyCartState();
                    }
                }, 300);
                
                showToast('Item removed from cart', 'success');
            } else {
                // Revert animation on error
                cartItem.classList.remove('removing');
                removeLoadingOverlay(cartItem);
                if (removeBtn) {
                    removeBtn.disabled = false;
                }
                showToast(data.message || 'Error removing item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert animation on error
            cartItem.classList.remove('removing');
            removeLoadingOverlay(cartItem);
            if (removeBtn) {
                removeBtn.disabled = false;
            }
            showToast('Error removing item', 'error');
        });
    }
}

// Clear cart without page reload
function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        const cartItems = document.querySelector('.cart-items');
        cartItems.style.opacity = '0.5';
        
        fetch('{{ route("cart.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear cart data
                cartData = [];
                cartSubtotal = 0;
                
                // Show empty cart state
                showEmptyCartState();
                updateGlobalCartCount();
                
                showToast('Cart cleared successfully', 'success');
            } else {
                cartItems.style.opacity = '1';
                showToast(data.message || 'Error clearing cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            cartItems.style.opacity = '1';
            showToast('Error clearing cart', 'error');
        });
    }
}

// Refresh cart without page reload
function refreshCart() {
    const refreshButton = document.querySelector('button[onclick="refreshCart()"]');
    const originalContent = refreshButton.innerHTML;
    
    // Show loading state on button
    refreshButton.disabled = true;
    refreshButton.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Updating...';
    
    // Add loading overlay to cart items
    const cartItemsContainer = document.querySelector('.cart-items');
    if (cartItemsContainer) {
        addLoadingOverlay(cartItemsContainer);
    }
    
    fetch('{{ route("cart.index") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart data
            cartData = data.cartItems;
            cartSubtotal = data.subtotal;
            
            // Check if cart is empty
            if (cartData.length === 0) {
                showEmptyCartState();
            } else {
                // Rebuild cart items display
                rebuildCartDisplay();
                updateCartSummary();
            }
            
            updateGlobalCartCount();
            showToast('Cart refreshed successfully', 'success');
        } else {
            showToast('Error refreshing cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error refreshing cart:', error);
        showToast('Error refreshing cart', 'error');
    })
    .finally(() => {
        // Restore button state
        refreshButton.disabled = false;
        refreshButton.innerHTML = originalContent;
        if (cartItemsContainer) {
            removeLoadingOverlay(cartItemsContainer);
        }
    });
}

// Helper function to rebuild cart display
function rebuildCartDisplay() {
    const cartItemsContainer = document.querySelector('.cart-items');
    if (!cartItemsContainer) return;
    
    let cartHTML = '';
    
    cartData.forEach(item => {
        // Comprehensive image URL handling (matching home.blade.php logic)
        let imageUrl = '';
        
        if (item.image) {
            if (typeof item.image === 'object') {
                // Handle complex nested structure
                const image = item.image;
                if (image.sizes && image.sizes.medium && image.sizes.medium.storage_url) {
                    imageUrl = image.sizes.medium.storage_url;
                } else if (image.sizes && image.sizes.original && image.sizes.original.storage_url) {
                    imageUrl = image.sizes.original.storage_url;
                } else if (image.sizes && image.sizes.large && image.sizes.large.storage_url) {
                    imageUrl = image.sizes.large.storage_url;
                } else if (image.urls && image.urls.medium) {
                    imageUrl = image.urls.medium;
                } else if (image.urls && image.urls.original) {
                    imageUrl = image.urls.original;
                } else if (image.url && typeof image.url === 'string') {
                    imageUrl = image.url;
                } else if (image.path && typeof image.path === 'string') {
                    imageUrl = '{{ asset("storage/") }}/' + image.path;
                }
            } else if (typeof item.image === 'string') {
                // Simple string path
                if (item.image.startsWith('http')) {
                    imageUrl = item.image;
                } else if (item.image.startsWith('/storage/')) {
                    imageUrl = '{{ asset("") }}' + item.image;
                } else {
                    imageUrl = '{{ asset("storage/") }}/' + item.image;
                }
            }
        }
        
        // Fallback to default if still empty
        if (!imageUrl) {
            imageUrl = '{{ asset("assets/img/product/1.png") }}';
        }
        
        cartHTML += `
            <div class="cart-item" data-product-id="${item.id}">
                <div class="row g-3 p-3 align-items-center">
                    <!-- Product Image -->
                    <div class="col-auto">
                        ${imageUrl ? `
                            <img src="${imageUrl}" 
                                 alt="${item.name}" 
                                 class="cart-item-image"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/img/product/1.png') }}';">
                        ` : `
                            <div class="cart-item-image bg-light d-flex align-items-center justify-content-center">
                                <i class="ti ti-photo text-muted"></i>
                            </div>
                        `}
                    </div>
                    
                    <!-- Product Details -->
                    <div class="col">
                        <h6 class="mb-1 fw-semibold">${item.name}</h6>
                        <div class="text-muted small mb-2">
                            Price: ৳${parseFloat(item.price).toFixed(2)}
                        </div>
                        
                        <!-- Mobile: Quantity and Remove -->
                        <div class="d-md-none">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn decrease-btn" data-product-id="${item.id}">
                                        <i class="ti ti-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="99"
                                           data-product-id="${item.id}">
                                    <button type="button" class="quantity-btn increase-btn" data-product-id="${item.id}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                                <button type="button" class="remove-btn" data-product-id="${item.id}" title="Remove item">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                            <div class="mt-2 fw-semibold text-primary">
                                Total: ৳${parseFloat(item.total).toFixed(2)}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Desktop: Quantity -->
                    <div class="col-auto d-none d-md-block">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn decrease-btn" data-product-id="${item.id}">
                                <i class="ti ti-minus"></i>
                            </button>
                            <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="99"
                                   data-product-id="${item.id}">
                            <button type="button" class="quantity-btn increase-btn" data-product-id="${item.id}">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Desktop: Total Price -->
                    <div class="col-auto d-none d-md-block">
                        <div class="fw-semibold text-primary">
                            ৳${parseFloat(item.total).toFixed(2)}
                        </div>
                    </div>
                    
                    <!-- Desktop: Remove Button -->
                    <div class="col-auto d-none d-md-block">
                        <button type="button" class="remove-btn" data-product-id="${item.id}" title="Remove item">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItemsContainer.innerHTML = cartHTML;
}

// Helper function to update cart item data
function updateCartItemData(productId, newQuantity, itemTotal) {
    const item = cartData.find(item => item.id == productId);
    if (item) {
        item.quantity = newQuantity;
        item.total = itemTotal;
    }
    
    // Recalculate subtotal
    cartSubtotal = cartData.reduce((sum, item) => sum + parseFloat(item.total), 0);
}

// Helper function to update cart item display
function updateCartItemDisplay(productId, newQuantity, itemTotal) {
    console.log('updateCartItemDisplay called with:', productId, newQuantity, itemTotal);
    
    const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
    if (!cartItem) {
        console.error('Cart item not found for display update:', productId);
        return;
    }
    
    // Update quantity input
    const quantityInputs = cartItem.querySelectorAll('.quantity-input');
    console.log('Found quantity inputs:', quantityInputs.length);
    
    quantityInputs.forEach((input, index) => {
        console.log(`Updating input ${index} from ${input.value} to ${newQuantity}`);
        input.value = newQuantity;
    });
    
    // Update item total display
    const totalElements = cartItem.querySelectorAll('.fw-semibold.text-primary');
    console.log('Found total elements:', totalElements.length);
    
    totalElements.forEach((element, index) => {
        if (element.textContent.includes('৳')) {
            const oldText = element.textContent;
            element.textContent = `Total: ৳${parseFloat(itemTotal).toFixed(2)}`;
            console.log(`Updated total element ${index} from "${oldText}" to "${element.textContent}"`);
        }
    });
    
    // Update desktop total display
    const desktopTotalElements = cartItem.querySelectorAll('.col-auto.d-none.d-md-block .fw-semibold.text-primary');
    console.log('Found desktop total elements:', desktopTotalElements.length);
    
    desktopTotalElements.forEach((element, index) => {
        const oldText = element.textContent;
        element.textContent = `৳${parseFloat(itemTotal).toFixed(2)}`;
        console.log(`Updated desktop total element ${index} from "${oldText}" to "${element.textContent}"`);
    });
    
    // Add update animation
    cartItem.style.transition = 'all 0.3s ease';
    cartItem.classList.add('updated');
    setTimeout(() => {
        cartItem.classList.remove('updated');
    }, 300);
    
    console.log('updateCartItemDisplay completed');
}

// Helper function to update cart summary
function updateCartSummary() {
    const itemCount = cartData.length;
    const totalQuantity = cartData.reduce((sum, item) => sum + parseInt(item.quantity), 0);
    
    // Update item count in header
    const itemCountElements = document.querySelectorAll('.page-pretitle');
    itemCountElements.forEach(element => {
        if (element.textContent.includes('item')) {
            element.textContent = `${itemCount} ${itemCount === 1 ? 'item' : 'items'} in your cart`;
        }
    });
    
    // Update subtotal
    const subtotalElements = document.querySelectorAll('span');
    subtotalElements.forEach(element => {
        if (element.previousElementSibling && element.previousElementSibling.textContent.includes('Subtotal')) {
            element.textContent = `৳${cartSubtotal.toFixed(2)}`;
        }
    });
    
    // Update shipping
    const shipping = 60;
    const tax = cartSubtotal * 0.05;
    const total = cartSubtotal + shipping + tax;
    
    // Update tax
    const taxElements = document.querySelectorAll('span');
    taxElements.forEach(element => {
        if (element.previousElementSibling && element.previousElementSibling.textContent.includes('Tax')) {
            element.textContent = `৳${tax.toFixed(2)}`;
        }
    });
    
    // Update total
    const totalElements = document.querySelectorAll('.total-row span:last-child');
    totalElements.forEach(element => {
        element.textContent = `৳${total.toFixed(2)}`;
    });
    
    // Update summary item count
    const summaryElements = document.querySelectorAll('span');
    summaryElements.forEach(element => {
        const text = element.textContent;
        if (text.includes('Subtotal') && text.includes('items')) {
            element.textContent = `Subtotal (${itemCount} items)`;
        }
    });
}

// Helper function to show empty cart state
function showEmptyCartState() {
    const container = document.querySelector('.container-xl');
    container.innerHTML = `
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Shopping Cart</h2>
                    <div class="page-pretitle">Your cart is empty</div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('shop.grid') }}" class="btn btn-outline-primary">
                        <i class="ti ti-shopping-bag me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="ti ti-shopping-cart"></i>
            </div>
            <h3 class="mb-3">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('shop.grid') }}" class="btn btn-primary btn-lg">
                <i class="ti ti-shopping-bag me-2"></i>Start Shopping
            </a>
        </div>
    `;
}

// Helper function to update global cart count
function updateGlobalCartCount() {
    const totalQuantity = cartData.reduce((sum, item) => sum + parseInt(item.quantity), 0);
    
    // Update cart icon badge in header/navbar
    const cartCountElements = document.querySelectorAll('.cart-count, .badge');
    cartCountElements.forEach(element => {
        if (element.closest('.cart-icon') || element.closest('[href*="cart"]')) {
            element.textContent = totalQuantity;
            
            // Hide badge if cart is empty
            if (totalQuantity === 0) {
                element.style.display = 'none';
            } else {
                element.style.display = 'inline-block';
            }
        }
    });
    
    // Update global cart count function if available
    if (typeof window.updateCartCount === 'function') {
        window.updateCartCount();
    }
    
    // Dispatch custom event for other components
    window.dispatchEvent(new CustomEvent('cartUpdated', {
        detail: { 
            count: totalQuantity,
            items: cartData,
            subtotal: cartSubtotal 
        }
    }));
}

// Proceed to checkout
function proceedToCheckout() {
    // Check if cart is not empty
    if (cartData.length === 0) {
        showToast('Your cart is empty', 'warning');
        return;
    }
    
    // Redirect to checkout page
    window.location.href = '{{ route("checkout.index") }}';
}

// Enhanced toast notification with better styling
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.custom-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed custom-toast`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    
    const iconMap = {
        success: 'ti-check-circle',
        error: 'ti-x-circle',
        warning: 'ti-alert-triangle',
        info: 'ti-info-circle'
    };
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="ti ${iconMap[type] || iconMap.info} me-2 fs-5"></i>
            <span class="flex-grow-1">${message}</span>
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 150);
        }
    }, 4000);
}

// Helper function to add loading overlay
function addLoadingOverlay(element) {
    if (element.querySelector('.loading-overlay')) return;
    
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay';
    overlay.innerHTML = '<div class="spinner"></div>';
    
    element.style.position = 'relative';
    element.appendChild(overlay);
}

// Helper function to remove loading overlay
function removeLoadingOverlay(element) {
    const overlay = element.querySelector('.loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    updateGlobalCartCount();
    
    // Event delegation for quantity buttons
    document.addEventListener('click', function(e) {
        // Handle decrease button - check for button or icon click
        if (e.target.closest('.decrease-btn') || e.target.classList.contains('decrease-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.decrease-btn') || 
                          (e.target.classList.contains('decrease-btn') ? e.target : null);
            
            if (!button) return;
            
            let productId = button.getAttribute('data-product-id');
            
            // Get the cart item container (not the button)
            const cartItem = button.closest('.cart-item');
            const quantityInput = cartItem ? cartItem.querySelector('.quantity-input') : null;
            
            console.log('Decrease button clicked for product:', productId);
            
            if (quantityInput && productId) {
                const currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    updateQuantity(productId, currentQuantity - 1);
                }
            }
        }
        
        // Handle increase button - check for button or icon click
        if (e.target.closest('.increase-btn') || e.target.classList.contains('increase-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.increase-btn') || 
                          (e.target.classList.contains('increase-btn') ? e.target : null);
            
            if (!button) return;
            
            let productId = button.getAttribute('data-product-id');
            
            // Get the cart item container (not the button)
            const cartItem = button.closest('.cart-item');
            const quantityInput = cartItem ? cartItem.querySelector('.quantity-input') : null;
            
            console.log('Increase button clicked for product:', productId);
            console.log('Cart item found:', cartItem);
            console.log('Quantity input found:', quantityInput);
            console.log('Product ID:', productId);
            
            if (quantityInput && productId) {
                const currentQuantity = parseInt(quantityInput.value);
                console.log('Current quantity:', currentQuantity);
                console.log('Checking if currentQuantity < 99:', currentQuantity < 99);
                
                if (currentQuantity < 99) {
                    console.log('Calling updateQuantity with:', productId, currentQuantity + 1);
                    updateQuantity(productId, currentQuantity + 1);
                } else {
                    console.log('Quantity is already at maximum (99)');
                }
            } else {
                console.log('Missing quantityInput or productId:', {
                    quantityInput: !!quantityInput,
                    productId: !!productId
                });
            }
        }
        
        // Handle remove button
        if (e.target.closest('.remove-btn') || e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.remove-btn') || 
                          (e.target.classList.contains('remove-btn') ? e.target : null);
            
            if (!button) return;
            
            const productId = button.getAttribute('data-product-id');
            if (productId) {
                removeFromCart(productId);
            }
        }
    });
    
    // Event delegation for quantity input changes (only on change, not input)
    document.addEventListener('change', function(e) {
        if (e.target.matches('.quantity-input')) {
            const input = e.target;
            const productId = input.getAttribute('data-product-id');
            
            if (!productId) {
                console.error('Product ID not found on quantity input');
                return;
            }
            
            const quantity = parseInt(input.value);
            
            if (isNaN(quantity)) {
                input.value = 1;
                return;
            }
            
            console.log('Quantity input changed for product:', productId, 'to:', quantity);
            
            if (quantity >= 1 && quantity <= 99) {
                updateQuantity(productId, quantity);
            } else if (quantity < 1) {
                input.value = 1;
                updateQuantity(productId, 1);
            } else if (quantity > 99) {
                input.value = 99;
                updateQuantity(productId, 99);
            }
        }
    });
});
</script>
@endpush
