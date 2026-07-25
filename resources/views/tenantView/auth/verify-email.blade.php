<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email — SwiftPOS</title>
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
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <!-- Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full">
                    <i class="fa-solid fa-envelope-circle-check text-blue-500 text-2xl"></i>
                </div>
            </div>

            <h1 class="text-xl font-bold text-gray-900 text-center mb-2">Verify Your Email</h1>
            <p class="text-sm text-gray-500 text-center mb-8">
                We've sent a verification link to<br>
                <strong class="text-gray-700">{{ auth()->user()->email }}</strong>
            </p>

            @if(session('status'))
            <div id="successMsg" class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">
                <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
            </div>
            @endif

            @if(session('verified'))
            <div class="mb-6 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">
                <i class="fa-solid fa-circle-check mr-1"></i> Your email has been verified! Redirecting...
            </div>
            @endif

            <!-- SIMPLE FORM POST — No AJAX -->
            <form method="POST" action="{{ route('tenant.verification.send') }}" id="resendForm">
                @csrf
                <button type="submit" id="resendBtn"
                    class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl text-sm hover:bg-blue-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span id="resendText">Resend Verification Email</span>
                </button>
            </form>

            <!-- Logout -->
            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <form method="POST" action="{{ route('tenant.auth.logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 font-medium transition">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i>Sign out
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            Didn't receive the email? Check your spam folder.
        </p>
    </div>

    <script>
        // Auto redirect after verified
        @if(session('verified'))
        setTimeout(function() {
            window.location.href = '{{ route("tenant.dashboard") }}';
        }, 2000);
        @endif

        // Just disable button on submit — page will reload with response
        document.getElementById('resendForm').addEventListener('submit', function() {
            var btn = document.getElementById('resendBtn');
            var text = document.getElementById('resendText');
            btn.disabled = true;
            text.textContent = 'Sending...';
            // Form submits normally, page reloads with flash message
        });
    </script>
</body>
</html>
