<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->prefix('admin-dashboard')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

// لوحة التحكم الجديدة (Tailwind + Flowbite)
Route::middleware(['auth'])
    ->prefix('admin-dashboard')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
    });
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'index'])
            ->name('ai-agent-chat.index');

        Route::post('ai-dev-chat', [\App\Http\Controllers\Admin\AIAgentChatController::class, 'send'])
            ->name('ai-agent-chat.send');
    });
