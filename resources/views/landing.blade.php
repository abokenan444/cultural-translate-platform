<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CulturalTranslate - AI-Powered Cultural Translation Platform</title>
    <meta name="description" content="Professional AI-powered translation platform with cultural adaptation. Translate content in 13 languages while preserving cultural context and brand voice.">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <svg class="h-8 w-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5zm0 18c-3.87-.93-7-5.43-7-10V8.3l7-3.11 7 3.11V10c0 4.57-3.13 9.07-7 10z"/>
                        </svg>
                        <span class="ml-2 text-xl font-bold gradient-text">CulturalTranslate</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 transition">Features</a>
                    <a href="#pricing" class="text-gray-700 hover:text-indigo-600 transition">Pricing</a>
                    <a href="#use-cases" class="text-gray-700 hover:text-indigo-600 transition">Use Cases</a>
                    <a href="/api-docs" class="text-gray-700 hover:text-indigo-600 transition">API Docs</a>
                    <x-language-switcher />
                    <a href="/admin" class="text-gray-700 hover:text-indigo-600 transition">Login</a>
                    <a href="/register" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="#features" class="block text-gray-700 hover:text-indigo-600">Features</a>
                <a href="#pricing" class="block text-gray-700 hover:text-indigo-600">Pricing</a>
                <a href="#use-cases" class="block text-gray-700 hover:text-indigo-600">Use Cases</a>
                <a href="/api-docs" class="block text-gray-700 hover:text-indigo-600">API Docs</a>
                <div class="py-2">
                    <x-language-switcher />
                </div>
                <a href="/admin" class="block text-gray-700 hover:text-indigo-600">Login</a>
                <a href="/register" class="block bg-indigo-600 text-white px-6 py-2 rounded-lg text-center">Get Started</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="pt-24 pb-16 gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        AI-Powered Cultural Translation
                    </h1>
                    <p class="text-xl mb-8 text-indigo-100">
                        Translate content in 13 languages while preserving cultural context, brand voice, and meaning. Trusted by 10,000+ teams worldwide.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/register" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition text-center">
                            Start Free Trial
                        </a>
                        <a href="#demo" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition text-center">
                            Watch Demo
                        </a>
                    </div>
                    <div class="mt-8 flex items-center gap-6 text-sm text-indigo-100">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            No credit card required
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            14-day free trial
                        </div>
                    </div>
                </div>
                <div class="hidden md:block animate-float">
                    <div class="bg-white rounded-2xl shadow-2xl p-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-100 rounded w-1/2 mt-2"></div>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <div class="h-3 bg-indigo-100 rounded w-full mb-2"></div>
                                <div class="h-3 bg-indigo-50 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="py-12 bg-white border-y">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">10,000+</div>
                    <div class="text-gray-600">Active Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">13</div>
                    <div class="text-gray-600">Languages</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">50M+</div>
                    <div class="text-gray-600">Words Translated</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-indigo-600 mb-2">99.9%</div>
                    <div class="text-gray-600">Uptime SLA</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Everything you need to translate content while preserving cultural context and brand voice
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Cultural Adaptation</h3>
                    <p class="text-gray-600">
                        AI-powered cultural context preservation ensures your message resonates with local audiences
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Lightning Fast</h3>
                    <p class="text-gray-600">
                        Translate thousands of words in seconds with our optimized AI models
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Enterprise Security</h3>
                    <p class="text-gray-600">
                        GDPR compliant, SOC 2 Type II certified, with end-to-end encryption
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                            <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                            <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Translation Memory</h3>
                    <p class="text-gray-600">
                        Save costs by reusing previous translations and maintaining consistency
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Custom Glossaries</h3>
                    <p class="text-gray-600">
                        Define brand-specific terminology for consistent translations
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Developer-Friendly API</h3>
                    <p class="text-gray-600">
                        RESTful API with SDKs in 7 languages and comprehensive documentation
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                Ready to Go Global?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join 10,000+ teams using CulturalTranslate to reach global audiences
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Start Free Trial
                </a>
                <a href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">Product</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="/api-docs" class="hover:text-white transition">API Docs</a></li>
                        <li><a href="/integrations" class="hover:text-white transition">Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="/about" class="hover:text-white transition">About</a></li>
                        <li><a href="/blog" class="hover:text-white transition">Blog</a></li>
                        <li><a href="/careers" class="hover:text-white transition">Careers</a></li>
                        <li><a href="/contact" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="/help" class="hover:text-white transition">Help Center</a></li>
                        <li><a href="/guides" class="hover:text-white transition">Guides</a></li>
                        <li><a href="/case-studies" class="hover:text-white transition">Case Studies</a></li>
                        <li><a href="/status" class="hover:text-white transition">Status</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="/privacy" class="hover:text-white transition">Privacy</a></li>
                        <li><a href="/terms" class="hover:text-white transition">Terms</a></li>
                        <li><a href="/security" class="hover:text-white transition">Security</a></li>
                        <li><a href="/gdpr" class="hover:text-white transition">GDPR</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
</body>
</html>
