<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAgencyApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $agency = $request->user('agency');

        // Prevent access when the agency is no longer approved.
        if ($agency && $agency->status !== 'approved') {
            Auth::guard('agency')->logout();

            return redirect()
                ->route('agency.login')
                ->withErrors(['login' => 'Your agency account is not approved.'], 'agency_login');
        }

        return $next($request);
    }
}
