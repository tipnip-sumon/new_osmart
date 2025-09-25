/**
 * Global Wishlist Management System
 * Handles wishlist toggle functionality with visual feedback
 */

class WishlistManager {
    constructor() {
        this.apiUrl = '/wishlist/toggle/';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        // Initialize all wishlist buttons on page load
        this.initializeWishlistButtons();
        
        // Set up event listeners for dynamic content with preventDefault to avoid conflicts
        document.addEventListener('click', (e) => {
            const wishlistButton = e.target.closest('.wishlist-btn') || 
                                 e.target.closest('[data-wishlist]') || 
                                 e.target.closest('.add-to-wishlist');
            
            if (wishlistButton) {
                e.preventDefault();
                e.stopImmediatePropagation(); // Stop other handlers
                this.handleWishlistClick(e);
            }
        }, true); // Use capture phase to run before other handlers
        
        // Load initial wishlist state
        this.loadWishlistState();
    }

    initializeWishlistButtons() {
        // Find all wishlist buttons and set up proper attributes
        const buttons = document.querySelectorAll('.wishlist-btn, [data-wishlist], .add-to-wishlist');
        buttons.forEach(button => {
            if (!button.dataset.initialized) {
                button.dataset.initialized = 'true';
                // Ensure button has proper structure
                this.ensureButtonStructure(button);
            }
        });
    }

    ensureButtonStructure(button) {
        // Make sure button has a heart icon
        if (!button.querySelector('i')) {
            const icon = document.createElement('i');
            icon.className = 'ti ti-heart';
            button.insertBefore(icon, button.firstChild);
        }
    }

    handleWishlistClick(event) {
        const button = event.target.closest('.wishlist-btn') || 
                      event.target.closest('[data-wishlist]') || 
                      event.target.closest('.add-to-wishlist');
        
        if (!button) return;

        // Get product ID from various possible attributes
        const productId = button.dataset.productId || 
                         button.dataset.product || 
                         button.dataset.id ||
                         this.extractProductIdFromOnclick(button);

        if (!productId) {
            console.error('No product ID found for wishlist button');
            return;
        }

        this.toggleWishlist(productId, button);
    }

    extractProductIdFromOnclick(button) {
        const onclick = button.getAttribute('onclick');
        if (onclick) {
            const match = onclick.match(/\d+/);
            return match ? match[0] : null;
        }
        return null;
    }

    async toggleWishlist(productId, button) {
        if (!productId || !button) {
            console.error('Missing product ID or button element');
            return;
        }

        // Disable button during request
        const originalDisabled = button.disabled;
        button.disabled = true;

        // Get current state
        const heartIcon = button.querySelector('i');
        const isCurrentlyInWishlist = heartIcon?.classList.contains('ti-heart-filled') || 
                                     button.classList.contains('active') ||
                                     button.classList.contains('btn-danger');

        try {
            // Show loading state
            this.showLoadingState(button, heartIcon);

            const response = await fetch(this.apiUrl + productId, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Update visual state based on API response
                this.updateButtonState(button, heartIcon, data.action);
                
                // Show success message
                this.showNotification(data.message, 'success');
                
                // Update wishlist count if function exists
                if (typeof updateSidebarWishlistCount === 'function') {
                    updateSidebarWishlistCount();
                }
                
                // Trigger custom event for other components
                this.triggerWishlistEvent(productId, data.action);
                
            } else {
                throw new Error(data.message || 'Failed to update wishlist');
            }

        } catch (error) {
            console.error('Wishlist toggle error:', error);
            
            // Revert to original state on error
            this.updateButtonState(button, heartIcon, isCurrentlyInWishlist ? 'added' : 'removed');
            
            // Show error message
            this.showNotification(error.message || 'Error updating wishlist', 'error');
            
        } finally {
            // Re-enable button
            button.disabled = originalDisabled;
        }
    }

    showLoadingState(button, heartIcon) {
        if (heartIcon) {
            heartIcon.className = 'ti ti-loader';
            heartIcon.style.animation = 'spin 1s linear infinite';
        }
        
        // Add loading class to button
        button.classList.add('wishlist-loading');
    }

    updateButtonState(button, heartIcon, action) {
        if (!heartIcon) return;

        // Remove loading state
        heartIcon.style.animation = '';
        button.classList.remove('wishlist-loading');

        if (action === 'added') {
            // Added to wishlist - show filled heart
            heartIcon.className = 'ti ti-heart-filled';
            button.classList.add('active', 'btn-danger');
            button.classList.remove('btn-outline-danger', 'btn-outline-secondary');
            
            // Add visual feedback animation
            this.animateButton(button, 'added');
            
        } else {
            // Removed from wishlist - show empty heart
            heartIcon.className = 'ti ti-heart';
            button.classList.remove('active', 'btn-danger');
            button.classList.add('btn-outline-danger');
            
            // Add visual feedback animation
            this.animateButton(button, 'removed');
        }
    }

    animateButton(button, action) {
        // Add pulse animation
        button.style.transform = 'scale(1.1)';
        button.style.transition = 'transform 0.2s ease';
        
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 200);

        // Add color flash effect
        if (action === 'added') {
            button.style.backgroundColor = '#dc3545';
            button.style.borderColor = '#dc3545';
            button.style.color = 'white';
        }
    }

    loadWishlistState() {
        // For guest users, load from localStorage
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        
        // Update button states based on localStorage
        document.querySelectorAll('.wishlist-btn, [data-wishlist], .add-to-wishlist').forEach(button => {
            const productId = parseInt(
                button.dataset.productId || 
                button.dataset.product || 
                button.dataset.id ||
                this.extractProductIdFromOnclick(button)
            );
            
            if (productId && wishlist.includes(productId)) {
                const heartIcon = button.querySelector('i');
                this.updateButtonState(button, heartIcon, 'added');
            }
        });
    }

    showNotification(message, type = 'info') {
        // Use toastr if available (primary notification system)
        if (typeof toastr !== 'undefined') {
            // Clear any existing toasts first to prevent duplicates
            toastr.clear();
            
            // Show the message with appropriate type
            switch(type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning(message);
                    break;
                default:
                    toastr.info(message);
            }
            return;
        }

        // Fallback to custom toast if toastr is not available
        this.showCustomToast(message, type);
    }

    showCustomToast(message, type) {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('wishlist-toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'wishlist-toast-container';
            toastContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                pointer-events: none;
            `;
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'}`;
        toast.style.cssText = `
            min-width: 250px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
            pointer-events: auto;
        `;
        toast.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>${message}</span>
                <button type="button" class="btn-close" style="margin-left: 10px;"></button>
            </div>
        `;

        // Add animation styles if not exists
        if (!document.getElementById('wishlist-toast-styles')) {
            const style = document.createElement('style');
            style.id = 'wishlist-toast-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .wishlist-loading {
                    pointer-events: none;
                    opacity: 0.7;
                }
            `;
            document.head.appendChild(style);
        }

        toastContainer.appendChild(toast);

        // Handle close button
        const closeBtn = toast.querySelector('.btn-close');
        closeBtn.addEventListener('click', () => {
            this.removeToast(toast);
        });

        // Auto remove after 3 seconds
        setTimeout(() => {
            this.removeToast(toast);
        }, 3000);
    }

    removeToast(toast) {
        if (toast && toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
    }

    triggerWishlistEvent(productId, action) {
        // Dispatch custom event for other components to listen to
        const event = new CustomEvent('wishlistUpdated', {
            detail: { productId, action }
        });
        document.dispatchEvent(event);
        
        // Update localStorage for guest users
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        productId = parseInt(productId);
        
        if (action === 'added' && !wishlist.includes(productId)) {
            wishlist.push(productId);
        } else if (action === 'removed') {
            wishlist = wishlist.filter(id => id !== productId);
        }
        
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
}

// Initialize the wishlist manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.wishlistManager = new WishlistManager();
});

// Global function for backward compatibility
window.addToWishlist = function(productId) {
    if (window.wishlistManager) {
        // Find the button that was clicked
        const button = event.target.closest('.wishlist-btn') || 
                      event.target.closest('[data-wishlist]') || 
                      event.target.closest('.add-to-wishlist');
        
        if (button) {
            window.wishlistManager.toggleWishlist(productId, button);
        }
    }
};

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = WishlistManager;
}
