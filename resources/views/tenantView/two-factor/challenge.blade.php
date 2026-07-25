<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>2FA Verification — SwiftPOS</title>
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
                <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-50 rounded-full">
                    <i class="fa-solid fa-shield-halved text-amber-500 text-2xl"></i>
                </div>
            </div>

            <h1 class="text-xl font-bold text-gray-900 text-center mb-2">Two-Factor Authentication</h1>
            <p class="text-sm text-gray-500 text-center mb-8">
                @if($user->two_factor_method === 'email')
                    Enter the 6-digit code sent to <strong class="text-gray-700">{{ $user->email }}</strong>
                @else
                    Enter the 6-digit code from your authenticator app
                @endif
            </p>

            @if(session('status'))
            <div class="mb-5 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">
                {{ session('status') }}
            </div>
            @endif

            @error('code')
            <div class="mb-5 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                {{ $message }}
            </div>
            @enderror

            @error('recovery_code')
            <div class="mb-5 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                {{ $message }}
            </div>
            @enderror

            <!-- Code Input Form -->
            <form method="POST" action="{{ route('two-factor.verify') }}" id="codeForm">
                @csrf
                <input type="hidden" name="is_recovery" value="0" id="isRecoveryHidden">

                <div id="codeSection">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input type="text" name="code" id="codeInput"
                        class="w-full px-4 py-3 text-center text-xl font-bold tracking-[0.5em] border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        placeholder="000000" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                        value="{{ old('code') }}" required>
                </div>

                <button type="submit" id="verifyBtn"
                    class="w-full mt-4 px-4 py-3 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition disabled:opacity-50">
                    Verify
                </button>
            </form>

            <!-- Resend (Email only) -->
            @if($user->two_factor_method === 'email')
            <div class="text-center mt-4">
                <form method="POST" action="{{ route('two-factor.resend') }}">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Didn't get the code? Resend
                    </button>
                </form>
            </div>
            @endif

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">OR</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Recovery Code -->
            <div id="recoverySection" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Recovery Code</label>
                <input type="text" name="recovery_code" id="recoveryInput"
                    class="w-full px-4 py-3 text-center text-sm font-mono uppercase tracking-wider border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                    placeholder="ABCD1234" maxlength="8">
            </div>

            <button type="button" id="toggleRecovery"
                class="w-full px-4 py-3 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition mt-1">
                <i class="fa-solid fa-key mr-2 text-xs"></i>Use Recovery Code
            </button>

        </div>

        <!-- Logout -->
        <div class="text-center mt-6">
            <form method="POST" action="{{ route('tenant.auth.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>Sign out
                </button>
            </form>
        </div>
    </div>

    <script>
        let isRecovery = false;
        const toggleBtn = document.getElementById('toggleRecovery');
        const codeSection = document.getElementById('codeSection');
        const recoverySection = document.getElementById('recoverySection');
        const isRecoveryHidden = document.getElementById('isRecoveryHidden');
        const codeForm = document.getElementById('codeForm');

        toggleBtn.addEventListener('click', function() {
            isRecovery = !isRecovery;
            if (isRecovery) {
                codeSection.classList.add('hidden');
                recoverySection.classList.remove('hidden');
                isRecoveryHidden.value = '1';
                toggleBtn.innerHTML = '<i class="fa-solid fa-arrow-left mr-2 text-xs"></i>Back to Code';
                toggleBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                toggleBtn.classList.add('bg-amber-50', 'text-amber-700', 'hover:bg-amber-100');
                document.getElementById('recoveryInput').focus();
            } else {
                codeSection.classList.remove('hidden');
                recoverySection.classList.add('hidden');
                isRecoveryHidden.value = '0';
                toggleBtn.innerHTML = '<i class="fa-solid fa-key mr-2 text-xs"></i>Use Recovery Code';
                toggleBtn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                toggleBtn.classList.remove('bg-amber-50', 'text-amber-700', 'hover:bg-amber-100');
                document.getElementById('codeInput').focus();
            }
        });

        // Handle recovery code submit
        codeForm.addEventListener('submit', function(e) {
            if (isRecovery) {
                e.preventDefault();
                const recoveryVal = document.getElementById('recoveryInput').value;
                // Create hidden input for recovery code
                let codeInput = codeForm.querySelector('input[name="code"]');
                codeInput.value = recoveryVal;
                codeForm.submit();
            }
        });

        // Numeric only for code input
        document.getElementById('codeInput').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>