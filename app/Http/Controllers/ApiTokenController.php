<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
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
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('dashboard_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }
}
