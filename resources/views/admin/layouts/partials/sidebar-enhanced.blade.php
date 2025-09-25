<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        @if(Auth::user()->role === 'vendor')
        <a href="{{ route('vendor.dashboard') }}" class="header-logo">
        @else
        <a href="{{ route('admin.dashboard') }}" class="header-logo">
        @endif
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('admin-assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin-assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">
        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu">
                @if(Auth::user()->role === 'vendor')
                    {{-- Vendor Menu Items (Static for now - can be made dynamic later) --}}
                    <!-- Dashboard -->
                    <li class="slide__category"><span class="category-name">Vendor Panel</span></li>
                    <li class="slide">
                        <a href="{{ route('vendor.dashboard') }}" class="side-menu__item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                            <i class="bx bx-home side-menu__icon"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="slide">
                        <a href="{{ route('vendor.profile') }}" class="side-menu__item {{ request()->routeIs('vendor.profile*') ? 'active' : '' }}">
                            <i class="bx bx-user side-menu__icon"></i>
                            <span class="side-menu__label">My Profile</span>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.products.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}">
                            <i class="bx bx-box side-menu__icon"></i>
                            <span class="side-menu__label">Products</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.products.index') }}" class="side-menu__item {{ request()->routeIs('vendor.products.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Products
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.products.create') }}" class="side-menu__item {{ request()->routeIs('vendor.products.create') ? 'active' : '' }}">
                                    <i class="bx bx-plus"></i> Add Product
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Orders -->
                    <li class="slide has-sub {{ request()->routeIs('vendor.orders.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
                            <i class="bx bx-shopping-bag side-menu__icon"></i>
                            <span class="side-menu__label">Orders</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('vendor.orders.index') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Orders
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.orders.pending') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.pending') ? 'active' : '' }}">
                                    <i class="bx bx-time"></i> Pending Orders
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('vendor.orders.completed') }}" class="side-menu__item {{ request()->routeIs('vendor.orders.completed') ? 'active' : '' }}">
                                    <i class="bx bx-check"></i> Completed Orders
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reports -->
                    <li class="slide">
                        <a href="{{ route('vendor.reports.index') }}" class="side-menu__item {{ request()->routeIs('vendor.reports.*') ? 'active' : '' }}">
                            <i class="bx bx-bar-chart side-menu__icon"></i>
                            <span class="side-menu__label">Sales Reports</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="slide">
                        <a href="{{ route('vendor.settings.index') }}" class="side-menu__item {{ request()->routeIs('vendor.settings.*') ? 'active' : '' }}">
                            <i class="bx bx-cog side-menu__icon"></i>
                            <span class="side-menu__label">Settings</span>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="slide">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="side-menu__item w-100 text-start border-0 bg-transparent">
                                <i class="bx bx-power-off side-menu__icon"></i>
                                <span class="side-menu__label">Logout</span>
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Admin Menu Items - Dynamic Menu System --}}
                    @try
                        @php
                            use App\Helpers\AdminMenuHelper;
                        @endphp
                        
                        {!! AdminMenuHelper::generate('sidebar') !!}
                        
                    @catch(\Exception $e)
                        {{-- Fallback to static menu if dynamic system fails --}}
                        <li class="slide__category"><span class="category-name">Main</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bx bx-home side-menu__icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Ecommerce</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.products.index') }}" class="side-menu__item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="bx bx-package side-menu__icon"></i>
                                <span class="side-menu__label">Products</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.orders.index') }}" class="side-menu__item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <i class="bx bx-shopping-bag side-menu__icon"></i>
                                <span class="side-menu__label">Orders</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Users</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Reports</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.reports.index') }}" class="side-menu__item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                <i class="bx bx-bar-chart side-menu__icon"></i>
                                <span class="side-menu__label">Reports</span>
                            </a>
                        </li>
                        
                        <li class="slide__category"><span class="category-name">Settings</span></li>
                        <li class="slide">
                            <a href="{{ route('admin.settings.general') }}" class="side-menu__item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="bx bx-cog side-menu__icon"></i>
                                <span class="side-menu__label">Settings</span>
                            </a>
                        </li>
                    @endtry
                    
                    {{-- Menu Management System (Always Available for Admin) --}}
                    <li class="slide__category"><span class="category-name">System Management</span></li>
                    
                    <!-- Menu Management -->
                    <li class="slide has-sub {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            <i class="bx bx-menu side-menu__icon"></i>
                            <span class="side-menu__label">Menu Management</span>
                            <span class="badge bg-success ms-auto">New</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('admin.menu.index') }}" class="side-menu__item {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}">
                                    <i class="bx bx-list-ul"></i> All Menus
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.create') }}" class="side-menu__item {{ request()->routeIs('admin.menu.create') ? 'active' : '' }}">
                                    <i class="bx bx-plus"></i> Add Menu Item
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.builder') }}" class="side-menu__item {{ request()->routeIs('admin.menu.builder') ? 'active' : '' }}">
                                    <i class="bx bx-customize"></i> Menu Builder
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.demo') }}" class="side-menu__item {{ request()->routeIs('admin.menu.demo') ? 'active' : '' }}">
                                    <i class="bx bx-show"></i> System Demo
                                </a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.menu.settings') }}" class="side-menu__item {{ request()->routeIs('admin.menu.settings') ? 'active' : '' }}">
                                    <i class="bx bx-cog"></i> Sidebar Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- System Tools -->
                    <li class="slide has-sub {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.cache.*') || request()->routeIs('admin.logs.*') ? 'open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.cache.*') || request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                            <i class="bx bx-wrench side-menu__icon"></i>
                            <span class="side-menu__label">System Tools</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="#" onclick="clearAllCache()" class="side-menu__item">
                                    <i class="bx bx-refresh"></i> Clear All Cache
                                </a>
                            </li>
                            <li class="slide">
                                <a href="#" onclick="clearMenuCache()" class="side-menu__item">
                                    <i class="bx bx-menu"></i> Clear Menu Cache
                                </a>
                            </li>
                            @if(config('app.debug'))
                            <li class="slide">
                                <a href="#" onclick="showSystemInfo()" class="side-menu__item">
                                    <i class="bx bx-info-circle"></i> System Info
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    
                    <!-- Logout -->
                    <li class="slide">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="side-menu__item w-100 text-start border-0 bg-transparent">
                                <i class="bx bx-power-off side-menu__icon"></i>
                                <span class="side-menu__label">Logout</span>
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->
    </div>
    <!-- End::main-sidebar -->
</aside>
<!-- End::app-sidebar -->

<!-- Sidebar Enhancement Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced sidebar functionality
    initSidebarEnhancements();
});

function initSidebarEnhancements() {
    // Add smooth transitions
    const menuItems = document.querySelectorAll('.side-menu__item');
    menuItems.forEach(item => {
        item.style.transition = 'all 0.3s ease';
    });

    // Add hover effects for menu categories
    const categories = document.querySelectorAll('.slide__category');
    categories.forEach(category => {
        category.style.transition = 'all 0.2s ease';
        category.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        category.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Auto-collapse other menus when one is opened
    const subMenuToggles = document.querySelectorAll('.has-sub > .side-menu__item');
    subMenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const parentLi = this.closest('.has-sub');
            const isOpen = parentLi.classList.contains('open');
            
            // Close all other sub-menus at the same level
            const siblings = parentLi.parentElement.children;
            Array.from(siblings).forEach(sibling => {
                if (sibling !== parentLi && sibling.classList.contains('has-sub')) {
                    sibling.classList.remove('open');
                }
            });
            
            // Toggle current menu
            if (!isOpen) {
                parentLi.classList.add('open');
            }
        });
    });
}

// Cache management functions
function clearMenuCache() {
    if (confirm('Are you sure you want to clear the menu cache?')) {
        fetch('{{ route("admin.menu.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Menu cache cleared successfully!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error clearing cache. Please try again.', 'error');
        });
    }
}

function clearAllCache() {
    if (confirm('Are you sure you want to clear all application cache?')) {
        // You can implement this route in your admin controller
        showNotification('Cache clearing initiated...', 'info');
        // Implement the actual cache clearing logic here
    }
}

function showSystemInfo() {
    // Display system information modal
    alert('System Info:\nLaravel Version: {{ app()->version() }}\nPHP Version: {{ phpversion() }}\nEnvironment: {{ app()->environment() }}');
}

function showNotification(message, type = 'info') {
    // Create a simple notification system
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Menu state persistence
function saveMenuState() {
    const openMenus = [];
    document.querySelectorAll('.has-sub.open').forEach(menu => {
        const menuId = menu.querySelector('.side-menu__item').textContent.trim();
        openMenus.push(menuId);
    });
    localStorage.setItem('sidebar_open_menus', JSON.stringify(openMenus));
}

function restoreMenuState() {
    const openMenus = JSON.parse(localStorage.getItem('sidebar_open_menus') || '[]');
    openMenus.forEach(menuText => {
        const menuItem = Array.from(document.querySelectorAll('.has-sub .side-menu__item')).find(
            item => item.textContent.trim() === menuText
        );
        if (menuItem) {
            menuItem.closest('.has-sub').classList.add('open');
        }
    });
}

// Save menu state when navigating away
window.addEventListener('beforeunload', saveMenuState);

// Restore menu state on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(restoreMenuState, 100);
});
</script>

<!-- Enhanced Sidebar Styles -->
<style>
/* Menu Enhancement Styles */
.slide__category:hover .category-name {
    color: #007bff;
    font-weight: 700;
}

.side-menu__item:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(3px);
}

.side-menu__item.active {
    background: linear-gradient(135deg, #007bff, #0056b3);
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
}

.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Smooth transitions */
.slide-menu {
    transition: all 0.3s ease;
}

.has-sub.open .slide-menu {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Menu item icons */
.side-menu__icon {
    transition: all 0.3s ease;
}

.side-menu__item:hover .side-menu__icon {
    transform: scale(1.1);
    color: #007bff;
}

/* System Management section highlight */
.slide__category:has(+ .slide .side-menu__item[href*="menu"]) .category-name {
    background: linear-gradient(45deg, #28a745, #20c997);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}
</style>
