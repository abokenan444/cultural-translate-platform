@extends('layouts.admin') {{-- نفس لياوت الأدمن عندك --}}

@section('title', 'AI Dev Chat')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold mb-4">AI Dev Chat</h1>

    <div class="border rounded-lg p-4 bg-gray-50 h-[60vh] overflow-y-auto">
        @forelse($messages as $msg)
            <div class="mb-4">
                <div class="text-xs text-gray-500 mb-1">
                    {{ $msg->created_at->format('Y-m-d H:i') }}
                    — {{ $msg->role === 'user' ? 'You' : 'AI Agent' }}
                </div>

                @if($msg->role === 'user')
                    <div class="bg-blue-50 border border-blue-100 rounded-md p-3">
                        {!! nl2br(e($msg->message)) !!}
                    </div>
                @else
                    <div class="bg-green-50 border border-green-100 rounded-md p-3">
                        {!! nl2br(e($msg->response)) !!}
                    </div>

                    @if(!empty($msg->meta['suggested_commands'] ?? []))
                        <div class="mt-2 text-xs">
                            <div class="font-semibold mb-1">Suggested commands:</div>
                            <ul class="list-disc pl-5">
                                @foreach($msg->meta['suggested_commands'] as $cmd)
                                    <li><code>{{ $cmd }}</code></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-sm">No messages yet. Start by writing a request below.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('admin.ai-agent-chat.send') }}" class="space-y-3">
        @csrf
        <label class="block text-sm font-medium text-gray-700">
            Your request to the Dev Agent
        </label>
        <textarea name="message" rows="4"
                  class="w-full border rounded-lg p-3 focus:outline-none focus:ring focus:border-blue-400"
                  placeholder="مثال: أضف تقريراً جديداً يوضح عدد الترجمات لكل شركة في آخر 30 يوماً...">{{ old('message') }}</textarea>
        @error('message')
            <div class="text-red-600 text-xs">{{ $message }}</div>
        @enderror

        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Send
        </button>
    </form>
</div>
@endsection
