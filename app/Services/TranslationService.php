<?php

namespace App\Services;

use App\Models\Translation;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translate text using OpenAI API
     */
    public function translate(array $data, User $user): array
    {
        try {
            // Prepare translation request
            $sourceText = $data['text'];
            $sourceLang = $data['source_language'] ?? 'auto';
            $targetLang = $data['target_language'];
            $model = $data['model'] ?? 'gpt-4';
            $tone = $data['tone'] ?? 'neutral';
            $culturalAdaptation = $data['cultural_adaptation'] ?? false;
            $preserveBrandVoice = $data['preserve_brand_voice'] ?? false;
            
            // Call OpenAI API
            $translatedText = $this->callOpenAI($sourceText, $sourceLang, $targetLang, $model, $tone);
            
            // Calculate tokens and cost
            $tokensIn = $this->estimateTokens($sourceText);
            $tokensOut = $this->estimateTokens($translatedText);
            $cost = $this->calculateCost($tokensIn, $tokensOut, $model);
            
            // Save translation to database (with training data)
            $translation = $this->saveTranslation([
                'user_id' => $user->id,
                'source_text' => $sourceText,
                'translated_text' => $translatedText,
                'source_language' => $sourceLang,
                'target_language' => $targetLang,
                'ai_model' => $model,
                'tone' => $tone,
                'cultural_adaptation' => $culturalAdaptation,
                'preserve_brand_voice' => $preserveBrandVoice,
                'characters_count' => strlen($sourceText),
                'tokens_in' => $tokensIn,
                'tokens_out' => $tokensOut,
                'cost' => $cost,
                'status' => 'success',
            ]);
            
            return [
                'success' => true,
                'translation' => $translatedText,
                'translation_id' => $translation->id,
                'tokens_used' => $tokensIn + $tokensOut,
                'cost' => $cost,
            ];
            
        } catch (\Exception $e) {
            Log::error('Translation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Translation failed: ' . $e->getMessage(),
            ];
        }
    }
    
    /**
     * Call OpenAI API for translation
     */
    private function callOpenAI(string $text, string $sourceLang, string $targetLang, string $model, string $tone): string
    {
        // Build prompt
        $prompt = $this->buildPrompt($text, $sourceLang, $targetLang, $tone);
        
        // Call OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model === 'gpt-4' ? 'gpt-4' : 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional translator with expertise in cultural adaptation.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.3,
        ]);
        
        if ($response->failed()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }
        
        $result = $response->json();
        
        return $result['choices'][0]['message']['content'] ?? '';
    }
    
    /**
     * Build translation prompt
     */
    private function buildPrompt(string $text, string $sourceLang, string $targetLang, string $tone): string
    {
        $toneInstruction = '';
        
        if ($tone === 'formal') {
            $toneInstruction = 'Use formal language and professional tone.';
        } elseif ($tone === 'casual') {
            $toneInstruction = 'Use casual and friendly language.';
        }
        
        return "Translate the following text from {$sourceLang} to {$targetLang}. {$toneInstruction}\n\nText: {$text}\n\nTranslation:";
    }
    
    /**
     * Save translation to database (with training data collection)
     */
    private function saveTranslation(array $data): Translation
    {
        try {
            // Check for sensitive data
            $containsSensitiveData = $this->detectSensitiveData($data['source_text']);
            
            // Determine if suitable for training
            $isSuitableForTraining = !$containsSensitiveData && strlen($data['source_text']) >= 10;
            
            // Calculate quality score (initial estimate)
            $qualityScore = $this->calculateInitialQualityScore($data);
            
            // Save translation
            $translation = Translation::create([
                'user_id' => $data['user_id'],
                'source_text' => $data['source_text'],
                'translated_text' => $data['translated_text'],
                'source_language' => $data['source_language'],
                'target_language' => $data['target_language'],
                'ai_model' => $data['ai_model'],
                'tone' => $data['tone'] ?? null,
                'cultural_adaptation' => $data['cultural_adaptation'] ?? false,
                'preserve_brand_voice' => $data['preserve_brand_voice'] ?? false,
                'characters_count' => $data['characters_count'],
                'tokens_in' => $data['tokens_in'],
                'tokens_out' => $data['tokens_out'],
                'cost' => $data['cost'],
                'status' => $data['status'],
                'quality_score' => $qualityScore,
                'is_approved_for_training' => false, // Will be approved after user review
                'is_in_translation_memory' => $isSuitableForTraining,
                'ml_metadata' => json_encode([
                    'contains_sensitive_data' => $containsSensitiveData,
                    'suitable_for_training' => $isSuitableForTraining,
                    'created_at' => now()->toIso8601String(),
                ]),
            ]);
            
            Log::info("Translation saved for training data collection", [
                'translation_id' => $translation->id,
                'suitable_for_training' => $isSuitableForTraining,
            ]);
            
            return $translation;
            
        } catch (\Exception $e) {
            // Silently fail - don't break translation if saving fails
            Log::error('Failed to save translation for training: ' . $e->getMessage());
            
            // Create minimal translation record
            return Translation::create([
                'user_id' => $data['user_id'],
                'source_language' => $data['source_language'],
                'target_language' => $data['target_language'],
                'status' => $data['status'],
            ]);
        }
    }
    
    /**
     * Detect sensitive data in text
     */
    private function detectSensitiveData(string $text): bool
    {
        // Patterns for sensitive data
        $patterns = [
            '/\b\d{3}-\d{2}-\d{4}\b/', // SSN
            '/\b\d{16}\b/', // Credit card
            '/\b\d{4}[- ]?\d{4}[- ]?\d{4}[- ]?\d{4}\b/', // Credit card with spaces
            '/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i', // Email (may contain personal info)
            '/\b\+?\d{10,15}\b/', // Phone number
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Calculate initial quality score
     */
    private function calculateInitialQualityScore(array $data): float
    {
        $score = 0.5; // Base score
        
        // Longer texts tend to be more useful
        if ($data['characters_count'] > 100) {
            $score += 0.2;
        }
        
        // Professional tone is more valuable
        if (($data['tone'] ?? '') === 'formal') {
            $score += 0.1;
        }
        
        // Cultural adaptation adds value
        if ($data['cultural_adaptation'] ?? false) {
            $score += 0.1;
        }
        
        return min(1.0, $score);
    }
    
    /**
     * Estimate tokens for text
     */
    private function estimateTokens(string $text): int
    {
        // Rough estimation: 1 token ≈ 4 characters
        return (int) ceil(strlen($text) / 4);
    }
    
    /**
     * Calculate cost based on tokens and model
     */
    private function calculateCost(int $tokensIn, int $tokensOut, string $model): float
    {
        // OpenAI pricing (as of 2024)
        $pricing = [
            'gpt-4' => [
                'input' => 0.03 / 1000,  // $0.03 per 1K tokens
                'output' => 0.06 / 1000, // $0.06 per 1K tokens
            ],
            'gpt-3.5-turbo' => [
                'input' => 0.0015 / 1000,  // $0.0015 per 1K tokens
                'output' => 0.002 / 1000,  // $0.002 per 1K tokens
            ],
        ];
        
        $modelPricing = $pricing[$model] ?? $pricing['gpt-3.5-turbo'];
        
        return ($tokensIn * $modelPricing['input']) + ($tokensOut * $modelPricing['output']);
    }
}
