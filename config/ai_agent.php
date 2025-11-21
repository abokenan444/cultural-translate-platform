<?php

return [

    // عنوان الـ API الخاص بالـ Agent
    'base_url' => env('AI_AGENT_URL', 'http://127.0.0.1:5050'),

    // مسار health check
    'health_endpoint' => '/health',

    // مسار health داخل /api
    'api_health_endpoint' => '/api/health',

    // مسار تنفيذ الأوامر
    'run_command_endpoint' => '/run-command',

    // مسار نشر التحديثات
    'deploy_endpoint' => '/deploy',

    // مسار تحسين Laravel
    'optimize_endpoint' => '/optimize',

    // التوكن السري بين Laravel والـ Agent
    'auth_token' => env('AI_AGENT_TOKEN'),

  // نقطة الشات الجديدة
    'chat_endpoint' => '/chat',

    // مهلة الطلبات
    'timeout' => 30,

];
