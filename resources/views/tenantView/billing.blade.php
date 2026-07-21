<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Expired — SwiftPOS</title>
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
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.5s ease-out; }
        .fade-in-delay { animation: fadeInUp 0.5s ease-out 0.15s both; }
        .fade-in-delay-2 { animation: fadeInUp 0.5s ease-out 0.3s both; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float { animation: float 4s ease-in-out infinite; }

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(239, 68, 68, 0.4);
            animation: pulse-ring 2s ease-out infinite;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md fade-in">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-white text-sm"></i>
                </div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">SwiftPOS</span>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-br from-red-500 via-red-500 to-red-600 px-6 py-10 text-center relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="relative">
                    <div class="float inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mb-4 relative pulse-ring">
                        <i class="fa-solid fa-clock text-white text-2xl"></i>
                    </div>
                    <h1 class="text-white text-xl font-extrabold">Subscription Expired</h1>
                    <p class="text-red-100 text-sm mt-1">Your current plan has ended</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">

                {{-- Flash Message --}}
                @if(session('error'))
                <div class="fade-in mb-5 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
                @endif

                {{-- Tenant Info Card --}}
                <div class="fade-in-delay bg-gray-50 rounded-xl p-4 mb-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-building text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $tenant->name }}</p>
                            <p class="text-[11px] text-gray-400">{{ $tenant->owner_name }} · {{ $tenant->owner_email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Status</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 text-[11px] font-bold rounded-full">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </div>
                        <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Expired</p>
                            <p class="mt-1 text-xs font-bold text-gray-900">
                                {{ $tenant->will_expire_at ? $tenant->will_expire_at->format('M j, Y') : ($tenant->trial_ends_at ? $tenant->trial_ends_at->format('M j, Y') : 'N/A') }}
                            </p>
                        </div>
                        @if($tenant->plan)
                        <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Plan</p>
                            <p class="mt-1 text-xs font-bold text-gray-900">{{ $tenant->plan->name }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Cycle</p>
                            <p class="mt-1 text-xs font-bold text-gray-900 capitalize">{{ $tenant->plan->billing_cycle }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Warning --}}
                <div class="fade-in-delay-2 bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-amber-800">What happens now?</p>
                            <ul class="text-xs text-amber-700 mt-1.5 space-y-1 leading-relaxed">
                                <li class="flex items-start gap-1.5">
                                    <i class="fa-solid fa-check text-amber-500 mt-0.5 text-[9px]"></i>
                                    Your data is completely safe and backed up
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <i class="fa-solid fa-check text-amber-500 mt-0.5 text-[9px]"></i>
                                    Dashboard access is paused until renewal
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <i class="fa-solid fa-check text-amber-500 mt-0.5 text-[9px]"></i>
                                    Reactivation is instant after payment
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="fade-in-delay-2 space-y-2.5">
                    <a href="https://wa.me/923001234567?text={{ urlencode("Hi,\nMy SwiftPOS subscription has expired.\n\nBusiness: " . $tenant->name . "\nOwner: " . $tenant->owner_name . "\nEmail: " . $tenant->owner_email . "\n\nI would like to renew it.") }}" 
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2.5 px-5 py-3.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-sm transition shadow-sm shadow-green-500/20 hover:shadow-green-500/30">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Chat on WhatsApp
                    </a>

                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="tel:+923001234567" 
                           class="flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
                            <i class="fa-solid fa-phone text-xs"></i>
                            <span class="text-xs">Call Us</span>
                        </a>
                        <a href="mailto:support@swiftpos.com?subject=Subscription Renewal — {{ $tenant->name }}&body={{ urlencode("Hi,\nMy subscription has expired.\nBusiness: " . $tenant->name . "\nPlease help me renew it.") }}" 
                           class="flex items-center justify-center gap-2 px-4 py-3 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
                            <i class="fa-solid fa-envelope text-xs"></i>
                            <span class="text-xs">Email Us</span>
                        </a>
                    </div>
                </div>

                {{-- Logout --}}
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <form method="POST" action="{{ route('tenant.auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 font-medium transition inline-flex items-center gap-1.5 group">
                            <i class="fa-solid fa-right-from-bracket group-hover:translate-x-0.5 transition-transform"></i>
                            Sign out
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            <i class="fa-solid fa-shield-halved mr-1"></i>
            Your data is safe. Account reactivates instantly after payment.
        </p>

    </div>

</body>
</html>