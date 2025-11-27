<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SimpleTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Try Bearer token first
        $token = $request->bearerToken();
        
        if ($token) {
            // Decode token (format: base64(user_id|random_string))
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, '|') !== false) {
                [$userId, $tokenPart] = explode('|', $decoded, 2);
                
                // Find user and verify token
                $user = User::find($userId);
                if ($user && $user->remember_token === $token) {
                    // Authenticate user
                    Auth::setUser($user);
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                    return $next($request);
                }
            }
        }
        
        // Fallback to session authentication
        if (Auth::check()) {
            return $next($request);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }
}
