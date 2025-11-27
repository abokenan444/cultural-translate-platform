<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageTranslationController extends Controller
{
    /**
     * Translate text from an image using OCR + Translation
     */
    public function translateImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
            'source_language' => 'required|string|in:auto,en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr',
            'target_language' => 'required|string|size:2',
        ]);

        try {
            // Get the uploaded image
            $image = $request->file('image');
            
            // Step 1: Extract text from image using OpenAI Vision API
            $extractedText = $this->extractTextFromImage($image);
            
            if (empty($extractedText)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No text found in the image',
                ], 422);
            }

            // Step 2: Translate the extracted text
            $translatedText = $this->translateText(
                $extractedText,
                $validated['source_language'],
                $validated['target_language']
            );

            return response()->json([
                'success' => true,
                'original_text' => $extractedText,
                'translated_text' => $translatedText,
                'source_language' => $validated['source_language'],
                'target_language' => $validated['target_language'],
            ]);

        } catch (\Exception $e) {
            Log::error('Image translation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to translate image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract text from image using OpenAI Vision API
     */
    private function extractTextFromImage($image)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        // Convert image to base64
        $imageContent = base64_encode(file_get_contents($image->getPathname()));
        $mimeType = $image->getMimeType();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Extract all text from this image. Return only the text without any additional commentary or formatting.'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:{$mimeType};base64,{$imageContent}"
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 1000,
        ]);

        if ($response->failed()) {
            throw new \Exception('Vision API request failed: ' . $response->body());
        }

        $data = $response->json();
        
        return trim($data['choices'][0]['message']['content'] ?? '');
    }

    /**
     * Translate text using OpenAI API
     */
    private function translateText($text, $sourceLanguage, $targetLanguage)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $languageNames = [
            'en' => 'English',
            'ar' => 'Arabic',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'hi' => 'Hindi',
            'tr' => 'Turkish',
        ];

        $targetLanguageName = $languageNames[$targetLanguage] ?? 'English';
        
        $prompt = "Translate the following text to {$targetLanguageName}. Return only the translation without any additional commentary:\n\n{$text}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional translator. Return only the translation without any additional text.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        if ($response->failed()) {
            throw new \Exception('Translation API request failed');
        }

        $data = $response->json();
        
        return trim($data['choices'][0]['message']['content'] ?? '');
    }
}
