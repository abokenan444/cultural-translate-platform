<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class AiDevChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    // اسم الصفحة في القائمة
    protected static ?string $navigationLabel = 'AI Dev Chat';

    // المجموعة في القائمة (يمكن تغييره إذا أردت)
    protected static ?string $navigationGroup = 'System Tools';

    // الـ slug الذي يُستخدم في الرابط:
    // سيكون الرابط: /admin-dashboard/ai-dev-chat
    protected static ?string $slug = 'ai-dev-chat';

    protected static string $view = 'filament.pages.ai-dev-chat';

    public ?string $prompt = '';
    public ?string $response = '';

    public function mount(): void
    {
        $this->response = '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('prompt')
                    ->label('Developer instruction')
                    ->placeholder('اكتب ما تريد أن يقوم به الـ Agent على السيرفر أو في Laravel...')
                    ->rows(6)
                    ->required(),
            ]);
    }

    public function submit(): void
    {
        if (! $this->prompt) {
            Notification::make()
                ->title('Please write your instruction first.')
                ->danger()
                ->send();

            return;
        }

        try {
            // نرسل الطلب إلى سيرفر الـ Agent (الذي يعمل على 5050 مثلاً)
            $apiBase = config('ai_agent.base_url', 'http://127.0.0.1:5050');

            $response = Http::timeout(120)->post("{$apiBase}/run-command", [
                'command' => $this->prompt,
                'via_chat' => true,
            ]);

            if ($response->failed()) {
                $this->response = 'Agent error: ' . $response->body();

                Notification::make()
                    ->title('Agent returned an error')
                    ->danger()
                    ->send();

                return;
            }

            $data = $response->json();

            $this->response = $data['output'] ?? $response->body();

            Notification::make()
                ->title('Command sent to AI Server Agent')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->response = 'Exception: ' . $e->getMessage();

            Notification::make()
                ->title('Could not contact AI Server Agent')
                ->danger()
                ->send();
        }

        // لا نفرغ الـ prompt مباشرة حتى تستطيع تعديله
        // $this->prompt = '';
    }
}
