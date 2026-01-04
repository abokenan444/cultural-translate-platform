<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class DashboardApiController extends Controller
{
    /**
     * Get authenticated user data.
     */
    public function getUser(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

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
        } catch (Exception $e) {
            Log::error('Error fetching user data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch user data', 500);
        }
    }

    /**
     * Get dashboard statistics.
     */
    public function getStats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            $subscription = $user->subscription;  // Use accessor

            // Check if translations relationship exists
            $translationsCount = 0;
            if (method_exists($user, 'translations')) {
                try {
                    $translationsCount = $user->translations()->count() ?? 0;
                } catch (QueryException $e) {
                    Log::warning('Failed to count translations', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $stats = [
                'tokens_used' => $subscription?->tokens_used ?? 0,
                'tokens_remaining' => $subscription?->tokens_remaining ?? 0,
                'tokens_limit' => $subscription?->subscriptionPlan?->tokens_limit ?? 0,
                'usage_percentage' => $this->calculateUsagePercentage($subscription),
                'translations_count' => $translationsCount,
                'days_until_expiry' => $this->getDaysUntilExpiry($subscription),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching dashboard stats', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch dashboard statistics', 500);
        }
    }

    /**
     * Get usage data over time.
     */
    public function getUsageData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            // Check if tokenUsageLogs relationship exists
            if (!method_exists($user, 'tokenUsageLogs')) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

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
        } catch (QueryException $e) {
            Log::error('Database error fetching usage data', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Database error occurred', 500);
        } catch (Exception $e) {
            Log::error('Error fetching usage data', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch usage data', 500);
        }
    }

    /**
     * Get languages data/statistics.
     */
    public function getLanguagesData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            // Check if translations relationship exists
            if (!method_exists($user, 'translations')) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

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
        } catch (QueryException $e) {
            Log::error('Database error fetching language stats', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Database error occurred', 500);
        } catch (Exception $e) {
            Log::error('Error fetching language statistics', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch language statistics', 500);
        }
    }

    /**
     * Get translation history.
     */
    public function getHistory(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

            // Check if translations relationship exists
            if (!method_exists($user, 'translations')) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

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
        } catch (QueryException $e) {
            Log::error('Database error fetching translation history', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage()
            ]);

            return $this->errorResponse('Database error occurred', 500);
        } catch (Exception $e) {
            Log::error('Error fetching translation history', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch translation history', 500);
        }
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
        try {
            $user = $request->user();

            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }

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
        } catch (Exception $e) {
            Log::error('Error fetching subscription data', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse('Failed to fetch subscription data', 500);
        }
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

    /**
     * Standardized error response.
     */
    private function errorResponse(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => true,
        ], $statusCode);
    }
}
