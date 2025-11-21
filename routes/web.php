<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
