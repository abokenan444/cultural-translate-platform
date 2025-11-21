<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('billing_period', ['monthly', 'yearly', 'lifetime'])->default('monthly');
            $table->integer('tokens_limit')->comment('Monthly token limit');
            $table->json('features')->nullable()->comment('Array of features included');
            $table->integer('max_projects')->default(1);
            $table->integer('max_team_members')->default(1);
            $table->boolean('api_access')->default(false);
            $table->boolean('priority_support')->default(false);
            $table->boolean('custom_integrations')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_custom')->default(false)->comment('Custom plan requires contact');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
