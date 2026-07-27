@php
    $activeRoute = request()->route()?->getName() ?? '';
    $admin = auth('platform')->user();
    
    function isPlatformActive($routeName, $activeRoute) {
        return strpos($activeRoute, $routeName) === 0 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white';
    }
@endphp

<!-- Sidebar: w-16 icons only → hover → w-64 full sidebar -->
<aside id="sidebar" 
       class="group/sidebar fixed top-0 left-0 z-50 h-screen 
              w-64 lg:w-16 lg:hover:w-64 
              bg-gray-900 text-gray-100 flex flex-col 
              transition-all duration-300 ease-in-out 
              -translate-x-full lg:translate-x-0">
    
    <!-- Logo Section -->
    <div class="h-16 flex items-center px-4 lg:group-hover/sidebar:px-6 border-b border-gray-800 shrink-0">
        <a href="{{ route('platform.dashboard') }}" class="flex items-center w-full">
            <i class="fa-solid fa-cube text-blue-500 text-xl shrink-0"></i>
            <span class="ml-3 font-bold text-lg tracking-wide whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">SaaS Admin</span>
        </a>
        <!-- Close button (mobile only) -->
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white shrink-0 ml-auto">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-4 px-2.5 lg:group-hover/sidebar:px-3 space-y-1 sidebar-scroll">
        
        <!-- Dashboard -->
        <a href="{{ route('platform.dashboard') }}" 
           title="Dashboard"
           class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.dashboard', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
            <i class="fa-solid fa-gauge-high w-5 text-center shrink-0"></i>
            <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Dashboard</span>
        </a>

        <!-- Tenants -->
        <a href="{{ route('platform.tenants.index') }}" 
           title="Tenants"
           class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.tenants', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
            <i class="fa-solid fa-building w-5 text-center shrink-0"></i>
            <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Tenants</span>
        </a>

        <!-- Plans -->
        <a href="{{ route('platform.plans.index') }}" 
           title="Plans"
           class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.plans', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
            <i class="fa-solid fa-tags w-5 text-center shrink-0"></i>
            <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Plans</span>
        </a>

        <!-- Invoices -->
        <a href="{{ route('platform.invoices.index') }}" 
           title="Invoices"
           class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.invoices', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center shrink-0"></i>
            <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Invoices</span>
        </a>

        <!-- Audit Logs -->
        <a href="{{ route('platform.audit-logs.index') }}" 
           title="Audit Logs"
           class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.audit-logs', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
            <i class="fa-solid fa-shield-halved w-5 text-center shrink-0"></i>
            <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Audit Logs</span>
        </a>

        <!-- Reports Section -->
        <div class="pt-4 mt-4 border-t border-gray-800">
            <p class="px-2.5 lg:group-hover/sidebar:px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75 whitespace-nowrap">Reports</p>
            
            <a href="{{ route('platform.reports.revenue') }}" title="Revenue Report"
               class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.reports.revenue', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
                <i class="fa-solid fa-chart-line w-5 text-center shrink-0"></i>
                <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Revenue Report</span>
            </a>
            
            <a href="{{ route('platform.reports.sales') }}" title="Sales Report"
               class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.reports.sales', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
                <i class="fa-solid fa-chart-pie w-5 text-center shrink-0"></i>
                <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Sales Report</span>
            </a>
        </div>

                <!-- System Section -->
        <div class="pt-4 mt-4 border-t border-gray-800">
            <p class="px-2.5 lg:group-hover/sidebar:px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75 whitespace-nowrap">System</p>
            
            <a href="{{ route('platform.roles.index') }}" title="Roles & Permissions"
               class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.roles', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
                <i class="fa-solid fa-user-shield w-5 text-center shrink-0"></i>
                <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Roles & Permissions</span>
            </a>

            <a href="{{ route('platform.settings.index') }}" title="Settings"
               class="flex items-center rounded-lg text-sm font-medium transition {{ isPlatformActive('platform.settings', $activeRoute) }} px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
                <i class="fa-solid fa-gear w-5 text-center shrink-0"></i>
                <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">Settings</span>
            </a>
            
            <a href="#" title="System Info"
               class="flex items-center rounded-lg text-sm font-medium transition text-gray-400 hover:bg-gray-700 hover:text-white px-2.5 py-2.5 lg:group-hover/sidebar:px-3">
                <i class="fa-solid fa-circle-info w-5 text-center shrink-0"></i>
                <span class="ml-3 whitespace-nowrap opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75">System Info</span>
            </a>
        </div>

    </nav>

    <!-- Sidebar Footer -->
    <div class="p-2.5 lg:group-hover/sidebar:p-3 border-t border-gray-800 shrink-0">
        @if($admin && $admin->role === 'super_admin' && app('currentTenant'))
            <a href="{{ route('dashboard') }}" title="Exit Admin Mode"
               class="flex items-center justify-center lg:group-hover/sidebar:justify-start gap-2 w-full px-2.5 py-2.5 lg:group-hover/sidebar:px-3 bg-gray-800 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-700 transition">
                <i class="fa-solid fa-arrow-right-from-bracket shrink-0"></i>
                <span class="opacity-0 lg:group-hover/sidebar:opacity-100 transition-opacity duration-200 delay-75 whitespace-nowrap">Exit Admin Mode</span>
            </a>
        @endif
    </div>

</aside>