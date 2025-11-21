<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integrations - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Integrations</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                Connect CulturalTranslate with your favorite tools and streamline your translation workflow
            </p>
        </div>
    </section>

    <!-- Integration Categories -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Communication Tools -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Communication & Collaboration</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-slack text-5xl" style="color: #4A154B"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Slack</h3>
                        <p class="text-gray-600 mb-4">Translate messages in real-time directly in your Slack channels. Use slash commands to translate any text instantly.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Real-time translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Slash commands</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Bot integration</span>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Slack
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-microsoft text-5xl" style="color: #5059C9"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Microsoft Teams</h3>
                        <p class="text-gray-600 mb-4">Integrate translation capabilities into Teams chats and channels for seamless global collaboration.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Teams bot</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Adaptive cards</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Channel integration</span>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Teams
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-video text-5xl text-blue-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Zoom</h3>
                        <p class="text-gray-600 mb-4">Real-time transcription and translation for Zoom meetings, making global meetings accessible to everyone.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Live transcription</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Real-time translation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Meeting summaries</span>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect Zoom
                        </button>
                    </div>
                </div>
            </div>

            <!-- Development Tools -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Development & API</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-github text-5xl text-gray-900"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">GitHub</h3>
                        <p class="text-gray-600 mb-4">Automate translation of documentation, README files, and issue comments in your repositories.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>GitHub Actions</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>PR translations</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Docs automation</span>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect GitHub
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-gitlab text-5xl" style="color: #FC6D26"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">GitLab</h3>
                        <p class="text-gray-600 mb-4">Integrate translation into your GitLab CI/CD pipeline for automated localization workflows.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>CI/CD integration</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Pipeline automation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Merge request hooks</span>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Connect GitLab
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-code text-5xl text-indigo-600"></i>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">REST API</h3>
                        <p class="text-gray-600 mb-4">Full-featured REST API for custom integrations. Build your own translation workflows.</p>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>RESTful endpoints</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>Webhooks</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-check text-green-600"></i>
                                <span>SDKs available</span>
                            </div>
                        </div>
                        <a href="/api-docs" class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-center">
                            View API Docs
                        </a>
                    </div>
                </div>
            </div>

            <!-- E-commerce -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">E-commerce Platforms</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-shopify text-5xl" style="color: #96BF48"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Shopify</h3>
                        <p class="text-gray-600 mb-4">Translate your entire store including products, collections, and checkout pages.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-wordpress text-5xl" style="color: #21759B"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">WooCommerce</h3>
                        <p class="text-gray-600 mb-4">WordPress plugin for automatic translation of WooCommerce stores.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-magento text-5xl" style="color: #EE672F"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Magento</h3>
                        <p class="text-gray-600 mb-4">Enterprise-grade translation for Magento stores with multi-store support.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>
                </div>
            </div>

            <!-- CMS -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Content Management Systems</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-wordpress text-5xl" style="color: #21759B"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">WordPress</h3>
                        <p class="text-gray-600 mb-4">Plugin for translating WordPress posts, pages, and custom post types.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-cube text-5xl text-blue-600"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Contentful</h3>
                        <p class="text-gray-600 mb-4">Headless CMS integration for automatic content translation.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-8 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-file-alt text-5xl text-green-600"></i>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Coming Soon</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Strapi</h3>
                        <p class="text-gray-600 mb-4">Open-source headless CMS integration with translation automation.</p>
                        <button class="w-full px-4 py-2 bg-gray-200 text-gray-600 rounded-lg cursor-not-allowed">
                            Coming Soon
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Need a Custom Integration?</h2>
            <p class="text-xl text-indigo-100 mb-8">
                Our team can help you build custom integrations for your specific needs
            </p>
            <a href="/contact" class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition font-semibold inline-block">
                Contact Us
            </a>
        </div>
    </section>

    @include('components.footer')

</body>
</html>
