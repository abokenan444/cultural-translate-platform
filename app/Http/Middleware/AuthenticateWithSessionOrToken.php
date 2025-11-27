<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithSessionOrToken
{
    /**
     * Handle an incoming request.
     * Supports both Session (web) and Sanctum (API token) authentication
     */
    public function handle(Request $request, Closure $next)
    {
        // Try Sanctum token first
        if ($request->bearerToken()) {
            return app('auth:sanctum')->handle($request, $next);
        }
        
        // Fall back to web session
        if (Auth::guard('web')->check()) {
            // Set the authenticated user for the request
            $request->setUserResolver(function () {
                return Auth::guard('web')->user();
            });
            return $next($request);
        }
        
        // No authentication found
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }
}
