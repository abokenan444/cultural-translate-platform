<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionFixController extends Controller
{
    /**
     * Create free trial subscription for current user (temporary fix)
     */
    public function createFreeTrial(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Check if user already has a subscription
        $existingSubscription = $user->activeSubscription()->first();
        if ($existingSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'User already has an active subscription'
            ], 400);
        }

        // Get free plan
        $freePlan = SubscriptionPlan::where('slug', 'free')
            ->orWhere('price', 0)
            ->first();
        
        if (!$freePlan) {
            return response()->json([
                'success' => false,
                'message' => 'Free plan not found'
            ], 404);
        }

        // Create subscription
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $freePlan->id,
            'status' => 'active',
            'tokens_used' => 0,
            'tokens_remaining' => $freePlan->tokens_limit ?? 100000,
            'starts_at' => now(),
            'expires_at' => now()->addDays(14),
            'auto_renew' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Free trial subscription created successfully',
            'data' => $subscription
        ]);
    }
}
