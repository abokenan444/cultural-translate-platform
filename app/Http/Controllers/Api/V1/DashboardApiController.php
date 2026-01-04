<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardApiController extends Controller
{
    /**
     * Get authenticated user data.
     */
    public function getUser(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
            ]
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function getStats(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $user->subscription;  // Use accessor

        $stats = [
            'tokens_used' => $subscription?->tokens_used ?? 0,
            'tokens_remaining' => $subscription?->tokens_remaining ?? 0,
            'tokens_limit' => $subscription?->subscriptionPlan?->tokens_limit ?? 0,
            'usage_percentage' => $this->calculateUsagePercentage($subscription),
            'translations_count' => $user->translations()->count() ?? 0,
            'days_until_expiry' => $this->getDaysUntilExpiry($subscription),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get usage data over time.
     */
    public function getUsageData(Request $request): JsonResponse
    {
        $user = $request->user();

        $usageLogs = $user->tokenUsageLogs()
            ->with('userSubscription.subscriptionPlan')
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'tokens_used' => $log->tokens_used,
                    'action' => $log->action ?? 'translation',
                    'created_at' => $log->created_at,
                    'plan_name' => $log->userSubscription?->subscriptionPlan?->name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $usageLogs
        ]);
    }

    /**
     * Get languages data/statistics.
     */
    public function getLanguagesData(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get translation statistics by language
        $languageStats = $user->translations()
            ->select('source_language', 'target_language', \DB::raw('count(*) as count'))
            ->groupBy('source_language', 'target_language')
            ->get()
            ->map(function ($stat) {
                return [
                    'source' => $stat->source_language,
                    'target' => $stat->target_language,
                    'count' => $stat->count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $languageStats
        ]);
    }

    /**
     * Get translation history.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $user = $request->user();

        $translations = $user->translations()
            ->latest()
            ->take(50)
            ->get()
            ->map(function ($translation) {
                return [
                    'id' => $translation->id,
                    'source_text' => $translation->source_text,
                    'translated_text' => $translation->translated_text,
                    'source_language' => $translation->source_language,
                    'target_language' => $translation->target_language,
                    'tone' => $translation->tone,
                    'quality_score' => $translation->quality_score,
                    'created_at' => $translation->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $translations
        ]);
    }

    /**
     * Get user projects (if applicable).
     */
    public function getProjects(Request $request): JsonResponse
    {
        $user = $request->user();

        // For now, return empty array or implement actual projects logic
        $projects = [];

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Projects feature coming soon'
        ]);
    }

    /**
     * Get subscription information.
     */
    public function getSubscription(Request $request): JsonResponse
    {
        $user = $request->user();
        $subscription = $user->subscription;

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 404);
        }

        $data = [
            'id' => $subscription->id,
            'plan_name' => $subscription->subscriptionPlan?->name,
            'plan_slug' => $subscription->subscriptionPlan?->slug,
            'status' => $subscription->status,
            'tokens_used' => $subscription->tokens_used,
            'tokens_remaining' => $subscription->tokens_remaining,
            'tokens_limit' => $subscription->subscriptionPlan?->tokens_limit,
            'starts_at' => $subscription->starts_at,
            'expires_at' => $subscription->expires_at,
            'auto_renew' => $subscription->auto_renew,
            'days_remaining' => $this->getDaysUntilExpiry($subscription),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Calculate usage percentage safely.
     */
    private function calculateUsagePercentage($subscription): float
    {
        if (!$subscription || !$subscription->subscriptionPlan) {
            return 0.0;
        }

        $tokensLimit = $subscription->subscriptionPlan->tokens_limit;

        if (!$tokensLimit || $tokensLimit <= 0) {
            return 0.0;
        }

        return round(($subscription->tokens_used / $tokensLimit) * 100, 1);
    }

    /**
     * Get days until subscription expiry.
     */
    private function getDaysUntilExpiry($subscription): ?int
    {
        if (!$subscription || !$subscription->expires_at) {
            return null;
        }

        return now()->diffInDays($subscription->expires_at, false);
    }
}
