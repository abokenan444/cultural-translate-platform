<?php

namespace App\Filament\Pages;

use App\Services\AIAgentService;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class AIDevChat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'AI Dev Chat';

    protected static ?string $navigationGroup = 'System Tools';

    protected static ?string $slug = 'ai-dev-chat';

    protected static string $view = 'filament.pages.ai-dev-chat';

    public ?string $message = '';

    public ?string $context = '';

    public ?string $reply = '';

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                Forms\Form::make()
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Your request')
                            ->required()
                            ->rows(5),
                        Forms\Components\Textarea::make('context')
                            ->label('Extra context (optional)')
                            ->rows(3),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public array $data = [];

    public function mount(): void
    {
        $this->data = [
            'message' => '',
            'context' => '',
        ];
    }

    public function submit(AIAgentService $agent): void
    {
        $message = $this->data['message'] ?? '';
        $context = $this->data['context'] ?? null;

        if (trim($message) === '') {
            Notification::make()
                ->title('Please enter a message')
                ->danger()
                ->send();

            return;
        }

        try {
            $response = $agent->chat($message, $context);
            $this->reply = $response['reply'] ?? json_encode($response, JSON_PRETTY_PRINT);

            Notification::make()
                ->title('Agent replied')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->reply = 'Error: '.$e->getMessage();

            Notification::make()
                ->title('Agent error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
