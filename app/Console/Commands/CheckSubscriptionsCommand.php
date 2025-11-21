<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:check';
    
    protected $description = 'Check subscriptions for expiry and low tokens, send notifications';

    public function handle()
    {
        $this->info('Checking subscriptions...');

        // Check for expired subscriptions
        $expiredCount = $this->checkExpiredSubscriptions();
        
        // Check for expiring soon subscriptions
        $expiringSoonCount = $this->checkExpiringSoonSubscriptions();
        
        // Check for low tokens
        $lowTokensCount = $this->checkLowTokens();
        
        // Reset monthly tokens if needed
        $resetCount = $this->resetMonthlyTokens();

        $this->info("Expired subscriptions: {$expiredCount}");
        $this->info("Expiring soon subscriptions: {$expiringSoonCount}");
        $this->info("Low tokens subscriptions: {$lowTokensCount}");
        $this->info("Reset tokens subscriptions: {$resetCount}");
        
        $this->info('Subscription check completed!');
        
        return 0;
    }

    protected function checkExpiredSubscriptions()
    {
        $expired = UserSubscription::where('status', 'active')
            ->where('expires_at', '<=', Carbon::now())
            ->get();

        foreach ($expired as $subscription) {
            $subscription->update([
                'status' => 'expired',
            ]);
            
            $this->line("Expired: User #{$subscription->user_id} - {$subscription->plan->name}");
        }

        return $expired->count();
    }

    protected function checkExpiringSoonSubscriptions()
    {
        $expiringSoon = UserSubscription::where('status', 'active')
            ->where('expiry_notified', false)
            ->where('expires_at', '<=', Carbon::now()->addDays(7))
            ->where('expires_at', '>', Carbon::now())
            ->get();

        foreach ($expiringSoon as $subscription) {
            $subscription->checkExpiry();
            $this->line("Expiring soon: User #{$subscription->user_id} - {$subscription->plan->name}");
        }

        return $expiringSoon->count();
    }

    protected function checkLowTokens()
    {
        $lowTokens = UserSubscription::where('status', 'active')
            ->where('low_tokens_notified', false)
            ->whereRaw('tokens_remaining < (SELECT tokens_limit FROM subscription_plans WHERE id = subscription_plan_id) * 0.2')
            ->get();

        foreach ($lowTokens as $subscription) {
            $subscription->checkLowTokens();
            $this->line("Low tokens: User #{$subscription->user_id} - {$subscription->tokens_remaining} remaining");
        }

        return $lowTokens->count();
    }

    protected function resetMonthlyTokens()
    {
        $toReset = UserSubscription::where('status', 'active')
            ->where(function($query) {
                $query->whereNull('last_token_reset_at')
                    ->orWhere('last_token_reset_at', '<=', Carbon::now()->subMonth());
            })
            ->whereHas('plan', function($query) {
                $query->where('billing_period', 'monthly');
            })
            ->get();

        foreach ($toReset as $subscription) {
            $subscription->update([
                'tokens_used' => 0,
                'tokens_remaining' => $subscription->plan->tokens_limit,
                'last_token_reset_at' => Carbon::now(),
                'low_tokens_notified' => false,
            ]);
            
            $this->line("Reset tokens: User #{$subscription->user_id} - {$subscription->plan->name}");
        }

        return $toReset->count();
    }
}
