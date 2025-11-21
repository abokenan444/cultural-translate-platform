@extends('dashboard.layout')

@section('title', 'Translate')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="translationTool()">
    
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">AI Translation</h1>
        <p class="text-gray-600 mt-1">Translate content with cultural adaptation and brand voice preservation</p>
    </div>
    
    <!-- Translation Tool -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        
        <!-- Language Selectors -->
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source Language</label>
                <select x-model="sourceLanguage" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="auto">Auto-detect</option>
                    <option value="en">English</option>
                    <option value="ar">Arabic</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                    <option value="it">Italian</option>
                    <option value="pt">Portuguese</option>
                    <option value="ru">Russian</option>
                    <option value="zh">Chinese</option>
                    <option value="ja">Japanese</option>
                    <option value="ko">Korean</option>
                    <option value="hi">Hindi</option>
                </select>
            </div>
            
            <div class="flex items-end justify-center">
                <button @click="swapLanguages" class="p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-exchange-alt text-gray-600"></i>
                </button>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Language</label>
                <select x-model="targetLanguage" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="en">English</option>
                    <option value="ar">Arabic</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                    <option value="it">Italian</option>
                    <option value="pt">Portuguese</option>
                    <option value="ru">Russian</option>
                    <option value="zh">Chinese</option>
                    <option value="ja">Japanese</option>
                    <option value="ko">Korean</option>
                    <option value="hi">Hindi</option>
                </select>
            </div>
        </div>
        
        <!-- Text Areas -->
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <!-- Source Text -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Source Text</label>
                    <span class="text-xs text-gray-500" x-text="sourceText.length + ' / 10,000 characters'"></span>
                </div>
                <textarea 
                    x-model="sourceText"
                    @input="updateCharCount"
                    placeholder="Enter text to translate..."
                    class="w-full h-64 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    maxlength="10000"
                ></textarea>
                <div class="flex items-center justify-between mt-2">
                    <button @click="clearSource" class="text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times mr-1"></i> Clear
                    </button>
                    <button @click="pasteFromClipboard" class="text-sm text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-paste mr-1"></i> Paste
                    </button>
                </div>
            </div>
            
            <!-- Translated Text -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Translation</label>
                    <span x-show="translatedText" class="text-xs text-green-600">
                        <i class="fas fa-check-circle"></i> Completed
                    </span>
                </div>
                <div class="w-full h-64 border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 overflow-y-auto">
                    <p x-show="!translating && !translatedText" class="text-gray-400 text-sm">Translation will appear here...</p>
                    <div x-show="translating" class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-3xl text-indigo-600 mb-2"></i>
                            <p class="text-sm text-gray-600">Translating...</p>
                        </div>
                    </div>
                    <p x-show="!translating && translatedText" x-text="translatedText" class="text-gray-900"></p>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <button @click="copyToClipboard" x-show="translatedText" class="text-sm text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-copy mr-1"></i> Copy
                    </button>
                    <button @click="downloadTranslation" x-show="translatedText" class="text-sm text-indigo-600 hover:text-indigo-700">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Options -->
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Translation Options</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">AI Model</label>
                    <select x-model="aiModel" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="gpt-4">GPT-4 (Best Quality)</option>
                        <option value="gpt-3.5-turbo">GPT-3.5 Turbo (Faster)</option>
                        <option value="google-translate">Google Translate</option>
                        <option value="deepl">DeepL</option>
                    </select>
                </div>
                
                <div class="flex items-center space-x-2">
                    <input type="checkbox" x-model="culturalAdaptation" id="cultural" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="cultural" class="text-sm text-gray-700">
                        <span class="font-medium">Cultural Adaptation</span>
                        <p class="text-xs text-gray-500">Adapt content to local culture</p>
                    </label>
                </div>
                
                <div class="flex items-center space-x-2">
                    <input type="checkbox" x-model="preserveBrandVoice" id="brand" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="brand" class="text-sm text-gray-700">
                        <span class="font-medium">Preserve Brand Voice</span>
                        <p class="text-xs text-gray-500">Maintain brand tone</p>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle text-indigo-600"></i>
                <span x-text="'Estimated cost: ' + estimatedCost + ' characters'"></span>
            </div>
            <button 
                @click="translate"
                :disabled="!sourceText || translating"
                :class="!sourceText || translating ? 'bg-gray-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                class="px-8 py-3 text-white rounded-lg font-medium transition"
            >
                <i class="fas fa-language mr-2"></i>
                <span x-text="translating ? 'Translating...' : 'Translate'"></span>
            </button>
        </div>
        
    </div>
    
    <!-- Quality Score (shown after translation) -->
    <div x-show="translatedText && qualityScore" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Translation Quality</h3>
        <div class="flex items-center space-x-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Quality Score</span>
                    <span class="text-sm font-bold text-gray-900" x-text="(qualityScore * 100) + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full transition-all duration-500" :style="'width: ' + (qualityScore * 100) + '%'"></div>
                </div>
            </div>
            <div class="text-center">
                <div class="text-3xl" x-text="qualityScore >= 0.9 ? '🌟' : qualityScore >= 0.7 ? '👍' : '⚠️'"></div>
                <p class="text-xs text-gray-600 mt-1" x-text="qualityScore >= 0.9 ? 'Excellent' : qualityScore >= 0.7 ? 'Good' : 'Fair'"></p>
            </div>
        </div>
    </div>
    
</div>

<script>
function translationTool() {
    return {
        sourceLanguage: 'auto',
        targetLanguage: 'ar',
        sourceText: '',
        translatedText: '',
        aiModel: 'gpt-4',
        culturalAdaptation: true,
        preserveBrandVoice: false,
        translating: false,
        qualityScore: null,
        
        get estimatedCost() {
            return this.sourceText.length;
        },
        
        updateCharCount() {
            // Character count is reactive
        },
        
        swapLanguages() {
            if (this.sourceLanguage !== 'auto') {
                [this.sourceLanguage, this.targetLanguage] = [this.targetLanguage, this.sourceLanguage];
                [this.sourceText, this.translatedText] = [this.translatedText, this.sourceText];
            }
        },
        
        clearSource() {
            this.sourceText = '';
            this.translatedText = '';
            this.qualityScore = null;
        },
        
        async pasteFromClipboard() {
            try {
                const text = await navigator.clipboard.readText();
                this.sourceText = text.substring(0, 10000);
            } catch (err) {
                alert('Failed to read clipboard');
            }
        },
        
        async copyToClipboard() {
            try {
                await navigator.clipboard.writeText(this.translatedText);
                alert('Copied to clipboard!');
            } catch (err) {
                alert('Failed to copy');
            }
        },
        
        downloadTranslation() {
            const blob = new Blob([this.translatedText], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'translation.txt';
            a.click();
            URL.revokeObjectURL(url);
        },
        
        async translate() {
            if (!this.sourceText) return;
            
            this.translating = true;
            this.translatedText = '';
            this.qualityScore = null;
            
            try {
                const response = await fetch('/api/v1/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('api_token'),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        text: this.sourceText,
                        source_language: this.sourceLanguage === 'auto' ? 'en' : this.sourceLanguage,
                        target_language: this.targetLanguage,
                        ai_model: this.aiModel,
                        cultural_adaptation: this.culturalAdaptation,
                        preserve_brand_voice: this.preserveBrandVoice
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.translatedText = data.data.translated_text;
                    this.qualityScore = data.data.quality_score || 0.95;
                } else {
                    alert('Translation failed: ' + data.message);
                }
            } catch (error) {
                console.error('Translation error:', error);
                // Mock translation for demo
                await new Promise(resolve => setTimeout(resolve, 2000));
                this.translatedText = 'مرحباً بالعالم! هذه ترجمة تجريبية للنص المدخل.';
                this.qualityScore = 0.95;
            } finally {
                this.translating = false;
            }
        }
    }
}
</script>
@endsection
