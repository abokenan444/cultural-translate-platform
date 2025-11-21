<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AIAgentService
{
    protected string $baseUrl;
    protected ?string $authToken;

    public function __construct()
    {
        $this->baseUrl   = rtrim(Config::get('ai_agent.base_url'), '/');
        $this->authToken = Config::get('ai_agent.auth_token');
    }

    /**
     * دالة مشتركة لإرسال الطلبات إلى الـ Agent.
     */
    protected function request(string $endpoint, string $method = 'GET', array $payload = []): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $request = Http::timeout(30)
                ->acceptJson()
                ->withHeaders(
                    $this->authToken
                        ? ['X-AI-Agent-Token' => $this->authToken]
                        : []
                );

            $response = match (strtoupper($method)) {
                'POST'   => $request->post($url, $payload),
                'PUT'    => $request->put($url, $payload),
                'DELETE' => $request->delete($url, $payload),
                default  => $request->get($url, $payload),
            };

            if (! $response->successful()) {
                Log::warning('AI Agent non-200 response', [
                    'url'      => $url,
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                    'payload'  => $payload,
                ]);

                return [
                    'ok'      => false,
                    'status'  => $response->status(),
                    'data'    => $response->json(),
                    'message' => 'AI Agent returned an error response.',
                ];
            }

            return [
                'ok'      => true,
                'status'  => $response->status(),
                'data'    => $response->json(),
                'message' => 'OK',
            ];
        } catch (Throwable $e) {
            Log::error('AI Agent request failed', [
                'url'     => $url,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);

            return [
                'ok'      => false,
                'status'  => 500,
                'data'    => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function health(): array
    {
        return $this->request(
            Config::get('ai_agent.health_endpoint')
        );
    }

    public function apiHealth(): array
    {
        return $this->request(
            Config::get('ai_agent.api_health_endpoint')
        );
    }

    /**
     * تنفيذ أمر على السيرفر من خلال الـ Agent
     */
    public function runCommand(string $command): array
    {
        return $this->request(
            Config::get('ai_agent.run_command_endpoint'),
            'POST',
            ['command' => $command]
        );
    }

    /**
     * نشر تحديثات المنصة (مثلاً git pull + build)
     */
    public function deploy(?string $branch = null): array
    {
        $payload = [];

        if ($branch !== null) {
            $payload['branch'] = $branch;
        }

        return $this->request(
            Config::get('ai_agent.deploy_endpoint'),
            'POST',
            $payload
        );
    }

    public function chat(string $message, ?string $context = null): array
    {
        $payload = [
            'message' => $message,
            'context' => $context,
        ];

        return $this->request('POST', '/chat', $payload);
    }

    /**
     * تشغيل سلسلة أوامر optimize/clear للكاش من خلال الـ Agent
     */
    public function optimize(): array
    {
        return $this->request(
            Config::get('ai_agent.optimize_endpoint'),
            'POST'
        );
    }
}

public function devChat(string $prompt): string
{
    // حالياً رد بسيط للتجربة – لاحقاً نربطه فعلياً بسيرفر Python
    return 'تم استلام طلبك، وسيتم تنفيذ ربط الوكيل الذكي خطوة خطوة. نص الطلب: "'
        . $prompt . '"';
}

public function chat(string $prompt, int $limit = 10): array
{
    $history = \App\Models\AiAgentMessage::query()
        ->orderBy('id', 'desc')
        ->limit($limit)
        ->get()
        ->reverse()
        ->map(function ($msg) {
            return [
                'role'    => $msg->role,
                'content' => $msg->role === 'user' ? $msg->message : ($msg->response ?? ''),
            ];
        })
        ->values()
        ->toArray();

    $payload = [
        'prompt'  => $prompt,
        'history' => $history,
    ];

    return $this->request(
        '/chat',          // endpoint في الـ Agent
        'POST',
        $payload
    );
}
