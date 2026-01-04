<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\QueryException;

class UserDashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('Unauthenticated user attempted to access dashboard');
                return redirect()->route('login');
            }

            // Get active subscription (using accessor for proper retrieval)
            $subscription = $user->subscription;

            // Get usage statistics with safe null checks
            $stats = [
                'tokens_used' => $subscription?->tokens_used ?? 0,
                'tokens_remaining' => $subscription?->tokens_remaining ?? 0,
                'tokens_limit' => $subscription?->subscriptionPlan?->tokens_limit ?? 0,
                'usage_percentage' => $this->calculateUsagePercentage($subscription),
            ];

            // Get recent token usage logs
            $recentUsage = collect([]);
            if (method_exists($user, 'tokenUsageLogs')) {
                try {
                    $recentUsage = $user->tokenUsageLogs()
                        ->with('userSubscription.subscriptionPlan')
                        ->latest()
                        ->take(10)
                        ->get();
                } catch (QueryException $e) {
                    Log::error('Failed to fetch token usage logs', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Get payment history
            $payments = collect([]);
            if (method_exists($user, 'payments')) {
                try {
                    $payments = $user->payments()
                        ->with('subscriptionPlan')
                        ->latest()
                        ->take(5)
                        ->get();
                } catch (QueryException $e) {
                    Log::error('Failed to fetch payment history', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Get company memberships
            $companies = collect([]);
            if (method_exists($user, 'companies')) {
                try {
                    $companies = $user->companies()
                        ->with('subscriptionPlan')
                        ->get();
                } catch (QueryException $e) {
                    Log::error('Failed to fetch company memberships', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Days until subscription expires
            $daysUntilExpiry = $subscription && $subscription->expires_at ?
                now()->diffInDays($subscription->expires_at, false) : null;

            return view('user-dashboard.index', compact(
                'user',
                'subscription',
                'stats',
                'recentUsage',
                'payments',
                'companies',
                'daysUntilExpiry'
            ));
        } catch (Exception $e) {
            Log::error('Error loading dashboard', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load dashboard. Please try again.');
        }
    }
    
    public function subscription()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $subscription = $user->subscription;  // FIXED: Use accessor

            return view('user-dashboard.subscription', compact('user', 'subscription'));
        } catch (Exception $e) {
            Log::error('Error loading subscription page', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load subscription details.');
        }
    }

    public function usage()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $usageLogs = collect([]);
            if (method_exists($user, 'tokenUsageLogs')) {
                try {
                    $usageLogs = $user->tokenUsageLogs()
                        ->with('userSubscription.subscriptionPlan')
                        ->latest()
                        ->paginate(20);
                } catch (QueryException $e) {
                    Log::error('Failed to fetch usage logs', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    $usageLogs = collect([]);
                }
            }

            return view('user-dashboard.usage', compact('user', 'usageLogs'));
        } catch (Exception $e) {
            Log::error('Error loading usage page', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load usage data.');
        }
    }

    public function billing()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $payments = collect([]);
            if (method_exists($user, 'payments')) {
                try {
                    $payments = $user->payments()
                        ->with('subscriptionPlan')
                        ->latest()
                        ->paginate(15);
                } catch (QueryException $e) {
                    Log::error('Failed to fetch payment history', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    $payments = collect([]);
                }
            }

            return view('user-dashboard.billing', compact('user', 'payments'));
        } catch (Exception $e) {
            Log::error('Error loading billing page', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load billing information.');
        }
    }

    public function companies()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $ownedCompanies = collect([]);
            $memberCompanies = collect([]);

            if (method_exists($user, 'ownedCompanies')) {
                try {
                    $ownedCompanies = $user->ownedCompanies()
                        ->with(['subscriptionPlan', 'members'])
                        ->get();
                } catch (QueryException $e) {
                    Log::error('Failed to fetch owned companies', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (method_exists($user, 'companies')) {
                try {
                    $memberCompanies = $user->companies()
                        ->with(['owner', 'subscriptionPlan'])
                        ->get();
                } catch (QueryException $e) {
                    Log::error('Failed to fetch member companies', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return view('user-dashboard.companies', compact('user', 'ownedCompanies', 'memberCompanies'));
        } catch (Exception $e) {
            Log::error('Error loading companies page', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load company information.');
        }
    }

    /**
     * Calculate usage percentage safely.
     * Prevents division by zero errors.
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
}
