<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->trim()->toString();

        $notifications = AdminNotification::query()
            ->when($this->userScope(), function ($query, $userId) {
                $query->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->when($status && $status !== 'all', function ($query) use ($status) {
                $query->where('is_read', $status === 'read');
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $unreadCount = AdminNotification::query()
            ->when($this->userScope(), function ($query, $userId) {
                $query->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->where('is_read', false)
            ->count();

        return view('admin.notifications', [
            'notifications' => $notifications,
            'statusFilter' => $status ?: 'all',
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markRead(AdminNotification $notification)
    {
        $this->authorizeAccess($notification);
        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        AdminNotification::query()
            ->when($this->userScope(), function ($query, $userId) {
                $query->where('user_id', $userId)->orWhereNull('user_id');
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    private function userScope(): ?int
    {
        return auth()->check() ? auth()->id() : null;
    }

    private function authorizeAccess(AdminNotification $notification): void
    {
        $userId = $this->userScope();
        if ($notification->user_id && $notification->user_id !== $userId) {
            abort(403);
        }
    }
}
