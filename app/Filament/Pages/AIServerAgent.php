<?php

namespace App\Filament\Pages;

use App\Services\AIAgentService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;

class AIServerAgent extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'AI Server Agent';
    protected static ?string $navigationGroup = 'System Tools';
    protected static ?string $title           = 'AI Server Agent';
    protected static string $view             = 'filament.pages.ai-server-agent';

    public ?string $command = null;
    public ?string $branch  = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('command')
                    ->label('Server Command')
                    ->placeholder('مثال: php artisan migrate --force')
                    ->rows(3),
                Forms\Components\TextInput::make('branch')
                    ->label('Git Branch (اختياري)')
                    ->placeholder('main'),
            ]);
    }

    public function checkHealth(AIAgentService $agent): void
    {
        $result = $agent->health();

        Notification::make()
            ->title('Agent Health: ' . ($result['data']['status'] ?? $result['message'] ?? 'unknown'))
            ->success()
            ->send();
    }

    public function runCommandAction(AIAgentService $agent): void
    {
        if (! $this->command) {
            Notification::make()
                ->title('يرجى إدخال الأمر أولاً')
                ->danger()
                ->send();

            return;
        }

        $result = $agent->runCommand($this->command);

        Notification::make()
            ->title('Command executed')
            ->body(substr((string) ($result['data']['output'] ?? $result['message']), 0, 4000))
            ->success()
            ->send();
    }

    public function deployAction(AIAgentService $agent): void
    {
        $result = $agent->deploy($this->branch ?: null);

        Notification::make()
            ->title('Deploy triggered')
            ->body($result['message'] ?? 'تم إرسال طلب النشر.')
            ->success()
            ->send();
    }

    public function optimizeAction(AIAgentService $agent): void
    {
        $result = $agent->optimize();

        Notification::make()
            ->title('Optimize triggered')
            ->body($result['message'] ?? 'تم إرسال طلب التحسين.')
            ->success()
            ->send();
    }
}
