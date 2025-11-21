<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Companies Table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->integer('member_count')->default(0);
            $table->bigInteger('translation_memory_size')->default(0);
            $table->boolean('sso_enabled')->default(false);
            $table->boolean('custom_domain_enabled')->default(false);
            $table->timestamps();
            
            $table->index('domain');
            $table->index('status');
        });

        // 2. Plans Table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->integer('words_limit')->default(0);
            $table->integer('daily_requests_limit')->default(0);
            $table->integer('document_size_limit')->default(0);
            $table->integer('document_count_limit')->default(0);
            $table->integer('video_minutes_monthly')->default(0);
            $table->integer('image_count_monthly')->default(0);
            $table->integer('ocr_limit')->default(0);
            $table->integer('game_json_size_limit')->default(0);
            $table->boolean('api_access_enabled')->default(false);
            $table->integer('rate_limit_per_minute')->default(10);
            $table->json('allowed_models')->nullable();
            $table->json('features_flags')->nullable();
            $table->enum('status', ['active', 'inactive', 'hidden'])->default('active');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
        });

        // Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'support_admin', 'financial_admin', 'technical_admin', 'user'])->default('user')->after('password');
            $table->foreignId('company_id')->nullable()->after('role')->constrained('companies')->nullOnDelete();
            $table->string('country')->nullable()->after('company_id');
            $table->string('language')->default('en')->after('country');
            $table->foreignId('plan_id')->nullable()->after('language')->constrained('plans')->nullOnDelete();
            $table->enum('account_status', ['active', 'suspended', 'deleted'])->default('active')->after('plan_id');
            $table->timestamp('last_login_at')->nullable()->after('account_status');
            $table->integer('total_translations')->default(0)->after('last_login_at');
            $table->json('custom_usage_limit')->nullable()->after('total_translations');
            $table->integer('extra_credits')->default(0)->after('custom_usage_limit');
            $table->boolean('two_factor_enabled')->default(false)->after('extra_credits');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->softDeletes();
            
            $table->index('company_id');
            $table->index('plan_id');
            $table->index('account_status');
        });

        // 3. Subscriptions Table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->string('stripe_subscription_id')->nullable();
            $table->enum('status', ['active', 'cancelled', 'expired', 'trial'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start');
            $table->timestamp('current_period_end');
            $table->boolean('auto_renewal_enabled')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('company_id');
            $table->index('status');
        });

        // 4. API Providers Table
        Schema::create('api_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['text', 'vision', 'speech', 'ocr'])->default('text');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->integer('key_count')->default(0);
            $table->json('performance_24h')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
        });

        // 5. API Keys Table
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('api_providers')->cascadeOnDelete();
            $table->string('name');
            $table->text('key_value'); // encrypted
            $table->enum('type', ['primary', 'backup'])->default('primary');
            $table->json('linked_plan_ids')->nullable();
            $table->decimal('usage_rate', 8, 2)->default(0);
            $table->decimal('error_rate', 5, 2)->default(0);
            $table->decimal('avg_response_time', 8, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'error'])->default('active');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            
            $table->index('provider_id');
            $table->index('status');
        });

        // 6. AI Models Table
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('provider_id')->constrained('api_providers')->cascadeOnDelete();
            $table->enum('model_type', ['text', 'document', 'image', 'video', 'game'])->default('text');
            $table->decimal('cost_per_1k_tokens', 10, 6)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('slug');
            $table->index('status');
        });

        // 7. Plan Models (Pivot)
        Schema::create('plan_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('ai_models')->cascadeOnDelete();
            $table->enum('translation_type', ['text', 'document', 'image', 'video', 'game']);
            $table->timestamps();
            
            $table->unique(['plan_id', 'model_id', 'translation_type']);
        });

        // 8. Translations Table
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->enum('type', ['text', 'document', 'image', 'video', 'game'])->default('text');
            $table->string('source_language');
            $table->string('target_language');
            $table->string('source_culture')->nullable();
            $table->string('target_culture')->nullable();
            $table->foreignId('model_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->foreignId('api_key_id')->nullable()->constrained('api_keys')->nullOnDelete();
            $table->integer('tokens_in')->default(0);
            $table->integer('tokens_out')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->decimal('cost', 10, 6)->default(0);
            $table->integer('response_time_ms')->default(0);
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('company_id');
            $table->index('type');
            $table->index('created_at');
        });

        // 9. Usage Stats Table
        Schema::create('usage_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->date('date');
            $table->bigInteger('tokens_in')->default(0);
            $table->bigInteger('tokens_out')->default(0);
            $table->bigInteger('total_tokens')->default(0);
            $table->integer('requests_count')->default(0);
            $table->integer('ocr_count')->default(0);
            $table->integer('video_minutes_processed')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
            $table->index('company_id');
            $table->index('date');
        });

        // 10. Invoices Table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->string('stripe_invoice_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['paid', 'pending', 'failed', 'refunded'])->default('pending');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('company_id');
            $table->index('status');
        });

        // 11. Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('stripe_payment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method')->nullable();
            $table->enum('status', ['succeeded', 'pending', 'failed'])->default('pending');
            $table->timestamps();
            
            $table->index('invoice_id');
            $table->index('user_id');
        });

        // 12. Activity Logs Table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('log_type', ['translation', 'error', 'api', 'billing', 'email', 'security', 'admin_action']);
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('log_type');
            $table->index('created_at');
        });

        // 13. System Health Table
        Schema::create('system_health', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->decimal('metric_value', 10, 2);
            $table->enum('status', ['healthy', 'warning', 'critical'])->default('healthy');
            $table->timestamp('checked_at');
            $table->timestamps();
            
            $table->index('metric_name');
            $table->index('checked_at');
        });

        // 14. KPI Snapshots Table
        Schema::create('kpi_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('active_customers')->default(0);
            $table->integer('active_workspaces')->default(0);
            $table->integer('daily_translations')->default(0);
            $table->integer('monthly_translations')->default(0);
            $table->decimal('mrr', 12, 2)->default(0);
            $table->decimal('arr', 12, 2)->default(0);
            $table->decimal('churn_rate', 5, 2)->default(0);
            $table->integer('avg_response_time_ms')->default(0);
            $table->decimal('error_rate', 5, 2)->default(0);
            $table->timestamps();
            
            $table->index('date');
        });

        // 15. Website Content Table
        Schema::create('website_content', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique();
            $table->string('page_title');
            $table->json('sections')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->timestamps();
            
            $table->index('page_slug');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_content');
        Schema::dropIfExists('kpi_snapshots');
        Schema::dropIfExists('system_health');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('usage_stats');
        Schema::dropIfExists('translations');
        Schema::dropIfExists('plan_models');
        Schema::dropIfExists('ai_models');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('api_providers');
        Schema::dropIfExists('subscriptions');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn([
                'role', 'company_id', 'country', 'language', 'plan_id',
                'account_status', 'last_login_at', 'total_translations',
                'custom_usage_limit', 'extra_credits', 'two_factor_enabled',
                'two_factor_secret', 'deleted_at'
            ]);
        });
        
        Schema::dropIfExists('plans');
        Schema::dropIfExists('companies');
    }
};
