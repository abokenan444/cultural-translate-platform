<x-filament::page>
    <div class="space-y-6">
        <div class="flex flex-wrap gap-4">
            <x-filament::button
                wire:click="checkHealth"
                color="success"
            >
                Check Agent Health
            </x-filament::button>

            <x-filament::button
                wire:click="optimizeAction"
                color="primary"
            >
                Optimize Laravel (via Agent)
            </x-filament::button>

            <x-filament::button
                wire:click="deployAction"
                color="warning"
            >
                Deploy Updates
            </x-filament::button>
        </div>

        <x-filament::section heading="Run Server Command">
            {{ $this->form }}
            <x-slot name="footer">
                <x-filament::button
                    wire:click="runCommandAction"
                    color="danger"
                >
                    Run Command
                </x-filament::button>
            </x-slot>
        </x-filament::section>
    </div>
</x-filament::page>
