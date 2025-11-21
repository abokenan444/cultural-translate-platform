<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    @include('components.navigation')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="sticky top-4 space-y-2">
                    <a href="#getting-started" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Getting Started</a>
                    <a href="#authentication" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Authentication</a>
                    <a href="#translation" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Translation API</a>
                    <a href="#voice" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Voice Translation</a>
                    <a href="#visual" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Visual Translation</a>
                    <a href="#errors" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Error Handling</a>
                </div>
            </div>

            <!-- Content -->
            <div class="md:col-span-3 space-y-12">
                
                <!-- Getting Started -->
                <section id="getting-started" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Getting Started</h2>
                    <p class="text-gray-600 mb-6">
                        Welcome to the CulturalTranslate API! Our RESTful API allows you to integrate powerful translation capabilities into your applications.
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Base URL</div>
                        <code class="text-indigo-600">https://api.culturaltranslate.com/v1</code>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4">
                        <p class="text-sm text-blue-900">
                            <strong>Note:</strong> All API requests must be made over HTTPS. Requests over HTTP will fail.
                        </p>
                    </div>
                </section>

                <!-- Authentication -->
                <section id="authentication" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Authentication</h2>
                    <p class="text-gray-600 mb-6">
                        Authenticate your API requests using Bearer tokens. You can generate API keys from your dashboard.
                    </p>
                    <pre class="language-bash"><code>curl -X POST https://api.culturaltranslate.com/v1/translate \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"text": "Hello", "target_language": "ar"}'</code></pre>
                </section>

                <!-- Translation API -->
                <section id="translation" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Translation API</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Translate Text</h3>
                    <p class="text-gray-600 mb-4">Translate text from one language to another with cultural adaptation.</p>
                    
                    <div class="mb-6">
                        <div class="bg-gray-50 rounded-t-lg px-4 py-2 border-b border-gray-200">
                            <code class="text-sm font-mono">POST /v1/translate</code>
                        </div>
                        <div class="bg-gray-900 rounded-b-lg p-4">
                            <pre class="language-javascript"><code>{
  "text": "Hello, world!",
  "source_language": "en",
  "target_language": "ar",
  "ai_model": "gpt-4",
  "cultural_adaptation": true,
  "preserve_brand_voice": true,
  "formal_tone": false
}</code></pre>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Response</h4>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>{
  "success": true,
  "data": {
    "translated_text": "مرحباً بالعالم!",
    "source_language": "en",
    "target_language": "ar",
    "characters_used": 13,
    "quality_score": 0.95
  }
}</code></pre>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Batch Translation</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/translate/batch

{
  "texts": ["Hello", "Goodbye", "Thank you"],
  "source_language": "en",
  "target_language": "ar"
}</code></pre>
                    </div>
                </section>

                <!-- Voice Translation -->
                <section id="voice" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Voice Translation</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Speech to Text</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>POST /v1/voice/speech-to-text

{
  "audio_url": "https://example.com/audio.mp3",
  "source_language": "en"
}</code></pre>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Text to Speech</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/voice/text-to-speech

{
  "text": "Hello, world!",
  "language": "en",
  "voice": "male",
  "cultural_tone": true
}</code></pre>
                    </div>
                </section>

                <!-- Visual Translation -->
                <section id="visual" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Visual Translation</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Translate Image</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <pre class="language-javascript"><code>POST /v1/visual/translate-image

{
  "image_url": "https://example.com/image.jpg",
  "target_language": "ar",
  "preserve_layout": true
}</code></pre>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Translate Document</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <pre class="language-javascript"><code>POST /v1/visual/translate-document

{
  "document_url": "https://example.com/doc.pdf",
  "target_language": "ar",
  "format": "pdf"
}</code></pre>
                    </div>
                </section>

                <!-- Error Handling -->
                <section id="errors" class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Error Handling</h2>
                    <p class="text-gray-600 mb-6">
                        The API uses standard HTTP response codes to indicate success or failure.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-green-600 bg-green-50 p-4">
                            <div class="font-semibold text-green-900">200 - OK</div>
                            <div class="text-sm text-green-700">Request succeeded</div>
                        </div>
                        <div class="border-l-4 border-yellow-600 bg-yellow-50 p-4">
                            <div class="font-semibold text-yellow-900">400 - Bad Request</div>
                            <div class="text-sm text-yellow-700">Invalid request parameters</div>
                        </div>
                        <div class="border-l-4 border-red-600 bg-red-50 p-4">
                            <div class="font-semibold text-red-900">401 - Unauthorized</div>
                            <div class="text-sm text-red-700">Invalid or missing API key</div>
                        </div>
                        <div class="border-l-4 border-red-600 bg-red-50 p-4">
                            <div class="font-semibold text-red-900">429 - Too Many Requests</div>
                            <div class="text-sm text-red-700">Rate limit exceeded</div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    @include('components.footer')

</body>
</html>
