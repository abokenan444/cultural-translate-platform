<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the company that owns the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get the user's complaints.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get the user's token usage logs.
     */
    public function tokenUsageLogs()
    {
        return $this->hasMany(TokenUsageLog::class);
    }

    /**
     * Check if user has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if user has sufficient tokens.
     */
    public function hasTokens(int $amount = 1): bool
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            return false;
        }
        
        return $subscription->tokens_remaining >= $amount;
    }

    /**
     * Get the user's payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the user's token usage logs.
     */
    public function tokenUsageLogs()
    {
        return $this->hasMany(TokenUsageLog::class);
    }

    /**
     * Get the user's owned companies.
     */
    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    /**
     * Get the user's company memberships.
     */
    public function companyMemberships()
    {
        return $this->hasMany(CompanyMember::class);
    }

    /**
     * Get all companies the user is a member of.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_members')
            ->withPivot(['role', 'permissions', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Check if user is owner of a company.
     */
    public function isCompanyOwner(Company $company): bool
    {
        return $this->id === $company->owner_id;
    }

    /**
     * Check if user is member of a company.
     */
    public function isMemberOf(Company $company): bool
    {
        return $this->companyMemberships()
            ->where('company_id', $company->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get user's role in a company.
     */
    public function getRoleInCompany(Company $company): ?string
    {
        $membership = $this->companyMemberships()
            ->where('company_id', $company->id)
            ->first();
        
        return $membership?->role;
    }
}
