<?php

namespace App\Http\Controllers\AI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $msg = $request->input('message');

        $response = Http::post(config('ai_agent.base_url') . '/run-command', [
            'auth_token' => config('ai_agent.auth_token'),
            'command' => $msg
        ]);

        return response()->json([
            'reply' => $response->json()['output'] ?? 'No response from agent'
        ]);
    }
}
