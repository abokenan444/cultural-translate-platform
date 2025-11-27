<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingData extends Model
{
    use HasFactory;

    protected $table = 'training_data';

    protected $fillable = [
        'user_id',
        'project_id',
        'source_text',
        'source_language',
        'target_language',
        'translated_text',
        'tone',
        'context',
        'industry',
        'model_used',
        'user_rating',
        'user_feedback',
        'is_approved',
        'word_count',
        'tokens_used',
        'is_suitable_for_training',
        'contains_sensitive_data',
        'data_quality',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_suitable_for_training' => 'boolean',
        'contains_sensitive_data' => 'boolean',
        'user_rating' => 'integer',
        'word_count' => 'integer',
        'tokens_used' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeSuitableForTraining($query)
    {
        return $query->where('is_suitable_for_training', true)
                     ->where('contains_sensitive_data', false);
    }

    public function scopeHighQuality($query)
    {
        return $query->whereIn('data_quality', ['good', 'excellent'])
                     ->where('user_rating', '>=', 4);
    }

    public function scopeLanguagePair($query, string $sourceLang, string $targetLang)
    {
        return $query->where('source_language', $sourceLang)
                     ->where('target_language', $targetLang);
    }

    /**
     * Get statistics for training data
     */
    public static function getStatistics(): array
    {
        $total = self::count();
        $approved = self::approved()->count();
        $suitable = self::suitableForTraining()->count();
        $highQuality = self::highQuality()->count();
        
        $languagePairs = self::selectRaw('source_language, target_language, COUNT(*) as count')
            ->groupBy('source_language', 'target_language')
            ->get();
        
        $qualityDistribution = self::selectRaw('data_quality, COUNT(*) as count')
            ->groupBy('data_quality')
            ->get();
        
        return [
            'total' => $total,
            'approved' => $approved,
            'suitable_for_training' => $suitable,
            'high_quality' => $highQuality,
            'approval_rate' => $total > 0 ? round(($approved / $total) * 100, 2) : 0,
            'quality_rate' => $total > 0 ? round(($highQuality / $total) * 100, 2) : 0,
            'language_pairs' => $languagePairs,
            'quality_distribution' => $qualityDistribution,
        ];
    }

    /**
     * Export training data in JSONL format
     */
    public static function exportTrainingData(string $sourceLang = null, string $targetLang = null): string
    {
        $query = self::approved()->suitableForTraining()->highQuality();
        
        if ($sourceLang && $targetLang) {
            $query->languagePair($sourceLang, $targetLang);
        }
        
        $data = $query->get();
        
        $jsonl = '';
        foreach ($data as $item) {
            $jsonl .= json_encode([
                'source' => $item->source_text,
                'target' => $item->translated_text,
                'source_lang' => $item->source_language,
                'target_lang' => $item->target_language,
                'tone' => $item->tone,
                'context' => $item->context,
                'industry' => $item->industry,
                'rating' => $item->user_rating,
            ]) . "\n";
        }
        
        return $jsonl;
    }
}
