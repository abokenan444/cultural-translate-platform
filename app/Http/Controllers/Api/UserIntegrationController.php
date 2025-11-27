<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserIntegration;
use Illuminate\Http\Request;

class UserIntegrationController extends Controller
{
    /**
     * Get all integrations for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $integrations = $user->userIntegrations()
            ->select('id', 'platform', 'status', 'connected_at', 'last_used_at')
            ->get();
        
        return response()->json([
            'success' => true,
            'integrations' => $integrations
        ]);
    }

    /**
     * Get a specific integration.
     */
    public function show(Request $request, string $platform)
    {
        $user = $request->user();
        
        $integration = $user->userIntegrations()
            ->where('platform', $platform)
            ->first();
        
        if (!$integration) {
            return response()->json([
                'success' => false,
                'error' => 'Integration not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'integration' => $integration
        ]);
    }

    /**
     * Get integration statistics.
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        $totalIntegrations = $user->userIntegrations()->count();
        $activeIntegrations = $user->userIntegrations()->active()->count();
        $inactiveIntegrations = $totalIntegrations - $activeIntegrations;
        
        $platforms = [
            'slack' => $user->userIntegrations()->platform('slack')->exists(),
            'teams' => $user->userIntegrations()->platform('teams')->exists(),
            'zoom' => $user->userIntegrations()->platform('zoom')->exists(),
            'gitlab' => $user->userIntegrations()->platform('gitlab')->exists(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $totalIntegrations,
                'active' => $activeIntegrations,
                'inactive' => $inactiveIntegrations,
                'platforms' => $platforms
            ]
        ]);
    }
}
