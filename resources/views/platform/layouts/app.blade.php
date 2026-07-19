@extends('layouts.master')

@section('body')

@php
    $admin = auth('platform')->user();
@endphp

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
@include('layouts.platform_navigation')

<!-- ✅ Main Content: lg:pl-16 fixed for mini sidebar -->
<div id="mainWrapper" class="min-h-screen lg:pl-16 transition-all duration-300">

    <!-- Top Bar -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200/80">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6">
            
            <!-- Left Side -->
            <div class="flex items-center gap-3">
                <!-- Hamburger: Mobile only -->
                <button onclick="toggleSidebar()" 
                        class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition"
                        title="Open Menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                @isset($header)
                    <h1 class="text-lg font-semibold text-gray-800">{{ $header }}</h1>
                @endisset
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-3">
                
                <!-- Super Admin Badge -->
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Super Admin
                </span>

                <!-- User Dropdown -->
                <div class="relative" id="userMenu">
                    <button onclick="document.getElementById('userDropdown').classList.toggle('hidden')" 
                            class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ $admin?->name ? strtoupper(substr($admin->name, 0, 1)) : 'A' }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-700 leading-tight">{{ $admin?->name }}</p>
                            <p class="text-xs text-gray-500 leading-tight">{{ ucfirst($admin?->role ?? 'Admin') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ $admin?->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $admin?->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fa-solid fa-user-gear w-4 text-gray-400"></i>
                                My Profile
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fa-solid fa-gear w-4 text-gray-400"></i>
                                Settings
                            </a>
                        </div>
                        <div class="border-t border-gray-100 pt-1">
                            <form method="POST" action="{{ route('platform.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function isMobile() {
        return window.innerWidth < 1024;
    }

    function toggleSidebar() {
        // Only for mobile
        if (!isMobile()) return;
        
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    // Close sidebar when clicking a nav link on mobile
    document.querySelectorAll('#sidebar a').forEach(function(link) {
        link.addEventListener('click', function() {
            if (isMobile()) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });

    // Reset on resize
    window.addEventListener('resize', function() {
        if (!isMobile()) {
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
</script>

@endsection