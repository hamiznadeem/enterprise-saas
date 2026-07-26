<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SwiftPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.4s ease-out; }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .shake { animation: shake 0.3s ease; }

        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-login {
            background: #3b82f6;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-login:hover:not(:disabled) { background: #2563eb; }
        .btn-login:active:not(:disabled) { transform: scale(0.98); }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; }

        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
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
                <span class="text-xl font-extrabold text-gray-900 tracking-tight">POS</span>
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <h1 class="text-lg font-bold text-gray-900 text-center">Sign in to your account</h1>
            <p class="text-sm text-gray-500 text-center mt-1 mb-6">Enter your credentials below</p>

            <!-- Error -->
            <div id="errorBox" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-xs flex-shrink-0"></i>
                <span id="errorMsg"></span>
            </div>

            <!-- Form -->
            <form id="loginForm" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@business.com"
                            autocomplete="email"
                            class="input-field w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-gray-600">Password</label>
                        <a href="{{ route('tenant.password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter password"
                            autocomplete="current-password"
                            class="input-field w-full pl-9 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                        >
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="eyeIcon" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember -->
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600 accent-blue-600 cursor-pointer">
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>

                <!-- Submit -->
                <button type="submit" id="submitBtn" class="btn-login w-full py-2.5 text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2">
                    <span id="btnText">Sign In</span>
                    <div id="btnSpinner" class="spinner hidden"></div>
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-5 pt-5 border-t border-gray-100 flex items-center justify-center gap-4">
                <a href="{{ route('tenant.password.request') }}" class="text-xs text-gray-400 hover:text-blue-600 font-medium transition">
                    Forgot password?
                </a>
                <span class="text-gray-200">|</span>
                <a href="{{ route('trial.form') }}" class="text-xs text-gray-400 hover:text-blue-600 font-medium transition">
                    Create account
                </a>
            </div>
        </div>

        <!-- Admin Login Link -->
        <div class="text-center mt-4">
            <a href="{{ route('platform.login') }}" class="text-xs text-gray-400 hover:text-blue-600 font-medium transition">
                <i class="fa-solid fa-shield-halved text-[10px] mr-1"></i>Admin Login
            </a>
        </div>
    </div>

    <script>
        let submitting = false;

        function togglePass() {
            const inp = document.getElementById('password');
            const ico = document.getElementById('eyeIcon');
            inp.type = inp.type === 'password' ? 'text' : 'password';
            ico.classList.toggle('fa-eye');
            ico.classList.toggle('fa-eye-slash');
        }

        function showError(msg) {
            const box = document.getElementById('errorBox');
            document.getElementById('errorMsg').textContent = msg;
            box.classList.remove('hidden', 'shake');
            void box.offsetWidth;
            box.classList.add('shake');
        }

        function hideError() {
            document.getElementById('errorBox').classList.add('hidden');
        }

        function setLoading(on) {
            submitting = on;
            const btn = document.getElementById('submitBtn');
            btn.disabled = on;
            document.getElementById('btnText').textContent = on ? 'Signing in...' : 'Sign In';
            document.getElementById('btnSpinner').classList.toggle('hidden', !on);
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (submitting) return;

            hideError();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.querySelector('[name="remember"]').checked;

            if (!email) { showError('Email is required.'); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('Enter a valid email.'); return; }
            if (!password) { showError('Password is required.'); return; }

            setLoading(true);

            fetch('{{ route("tenant.auth.login") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(this)
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Login failed.'));
                }
                window.location.href = data.redirect || '/tenant/dashboard';
            })
            .catch(err => {
                showError(err.message);
            })
            .finally(() => setLoading(false));
        });

        ['email', 'password'].forEach(id => {
            document.getElementById(id).addEventListener('input', hideError);
        });

        window.addEventListener('load', () => {
            setTimeout(() => document.getElementById('email').focus(), 200);
        });
    </script>
</body>
</html>