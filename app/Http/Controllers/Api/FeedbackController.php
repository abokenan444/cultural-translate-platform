<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Models\TranslationFeedback;
use App\Models\TranslationVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Submit feedback for a translation
     */
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'translation_id' => 'required|exists:translations,id',
            'rating' => 'required|integer|min:1|max:5',
            'tag' => 'nullable|string|in:cultural,tone,wording,error,excellent',
            'comment' => 'nullable|string|max:1000',
            'suggested_text' => 'nullable|string|max:10000',
        ]);

        $user = Auth::user();
        $translation = Translation::findOrFail($request->translation_id);

        // Create feedback
        $feedback = TranslationFeedback::create([
            'translation_id' => $translation->id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'tag' => $request->tag,
            'comment' => $request->comment,
            'suggested_text' => $request->suggested_text,
        ]);

        // If user suggested a better translation, create a version
        if ($request->suggested_text) {
            $version = TranslationVersion::create([
                'translation_id' => $translation->id,
                'user_id' => $user->id,
                'source_text' => $translation->source_text,
                'translated_text' => $request->suggested_text,
                'tone' => $translation->tone,
                'culture_code' => $translation->target_language,
                'is_suggested' => true,
                'is_approved' => false,
                'score' => $request->rating,
            ]);

            $feedback->update(['translation_version_id' => $version->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'شكراً لتقييمك! سنستخدمه لتحسين الترجمات المستقبلية.',
            'feedback' => $feedback,
        ]);
    }

    /**
     * Get feedback for a translation
     */
    public function getFeedback(Request $request, $translationId)
    {
        $translation = Translation::findOrFail($translationId);
        
        $feedback = TranslationFeedback::where('translation_id', $translation->id)
            ->with(['user', 'translationVersion'])
            ->orderBy('created_at', 'desc')
            ->get();

        $averageRating = $feedback->avg('rating');
        $totalFeedback = $feedback->count();

        return response()->json([
            'success' => true,
            'feedback' => $feedback,
            'average_rating' => round($averageRating, 2),
            'total_feedback' => $totalFeedback,
        ]);
    }

    /**
     * Get suggested versions for a translation
     */
    public function getSuggestedVersions(Request $request, $translationId)
    {
        $translation = Translation::findOrFail($translationId);
        
        $versions = TranslationVersion::where('translation_id', $translation->id)
            ->where('is_suggested', true)
            ->with(['user'])
            ->orderBy('score', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'versions' => $versions,
        ]);
    }

    /**
     * Approve a suggested version (Admin only)
     */
    public function approveVersion(Request $request, $versionId)
    {
        $user = Auth::user();
        
        // Check if user is admin
        if (!$user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا الإجراء',
            ], 403);
        }

        $version = TranslationVersion::findOrFail($versionId);
        $version->approve();

        // Update the original translation
        $translation = $version->translation;
        $translation->update([
            'translated_text' => $version->translated_text,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تمت الموافقة على النسخة المقترحة',
            'version' => $version,
        ]);
    }

    /**
     * Get user stats
     */
    public function getUserStats(Request $request)
    {
        $user = Auth::user();
        $stats = $user->stats;

        if (!$stats) {
            $stats = $user->stats()->create([
                'total_requests' => 0,
                'total_tokens' => 0,
                'monthly_requests' => 0,
                'monthly_tokens' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }
}
