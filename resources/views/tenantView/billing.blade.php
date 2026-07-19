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
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float { animation: float 4s ease-in-out infinite; }
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
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-10 text-center">
                <div class="float inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fa-solid fa-clock text-white text-2xl"></i>
                </div>
                <h1 class="text-white text-xl font-extrabold">Subscription Expired</h1>
                <p class="text-red-100 text-sm mt-1">Your current plan has ended</p>
            </div>

            <!-- Content -->
            <div class="p-6">

                <!-- Warning -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-amber-800">What happens now?</p>
                            <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                                Your account data is safe. You cannot access the dashboard until your subscription is renewed.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="space-y-3">
                    <a href="https://wa.me/923001234567?text=Hi,%0D%0AMy subscription has expired. I would like to renew it." 
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-sm transition">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Chat on WhatsApp
                    </a>

                    <a href="tel:+923001234567" 
                       class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-semibold rounded-xl text-sm transition">
                        <i class="fa-solid fa-phone text-xs"></i>
                        Call: +92-300-1234567
                    </a>
                </div>

                <!-- Logout -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <form method="POST" action="{{ route('tenant.auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 font-medium transition">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i>Sign out
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            Your data is safe. Account will be reactivated after payment.
        </p>

    </div>

</body>
</html>