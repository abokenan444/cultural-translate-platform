<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TrainingData;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrainingDataController extends Controller
{
    /**
     * Get recent translations for rating
     */
    public function getRecent(Request $request)
    {
        $user = $request->user();
        
        $translations = Translation::where('user_id', $user->id)
            ->whereNotNull('source_text')
            ->whereNotNull('translated_text')
            ->whereNull('user_rating')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $translations,
        ]);
    }
    
    /**
     * Rate a translation
     */
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        $translation = Translation::findOrFail($id);
        
        // Check ownership
        if ($translation->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Update rating
        $translation->update([
            'user_rating' => $request->rating,
            'user_feedback' => $request->feedback,
        ]);
        
        // Auto-approve high-quality translations
        if ($request->rating >= 4) {
            $translation->update([
                'is_approved_for_training' => true,
                'quality_score' => min(1.0, $translation->quality_score + 0.2),
            ]);
        }
        
        // Copy to training_data if suitable
        if ($request->rating >= 4 && $translation->is_in_translation_memory) {
            $this->copyToTrainingData($translation);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Translation rated successfully',
            'data' => $translation,
        ]);
    }
    
    /**
     * Get training data statistics
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        
        // Overall statistics
        $totalTranslations = Translation::where('user_id', $user->id)->count();
        $ratedTranslations = Translation::where('user_id', $user->id)
            ->whereNotNull('user_rating')
            ->count();
        $approvedForTraining = Translation::where('user_id', $user->id)
            ->where('is_approved_for_training', true)
            ->count();
        
        // Training data statistics
        $trainingDataStats = TrainingData::getStatistics();
        
        // User's contribution
        $userContribution = TrainingData::where('user_id', $user->id)->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user_stats' => [
                    'total_translations' => $totalTranslations,
                    'rated_translations' => $ratedTranslations,
                    'approved_for_training' => $approvedForTraining,
                    'contribution_to_training' => $userContribution,
                ],
                'global_stats' => $trainingDataStats,
            ],
        ]);
    }
    
    /**
     * Export training data
     */
    public function export(Request $request)
    {
        $request->validate([
            'source_language' => 'nullable|string|max:10',
            'target_language' => 'nullable|string|max:10',
            'format' => 'nullable|in:jsonl,csv',
        ]);
        
        $sourceLang = $request->source_language;
        $targetLang = $request->target_language;
        $format = $request->format ?? 'jsonl';
        
        // Export data
        if ($format === 'jsonl') {
            $data = TrainingData::exportTrainingData($sourceLang, $targetLang);
            $filename = 'training_data_' . date('Y-m-d_H-i-s') . '.jsonl';
            
            return response($data, 200)
                ->header('Content-Type', 'application/x-ndjson')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }
        
        // CSV format
        $query = TrainingData::approved()->suitableForTraining()->highQuality();
        
        if ($sourceLang && $targetLang) {
            $query->languagePair($sourceLang, $targetLang);
        }
        
        $data = $query->get();
        
        $csv = "source_text,translated_text,source_language,target_language,tone,rating\n";
        foreach ($data as $item) {
            $csv .= '"' . str_replace('"', '""', $item->source_text) . '",';
            $csv .= '"' . str_replace('"', '""', $item->translated_text) . '",';
            $csv .= $item->source_language . ',';
            $csv .= $item->target_language . ',';
            $csv .= ($item->tone ?? '') . ',';
            $csv .= ($item->user_rating ?? '') . "\n";
        }
        
        $filename = 'training_data_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Bulk approve translations
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'translation_ids' => 'required|array',
            'translation_ids.*' => 'required|integer|exists:translations,id',
        ]);
        
        $user = $request->user();
        
        // Update translations
        Translation::whereIn('id', $request->translation_ids)
            ->where('user_id', $user->id)
            ->update([
                'is_approved_for_training' => true,
                'user_rating' => 5,
            ]);
        
        // Copy to training_data
        $translations = Translation::whereIn('id', $request->translation_ids)
            ->where('user_id', $user->id)
            ->get();
        
        foreach ($translations as $translation) {
            $this->copyToTrainingData($translation);
        }
        
        return response()->json([
            'success' => true,
            'message' => count($request->translation_ids) . ' translations approved for training',
        ]);
    }
    
    /**
     * Copy translation to training_data table
     */
    private function copyToTrainingData(Translation $translation)
    {
        // Check if already exists
        $exists = TrainingData::where('user_id', $translation->user_id)
            ->where('source_text', $translation->source_text)
            ->where('translated_text', $translation->translated_text)
            ->exists();
        
        if ($exists) {
            return;
        }
        
        // Create training data entry
        TrainingData::create([
            'user_id' => $translation->user_id,
            'project_id' => null,
            'source_text' => $translation->source_text,
            'source_language' => $translation->source_language,
            'target_language' => $translation->target_language,
            'translated_text' => $translation->translated_text,
            'tone' => $translation->tone,
            'context' => null,
            'industry' => null,
            'model_used' => $translation->ai_model,
            'user_rating' => $translation->user_rating,
            'user_feedback' => $translation->user_feedback,
            'is_approved' => $translation->is_approved_for_training,
            'word_count' => str_word_count($translation->source_text),
            'tokens_used' => $translation->tokens_in + $translation->tokens_out,
            'is_suitable_for_training' => $translation->is_in_translation_memory,
            'contains_sensitive_data' => false,
            'data_quality' => $translation->user_rating >= 4 ? 'good' : 'pending',
        ]);
    }
}
