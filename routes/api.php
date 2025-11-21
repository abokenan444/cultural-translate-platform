use App\Http\Controllers\AIAgentController;

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
