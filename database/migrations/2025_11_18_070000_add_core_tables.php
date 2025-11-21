<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Feature Flags
        if (!Schema::hasTable('feature_flags')) {
            Schema::create('feature_flags', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('status', ['enabled', 'disabled'])->default('disabled');
                $table->integer('rollout_percentage')->default(0);
                $table->timestamps();
            });
        }

        // 2. System Settings
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value');
                $table->enum('type', ['system', 'api', 'branding', 'email', 'billing'])->default('system');
                $table->timestamp('updated_at');
            });
        }

        // 3. Background Jobs
        if (!Schema::hasTable('background_jobs')) {
            Schema::create('background_jobs', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['translation', 'video', 'ocr', 'document', 'export'])->default('translation');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
                $table->enum('status', ['pending', 'running', 'failed', 'completed'])->default('pending');
                $table->integer('progress_percentage')->default(0);
                $table->json('payload')->nullable();
                $table->json('result')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('background_jobs');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('feature_flags');
    }
};
