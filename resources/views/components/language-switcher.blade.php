@php
    $languages = \App\Http\Controllers\LanguageController::all();
    $currentLocale = session('locale', config('app.locale', 'en'));
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
        <span class="text-xl">{{ $languages[$currentLocale]['flag'] ?? '🌐' }}</span>
        <span class="text-sm font-medium text-gray-700">{{ $languages[$currentLocale]['name'] ?? 'English' }}</span>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" 
         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50 max-h-96 overflow-y-auto"
         style="display: none;">
        @foreach($languages as $code => $lang)
            <a href="{{ route('language.switch', $code) }}" 
               class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 transition {{ $currentLocale === $code ? 'bg-indigo-50' : '' }}">
                <span class="text-2xl">{{ $lang['flag'] }}</span>
                <span class="text-sm font-medium {{ $currentLocale === $code ? 'text-indigo-600' : 'text-gray-700' }}">
                    {{ $lang['name'] }}
                </span>
                @if($currentLocale === $code)
                    <svg class="w-4 h-4 text-indigo-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
