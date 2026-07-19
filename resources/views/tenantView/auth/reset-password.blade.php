<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password — SwiftPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] } } } }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-in { animation: fadeInUp 0.5s ease-out; }
        .input-field { transition: border-color 0.2s, box-shadow 0.2s; }
        .input-field:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn-reset { background: #3b82f6; transition: background 0.2s, transform 0.1s; }
        .btn-reset:hover { background: #2563eb; }
        .btn-reset:active { transform: scale(0.98); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm fade-in">

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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-lock-open text-emerald-500 text-xl"></i>
                </div>
                <h1 class="text-lg font-bold text-gray-900">Reset Password</h1>
                <p class="text-sm text-gray-500 mt-1">Enter your new password below.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700 text-center font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600 text-center">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.password.update') }}" class="space-y-4">
                @csrf
                
                <!-- ✅ Safe Token -->
                <input type="hidden" name="token" value="{{ $token ?? $request->token ?? '' }}">

                <!-- ✅ Safe Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ $email ?? $request->email ?? old('email') }}" required readonly class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">New Password</label>
                    <input type="password" name="password" required minlength="8" placeholder="Min 8 characters" class="input-field w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter password" class="input-field w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none">
                </div>

                <button type="submit" class="btn-reset w-full py-2.5 text-white font-semibold rounded-lg text-sm">
                    Reset Password
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                <a href="{{ route('tenantView.login') }}" class="text-xs text-gray-400 hover:text-blue-600 font-medium transition inline-flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>