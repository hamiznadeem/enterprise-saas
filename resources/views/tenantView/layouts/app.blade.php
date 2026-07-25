@extends('layouts.master')

@section('body')
    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main Area -->
    <div id="mainContent" class="min-h-screen flex flex-col transition-all duration-300">

        <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200/80">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    @isset($header)
                        <div class="text-sm font-medium text-gray-700">{{ $header }}</div>
                    @endisset
                </div>
                <div class="flex items-center gap-2">
                    <!-- Tenant Name Badge -->
                    @if($tenant = app('currentTenant'))
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        {{ $tenant->name }}
                    </span>
                    @endif
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition" title="Toggle Dark Mode">
                        <i id="darkModeIcon" class="fa-solid fa-moon w-5 h-5"></i>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" id="userMenu">
                        <button onclick="document.getElementById('userDropdown').classList.toggle('hidden')" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ auth()->user()->name ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-1.5 z-50">
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                Profile
                            </a>
                            @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('superAdmin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Super Admin
                            </a>
                            @endif
                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form method="POST" action="{{ route('tenant.auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Trial Warning Banner (Sticky Top) -->
        @php 
            $topTenant = app('currentTenant'); 
            $topDays = $topTenant && $topTenant->trial_ends_at ? (int) now()->diffInDays($topTenant->trial_ends_at) : 0;
            $topUrgent = $topDays <= 5;
        @endphp
        
        @if($topTenant && $topTenant->trial_ends_at && $topTenant->trial_ends_at->isFuture())
        <div class="sticky top-16 z-20 mx-4 sm:mx-6 mt-4 p-3.5 rounded-xl border {{ $topUrgent ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200' }} flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full {{ $topUrgent ? 'bg-red-100' : 'bg-amber-100' }} flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 {{ $topUrgent ? 'text-red-600' : 'text-amber-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold {{ $topUrgent ? 'text-red-800' : 'text-amber-800' }}">
                        {{ $topUrgent ? 'Action Required:' : 'Heads up:' }} Your trial ends in <span class="underline">{{ $topDays }} days</span>
                    </p>
                    <p class="text-xs {{ $topUrgent ? 'text-red-600' : 'text-amber-600' }}">Upgrade your plan to avoid losing access to your data.</p>
                </div>
            </div>
            <a href="{{ route('tenant.billing') }}" class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-white text-sm font-medium rounded-lg border {{ $topUrgent ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-amber-300 text-amber-700 hover:bg-amber-50' }} transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Upgrade Now
            </a>
        </div>
        @endif

        <!-- Flash Messages -->
        <div class="px-4 sm:px-6 pt-4 space-y-2">
            @if(session('success'))
            <div id="success-alert" class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm shadow-sm transition-all duration-500 ease-out">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check w-5 h-5 text-emerald-500 shrink-0"></i>
                    {{ session('success') }}
                </span>
                <button onclick="hideAlert('success-alert')" class="text-emerald-600 hover:text-emerald-900 transition p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <script>
                setTimeout(function() { hideAlert('success-alert'); }, 3000);
                function hideAlert(id) {
                    let alert = document.getElementById(id);
                    if(alert) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => alert.remove(), 500);
                    }
                }
            </script>
            @endif

            @if(session('error'))
            <div id="error-alert" class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm shadow-sm transition-all duration-500 ease-out">
                <span class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark w-5 h-5 text-red-500 shrink-0"></i>
                    {{ session('error') }}
                </span>
                <button onclick="hideErrorAlert()" class="text-red-600 hover:text-red-900 transition p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <script>
                setTimeout(function() { hideErrorAlert(); }, 4000);
                function hideErrorAlert() {
                    let alert = document.getElementById('error-alert');
                    if(alert) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => alert.remove(), 500);
                    }
                }
            </script>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Check if desktop screen
            if (window.innerWidth >= 1024) {
                // Desktop: Toggle expanded class on body
                document.body.classList.toggle('sidebar-expanded');
                
                // Save state to localStorage
                if (document.body.classList.contains('sidebar-expanded')) {
                    localStorage.setItem('sidebarState', 'expanded');
                } else {
                    localStorage.removeItem('sidebarState');
                }
            } else {
                // Mobile: Slide in/out
                if(sidebar) sidebar.classList.toggle('-translate-x-full');
                if(overlay) overlay.classList.toggle('hidden');
            }
        }

        // Restore sidebar state on page load (Desktop only)
        if (window.innerWidth >= 1024 && localStorage.getItem('sidebarState') === 'expanded') {
            document.body.classList.add('sidebar-expanded');
        }

        // Dark Mode
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark ? '1' : '0');
            updateDarkModeIcon();
        }

        function updateDarkModeIcon() {
            const icon = document.getElementById('darkModeIcon');
            if (!icon) return;
            const isDark = document.documentElement.classList.contains('dark');
            icon.classList.toggle('fa-moon', !isDark);
            icon.classList.toggle('fa-sun', isDark);
        }

        // Restore dark mode on load
        if (localStorage.getItem('darkMode') === '1') {
            document.documentElement.classList.add('dark');
        }
        updateDarkModeIcon();
    </script>
@endsection