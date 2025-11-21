<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class AiDevChat extends Component
{
    public $messages = [];
    public $message = '';

    public function sendMessage()
    {
        if (!$this->message) return;

        $this->messages[] = [
            'role' => 'You',
            'content' => $this->message
        ];

        $response = Http::post(config('ai-agent.server_url') . '/run-command', [
            'command' => $this->message,
            'auth' => config('ai-agent.password')
        ]);

        $this->messages[] = [
            'role' => 'Agent',
            'content' => $response->json('output') ?? 'No response'
        ];

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.ai-dev-chat');
    }
}
