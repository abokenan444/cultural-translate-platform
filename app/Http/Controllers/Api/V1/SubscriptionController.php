<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    /**
     * Get all available plans
     */
    public function plans()
    {
        $plans = Plan::with('features')
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    /**
     * Get current subscription
     */
    public function current(Request $request)
    {
        $subscription = $request->user()->subscription()
            ->with(['plan', 'plan.features'])
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'subscription' => $subscription,
                'usage' => [
                    'characters_used' => $subscription->getCharactersUsed(),
                    'characters_limit' => $subscription->plan->character_limit,
                    'characters_remaining' => $subscription->getRemainingCharacters(),
                    'api_calls_used' => $subscription->getApiCallsUsed(),
                    'api_calls_limit' => $subscription->plan->api_calls_limit,
                ],
                'status' => $subscription->status,
                'expires_at' => $subscription->expires_at,
            ]
        ]);
    }

    /**
     * Subscribe to a plan
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'payment_method' => 'required|string|in:stripe,paypal,credit_card',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $plan = Plan::findOrFail($request->plan_id);

        // Check if user already has an active subscription
        $existingSubscription = $user->subscription()->where('status', 'active')->first();
        if ($existingSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription'
            ], 400);
        }

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'auto_renew' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'data' => $subscription->load('plan')
        ], 201);
    }

    /**
     * Upgrade subscription
     */
    public function upgrade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $newPlan = Plan::findOrFail($request->plan_id);
        $currentSubscription = $user->subscription()->where('status', 'active')->first();

        if (!$currentSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        // Update subscription
        $currentSubscription->update([
            'plan_id' => $newPlan->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription upgraded successfully',
            'data' => $currentSubscription->load('plan')
        ]);
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $subscription = $request->user()->subscription()
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        $subscription->update([
            'status' => 'cancelled',
            'auto_renew' => false,
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully'
        ]);
    }

    /**
     * Get usage statistics
     */
    public function usage(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription()->where('status', 'active')->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        $usageLogs = $user->usageLogs()
            ->whereBetween('created_at', [$subscription->starts_at, now()])
            ->get();

        $totalCharacters = $usageLogs->sum('characters_used');
        $totalApiCalls = $usageLogs->sum('api_calls');

        return response()->json([
            'success' => true,
            'data' => [
                'period' => [
                    'start' => $subscription->starts_at,
                    'end' => $subscription->expires_at,
                ],
                'usage' => [
                    'characters' => [
                        'used' => $totalCharacters,
                        'limit' => $subscription->plan->character_limit,
                        'remaining' => max(0, $subscription->plan->character_limit - $totalCharacters),
                        'percentage' => round(($totalCharacters / $subscription->plan->character_limit) * 100, 2),
                    ],
                    'api_calls' => [
                        'used' => $totalApiCalls,
                        'limit' => $subscription->plan->api_calls_limit,
                        'remaining' => max(0, $subscription->plan->api_calls_limit - $totalApiCalls),
                        'percentage' => round(($totalApiCalls / $subscription->plan->api_calls_limit) * 100, 2),
                    ],
                ],
                'daily_usage' => $usageLogs->groupBy(function($log) {
                    return $log->created_at->format('Y-m-d');
                })->map(function($logs) {
                    return [
                        'characters' => $logs->sum('characters_used'),
                        'api_calls' => $logs->sum('api_calls'),
                    ];
                }),
            ]
        ]);
    }
}
