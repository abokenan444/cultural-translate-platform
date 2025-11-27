<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'tokens_used',
        'tokens_remaining',
        'starts_at',
        'expires_at',
        'cancelled_at',
        'last_token_reset_at',
        'auto_renew',
        'low_tokens_notified',
        'expiry_notified',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'last_token_reset_at' => 'datetime',
        'auto_renew' => 'boolean',
        'low_tokens_notified' => 'boolean',
        'expiry_notified' => 'boolean',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function tokenUsageLogs()
    {
        return $this->hasMany(TokenUsageLog::class);
    }

    public function useTokens(int $amount, string $action, ?string $description = null, ?array $metadata = null)
    {
        if ($this->tokens_remaining < $amount) {
            throw new \Exception('Insufficient tokens. Please upgrade your plan.');
        }

        $tokensBefore = $this->tokens_remaining;
        $this->tokens_used += $amount;
        $this->tokens_remaining -= $amount;
        $this->save();

        // Log the usage
        TokenUsageLog::create([
            'user_id' => $this->user_id,
            'user_subscription_id' => $this->id,
            'tokens_used' => $amount,
            'tokens_before' => $tokensBefore,
            'tokens_after' => $this->tokens_remaining,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);

        // Check if low tokens notification should be sent
        $this->checkLowTokens();

        return $this;
    }

    public function checkLowTokens()
    {
        if (!$this->low_tokens_notified && $this->plan) {
            $percentage = ($this->tokens_remaining / $this->plan->tokens_limit) * 100;
            
            if ($percentage <= 20) {
                // Send notification
                $this->user->notify(new \App\Notifications\LowTokensNotification($this));
                $this->update(['low_tokens_notified' => true]);
            }
        }
    }

    public function checkExpiry()
    {
        if (!$this->expiry_notified && $this->expires_at) {
            $daysUntilExpiry = Carbon::now()->diffInDays($this->expires_at, false);
            
            if ($daysUntilExpiry <= 7 && $daysUntilExpiry > 0) {
                // Send notification
                $this->user->notify(new \App\Notifications\SubscriptionExpiryNotification($this));
                $this->update(['expiry_notified' => true]);
            }
        }
    }

    public function isActive()
    {
        return $this->status === 'active' 
            && $this->tokens_remaining > 0 
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasTokens()
    {
        return $this->tokens_remaining > 0;
    }

    public function getRemainingCharacters()
    {
        // Estimate: 1 token ≈ 4 characters
        return $this->tokens_remaining * 4;
    }

    public function getTokenUsagePercentageAttribute()
    {
        if (!$this->plan || $this->plan->tokens_limit == 0) {
            return 0;
        }
        
        return ($this->tokens_used / $this->plan->tokens_limit) * 100;
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function($q) {
                $q->where('expires_at', '<=', Carbon::now());
            });
    }
}
