<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-bold mb-6">Our Blog</h1>
            <p class="text-xl text-indigo-100">Insights, tips, and stories about translation and global communication</p>
        </div>
    </section>
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=500&h=300&fit=crop" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-semibold mb-2">Translation Tips</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">10 Best Practices for Global Marketing</h3>
                        <p class="text-gray-600 mb-4">Learn how to adapt your marketing content for international audiences while maintaining brand consistency.</p>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Read More →</a>
                    </div>
                </article>
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=500&h=300&fit=crop" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-semibold mb-2">AI & Technology</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">The Future of AI Translation</h3>
                        <p class="text-gray-600 mb-4">Explore how artificial intelligence is revolutionizing the translation industry and what it means for businesses.</p>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Read More →</a>
                    </div>
                </article>
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=500&h=300&fit=crop" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-semibold mb-2">Case Studies</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">How Company X Expanded to 50 Countries</h3>
                        <p class="text-gray-600 mb-4">A success story of international expansion powered by culturally-adapted translation.</p>
                        <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Read More →</a>
                    </div>
                </article>
            </div>
        </div>
    </section>
    @include('components.footer')
</body>
</html>
