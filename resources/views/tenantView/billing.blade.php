<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @if($tenant->status === 'trial')
            Free Trial Active — Enterprise POS
        @elseif($tenant->status === 'active')
            Subscription Active — Enterprise POS
        @else
            Subscription Expired — Enterprise POS
        @endif
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between p-4 md:p-8">

    @php
        $daysLeft = 0;
        if ($tenant->status === 'trial' && $tenant->trial_ends_at) {
            $daysLeft = max(0, (int) now()->diffInDays($tenant->trial_ends_at, false));
        } elseif ($tenant->status === 'active' && $tenant->will_expire_at) {
            $daysLeft = max(0, (int) now()->diffInDays($tenant->will_expire_at, false));
        }
    @endphp

    <!-- Top Navigation Header -->
    <header class="max-w-5xl w-full mx-auto flex items-center justify-between py-2 border-b border-slate-200">
        <a href="/" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                <i class="fa-solid fa-bolt text-xs"></i>
            </div>
            <span class="text-lg font-bold text-slate-900 tracking-tight">Enterprise POS</span>
        </a>

        <div class="flex items-center gap-3">
            @if($tenant->status === 'trial' || $tenant->status === 'active')
                <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Dashboard</span>
                </a>
            @endif

            <form method="POST" action="{{ route('tenant.auth.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-slate-500 hover:text-red-600 font-medium transition flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 border border-slate-200">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Viewport Section -->
    <main class="max-w-5xl w-full mx-auto my-auto py-6">

        {{-- Flash Notification Banners --}}
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-3 flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-600 text-sm"></i>
            <p class="text-xs text-red-800 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-4 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
            <p class="text-xs text-amber-800 font-medium">{{ session('warning') }}</p>
        </div>
        @endif

        <!-- 2-Column Responsive Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Left Banner: Status & Overview -->
            <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col justify-between shadow-sm">
                <div>
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold mb-4
                        @if($tenant->status === 'trial') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($tenant->status === 'active') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @else bg-red-50 text-red-700 border border-red-200 @endif">
                        <span class="w-2 h-2 rounded-full @if($tenant->status === 'trial') bg-blue-600 @elseif($tenant->status === 'active') bg-emerald-600 @else bg-red-600 @endif"></span>
                        @if($tenant->status === 'trial') Free Trial Mode @elseif($tenant->status === 'active') Active Subscription @else Plan Expired @endif
                    </div>

                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                        @if($tenant->status === 'trial')
                            Your 14-Day Free Trial is Active
                        @elseif($tenant->status === 'active')
                            Your Subscription is Active
                        @else
                            Your Subscription Has Expired
                        @endif
                    </h1>

                    <p class="text-xs md:text-sm text-slate-600 mt-2 leading-relaxed">
                        @if($tenant->status === 'trial')
                            You have <strong class="text-slate-900 font-semibold bg-blue-100 text-blue-800 px-2 py-0.5 rounded">{{ $daysLeft }} Days</strong> remaining in your trial. All POS features are unlocked.
                        @elseif($tenant->status === 'active')
                            Your store account is fully active. Next billing renewal date is <strong class="text-slate-900 font-semibold">{{ $tenant->will_expire_at ? $tenant->will_expire_at->format('M j, Y') : 'N/A' }}</strong>.
                        @else
                            Your plan ended on <strong class="text-slate-900 font-semibold">{{ $tenant->will_expire_at ? $tenant->will_expire_at->format('M j, Y') : ($tenant->trial_ends_at ? $tenant->trial_ends_at->format('M j, Y') : 'N/A') }}</strong>. Access is paused until renewal. Your store data is completely safe.
                        @endif
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Business Name</p>
                        <p class="text-sm font-bold text-slate-900 truncate max-w-[200px]">{{ $tenant->name }}</p>
                    </div>

                    @if($tenant->status === 'trial' || $tenant->status === 'active')
                        <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg transition shadow-sm">
                            <span>Open POS Dashboard</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Column: Account Details & Actions -->
            <div class="lg:col-span-6 bg-white border border-slate-200 rounded-2xl p-6 flex flex-col justify-between shadow-sm">
                
                <div>
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-blue-600"></i> Account & Plan Summary
                    </h2>

                    <!-- Specs Grid -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Owner Name</p>
                            <p class="text-xs font-semibold text-slate-800 mt-1 truncate">{{ $tenant->owner_name }}</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Subdomain URL</p>
                            <p class="text-xs font-semibold text-blue-600 mt-1 truncate">{{ $tenant->domain }}</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Current Plan</p>
                            <p class="text-xs font-semibold text-slate-800 mt-1">{{ $tenant->plan->name ?? 'Free Trial' }}</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Expiry Date</p>
                            <p class="text-xs font-semibold text-slate-900 mt-1">
                                @if($tenant->status === 'trial' && $tenant->trial_ends_at)
                                    {{ $tenant->trial_ends_at->format('M j, Y') }}
                                @elseif($tenant->will_expire_at)
                                    {{ $tenant->will_expire_at->format('M j, Y') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact & Support Action Buttons -->
                <div>
                    <p class="text-xs font-medium text-slate-600 mb-2">Need help or want to upgrade?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="https://wa.me/923001234567?text={{ urlencode("Hi,\nI would like to upgrade / renew my subscription.\n\nBusiness Name: " . $tenant->name . "\nDomain: " . $tenant->domain . "\nOwner: " . $tenant->owner_name) }}" 
                           target="_blank"
                           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-lg transition shadow-sm">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>WhatsApp Sales</span>
                        </a>

                        <a href="tel:+923001234567" 
                           class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition border border-slate-200">
                            <i class="fa-solid fa-phone text-blue-600"></i>
                            <span>Call Support</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="max-w-5xl w-full mx-auto text-center py-2 text-[11px] text-slate-400 border-t border-slate-200">
        <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i> 100% Safe & Encrypted Enterprise Platform
    </footer>

</body>
</html>