<!-- Header -->
<header class="admin-header">
    <div class="header-left">
        <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="header-page-title">@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="header-right">
        @php
            $notifications = $notifications ?? collect();
            $unreadCount = $unreadCount ?? $notifications->where('is_read', false)->count();
        @endphp
        <div class="notification-wrap">
            <button class="header-icon-btn notification-toggle" id="notificationToggle" aria-expanded="false"
                title="Notifications" type="button">
                <i class="fas fa-bell"></i>
                @if ($unreadCount > 0)
                    <span class="notification-badge" id="notificationBadge">{{ $unreadCount }}</span>
                @endif
            </button>
            <div class="notification-panel" id="notificationPanel" aria-labelledby="notificationToggle"
                data-mark-all-url="{{ route('admin.notifications.markAll') }}">
                <div class="notification-panel-header">
                    <div class="notification-panel-title">
                        <span>Notifications</span>
                        @if ($unreadCount > 0)
                            <span class="notification-count">{{ $unreadCount }} new</span>
                        @endif
                    </div>
                    <div class="notification-panel-actions">
                        <button class="notification-link" type="button" id="markNotificationsRead">Mark all as read</button>
                        <a class="notification-link" href="{{ route('admin.notifications') }}">View all</a>
                    </div>
                </div>
                <div class="notification-panel-body">
                    @if (count($notifications) === 0)
                        <div class="notification-empty">
                            <div class="notification-empty-icon"><i class="fas fa-bell-slash"></i></div>
                            <div class="notification-empty-title">No notifications yet</div>
                            <div class="notification-empty-text">We will let you know when something needs attention.</div>
                        </div>
                    @else
                        <div class="notification-list">
                            @foreach ($notifications as $notification)
                                <div class="notification-item {{ $notification->is_read ? 'is-read' : 'is-unread' }}"
                                    data-id="{{ $notification->id }}"
                                    data-read-url="{{ route('admin.notifications.read', $notification) }}">
                                    <div class="notification-icon type-{{ $notification->type }}">
                                        <i
                                            class="fas {{ $notification->type === 'payment' ? 'fa-wallet' : ($notification->type === 'booking' ? 'fa-calendar-check' : ($notification->type === 'user' ? 'fa-user' : ($notification->type === 'alert' ? 'fa-triangle-exclamation' : 'fa-building'))) }}"></i>
                                    </div>
                                    <a class="notification-content" href="{{ $notification->action_url ?? route('admin.notifications') }}">
                                        <div class="notification-title">{{ $notification->title }}</div>
                                        @if ($notification->message)
                                            <div class="notification-message">{{ $notification->message }}</div>
                                        @endif
                                        <div class="notification-meta">{{ $notification->created_at->diffForHumans() }}</div>
                                    </a>
                                    <div class="notification-actions">
                                        @if (!$notification->is_read)
                                            <button class="notification-mark-read" type="button">Mark read</button>
                                            <span class="notification-dot"></span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="notification-panel-footer">
                    <a class="notification-footer-link" href="{{ route('admin.notifications') }}">
                        See all notifications
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <a href="{{ url('/') }}" class="header-back-link">
            <i class="fas fa-chevron-left"></i>
            <span>Back to Site</span>
        </a>
    </div>
</header>
