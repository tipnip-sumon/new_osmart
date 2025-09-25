@extends('member.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="main-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <i class="fe fe-bell me-2"></i>Notifications
            </h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('member.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Notification Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary-transparent">
                                    <i class="fe fe-bell fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Notifications</p>
                                        <h4 class="fw-semibold mt-1" id="total-notifications">{{ $stats['total'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-danger-transparent">
                                    <i class="fe fe-bell fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Unread</p>
                                        <h4 class="fw-semibold mt-1 text-danger" id="unread-notifications">{{ $stats['unread'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning-transparent">
                                    <i class="fe fe-star fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Important</p>
                                        <h4 class="fw-semibold mt-1 text-warning" id="important-notifications">{{ $stats['important'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success-transparent">
                                    <i class="fe fe-clock fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">This Week</p>
                                        <h4 class="fw-semibold mt-1 text-success" id="recent-notifications">{{ $stats['recent'] ?? 0 }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Filters and Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0">
                            <i class="fe fe-list me-2"></i>All Notifications
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Filter Buttons -->
                            <div class="btn-group" role="group" aria-label="Notification Filters">
                                <input type="radio" class="btn-check" name="notificationFilter" id="filter-all-page" autocomplete="off" checked>
                                <label class="btn btn-outline-primary btn-sm" for="filter-all-page">All</label>
                                
                                <input type="radio" class="btn-check" name="notificationFilter" id="filter-unread-page" autocomplete="off">
                                <label class="btn btn-outline-danger btn-sm" for="filter-unread-page">Unread</label>
                                
                                <input type="radio" class="btn-check" name="notificationFilter" id="filter-important-page" autocomplete="off">
                                <label class="btn btn-outline-warning btn-sm" for="filter-important-page">Important</label>
                            </div>

                            <!-- Action Buttons -->
                            <button class="btn btn-success btn-sm" onclick="markAllNotificationsReadPage()">
                                <i class="fe fe-check-circle"></i> Mark All Read
                            </button>
                            <button class="btn btn-info btn-sm" onclick="refreshNotificationsPage()">
                                <i class="fe fe-refresh-cw"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Loading Spinner -->
                        <div class="text-center p-4" id="notifications-loading" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading notifications...</p>
                        </div>

                        <!-- Notifications List -->
                        <div class="list-group list-group-flush" id="notifications-list-page">
                            @forelse($notifications as $notification)
                                <div class="list-group-item notification-item-page {{ !$notification->is_read ? 'bg-light border-start border-primary border-3' : '' }}" 
                                     data-notification-id="{{ $notification->id }}">
                                    <div class="d-flex align-items-start">
                                        <!-- Notification Icon -->
                                        <div class="me-3">
                                            <span class="avatar avatar-md avatar-rounded bg-{{ $notification->category_color }}-transparent">
                                                <i class="{{ $notification->icon }}"></i>
                                            </span>
                                        </div>

                                        <!-- Notification Content -->
                                        <div class="flex-fill">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <h6 class="mb-0 fw-medium {{ $notification->is_read ? 'text-muted' : '' }}">
                                                    {{ $notification->title }}
                                                    @if(!$notification->is_read)
                                                        <span class="badge bg-primary ms-2">New</span>
                                                    @endif
                                                    @if($notification->is_important)
                                                        <i class="fe fe-star text-warning ms-1" title="Important"></i>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">{{ $notification->time_ago }}</small>
                                            </div>
                                            
                                            <p class="mb-2 {{ $notification->is_read ? 'text-muted' : '' }}">
                                                {{ $notification->message }}
                                            </p>

                                            <!-- Action Buttons -->
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    @if($notification->action_url)
                                                        <a href="{{ $notification->action_url }}" class="btn btn-outline-primary btn-sm">
                                                            {{ $notification->action_text ?? 'View Details' }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if(!$notification->is_read)
                                                        <button class="btn btn-outline-success btn-sm" 
                                                                onclick="markNotificationReadPage({{ $notification->id }})"
                                                                title="Mark as read">
                                                            <i class="fe fe-check"></i>
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-outline-danger btn-sm" 
                                                            onclick="deleteNotificationPage({{ $notification->id }})"
                                                            title="Delete notification">
                                                        <i class="fe fe-trash-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-5" id="no-notifications-page">
                                    <span class="avatar avatar-xl avatar-rounded bg-info-transparent">
                                        <i class="fe fe-bell fs-2"></i>
                                    </span>
                                    <h6 class="fw-bold mb-1 mt-3">No Notifications</h6>
                                    <p class="mb-0 fw-normal fs-13 text-muted">You're all caught up! No notifications to display.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Pagination would go here if needed -->
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript for Notification Page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupNotificationPageFilters();
    
    // Auto-refresh every 60 seconds on notifications page
    setInterval(refreshNotificationsPage, 60000);
});

// Setup filter buttons for notification page
function setupNotificationPageFilters() {
    const filterButtons = document.querySelectorAll('input[name="notificationFilter"]');
    filterButtons.forEach(button => {
        button.addEventListener('change', function() {
            if (this.checked) {
                filterNotificationsPage(this.id.replace('filter-', '').replace('-page', ''));
            }
        });
    });
}

// Filter notifications on page
function filterNotificationsPage(filter) {
    const notifications = document.querySelectorAll('.notification-item-page');
    let visibleCount = 0;

    notifications.forEach(notification => {
        const isUnread = notification.classList.contains('bg-light');
        const isImportant = notification.querySelector('.fe-star') !== null;
        let shouldShow = false;

        switch(filter) {
            case 'all':
                shouldShow = true;
                break;
            case 'unread':
                shouldShow = isUnread;
                break;
            case 'important':
                shouldShow = isImportant;
                break;
        }

        if (shouldShow) {
            notification.style.display = 'block';
            visibleCount++;
        } else {
            notification.style.display = 'none';
        }
    });

    // Show/hide no notifications message
    const noNotifications = document.getElementById('no-notifications-page');
    if (visibleCount === 0 && noNotifications) {
        noNotifications.style.display = 'block';
    } else if (noNotifications) {
        noNotifications.style.display = 'none';
    }
}

// Mark single notification as read
function markNotificationReadPage(notificationId) {
    fetch(`{{ url('member/notifications') }}/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                // Remove unread styling
                notificationElement.classList.remove('bg-light', 'border-start', 'border-primary', 'border-3');
                
                // Remove "New" badge
                const newBadge = notificationElement.querySelector('.badge.bg-primary');
                if (newBadge) newBadge.remove();
                
                // Remove mark as read button
                const readButton = notificationElement.querySelector('.btn-outline-success');
                if (readButton) readButton.remove();
                
                // Update text color
                const title = notificationElement.querySelector('h6');
                const message = notificationElement.querySelector('p');
                if (title) title.classList.add('text-muted');
                if (message) message.classList.add('text-muted');
            }
            
            // Update statistics
            updateNotificationStats();
            showToastPage('Notification marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Failed to mark notification as read:', error);
        showToastPage('Failed to update notification', 'error');
    });
}

// Mark all notifications as read
function markAllNotificationsReadPage() {
    fetch('{{ route('member.notifications.mark-all-read') }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update all notification items
            document.querySelectorAll('.notification-item-page').forEach(item => {
                item.classList.remove('bg-light', 'border-start', 'border-primary', 'border-3');
                
                const newBadge = item.querySelector('.badge.bg-primary');
                if (newBadge) newBadge.remove();
                
                const readButton = item.querySelector('.btn-outline-success');
                if (readButton) readButton.remove();
                
                const title = item.querySelector('h6');
                const message = item.querySelector('p');
                if (title) title.classList.add('text-muted');
                if (message) message.classList.add('text-muted');
            });
            
            updateNotificationStats();
            showToastPage(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Failed to mark all notifications as read:', error);
        showToastPage('Failed to update notifications', 'error');
    });
}

// Delete notification
function deleteNotificationPage(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }

    fetch(`{{ url('member/notifications') }}/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.remove();
            }
            updateNotificationStats();
            showToastPage('Notification deleted', 'success');
        }
    })
    .catch(error => {
        console.error('Failed to delete notification:', error);
        showToastPage('Failed to delete notification', 'error');
    });
}

// Refresh notifications
function refreshNotificationsPage() {
    document.getElementById('notifications-loading').style.display = 'block';
    
    fetch('{{ route('member.notifications.index') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // This would require more complex implementation to replace content
        // For now, just reload the page
        window.location.reload();
    })
    .catch(error => {
        console.error('Failed to refresh notifications:', error);
        showToastPage('Failed to refresh notifications', 'error');
    })
    .finally(() => {
        document.getElementById('notifications-loading').style.display = 'none';
    });
}

// Update notification statistics
function updateNotificationStats() {
    fetch('{{ route('member.notifications.header') }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update page statistics
            const totalEl = document.getElementById('total-notifications');
            const unreadEl = document.getElementById('unread-notifications');
            const importantEl = document.getElementById('important-notifications');
            
            if (totalEl && data.stats) {
                totalEl.textContent = data.stats.total || 0;
                unreadEl.textContent = data.stats.unread || 0;
                importantEl.textContent = data.stats.important || 0;
            }
            
            // Also update header notification badge if function exists
            if (typeof updateNotificationUI === 'function') {
                updateNotificationUI();
            }
        }
    })
    .catch(error => {
        console.error('Failed to update stats:', error);
    });
}

// Toast notification for page
function showToastPage(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fe fe-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" aria-label="Close"></button>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);

    const closeBtn = toast.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        });
    }
}
</script>
@endsection