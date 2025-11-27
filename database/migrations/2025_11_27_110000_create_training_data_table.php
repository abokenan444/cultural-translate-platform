<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            
            // Translation data
            $table->text('source_text');
            $table->string('source_language', 10);
            $table->string('target_language', 10);
            $table->text('translated_text');
            
            // Context & metadata
            $table->string('tone', 50)->nullable(); // formal, casual, technical, etc.
            $table->text('context')->nullable(); // Business context, industry, etc.
            $table->string('industry', 100)->nullable(); // Healthcare, Legal, Tech, etc.
            $table->string('model_used', 50)->nullable(); // gpt-4, gpt-3.5, custom, etc.
            
            // Quality & feedback
            $table->tinyInteger('user_rating')->nullable(); // 1-5 stars
            $table->text('user_feedback')->nullable();
            $table->boolean('is_approved')->default(false);
            
            // Statistics
            $table->integer('word_count')->default(0);
            $table->integer('tokens_used')->default(0);
            
            // Training suitability
            $table->boolean('is_suitable_for_training')->default(true);
            $table->boolean('contains_sensitive_data')->default(false);
            $table->enum('data_quality', ['pending', 'good', 'excellent', 'poor'])->default('pending');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['source_language', 'target_language']);
            $table->index('is_approved');
            $table->index('is_suitable_for_training');
            $table->index('data_quality');
            $table->index('user_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_data');
    }
};
