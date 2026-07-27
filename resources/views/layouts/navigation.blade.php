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

    /* Text Labels & Visibility Controls */
    .desktop-hide { display: none; }
    body.sidebar-expanded .desktop-hide { display: block !important; }
    body.sidebar-expanded .mobile-hide { display: none !important; }

    /* Tooltips (Sirf Slim mode mein) */
    .tip { position: relative; }
    .tip::after {
        content: attr(data-tip);
        position: absolute; left: 110%; top: 50%; transform: translateY(-50%);
        background: #0f172a; color: #fff; padding: 6px 12px; border-radius: 6px;
        font-size: 12px; font-weight: 500; white-space: nowrap; z-index: 1000; pointer-events: none;
        opacity: 0; transition: opacity 0.15s ease; margin-left: 10px;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.3); border: 1px solid #334155;
    }
    body:not(.sidebar-expanded) .tip:hover::after { opacity: 1; }
    body.sidebar-expanded .tip::after { display: none; }

    /* Flyout Menu (Sirf Slim mode mein) */
    .nav-group { position: relative; }
    .flyout-menu {
        position: absolute; left: calc(100% + 0.75rem); top: 0; width: 220px;
        background: #0f172a; border: 1px solid #334155; border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.4); padding: 0.5rem;
        opacity: 0; visibility: hidden; transform: translateX(-10px) scale(0.95);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); z-index: 1000;
    }
    body:not(.sidebar-expanded) .nav-group:hover .flyout-menu {
        opacity: 1; visibility: visible; transform: translateX(0) scale(1);
    }
    body.sidebar-expanded .flyout-menu { display: none !important; }

    .flyout-link {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.625rem 0.75rem; border-radius: 0.5rem;
        color: #cbd5e1; font-size: 0.875rem; font-weight: 500;
        transition: all 0.15s; text-decoration: none;
    }
    .flyout-link:hover { background: #1e293b; color: #fff; }
    .flyout-link.active { background: #4f46e5; color: #fff; box-shadow: 0 4px 6px -1px rgb(79 70 229 / 0.4); }

    /* Mobile: Always full width */
    @media (max-width: 1023px) {
        .sidebar-slim { width: 16rem; overflow-y: auto; }
        .mobile-hide { display: none !important; }
        .desktop-hide { display: block !important; }
    }

    /* Toggle Button Icon Spin */
    #sidebarToggleIcon { transition: transform 0.3s ease; }
    body.sidebar-expanded #sidebarToggleIcon { transform: rotate(180deg); }
</style>

<aside id="sidebar" class="sidebar-slim fixed top-0 left-0 z-50 h-screen bg-slate-900 flex flex-col transition-transform duration-300 lg:translate-x-0 -translate-x-full border-r border-slate-800">

    <!-- Brand Header -->
    <div class="flex items-center gap-3 px-3.5 h-16 border-b border-slate-800 shrink-0">
        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30 shrink-0">
            <i class="fa-solid fa-bolt text-white text-sm"></i>
        </div>
        <div class="desktop-hide">
            <span class="text-white font-extrabold text-base tracking-tight">Enterprise POS</span>
            <p class="text-slate-400 text-[10px] font-semibold uppercase tracking-wider">Suite</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-2.5 py-4 space-y-4">
        
        <!-- Dashboard Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dashboard</p>
            
            <!-- SLIM MODE: Flyout Group -->
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ ($isActive('dashboard') || $isActive('tenant/clinic-dashboard')) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Dashboards">
                    <i class="fa-solid fa-table-cells-large w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Dashboards</p>
                    <a href="{{ route('tenant.dashboard') }}" class="flyout-link {{ ($isActive('dashboard') || $currentPath === 'tenant/dashboard') && !$isActive('tenant/clinic-dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line w-4 text-center"></i>
                        POS Dashboard
                    </a>
                    <a href="{{ route('tenant.clinic-dashboard') }}" class="flyout-link {{ $isActive('tenant/clinic-dashboard') || $currentPath === 'tenant/clinic-dashboard' ? 'active' : '' }}">
                        <i class="fa-solid fa-hospital-user w-4 text-center"></i>
                        Clinic Dashboard
                    </a>
                </div>
            </div>

            <!-- EXPANDED MODE: Simple List -->
            <div class="space-y-1 desktop-hide">
                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ ($isActive('dashboard') || $currentPath === 'tenant/dashboard') && !$isActive('tenant/clinic-dashboard') ? '!bg-indigo-600 !text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center shrink-0"></i>
                    POS Dashboard
                </a>
                <a href="{{ route('tenant.clinic-dashboard') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive('tenant/clinic-dashboard') || $currentPath === 'tenant/clinic-dashboard' ? '!bg-indigo-600 !text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-hospital-user w-5 text-center shrink-0"></i>
                    Clinic Dashboard
                </a>
            </div>
        </div>

        <!-- Patients Link -->
        <div>
            <a href="{{ route('patients.index') }}" class="tip flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isActive('patients') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Patients">
                <i class="fa-solid fa-user-group w-5 text-center shrink-0"></i>
                <span class="desktop-hide">Patients</span>
            </a>
        </div>

        <!-- Queue Management Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Queue</p>
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['tokens', 'doctor']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Queue Mgmt">
                    <i class="fa-solid fa-bars-staggered w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Queue Management</p>
                    <a href="{{ route('tokens.create') }}" class="flyout-link {{ $isActive('tokens/create') ? 'active' : '' }}"><i class="fa-solid fa-plus w-4 text-center"></i>New Token</a>
                    <a href="{{ route('tokens.index') }}" class="flyout-link {{ ($isActive('tokens') && !$isActive('tokens/create')) ? 'active' : '' }}"><i class="fa-solid fa-list w-4 text-center"></i>All Tokens</a>
                    <a href="{{ route('tokens.doctor.dashboard') }}" class="flyout-link {{ $isActive('doctor') ? 'active' : '' }}"><i class="fa-solid fa-user-doctor w-4 text-center"></i>Doctor View</a>
                </div>
            </div>

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

        <!-- Pharmacy & POS Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pharmacy & POS</p>
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['pos', 'pharmacy']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Pharmacy">
                    <i class="fa-solid fa-pills w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Pharmacy & POS</p>
                    <a href="{{ route('pos.index') }}" class="flyout-link {{ $isActive('pos') ? 'active' : '' }}"><i class="fa-solid fa-cash-register w-4 text-center"></i>POS Terminal</a>
                    <a href="{{ route('pharmacy.dashboard') }}" class="flyout-link {{ $isActive('pharmacy') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation w-4 text-center"></i>Inventory Alerts</a>
                </div>
            </div>

            <div class="space-y-1 desktop-hide">
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('pos') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-cash-register w-5 text-center shrink-0"></i>POS Terminal
                </a>
                <a href="{{ route('pharmacy.dashboard') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('pharmacy') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-triangle-exclamation w-5 text-center shrink-0"></i>Inventory Alerts
                </a>
            </div>
        </div>

        <!-- Management Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Management</p>
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['doctors', 'staff']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Management">
                    <i class="fa-solid fa-user-gear w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Management</p>
                    <a href="{{ route('doctors.index') }}" class="flyout-link {{ $isActive('doctors') ? 'active' : '' }}"><i class="fa-solid fa-user-doctor w-4 text-center"></i>Doctors</a>
                    <a href="{{ route('staff.index') }}" class="flyout-link {{ $isActive('staff') ? 'active' : '' }}"><i class="fa-solid fa-users w-4 text-center"></i>Staff</a>
                </div>
            </div>

            <div class="space-y-1 desktop-hide">
                <a href="{{ route('doctors.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('doctors') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-user-doctor w-5 text-center shrink-0"></i>Doctors
                </a>
                <a href="{{ route('staff.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('staff') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-users w-5 text-center shrink-0"></i>Staff
                </a>
            </div>
        </div>

        <!-- Reports Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Reports</p>
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['reports']) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="Reports">
                    <i class="fa-solid fa-chart-pie w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Reports & Analytics</p>
                    <a href="{{ route('tenant.reports.sales') }}" class="flyout-link {{ $isActive('reports/sales') ? 'active' : '' }}"><i class="fa-solid fa-receipt w-4 text-center"></i>Sales Report</a>
                    <a href="{{ route('tenant.reports.revenue') }}" class="flyout-link {{ $isActive('reports/revenue') ? 'active' : '' }}"><i class="fa-solid fa-chart-line w-4 text-center"></i>Revenue Report</a>
                </div>
            </div>

            <div class="space-y-1 desktop-hide">
                <a href="{{ route('tenant.reports.sales') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('reports/sales') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-receipt w-5 text-center shrink-0"></i>Sales Report
                </a>
                <a href="{{ route('tenant.reports.revenue') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('reports/revenue') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center shrink-0"></i>Revenue Report
                </a>
            </div>
        </div>

        <!-- System Settings Section -->
        <div>
            <p class="desktop-hide px-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">System</p>
            
            <div class="nav-group mobile-hide">
                <button class="tip w-full flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ $isGroupActive(['setup', 'branch-setup', 'activity-logs', 'sessions', 'change-password']) || $isActive('setup') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}" data-tip="System">
                    <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
                </button>
                <div class="flyout-menu">
                    <p class="px-3 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">System Setup</p>
                    <a href="{{ route('tenant.setup') }}" class="flyout-link {{ $isActive('setup') ? 'active' : '' }}"><i class="fa-solid fa-sliders w-4 text-center"></i>System Setup</a>
                    <a href="{{ route('tenant.branch-setup') }}" class="flyout-link {{ $isActive('branch-setup') ? 'active' : '' }}"><i class="fa-solid fa-code-branch w-4 text-center"></i>Branch Setup</a>
                    <a href="{{ route('tenant.activity-logs') }}" class="flyout-link {{ $isActive('activity-logs') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>Activity Logs</a>
                    <a href="{{ route('tenant.sessions.index') }}" class="flyout-link {{ $isActive('sessions') ? 'active' : '' }}"><i class="fa-solid fa-desktop w-4 text-center"></i>Active Sessions</a>
                    <a href="{{ route('password.change') }}" class="flyout-link {{ $isActive('change-password') ? 'active' : '' }}"><i class="fa-solid fa-key w-4 text-center"></i>Change Password</a>
                    <a href="{{ route('two-factor.index') }}" class="flyout-link {{ $isActive('two-factor') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved w-4 text-center"></i>Two-Factor Auth</a>
                </div>
            </div>

            <div class="space-y-1 desktop-hide">
                <a href="{{ route('tenant.setup') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('setup') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-sliders w-5 text-center shrink-0"></i>System Setup
                </a>
                <a href="{{ route('tenant.branch-setup') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('branch-setup') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-code-branch w-5 text-center shrink-0"></i>Branch Setup
                </a>
                <a href="{{ route('tenant.activity-logs') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('activity-logs') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center shrink-0"></i>Activity Logs
                </a>
                <a href="{{ route('tenant.sessions.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('sessions') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-desktop w-5 text-center shrink-0"></i>Active Sessions
                </a>
                <a href="{{ route('password.change') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('change-password') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-key w-5 text-center shrink-0"></i>Change Password
                </a>
                <a href="{{ route('two-factor.index') }}" class="flex items-center gap-3 px-2.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition {{ $isActive('two-factor') ? '!bg-indigo-600 !text-white' : '' }}">
                    <i class="fa-solid fa-shield-halved w-5 text-center shrink-0"></i>Two-Factor Auth
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom Sidebar Footer: Trial Badge & Collapse Toggle -->
    <div class="px-2.5 py-3 border-t border-slate-800 shrink-0 space-y-2">

        @php 
            $tenant = app('currentTenant'); 
            $remainingDays = $tenant && $tenant->trial_ends_at ? max(0, (int) now()->diffInDays($tenant->trial_ends_at, false)) : 0;
            $isUrgent = $remainingDays <= 3;
        @endphp

        @if($tenant && $tenant->status === 'trial')
            <div class="desktop-hide flex items-center gap-2 px-2.5 py-2 rounded-lg {{ $isUrgent ? 'bg-red-950/40 border border-red-500/30' : 'bg-slate-800/80 border border-slate-700/60' }}">
                <i class="fa-solid fa-clock w-4 text-center shrink-0 {{ $isUrgent ? 'text-red-400' : 'text-amber-400' }}"></i>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Free Trial Mode</p>
                    <p class="text-xs font-extrabold {{ $isUrgent ? 'text-red-400' : 'text-amber-400' }}">{{ $remainingDays }} Days Left</p>
                </div>
            </div>
        @endif

        <!-- Toggle Collapse Button -->
        <button onclick="toggleSidebar()" class="hidden lg:flex w-full items-center justify-center gap-2 px-2.5 py-2 rounded-lg text-xs font-semibold text-slate-400 hover:bg-slate-800 hover:text-white transition border border-slate-800">
            <i id="sidebarToggleIcon" class="fa-solid fa-angles-right w-4 text-center"></i>
            <span class="desktop-hide">Collapse Sidebar</span>
        </button>
    </div>
</aside>