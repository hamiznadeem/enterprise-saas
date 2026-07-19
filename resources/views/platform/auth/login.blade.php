<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Platform Login — SwiftPOS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        .login-bg {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 20% 80%, rgba(59, 130, 246, 0.06) 0px, transparent 50%),
                radial-gradient(at 80% 20%, rgba(6, 182, 212, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.03) 0px, transparent 70%);
        }

        .card-shine {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.6) 100%);
            backdrop-filter: blur(20px);
        }

        .grid-pattern {
            background-image: 
                linear-gradient(rgba(148, 163, 184, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            box-shadow: 0 4px 15px -3px rgba(59, 130, 246, 0.4);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
            box-shadow: 0 6px 20px -3px rgba(59, 130, 246, 0.5);
            transform: translateY(-1px);
        }
        .btn-gradient:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px -3px rgba(59, 130, 246, 0.4);
        }
        .btn-gradient:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .logo-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.4);
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .shake {
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .spinner {
            width: 1rem;
            height: 1rem;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background Grid Pattern -->
    <div class="absolute inset-0 grid-pattern"></div>

    <!-- Floating Decorative Elements -->
    <div class="absolute top-16 left-16 w-72 h-72 bg-blue-200/20 rounded-full blur-3xl float-animation"></div>
    <div class="absolute bottom-16 right-16 w-96 h-96 bg-cyan-200/20 rounded-full blur-3xl float-animation" style="animation-delay: -3s;"></div>
    <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-indigo-200/10 rounded-full blur-3xl float-animation" style="animation-delay: -1.5s;"></div>

    <!-- Main Card -->
    <div class="relative w-full max-w-md fade-in">
        
        <!-- Card -->
        <div class="card-shine rounded-2xl border border-gray-200/80 shadow-xl shadow-gray-200/50 overflow-hidden">
            
            <!-- Top Blue Bar -->
            <div class="h-1.5 bg-gradient-to-r from-blue-500 via-blue-600 to-cyan-500"></div>

            <div class="p-8 sm:p-10">
                
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <div class="logo-gradient w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg">
                        <i class="fa-solid fa-bolt text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">SwiftPOS Admin</h1>
                    <p class="text-gray-500 text-sm mt-1.5">Owner & Admin Access Only</p>
                </div>

                <!-- Error Alert -->
                <div id="errorBox" class="hidden flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl mb-6 text-sm">
                    <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xs"></i>
                    </div>
                    <p id="errorMsg" class="text-red-700 font-medium"></p>
                </div>

                <!-- Success Alert -->
                <div id="successBox" class="hidden flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl mb-6 text-sm">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-check text-emerald-600 text-xs"></i>
                    </div>
                    <p id="successMsg" class="text-emerald-700 font-medium"></p>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-5">
                    @csrf
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email"
                                class="input-focus w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition"
                                placeholder="admin@yoursaas.com"
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                required 
                                autocomplete="current-password"
                                class="input-focus w-full pl-11 pr-12 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition"
                                placeholder="Enter your password"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" id="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 accent-blue-600">
                            <span class="text-sm text-gray-600 font-medium">Remember me</span>
                        </label>
                        <a href="{{ route('platform.password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="btn-gradient w-full py-3.5 text-white font-semibold rounded-xl text-sm flex items-center justify-center gap-2">
                        <span id="btnText">Access Platform</span>
                        <i id="btnArrow" class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                        <div id="btnSpinner" class="spinner"></div>
                    </button>
                </form>


                <!-- Divider -->
                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">or</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <!-- Back Link -->
                <div class="text-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 font-medium transition group">
                        <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                        <span>Back to Tenant Login</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-400 mt-6">
            Secured platform access · All activity is monitored
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showError(msg) {
            const box = document.getElementById('errorBox');
            document.getElementById('errorMsg').textContent = msg;
            box.classList.remove('hidden');
            box.classList.add('shake');
            // Remove shake after animation
            setTimeout(() => box.classList.remove('shake'), 500);
        }

        function hideErrors() {
            document.getElementById('errorBox').classList.add('hidden');
            document.getElementById('successBox').classList.add('hidden');
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            hideErrors();

            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const arrow = document.getElementById('btnArrow');
            const spinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            text.textContent = 'Signing in...';
            arrow.style.display = 'none';
            spinner.style.display = 'inline-block';

            fetch('{{ route('platform.login.post') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    remember: document.getElementById('remember').checked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showError(data.message || 'An unknown error occurred.');
                }
            })
            .catch(() => {
                showError('Something went wrong. Please try again.');
            })
            .finally(() => {
                btn.disabled = false;
                text.textContent = 'Access Platform';
                arrow.style.display = '';
                spinner.style.display = 'none';
            });
        });
    </script>
</body>
</html>