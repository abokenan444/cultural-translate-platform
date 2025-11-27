<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VoiceTranslationController extends Controller
{
    /**
     * Translate voice/audio to text and then translate
     */
    public function translateVoice(Request $request)
    {
        $validated = $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,ogg,webm,m4a|max:25600', // 25MB max
            'source_language' => 'required|string|in:auto,en,ar,es,fr,de,it,pt,ru,zh,ja,ko,hi,tr',
            'target_language' => 'required|string|size:2',
        ]);

        try {
            // Get the uploaded audio file
            $audio = $request->file('audio');
            
            // Step 1: Convert speech to text using OpenAI Whisper API
            $transcribedText = $this->transcribeAudio($audio, $validated['source_language']);
            
            if (empty($transcribedText)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No speech detected in the audio',
                ], 422);
            }

            // Step 2: Translate the transcribed text
            $translatedText = $this->translateText(
                $transcribedText,
                $validated['source_language'],
                $validated['target_language']
            );

            return response()->json([
                'success' => true,
                'transcribed_text' => $transcribedText,
                'translated_text' => $translatedText,
                'source_language' => $validated['source_language'],
                'target_language' => $validated['target_language'],
            ]);

        } catch (\Exception $e) {
            Log::error('Voice translation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to translate voice: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Transcribe audio to text using OpenAI Whisper API
     */
    private function transcribeAudio($audio, $sourceLanguage)
    {
        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        // Save audio temporarily
        $audioPath = $audio->store('temp', 'local');
        $fullPath = storage_path('app/' . $audioPath);

        try {
            // Call OpenAI Whisper API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->attach(
                'file', file_get_contents($fullPath), $audio->getClientOriginalName()
            )->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1',
                'language' => $sourceLanguage === 'auto' ? null : $sourceLanguage,
            ]);

            if ($response->failed()) {
                throw new \Exception('Transcription API request failed');
            }

            $data = $response->json();
            
            return $data['text'] ?? '';

        } finally {
            // Clean up temporary file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
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
        
        $prompt = "Translate the following text to {$targetLanguageName}:\n\n{$text}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional translator.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
        ]);

        if ($response->failed()) {
            throw new \Exception('Translation API request failed');
        }

        $data = $response->json();
        
        return $data['choices'][0]['message']['content'] ?? '';
    }
}
