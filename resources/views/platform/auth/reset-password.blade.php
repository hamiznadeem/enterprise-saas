<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — SwiftPOS</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }

        .login-bg {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 20% 80%, rgba(59, 130, 246, 0.06) 0px, transparent 50%),
                radial-gradient(at 80% 20%, rgba(6, 182, 212, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.03) 0px, transparent 70%);
        }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .card-shine {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.6) 100%);
            backdrop-filter: blur(20px);
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
        .btn-gradient:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
        .logo-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.4);
        }
        .float-animation { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .fade-in { animation: fadeIn 0.6s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .strength-bar { height: 5px; background: #e2e8f0; border-radius: 999px; margin-top: 0.5rem; overflow: hidden; }
        .strength-fill { height: 100%; width: 0; border-radius: 999px; transition: all 0.3s ease; }
        .strength-text { font-size: 0.6875rem; margin-top: 0.375rem; font-weight: 600; }
        .msg-box { border-radius: 0.75rem; padding: 0.75rem 1rem; margin-bottom: 1.5rem; display: none; font-size: 0.875rem; }
        .msg-box.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #166534; }
        .msg-box.error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .spinner {
            width: 1rem; height: 1rem;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } 
    </style>
</head>
<body class="login-bg min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute inset-0 grid-pattern"></div>
    <div class="absolute top-16 left-16 w-72 h-72 bg-blue-200/20 rounded-full blur-3xl float-animation"></div>
    <div class="absolute bottom-16 right-16 w-96 h-96 bg-cyan-200/20 rounded-full blur-3xl float-animation" style="animation-delay: -3s;"></div>

    <div class="relative w-full max-w-md fade-in">
        <div class="card-shine rounded-2xl border border-gray-200/80 shadow-xl shadow-gray-200/50 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-blue-500 via-blue-600 to-cyan-500"></div>
            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <div class="w-14 h-14 logo-gradient rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg">
                        <i class="fa-solid fa-lock text-white text-xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Reset Password</h1>
                    <p class="text-gray-500 text-sm mt-1.5">Enter your new password below</p>
                </div>

                <div id="msgBox" class="msg-box"></div>

                <form id="resetForm" class="space-y-5">
                    <input type="hidden" id="token" name="token" value="{{ $token }}">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" placeholder="admin@yoursaas.com" required
                                class="input-focus w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="password" name="password" placeholder="Min 8 characters" required minlength="8" oninput="checkStrength(this.value)"
                                class="input-focus w-full pl-11 pr-12 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="togglePw('password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-eye text-sm pw-icon"></i>
                            </button>
                        </div>
                        <div class="strength-bar"><div id="strengthFill" class="strength-fill"></div></div>
                        <div id="strengthText" class="strength-text"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password" required minlength="8"
                                class="input-focus w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="btn-gradient w-full py-3.5 text-white font-semibold rounded-xl text-sm flex items-center justify-center gap-2">
                        <span id="btnText">Reset Password</span>
                        <div id="btnSpinner" class="spinner"></div>
                    </button>
                </form>

                <div class="flex items-center gap-4 my-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">or</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <div class="text-center">
                    <a href="{{ route('platform.login') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 font-medium transition group">
                        <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                        <span>Back to login</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkStrength(pw) {
            let s = 0;
            if (pw.length >= 8) s++;
            if (/[a-z]/.test(pw)) s++;
            if (/[A-Z]/.test(pw)) s++;
            if (/[0-9]/.test(pw)) s++;
            if (/[^a-zA-Z0-9]/.test(pw)) s++;
            const colors = ['#ef4444','#f97316','#eab308','#22c55e','#16a34a'];
            const labels = ['Very Weak','Weak','Fair','Good','Strong'];
            document.getElementById('strengthFill').style.width = (s * 20) + '%';
            document.getElementById('strengthFill').style.background = colors[s];
            document.getElementById('strengthText').textContent = labels[s];
            document.getElementById('strengthText').style.color = colors[s];
        }

        function togglePw(id, btn) {
            const f = document.getElementById(id);
            const i = btn.querySelector('.pw-icon');
            if (f.type === 'password') { f.type = 'text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash'); }
            else { f.type = 'password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye'); }
        }

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const box = document.getElementById('msgBox');
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Resetting...';
            spinner.style.display = 'inline-block';
            box.style.display = 'none';

            fetch('{{ route('platform.password.update') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    token: document.getElementById('token').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    box.className = 'msg-box success';
                    box.textContent = data.message;
                    box.style.display = 'block';
                    setTimeout(() => window.location.href = data.redirect, 1500);
                } else {
                    box.className = 'msg-box error';
                    box.textContent = data.message;
                    box.style.display = 'block';
                }
            })
            .catch(() => { box.className = 'msg-box error'; box.textContent = 'Something went wrong.'; box.style.display = 'block'; })
            .finally(() => { btn.disabled = false; text.textContent = 'Reset Password'; spinner.style.display = 'none'; });
        });
    </script>
</body>
</html>