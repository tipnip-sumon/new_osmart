/**
 * Wishlist functionality for Ecomus theme
 * Handles adding/removing items from wishlist with AJAX
 */

class WishlistManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateWishlistCount();
    }

    bindEvents() {
        // Handle wishlist toggle buttons (heart icons)
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-icon-action.wishlist') || e.target.closest('.wishlist-toggle')) {
                e.preventDefault();
                const button = e.target.closest('.btn-icon-action') || e.target.closest('.wishlist-toggle');
                const productId = button.dataset.productId || button.closest('[data-product-id]')?.dataset.productId;
                
                if (productId) {
                    this.toggleWishlist(productId, button);
                }
            }
        });
    }

    async toggleWishlist(productId, button) {
        try {
            // Add loading state
            button.classList.add('loading');
            button.style.pointerEvents = 'none';

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
                // Update button state
                this.updateButtonState(button, data.action);
                
                // Update wishlist count
                this.updateWishlistCount();
                
                // Show notification
                this.showNotification(data.message, 'success');
            } else {
                throw new Error(data.message || 'Failed to update wishlist');
            }
        } catch (error) {
            console.error('Wishlist error:', error);
            this.showNotification('Failed to update wishlist', 'error');
        } finally {
            // Remove loading state
            button.classList.remove('loading');
            button.style.pointerEvents = 'auto';
        }
    }

    updateButtonState(button, action) {
        const heartIcon = button.querySelector('.icon-heart');
        const deleteIcon = button.querySelector('.icon-delete');
        const tooltip = button.querySelector('.tooltip');
        
        if (action === 'added') {
            button.classList.add('active', 'added');
            if (tooltip) tooltip.textContent = 'Remove from Wishlist';
            // Toggle icon visibility if both exist
            if (heartIcon && deleteIcon) {
                heartIcon.style.display = 'none';
                deleteIcon.style.display = 'block';
            }
        } else {
            button.classList.remove('active', 'added');
            if (tooltip) tooltip.textContent = 'Add to Wishlist';
            // Toggle icon visibility if both exist
            if (heartIcon && deleteIcon) {
                heartIcon.style.display = 'block';
                deleteIcon.style.display = 'none';
            }
        }
    }

    async updateWishlistCount() {
        try {
            const response = await fetch('/wishlist/count');
            const data = await response.json();
            
            // Update all wishlist count elements
            const countElements = document.querySelectorAll('.wishlist-count, .nav-wishlist .count-box, .toolbar-count');
            countElements.forEach(element => {
                element.textContent = data.count;
                element.style.display = data.count > 0 ? 'block' : 'none';
            });
        } catch (error) {
            console.error('Failed to update wishlist count:', error);
        }
    }

    showNotification(message, type = 'info') {
        // Try to use existing notification system
        if (typeof window.showNotification === 'function') {
            window.showNotification(message, type);
            return;
        }

        // Fallback to simple notification
        this.createSimpleNotification(message, type);
    }

    createSimpleNotification(message, type) {
        // Remove existing notifications
        const existing = document.querySelector('.wishlist-notification');
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `wishlist-notification wishlist-notification--${type}`;
        notification.innerHTML = `
            <div class="wishlist-notification__content">
                <span class="wishlist-notification__icon">
                    ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
                </span>
                <span class="wishlist-notification__message">${message}</span>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-size: 14px;
            max-width: 300px;
        `;

        // Add to DOM
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remove after delay
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Product card wishlist functionality
class ProductWishlist {
    constructor() {
        this.init();
    }

    init() {
        this.bindProductCardEvents();
    }

    bindProductCardEvents() {
        // Handle product card wishlist buttons
        document.addEventListener('click', (e) => {
            const wishlistBtn = e.target.closest('.box-icon.wishlist, .btn-icon-action.wishlist');
            if (wishlistBtn) {
                e.preventDefault();
                e.stopPropagation();
                
                const productCard = wishlistBtn.closest('.card-product');
                const productId = this.getProductId(productCard, wishlistBtn);
                
                if (productId) {
                    this.handleWishlistClick(productId, wishlistBtn, productCard);
                }
            }
        });
    }

    getProductId(productCard, button) {
        // Try multiple methods to get product ID
        return button.dataset.productId || 
               productCard?.dataset.productId || 
               button.closest('form')?.querySelector('input[name="product_id"]')?.value ||
               button.closest('[data-product-id]')?.dataset.productId;
    }

    async handleWishlistClick(productId, button, productCard) {
        try {
            button.classList.add('loading');
            
            const response = await fetch(`/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.updateWishlistButton(button, data.action);
                this.showFeedback(data.message, data.action === 'added' ? 'success' : 'info');
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Wishlist error:', error);
            this.showFeedback('Failed to update wishlist', 'error');
        } finally {
            button.classList.remove('loading');
        }
    }

    updateWishlistButton(button, action) {
        const icon = button.querySelector('.icon');
        const tooltip = button.querySelector('.tooltip');
        
        if (action === 'added') {
            button.classList.add('active');
            if (icon) icon.className = 'icon icon-delete';
            if (tooltip) tooltip.textContent = 'Remove from Wishlist';
        } else {
            button.classList.remove('active');
            if (icon) icon.className = 'icon icon-heart';
            if (tooltip) tooltip.textContent = 'Add to Wishlist';
        }
    }

    showFeedback(message, type) {
        if (window.wishlistManager) {
            window.wishlistManager.showNotification(message, type);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize wishlist functionality
    window.wishlistManager = new WishlistManager();
    window.productWishlist = new ProductWishlist();
    
    console.log('Wishlist functionality initialized');
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { WishlistManager, ProductWishlist };
}