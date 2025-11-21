<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-800">
                <a href="/dashboard" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                    <span class="text-lg font-bold">CulturalTranslate</span>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2">
                <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="/dashboard/translate" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/translate') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-language w-5"></i>
                    <span>Translate</span>
                </a>
                
                <a href="/dashboard/history" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/history') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-history w-5"></i>
                    <span>History</span>
                </a>
                
                <a href="/dashboard/api-keys" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/api-keys') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-key w-5"></i>
                    <span>API Keys</span>
                </a>
                
                <a href="/dashboard/usage" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/usage') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Usage</span>
                </a>
                
                <a href="/dashboard/subscription" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/subscription') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-crown w-5"></i>
                    <span>Subscription</span>
                </a>
                
                <a href="/dashboard/billing" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/billing') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-credit-card w-5"></i>
                    <span>Billing</span>
                </a>
                
                <a href="/dashboard/settings" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 transition {{ request()->is('dashboard/settings') ? 'bg-gray-800' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <!-- User Section -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                        <span class="text-sm font-semibold">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="search" placeholder="Search..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                    </button>
                    
                    <a href="/" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home text-xl"></i>
                    </a>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
            
        </div>
    </div>
    
    @yield('scripts')
</body>
</html>
