<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CulturalTranslate Admin</title>

    <!-- Tailwind via CDN (sufficient for admin panel) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite (components like dropdowns, modals, etc.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.css" integrity="sha512-jmNwN0xqugX8Vq8qE+R6zqF7d5l4p9jC0Gg1uG4F6Py0F2+zH4B7GbqYz1O6E7Zf4p6s4WkL3I4sY3Z5hz0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.1/flowbite.min.js" integrity="sha512-4K3w3bR8a9K5qXJ4/HSy7l+N/7q8q3Tg9v8H0c1rH7z9zM5lXg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Top bar -->
        <header class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16 border-b bg-white/80 backdrop-blur z-20">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 hover:bg-slate-100 focus:outline-none lg:hidden">
                    <!-- Icon -->
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10" />
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 via-sky-500 to-emerald-400 text-white font-semibold shadow-sm">
                        CT
                    </div>
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="text-sm font-semibold">CulturalTranslate</span>
                        <span class="text-xs text-slate-500">Admin Panel</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>System status: <span class="font-medium text-emerald-600">All services operational</span></span>
                </div>

                <div class="relative">
                    <button id="userMenuButton" data-dropdown-toggle="userMenu" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2 py-1.5 text-sm shadow-sm hover:bg-slate-50">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-tr from-sky-500 to-indigo-500 text-white text-xs font-semibold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'SA', 0, 2)) }}
                        </div>
                        <div class="hidden sm:flex flex-col text-left leading-tight">
                            <span class="text-xs font-medium">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                            <span class="text-[11px] text-slate-500">Super Admin</span>
                        </div>
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="userMenu" class="z-50 hidden w-52 divide-y divide-slate-100 rounded-xl border border-slate-100 bg-white shadow-lg">
                        <div class="px-3 py-2">
                            <p class="text-xs font-medium text-slate-900">{{ auth()->user()->name ?? 'Super Admin' }}</p>
                            <p class="text-[11px] text-slate-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                        </div>
                        <ul class="py-1 text-xs text-slate-700">
                            <li>
                                <button class="block w-full text-left px-3 py-2 hover:bg-slate-50">Profile & Settings</button>
                            </li>
                            <li>
                                <button class="block w-full text-left px-3 py-2 hover:bg-slate-50">Audit Log</button>
                            </li>
                        </ul>
                        <div class="py-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="flex w-full items-center justify-between px-3 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                    <span>Sign out</span>
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside id="sidebar" class="ct-sidebar hidden lg:flex lg:flex-col w-64 shrink-0 border-r bg-white">
                <div class="flex-1 overflow-y-auto">
                    @include('admin.partials.sidebar')
                </div>
                <div class="border-t px-4 py-3 text-[11px] text-slate-400">
                    CulturalTranslate Admin · v1.0
                </div>
            </aside>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 py-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Simple sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (toggle && sidebar) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
