<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <x-filament::section>
                    <x-slot name="heading">
                        AI Dev Chat Console
                    </x-slot>

                    <x-filament::form wire:submit="submit">
                        {{ $this->form }}

                        <div class="mt-4">
                            <x-filament::button type="submit">
                                Send to Agent
                            </x-filament::button>
                        </div>
                    </x-filament::form>
                </x-filament::section>
            </div>

            <div class="space-y-4">
                <x-filament::section>
                    <x-slot name="heading">
                        Agent Response
                    </x-slot>

                    @if($response)
                        <pre class="text-xs whitespace-pre-wrap bg-gray-900 text-gray-100 rounded-lg p-4 overflow-auto max-h-[600px]">
{{ $response }}
                        </pre>
                    @else
                        <p class="text-sm text-gray-500">
                            لم يتم تنفيذ أي أمر بعد. اكتب تعليماتك في الحقل الأيسر ثم اضغط "Send to Agent".
                        </p>
                    @endif
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
