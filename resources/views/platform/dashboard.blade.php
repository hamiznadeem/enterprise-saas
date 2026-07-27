@extends('platform.layouts.app')

@section('header', 'Platform Overview')

@section('content')
<!-- Top Welcome & System Status Bar -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fa-solid fa-circle text-[8px] text-emerald-600"></i> All Systems Operational
            </span>
            <span class="text-xs text-slate-400 font-medium">• {{ now()->format('l, d M Y') }}</span>
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome back, {{ Auth::guard('platform')->user()->name }}</h2>
        <p class="text-xs text-slate-500 mt-0.5">Real-time performance metrics and multi-tenant store control across your SaaS network.</p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('platform.tenants.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add Tenant Store</span>
        </a>
        <a href="{{ route('platform.invoices.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-indigo-600"></i>
            <span>Invoices</span>
        </a>
    </div>
</div>

<!-- ==================== 2-TIER PROMINENT KPI GRID (8 STANDALONE CARDS) ==================== -->

<!-- TIER 1: Revenue & Tenant Performance (4 Cards) -->
<div class="mb-5">
    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1.5">
        <i class="fa-solid fa-chart-line text-indigo-600"></i> Business & Revenue Performance
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Monthly Revenue (MRR) -->
        <div class="bg-indigo-600 rounded-xl p-5 text-white shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Monthly Revenue (MRR)</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-700 flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">${{ number_format($monthlyRevenue, 2) }}</div>
            <p class="text-[11px] text-indigo-100 font-medium mt-1">Paid subscriptions this month</p>
        </div>

        <!-- Card 2: Total Sales Volume -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Sales Volume</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-cash-register"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">${{ number_format($totalSales, 2) }}</div>
            <p class="text-[11px] text-emerald-600 font-bold mt-1">Cumulative platform sales</p>
        </div>

        <!-- Card 3: Active Paid Stores -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Paid Stores</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-store"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $activeTenants }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Out of {{ $totalTenants }} total registered stores</p>
        </div>

        <!-- Card 4: Free Trial Accounts -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Free Trial Accounts</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $trialTenants }}</div>
            <p class="text-[11px] text-amber-600 font-bold mt-1">Active 14-day trial stores</p>
        </div>

    </div>
</div>

<!-- TIER 2: Infrastructure & Growth Metrics (4 Cards) -->
<div class="mb-8">
    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1.5">
        <i class="fa-solid fa-server text-indigo-600"></i> Network Growth & Infrastructure
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 5: New Registrations -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">New Signups</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $newRegistrations }}</div>
            <p class="text-[11px] text-purple-600 font-bold mt-1">New store signups this month</p>
        </div>

        <!-- Card 6: Total Network Users -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Platform Users</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalUsers }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Store owners, cashiers & managers</p>
        </div>

        <!-- Card 7: Active Live Sessions -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Live Sessions</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-network-wired"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $activeSessions }}</div>
            <p class="text-[11px] text-slate-600 font-bold mt-1">Active users in last 5 minutes</p>
        </div>

        <!-- Card 8: Database Storage -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Database Storage</span>
                <span class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-database"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalStorage }} <span class="text-sm font-semibold text-slate-500">MB</span></div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Total MySQL database usage</p>
        </div>

    </div>
</div>

<!-- Critical Action Alert (Expired Tenants Banner if > 0) -->
@if($expiredTenants > 0)
<div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-lg bg-red-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-lock text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-extrabold text-red-900">Attention Required: {{ $expiredTenants }} Tenant Account(s) Expired</h3>
            <p class="text-xs text-red-700 mt-0.5">These store accounts are currently locked out. Review subscription renewals or reactivate access.</p>
        </div>
    </div>
    <a href="{{ route('platform.tenants.index') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shrink-0">
        Manage Expired Accounts &rarr;
    </a>
</div>
@endif

<!-- 2-Column Operational Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- LEFT 7 COLS: Recent Activity Log Timeline -->
    <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Recent Platform Activity</h3>
                    <p class="text-[11px] text-slate-400">Live audit log stream of admin actions</p>
                </div>
            </div>
            <a href="{{ route('platform.audit-logs.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View All Logs &rarr;</a>
        </div>

        <div class="space-y-3">
            @forelse($recentActivities as $log)
            <div class="flex items-start gap-3.5 p-3 rounded-lg bg-slate-50 border border-slate-100 hover:bg-white hover:border-slate-200 transition">
                <div class="w-7 h-7 rounded-md bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                    {{ strtoupper(substr($log->admin->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-slate-900 truncate">{{ $log->admin->name ?? 'System' }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-slate-600 mt-0.5 font-medium leading-relaxed">
                        {{ $log->description }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <i class="fa-solid fa-clipboard-list text-3xl mb-2 text-slate-300"></i>
                <p class="text-xs font-medium">No recent audit log activities recorded yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- RIGHT 5 COLS: Quick Tools & Tenant Breakdown -->
    <div class="lg:col-span-5 space-y-6">
        
        <!-- Tenant Status Breakdown Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                <span>Tenant Network Health</span>
                <span class="text-xs font-normal text-slate-500">{{ $totalTenants }} Total Stores</span>
            </h3>

            <div class="space-y-2.5 text-xs">
                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                    <span class="flex items-center gap-2 font-semibold text-slate-800">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Active Paid Stores
                    </span>
                    <span class="font-extrabold text-slate-900 text-sm">{{ $activeTenants }}</span>
                </div>

                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                    <span class="flex items-center gap-2 font-semibold text-slate-800">
                        <i class="fa-solid fa-hourglass-half text-amber-600"></i> Free Trial Accounts
                    </span>
                    <span class="font-extrabold text-slate-900 text-sm">{{ $trialTenants }}</span>
                </div>

                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                    <span class="flex items-center gap-2 font-semibold text-slate-800">
                        <i class="fa-solid fa-lock text-red-600"></i> Expired Accounts
                    </span>
                    <span class="font-extrabold text-slate-900 text-sm">{{ $expiredTenants }}</span>
                </div>

                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200">
                    <span class="flex items-center gap-2 font-semibold text-slate-800">
                        <i class="fa-solid fa-ban text-slate-500"></i> Suspended Accounts
                    </span>
                    <span class="font-extrabold text-slate-900 text-sm">{{ $suspendedTenants }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Platform Navigation Cards -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Quick Navigation Tools</h3>
            
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('platform.tenants.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-center group">
                    <div class="w-8 h-8 mx-auto rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs mb-2">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Tenants</span>
                </a>

                <a href="{{ route('platform.plans.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-center group">
                    <div class="w-8 h-8 mx-auto rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs mb-2">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Subscription Plans</span>
                </a>

                <a href="{{ route('platform.invoices.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-center group">
                    <div class="w-8 h-8 mx-auto rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs mb-2">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Billing Invoices</span>
                </a>

                <a href="{{ route('platform.audit-logs.index') }}" class="p-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition text-center group">
                    <div class="w-8 h-8 mx-auto rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs mb-2">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 block">Audit Logs</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection