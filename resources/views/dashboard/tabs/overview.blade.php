<div x-data="overviewTab()" x-init="loadData()" class="space-y-6">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-medium text-gray-600">{{ __('messages.dashboard.stats.translations') }}</div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-language text-blue-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="stats.translations.toLocaleString()"></div>
            <div class="text-sm" :class="stats.translationsGrowth >= 0 ? 'text-green-600' : 'text-red-600'">
                <i class="fas" :class="stats.translationsGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'"></i>
                <span x-text="Math.abs(stats.translationsGrowth) + '%'"></span> this month
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-medium text-gray-600">{{ __('messages.dashboard.stats.characters_used') }}</div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-font text-purple-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="formatNumber(stats.charactersUsed)"></div>
            <div class="text-sm text-gray-500">
                of <span x-text="formatNumber(stats.charactersLimit)"></span> limit
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full bg-purple-500 transition-all" :style="`width: ${(stats.charactersUsed / stats.charactersLimit * 100)}%`"></div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-medium text-gray-600">{{ __('messages.dashboard.stats.projects') }}</div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-green-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="stats.projects"></div>
            <div class="text-sm text-gray-500">
                <span x-text="stats.activeProjects"></span> {{ __('messages.dashboard.stats.active') }}
            </div>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-medium text-gray-600">{{ __('messages.dashboard.stats.team_members') }}</div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-yellow-600"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="stats.teamMembers"></div>
            <div class="text-sm text-gray-500">
                of <span x-text="stats.teamLimit"></span> seats
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Usage This Month</h3>
            <canvas id="usageChart" height="250"></canvas>
        </div>
        
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Languages Distribution</h3>
            <canvas id="languagesChart" height="250"></canvas>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Translations</h3>
            <button @click="refreshActivity()" class="px-3 py-1 text-sm text-indigo-600 hover:bg-indigo-50 rounded transition">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
        <div class="divide-y divide-gray-200">
            <template x-for="(item, index) in recentActivity" :key="index">
                <div class="p-6 hover:bg-gray-50 transition cursor-pointer" @click="viewTranslation(item.id)">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900" x-text="item.title || 'Translation #' + item.id"></div>
                            <div class="text-sm text-gray-500 mt-1">
                                <span x-text="item.source_language"></span> → <span x-text="item.target_language"></span> • 
                                <span x-text="item.character_count.toLocaleString()"></span> characters
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full" 
                                  :class="{
                                      'bg-green-100 text-green-800': item.status === 'completed',
                                      'bg-yellow-100 text-yellow-800': item.status === 'processing',
                                      'bg-red-100 text-red-800': item.status === 'failed'
                                  }" 
                                  x-text="item.status.charAt(0).toUpperCase() + item.status.slice(1)"></span>
                            <span class="text-sm text-gray-500" x-text="formatTime(item.created_at)"></span>
                        </div>
                    </div>
                </div>
            </template>
            
            <div x-show="recentActivity.length === 0" class="p-6 text-center text-gray-500">
                No translations yet. Start translating to see your activity here.
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 text-center">
            <button @click="$dispatch('change-tab', 'history')" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                View All Translations →
            </button>
        </div>
    </div>
    
</div>

<script>
function overviewTab() {
    return {
        stats: {
            translations: 0,
            translationsGrowth: 0,
            charactersUsed: 0,
            charactersLimit: 100000,
            projects: 0,
            activeProjects: 0,
            teamMembers: 0,
            teamLimit: 10
        },
        recentActivity: [],
        usageChart: null,
        languagesChart: null,
        
        async loadData() {
            await Promise.all([
                this.loadStats(),
                this.loadRecentActivity(),
                this.loadCharts()
            ]);
        },
        
        async loadStats() {
            try {
                const response = await window.apiClient.getDashboard();
                this.stats = {
                    translations: response.data.total_translations || 0,
                    translationsGrowth: response.data.translations_growth || 0,
                    charactersUsed: response.data.characters_used || 0,
                    charactersLimit: response.data.characters_limit || 100000,
                    projects: response.data.total_projects || 0,
                    activeProjects: response.data.active_projects || 0,
                    teamMembers: response.data.team_members || 1,
                    teamLimit: response.data.team_limit || 10
                };
            } catch (error) {
                console.error('Failed to load stats:', error);
                // Use demo data if API fails
                this.stats = {
                    translations: 1234,
                    translationsGrowth: 12,
                    charactersUsed: 45200,
                    charactersLimit: 100000,
                    projects: 8,
                    activeProjects: 3,
                    teamMembers: 5,
                    teamLimit: 10
                };
            }
        },
        
        async loadRecentActivity() {
            try {
                const response = await window.apiClient.getTranslations(1, 5);
                this.recentActivity = response.data.data || [];
            } catch (error) {
                console.error('Failed to load recent activity:', error);
                // Use demo data if API fails
                this.recentActivity = [
                    {
                        id: 1,
                        title: 'Marketing Campaign Text',
                        source_language: 'English',
                        target_language: 'Arabic',
                        character_count: 1234,
                        status: 'completed',
                        created_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
                    },
                    {
                        id: 2,
                        title: 'Product Description',
                        source_language: 'Spanish',
                        target_language: 'French',
                        character_count: 856,
                        status: 'completed',
                        created_at: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString()
                    },
                    {
                        id: 3,
                        title: 'Website Content',
                        source_language: 'English',
                        target_language: 'German',
                        character_count: 2145,
                        status: 'completed',
                        created_at: new Date(Date.now() - 8 * 60 * 60 * 1000).toISOString()
                    }
                ];
            }
        },
        
        async loadCharts() {
            await this.$nextTick();
            this.createUsageChart();
            this.createLanguagesChart();
        },
        
        createUsageChart() {
            const ctx = document.getElementById('usageChart');
            if (!ctx) return;
            
            if (this.usageChart) {
                this.usageChart.destroy();
            }
            
            this.usageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Characters Used',
                        data: [8500, 12300, 15600, 18200],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },
        
        createLanguagesChart() {
            const ctx = document.getElementById('languagesChart');
            if (!ctx) return;
            
            if (this.languagesChart) {
                this.languagesChart.destroy();
            }
            
            this.languagesChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Arabic', 'English', 'Spanish', 'French', 'Others'],
                    datasets: [{
                        data: [35, 25, 15, 12, 13],
                        backgroundColor: [
                            '#3b82f6',
                            '#8b5cf6',
                            '#10b981',
                            '#f59e0b',
                            '#6b7280'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        async refreshActivity() {
            await this.loadRecentActivity();
            this.$dispatch('show-toast', { type: 'success', message: 'Activity refreshed!' });
        },
        
        viewTranslation(id) {
            this.$dispatch('change-tab', 'history');
            // Optionally scroll to specific translation
        },
        
        formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toLocaleString();
        },
        
        formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            if (diffMins < 60) {
                return diffMins + ' mins ago';
            } else if (diffHours < 24) {
                return diffHours + ' hours ago';
            } else if (diffDays < 7) {
                return diffDays + ' days ago';
            } else {
                return date.toLocaleDateString();
            }
        }
    }
}
</script>
