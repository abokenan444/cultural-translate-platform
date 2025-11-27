<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Admin Subdomain Routes
|--------------------------------------------------------------------------
| Routes for admin.culturaltranslate.com subdomain
| Redirects to Filament Admin Panel
*/

Route::domain('admin.culturaltranslate.com')->group(function () {
    Route::get('/', function () {
        return redirect()->route('filament.admin.pages.dashboard');
    });
});

/*
|--------------------------------------------------------------------------
| Main Website Routes
|--------------------------------------------------------------------------
| Routes for culturaltranslate.com main domain
*/

// Language Switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('home');

// Public Pages
Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/pricing', [\App\Http\Controllers\PricingController::class, 'index'])->name('pricing');
Route::post('/contact-custom-plan', [\App\Http\Controllers\PricingController::class, 'contactCustomPlan'])->name('pricing.contact-custom');

Route::get('/use-cases', function () {
    return view('pages.use-cases');
})->name('use-cases');

Route::get('/api-docs', function () {
    return view('pages.api-docs');
})->name('api-docs');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/integrations', function () {
    return view('pages.integrations');
})->name('integrations');

// Complaints
Route::get('/complaints', [\App\Http\Controllers\ComplaintController::class, 'index'])->name('complaints');
Route::post('/complaints/submit', [\App\Http\Controllers\ComplaintController::class, 'submit'])->name('complaints.submit');
Route::get('/complaints/track', [\App\Http\Controllers\ComplaintController::class, 'track'])->name('complaints.track');

// API Token Generation
Route::middleware(['auth'])->group(function () {
    Route::post('/api-token/generate', [\App\Http\Controllers\ApiTokenController::class, 'generate'])->name('api-token.generate');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{plan}', [\App\Http\Controllers\StripePaymentController::class, 'checkout'])->name('checkout');
    Route::post('/payment/create-intent', [\App\Http\Controllers\StripePaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');
});

// Stripe Webhook (no auth required)
Route::post('/webhook/stripe', [\App\Http\Controllers\StripePaymentController::class, 'webhook'])->name('webhook.stripe');

// User Dashboard
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/subscription', [\App\Http\Controllers\UserDashboardController::class, 'subscription'])->name('dashboard.subscription');
    Route::get('/usage', [\App\Http\Controllers\UserDashboardController::class, 'usage'])->name('dashboard.usage');
    Route::get('/billing', [\App\Http\Controllers\UserDashboardController::class, 'billing'])->name('dashboard.billing');
    Route::get('/companies', [\App\Http\Controllers\UserDashboardController::class, 'companies'])->name('dashboard.companies');
});

/*
|--------------------------------------------------------------------------
| OLD ROUTES - COMMENTED OUT (Replaced by Filament Admin Panel)
|--------------------------------------------------------------------------
| These routes are no longer used as we've migrated to Filament
| Keeping them commented for reference
*/

// OLD: Admin Dashboard Routes (replaced by Filament)
// Route::middleware(['auth'])->prefix('admin-dashboard')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
//         ->name('admin.dashboard');
// });

// OLD: Admin Dashboard (Tailwind + Flowbite) (replaced by Filament)
// Route::middleware(['auth'])
//     ->prefix('admin-dashboard')
//     ->name('admin.')
//     ->group(function () {
//         Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
//             ->name('dashboard');
//     });

// OLD: AI Dev Chat Routes (replaced by Filament Pages)
// Route::middleware(['auth', 'admin'])
//     ->prefix('admin')
//     ->name('admin.')
//     ->group(function () {
//         Route::get('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'index'])
//             ->name('ai-agent-chat.index');
//
//         Route::post('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'send'])
//             ->name('ai-agent-chat.send');
//     });
