<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Optional Authentication Middleware
 *
 * Processes authentication if a token is provided, but doesn't reject
 * unauthenticated requests. This allows endpoints to support both
 * authenticated and guest users.
 */
class OptionalAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Try to authenticate if a token is present
        if ($request->bearerToken()) {
            // Attempt to authenticate using Sanctum
            Auth::guard('sanctum')->check();
        }

        return $next($request);
    }
}
