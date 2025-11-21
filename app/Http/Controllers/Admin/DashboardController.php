<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Translation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات Dashboard
        $stats = [
            'active_companies'      => Company::where('status', 'active')->count(),
            'total_users'           => User::count(),
            'translations_24h'      => Translation::where('created_at', '>=', now()->subDay())->count(),
            'free_translations'     => 300,  // قيمة افتراضية
            'pro_translations'      => 900,  // قيمة افتراضية
            'business_translations' => 640,  // قيمة افتراضية
        ];

        // آخر النشاطات
        $recentActivity = [];
        
        if (class_exists(ActivityLog::class)) {
            $recentActivity = ActivityLog::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($log) {
                    return [
                        'actor'       => $log->actor ?? 'System',
                        'description' => $log->description ?? 'Activity logged',
                        'time'        => $log->created_at->diffForHumans(),
                    ];
                })
                ->toArray();
        }
        
        // إذا لم تكن هناك نشاطات، أضف نشاط افتراضي
        if (empty($recentActivity)) {
            $recentActivity = [
                [
                    'actor'       => 'System',
                    'description' => 'Admin panel initialized successfully.',
                    'time'        => 'just now',
                ],
            ];
        }

        // إحصائيات API
        $apiUsage = [
            'openai'  => Translation::where('created_at', '>=', now()->subDay())->count() . ' calls',
            'others'  => '0 calls',
            'latency' => '0.5s avg',
        ];

        return view('admin.dashboard', compact('stats', 'recentActivity', 'apiUsage'));
    }
}
