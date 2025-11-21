<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'primary_contact_email',
        'plan_id',
        'owner_id',
        'subscription_plan_id',
        'max_members',
        'settings',
        'is_active',
        'monthly_word_limit',
        'current_month_usage',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'monthly_word_limit' => 'integer',
        'current_month_usage' => 'integer',
        'settings' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function translationLogs(): HasMany
    {
        return $this->hasMany(TranslationLog::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(CompanyMember::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(CompanyMember::class)->where('is_active', true);
    }

    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function getMemberRole(User $user): ?string
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member?->role;
    }

    public function canAddMember(): bool
    {
        return $this->activeMembers()->count() < $this->max_members;
    }
}
