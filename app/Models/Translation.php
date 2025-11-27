<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'type',
        'source_language',
        'target_language',
        'source_language_id',
        'target_language_id',
        'source_culture',
        'target_culture',
        'source_text',
        'translated_text',
        'model_id',
        'api_key_id',
        'tokens_in',
        'tokens_out',
        'total_tokens',
        'cost',
        'response_time_ms',
        'status',
        'error_message',
        // Deep Learning fields
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
    ];

    protected $casts = [
        'cultural_adaptation' => 'boolean',
        'preserve_brand_voice' => 'boolean',
        'is_approved_for_training' => 'boolean',
        'is_in_translation_memory' => 'boolean',
        'ml_metadata' => 'array',
        'quality_score' => 'decimal:2',
        'cost' => 'decimal:6',
    ];

    /**
     * Get the user that owns the translation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that owns the translation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the AI model used for this translation.
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(AIModel::class, 'model_id');
    }

    /**
     * Get the API key used for this translation.
     */
    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(APIKey::class, 'api_key_id');
    }

    /**
     * Get the source language.
     */
    public function sourceLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'source_language_id');
    }

    /**
     * Get the target language.
     */
    public function targetLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'target_language_id');
    }

    /**
     * Scope for approved training data.
     */
    public function scopeApprovedForTraining($query)
    {
        return $query->where('is_approved_for_training', true)
            ->where('status', 'success')
            ->whereNotNull('translated_text');
    }

    /**
     * Scope for translation memory.
     */
    public function scopeInTranslationMemory($query)
    {
        return $query->where('is_in_translation_memory', true)
            ->where('status', 'success');
    }

    /**
     * Scope for high quality translations.
     */
    public function scopeHighQuality($query, $minScore = 0.8)
    {
        return $query->where('quality_score', '>=', $minScore)
            ->orWhere('user_rating', '>=', 4);
    }

    /**
     * Find similar translations in memory.
     */
    public static function findSimilar($sourceText, $sourceLang, $targetLang, $threshold = 0.9)
    {
        // TODO: Implement similarity search using embeddings or fuzzy matching
        return self::inTranslationMemory()
            ->where('source_language', $sourceLang)
            ->where('target_language', $targetLang)
            ->where('source_text', 'LIKE', '%' . substr($sourceText, 0, 50) . '%')
            ->highQuality()
            ->limit(5)
            ->get();
    }

    /**
     * Export training data for ML model.
     */
    public static function exportTrainingData($format = 'json')
    {
        $data = self::approvedForTraining()
            ->select([
                'source_text',
                'translated_text',
                'source_language',
                'target_language',
                'quality_score',
                'user_rating',
                'cultural_adaptation',
            ])
            ->get();

        if ($format === 'json') {
            return $data->toJson();
        } elseif ($format === 'csv') {
            // TODO: Implement CSV export
        }

        return $data;
    }
}
