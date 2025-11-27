<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Models\UsageLog;
use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TranslationController extends Controller
{
    /**
     * Translate text
     */
    public function translate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:10000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'ai_model' => 'nullable|string|in:gpt-4,gpt-3.5-turbo,google-translate,deepl',
            'cultural_adaptation' => 'nullable|boolean',
            'preserve_brand_voice' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $text = $request->text;
        $sourceLanguage = $request->source_language;
        $targetLanguage = $request->target_language;
        $aiModel = $request->ai_model ?? 'gpt-4';
        $culturalAdaptation = $request->cultural_adaptation ?? true;
        $preserveBrandVoice = $request->preserve_brand_voice ?? false;

        // Check user's subscription limits
        $subscription = $user->subscription;
        
        // Auto-create free trial if user doesn't have a subscription
        if (!$subscription) {
            $freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')
                ->orWhere('price', 0)
                ->first();
            
            if ($freePlan) {
                $subscription = \App\Models\UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $freePlan->id,
                    'status' => 'active',
                    'tokens_used' => 0,
                    'tokens_remaining' => $freePlan->tokens_limit ?? 100000,
                    'starts_at' => now(),
                    'expires_at' => now()->addDays(14),
                    'auto_renew' => false,
                ]);
                // Refresh user's subscription relationship
                $user->load('subscriptions');
            }
        }
        
        if (!$subscription || !$subscription->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ], 403);
        }

        // Check character limit
        $charactersUsed = strlen($text);
        $remainingCharacters = $subscription->getRemainingCharacters();
        
        if ($charactersUsed > $remainingCharacters) {
            return response()->json([
                'success' => false,
                'message' => 'Character limit exceeded',
                'data' => [
                    'characters_requested' => $charactersUsed,
                    'characters_remaining' => $remainingCharacters,
                ]
            ], 403);
        }

        // Perform translation using TranslationService
        $translationService = new TranslationService();
        
        $result = $translationService->translate([
            'text' => $text,
            'source_language' => $sourceLanguage,
            'target_language' => $targetLanguage,
            'model' => $aiModel,
            'tone' => $request->tone ?? 'neutral',
            'cultural_adaptation' => $culturalAdaptation,
            'preserve_brand_voice' => $preserveBrandVoice,
        ], $user);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Translation failed'
            ], 500);
        }
        
        $translatedText = $result['translation'];
        $tokensUsed = $result['tokens_used'] ?? 0;

        // Translation is already saved by TranslationService
        // Get the translation ID from result
        $translationId = $result['translation_id'] ?? null;
        
        // Update subscription tokens
        if (isset($tokensUsed)) {
            $subscription->decrement('tokens_remaining', $tokensUsed);
        }

        // Log usage
        UsageLog::create([
            'user_id' => $user->id,
            'action' => 'translation',
            'characters_used' => $charactersUsed,
            'api_calls' => 1,
            'metadata' => json_encode([
                'translation_id' => $translationId,
                'source_language' => $sourceLanguage,
                'target_language' => $targetLanguage,
                'ai_model' => $aiModel,
                'tokens_used' => $tokensUsed ?? 0,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Translation completed successfully',
            'data' => [
                'translation_id' => $translationId,
                'source_text' => $text,
                'translated_text' => $translatedText,
                'source_language' => $sourceLanguage,
                'target_language' => $targetLanguage,
                'characters_used' => $charactersUsed,
                'tokens_used' => $tokensUsed ?? 0,
                'ai_model' => $aiModel,
                'cultural_adaptation' => $culturalAdaptation,
                'cost' => $result['cost'] ?? 0,
            ]
        ]);
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'texts' => 'required|array|max:100',
            'texts.*' => 'required|string|max:10000',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'ai_model' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $results = [];
        foreach ($request->texts as $text) {
            $translatedText = $this->performTranslation(
                $text,
                $request->source_language,
                $request->target_language,
                $request->ai_model ?? 'gpt-4'
            );

            $results[] = [
                'source_text' => $text,
                'translated_text' => $translatedText,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Batch translation completed',
            'data' => [
                'translations' => $results,
                'total_count' => count($results),
            ]
        ]);
    }

    /**
     * Get translation history
     */
    public function history(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        
        $translations = Translation::where('user_id', $request->user()->id)
            ->with(['sourceLanguage', 'targetLanguage'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $translations
        ]);
    }

    /**
     * Get single translation
     */
    public function show(Request $request, $id)
    {
        $translation = Translation::where('user_id', $request->user()->id)
            ->with(['sourceLanguage', 'targetLanguage'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $translation
        ]);
    }

    /**
     * Delete translation
     */
    public function destroy(Request $request, $id)
    {
        $translation = Translation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $translation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Translation deleted successfully'
        ]);
    }

    /**
     * Perform actual translation (mock implementation)
     */
    private function performTranslation(
        string $text,
        string $sourceLanguage,
        string $targetLanguage,
        string $aiModel = 'gpt-4',
        bool $culturalAdaptation = true,
        bool $preserveBrandVoice = false
    ): string {
        // This is a mock implementation
        // In production, this would call OpenAI, Google Translate, or DeepL APIs
        
        $translations = [
            'en' => [
                'ar' => 'مرحبا بالعالم! هذه ترجمة تجريبية.',
                'es' => '¡Hola Mundo! Esta es una traducción de prueba.',
                'fr' => 'Bonjour le monde! Ceci est une traduction test.',
            ],
            'ar' => [
                'en' => 'Hello World! This is a test translation.',
                'es' => '¡Hola Mundo! Esta es una traducción de prueba.',
            ],
        ];

        return $translations[$sourceLanguage][$targetLanguage] ?? 
               "[Translated from {$sourceLanguage} to {$targetLanguage}]: {$text}";
    }
}
