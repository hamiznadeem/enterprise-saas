@php
    $currentPath = request()->path();
    $isActive = function($path) use ($currentPath) {
        return $currentPath === $path || str_starts_with($currentPath, $path . '/');
    };
    $isGroupActive = function($paths) use ($isActive) {
        foreach($paths as $path) {
            if($isActive($path)) return true;
        }
        return false;
    };
@endphp

<style>
    /* Main Layout Margin Control */
    body:not(.sidebar-expanded) #mainContent { margin-left: 4.5rem; }
    body.sidebar-expanded #mainContent { margin-left: 16rem; }
    @media (max-width: 1023px) {
        body #mainContent { margin-left: 0; }
    }

    /* Sidebar Base */
    .sidebar-slim {
        width: 4.5rem; overflow: visible; transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    body.sidebar-expanded .sidebar-slim {
        width: 16rem; overflow-y: auto; overflow-x: hidden;
    }

    /* Text Labels (Desktop par slim mein chupao, expanded mein dikhao) */
    .desktop-hide { display: none; }
    body.sidebar-expanded .desktop-hide { display: block !important; }
    
    /* Tooltips (Sirf Slim mode mein) */
    .tip { position: relative; }
    .tip::after {
        content: attr(data-tip);
        position: absolute; left: 110%; top: 50%; transform: translateY(-50%);
        background: #1e293b; color: #fff; padding: 6px 12px; border-radius: 6px;
        font-size: 12px; font-weight: 500; white-space: nowrap; z-index: 1000; pointer-events: none;
        opacity: 0; transition: opacity 0.15s ease; margin-left: 10px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.3);
    }
    body:not(.sidebar-expanded) .tip:hover::after { opacity: 1; }
    body.sidebar-expanded .tip::after { display: none; }

    /* Flyout Menu (Sirf Slim mode mein) */
    .nav-group { position: relative; }
    .flyout-menu {
        position: absolute; left: calc(100% + 0.75rem); top: 0; width: 220px;
        background: #1e293b; border: 1px solid #334155; border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.4); padding: 0.5rem;
        opacity: 0; visibility: hidden; transform: translateX(-10px) scale(0.95);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1000;
    }
    body:not(.sidebar-expanded) .nav-group:hover .flyout-menu {
        opacity: 1; visibility: visible; transform: translateX(0) scale(1);
    }
    body.sidebar-expanded .flyout-menu { display: none; }

    .flyout-link {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.625rem 0.75rem; border-radius: 0.5rem;
        color: #cbd5e1; font-size: 0.875rem; font-weight: 500;
        transition: all 0.15s; text-decoration: none;
    }
    .flyout-link:hover { background: #334155; color: #fff; }
    .flyout-link.active { background: #4f46e5; color: #fff; box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.4); }

    /* Mobile: Always full width */
    @media (max-width: 1023px) {
        .sidebar-slim { width: 16rem; overflow-y: auto; }
        .mobile-hide { display: none; }
    }

    /* Toggle Button Icon Spin */
    #sidebarToggleIcon { transition: transform 0.3s ease; }
    body.sidebar-expanded #sidebarToggleIcon { transform: rotate(180deg); }
</style>

<aside id="sidebar" class="sidebar-slim fixed top-0 left-0 z-50 h-screen bg-slate-900 flex flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full">

    <!-- Brand -->
    <div class="flex items-center gap-3 px-3 h-16 border-b border-slate-800 shrink-0">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0">
            <i class="fa-solid fa-syringe text-white text-sm"></i>
        </div>
        <div class="desktop-hide">
            <span class="text-white font-bold text-base tracking-tight">ClinicPOS</span>
            <p class="text-slate-500 text-[10px] font-medium uppercase tracking-wider">Suite</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2.5 py-4 space-y-4">
        
        <!-- Main -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Main</p>
            <div class="space-y-1">
                <a href="{{ route('tenant.dashboard') }}" class="tip flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Dashboard">
                    <i class="fa-solid fa-table-cells-large w-5 text-center shrink-0"></i>
                    <span class="desktop-hide">Dashboard</span>
                </a>
                <a href="{{ route('patients.index') }}" class="tip flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive('patients') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Patients">
                    <i class="fa-solid fa-user-group w-5 text-center shrink-0"></i>
                    <span class="desktop-hide">Patients</span>
                </a>
            </div>
        </div>

        <!-- Queue -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Queue</p>
            
            <!-- DESKTOP: Flyout Group -->
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['tokens', 'doctor']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Queue Mgmt">
                    <i class="fa-solid fa-bars-staggered w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Queue Management</p>
                    <a href="{{ route('tokens.create') }}" class="flyout-link {{ $isActive('tokens/create') ? 'active' : '' }}"><i class="fa-solid fa-plus w-4 text-center"></i>New Token</a>
                    <a href="{{ route('tokens.index') }}" class="flyout-link {{ ($isActive('tokens') && !$isActive('tokens/create')) ? 'active' : '' }}"><i class="fa-solid fa-list w-4 text-center"></i>All Tokens</a>
                    <a href="{{ route('tokens.doctor.dashboard') }}" class="flyout-link {{ $isActive('doctor') ? 'active' : '' }}"><i class="fa-solid fa-user-doctor w-4 text-center"></i>Doctor View</a>
                </div>
            </div>

            <!-- EXPANDED MODE: Simple List -->
            <div class="space-y-1 desktop-hide">
                <a href="{{ route('tokens.create') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('tokens/create') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-plus w-5 text-center shrink-0"></i>New Token
                </a>
                <a href="{{ route('tokens.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ ($isActive('tokens') && !$isActive('tokens/create')) ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-list w-5 text-center shrink-0"></i>All Tokens
                </a>
                <a href="{{ route('tokens.doctor.dashboard') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('doctor') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-user-doctor w-5 text-center shrink-0"></i>Doctor View
                </a>
            </div>
        </div>

        <!-- Pharmacy -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pharmacy</p>
            @php $lowStockBadge = \App\Models\Medicine::where('stock_quantity', '<=', 10)->count() > 0 @endphp
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['pos', 'pharmacy']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Pharmacy">
                    <i class="fa-solid fa-pills w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pharmacy</p>
                    <a href="{{ route('pos.index') }}" class="flyout-link {{ $isActive('pos') ? 'active' : '' }}"><i class="fa-solid fa-cash-register w-4 text-center"></i>POS (Point of Sale)</a>
                    <a href="{{ route('pharmacy.dashboard') }}" class="flyout-link {{ $isActive('pharmacy') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i>Inventory Alerts</a>
                </div>
            </div>

            <div class="space-y-1 desktop-hide">
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('pos') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-cash-register w-5 text-center shrink-0"></i>POS
                </a>
                <a href="{{ route('pharmacy.dashboard') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('pharmacy') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-triangle-exclamation w-5 text-center shrink-0"></i>Inventory Alerts
                </a>
            </div>
        </div>

        <!-- Management (Grouped Flyout) -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Management</p>
            
            <!-- DESKTOP: Flyout Group -->
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['doctors', 'staff']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Management">
                    <i class="fa-solid fa-user-gear w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</p>
                    <a href="{{ route('doctors.index') }}" class="flyout-link {{ $isActive('doctors') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-doctor w-4 text-center"></i>
                        Doctors
                    </a>
                    <a href="{{ route('staff.index') }}" class="flyout-link {{ $isActive('staff') ? 'active' : '' }}">
                        <i class="fa-solid fa-users w-4 text-center"></i>
                        Staff
                    </a>
                </div>
            </div>

            <!-- EXPANDED MODE: Normal List -->
            <div class="space-y-1 desktop-hide">
                <a href="{{ route('doctors.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('doctors') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-user-doctor w-5 text-center shrink-0"></i>
                    Doctors
                </a>
                <a href="{{ route('staff.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('staff') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-users w-5 text-center shrink-0"></i>
                    Staff
                </a>
            </div>
        </div>

   <!-- ═══ SYSTEM ═══ -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">System</p>
            
            <!-- DESKTOP: Flyout -->
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['activity-logs', 'sessions', 'change-password']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="System">
                    <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
                    <a href="{{ route('tenant.activity-logs') }}" class="flyout-link {{ $isActive('activity-logs') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>Activity Logs</a>
                    <a href="{{ route('tenant.sessions.index') }}" class="flyout-link {{ $isActive('sessions') ? 'active' : '' }}"><i class="fa-solid fa-desktop w-4 text-center"></i>Active Sessions</a>
                    <a href="{{ route('password.change') }}" class="flyout-link {{ $isActive('change-password') ? 'active' : '' }}"><i class="fa-solid fa-key w-4 text-center"></i>Change Password</a>
                    <a href="{{ route('two-factor.index') }}" class="flyout-link {{ $isActive('two-factor') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved w-4 text-center"></i>Two-Factor Auth</a>
                </div>
            </div>

            <!-- EXPANDED: Normal list -->
            <div class="space-y-1 desktop-hide">
                <a href="{{ route('tenant.activity-logs') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('activity-logs') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center shrink-0"></i>Activity Logs
                </a>
                <a href="{{ route('tenant.sessions.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('sessions') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-desktop w-5 text-center shrink-0"></i>Active Sessions
                </a>
                <a href="{{ route('password.change') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('change-password') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-key w-5 text-center shrink-0"></i>Change Password
                </a>
                <a href="{{ route('two-factor.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('change-password') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center shrink-0"></i>Two-Factor Auth
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom Area: Trial Info + Toggle Button -->
    <div class="px-2.5 py-3 border-t border-slate-800 shrink-0">

                <!-- Branch Switcher -->
        @php
            $userBranches = auth()->user()->branches()->where('is_active', true)->get();
            $currentBranchId = session('current_branch_id');
            $currentBranch = $userBranches->firstWhere('id', $currentBranchId) ?? $userBranches->firstWhere('is_default', true) ?? $userBranches->first();
        @endphp

        @if($userBranches->count() > 1)
        <div class="desktop-hide px-2 mb-2">
            <form method="POST" action="{{ route('branch.switch') }}">
                @csrf
                <select name="branch_id" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none cursor-pointer appearance-none"
                    style="background-image: url('data:image/svg+xml;utf8,<svg fill=\"white\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" clip-rule=\"evenodd\"/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1rem;">
                    @foreach($userBranches as $branch)
                    <option value="{{ $branch->id }}" {{ ($currentBranch && $branch->id === $currentBranch->id) ? 'selected' : '' }}>
                        {{ $branch->branch_name ?? 'Branch ' . $branch->id }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Slim mode: tooltip button -->
        <div class="hidden desktop-hide lg:block mb-2">
            <div class="nav-group">
                <button class="tip w-full flex items-center justify-center px-2.5 py-2 rounded-lg text-sm text-slate-400 hover:bg-slate-800 hover:text-white transition" data-tip="{{ $currentBranch ? ($currentBranch->branch_name ?? 'Branch') : 'No Branch' }}">
                    <i class="fa-solid fa-building w-5 text-center"></i>
                </button>
                <div class="flyout-menu" style="width: 220px;">
                    <p class="px-3 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Switch Branch</p>
                    <form method="POST" action="{{ route('branch.switch') }}">
                        @csrf
                        @foreach($userBranches as $branch)
                        <button type="submit" name="branch_id" value="{{ $branch->id }}"
                            class="w-full text-left flyout-link {{ ($currentBranch && $branch->id === $currentBranch->id) ? 'active' : '' }}">
                            <i class="fa-solid fa-building w-4 text-center"></i>
                            {{ $branch->branch_name ?? 'Branch ' . $branch->id }}
                            @if($currentBranch && $branch->id === $currentBranch->id)
                                <i class="fa-solid fa-check ml-auto text-[10px]"></i>
                            @endif
                        </button>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
        @endif

        @php 
            $tenant = app('currentTenant'); 
            $remainingDays = $tenant ? (int) now()->diffInDays($tenant->trial_ends_at) : 0;
            $isUrgent = $remainingDays <= 5;
        @endphp
        @if($tenant && $tenant->trial_ends_at && $tenant->trial_ends_at->isFuture())
            <div class="desktop-hide flex items-center gap-2 px-2 py-2 rounded-lg {{ $isUrgent ? 'bg-red-900/30' : 'bg-slate-800/60' }} mb-2">
                <i class="fa-solid fa-clock w-4 text-center shrink-0 {{ $isUrgent ? 'text-red-400' : 'text-amber-400' }}"></i>
                <div>
                    <p class="text-[11px] text-slate-400">Trial expires</p>
                    <p class="text-xs font-bold {{ $isUrgent ? 'text-red-400' : 'text-amber-400' }}">{{ $remainingDays }} days left</p>
                </div>
            </div>
        @endif

        @php
    $pwdExpiry = \App\Services\PasswordExpiryService::shouldWarn(auth()->user()) ? \App\Services\PasswordExpiryService::getDaysRemaining(auth()->user()) : null;
@endphp

@if($pwdExpiry !== null)
<div class="sticky top-16 z-20 mx-4 sm:mx-6 mt-4 p-3.5 rounded-xl border bg-amber-50 border-amber-200 flex items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-key text-amber-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-amber-800">Password expires in {{ $pwdExpiry }} days</p>
            <p class="text-xs text-amber-600">Update your password to avoid being locked out.</p>
        </div>
    </div>
    <a href="{{ route('password.change') }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-white text-sm font-medium rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 transition shadow-sm">
        Update Now
    </a>
</div>
@endif

        <!-- TOGGLE BUTTON -->
        <button onclick="toggleSidebar()" class="hidden lg:flex w-full items-center justify-center gap-2 px-2.5 py-2 rounded-lg text-xs font-semibold text-slate-500 hover:bg-slate-800 hover:text-white transition">
            <i id="sidebarToggleIcon" class="fa-solid fa-angles-right w-4 text-center"></i>
            <span class="desktop-hide">Collapse</span>
        </button>
    </div>
</aside>