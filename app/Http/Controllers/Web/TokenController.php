<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    /**
     * Generate API token for authenticated user
     */
    public function generate(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }
        
        // Generate a simple token
        $token = base64_encode($user->id . '|' . Str::random(60));
        
        // Store token in user's remember_token field (or create a tokens table)
        $user->remember_token = $token;
        $user->save();
        
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}
