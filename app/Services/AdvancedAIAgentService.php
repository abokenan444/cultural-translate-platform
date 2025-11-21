<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;

class AdvancedAIAgentService
{
    protected string $openaiApiKey;
    protected string $model = 'gpt-4';
    protected array $conversationHistory = [];

    public function __construct()
    {
        $this->openaiApiKey = env('OPENAI_API_KEY');
    }

    /**
     * Process natural language request and execute it
     */
    public function processRequest(string $request, array $context = []): array
    {
        try {
            // Step 1: Understand the request using GPT-4
            $understanding = $this->understandRequest($request, $context);

            // Step 2: Generate execution plan
            $plan = $this->generateExecutionPlan($understanding);

            // Step 3: Execute the plan
            $result = $this->executePlan($plan);

            // Step 4: Generate human-friendly response
            $response = $this->generateResponse($result);

            return [
                'success' => true,
                'understanding' => $understanding,
                'plan' => $plan,
                'result' => $result,
                'response' => $response,
            ];

        } catch (\Exception $e) {
            Log::error('AI Agent Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => 'عذراً، حدث خطأ أثناء معالجة طلبك: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Understand the request using GPT-4
     */
    protected function understandRequest(string $request, array $context): array
    {
        $systemPrompt = <<<PROMPT
أنت مساعد ذكي لتطوير منصة CulturalTranslate (Laravel + Filament).
مهمتك فهم طلبات المستخدم وتحويلها إلى خطة تنفيذ واضحة.

المنصة تحتوي على:
- Laravel 11.x Backend
- Filament v3 Admin Panel
- 35 Resources
- Frontend (Tailwind + Alpine.js)
- API (58+ endpoints)
- Database (MySQL)

أنواع الطلبات التي يمكنك فهمها:
1. إضافة ميزات جديدة (صفحات، resources، APIs)
2. تعديل التصميم (ألوان، layouts، styles)
3. إصلاح أخطاء (bugs، errors)
4. إدارة المشروع (git، migrations، cache)
5. تحليل وتقارير (logs، statistics، performance)

قم بتحليل الطلب وإرجاع JSON بهذا الشكل:
{
  "type": "feature|modification|fix|management|analysis",
  "category": "frontend|backend|database|api|admin",
  "action": "create|update|delete|fix|analyze",
  "target": "الهدف المحدد",
  "details": "تفاصيل إضافية",
  "priority": "high|medium|low",
  "estimated_time": "الوقت المتوقع بالدقائق"
}
PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $request],
            ],
            'temperature' => 0.7,
            'response_format' => ['type' => 'json_object'],
        ]);

        if (!$response->successful()) {
            throw new \Exception('فشل الاتصال بـ OpenAI: ' . $response->body());
        }

        $content = $response->json('choices.0.message.content');
        return json_decode($content, true);
    }

    /**
     * Generate execution plan
     */
    protected function generateExecutionPlan(array $understanding): array
    {
        $plan = [
            'steps' => [],
            'commands' => [],
            'files' => [],
        ];

        $type = $understanding['type'] ?? 'unknown';
        $category = $understanding['category'] ?? 'unknown';
        $action = $understanding['action'] ?? 'unknown';

        // Generate steps based on type and action
        switch ($type) {
            case 'feature':
                $plan = $this->planFeatureAddition($understanding);
                break;

            case 'modification':
                $plan = $this->planModification($understanding);
                break;

            case 'fix':
                $plan = $this->planFix($understanding);
                break;

            case 'management':
                $plan = $this->planManagement($understanding);
                break;

            case 'analysis':
                $plan = $this->planAnalysis($understanding);
                break;
        }

        return $plan;
    }

    /**
     * Plan feature addition
     */
    protected function planFeatureAddition(array $understanding): array
    {
        $category = $understanding['category'] ?? 'backend';
        $target = $understanding['target'] ?? '';

        $steps = [];
        $commands = [];
        $files = [];

        if ($category === 'admin' || $category === 'backend') {
            $steps[] = 'إنشاء Resource جديد في Filament';
            $steps[] = 'إنشاء Model و Migration';
            $steps[] = 'إنشاء Controller و API endpoints';
            $steps[] = 'تحديث Routes';
            
            $commands[] = 'php artisan make:model ' . $target . ' -m';
            $commands[] = 'php artisan make:filament-resource ' . $target;
            $commands[] = 'php artisan migrate';
        }

        if ($category === 'frontend') {
            $steps[] = 'إنشاء Blade template جديد';
            $steps[] = 'إضافة Route للصفحة';
            $steps[] = 'تحديث Navigation';
        }

        return compact('steps', 'commands', 'files');
    }

    /**
     * Plan modification
     */
    protected function planModification(array $understanding): array
    {
        return [
            'steps' => [
                'تحديد الملفات المطلوب تعديلها',
                'تطبيق التعديلات',
                'اختبار التعديلات',
            ],
            'commands' => [],
            'files' => [],
        ];
    }

    /**
     * Plan fix
     */
    protected function planFix(array $understanding): array
    {
        return [
            'steps' => [
                'تحليل الخطأ من الـ logs',
                'تحديد السبب',
                'تطبيق الإصلاح',
                'اختبار الإصلاح',
            ],
            'commands' => [
                'tail -n 100 storage/logs/laravel.log',
            ],
            'files' => [],
        ];
    }

    /**
     * Plan management tasks
     */
    protected function planManagement(array $understanding): array
    {
        $target = $understanding['target'] ?? '';
        $commands = [];

        if (str_contains($target, 'git')) {
            $commands[] = 'git status';
            $commands[] = 'git add .';
            $commands[] = 'git commit -m "Update from AI Agent"';
            $commands[] = 'git push origin main';
        }

        if (str_contains($target, 'cache')) {
            $commands[] = 'php artisan cache:clear';
            $commands[] = 'php artisan config:clear';
            $commands[] = 'php artisan route:clear';
            $commands[] = 'php artisan view:clear';
        }

        if (str_contains($target, 'migration')) {
            $commands[] = 'php artisan migrate --force';
        }

        return [
            'steps' => ['تنفيذ الأوامر المطلوبة'],
            'commands' => $commands,
            'files' => [],
        ];
    }

    /**
     * Plan analysis
     */
    protected function planAnalysis(array $understanding): array
    {
        return [
            'steps' => [
                'جمع البيانات المطلوبة',
                'تحليل البيانات',
                'إنشاء التقرير',
            ],
            'commands' => [
                'php artisan route:list',
                'du -sh storage/',
                'df -h',
            ],
            'files' => [],
        ];
    }

    /**
     * Execute the plan
     */
    protected function executePlan(array $plan): array
    {
        $results = [];

        // Execute commands
        foreach ($plan['commands'] ?? [] as $command) {
            try {
                $result = Process::run($command);
                
                $results[] = [
                    'command' => $command,
                    'success' => $result->successful(),
                    'output' => $result->output(),
                    'error' => $result->errorOutput(),
                ];

                Log::info('AI Agent executed: ' . $command, [
                    'success' => $result->successful(),
                ]);

            } catch (\Exception $e) {
                $results[] = [
                    'command' => $command,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'executed' => count($plan['commands'] ?? []),
            'results' => $results,
            'steps_completed' => $plan['steps'] ?? [],
        ];
    }

    /**
     * Generate human-friendly response
     */
    protected function generateResponse(array $result): string
    {
        $executed = $result['executed'] ?? 0;
        $results = $result['results'] ?? [];
        
        $response = "تم تنفيذ {$executed} أمر بنجاح! ✅\n\n";
        
        $response .= "**الخطوات المنفذة:**\n";
        foreach ($result['steps_completed'] ?? [] as $step) {
            $response .= "✓ {$step}\n";
        }

        $response .= "\n**النتائج:**\n";
        foreach ($results as $r) {
            if ($r['success']) {
                $response .= "✓ {$r['command']}\n";
                if (!empty($r['output'])) {
                    $response .= "  → " . substr($r['output'], 0, 100) . "...\n";
                }
            } else {
                $response .= "✗ {$r['command']}\n";
                $response .= "  → خطأ: {$r['error']}\n";
            }
        }

        return $response;
    }

    /**
     * Get system status
     */
    public function getSystemStatus(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'database' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
        ];
    }
}
