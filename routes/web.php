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

Route::get('/pricing', function () {
    return view('pages.pricing');
})->name('pricing');

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

// Dashboard for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.app');
    })->name('dashboard');
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
