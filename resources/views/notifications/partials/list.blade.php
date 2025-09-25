@if($notifications->count() > 0)
    @foreach($notifications as $notification)
        @php
            $iconMap = [
                'commission' => ['icon' => 'ti-coin', 'color' => 'success'],
                'payment' => ['icon' => 'ti-building-bank', 'color' => 'primary'],
                'mlm' => ['icon' => 'ti-users', 'color' => 'info'],
                'system' => ['icon' => 'ti-settings', 'color' => 'warning'],
                'account' => ['icon' => 'ti-shield-check', 'color' => 'success'],
                'order' => ['icon' => 'ti-shopping-bag', 'color' => 'primary'],
                'bonus' => ['icon' => 'ti-gift', 'color' => 'success'],
            ];
            $notificationStyle = $iconMap[$notification->type] ?? ['icon' => 'ti-bell', 'color' => 'info'];
        @endphp
        <div class="notification-item {{ !$notification->read_at ? 'unread' : '' }}" 
             data-id="{{ $notification->id }}" 
             data-type="{{ $notification->type }}" 
             data-status="{{ $notification->read_at ? 'read' : 'unread' }}">
            <div class="d-flex align-items-start p-3 border-bottom">
                <div class="notification-icon me-3">
                    <div class="icon-circle bg-{{ $notificationStyle['color'] }} text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;">
                        <i class="{{ $notificationStyle['icon'] }}"></i>
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
