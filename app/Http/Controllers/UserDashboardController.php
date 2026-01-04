<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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
        $recentUsage = $user->tokenUsageLogs()
            ->with('userSubscription.subscriptionPlan')
            ->latest()
            ->take(10)
            ->get();
        
        // Get payment history
        $payments = $user->payments()
            ->with('subscriptionPlan')
            ->latest()
            ->take(5)
            ->get();
        
        // Get company memberships
        $companies = $user->companies()
            ->with('subscriptionPlan')
            ->get();
        
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
    }
    
    public function subscription()
    {
        $user = Auth::user();
        $subscription = $user->subscription;  // FIXED: Use accessor

        return view('user-dashboard.subscription', compact('user', 'subscription'));
    }

    public function usage()
    {
        $user = Auth::user();

        $usageLogs = $user->tokenUsageLogs()
            ->with('userSubscription.subscriptionPlan')
            ->latest()
            ->paginate(20);

        return view('user-dashboard.usage', compact('user', 'usageLogs'));
    }

    public function billing()
    {
        $user = Auth::user();

        $payments = $user->payments()
            ->with('subscriptionPlan')
            ->latest()
            ->paginate(15);

        return view('user-dashboard.billing', compact('user', 'payments'));
    }

    public function companies()
    {
        $user = Auth::user();

        $ownedCompanies = $user->ownedCompanies()
            ->with(['subscriptionPlan', 'members'])
            ->get();

        $memberCompanies = $user->companies()
            ->with(['owner', 'subscriptionPlan'])
            ->get();

        return view('user-dashboard.companies', compact('user', 'ownedCompanies', 'memberCompanies'));
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
