<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الشكاوى والدعم الفني - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    
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
                    <a href="/pricing" class="text-gray-700 hover:text-indigo-600 transition">الأسعار</a>
                    <x-language-switcher />
                    <a href="/admin" class="text-gray-700 hover:text-indigo-600 transition">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">الشكاوى والدعم الفني</h1>
            <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                نحن هنا لمساعدتك! أرسل شكواك أو استفسارك وسنرد عليك في أقرب وقت
            </p>
        </div>
    </section>

    <!-- Complaint Form -->
    <section class="py-16 -mt-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                
                @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="/complaints/submit" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                            <input type="text" name="name" required value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                            <input type="email" name="email" required value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">التصنيف *</label>
                            <select name="category" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">اختر التصنيف</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>مشكلة تقنية</option>
                                <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>الفواتير والاشتراكات</option>
                                <option value="feature_request" {{ old('category') == 'feature_request' ? 'selected' : '' }}>طلب ميزة جديدة</option>
                                <option value="bug_report" {{ old('category') == 'bug_report' ? 'selected' : '' }}>الإبلاغ عن خطأ</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية *</label>
                        <select name="priority" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>عاجلة</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الموضوع *</label>
                        <input type="text" name="subject" required value="{{ old('subject') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="مثال: مشكلة في الترجمة الآلية">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تفاصيل الشكوى *</label>
                        <textarea name="message" required rows="6" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="يرجى وصف المشكلة بالتفصيل...">{{ old('message') }}</textarea>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" required class="mt-1 ml-2">
                        <label class="text-sm text-gray-600">
                            أوافق على <a href="/privacy" class="text-indigo-600 hover:underline">سياسة الخصوصية</a> و 
                            <a href="/terms" class="text-indigo-600 hover:underline">شروط الاستخدام</a>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-indigo-600 text-white py-4 rounded-lg font-semibold hover:bg-indigo-700 transition text-lg">
                        إرسال الشكوى
                    </button>
                </form>
                
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">طرق التواصل الأخرى</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">البريد الإلكتروني</h3>
                    <p class="text-gray-600">support@culturaltranslate.com</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">الهاتف</h3>
                    <p class="text-gray-600">+966 50 123 4567</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">الدردشة المباشرة</h3>
                    <p class="text-gray-600">متاح من 9 صباحاً - 5 مساءً</p>
                </div>
            </div>
        </div>
    </section>

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
