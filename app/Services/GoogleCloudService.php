<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleCloudService
{
    private string $apiKey;
    private string $translateUrl = 'https://translation.googleapis.com/language/translate/v2';
    private string $visionUrl = 'https://vision.googleapis.com/v1/images:annotate';

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_CLOUD_API_KEY');
    }

    /**
     * Translate text using Google Translate API
     */
    public function translate(string $text, string $targetLanguage, string $sourceLanguage = null): array
    {
        try {
            $params = [
                'q' => $text,
                'target' => $targetLanguage,
                'key' => $this->apiKey,
                'format' => 'text',
            ];

            if ($sourceLanguage) {
                $params['source'] = $sourceLanguage;
            }

            $response = Http::timeout(30)->post($this->translateUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                $translation = $data['data']['translations'][0] ?? [];
                
                return [
                    'success' => true,
                    'translated_text' => $translation['translatedText'] ?? '',
                    'detected_source_language' => $translation['detectedSourceLanguage'] ?? $sourceLanguage,
                ];
            }

            return [
                'success' => false,
                'error' => 'Translation failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Google Translate Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Detect language using Google Translate API
     */
    public function detectLanguage(string $text): array
    {
        try {
            $response = Http::timeout(30)->post($this->translateUrl . '/detect', [
                'q' => $text,
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $detection = $data['data']['detections'][0][0] ?? [];
                
                return [
                    'success' => true,
                    'language' => $detection['language'] ?? 'unknown',
                    'confidence' => $detection['confidence'] ?? 0,
                ];
            }

            return ['success' => false, 'error' => 'Detection failed'];

        } catch (\Exception $e) {
            Log::error('Google Language Detection Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Extract text from image using Google Vision API (OCR)
     */
    public function extractTextFromImage(string $imagePath): array
    {
        try {
            $imageContent = base64_encode(file_get_contents($imagePath));
            
            $response = Http::timeout(60)->post($this->visionUrl, [
                'requests' => [
                    [
                        'image' => [
                            'content' => $imageContent
                        ],
                        'features' => [
                            [
                                'type' => 'TEXT_DETECTION',
                                'maxResults' => 50
                            ]
                        ]
                    ]
                ],
                'key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $textAnnotations = $data['responses'][0]['textAnnotations'] ?? [];
                
                $fullText = $textAnnotations[0]['description'] ?? '';
                $textRegions = [];
                
                foreach (array_slice($textAnnotations, 1) as $annotation) {
                    $textRegions[] = [
                        'text' => $annotation['description'] ?? '',
                        'bounds' => $annotation['boundingPoly']['vertices'] ?? [],
                    ];
                }
                
                return [
                    'success' => true,
                    'full_text' => $fullText,
                    'text_regions' => $textRegions,
                    'region_count' => count($textRegions),
                ];
            }

            return ['success' => false, 'error' => 'OCR failed'];

        } catch (\Exception $e) {
            Log::error('Google Vision OCR Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(array $texts, string $targetLanguage, string $sourceLanguage = null): array
    {
        try {
            $params = [
                'q' => $texts,
                'target' => $targetLanguage,
                'key' => $this->apiKey,
                'format' => 'text',
            ];

            if ($sourceLanguage) {
                $params['source'] = $sourceLanguage;
            }

            $response = Http::timeout(60)->post($this->translateUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                $translations = $data['data']['translations'] ?? [];
                
                $results = [];
                foreach ($translations as $translation) {
                    $results[] = [
                        'translated_text' => $translation['translatedText'] ?? '',
                        'detected_source_language' => $translation['detectedSourceLanguage'] ?? $sourceLanguage,
                    ];
                }
                
                return [
                    'success' => true,
                    'translations' => $results,
                    'count' => count($results),
                ];
            }

            return ['success' => false, 'error' => 'Batch translation failed'];

        } catch (\Exception $e) {
            Log::error('Google Batch Translate Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages(): array
    {
        try {
            $response = Http::timeout(30)->get($this->translateUrl . '/languages', [
                'key' => $this->apiKey,
                'target' => 'en',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $languages = $data['data']['languages'] ?? [];
                
                return [
                    'success' => true,
                    'languages' => $languages,
                    'count' => count($languages),
                ];
            }

            return ['success' => false, 'error' => 'Failed to get languages'];

        } catch (\Exception $e) {
            Log::error('Google Get Languages Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
