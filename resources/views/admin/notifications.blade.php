{{-- Use the shared admin layout. --}}
@extends('layouts.admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="tn-card-header-title" style="margin:0;">Notifications</h2>
            <p class="text-muted-tn" style="margin:0;font-size:13px;">{{ $unreadCount }} unread</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn-outline-tn" href="{{ route('admin.notifications', ['status' => 'all']) }}">All</a>
            <a class="btn-outline-tn" href="{{ route('admin.notifications', ['status' => 'unread']) }}">Unread</a>
            <a class="btn-outline-tn" href="{{ route('admin.notifications', ['status' => 'read']) }}">Read</a>
            <form method="POST" action="{{ route('admin.notifications.markAll') }}">
                @csrf
                <button class="btn-primary-tn" type="submit">Mark all as read</button>
            </form>
        </div>
    </div>

    <div class="tn-card-static">
        <div class="p-4">
            @if ($notifications->count() === 0)
                <div class="notification-empty">
                    <div class="notification-empty-icon"><i class="fas fa-bell-slash"></i></div>
                    <div class="notification-empty-title">No notifications yet</div>
                    <div class="notification-empty-text">We will let you know when something needs attention.</div>
                </div>
            @else
                <div class="notification-list">
                    @foreach ($notifications as $notification)
                        <div class="notification-item {{ $notification->is_read ? 'is-read' : 'is-unread' }}"
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
                                    <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="notification-mark-read" type="submit">Mark read</button>
                                    </form>
                                    <span class="notification-dot"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="p-3 d-flex justify-content-end">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
