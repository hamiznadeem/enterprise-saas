@extends('platform.layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::guard('platform')->user()->name }}</h2>
    <p class="text-sm text-gray-500 mt-1">Here's what's happening across your SaaS platform right now.</p>
</div>

<!-- Top KPI Cards (3x3 Grid) -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
    
    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class="fa-solid fa-building text-indigo-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Total</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalTenants }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">Registered Tenants</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $activeTenants }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">Active Tenants</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-amber-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Trial</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $trialTenants }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">In Trial Phase</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-tags text-purple-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Plans</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalPlans }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">Configured Plans</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Users</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalUsers }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">Total Users</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-emerald-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Revenue</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">This Month</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl-pink-100 flex items-center justify-center">
                <i class="fa-solid fa-user-plus text-pink-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full">New</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $newRegistrations }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">This Month</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl-cyan-100 flex items-center justify-center">
                <i class="fa-solid fa-server text-cyan-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full">System</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $activeSessions }}</h3>
        <p class="text-sm text-gray-500 mt-0.5">Active Sessions (5 min)</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl-orange-100 flex items-center justify-center">
                <i class="fa-solid fa-hard-drive text-orange-600 text-lg"></i>
            </div>
            <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Storage</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-900">{{ $totalStorage }} MB</h3>
        <p class="text-sm text-gray-500 mt-0.5">Total Storage Used</p>
    </div>
</div>

<!-- Critical Section: Expired & Suspended -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    
    <!-- EXPIRED TENANTS (Red Alert) -->
    <div class="bg-white rounded-xl border-2 border-red-200 p-5 relative overflow-hidden">
        @if($expiredTenants > 0)
        <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">ACTION REQUIRED</div>
        @endif
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                <i class="fa-solid fa-lock text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-red-600">{{ $expiredTenants }}</h3>
                <p class="text-sm text-red-500 mt-0.5">Expired & Locked Out</p>
            </div>
        </div>
        <a href="{{ route('platform.tenants.index') }}" class="block text-center text-sm text-red-600 hover:text-red-800 font-medium mt-2">
            View Locked Tenants <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>

    <!-- SUSPENDED TENANTS -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                <i class="fa-solid fa-ban text-gray-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900">{{ $suspendedTenants }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">Suspended by Admin</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('platform.plans.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 transition group">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition">
                    <i class="fa-solid fa-tags text-indigo-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Manage Plans</span>
            </a>
            <a href="{{ route('platform.tenants.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-purple-50 hover:border-purple-200 transition group">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition">
                    <i class="fa-solid fa-user-plus text-purple-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Add Tenant</span>
            </a>
            <a href="{{ route('platform.invoices.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 transition group">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Invoices</span>
            </a>
            <a href="{{ route('platform.audit-logs.index') }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-amber-50 hover:border-amber-200 transition group">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition">
                    <i class="fa-solid fa-clipboard-list text-amber-600"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">Audit Logs</span>
            </a>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl p-6 text-white">
        <div class="flex items-center gap-3 mb-3">
            <i class="fa-solid fa-rocket text-2xl text-purple-200"></i>
            <h3 class="text-base font-bold">System Setup Progress</h3>
        </div>
        <p class="text-purple-100 text-sm mb-4">Complete these steps to fully launch your platform.</p>
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
                <span>Platform Authentication & Security</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
                <span>Plan Management</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
                <span>Tenant Management & Modules</span>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-300"></i>
                <span>Expiry System & Invoicing</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-purple-200">
                <i class="fa-regular fa-circle"></i>
                <span>Global Settings</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Timeline -->
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
    <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
        <i class="fa-solid fa-clock-rotate-left text-purple-600"></i> Recent Platform Activity
    </h3>
    <div class="space-y-4 relative pl-6 border-l-2 border-purple-100 ml-4">
        @forelse($recentActivities as $log)
        <div class="relative">
            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-purple-400 border-2 border-white"></div>
            <div class="ml-4 pb-4">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-purple-600">{{ $log->created_at->format('M d, h:i A') }}</span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-sm font-medium text-gray-900">{{ $log->admin->name ?? 'System' }}</span>
                </div>
                <p class="text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg mt-1 inline-block">
                    {{ $log->description }}
                </p>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-4">No recent activities yet.</p>
        @endforelse
    </div>
</div>
@endsection