@extends('dashboard.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome back! Here's what's happening with your translations.</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Translations This Month -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-language text-indigo-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-medium">+12%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium">Translations</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">1,247</p>
            <p class="text-gray-500 text-xs mt-2">This month</p>
        </div>
        
        <!-- Card 2: Characters Used -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-font text-purple-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-medium">+8%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium">Characters Used</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">245K</p>
            <p class="text-gray-500 text-xs mt-2">of 500K limit</p>
        </div>
        
        <!-- Card 3: API Calls -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-code text-blue-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-medium">+15%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium">API Calls</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">3,842</p>
            <p class="text-gray-500 text-xs mt-2">of 10K limit</p>
        </div>
        
        <!-- Card 4: Active Projects -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-green-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-medium">+3</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium">Active Projects</h3>
            <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
            <p class="text-gray-500 text-xs mt-2">Total projects</p>
        </div>
        
    </div>
    
    <!-- Usage Chart -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Usage Overview</h2>
            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>Last 90 days</option>
            </select>
        </div>
        
        <div class="h-64 flex items-end justify-between space-x-2">
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 60%"></div>
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 75%"></div>
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 45%"></div>
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 90%"></div>
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 65%"></div>
            <div class="flex-1 bg-indigo-100 rounded-t-lg hover:bg-indigo-200 transition cursor-pointer" style="height: 80%"></div>
            <div class="flex-1 bg-indigo-600 rounded-t-lg hover:bg-indigo-700 transition cursor-pointer" style="height: 100%"></div>
        </div>
        
        <div class="flex items-center justify-between mt-4 text-sm text-gray-600">
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
            <span class="font-semibold text-indigo-600">Sun</span>
        </div>
    </div>
    
    <!-- Recent Translations & Quick Actions -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Recent Translations -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Recent Translations</h2>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-6 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">Hello, world! Welcome to our platform...</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-flag"></i>
                                    <span>EN → AR</span>
                                </span>
                                <span>245 characters</span>
                                <span>2 minutes ago</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                    </div>
                </div>
                
                <div class="p-6 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">Thank you for your purchase. Your order...</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-flag"></i>
                                    <span>EN → ES</span>
                                </span>
                                <span>189 characters</span>
                                <span>15 minutes ago</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                    </div>
                </div>
                
                <div class="p-6 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">We are excited to announce our new...</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-flag"></i>
                                    <span>EN → FR</span>
                                </span>
                                <span>312 characters</span>
                                <span>1 hour ago</span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-100">
                <a href="/dashboard/history" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">View all translations →</a>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            <div class="space-y-3">
                <a href="/dashboard/translate" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                    <i class="fas fa-language mr-2"></i>
                    New Translation
                </a>
                
                <a href="/dashboard/api-keys" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-200 transition font-medium">
                    <i class="fas fa-key mr-2"></i>
                    Generate API Key
                </a>
                
                <a href="/dashboard/subscription" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-200 transition font-medium">
                    <i class="fas fa-crown mr-2"></i>
                    Upgrade Plan
                </a>
                
                <a href="/api-docs" class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg hover:bg-gray-200 transition font-medium">
                    <i class="fas fa-book mr-2"></i>
                    View API Docs
                </a>
            </div>
            
            <!-- Subscription Status -->
            <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Current Plan</span>
                    <span class="px-2 py-1 bg-indigo-600 text-white rounded text-xs font-bold">BUSINESS</span>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">500K</div>
                <div class="text-xs text-gray-600">characters / month</div>
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>245K used</span>
                        <span>49%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: 49%"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
