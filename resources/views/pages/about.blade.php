<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <!-- Hero -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">About CulturalTranslate</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                Breaking language barriers with AI-powered translation that understands culture
            </p>
        </div>
    </section>

    <!-- Mission -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Mission</h2>
            <p class="text-xl text-gray-600 leading-relaxed">
                We believe that language should never be a barrier to global communication. Our mission is to provide businesses and individuals with translation technology that not only converts words but also preserves cultural nuances, brand voice, and emotional context.
            </p>
        </div>
    </section>

    <!-- Story -->
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Our Story</h2>
                    <div class="space-y-4 text-gray-600">
                        <p>
                            Founded in 2024, CulturalTranslate was born from a simple observation: traditional translation tools were failing businesses trying to expand globally. They could translate words, but they couldn't capture the cultural context that makes communication truly effective.
                        </p>
                        <p>
                            Our founders, a team of linguists, AI researchers, and entrepreneurs, came together with a vision to create something better. We combined cutting-edge AI technology with deep cultural expertise to build a translation platform that truly understands context.
                        </p>
                        <p>
                            Today, we serve thousands of businesses across 150 countries, helping them communicate authentically with audiences around the world.
                        </p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl p-12 text-center">
                    <div class="space-y-8">
                        <div>
                            <div class="text-5xl font-bold text-indigo-600">150+</div>
                            <div class="text-gray-700 mt-2">Countries Served</div>
                        </div>
                        <div>
                            <div class="text-5xl font-bold text-indigo-600">10K+</div>
                            <div class="text-gray-700 mt-2">Active Users</div>
                        </div>
                        <div>
                            <div class="text-5xl font-bold text-indigo-600">1B+</div>
                            <div class="text-gray-700 mt-2">Words Translated</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-900 text-center mb-12">Our Values</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-globe text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Cultural Sensitivity</h3>
                    <p class="text-gray-600">
                        We respect and preserve cultural nuances in every translation, ensuring your message resonates authentically with local audiences.
                    </p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-rocket text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Innovation</h3>
                    <p class="text-gray-600">
                        We continuously push the boundaries of AI translation technology to deliver better, faster, and more accurate results.
                    </p>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Security & Privacy</h3>
                    <p class="text-gray-600">
                        Your data is encrypted and protected. We never share your content with third parties or use it to train our models.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-900 text-center mb-12">Meet Our Team</h2>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&size=200&background=6366f1&color=fff" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Sarah Johnson</h3>
                    <p class="text-indigo-600 text-sm">CEO & Co-Founder</p>
                </div>
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=Michael+Chen&size=200&background=8b5cf6&color=fff" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Michael Chen</h3>
                    <p class="text-indigo-600 text-sm">CTO & Co-Founder</p>
                </div>
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=Emma+Rodriguez&size=200&background=ec4899&color=fff" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Emma Rodriguez</h3>
                    <p class="text-indigo-600 text-sm">Head of Linguistics</p>
                </div>
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name=David+Kim&size=200&background=3b82f6&color=fff" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-lg font-bold text-gray-900">David Kim</h3>
                    <p class="text-indigo-600 text-sm">Head of AI Research</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Join Us on Our Journey</h2>
            <p class="text-xl text-indigo-100 mb-8">
                Be part of the future of global communication
            </p>
            <a href="/register" class="px-8 py-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-100 transition font-semibold inline-block">
                Get Started Today
            </a>
        </div>
    </section>

    @include('components.footer')

</body>
</html>
