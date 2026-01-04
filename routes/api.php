<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiTranslationController;
use App\Http\Controllers\Api\ImageTranslationController;
use App\Http\Controllers\Api\VoiceTranslationController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Cultural Translate Platform API v2.0
| Base URL: https://culturaltranslate.com/api/v2
|
*/
// Public Demo Translation (no authentication required)
Route::post('/demo-translate', [ApiTranslationController::class, 'demoTranslate']);

// API v1 routes - ALL using auth:sanctum (no web middleware)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Translation API
    Route::post('/translate', [ApiTranslationController::class, 'translate'])->name('api.translate');
    
    // User Integrations API
    Route::get('/integrations', [App\Http\Controllers\UserIntegrationController::class, 'index']);
    Route::post('/integrations/{platform}/disconnect', [App\Http\Controllers\UserIntegrationController::class, 'disconnect']);
    
    // Visual Translation API (Image)
    Route::post('/visual/image', [ImageTranslationController::class, 'translateImage']);
    
    // Voice Translation API
    Route::post('/visual/voice', [VoiceTranslationController::class, 'translateVoice']);
});

Route::prefix('v2')->group(function () {
    
    // Health check
    Route::get('/health', [ApiTranslationController::class, 'health']);
    
    // Get supported languages
    Route::get('/languages', [ApiTranslationController::class, 'languages']);
    
    // Get available tones
    Route::get('/tones', [ApiTranslationController::class, 'tones']);
    
    // Protected endpoints (require API key)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Translate text
        Route::post('/translate', [ApiTranslationController::class, 'translate']);
        
        // Detect language
        Route::post('/detect', [ApiTranslationController::class, 'detectLanguage']);
        
        // Get usage statistics
        Route::get('/stats', [ApiTranslationController::class, 'stats']);
    });
});

// Feedback API
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/feedback', [App\Http\Controllers\Api\FeedbackController::class, 'submitFeedback']);
    Route::get('/feedback/{translationId}', [App\Http\Controllers\Api\FeedbackController::class, 'getFeedback']);
    Route::get('/versions/{translationId}', [App\Http\Controllers\Api\FeedbackController::class, 'getSuggestedVersions']);
    Route::post('/versions/{versionId}/approve', [App\Http\Controllers\Api\FeedbackController::class, 'approveVersion']);
    Route::get('/user/stats', [App\Http\Controllers\Api\FeedbackController::class, 'getUserStats']);
});

// Dashboard API (FIXED: Changed from auth:web to auth:sanctum for API consistency)
Route::middleware('auth:sanctum')->prefix('dashboard')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getUser']);
    Route::get('/stats', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getStats']);
    Route::get('/usage', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getUsageData']);
    Route::get('/languages', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getLanguagesData']);
    Route::get('/history', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getHistory']);
    Route::get('/projects', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getProjects']);
    Route::get('/subscription', [App\Http\Controllers\Api\V1\DashboardApiController::class, 'getSubscription']);
});

// User Integrations API (FIXED: Changed from auth:web to auth:sanctum for API consistency)
Route::middleware('auth:sanctum')->prefix('integrations')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\UserIntegrationController::class, 'index']);
    Route::get('/stats', [App\Http\Controllers\Api\UserIntegrationController::class, 'stats']);
    Route::get('/{platform}', [App\Http\Controllers\Api\UserIntegrationController::class, 'show']);
});

// Training Data API (for Deep Learning System)
Route::middleware('auth:sanctum')->prefix('v1/training-data')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'store']);
    Route::get('/{id}', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'show']);
    Route::put('/{id}', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'update']);
    Route::delete('/{id}', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'destroy']);
    Route::post('/{id}/approve', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'approve']);
    Route::post('/{id}/reject', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'reject']);
    Route::get('/export/dataset', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'exportDataset']);
    Route::get('/stats/overview', [App\Http\Controllers\Api\V1\TrainingDataController::class, 'stats']);
});

// Subscription Plans API
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/plans', [App\Http\Controllers\Api\V1\SubscriptionController::class, 'getPlans']);
    Route::get('/me', function(\Illuminate\Http\Request $request) {
        return response()->json(['data' => $request->user()]);
    });
});
