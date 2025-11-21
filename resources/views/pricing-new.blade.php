<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الباقات والأسعار - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50" x-data="{ billingPeriod: 'monthly', showContactModal: false }">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
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
                
                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="/" class="text-gray-700 hover:text-indigo-600 transition">الرئيسية</a>
                    <a href="/features" class="text-gray-700 hover:text-indigo-600 transition">المميزات</a>
                    <a href="/pricing" class="text-indigo-600 font-semibold">الأسعار</a>
                    <x-language-switcher />
                    <a href="/admin" class="text-gray-700 hover:text-indigo-600 transition">تسجيل الدخول</a>
                    <a href="/register" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">ابدأ الآن</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">اختر الباقة المناسبة لك</h1>
            <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                باقات مرنة تناسب جميع الاحتياجات، من الأفراد إلى الشركات الكبرى
            </p>
            
            <!-- Billing Period Toggle -->
            <div class="inline-flex bg-white/20 backdrop-blur-sm rounded-lg p-1">
                <button 
                    @click="billingPeriod = 'monthly'"
                    :class="billingPeriod === 'monthly' ? 'bg-white text-indigo-600' : 'text-white'"
                    class="px-6 py-2 rounded-md transition font-semibold">
                    شهرياً
                </button>
                <button 
                    @click="billingPeriod = 'yearly'"
                    :class="billingPeriod === 'yearly' ? 'bg-white text-indigo-600' : 'text-white'"
                    class="px-6 py-2 rounded-md transition font-semibold">
                    سنوياً
                    <span class="mr-1 text-sm">(وفّر 20%)</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section class="py-16 -mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                
                @foreach($plans as $plan)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300 {{ $plan->is_popular ? 'ring-4 ring-indigo-500' : '' }}">
                    @if($plan->is_popular)
                    <div class="bg-indigo-600 text-white text-center py-2 font-semibold">
                        الأكثر شعبية ⭐
                    </div>
                    @endif
                    
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                        
                        @if($plan->is_custom)
                        <div class="mb-6">
                            <div class="text-4xl font-bold text-indigo-600">تواصل معنا</div>
                            <p class="text-gray-500 mt-2">للحصول على عرض مخصص</p>
                        </div>
                        @else
                        <div class="mb-6">
                            <div class="flex items-baseline justify-center">
                                <span class="text-5xl font-bold text-gray-900">
                                    <span x-show="billingPeriod === 'monthly'">{{ number_format($plan->price, 0) }}</span>
                                    <span x-show="billingPeriod === 'yearly'">{{ number_format($plan->price * 12 * 0.8, 0) }}</span>
                                </span>
                                <span class="text-gray-600 mr-2">{{ $plan->currency }}</span>
                            </div>
                            <p class="text-gray-500 mt-2">
                                <span x-show="billingPeriod === 'monthly'">شهرياً</span>
                                <span x-show="billingPeriod === 'yearly'">سنوياً</span>
                            </p>
                        </div>
                        @endif
                        
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ number_format($plan->tokens_limit) }} توكن شهرياً</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $plan->max_projects }} مشروع</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $plan->max_team_members }} عضو فريق</span>
                            </li>
                            @if($plan->api_access)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">الوصول إلى API</span>
                            </li>
                            @endif
                            @if($plan->priority_support)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">دعم فني مميز</span>
                            </li>
                            @endif
                            @if($plan->custom_integrations)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">تكاملات مخصصة</span>
                            </li>
                            @endif
                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-500 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                        
                        @if($plan->is_custom)
                        <button 
                            @click="showContactModal = true"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            تواصل معنا
                        </button>
                        @else
                        <a href="/register?plan={{ $plan->slug }}" 
                           class="block w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-center">
                            ابدأ الآن
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">الأسئلة الشائعة</h2>
            
            <div class="space-y-6" x-data="{ openFaq: null }">
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full px-6 py-4 text-right flex justify-between items-center">
                        <span class="font-semibold text-gray-900">ما هي التوكنات؟</span>
                        <svg class="w-5 h-5 transform transition" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-6 pb-4 text-gray-600">
                        التوكنات هي وحدات قياس الاستخدام في المنصة. كل عملية ترجمة تستهلك عدداً معيناً من التوكنات حسب طول النص ومستوى التعقيد.
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full px-6 py-4 text-right flex justify-between items-center">
                        <span class="font-semibold text-gray-900">هل يمكنني تغيير الباقة لاحقاً؟</span>
                        <svg class="w-5 h-5 transform transition" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-6 pb-4 text-gray-600">
                        نعم، يمكنك الترقية أو التخفيض في أي وقت. سيتم احتساب الفرق بشكل تناسبي.
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full px-6 py-4 text-right flex justify-between items-center">
                        <span class="font-semibold text-gray-900">ماذا يحدث عند انتهاء التوكنات؟</span>
                        <svg class="w-5 h-5 transform transition" :class="openFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-6 pb-4 text-gray-600">
                        ستتلقى تنبيهاً عندما تصل إلى 80% من استخدام التوكنات. عند انتهاء التوكنات، يمكنك ترقية باقتك أو الانتظار حتى إعادة التعيين الشهرية.
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-lg">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full px-6 py-4 text-right flex justify-between items-center">
                        <span class="font-semibold text-gray-900">كيف يمكنني الحصول على باقة مخصصة؟</span>
                        <svg class="w-5 h-5 transform transition" :class="openFaq === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-6 pb-4 text-gray-600">
                        للحصول على باقة مخصصة تناسب احتياجات شركتك، يمكنك التواصل معنا عبر نموذج الاتصال أو البريد الإلكتروني.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Modal -->
    <div x-show="showContactModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showContactModal = false" class="fixed inset-0 bg-black opacity-50"></div>
            
            <div class="relative bg-white rounded-lg max-w-md w-full p-8">
                <button @click="showContactModal = false" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <h3 class="text-2xl font-bold mb-4">تواصل معنا للباقة المخصصة</h3>
                <p class="text-gray-600 mb-6">املأ النموذج وسنتواصل معك خلال 24 ساعة</p>
                
                <form action="/contact-custom-plan" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">احتياجاتك</label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        إرسال الطلب
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">&copy; 2024 CulturalTranslate. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

</body>
</html>
