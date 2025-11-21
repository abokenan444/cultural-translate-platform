<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Services\AIAgentService;
use Illuminate\Support\Arr;

class AiDevChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'AI Tools';

    protected static ?string $navigationLabel = 'محادثة AI';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.admin.pages.ai-dev-chat';

    public string $prompt = '';

    public array $messages = [];

    public function mount(): void
    {
        // يمكن لاحقاً قراءة آخر المحادثات من DB
        $this->messages = [];
    }

    public function getTitle(): string
    {
        return 'AI Developer Chat';
    }

    public function send(): void
    {
        $this->validate([
            'prompt' => ['required', 'string', 'min:3'],
        ]);

        $text = trim($this->prompt);

        // أضف رسالة المستخدم في الأعلى
        $this->messages[] = [
            'role'    => 'user',
            'content' => $text,
        ];

        // استدعاء خدمة السيرفر الذكي
        /** @var \App\Services\AIAgentService $service */
        $service = app(AIAgentService::class);

        try {
            $reply = $service->runDevCommand($text);
        } catch (\Throwable $e) {
            $reply = 'Agent error: ' . $e->getMessage();
        }

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => $reply ?? 'No response from agent.',
        ];

        // تفريغ حقل الإدخال
        $this->prompt = '';
    }
}
