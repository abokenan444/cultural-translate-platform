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
        Schema::table('translations', function (Blueprint $table) {
            // Add missing core fields
            $table->text('source_text')->after('target_culture');
            $table->text('translated_text')->nullable()->after('source_text');
            
            // Deep Learning & Training Data fields
            $table->decimal('quality_score', 3, 2)->nullable()->after('translated_text')
                ->comment('AI-generated quality score (0.00-1.00)');
            
            $table->integer('user_rating')->nullable()->after('quality_score')
                ->comment('User rating (1-5 stars)');
            
            $table->text('user_feedback')->nullable()->after('user_rating')
                ->comment('User feedback or corrections');
            
            $table->text('corrected_translation')->nullable()->after('user_feedback')
                ->comment('User-corrected translation for training');
            
            $table->boolean('is_approved_for_training')->default(false)->after('corrected_translation')
                ->comment('Approved for ML model training');
            
            $table->boolean('is_in_translation_memory')->default(true)->after('is_approved_for_training')
                ->comment('Include in translation memory');
            
            // Additional metadata for ML
            $table->json('ml_metadata')->nullable()->after('is_in_translation_memory')
                ->comment('ML-specific metadata (embeddings, confidence, etc.)');
            
            $table->string('ai_model')->nullable()->after('ml_metadata')
                ->comment('AI model used (gpt-4, gpt-3.5, custom, etc.)');
            
            $table->integer('characters_count')->default(0)->after('ai_model');
            
            $table->boolean('cultural_adaptation')->default(false)->after('characters_count');
            
            $table->boolean('preserve_brand_voice')->default(false)->after('cultural_adaptation');
            
            // Foreign keys for compatibility
            $table->foreignId('source_language_id')->nullable()->after('source_language')
                ->constrained('languages')->nullOnDelete();
            
            $table->foreignId('target_language_id')->nullable()->after('target_language')
                ->constrained('languages')->nullOnDelete();
            
            // Indexes for ML queries
            $table->index('quality_score');
            $table->index('user_rating');
            $table->index('is_approved_for_training');
            $table->index('is_in_translation_memory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex(['quality_score']);
            $table->dropIndex(['user_rating']);
            $table->dropIndex(['is_approved_for_training']);
            $table->dropIndex(['is_in_translation_memory']);
            
            $table->dropForeign(['source_language_id']);
            $table->dropForeign(['target_language_id']);
            
            $table->dropColumn([
                'source_text',
                'translated_text',
                'quality_score',
                'user_rating',
                'user_feedback',
                'corrected_translation',
                'is_approved_for_training',
                'is_in_translation_memory',
                'ml_metadata',
                'ai_model',
                'characters_count',
                'cultural_adaptation',
                'preserve_brand_voice',
                'source_language_id',
                'target_language_id',
            ]);
        });
    }
};
