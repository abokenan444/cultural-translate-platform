<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2 space-x-reverse">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">CT</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">CulturalTranslate</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <span class="text-gray-700">{{ $user->name }}</span>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600">تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">مرحباً، {{ $user->name }}</h1>
            <p class="text-gray-600 mt-2">إليك نظرة عامة على حسابك واستخدامك</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            
            <!-- Tokens Remaining -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">التوكنات المتبقية</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ number_format($stats['tokens_remaining']) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ 100 - $stats['usage_percentage'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['usage_percentage'] }}% مستخدم</p>
                </div>
            </div>

            <!-- Tokens Used -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">التوكنات المستخدمة</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            {{ number_format($stats['tokens_used']) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Current Plan -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">الباقة الحالية</p>
                        <p class="text-xl font-bold text-green-600 mt-1">
                            {{ $subscription?->subscriptionPlan->name ?? 'لا يوجد' }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Days Until Expiry -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">أيام متبقية</p>
                        <p class="text-2xl font-bold {{ $daysUntilExpiry && $daysUntilExpiry < 7 ? 'text-red-600' : 'text-blue-600' }} mt-1">
                            {{ $daysUntilExpiry ?? '∞' }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Recent Usage -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-900">الاستخدام الأخير</h2>
                </div>
                <div class="p-6">
                    @if($recentUsage->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsage as $usage)
                        <div class="flex items-center justify-between pb-4 border-b last:border-0">
                            <div>
                                <p class="font-medium text-gray-900">{{ $usage->action }}</p>
                                <p class="text-sm text-gray-500">{{ $usage->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $usage->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-bold text-indigo-600">-{{ number_format($usage->tokens_used) }}</p>
                                <p class="text-xs text-gray-500">توكن</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">لا يوجد استخدام بعد</p>
                    @endif
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-900">سجل الدفعات</h2>
                </div>
                <div class="p-6">
                    @if($payments->count() > 0)
                    <div class="space-y-4">
                        @foreach($payments as $payment)
                        <div class="flex items-center justify-between pb-4 border-b last:border-0">
                            <div>
                                <p class="font-medium text-gray-900">{{ $payment->subscriptionPlan->name }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $payment->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="text-left">
                                <p class="text-lg font-bold text-green-600">${{ number_format($payment->amount, 2) }}</p>
                                <span class="text-xs px-2 py-1 rounded-full {{ $payment->status === 'succeeded' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $payment->status_text }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">لا توجد دفعات بعد</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Companies -->
        @if($companies->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">الشركات</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($companies as $company)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-bold text-lg">{{ $company->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $company->subscriptionPlan?->name ?? 'لا توجد باقة' }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full">
                                {{ $company->pivot->role_text ?? 'عضو' }}
                            </span>
                            <a href="/dashboard/companies/{{ $company->id }}" class="text-sm text-indigo-600 hover:underline">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

</body>
</html>
