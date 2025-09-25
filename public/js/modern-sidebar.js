/**
 * Modern Admin Sidebar JavaScript
 * Enhanced interactive functionality for the admin sidebar
 */

class ModernSidebar {
    constructor() {
        this.sidebar = document.getElementById('modernSidebar');
        this.sidebarToggle = document.getElementById('sidebarToggle');
        this.sidebarOverlay = document.getElementById('sidebarOverlay');
        this.mainContent = document.querySelector('.main-content');
        
        this.isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        this.isMobile = window.innerWidth <= 768;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupSubmenuToggle();
        this.loadStoredState();
        this.updateStats();
        this.setupRealtimeUpdates();
        
        // Initialize state
        if (this.isMobile) {
            this.sidebar.classList.add('mobile-hidden');
        } else if (this.isCollapsed) {
            this.collapse();
        }
    }
    
    setupEventListeners() {
        // Toggle sidebar
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => {
                this.toggle();
            });
        }
        
        // Mobile overlay
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => {
                this.hideMobile();
            });
        }
        
        // Handle window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });
        
        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobile && this.sidebar.classList.contains('mobile-active')) {
                this.hideMobile();
            }
        });
        
        // Mobile header toggle (if exists)
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                this.showMobile();
            });
        }
    }
    
    setupSubmenuToggle() {
        const submenuItems = document.querySelectorAll('.nav-item.has-submenu');
        
        submenuItems.forEach(item => {
            const link = item.querySelector('.nav-link');
            
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (this.isCollapsed && !this.isMobile) {
                    return; // Don't open submenus when collapsed on desktop
                }
                
                // Close other open submenus
                submenuItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('open')) {
                        otherItem.classList.remove('open');
                    }
                });
                
                // Toggle current submenu
                item.classList.toggle('open');
                
                // Store open state
                const menuId = link.dataset.menu || link.querySelector('.nav-text').textContent;
                this.storeSubmenuState(menuId, item.classList.contains('open'));
            });
        });
    }
    
    toggle() {
        if (this.isMobile) {
            this.toggleMobile();
        } else {
            this.toggleCollapse();
        }
    }
    
    toggleCollapse() {
        this.isCollapsed = !this.isCollapsed;
        
        if (this.isCollapsed) {
            this.collapse();
        } else {
            this.expand();
        }
        
        // Store state
        localStorage.setItem('sidebarCollapsed', this.isCollapsed);
    }
    
    collapse() {
        this.sidebar.classList.add('collapsed');
        if (this.mainContent) {
            this.mainContent.classList.add('sidebar-collapsed');
        }
        
        // Close all submenus when collapsing
        document.querySelectorAll('.nav-item.has-submenu.open').forEach(item => {
            item.classList.remove('open');
        });
        
        this.isCollapsed = true;
    }
    
    expand() {
        this.sidebar.classList.remove('collapsed');
        if (this.mainContent) {
            this.mainContent.classList.remove('sidebar-collapsed');
        }
        
        // Restore submenu states
        this.restoreSubmenuStates();
        
        this.isCollapsed = false;
    }
    
    toggleMobile() {
        if (this.sidebar.classList.contains('mobile-active')) {
            this.hideMobile();
        } else {
            this.showMobile();
        }
    }
    
    showMobile() {
        this.sidebar.classList.remove('mobile-hidden');
        this.sidebar.classList.add('mobile-active');
        this.sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    hideMobile() {
        this.sidebar.classList.remove('mobile-active');
        this.sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
        
        setTimeout(() => {
            this.sidebar.classList.add('mobile-hidden');
        }, 300);
    }
    
    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 768;
        
        if (wasMobile && !this.isMobile) {
            // Switching from mobile to desktop
            this.sidebar.classList.remove('mobile-active', 'mobile-hidden');
            this.sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
            
            if (this.isCollapsed) {
                this.collapse();
            }
        } else if (!wasMobile && this.isMobile) {
            // Switching from desktop to mobile
            this.sidebar.classList.remove('collapsed');
            this.sidebar.classList.add('mobile-hidden');
            if (this.mainContent) {
                this.mainContent.classList.remove('sidebar-collapsed');
            }
        }
    }
    
    loadStoredState() {
        if (!this.isMobile && this.isCollapsed) {
            this.collapse();
        }
        
        this.restoreSubmenuStates();
    }
    
    storeSubmenuState(menuId, isOpen) {
        const openMenus = JSON.parse(localStorage.getItem('openSubmenus') || '{}');
        openMenus[menuId] = isOpen;
        localStorage.setItem('openSubmenus', JSON.stringify(openMenus));
    }
    
    restoreSubmenuStates() {
        if (this.isCollapsed) return;
        
        const openMenus = JSON.parse(localStorage.getItem('openSubmenus') || '{}');
        
        Object.entries(openMenus).forEach(([menuId, isOpen]) => {
            if (isOpen) {
                const menuItem = document.querySelector(`[data-menu="${menuId}"]`)?.closest('.nav-item.has-submenu');
                if (menuItem) {
                    menuItem.classList.add('open');
                }
            }
        });
    }
    
    updateStats() {
        // Update realtime stats in sidebar
        this.fetchStats().then(data => {
            if (data.success) {
                const todayOrdersEl = document.getElementById('todayOrders');
                const todayRevenueEl = document.getElementById('todayRevenue');
                
                if (todayOrdersEl) {
                    this.animateValue(todayOrdersEl, parseInt(todayOrdersEl.textContent), data.todayOrders, 1000);
                }
                
                if (todayRevenueEl) {
                    todayRevenueEl.textContent = '$' + data.todayRevenue;
                }
            }
        }).catch(error => {
            console.log('Stats update failed:', error);
        });
    }
    
    setupRealtimeUpdates() {
        // Update stats every 30 seconds
        setInterval(() => {
            this.updateStats();
        }, 30000);
        
        // Update notification badges
        this.updateNotificationBadges();
        
        // Setup notification badge updates every minute
        setInterval(() => {
            this.updateNotificationBadges();
        }, 60000);
    }
    
    updateNotificationBadges() {
        // Update order badge (pending orders)
        const orderBadge = document.querySelector('.nav-item:has([href*="orders"]) .nav-badge');
        if (orderBadge) {
            // Mock data - replace with actual API call
            const pendingOrders = Math.floor(Math.random() * 10) + 1;
            orderBadge.textContent = pendingOrders;
            orderBadge.style.display = pendingOrders > 0 ? 'block' : 'none';
        }
        
        // Update vendor badge (pending vendors)
        const vendorBadge = document.querySelector('.nav-item:has([href*="vendors"]) .nav-badge');
        if (vendorBadge) {
            // Mock data - replace with actual API call
            const pendingVendors = Math.floor(Math.random() * 5) + 1;
            vendorBadge.textContent = pendingVendors;
            vendorBadge.style.display = pendingVendors > 0 ? 'block' : 'none';
        }
    }
    
    async fetchStats() {
        try {
            const response = await fetch('/admin/stats/realtime', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Failed to fetch stats:', error);
            return { success: false };
        }
    }
    
    animateValue(element, start, end, duration) {
        if (start === end) return;
        
        const range = end - start;
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const value = Math.floor(start + (range * this.easeOutCubic(progress)));
            element.textContent = value;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }
    
    easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }
    
    // Public API
    setActiveMenuItem(menuId) {
        // Remove active class from all items
        document.querySelectorAll('.nav-item.active').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to specified item
        const menuItem = document.querySelector(`[data-menu="${menuId}"]`)?.closest('.nav-item');
        if (menuItem) {
            menuItem.classList.add('active');
            
            // If it's a submenu item, also open the parent
            const parentSubmenu = menuItem.closest('.nav-submenu');
            if (parentSubmenu) {
                const parentItem = parentSubmenu.closest('.nav-item.has-submenu');
                if (parentItem) {
                    parentItem.classList.add('open');
                }
            }
        }
    }
    
    highlightMenuItem(path) {
        // Auto-highlight menu item based on current path
        const menuLinks = document.querySelectorAll('.nav-link[href], .nav-submenu a[href]');
        
        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && (path === href || path.startsWith(href + '/'))) {
                const menuItem = link.closest('.nav-item');
                if (menuItem) {
                    menuItem.classList.add('active');
                    
                    // If it's in a submenu, open the parent
                    const parentSubmenu = link.closest('.nav-submenu');
                    if (parentSubmenu) {
                        const parentItem = parentSubmenu.closest('.nav-item.has-submenu');
                        if (parentItem) {
                            parentItem.classList.add('open');
                        }
                    }
                }
            }
        });
    }
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.modernSidebar = new ModernSidebar();
    
    // Auto-highlight current menu item
    const currentPath = window.location.pathname;
    window.modernSidebar.highlightMenuItem(currentPath);
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernSidebar;
}
