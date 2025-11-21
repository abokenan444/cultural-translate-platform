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
        'is_active',
        'monthly_word_limit',
        'current_month_usage',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'monthly_word_limit' => 'integer',
        'current_month_usage' => 'integer',
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
}
