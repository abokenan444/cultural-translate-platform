<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_period',
        'tokens_limit',
        'features',
        'max_projects',
        'max_team_members',
        'api_access',
        'priority_support',
        'custom_integrations',
        'is_popular',
        'is_custom',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'api_access' => 'boolean',
        'priority_support' => 'boolean',
        'custom_integrations' => 'boolean',
        'is_popular' => 'boolean',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(UserSubscription::class)->where('status', 'active');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getBillingPeriodTextAttribute()
    {
        return match($this->billing_period) {
            'monthly' => 'شهرياً',
            'yearly' => 'سنوياً',
            'lifetime' => 'مدى الحياة',
            default => $this->billing_period,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotCustom($query)
    {
        return $query->where('is_custom', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }
}
