<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    @include('components.navigation')
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">Get in Touch</h1>
                <p class="text-xl text-gray-600">We're here to help and answer any question you might have</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a message</h2>
                    <form x-data="{ submitted: false }" @submit.prevent="submitted = true" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                            Send Message
                        </button>
                        <div x-show="submitted" x-cloak class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                            Thank you! We'll get back to you soon.
                        </div>
                    </form>
                </div>
                <div class="space-y-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-envelope text-indigo-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Email</div>
                                    <a href="mailto:support@culturaltranslate.com" class="text-indigo-600">support@culturaltranslate.com</a>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-phone text-indigo-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Phone</div>
                                    <a href="tel:+1234567890" class="text-indigo-600">+1 (234) 567-890</a>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-map-marker-alt text-indigo-600 mt-1"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Address</div>
                                    <p class="text-gray-600">123 Translation Street<br>San Francisco, CA 94102</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">Business Hours</h3>
                        <div class="space-y-2 text-gray-600">
                            <div class="flex justify-between">
                                <span>Monday - Friday</span>
                                <span class="font-semibold">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Saturday</span>
                                <span class="font-semibold">10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Sunday</span>
                                <span class="font-semibold">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('components.footer')
</body>
</html>
