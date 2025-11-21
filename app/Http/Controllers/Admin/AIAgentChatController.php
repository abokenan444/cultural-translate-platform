<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiAgentMessage;
use App\Services\AIAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIAgentChatController extends Controller
{
    public function index()
    {
        $messages = AiAgentMessage::orderBy('id')->take(50)->get();

        return view('admin.ai-agent-chat', compact('messages'));
    }

    public function send(Request $request, AIAgentService $service)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $user = Auth::user();

        // حفظ رسالة المستخدم
        $userMessage = AiAgentMessage::create([
            'user_id' => $user?->id,
            'role'    => 'user',
            'message' => $data['message'],
            'status'  => 'pending',
        ]);

        try {
            $response = $service->chat($data['message']);

            $assistantText = $response['message'] ?? ($response['answer'] ?? '');
            $meta          = $response;
            unset($meta['message'], $meta['answer']);

            $userMessage->update([
                'response' => $assistantText,
                'meta'     => $meta,
                'status'   => 'done',
            ]);
        } catch (\Throwable $e) {
            $userMessage->update([
                'status'   => 'error',
                'response' => 'Agent error: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.ai-agent-chat.index');
    }
}
