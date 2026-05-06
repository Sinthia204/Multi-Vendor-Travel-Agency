<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $isAdmin = $user && ($user->hasRole('Admin') || ($user->role ?? null) === 'admin' || ($user->is_admin ?? false));

        if (!$isAdmin) {
            return redirect('/')
                ->withErrors(['login' => 'You do not have access to the admin dashboard.'])
                ->with('show_login', true);
        }

        return $next($request);
    }
}
