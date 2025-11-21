@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold text-slate-900">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Overview of CulturalTranslate usage, companies, and system status.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                    <span>View Analytics</span>
                </button>
                <button class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-400 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:from-indigo-600 hover:via-sky-600 hover:to-emerald-500">
                    <span class="text-base leading-none">＋</span>
                    <span>New Company</span>
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-slate-500">Active Companies</p>
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-50 text-xs">🏢</span>
                </div>
                <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $stats['active_companies'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-emerald-600">All with valid subscriptions</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-slate-500">Total Users</p>
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-sky-50 text-xs">👥</span>
                </div>
                <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $stats['total_users'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-sky-600">Including admins, managers, and translators</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-slate-500">Translations (24h)</p>
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-50 text-xs">⚡</span>
                </div>
                <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $stats['translations_24h'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-indigo-600">Across all companies and projects</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-slate-500">System Health</p>
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-50 text-xs">✅</span>
                </div>
                <p class="mt-3 text-2xl font-semibold text-slate-900">99.98%</p>
                <p class="mt-1 text-[11px] text-emerald-600">Uptime last 30 days</p>
            </div>
        </div>

        <!-- Main grid -->
        <div class="grid gap-4 lg:grid-cols-3">
            <!-- Left: charts / recent activity -->
            <div class="space-y-4 lg:col-span-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-slate-600">Translations by plan</p>
                        <span class="text-[11px] text-slate-400">Last 7 days</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-xs">
                        <div class="space-y-1">
                            <p class="text-slate-500">Free</p>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-slate-300" style="width: 35%;"></div>
                            </div>
                            <p class="text-[11px] text-slate-400">{{ $stats['free_translations'] ?? 0 }} translations</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-slate-500">Pro</p>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-sky-400" style="width: 65%;"></div>
                            </div>
                            <p class="text-[11px] text-slate-400">{{ $stats['pro_translations'] ?? 0 }} translations</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-slate-500">Business</p>
                            <div class="h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-emerald-500" style="width: 80%;"></div>
                            </div>
                            <p class="text-[11px] text-slate-400">{{ $stats['business_translations'] ?? 0 }} translations</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-medium text-slate-600">Recent activity</p>
                        <span class="text-[11px] text-slate-400">View all</span>
                    </div>
                    <div class="space-y-3 text-xs">
                        @forelse($recentActivity ?? [] as $event)
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 h-6 w-6 flex items-center justify-center rounded-full bg-slate-900 text-[11px] text-white">
                                    {{ strtoupper(substr($event['actor'] ?? 'CT', 0, 2)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-slate-700">
                                        <span class="font-medium">{{ $event['actor'] ?? 'System' }}</span>
                                        <span class="text-slate-500">{{ $event['description'] ?? 'performed an action.' }}</span>
                                    </p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">{{ $event['time'] ?? 'Just now' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400">No recent activity yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right: system cards -->
            <div class="space-y-4">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs font-medium text-slate-600 mb-2">API usage today</p>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center justify-between">
                            <span class="text-slate-500">OpenAI</span>
                            <span class="font-medium text-slate-800">{{ $apiUsage['openai'] ?? '0' }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-slate-500">DeepL / Others</span>
                            <span class="font-medium text-slate-800">{{ $apiUsage['others'] ?? '0' }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-slate-500">Average latency</span>
                            <span class="font-medium text-slate-800">{{ $apiUsage['latency'] ?? '0.0s' }}</span>
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                    <p class="text-xs font-medium text-slate-600 mb-2">Quick actions</p>
                    <div class="flex flex-col gap-2">
                        <button class="inline-flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 w-full">
                            <span>Platform settings</span>
                            <span class="text-slate-400">⚙️</span>
                        </button>
                        <button class="inline-flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 w-full">
                            <span>Manage API keys</span>
                            <span class="text-slate-400">🔑</span>
                        </button>
                        <button class="inline-flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 w-full">
                            <span>Support & incidents</span>
                            <span class="text-slate-400">🧩</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
