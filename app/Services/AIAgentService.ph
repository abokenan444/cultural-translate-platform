public function chat(string $prompt, int $limit = 10): array
{
    // آخر N رسائل للمحادثة
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

    return $this->request('POST', '/chat', $payload);
}
