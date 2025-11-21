<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Stripe Payment');
            $table->string('provider')->default('stripe'); // stripe, paypal, etc
            $table->string('stripe_public_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->string('stripe_webhook_secret')->nullable();
            $table->string('currency')->default('USD');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_test_mode')->default(true);
            $table->json('supported_currencies')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
