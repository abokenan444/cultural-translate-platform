<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AdvancedAIAgentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIAgentController extends Controller
{
    protected AdvancedAIAgentService $aiAgent;

    public function __construct(AdvancedAIAgentService $aiAgent)
    {
        $this->aiAgent = $aiAgent;
    }

    /**
     * Process natural language request
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|min:3',
            'context' => 'sometimes|array',
        ]);

        $result = $this->aiAgent->processRequest(
            $validated['message'],
            $validated['context'] ?? []
        );

        return response()->json($result);
    }

    /**
     * Get system status
     * 
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        $status = $this->aiAgent->getSystemStatus();

        return response()->json([
            'success' => true,
            'status' => $status,
            'agent' => 'online',
            'capabilities' => [
                'feature_addition' => true,
                'code_modification' => true,
                'bug_fixing' => true,
                'project_management' => true,
                'analysis_reporting' => true,
            ],
        ]);
    }

    /**
     * Get conversation history
     * 
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        // TODO: Implement conversation history from database
        
        return response()->json([
            'success' => true,
            'history' => [],
        ]);
    }

    /**
     * Clear conversation history
     * 
     * @return JsonResponse
     */
    public function clearHistory(): JsonResponse
    {
        // TODO: Implement clear history
        
        return response()->json([
            'success' => true,
            'message' => 'تم مسح سجل المحادثات',
        ]);
    }
}
