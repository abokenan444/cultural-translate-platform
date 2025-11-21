<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Use Cases - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    @include('components.navigation')

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Real-World Use Cases</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                Discover how businesses across industries use CulturalTranslate to break language barriers and expand globally
            </p>
        </div>
    </section>

    <!-- Use Cases Grid -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- E-commerce -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold mb-4">
                            E-commerce
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Global Online Stores</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Translate product descriptions, reviews, and checkout pages to sell worldwide. Maintain your brand voice across all languages while adapting to local cultural preferences.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Product Catalog Translation</div>
                                    <div class="text-gray-600">Translate thousands of products in minutes</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Customer Reviews</div>
                                    <div class="text-gray-600">Show reviews in customer's language</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">SEO Optimization</div>
                                    <div class="text-gray-600">Culturally-adapted content for better rankings</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="/pricing" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition inline-block">
                                Start Free Trial
                            </a>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Fashion Retailer</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Expanding to 12 new markets with 50,000 products</p>
                            <p><strong class="text-gray-900">Solution:</strong> Automated translation with cultural adaptation</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>300% increase in international sales</li>
                                <li>95% reduction in translation time</li>
                                <li>4.8/5 customer satisfaction rating</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketing & Advertising -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1 bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Global Marketing Agency</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Launch campaigns in 20 countries simultaneously</p>
                            <p><strong class="text-gray-900">Solution:</strong> Real-time collaboration with cultural experts</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>70% faster campaign launches</li>
                                <li>2x higher engagement rates</li>
                                <li>$500K saved on translation costs</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="inline-block px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold mb-4">
                            Marketing & Advertising
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Global Campaigns</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Launch marketing campaigns across multiple markets with culturally-adapted messaging that resonates with local audiences while maintaining brand consistency.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Ad Copy Translation</div>
                                    <div class="text-gray-600">Culturally-adapted ad content</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Social Media Content</div>
                                    <div class="text-gray-600">Localized posts for each market</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Video Subtitles</div>
                                    <div class="text-gray-600">Automatic subtitle generation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SaaS & Technology -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mb-4">
                            SaaS & Technology
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Software Localization</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Localize your software, documentation, and support content to serve global users. Integrate seamlessly with your development workflow.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">UI/UX Translation</div>
                                    <div class="text-gray-600">Translate interface strings with context</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Documentation</div>
                                    <div class="text-gray-600">Technical docs in multiple languages</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">API Integration</div>
                                    <div class="text-gray-600">Automate translation in your workflow</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Project Management Tool</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Support 15 languages for global teams</p>
                            <p><strong class="text-gray-900">Solution:</strong> API integration with CI/CD pipeline</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>200% increase in international users</li>
                                <li>Zero translation delays in releases</li>
                                <li>4.9/5 user satisfaction score</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education & E-learning -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1 bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Online Learning Platform</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Make 5,000 courses available in 10 languages</p>
                            <p><strong class="text-gray-900">Solution:</strong> Video subtitle generation + course material translation</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>500% growth in international students</li>
                                <li>90% cost reduction vs. human translation</li>
                                <li>Courses launched 10x faster</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold mb-4">
                            Education & E-learning
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Global Education</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Make education accessible worldwide by translating courses, videos, and learning materials while preserving educational quality and context.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Course Content</div>
                                    <div class="text-gray-600">Translate lessons and materials</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Video Subtitles</div>
                                    <div class="text-gray-600">Auto-generate multilingual subtitles</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Assessments</div>
                                    <div class="text-gray-600">Translate quizzes and exams</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Healthcare -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold mb-4">
                            Healthcare
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Medical Translation</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Translate medical documents, patient information, and healthcare content with accuracy and cultural sensitivity for better patient care.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Patient Records</div>
                                    <div class="text-gray-600">Accurate medical terminology</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Prescriptions</div>
                                    <div class="text-gray-600">Clear instructions in patient's language</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Research Papers</div>
                                    <div class="text-gray-600">Share medical research globally</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">International Hospital</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Serve patients speaking 30+ languages</p>
                            <p><strong class="text-gray-900">Solution:</strong> Real-time translation for patient communication</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>50% reduction in miscommunication</li>
                                <li>Better patient satisfaction scores</li>
                                <li>Faster treatment decisions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legal & Finance -->
            <div class="mb-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1 bg-white rounded-2xl shadow-xl p-8">
                        <div class="text-sm text-gray-500 mb-2">Case Study</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">International Law Firm</h3>
                        <div class="space-y-4 text-gray-600">
                            <p><strong class="text-gray-900">Challenge:</strong> Translate contracts and legal documents accurately</p>
                            <p><strong class="text-gray-900">Solution:</strong> Legal terminology database + human review</p>
                            <p><strong class="text-gray-900">Results:</strong></p>
                            <ul class="list-disc list-inside space-y-2 ml-4">
                                <li>80% faster document processing</li>
                                <li>100% accuracy with legal terms</li>
                                <li>60% cost savings</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold mb-4">
                            Legal & Finance
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Professional Services</h2>
                        <p class="text-lg text-gray-600 mb-6">
                            Translate legal documents, contracts, and financial reports with precision and industry-specific terminology for global business operations.
                        </p>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Contracts & Agreements</div>
                                    <div class="text-gray-600">Accurate legal terminology</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Financial Reports</div>
                                    <div class="text-gray-600">Precise numbers and terms</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Compliance Documents</div>
                                    <div class="text-gray-600">Regulatory compliance across markets</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Ready to Transform Your Business?</h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join thousands of companies using CulturalTranslate to expand globally
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition font-semibold">
                    Start Free Trial
                </a>
                <a href="/contact" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-indigo-600 transition font-semibold">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.footer')

</body>
</html>
