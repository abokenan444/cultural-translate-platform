<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'stripe_public_key',
        'stripe_secret_key',
        'stripe_webhook_secret',
        'currency',
        'is_active',
        'is_test_mode',
        'supported_currencies',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'supported_currencies' => 'array',
    ];

    protected $hidden = [
        'stripe_secret_key',
        'stripe_webhook_secret',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getStripeSecretKeyAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function setStripeSecretKeyAttribute($value)
    {
        $this->attributes['stripe_secret_key'] = $value ? encrypt($value) : null;
    }

    public function getStripeWebhookSecretAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function setStripeWebhookSecretAttribute($value)
    {
        $this->attributes['stripe_webhook_secret'] = $value ? encrypt($value) : null;
    }
}
