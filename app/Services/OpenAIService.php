<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Translate text using GPT-4
     */
    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): array
    {
        $prompt = $this->buildTranslationPrompt($text, $sourceLanguage, $targetLanguage, $options);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl . '/chat/completions', [
                'model' => $options['model'] ?? 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional translator with expertise in cultural adaptation and localization.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $translatedText = $data['choices'][0]['message']['content'] ?? '';
                
                return [
                    'success' => true,
                    'translated_text' => trim($translatedText),
                    'model' => $data['model'] ?? 'gpt-4',
                    'usage' => $data['usage'] ?? [],
                ];
            }

            return [
                'success' => false,
                'error' => 'Translation failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Translation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Transcribe audio using Whisper
     */
    public function transcribe(string $audioPath, string $language = null): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->timeout(120)->attach(
                'file', file_get_contents($audioPath), basename($audioPath)
            )->post($this->baseUrl . '/audio/transcriptions', [
                'model' => 'whisper-1',
                'language' => $language,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'text' => $data['text'] ?? '',
                ];
            }

            return [
                'success' => false,
                'error' => 'Transcription failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Transcription Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate speech using TTS
     */
    public function textToSpeech(string $text, array $options = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->baseUrl . '/audio/speech', [
                'model' => 'tts-1',
                'input' => $text,
                'voice' => $options['voice'] ?? 'alloy',
                'speed' => $options['speed'] ?? 1.0,
            ]);

            if ($response->successful()) {
                $audioContent = $response->body();
                $filename = 'tts-' . uniqid() . '.mp3';
                $path = storage_path('app/public/audio/' . $filename);
                
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                
                file_put_contents($path, $audioContent);
                
                return [
                    'success' => true,
                    'audio_path' => $path,
                    'audio_url' => asset('storage/audio/' . $filename),
                ];
            }

            return [
                'success' => false,
                'error' => 'TTS failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI TTS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Analyze context and sentiment
     */
    public function analyzeContext(string $text, string $language): array
    {
        $prompt = "Analyze the following text and provide:\n"
                . "1. Industry/Domain\n"
                . "2. Tone (formal/informal/professional/casual)\n"
                . "3. Target Audience\n"
                . "4. Sentiment (positive/negative/neutral)\n"
                . "5. Key Topics\n\n"
                . "Text: {$text}\n\n"
                . "Provide response in JSON format.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a text analysis expert.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $analysis = $data['choices'][0]['message']['content'] ?? '{}';
                
                return [
                    'success' => true,
                    'analysis' => json_decode($analysis, true) ?? [],
                ];
            }

            return ['success' => false, 'error' => 'Analysis failed'];

        } catch (\Exception $e) {
            Log::error('OpenAI Context Analysis Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Build translation prompt with options
     */
    private function buildTranslationPrompt(string $text, string $source, string $target, array $options): string
    {
        $prompt = "Translate the following text from {$source} to {$target}:\n\n{$text}\n\n";
        
        if ($options['cultural_adaptation'] ?? false) {
            $prompt .= "Apply cultural adaptation and localization.\n";
        }
        
        if ($options['preserve_brand_voice'] ?? false) {
            $prompt .= "Preserve the brand voice and tone.\n";
        }
        
        if ($options['industry'] ?? false) {
            $prompt .= "Use {$options['industry']} industry terminology.\n";
        }
        
        $prompt .= "\nProvide only the translation without explanations.";
        
        return $prompt;
    }
}
