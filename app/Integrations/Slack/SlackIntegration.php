<?php

namespace App\Integrations\Slack;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SlackIntegration
{
    private string $webhookUrl;
    private string $botToken;
    private string $apiUrl = 'https://slack.com/api';

    public function __construct()
    {
        $this->webhookUrl = env('SLACK_WEBHOOK_URL');
        $this->botToken = env('SLACK_BOT_TOKEN');
    }

    /**
     * Send translation notification to Slack channel
     */
    public function sendTranslationNotification(array $translation): bool
    {
        try {
            $message = [
                'text' => 'New Translation Completed',
                'blocks' => [
                    [
                        'type' => 'header',
                        'text' => [
                            'type' => 'plain_text',
                            'text' => '✅ Translation Completed'
                        ]
                    ],
                    [
                        'type' => 'section',
                        'fields' => [
                            [
                                'type' => 'mrkdwn',
                                'text' => "*From:*\n{$translation['source_language']}"
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => "*To:*\n{$translation['target_language']}"
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => "*Characters:*\n{$translation['character_count']}"
                            ],
                            [
                                'type' => 'mrkdwn',
                                'text' => "*Model:*\n{$translation['ai_model']}"
                            ]
                        ]
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => "*Original Text:*\n```{$translation['original_text']}```"
                        ]
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => "*Translated Text:*\n```{$translation['translated_text']}```"
                        ]
                    ],
                    [
                        'type' => 'actions',
                        'elements' => [
                            [
                                'type' => 'button',
                                'text' => [
                                    'type' => 'plain_text',
                                    'text' => 'View in Dashboard'
                                ],
                                'url' => env('APP_URL') . '/translations/' . $translation['id']
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::post($this->webhookUrl, $message);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Slack Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Slash command handler for /translate
     */
    public function handleTranslateCommand(array $payload): array
    {
        $text = $payload['text'] ?? '';
        
        // Parse command: /translate [source] [target] [text]
        $parts = explode(' ', $text, 3);
        
        if (count($parts) < 3) {
            return [
                'response_type' => 'ephemeral',
                'text' => 'Usage: /translate [source_lang] [target_lang] [text]'
            ];
        }

        [$sourceLang, $targetLang, $textToTranslate] = $parts;

        // Call translation service
        $translationService = app(\App\Services\OpenAIService::class);
        $result = $translationService->translate($textToTranslate, $sourceLang, $targetLang);

        if ($result['success']) {
            return [
                'response_type' => 'in_channel',
                'blocks' => [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => "*Translation Result:*\n{$result['translated_text']}"
                        ]
                    ],
                    [
                        'type' => 'context',
                        'elements' => [
                            [
                                'type' => 'mrkdwn',
                                'text' => "Translated from {$sourceLang} to {$targetLang} using {$result['model']}"
                            ]
                        ]
                    ]
                ]
            ];
        }

        return [
            'response_type' => 'ephemeral',
            'text' => 'Translation failed: ' . ($result['error'] ?? 'Unknown error')
        ];
    }

    /**
     * Send message to Slack channel
     */
    public function sendMessage(string $channel, string $text, array $blocks = []): bool
    {
        try {
            $payload = [
                'channel' => $channel,
                'text' => $text,
            ];

            if (!empty($blocks)) {
                $payload['blocks'] = $blocks;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/chat.postMessage', $payload);

            return $response->successful() && $response->json()['ok'] === true;

        } catch (\Exception $e) {
            Log::error('Slack Send Message Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Slack user info
     */
    public function getUserInfo(string $userId): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken
            ])->get($this->apiUrl . '/users.info', [
                'user' => $userId
            ]);

            if ($response->successful() && $response->json()['ok'] === true) {
                return $response->json()['user'];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Slack Get User Info Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create interactive translation workflow
     */
    public function createTranslationWorkflow(string $triggerId): bool
    {
        try {
            $view = [
                'type' => 'modal',
                'callback_id' => 'translation_modal',
                'title' => [
                    'type' => 'plain_text',
                    'text' => 'CulturalTranslate'
                ],
                'submit' => [
                    'type' => 'plain_text',
                    'text' => 'Translate'
                ],
                'blocks' => [
                    [
                        'type' => 'input',
                        'block_id' => 'source_text',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'Text to translate'
                        ],
                        'element' => [
                            'type' => 'plain_text_input',
                            'action_id' => 'text_input',
                            'multiline' => true
                        ]
                    ],
                    [
                        'type' => 'input',
                        'block_id' => 'source_language',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'Source Language'
                        ],
                        'element' => [
                            'type' => 'static_select',
                            'action_id' => 'source_lang_select',
                            'options' => $this->getLanguageOptions()
                        ]
                    ],
                    [
                        'type' => 'input',
                        'block_id' => 'target_language',
                        'label' => [
                            'type' => 'plain_text',
                            'text' => 'Target Language'
                        ],
                        'element' => [
                            'type' => 'static_select',
                            'action_id' => 'target_lang_select',
                            'options' => $this->getLanguageOptions()
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->botToken,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/views.open', [
                'trigger_id' => $triggerId,
                'view' => $view
            ]);

            return $response->successful() && $response->json()['ok'] === true;

        } catch (\Exception $e) {
            Log::error('Slack Create Workflow Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get language options for Slack select menu
     */
    private function getLanguageOptions(): array
    {
        return [
            ['text' => ['type' => 'plain_text', 'text' => 'English'], 'value' => 'en'],
            ['text' => ['type' => 'plain_text', 'text' => 'Arabic'], 'value' => 'ar'],
            ['text' => ['type' => 'plain_text', 'text' => 'Spanish'], 'value' => 'es'],
            ['text' => ['type' => 'plain_text', 'text' => 'French'], 'value' => 'fr'],
            ['text' => ['type' => 'plain_text', 'text' => 'German'], 'value' => 'de'],
            ['text' => ['type' => 'plain_text', 'text' => 'Italian'], 'value' => 'it'],
            ['text' => ['type' => 'plain_text', 'text' => 'Portuguese'], 'value' => 'pt'],
            ['text' => ['type' => 'plain_text', 'text' => 'Russian'], 'value' => 'ru'],
            ['text' => ['type' => 'plain_text', 'text' => 'Chinese'], 'value' => 'zh'],
            ['text' => ['type' => 'plain_text', 'text' => 'Japanese'], 'value' => 'ja'],
            ['text' => ['type' => 'plain_text', 'text' => 'Korean'], 'value' => 'ko'],
            ['text' => ['type' => 'plain_text', 'text' => 'Hindi'], 'value' => 'hi'],
            ['text' => ['type' => 'plain_text', 'text' => 'Turkish'], 'value' => 'tr'],
        ];
    }
}
