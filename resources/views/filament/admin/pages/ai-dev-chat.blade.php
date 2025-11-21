<x-filament::page>
    <div class="space-y-6" dir="rtl">
        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-gray-100">
                AI Dev Chat – Smart Server Agent
            </h2>

            <p class="text-sm text-gray-300 leading-relaxed">
                اكتب للمساعد ما تريد القيام به في منصة CulturalTranslate:
                تشغيل أوامر Laravel، إصلاح الأخطاء، تنفيذ الترحيلات، نشر التحديثات من Git،
                أو أي مهام تطويرية أخرى على الخادم.
            </p>
        </div>

        {{-- صندوق المحادثة --}}
        <div class="space-y-3">
            <div class="h-72 rounded-xl border border-gray-700 bg-gray-900/70 p-4 overflow-y-auto space-y-3">
                @forelse ($messages as $message)
                    <div class="space-y-1">
                        <div class="text-xs font-semibold text-gray-400">
                            {{ $message['role'] === 'user' ? 'أنت' : 'الوكيل' }}
                        </div>

                        <div class="rounded-lg px-3 py-2 text-sm whitespace-pre-wrap
                                    {{ $message['role'] === 'user'
                                        ? 'bg-primary-900/60 text-primary-100'
                                        : 'bg-emerald-900/60 text-emerald-100' }}">
                            {{ $message['content'] }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">
                        لم تُرسل أي أوامر بعد. اكتب أمراً في الأسفل ثم اضغط "إرسال".
                    </p>
                @endforelse
            </div>

            {{-- نموذج الإدخال (Livewire فقط، بدون method / action / csrf) --}}
            <form wire:submit.prevent="send" class="space-y-3">
                <div>
                    <label for="prompt" class="block text-sm font-medium text-gray-200 mb-1">
                        الأمر الذي تريده من المساعد
                    </label>

                    <textarea
                        id="prompt"
                        wire:model.defer="prompt"
                        dir="auto"
                        class="w-full min-h-[140px] rounded-xl border border-gray-700 bg-gray-900
                               text-gray-100 text-sm p-3
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="مثال: شغّل أوامر الترحيل في Laravel للبيئة الإنتاجية مع أخذ نسخة احتياطية قبل التنفيذ..."
                    ></textarea>

                    @error('prompt')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <x-filament::button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="send"
                    >
                        <span wire:loading.remove wire:target="send">
                            إرسال
                        </span>

                        <span wire:loading wire:target="send">
                            جاري المعالجة...
                        </span>
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament::page>
