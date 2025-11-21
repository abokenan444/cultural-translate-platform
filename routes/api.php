<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIAgentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TranslationController;
use App\Http\Controllers\Api\V1\SubscriptionController;

Route::middleware(['auth:sanctum'])   // يمكنك تغيير الميدلوير حسب نظامك
    ->prefix('ai-agent')
    ->group(function () {
        Route::get('/health', [AIAgentController::class, 'health']);
        Route::get('/api-health', [AIAgentController::class, 'apiHealth']);
        Route::post('/run-command', [AIAgentController::class, 'runCommand']);
        Route::post('/deploy', [AIAgentController::class, 'deploy']);
        Route::post('/optimize', [AIAgentController::class, 'optimize']);
    });
use App\Http\Controllers\AI\AiChatController;

Route::post('/ai/agent-chat', [AiChatController::class, 'chat']);

/*
|--------------------------------------------------------------------------
| CulturalTranslate API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Public plans
    Route::get('/plans', [SubscriptionController::class, 'plans']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // Translations
    Route::post('/translate', [TranslationController::class, 'translate']);
    Route::post('/translate/batch', [TranslationController::class, 'batchTranslate']);
    Route::get('/translations', [TranslationController::class, 'history']);
    Route::get('/translations/{id}', [TranslationController::class, 'show']);
    Route::delete('/translations/{id}', [TranslationController::class, 'destroy']);
    
    // Subscriptions
    Route::get('/subscription', [SubscriptionController::class, 'current']);
    Route::post('/subscription', [SubscriptionController::class, 'subscribe']);
    Route::put('/subscription/upgrade', [SubscriptionController::class, 'upgrade']);
    Route::delete('/subscription/cancel', [SubscriptionController::class, 'cancel']);
    Route::get('/subscription/usage', [SubscriptionController::class, 'usage']);
});
