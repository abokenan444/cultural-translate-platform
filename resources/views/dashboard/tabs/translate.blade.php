<div class="max-w-4xl mx-auto" x-data="translateTab()">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('messages.dashboard.translate_form.title') }}</h3>
        
        <!-- Language Selectors -->
        <div class="flex items-center space-x-4 mb-6">
            <select x-model="sourceLanguage" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                <option value="tr">Turkish</option>
            </select>
            
            <button @click="swapLanguages()" class="p-3 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-exchange-alt"></i>
            </button>
            
            <select x-model="targetLanguage" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="ar">Arabic</option>
                <option value="en">English</option>
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
                <option value="tr">Turkish</option>
            </select>
        </div>
        
        <!-- AI Model Selector -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">AI Model</label>
            <div class="grid grid-cols-4 gap-3">
                <button @click="aiModel = 'gpt-4'" :class="aiModel === 'gpt-4' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-lg font-medium transition">
                    GPT-4
                </button>
                <button @click="aiModel = 'gpt-3.5'" :class="aiModel === 'gpt-3.5' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-lg font-medium transition">
                    GPT-3.5
                </button>
                <button @click="aiModel = 'google'" :class="aiModel === 'google' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-lg font-medium transition">
                    Google
                </button>
                <button @click="aiModel = 'deepl'" :class="aiModel === 'deepl' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-lg font-medium transition">
                    DeepL
                </button>
            </div>
        </div>
        
        <!-- Options -->
        <div class="mb-6 flex items-center space-x-6">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="culturalAdaptation" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Cultural Adaptation</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="preserveBrandVoice" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Preserve Brand Voice</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="formalTone" class="w-4 h-4 text-indigo-600 rounded">
                <span class="text-sm text-gray-700">Formal Tone</span>
            </label>
        </div>
        
        <!-- Source Text -->
        <div class="mb-6">
            <textarea x-model="sourceText" @input="updateCharCount()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" rows="8" placeholder="{{ __('messages.dashboard.translate_form.placeholder') }}"></textarea>
            <div class="flex items-center justify-between mt-2">
                <div class="text-sm text-gray-500">
                    <span x-text="charCount"></span> / 5,000 characters
                </div>
                <div class="flex space-x-2">
                    <button @click="startVoiceInput()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-microphone mr-1"></i> {{ __('messages.dashboard.translate_form.voice') }}
                    </button>
                    <label class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition cursor-pointer">
                        <i class="fas fa-image mr-1"></i> {{ __('messages.dashboard.translate_form.image') }}
                        <input type="file" @change="handleImageUpload($event)" accept="image/*" class="hidden">
                    </label>
                    <button @click="clearText()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-times mr-1"></i> Clear
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Translate Button -->
        <button @click="translate()" :disabled="translating || !sourceText" :class="translating || !sourceText ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'" class="w-full gradient-bg text-white px-6 py-4 rounded-lg font-semibold transition mb-6">
            <span x-show="!translating">{{ __('messages.dashboard.translate_form.translate_button') }}</span>
            <span x-show="translating" class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin mr-2"></i> Translating...
            </span>
        </button>
        
        <!-- Translation Output -->
        <div x-show="translatedText" class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-gray-700">{{ __('messages.dashboard.translate_form.translation_label') }}</span>
                <div class="flex space-x-2">
                    <button @click="copyTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition">
                        <i class="fas fa-copy mr-1"></i> {{ __('messages.dashboard.translate_form.copy') }}
                    </button>
                    <button @click="listenTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition">
                        <i class="fas fa-volume-up mr-1"></i> {{ __('messages.dashboard.translate_form.listen') }}
                    </button>
                    <button @click="downloadTranslation()" class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded transition">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                </div>
            </div>
            <div class="text-gray-900 whitespace-pre-wrap" x-text="translatedText"></div>
            
            <!-- Quality Score -->
            <div x-show="qualityScore" class="mt-4 pt-4 border-t border-gray-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Quality Score</span>
                    <span class="text-sm font-bold" :class="qualityScore >= 90 ? 'text-green-600' : qualityScore >= 70 ? 'text-yellow-600' : 'text-red-600'" x-text="qualityScore + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all" :class="qualityScore >= 90 ? 'bg-green-500' : qualityScore >= 70 ? 'bg-yellow-500' : 'bg-red-500'" :style="`width: ${qualityScore}%`"></div>
                </div>
            </div>
        </div>
        
        <div x-show="!translatedText && !translating" class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <div class="text-gray-500 text-center py-8">
                {{ __('messages.dashboard.translate_form.result_placeholder') }}
            </div>
        </div>
    </div>
</div>

<script>
function translateTab() {
    return {
        sourceLanguage: 'auto',
        targetLanguage: 'ar',
        aiModel: 'gpt-4',
        sourceText: '',
        translatedText: '',
        charCount: 0,
        translating: false,
        culturalAdaptation: true,
        preserveBrandVoice: false,
        formalTone: false,
        qualityScore: null,
        
        updateCharCount() {
            this.charCount = this.sourceText.length;
        },
        
        swapLanguages() {
            if (this.sourceLanguage !== 'auto') {
                [this.sourceLanguage, this.targetLanguage] = [this.targetLanguage, this.sourceLanguage];
                [this.sourceText, this.translatedText] = [this.translatedText, this.sourceText];
            }
        },
        
        async translate() {
            if (!this.sourceText) return;
            
            this.translating = true;
            this.translatedText = '';
            this.qualityScore = null;
            
            try {
                const response = await window.apiClient.translate(
                    this.sourceText,
                    this.sourceLanguage,
                    this.targetLanguage,
                    {
                        ai_model: this.aiModel,
                        cultural_adaptation: this.culturalAdaptation,
                        preserve_brand_voice: this.preserveBrandVoice,
                        formal_tone: this.formalTone
                    }
                );
                
                this.translatedText = response.data.translated_text;
                this.qualityScore = response.data.quality_score || 95;
                
                this.$dispatch('show-toast', { type: 'success', message: 'Translation completed!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Translation failed: ' + error.message });
            } finally {
                this.translating = false;
            }
        },
        
        copyTranslation() {
            navigator.clipboard.writeText(this.translatedText);
            this.$dispatch('show-toast', { type: 'success', message: 'Copied to clipboard!' });
        },
        
        async listenTranslation() {
            try {
                const response = await window.apiClient.textToSpeech(this.translatedText, this.targetLanguage);
                const audio = new Audio(response.data.audio_url);
                audio.play();
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to play audio' });
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
        
        clearText() {
            this.sourceText = '';
            this.translatedText = '';
            this.charCount = 0;
            this.qualityScore = null;
        },
        
        startVoiceInput() {
            this.$dispatch('show-toast', { type: 'info', message: 'Voice input feature coming soon!' });
        },
        
        async handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            try {
                this.translating = true;
                const response = await window.apiClient.translateImage(file, this.sourceLanguage, this.targetLanguage);
                this.sourceText = response.data.extracted_text;
                this.translatedText = response.data.translated_text;
                this.updateCharCount();
                this.$dispatch('show-toast', { type: 'success', message: 'Image translated successfully!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to translate image' });
            } finally {
                this.translating = false;
            }
        }
    }
}
</script>
