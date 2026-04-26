<?php

namespace App\Providers;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.admin-header', function ($view) {
            if (!auth()->check()) {
                $view->with(['notifications' => collect(), 'unreadCount' => 0]);
                return;
            }

            $userId = auth()->id();
            $notifications = AdminNotification::query()
                ->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId)->orWhereNull('user_id');
                })
                ->orderByDesc('created_at')
                ->take(6)
                ->get();

            $unreadCount = $notifications->where('is_read', false)->count();

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        });
    }
}
