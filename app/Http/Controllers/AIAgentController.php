<?php

namespace App\Http\Controllers;

use App\Services\AIAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIAgentController extends Controller
{
    public function __construct(
        protected AIAgentService $agent
    ) {
        // يمكنك إضافة middlewares إضافية هنا إذا رغبت
        // $this->middleware('auth:sanctum');
    }

    public function health(): JsonResponse
    {
        $result = $this->agent->health();

        return response()->json($result, $result['status'] ?? 200);
    }

    public function apiHealth(): JsonResponse
    {
        $result = $this->agent->apiHealth();

        return response()->json($result, $result['status'] ?? 200);
    }

    public function runCommand(Request $request): JsonResponse
    {
        $request->validate([
            'command' => ['required', 'string', 'max:2000'],
        ]);

        $result = $this->agent->runCommand($request->string('command'));

        return response()->json($result, $result['status'] ?? 200);
    }

    public function deploy(Request $request): JsonResponse
    {
        $request->validate([
            'branch' => ['nullable', 'string', 'max:255'],
        ]);

        $result = $this->agent->deploy($request->input('branch'));

        return response()->json($result, $result['status'] ?? 200);
    }

    public function optimize(): JsonResponse
    {
        $result = $this->agent->optimize();

        return response()->json($result, $result['status'] ?? 200);
    }
}
