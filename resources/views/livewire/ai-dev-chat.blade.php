<div class="p-4" wire:poll.keep-alive>
    <div class="mb-4 h-80 overflow-y-auto bg-gray-800 text-white p-3 rounded"
         id="chat-box">
        @foreach($messages as $msg)
            <div class="mb-2">
                <strong>{{ $msg['role'] }}:</strong>
                <span>{{ $msg['content'] }}</span>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage">
        @csrf
        <textarea
            wire:model.defer="message"
            class="w-full p-3 border rounded text-black"
            placeholder="اكتب هنا..."
            rows="3"
        ></textarea>

        <button
            type="submit"
            class="w-full mt-3 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
            إرسال
        </button>
    </form>
</div>
