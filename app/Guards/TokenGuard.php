<?php

namespace App\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\Models\User;

class TokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        // Try Bearer token first
        $token = $this->request->bearerToken();
        
        if ($token) {
            // Decode token (format: base64(user_id|random_string))
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, '|') !== false) {
                [$userId, $tokenPart] = explode('|', $decoded, 2);
                
                // Find user and verify token
                $user = User::find($userId);
                if ($user && $user->remember_token === $token) {
                    return $this->user = $user;
                }
            }
        }

        // Fallback to session
        if ($this->request->session() && $this->request->session()->has('login_web_' . sha1(static::class))) {
            $userId = $this->request->session()->get('login_web_' . sha1(static::class));
            return $this->user = User::find($userId);
        }

        return null;
    }

    public function validate(array $credentials = [])
    {
        return false;
    }
}
