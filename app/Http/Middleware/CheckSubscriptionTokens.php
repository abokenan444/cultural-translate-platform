<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionTokens
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Get active subscription
        $subscription = $user->subscriptions()
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return response()->json([
                'error' => 'No active subscription found',
                'message' => 'يرجى الاشتراك في إحدى الباقات للمتابعة',
                'redirect' => '/pricing',
            ], 403);
        }

        // Check if subscription is expired
        if ($subscription->isExpired()) {
            $subscription->update(['status' => 'expired']);
            
            return response()->json([
                'error' => 'Subscription expired',
                'message' => 'اشتراكك منتهي. يرجى تجديد الاشتراك للمتابعة',
                'redirect' => '/pricing',
            ], 403);
        }

        // Check if has tokens
        if (!$subscription->hasTokens()) {
            return response()->json([
                'error' => 'No tokens remaining',
                'message' => 'لقد استنفدت جميع التوكنات المتاحة. يرجى ترقية باقتك أو الانتظار حتى إعادة التعيين الشهرية',
                'tokens_remaining' => 0,
                'plan_name' => $subscription->plan->name,
                'redirect' => '/pricing',
            ], 403);
        }

        // Attach subscription to request for easy access
        $request->attributes->set('subscription', $subscription);

        return $next($request);
    }
}
