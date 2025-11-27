<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdvancedTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiTranslationController extends Controller
{
    protected $translationService;

    public function __construct(AdvancedTranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    /**
     * Translate text with cultural adaptation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function translate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:10000',
            'source_language' => 'required|string|in:auto,en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr',
            'target_language' => 'required|string|in:en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr',
            'tone' => 'nullable|string|in:professional,friendly,formal,casual,technical,marketing,creative,empathetic,authoritative',
            'context' => 'nullable|string|max:500',
            'industry' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->translationService->translate([
                "text" => $request->text,
                "source_language" => $request->source_language,
                "target_language" => $request->target_language,
                "tone" => $request->tone ?? "professional",
                "context" => $request->context,
                "task_type" => null,
                "industry" => $request->industry
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detect language of text
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detectLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->translationService->detectLanguage($request->text);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supported languages
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function languages()
    {
        try {
            $languages = $this->translationService->getSupportedLanguages();
            return response()->json([
                'success' => true,
                'languages' => $languages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available tones
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function tones()
    {
        try {
            $tones = $this->translationService->getToneOptions();
            return response()->json([
                'success' => true,
                'tones' => $tones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get API usage statistics
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        try {
            $user = $request->user();
            
            $stats = [
                'total_translations' => \DB::table('translations')
                    ->where('user_id', $user->id)
                    ->count(),
                'total_tokens' => \DB::table('translations')
                    ->where('user_id', $user->id)
                    ->sum('total_tokens'),
                'average_quality' => \DB::table('translations')
                    ->where('user_id', $user->id)
                    ->avg('quality_score'),
                'languages_used' => \DB::table('translations')
                    ->where('user_id', $user->id)
                    ->distinct()
                    ->count('target_language'),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Health check endpoint
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function health()
    {
        return response()->json([
            'success' => true,
            'status' => 'healthy',
            'version' => '2.0',
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Demo translation endpoint (public, no authentication)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function demoTranslate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:1000',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'الرجاء التحقق من البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $langMap = [
                'English' => 'en',
                'العربية' => 'ar',
                'Español' => 'es',
                'Français' => 'fr',
                'Deutsch' => 'de',
            ];

            $sourceCode = $langMap[$request->source_language] ?? $request->source_language;
            $targetCode = $langMap[$request->target_language] ?? $request->target_language;

            $result = $this->translationService->translate([
                'text' => $request->text,
                'source_language' => $sourceCode,
                'target_language' => $targetCode,
                'tone' => 'professional',
                'context' => null,
                'task_type' => null,
                'industry' => null
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'translation' => $result['translated_text']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'] ?? 'فشلت الترجمة'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الترجمة: ' . $e->getMessage()
            ], 500);
        }
    }
}
