<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictNormalUserAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->usertype == 0) {
            // Allow only dashboard and sales routes
            $allowedRoutes = [
                'dashboard',
                'sales',
                'track',
            ];

            $currentRoute = $request->route()->getName();

            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->back()
                    ->with('error', 'Access denied. You are not authorized to view this page.');
            }
        }

        return $next($request);
    }
}
