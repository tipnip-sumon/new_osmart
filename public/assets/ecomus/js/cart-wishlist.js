/**
 * Cart and Wishlist Management for Ecomus Theme
 * Dynamic functionality for cart and wishlist operations
 */

class CartWishlistManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currencySymbol = window.currencySymbol || '৳'; // Default to BDT symbol
        this.init();
    }

    init() {
        this.loadCartCount();
        this.loadWishlistCount();
        this.loadCartItems();
        this.bindEvents();
        
        // Auto-refresh counts every 30 seconds
        setInterval(() => {
            this.loadCartCount();
            this.loadWishlistCount();
        }, 30000);
    }

    bindEvents() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.quick-add') || e.target.closest('.add-to-cart') || e.target.closest('[data-action="add-to-cart"]')) {
                e.preventDefault();
                e.stopPropagation();
                this.handleAddToCart(e);
            }
        });

        // Add to wishlist buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.wishlist') || e.target.closest('.add-to-wishlist') || e.target.closest('[data-action="add-to-wishlist"]')) {
                e.preventDefault();
                e.stopPropagation();
                this.handleAddToWishlist(e);
            }
        });

        // Cart quantity controls
        document.addEventListener('click', (e) => {
            if (e.target.closest('.plus-btn')) {
                e.preventDefault();
                this.updateCartQuantity(e.target.closest('.plus-btn'), 'increase');
            }
            if (e.target.closest('.minus-btn')) {
                e.preventDefault();
                this.updateCartQuantity(e.target.closest('.minus-btn'), 'decrease');
            }
        });

        // Remove from cart
        document.addEventListener('click', (e) => {
            if (e.target.closest('.tf-mini-cart-remove')) {
                e.preventDefault();
                this.removeFromCart(e.target.closest('.tf-mini-cart-remove'));
            }
        });

        // Cart modal events
        document.getElementById('shoppingCart')?.addEventListener('shown.bs.modal', () => {
            this.loadCartItems();
        });
    }

    async loadCartCount() {
        try {
            const response = await fetch('/cart/count');
            const data = await response.json();
            
            // Update cart count
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                const count = data.count || 0;
                cartCountElement.textContent = count;
                
                // Show/hide badge based on count
                if (count > 0) {
                    cartCountElement.style.display = 'inline-block';
                    cartCountElement.classList.add('has-items');
                } else {
                    cartCountElement.style.display = 'none';
                    cartCountElement.classList.remove('has-items');
                }
            }
            
            console.log('Cart count updated:', data.count);
        } catch (error) {
            console.error('Error loading cart count:', error);
            // Set default to 0 and hide if cart route doesn't exist
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = '0';
                cartCountElement.style.display = 'none';
                cartCountElement.classList.remove('has-items');
            }
        }
    }

    async loadWishlistCount() {
        try {
            const response = await fetch('/wishlist/count');
            const data = await response.json();
            
            // Update wishlist count
            const wishlistCountElements = document.querySelectorAll('#wishlist-count, .wishlist-count');
            wishlistCountElements.forEach(element => {
                const count = data.count || 0;
                element.textContent = count;
                
                // Show/hide badge based on count
                if (count > 0) {
                    element.style.display = 'inline-block';
                    element.classList.add('has-items');
                } else {
                    element.style.display = 'none';
                    element.classList.remove('has-items');
                }
            });
            
            console.log('Wishlist count updated:', data.count);
        } catch (error) {
            console.error('Error loading wishlist count:', error);
            // Set default to 0 and hide if wishlist route doesn't exist
            const wishlistCountElements = document.querySelectorAll('#wishlist-count, .wishlist-count');
            wishlistCountElements.forEach(element => {
                element.textContent = '0';
                element.style.display = 'none';
                element.classList.remove('has-items');
            });
        }
    }

    async loadCartItems() {
        try {
            const response = await fetch('/cart/items');
            const data = await response.json();
            
            if (data.success) {
                this.renderCartItems(data.items, data.subtotal);
            }
        } catch (error) {
            console.error('Error loading cart items:', error);
            // If cart items route doesn't exist, show empty cart
            this.renderEmptyCart();
        }
    }

    formatCurrency(amount) {
        return this.currencySymbol + parseFloat(amount).toFixed(2);
    }

    renderCartItems(items, subtotal = 0) {
        const cartContainer = document.getElementById('cart-items-container');
        if (!cartContainer) return;

        // Clear container first
        cartContainer.innerHTML = '';

        if (!items || items.length === 0) {
            this.renderEmptyCart();
            return;
        }

        // Create items HTML
        const itemsHtml = items.map(item => `
            <div class="tf-mini-cart-item" data-product-id="${item.id}">
                <div class="tf-mini-cart-image">
                    <a href="/products/${item.slug || item.id}">
                        <img src="${item.image || '/assets/ecomus/images/products/default.jpg'}" 
                             alt="${item.name}"
                             onerror="this.src='/assets/ecomus/images/products/default.jpg'">
                    </a>
                </div>
                <div class="tf-mini-cart-info">
                    <a class="title link" href="/products/${item.slug || item.id}">${item.name}</a>
                    ${item.variant ? `<div class="meta-variant">${item.variant}</div>` : ''}
                    <div class="price fw-6">${this.formatCurrency(item.price)}</div>
                    <div class="tf-mini-cart-btns">
                        <div class="wg-quantity small">
                            <button type="button" class="btn-quantity minus-btn">-</button>
                            <input type="text" name="quantity" value="${item.quantity}" readonly>
                            <button type="button" class="btn-quantity plus-btn">+</button>
                        </div>
                        <div class="tf-mini-cart-remove" data-product-id="${item.id}">Remove</div>
                    </div>
                </div>
            </div>
        `).join('');

        cartContainer.innerHTML = itemsHtml;

        // Update subtotal
        const subtotalElement = document.getElementById('cart-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = this.formatCurrency(subtotal);
        }

        // Update progress bar (example: free shipping at $75)
        const progressBar = document.querySelector('.tf-progress-bar span');
        const progressMsg = document.querySelector('.tf-progress-msg');
        if (progressBar && progressMsg) {
            const freeShippingThreshold = 75;
            const remaining = Math.max(0, freeShippingThreshold - subtotal);
            const percentage = Math.min(100, (subtotal / freeShippingThreshold) * 100);
            
            progressBar.style.width = `${percentage}%`;
            
            if (remaining > 0) {
                progressMsg.innerHTML = `Buy <span class="price fw-6">${this.formatCurrency(remaining)}</span> more to enjoy <span class="fw-6">Free Shipping</span>`;
            } else {
                progressMsg.innerHTML = `<span class="fw-6 text-success">🎉 You qualify for free shipping!</span>`;
            }
        }
    }

    renderEmptyCart() {
        const cartContainer = document.getElementById('cart-items-container');
        if (!cartContainer) return;

        cartContainer.innerHTML = `
            <div class="tf-mini-cart-empty text-center py-4">
                <div class="mb-3">
                    <i class="icon icon-bag" style="font-size: 3rem; color: #ddd;"></i>
                </div>
                <h5 class="mb-2">Your cart is empty</h5>
                <p class="text-muted mb-3">Start shopping to fill your cart</p>
                <a href="/shop/index" class="tf-btn btn-fill radius-3">Continue Shopping</a>
            </div>
        `;

        // Reset subtotal
        const subtotalElement = document.getElementById('cart-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = this.formatCurrency(0);
        }

        // Reset progress bar
        const progressBar = document.querySelector('.tf-progress-bar span');
        const progressMsg = document.querySelector('.tf-progress-msg');
        if (progressBar && progressMsg) {
            progressBar.style.width = '0%';
            progressMsg.innerHTML = `Buy <span class="price fw-6">${this.formatCurrency(75.00)}</span> more to enjoy <span class="fw-6">Free Shipping</span>`;
        }
    }

    async handleAddToCart(event) {
        const button = event.target.closest('button') || event.target.closest('a');
        const productCard = button.closest('.card-product') || button.closest('[data-product-id]');
        
        if (!productCard) {
            console.log('Product card not found');
            return;
        }

        const productId = productCard.dataset.productId || 
                         productCard.querySelector('[data-product-id]')?.dataset.productId;
        
        if (!productId) {
            console.error('Product ID not found');
            console.log('Product card:', productCard);
            return;
        }

        console.log('Adding product to cart:', productId);

        // Add loading state
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="icon icon-loading"></i>';
        button.disabled = true;

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            });

            const data = await response.json();
            console.log('Cart add response:', data);

            if (data.success) {
                // Show success message
                this.showToast(data.message || 'Product added to cart!', 'success');
                
                // Update cart count and items
                await this.loadCartCount();
                await this.loadCartItems();
                
                // Add success animation
                button.classList.add('added-to-cart');
                setTimeout(() => button.classList.remove('added-to-cart'), 2000);
            } else {
                this.showToast(data.message || 'Failed to add product to cart', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showToast('Error adding product to cart', 'error');
        } finally {
            // Restore button
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }

    async handleAddToWishlist(event) {
        const button = event.target.closest('button') || event.target.closest('a');
        const productCard = button.closest('.card-product') || button.closest('[data-product-id]');
        
        if (!productCard) return;

        const productId = productCard.dataset.productId || 
                         productCard.querySelector('[data-product-id]')?.dataset.productId;
        
        if (!productId) {
            console.error('Product ID not found');
            return;
        }

        // Add loading state
        const heartIcon = button.querySelector('.icon-heart') || button.querySelector('i');
        const originalClass = heartIcon?.className;

        try {
            const response = await fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                
                // Update heart icon
                if (heartIcon) {
                    if (data.action === 'added') {
                        heartIcon.className = heartIcon.className.replace('icon-heart', 'icon-heart-filled');
                        button.classList.add('active');
                    } else {
                        heartIcon.className = heartIcon.className.replace('icon-heart-filled', 'icon-heart');
                        button.classList.remove('active');
                    }
                }
                
                // Update wishlist count
                this.loadWishlistCount();
            } else {
                this.showToast(data.message || 'Failed to update wishlist', 'error');
            }
        } catch (error) {
            console.error('Error updating wishlist:', error);
            // If wishlist routes don't exist, just show a message
            this.showToast('Wishlist feature coming soon!', 'info');
        }
    }

    async updateCartQuantity(button, action) {
        const cartItem = button.closest('.tf-mini-cart-item');
        const productId = cartItem.dataset.productId;
        const quantityInput = cartItem.querySelector('input[name="quantity"]');
        const currentQuantity = parseInt(quantityInput.value);
        
        const newQuantity = action === 'increase' ? currentQuantity + 1 : Math.max(1, currentQuantity - 1);
        
        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: newQuantity
                })
            });

            const data = await response.json();

            if (data.success) {
                quantityInput.value = newQuantity;
                this.loadCartCount();
                this.loadCartItems();
            }
        } catch (error) {
            console.error('Error updating cart quantity:', error);
        }
    }

    async removeFromCart(button) {
        const cartItem = button.closest('.tf-mini-cart-item');
        const productId = cartItem.dataset.productId;

        try {
            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: productId
                })
            });

            const data = await response.json();

            if (data.success) {
                cartItem.remove();
                this.showToast('Product removed from cart', 'success');
                this.loadCartCount();
                this.loadCartItems();
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    }

    showToast(message, type = 'info') {
        // Use toastr if available
        if (window.toastr) {
            toastr[type](message);
        } else {
            // Fallback: simple alert
            alert(message);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.cartWishlistManager = new CartWishlistManager();
});

// Global functions for backward compatibility
window.updateCartCount = function() {
    if (window.cartWishlistManager) {
        window.cartWishlistManager.loadCartCount();
    }
};

window.updateWishlistCount = function() {
    if (window.cartWishlistManager) {
        window.cartWishlistManager.loadWishlistCount();
    }
};

window.addToCart = function(productId, productName, price) {
    if (window.cartWishlistManager) {
        // Create a fake event for the product
        const fakeEvent = {
            target: {
                closest: () => ({
                    dataset: { productId: productId.toString() }
                })
            }
        };
        window.cartWishlistManager.handleAddToCart(fakeEvent);
    }
};

window.toggleWishlist = function(productId) {
    if (window.cartWishlistManager) {
        // Create a fake event for the product
        const fakeEvent = {
            target: {
                closest: () => ({
                    dataset: { productId: productId.toString() },
                    querySelector: () => ({ className: 'icon-heart' })
                })
            }
        };
        window.cartWishlistManager.handleAddToWishlist(fakeEvent);
    }
};