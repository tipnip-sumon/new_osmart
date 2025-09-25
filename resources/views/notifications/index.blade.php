@extends('layouts.app')

@section('title', 'Notifications - ' . config('app.name'))
@section('description', 'Stay updated with your account activities and system announcements')

@section('header')
<!-- Page Header -->
<div class="container">
    <div class="page-header pt-3">
        <div class="d-flex align-items-center">
            <a class="btn btn-primary btn-back" href="{{ url()->previous() }}">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div class="page-title ms-3">
                <h3>Notifications</h3>
                <p class="mb-0">Stay updated with your activities</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row g-3">
        <div class="col-12">
            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Your Notifications</h5>
                            <p class="text-muted mb-0">Stay updated with your account activities</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                <i class="ti ti-checks"></i> Mark All Read
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearAllNotifications()">
                                <i class="ti ti-trash"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Filters -->
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="filterType" onchange="filterNotifications()">
                                <option value="">All Types</option>
                                <option value="commission" {{ request('type') == 'commission' ? 'selected' : '' }}>Commission Updates</option>
                                <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment Updates</option>
                                <option value="system" {{ request('type') == 'system' ? 'selected' : '' }}>System Announcements</option>
                                <option value="account" {{ request('type') == 'account' ? 'selected' : '' }}>Account Updates</option>
                                <option value="mlm" {{ request('type') == 'mlm' ? 'selected' : '' }}>MLM Activities</option>
                                <option value="order" {{ request('type') == 'order' ? 'selected' : '' }}>Order Updates</option>
                                <option value="bonus" {{ request('type') == 'bonus' ? 'selected' : '' }}>Bonus Updates</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterStatus" onchange="filterNotifications()">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterDate" onchange="filterNotifications()">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="notifications-list">
                        @if(isset($notifications) && $notifications->count() > 0)
                            @foreach($notifications as $notification)
                                <div class="notification-item {{ !$notification->read_at ? 'unread' : '' }}" 
                                     data-id="{{ $notification->id }}" 
                                     data-type="{{ $notification->type }}" 
                                     data-status="{{ $notification->read_at ? 'read' : 'unread' }}">
                                    <div class="d-flex align-items-start p-3 border-bottom">
                                        <div class="notification-icon me-3">
                                            @php
                                                $iconMap = [
                                                    'commission' => ['icon' => 'ti-coin', 'color' => 'success'],
                                                    'payment' => ['icon' => 'ti-building-bank', 'color' => 'primary'],
                                                    'mlm' => ['icon' => 'ti-users', 'color' => 'info'],
                                                    'system' => ['icon' => 'ti-settings', 'color' => 'warning'],
                                                    'account' => ['icon' => 'ti-shield-check', 'color' => 'success'],
                                                    'order' => ['icon' => 'ti-shopping-bag', 'color' => 'primary'],
                                                    'bonus' => ['icon' => 'ti-gift', 'color' => 'success'],
                                                    'default' => ['icon' => 'ti-bell', 'color' => 'info']
                                                ];
                                                $iconData = $iconMap[$notification->type] ?? $iconMap['default'];
                                            @endphp
                                            <div class="icon-circle bg-{{ $iconData['color'] }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                                                <i class="{{ $iconData['icon'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="notification-content flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="notification-title mb-0 fw-bold">{{ $notification->title }}</h6>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="notification-message text-muted mb-2">{{ $notification->message }}</p>
                                            <div class="d-flex gap-2">
                                                @if(!$notification->read_at)
                                                    <button class="btn btn-sm btn-outline-primary" onclick="markAsRead({{ $notification->id }})">
                                                        <i class="ti ti-check"></i> Mark Read
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="ti ti-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                        @if(!$notification->read_at)
                                            <div class="unread-indicator bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="ti ti-bell-ringing text-muted mb-3" style="font-size: 4rem;"></i>
                                <h5 class="text-muted">No Notifications</h5>
                                <p class="text-muted">You're all caught up! No new notifications at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if(isset($notifications) && $notifications->hasPages())
                <div class="d-flex justify-content-center pagination-wrapper">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.notification-item.unread {
    background-color: rgba(13, 110, 253, 0.05);
    border-left: 3px solid #0d6efd;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.icon-circle {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.notification-title {
    color: #333;
    font-weight: 600;
}

.notification-message {
    line-height: 1.4;
}

.unread-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .notification-item .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .btn-sm {
        width: 100%;
        font-size: 0.8rem;
    }
    
    .icon-circle {
        width: 35px !important;
        height: 35px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function markAsRead(notificationId) {
    const item = document.querySelector(`[data-id="${notificationId}"]`);
    if (item) {
        item.classList.add('opacity-50');
        
        fetch(`/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                item.classList.remove('unread', 'opacity-50');
                item.setAttribute('data-status', 'read');
                
                const markReadBtn = item.querySelector('.btn-outline-primary');
                const unreadIndicator = item.querySelector('.unread-indicator');
                
                if (markReadBtn) markReadBtn.remove();
                if (unreadIndicator) unreadIndicator.remove();
                
                showToast('Notification marked as read', 'success');
                
                // Update sidebar notification count
                if (typeof window.updateSidebarNotificationCount === 'function') {
                    window.updateSidebarNotificationCount();
                }
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            item.classList.remove('opacity-50');
            showToast('Error marking notification as read', 'danger');
            console.error('Error:', error);
        });
    }
}

function markAllAsRead() {
    const unreadItems = document.querySelectorAll('.notification-item.unread');
    
    if (unreadItems.length === 0) {
        showToast('No unread notifications', 'info');
        return;
    }
    
    unreadItems.forEach(item => item.classList.add('opacity-50'));
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            unreadItems.forEach(item => {
                item.classList.remove('unread', 'opacity-50');
                item.setAttribute('data-status', 'read');
                
                const markReadBtn = item.querySelector('.btn-outline-primary');
                const unreadIndicator = item.querySelector('.unread-indicator');
                
                if (markReadBtn) markReadBtn.remove();
                if (unreadIndicator) unreadIndicator.remove();
            });
            
            showToast(`${data.count} notifications marked as read`, 'success');
            
            // Update sidebar notification count
            if (typeof window.updateSidebarNotificationCount === 'function') {
                window.updateSidebarNotificationCount();
            }
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(error => {
        unreadItems.forEach(item => item.classList.remove('opacity-50'));
        showToast('Error marking notifications as read', 'danger');
        console.error('Error:', error);
    });
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        const item = document.querySelector(`[data-id="${notificationId}"]`);
        if (item) {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-100%)';
            
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        item.remove();
                        showToast('Notification deleted', 'success');
                        checkEmptyState();
                        
                        // Update sidebar notification count
                        if (typeof window.updateSidebarNotificationCount === 'function') {
                            window.updateSidebarNotificationCount();
                        }
                    }, 300);
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
                showToast('Error deleting notification', 'danger');
                console.error('Error:', error);
            });
        }
    }
}

function clearAllNotifications() {
    if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
        const items = document.querySelectorAll('.notification-item');
        
        if (items.length === 0) {
            showToast('No notifications to clear', 'info');
            return;
        }
        
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-100%)';
            }, index * 100);
        });
        
        fetch('/notifications/clear-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    showEmptyState();
                    showToast(`${data.count} notifications cleared`, 'success');
                    
                    // Update sidebar notification count
                    if (typeof window.updateSidebarNotificationCount === 'function') {
                        window.updateSidebarNotificationCount();
                    }
                }, items.length * 100 + 300);
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            // Reset items if error occurred
            items.forEach(item => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            });
            showToast('Error clearing notifications', 'danger');
            console.error('Error:', error);
        });
    }
}

function filterNotifications() {
    const typeFilter = document.getElementById('filterType').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const dateFilter = document.getElementById('filterDate').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (typeFilter) params.append('type', typeFilter);
    if (statusFilter) params.append('status', statusFilter);
    if (dateFilter) params.append('date', dateFilter);
    
    // Reload page with filters
    window.location.href = `/notifications?${params.toString()}`;
}

function showEmptyState(title = 'No Notifications', message = 'You\'re all caught up! No new notifications at the moment.') {
    document.querySelector('.notifications-list').innerHTML = `
        <div class="text-center py-5">
            <i class="ti ti-bell-ringing text-muted mb-3" style="font-size: 4rem;"></i>
            <h5 class="text-muted">${title}</h5>
            <p class="text-muted">${message}</p>
        </div>
    `;
}

function checkEmptyState() {
    const items = document.querySelectorAll('.notification-item');
    if (items.length === 0) {
        showEmptyState();
    }
}

function showToast(message, type = 'info') {
    // Create a toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.style.minWidth = '250px';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    
    const icons = {
        'success': 'ti-check',
        'danger': 'ti-x',
        'warning': 'ti-alert-triangle',
        'info': 'ti-info-circle'
    };
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="${icons[type] || 'ti-info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" aria-label="Close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Fade in
    setTimeout(() => {
        toast.style.opacity = '1';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// Set filter values from URL parameters on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('type')) {
        document.getElementById('filterType').value = urlParams.get('type');
    }
    if (urlParams.get('status')) {
        document.getElementById('filterStatus').value = urlParams.get('status');
    }
    if (urlParams.get('date')) {
        document.getElementById('filterDate').value = urlParams.get('date');
    }
});

// Auto-refresh notifications every 2 minutes
setInterval(() => {
    if (!document.hidden) {
        fetch('/notifications/unread-count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.count > 0) {
                console.log(`You have ${data.count} unread notifications`);
                // You could update a badge or show a subtle notification here
            }
        })
        .catch(error => {
            console.log('Error checking for new notifications:', error);
        });
    }
}, 120000); // 2 minutes

// Handle pagination clicks via AJAX
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('.pagination a').href;
        
        if (url) {
            const notificationsList = document.querySelector('.notifications-list');
            const paginationWrapper = document.querySelector('.pagination-wrapper');
            
            notificationsList.style.opacity = '0.5';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationsList.innerHTML = data.html;
                    paginationWrapper.innerHTML = data.pagination;
                    notificationsList.style.opacity = '1';
                    
                    // Scroll to top of notifications
                    document.querySelector('.notifications-list').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(error => {
                notificationsList.style.opacity = '1';
                showToast('Error loading notifications', 'danger');
                console.error('Error:', error);
            });
        }
    }
});
</script>
@endpush
