<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Simple Nav -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-indigo-600">CulturalTranslate</a>
                <a href="/" class="text-gray-600 hover:text-gray-900">← Back to Home</a>
            </div>
        </div>
    </nav>
    
    <!-- Hero -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Choose the perfect plan for your translation needs. All plans include 14-day free trial.</p>
        </div>
    </section>
    
    <!-- Pricing Cards -->
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                
                <!-- Starter Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200 hover:border-indigo-500 transition">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Starter</h3>
                        <p class="text-gray-600 mb-4">Perfect for individuals</p>
                        <div class="text-5xl font-bold text-gray-900 mb-2">$29<span class="text-xl text-gray-600">/mo</span></div>
                        <p class="text-sm text-gray-500">Billed monthly</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>100,000 characters/month</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>5 languages</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>1,000 API calls</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Basic support</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Translation memory</span>
                        </li>
                    </ul>
                    
                    <a href="/register?plan=starter" class="block w-full bg-gray-900 text-white text-center py-3 rounded-lg hover:bg-gray-800 transition font-semibold">
                        Start Free Trial
                    </a>
                </div>
                
                <!-- Business Plan (Popular) -->
                <div class="bg-white rounded-2xl shadow-2xl p-8 border-2 border-indigo-500 relative transform scale-105">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-500 text-white px-4 py-1 rounded-full text-sm font-semibold">MOST POPULAR</span>
                    </div>
                    
                    <div class="text-center mb-8 mt-4">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Business</h3>
                        <p class="text-gray-600 mb-4">For growing teams</p>
                        <div class="text-5xl font-bold text-indigo-600 mb-2">$99<span class="text-xl text-gray-600">/mo</span></div>
                        <p class="text-sm text-gray-500">Billed monthly</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span><strong>500,000</strong> characters/month</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span><strong>13 languages</strong></span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span><strong>10,000</strong> API calls</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span><strong>Priority support</strong></span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span>Translation memory + Glossary</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span>Cultural adaptation</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-indigo-500 mr-3"></i>
                            <span>Team collaboration</span>
                        </li>
                    </ul>
                    
                    <a href="/register?plan=business" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
                        Start Free Trial
                    </a>
                </div>
                
                <!-- Enterprise Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200 hover:border-indigo-500 transition">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Enterprise</h3>
                        <p class="text-gray-600 mb-4">For large organizations</p>
                        <div class="text-5xl font-bold text-gray-900 mb-2">Custom</div>
                        <p class="text-sm text-gray-500">Contact sales</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span><strong>Unlimited</strong> characters</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span><strong>All languages</strong></span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span><strong>Unlimited</strong> API calls</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span><strong>24/7 dedicated support</strong></span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Everything in Business</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Custom AI models</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>SLA guarantee</span>
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>On-premise deployment</span>
                        </li>
                    </ul>
                    
                    <a href="/contact?plan=enterprise" class="block w-full bg-gray-900 text-white text-center py-3 rounded-lg hover:bg-gray-800 transition font-semibold">
                        Contact Sales
                    </a>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- FAQ -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change plans later?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens if I exceed my limits?</h3>
                    <p class="text-gray-600">We'll notify you when you reach 80% of your limit. You can upgrade or purchase additional credits.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                    <p class="text-gray-600">Yes! All plans include a 14-day free trial. No credit card required to start.</p>
                </div>
                
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept all major credit cards, PayPal, and bank transfers for Enterprise plans.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 CulturalTranslate. All rights reserved.</p>
        </div>
    </footer>
    
</body>
</html>
