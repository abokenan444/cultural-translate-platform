<div x-data="historyTab()" x-init="loadTranslations()" class="space-y-6">
    
    <!-- Filters and Search -->
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input="searchTranslations()" 
                           placeholder="Search translations..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <select x-model="filterLanguage" @change="loadTranslations()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Languages</option>
                    <option value="en">English</option>
                    <option value="ar">Arabic</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                </select>
                
                <select x-model="filterStatus" @change="loadTranslations()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="processing">Processing</option>
                    <option value="failed">Failed</option>
                </select>
                
                <button @click="exportTranslations()" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
            </div>
        </div>
    </div>
    
    <!-- Translations Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" @change="toggleSelectAll($event)" class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Translation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Languages
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Characters
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Model
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(translation, index) in translations" :key="translation.id">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" :checked="selectedIds.includes(translation.id)" 
                                       @change="toggleSelect(translation.id)" class="rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900" x-text="translation.title || 'Translation #' + translation.id"></div>
                                <div class="text-sm text-gray-500 truncate max-w-xs" x-text="translation.source_text"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2 text-sm text-gray-700">
                                    <span x-text="translation.source_language"></span>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <span x-text="translation.target_language"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700" x-text="translation.character_count.toLocaleString()"></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700" 
                                      x-text="translation.ai_model"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full" 
                                      :class="{
                                          'bg-green-100 text-green-800': translation.status === 'completed',
                                          'bg-yellow-100 text-yellow-800': translation.status === 'processing',
                                          'bg-red-100 text-red-800': translation.status === 'failed'
                                      }" 
                                      x-text="translation.status.charAt(0).toUpperCase() + translation.status.slice(1)"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500" x-text="formatDate(translation.created_at)"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button @click="viewTranslation(translation)" 
                                            class="text-indigo-600 hover:text-indigo-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="copyTranslation(translation)" 
                                            class="text-green-600 hover:text-green-900" title="Copy">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button @click="downloadTranslation(translation)" 
                                            class="text-blue-600 hover:text-blue-900" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button @click="deleteTranslation(translation.id)" 
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <div x-show="translations.length === 0 && !loading" class="p-12 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                <p>No translations found</p>
                <button @click="$dispatch('change-tab', 'translate')" 
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Start Translating
                </button>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span x-text="((currentPage - 1) * perPage) + 1"></span> to 
                <span x-text="Math.min(currentPage * perPage, totalTranslations)"></span> of 
                <span x-text="totalTranslations"></span> results
            </div>
            <div class="flex items-center space-x-2">
                <button @click="previousPage()" :disabled="currentPage === 1" 
                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-3 py-1 border border-gray-300 rounded-lg transition">
                    Previous
                </button>
                <template x-for="page in totalPages" :key="page">
                    <button @click="goToPage(page)" 
                            :class="page === currentPage ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100'"
                            class="px-3 py-1 border border-gray-300 rounded-lg transition"
                            x-text="page"></button>
                </template>
                <button @click="nextPage()" :disabled="currentPage === totalPages" 
                        :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                        class="px-3 py-1 border border-gray-300 rounded-lg transition">
                    Next
                </button>
            </div>
        </div>
    </div>
    
    <!-- Bulk Actions -->
    <div x-show="selectedIds.length > 0" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-4">
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-700">
                <span x-text="selectedIds.length"></span> selected
            </span>
            <button @click="bulkExport()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fas fa-download mr-2"></i> Export Selected
            </button>
            <button @click="bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm">
                <i class="fas fa-trash mr-2"></i> Delete Selected
            </button>
            <button @click="clearSelection()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                Clear
            </button>
        </div>
    </div>
    
    <!-- View Modal -->
    <div x-show="viewModal.show" x-cloak @click.self="viewModal.show = false" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Translation Details</h3>
                <button @click="viewModal.show = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source Text</label>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 whitespace-pre-wrap" 
                         x-text="viewModal.translation.source_text"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Translated Text</label>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 whitespace-pre-wrap" 
                         x-text="viewModal.translation.translated_text"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Source Language</label>
                        <div class="text-gray-900" x-text="viewModal.translation.source_language"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Language</label>
                        <div class="text-gray-900" x-text="viewModal.translation.target_language"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">AI Model</label>
                        <div class="text-gray-900" x-text="viewModal.translation.ai_model"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Character Count</label>
                        <div class="text-gray-900" x-text="viewModal.translation.character_count"></div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="copyTranslation(viewModal.translation)" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-copy mr-2"></i> Copy
                </button>
                <button @click="downloadTranslation(viewModal.translation)" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
                <button @click="viewModal.show = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
    
</div>

<script>
function historyTab() {
    return {
        translations: [],
        loading: false,
        searchQuery: '',
        filterLanguage: '',
        filterStatus: '',
        selectedIds: [],
        currentPage: 1,
        perPage: 10,
        totalTranslations: 0,
        totalPages: 1,
        viewModal: {
            show: false,
            translation: {}
        },
        
        async loadTranslations() {
            this.loading = true;
            try {
                const response = await window.apiClient.getTranslations(this.currentPage, this.perPage);
                this.translations = response.data.data || [];
                this.totalTranslations = response.data.total || 0;
                this.totalPages = Math.ceil(this.totalTranslations / this.perPage);
            } catch (error) {
                console.error('Failed to load translations:', error);
                // Demo data
                this.translations = this.generateDemoTranslations();
                this.totalTranslations = 50;
                this.totalPages = 5;
            } finally {
                this.loading = false;
            }
        },
        
        generateDemoTranslations() {
            const languages = ['English', 'Arabic', 'Spanish', 'French', 'German'];
            const models = ['gpt-4', 'gpt-3.5', 'google', 'deepl'];
            const statuses = ['completed', 'processing', 'failed'];
            
            return Array.from({ length: 10 }, (_, i) => ({
                id: (this.currentPage - 1) * 10 + i + 1,
                title: `Translation #${(this.currentPage - 1) * 10 + i + 1}`,
                source_text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
                translated_text: 'النص المترجم هنا...',
                source_language: languages[Math.floor(Math.random() * languages.length)],
                target_language: languages[Math.floor(Math.random() * languages.length)],
                character_count: Math.floor(Math.random() * 5000) + 100,
                ai_model: models[Math.floor(Math.random() * models.length)],
                status: statuses[Math.floor(Math.random() * statuses.length)],
                created_at: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toISOString()
            }));
        },
        
        searchTranslations() {
            // Implement search logic
            this.loadTranslations();
        },
        
        toggleSelect(id) {
            const index = this.selectedIds.indexOf(id);
            if (index > -1) {
                this.selectedIds.splice(index, 1);
            } else {
                this.selectedIds.push(id);
            }
        },
        
        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedIds = this.translations.map(t => t.id);
            } else {
                this.selectedIds = [];
            }
        },
        
        clearSelection() {
            this.selectedIds = [];
        },
        
        viewTranslation(translation) {
            this.viewModal = {
                show: true,
                translation: translation
            };
        },
        
        copyTranslation(translation) {
            navigator.clipboard.writeText(translation.translated_text);
            this.$dispatch('show-toast', { type: 'success', message: 'Copied to clipboard!' });
        },
        
        downloadTranslation(translation) {
            const content = `Source (${translation.source_language}):\n${translation.source_text}\n\nTranslation (${translation.target_language}):\n${translation.translated_text}`;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `translation-${translation.id}.txt`;
            a.click();
            URL.revokeObjectURL(url);
        },
        
        async deleteTranslation(id) {
            if (!confirm('Are you sure you want to delete this translation?')) return;
            
            try {
                await window.apiClient.deleteTranslation(id);
                await this.loadTranslations();
                this.$dispatch('show-toast', { type: 'success', message: 'Translation deleted!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to delete translation' });
            }
        },
        
        async bulkDelete() {
            if (!confirm(`Are you sure you want to delete ${this.selectedIds.length} translations?`)) return;
            
            try {
                await Promise.all(this.selectedIds.map(id => window.apiClient.deleteTranslation(id)));
                this.selectedIds = [];
                await this.loadTranslations();
                this.$dispatch('show-toast', { type: 'success', message: 'Translations deleted!' });
            } catch (error) {
                this.$dispatch('show-toast', { type: 'error', message: 'Failed to delete translations' });
            }
        },
        
        exportTranslations() {
            // Export all translations
            this.$dispatch('show-toast', { type: 'info', message: 'Exporting translations...' });
        },
        
        bulkExport() {
            // Export selected translations
            this.$dispatch('show-toast', { type: 'info', message: 'Exporting selected translations...' });
        },
        
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadTranslations();
            }
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.loadTranslations();
            }
        },
        
        goToPage(page) {
            this.currentPage = page;
            this.loadTranslations();
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    }
}
</script>
