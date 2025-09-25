/* Modern Admin Header JavaScript */

$(document).ready(function() {
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Global search functionality
    initializeGlobalSearch();
    
    // Theme toggle functionality
    initializeThemeToggle();
    
    // Fullscreen functionality
    initializeFullscreen();
    
    // Notification functionality
    initializeNotifications();
    
    // Real-time stats update
    initializeRealTimeStats();
    
    // Quick actions
    initializeQuickActions();
    
    // User profile enhancements
    initializeUserProfile();
});

// Global Search Functions
function initializeGlobalSearch() {
    const searchInput = $('#globalSearch');
    const mobileSearchInput = $('#mobileGlobalSearch');
    const searchResults = $('#searchResults');
    const mobileSearchResults = $('#mobileSearchResults');
    let searchTimeout;

    // Desktop search
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                performSearch(query, searchResults);
            }, 300);
        } else {
            searchResults.hide();
        }
    });

    // Mobile search
    mobileSearchInput.on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                performSearch(query, mobileSearchResults);
            }, 300);
        } else {
            mobileSearchResults.hide();
        }
    });

    // Search button click
    $('#searchBtn, #mobileSearchBtn').on('click', function() {
        const input = $(this).closest('.input-group').find('input');
        const query = input.val().trim();
        
        if (query.length >= 2) {
            const resultsContainer = $(this).attr('id') === 'searchBtn' ? searchResults : mobileSearchResults;
            performSearch(query, resultsContainer);
        }
    });

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-search-bar, .search-container').length) {
            searchResults.hide();
            mobileSearchResults.hide();
        }
    });
}

function performSearch(query, resultsContainer) {
    // Show loading state
    resultsContainer.html(`
        <div class="text-center p-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Searching...</span>
            </div>
            <p class="mb-0 mt-2">Searching...</p>
        </div>
    `).show();

    // Perform AJAX search
    $.ajax({
        url: '/admin/search',
        method: 'GET',
        data: { q: query },
        success: function(response) {
            displaySearchResults(response, resultsContainer);
        },
        error: function() {
            resultsContainer.html(`
                <div class="text-center p-3 text-muted">
                    <i class="bx bx-error-circle fs-24 mb-2"></i>
                    <p class="mb-0">Search failed. Please try again.</p>
                </div>
            `);
        }
    });
}

function displaySearchResults(results, container) {
    if (!results || !results.data || results.data.length === 0) {
        container.html(`
            <div class="text-center p-3 text-muted">
                <i class="bx bx-search fs-24 mb-2"></i>
                <p class="mb-0">No results found</p>
            </div>
        `);
        return;
    }

    let html = '<div class="search-results-list">';
    
    results.data.forEach(item => {
        html += `
            <div class="search-result-item d-flex align-items-center p-2 border-bottom" data-url="${item.url}">
                <div class="result-icon me-3">
                    <i class="bx ${getSearchIcon(item.type)} text-${getSearchColor(item.type)}"></i>
                </div>
                <div class="result-content flex-grow-1">
                    <h6 class="mb-1">${highlightSearchTerm(item.title, results.query)}</h6>
                    <p class="mb-0 text-muted fs-12">${item.description}</p>
                    <span class="badge bg-${getSearchColor(item.type)}-transparent fs-10">${item.type}</span>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    if (results.data.length >= 10) {
        html += `
            <div class="text-center p-2 border-top">
                <a href="/admin/search?q=${encodeURIComponent(results.query)}" class="text-primary">
                    View all results
                </a>
            </div>
        `;
    }
    
    container.html(html).show();

    // Add click handlers
    container.find('.search-result-item').on('click', function() {
        const url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
}

function getSearchIcon(type) {
    const icons = {
        'product': 'bx-package',
        'order': 'bx-shopping-bag',
        'user': 'bx-user',
        'vendor': 'bx-store',
        'coupon': 'bx-gift',
        'category': 'bx-category'
    };
    return icons[type] || 'bx-file';
}

function getSearchColor(type) {
    const colors = {
        'product': 'primary',
        'order': 'success',
        'user': 'info',
        'vendor': 'warning',
        'coupon': 'danger',
        'category': 'secondary'
    };
    return colors[type] || 'primary';
}

function highlightSearchTerm(text, term) {
    if (!term) return text;
    const regex = new RegExp(`(${term})`, 'gi');
    return text.replace(regex, '<mark>$1</mark>');
}

function quickSearch(type) {
    const input = $('#mobileGlobalSearch');
    input.val('type:' + type).trigger('input');
}

// Theme Toggle Functions
function initializeThemeToggle() {
    const themeToggle = $('#themeToggle');
    const darkModeSwitch = $('#darkModeSwitch');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Set initial theme
    setTheme(currentTheme);
    darkModeSwitch.prop('checked', currentTheme === 'dark');
    
    // Theme toggle button
    themeToggle.on('click', function() {
        const newTheme = $('html').attr('data-theme-mode') === 'dark' ? 'light' : 'dark';
        setTheme(newTheme);
        darkModeSwitch.prop('checked', newTheme === 'dark');
    });
    
    // Dark mode switch in profile menu
    darkModeSwitch.on('change', function() {
        const newTheme = $(this).is(':checked') ? 'dark' : 'light';
        setTheme(newTheme);
    });
}

function setTheme(theme) {
    $('html').attr('data-theme-mode', theme);
    localStorage.setItem('theme', theme);
    
    // Update theme toggle icons
    if (theme === 'dark') {
        $('.theme-icon-dark').hide();
        $('.theme-icon-light').show();
        $('.dark-mode-icon').removeClass('bx-moon').addClass('bx-sun');
        $('.dark-mode-text').text('Light Mode');
    } else {
        $('.theme-icon-dark').show();
        $('.theme-icon-light').hide();
        $('.dark-mode-icon').removeClass('bx-sun').addClass('bx-moon');
        $('.dark-mode-text').text('Dark Mode');
    }
}

function toggleDarkMode() {
    $('#darkModeSwitch').trigger('change');
}

// Fullscreen Functions
function initializeFullscreen() {
    $('#fullscreenBtn').on('click', function() {
        toggleFullscreen();
    });
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.log('Error attempting to enable fullscreen:', err.message);
        });
        $('#fullscreenBtn i').removeClass('bx-fullscreen').addClass('bx-exit-fullscreen');
    } else {
        document.exitFullscreen();
        $('#fullscreenBtn i').removeClass('bx-exit-fullscreen').addClass('bx-fullscreen');
    }
}

// Notification Functions
function initializeNotifications() {
    loadNotifications();
    
    // Auto-refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Mark notification as read on click
    $(document).on('click', '.notification-item', function() {
        const notificationId = $(this).data('id');
        if (notificationId) {
            markNotificationAsRead(notificationId);
        }
    });
}

function loadNotifications() {
    $.ajax({
        url: '/admin/notifications/recent',
        method: 'GET',
        success: function(response) {
            updateNotificationCount(response.unread_count);
            updateNotificationList(response.notifications);
        },
        error: function() {
            console.log('Failed to load notifications');
        }
    });
}

function updateNotificationCount(count) {
    $('#notificationCount').text(count);
    
    if (count > 0) {
        $('#notificationCount').show();
    } else {
        $('#notificationCount').hide();
    }
}

function updateNotificationList(notifications) {
    const list = $('#notificationList');
    
    if (!notifications || notifications.length === 0) {
        list.html(`
            <li class="dropdown-item text-center py-4">
                <div class="text-muted">
                    <i class="bx bx-bell fs-24 mb-2"></i>
                    <p class="mb-0">No new notifications</p>
                </div>
            </li>
        `);
        return;
    }
    
    let html = '';
    notifications.forEach(notification => {
        html += `
            <li class="dropdown-item notification-item ${notification.read_at ? '' : 'unread'}" data-id="${notification.id}">
                <div class="d-flex align-items-start">
                    <div class="pe-2">
                        <span class="avatar avatar-md bg-${notification.color}-transparent">
                            <i class="bx ${notification.icon}"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold">${notification.title}</p>
                        <span class="fs-12 text-muted">${notification.message}</span>
                        <p class="fs-11 text-muted mb-0">${notification.time_ago}</p>
                    </div>
                    ${notification.action_url ? `
                        <div class="notification-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.location.href='${notification.action_url}'">View</button>
                        </div>
                    ` : ''}
                </div>
            </li>
        `;
    });
    
    list.html(html);
}

function markAllRead() {
    $.ajax({
        url: '/admin/notifications/mark-all-read',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
            loadNotifications();
            showToast('All notifications marked as read', 'success');
        },
        error: function() {
            showToast('Failed to mark notifications as read', 'error');
        }
    });
}

function markNotificationAsRead(notificationId) {
    $.ajax({
        url: `/admin/notifications/${notificationId}/read`,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
            $(`.notification-item[data-id="${notificationId}"]`).removeClass('unread');
            // Update count
            const currentCount = parseInt($('#notificationCount').text()) || 0;
            if (currentCount > 0) {
                updateNotificationCount(currentCount - 1);
            }
        }
    });
}

// Real-time Stats Functions
function initializeRealTimeStats() {
    updateStats();
    
    // Update stats every 60 seconds
    setInterval(updateStats, 60000);
}

function updateStats() {
    $.ajax({
        url: '/admin/stats/realtime',
        method: 'GET',
        success: function(response) {
            if (response.todayOrders !== undefined) {
                $('#todayOrders').text(response.todayOrders);
            }
            if (response.todayRevenue !== undefined) {
                $('#todayRevenue').text('$' + response.todayRevenue);
            }
            if (response.onlineUsers !== undefined) {
                $('#onlineUsers').text(response.onlineUsers);
            }
        },
        error: function() {
            console.log('Failed to update real-time stats');
        }
    });
}

// Quick Actions Functions
function initializeQuickActions() {
    // Quick action buttons in header
    window.quickAddProduct = function() {
        window.location.href = '/admin/products/create';
    };
    
    window.quickViewOrders = function() {
        window.location.href = '/admin/orders';
    };
    
    window.quickReports = function() {
        window.location.href = '/admin/reports';
    };
    
    window.quickCreateOrder = function() {
        window.location.href = '/admin/orders/create';
    };
    
    window.quickAddUser = function() {
        window.location.href = '/admin/users/create';
    };
    
    window.quickCreateCoupon = function() {
        window.location.href = '/admin/coupons/create';
    };
    
    window.quickViewReports = function() {
        window.location.href = '/admin/reports';
    };
    
    window.quickSettings = function() {
        window.location.href = '/admin/settings';
    };
}

// User Profile Functions
function initializeUserProfile() {
    // Profile dropdown enhancements
    $('#mainHeaderProfile').on('shown.bs.dropdown', function() {
        // Add animation class
        $('.user-profile-menu').addClass('show-animation');
    });
    
    // Logout confirmation
    window.confirmLogout = function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Confirm Logout',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logoutForm').submit();
                }
            });
        } else {
            if (confirm('Are you sure you want to logout?')) {
                $('#logoutForm').submit();
            }
        }
    };
}

// Utility Functions
function showToast(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    } else {
        // Fallback to console
        console.log(`${type.toUpperCase()}: ${message}`);
    }
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    // Ctrl/Cmd + K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        $('#globalSearch').focus();
    }
    
    // Ctrl/Cmd + Shift + D for dark mode toggle
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
        e.preventDefault();
        $('#themeToggle').click();
    }
    
    // F11 for fullscreen
    if (e.key === 'F11') {
        e.preventDefault();
        $('#fullscreenBtn').click();
    }
});

// Handle window resize
$(window).on('resize', function() {
    // Adjust search results position on mobile
    if ($(window).width() < 768) {
        $('.search-results-dropdown').css('position', 'fixed');
    } else {
        $('.search-results-dropdown').css('position', 'absolute');
    }
});

// Initialize animations
function initializeAnimations() {
    // Stagger animation for notification items
    $('.notification-item').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('fade-in-up');
    });
    
    // Parallax effect for header background
    $(window).on('scroll', function() {
        const scrollTop = $(this).scrollTop();
        $('.modern-header').css('transform', `translateY(${scrollTop * 0.1}px)`);
    });
}

// Performance optimization
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Error handling
window.addEventListener('error', function(e) {
    console.log('JavaScript error in modern header:', e.error);
});

// Initialize everything when DOM is loaded
$(document).ready(function() {
    initializeAnimations();
    
    // Add loading class removal after page load
    $(window).on('load', function() {
        $('body').removeClass('loading');
    });
});
