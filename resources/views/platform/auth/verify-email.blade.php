<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email — SwiftPOS</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap);
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
        .msg-box { border-radius: 0.75rem; padding: 0.75rem 1rem; margin-top: 1.5rem; display: none; font-size: 0.8125rem; white-space: pre-line; }
        .msg-box.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #166534; }
        .msg-box.error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .spinner {
            width: 1rem; height: 1rem;
            border: 2.5px solid rgba(59, 130, 246, 0.3);
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
            <div class="p-8 sm:p-10 text-center">
                <div class="w-16 h-16 logo-gradient rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg">
                    <i class="fa-solid fa-envelope-open text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Check Your Email</h1>
                <p class="text-gray-500 text-sm mt-2 leading-relaxed">We've sent a verification link to your email address.<br>Click the link to verify your account.</p>

                <button onclick="resend()" id="resendBtn" class="btn-gradient inline-flex items-center gap-2 px-8 py-3.5 text-white font-semibold rounded-xl text-sm mt-8">
                    <i id="resendIcon" class="fa-solid fa-rotate-right text-sm"></i>
                    <span id="resendText">Resend Verification Email</span>
                    <div id="resendSpinner" class="spinner"></div>
                </button>

                <div id="msgBox" class="msg-box"></div>

                <div class="flex items-center gap-4 mt-8">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">or</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <a href="{{ route('platform.login') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 font-medium transition group mt-4">
                    <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                    <span>Back to login</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function resend() {
            const box = document.getElementById('msgBox');
            const btn = document.getElementById('resendBtn');
            const text = document.getElementById('resendText');
            const icon = document.getElementById('resendIcon');
            const spinner = document.getElementById('resendSpinner');
            btn.disabled = true;
            text.textContent = 'Sending...';
            icon.style.display = 'none';
            spinner.style.display = 'inline-block';
            box.style.display = 'none';

            fetch('{{ route('platform.verification.send') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-XSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(r => r.json())
            .then(data => {
                box.className = 'msg-box ' + (data.success ? 'success' : 'error');
                box.textContent = data.message;
                if (data.url) box.textContent += '\n\nDev URL:\n' + data.url;
                box.style.display = 'block';
            })
            .catch(() => { box.className = 'msg-box error'; box.textContent = 'Something went wrong.'; box.style.display = 'block'; })
            .finally(() => {
                btn.disabled = false;
                text.textContent = 'Resend Verification Email';
                icon.style.display = '';
                spinner.style.display = 'none';
            });
        }
    </script>
</body>
</html>