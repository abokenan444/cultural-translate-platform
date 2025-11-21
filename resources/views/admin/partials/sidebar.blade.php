<nav class="px-3 py-4 space-y-6">
    <!-- Section: Overview -->
    <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400 mb-2">Overview</p>
        <ul class="space-y-1 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900 font-medium' : '' }}">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-gradient-to-tr from-indigo-500 to-sky-500 text-[11px] text-white">◎</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-900 text-[11px] text-white">📊</span>
                    <span>Analytics</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Section: Platform -->
    <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400 mb-2">Platform</p>
        <ul class="space-y-1 text-sm">
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-emerald-500 text-[11px] text-white">🏢</span>
                    <span>Companies</span>
                </button>
            </li>
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-sky-500 text-[11px] text-white">👤</span>
                    <span>Users</span>
                </button>
            </li>
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-amber-500 text-[11px] text-white">$</span>
                    <span>Plans & Billing</span>
                </button>
            </li>
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-800 text-[11px] text-white">🔑</span>
                    <span>API Keys</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Section: System -->
    <div>
        <p class="px-3 text-[11px] font-semibold uppercase tracking-wide text-slate-400 mb-2">System</p>
        <ul class="space-y-1 text-sm">
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-slate-700 text-[11px] text-white">⚙️</span>
                    <span>Settings</span>
                </button>
            </li>
            <li>
                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-rose-500 text-[11px] text-white">🧾</span>
                    <span>System Logs</span>
                </button>
            </li>
        </ul>
    </div>
</nav>
