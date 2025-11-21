<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="/js/api-client.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    
    <div x-data="dashboardApp()" x-init="init()" class="flex h-screen">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-gray-200 transition-all duration-300">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h1 x-show="sidebarOpen" class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CulturalTranslate</h1>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="p-4 space-y-2">
                <a @click="currentTab = 'overview'" :class="currentTab === 'overview' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-home w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.overview') }}</span>
                </a>
                
                <a @click="currentTab = 'translate'" :class="currentTab === 'translate' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-language w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.translate') }}</span>
                </a>
                
                <a @click="currentTab = 'history'" :class="currentTab === 'history' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-history w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.history') }}</span>
                </a>
                
                <a @click="currentTab = 'projects'" :class="currentTab === 'projects' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-folder w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.projects') }}</span>
                </a>
                
                <a @click="currentTab = 'subscription'" :class="currentTab === 'subscription' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-credit-card w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.subscription') }}</span>
                </a>
                
                <a @click="currentTab = 'settings'" :class="currentTab === 'settings' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50'" class="flex items-center space-x-3 px-4 py-3 rounded-lg cursor-pointer">
                    <i class="fas fa-cog w-5"></i>
                    <span x-show="sidebarOpen">{{ __('messages.dashboard.settings') }}</span>
                </a>
                
                <a @click="logout()" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span x-show="sidebarOpen">Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" x-text="getTabTitle()"></h2>
                    <div class="flex items-center space-x-4">
                        <button class="relative text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div class="flex items-center space-x-3">
                            <img :src="user.avatar" class="w-10 h-10 rounded-full">
                            <div>
                                <div class="text-sm font-medium text-gray-900" x-text="user.name"></div>
                                <div class="text-xs text-gray-500" x-text="user.plan"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                
                <!-- Overview Tab -->
                <div x-show="currentTab === 'overview'" x-cloak class="space-y-6">
                    @include('dashboard.tabs.overview')
                </div>
                
                <!-- Translate Tab -->
                <div x-show="currentTab === 'translate'" x-cloak>
                    @include('dashboard.tabs.translate')
                </div>
                
                <!-- History Tab -->
                <div x-show="currentTab === 'history'" x-cloak>
                    @include('dashboard.tabs.history')
                </div>
                
                <!-- Projects Tab -->
                <div x-show="currentTab === 'projects'" x-cloak>
                    @include('dashboard.tabs.projects')
                </div>
                
                <!-- Subscription Tab -->
                <div x-show="currentTab === 'subscription'" x-cloak>
                    @include('dashboard.tabs.subscription')
                </div>
                
                <!-- Settings Tab -->
                <div x-show="currentTab === 'settings'" x-cloak>
                    @include('dashboard.tabs.settings')
                </div>
                
            </main>
            
        </div>
        
        <!-- Loading Overlay -->
        <div x-show="loading" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-spinner fa-spin text-indigo-600 text-2xl"></i>
                    <span class="text-gray-900">{{ __('messages.common.loading') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Toast Notifications -->
        <div x-show="toast.show" x-cloak @click="toast.show = false" 
             class="fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg cursor-pointer transition-all"
             :class="{
                 'bg-green-500 text-white': toast.type === 'success',
                 'bg-red-500 text-white': toast.type === 'error',
                 'bg-yellow-500 text-white': toast.type === 'warning',
                 'bg-blue-500 text-white': toast.type === 'info'
             }">
            <div class="flex items-center space-x-3">
                <i class="fas" :class="{
                    'fa-check-circle': toast.type === 'success',
                    'fa-exclamation-circle': toast.type === 'error',
                    'fa-exclamation-triangle': toast.type === 'warning',
                    'fa-info-circle': toast.type === 'info'
                }"></i>
                <span x-text="toast.message"></span>
            </div>
        </div>
        
    </div>
    
    <script>
        function dashboardApp() {
            return {
                sidebarOpen: true,
                currentTab: 'overview',
                loading: false,
                user: {
                    name: 'Loading...',
                    email: '',
                    avatar: 'https://ui-avatars.com/api/?name=User&background=6366f1&color=fff',
                    plan: 'Loading...'
                },
                stats: {
                    translations: 0,
                    charactersUsed: 0,
                    charactersLimit: 100000,
                    projects: 0,
                    teamMembers: 0
                },
                translations: [],
                projects: [],
                subscription: null,
                toast: {
                    show: false,
                    type: 'info',
                    message: ''
                },
                
                async init() {
                    await this.loadUser();
                    await this.loadStats();
                },
                
                async loadUser() {
                    try {
                        const response = await window.apiClient.getProfile();
                        this.user = {
                            name: response.data.name,
                            email: response.data.email,
                            avatar: response.data.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(response.data.name)}&background=6366f1&color=fff`,
                            plan: response.data.subscription?.plan?.name || 'Free'
                        };
                    } catch (error) {
                        console.error('Failed to load user:', error);
                        this.showToast('error', 'Failed to load user data');
                    }
                },
                
                async loadStats() {
                    try {
                        const response = await window.apiClient.getDashboard();
                        this.stats = response.data;
                    } catch (error) {
                        console.error('Failed to load stats:', error);
                    }
                },
                
                async loadTranslations() {
                    try {
                        this.loading = true;
                        const response = await window.apiClient.getTranslations();
                        this.translations = response.data;
                    } catch (error) {
                        this.showToast('error', 'Failed to load translations');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async logout() {
                    try {
                        await window.apiClient.logout();
                        window.location.href = '/login';
                    } catch (error) {
                        this.showToast('error', 'Logout failed');
                    }
                },
                
                getTabTitle() {
                    const titles = {
                        overview: '{{ __("messages.dashboard.overview") }}',
                        translate: '{{ __("messages.dashboard.translate") }}',
                        history: '{{ __("messages.dashboard.history") }}',
                        projects: '{{ __("messages.dashboard.projects") }}',
                        subscription: '{{ __("messages.dashboard.subscription") }}',
                        settings: '{{ __("messages.dashboard.settings") }}'
                    };
                    return titles[this.currentTab] || 'Dashboard';
                },
                
                showToast(type, message) {
                    this.toast = { show: true, type, message };
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 3000);
                }
            }
        }
    </script>
</body>
</html>
