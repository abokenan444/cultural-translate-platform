<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIAgentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TranslationController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\VoiceTranslationController;
use App\Http\Controllers\Api\V1\CollaborationController;
use App\Http\Controllers\Api\V1\AIContextController;
use App\Http\Controllers\Api\V1\VisualTranslationController;
use AppHttpControllersApiV1AnalyticsController;
use AppHttpControllersApiV1AIAgentController;ller;

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
    
    // Voice Translation
    Route::post('/voice/translate', [VoiceTranslationController::class, 'translateVoice']);
    Route::post('/voice/text-to-speech', [VoiceTranslationController::class, 'textToSpeech']);
    Route::post('/voice/stream', [VoiceTranslationController::class, 'streamVoiceTranslation']);
    
    // Collaboration
    Route::post('/projects', [CollaborationController::class, 'createProject']);
    Route::post('/projects/{projectId}/invite', [CollaborationController::class, 'inviteMember']);
    Route::get('/projects/{projectId}/session', [CollaborationController::class, 'getSession']);
    Route::post('/projects/{projectId}/translations/{translationId}/comments', [CollaborationController::class, 'addComment']);
    Route::post('/projects/{projectId}/translations/{translationId}/suggestions', [CollaborationController::class, 'suggestAlternative']);
    Route::get('/projects/{projectId}/activity', [CollaborationController::class, 'getActivityFeed']);
    
    // AI Context & Smart Suggestions
    Route::post('/ai/analyze-context', [AIContextController::class, 'analyzeContext']);
    Route::post('/ai/smart-suggestions', [AIContextController::class, 'getSmartSuggestions']);
    Route::post('/ai/sentiment', [AIContextController::class, 'detectSentiment']);
    Route::get('/ai/terminology/{industry}', [AIContextController::class, 'getIndustryTerminology']);
    
    // Visual Translation
    Route::post('/visual/image', [VisualTranslationController::class, 'translateImage']);
    Route::post('/visual/video', [VisualTranslationController::class, 'translateVideo']);
    Route::get('/visual/video/status/{jobId}', [VisualTranslationController::class, 'getVideoStatus']);
    Route::post('/visual/document', [VisualTranslationController::class, 'translateDocument']);
    Route::post('/visual/screenshot', [VisualTranslationController::class, 'translateScreenshot']);
    
    // Analytics & Insigh    // Analytics
    Route::get('/analytics/overview', [AnalyticsController::class, 'overview']);
    Route::get('/analytics/usage', [AnalyticsController::class, 'usage']);
    Route::get('/analytics/performance', [AnalyticsController::class, 'performance']);
    Route::get('/analytics/insights', [AnalyticsController::class, 'insights']);
    Route::get('/analytics/predictions', [AnalyticsController::class, 'predictions']);
    Route::post('/analytics/export', [AnalyticsController::class, 'export']);

    // AI Agent (Natural Language Processing)
    Route::post('/ai-agent/process', [AIAgentController::class, 'process']);
    Route::get('/ai-agent/status', [AIAgentController::class, 'status']);
    Route::get('/ai-agent/history', [AIAgentController::class, 'history']);
    Route::delete('/ai-agent/history', [AIAgentController::class, 'clearHistory']);Report']);
});
