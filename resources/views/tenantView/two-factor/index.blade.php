<x-app-layout>
    <x-slot name="header">
        2FA Verification
    </x-slot>
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Two-Factor Authentication</h1>
            <p class="text-sm text-gray-500 mt-1">Add an extra layer of security to your account.</p>
        </div>

        @if(session('status'))
        <div class="mb-5 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
        @endif

        @if(!$user->two_factor_enabled)

            <!-- Disabled State: Two Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Email OTP -->
                <div class="bg-white rounded-xl border p-6 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-envelope text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Email OTP</h3>
                    <p class="text-sm text-gray-500 mb-5">Receive a 6-digit code via email every time you log in.</p>
                    <form method="POST" action="{{ route('two-factor.enable-email') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            Enable Email OTP
                        </button>
                    </form>
                </div>

                <!-- Authenticator App -->
                <div class="bg-white rounded-xl border p-6 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-mobile-screen text-purple-600 text-lg"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Authenticator App</h3>
                    <p class="text-sm text-gray-500 mb-5">Use Google Authenticator, Authy, or any TOTP app.</p>
                    <a href="{{ route('two-factor.setup-totp') }}"
                       class="block w-full px-4 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition text-center">
                        Set Up App
                    </a>
                </div>
            </div>

        @else

            <!-- Enabled State -->
            <div class="bg-white rounded-xl border p-6 mb-4">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-green-600"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-gray-900">2FA Enabled</h3>
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase">
                                    {{ $user->two_factor_method === 'email' ? 'Email OTP' : 'Authenticator' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">Your account is protected with two-factor authentication.</p>
                        </div>
                    </div>
                </div>

                <!-- Recovery Codes Section -->
                <div class="border-t pt-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700">Recovery Codes</h4>
                        <span class="text-xs text-gray-400">{{ count($recoveryCodes) }} remaining</span>
                    </div>

                    @if($showCodes && session('recovery_codes'))
                    <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-xs text-amber-700 font-semibold mb-3">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                            Save these codes now. You won't see them again!
                        </p>
                        <div class="grid grid-cols-2 gap-2 font-mono text-sm">
                            @foreach(session('recovery_codes') as $code)
                            <div class="px-3 py-2 bg-white rounded border text-gray-700">{{ $code }}</div>
                            @endforeach
                        </div>
                        <button onclick="copyCodes()" class="mt-3 text-xs text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fa-solid fa-copy mr-1"></i>Copy All Codes
                        </button>
                    </div>
                    @else
                    <p class="text-xs text-gray-500 mb-3">Use these if you lose access to your {{ $user->two_factor_method === 'email' ? 'email' : 'authenticator app' }}.</p>
                    @endif

                    <form method="POST" action="{{ route('two-factor.regenerate-codes') }}" class="inline">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="password" name="password" placeholder="Confirm password" required
                                class="px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none w-48">
                            <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                Regenerate
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Disable Section -->
                <div class="border-t pt-4 mt-4">
                    <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Are you sure? This will make your account less secure.')">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="password" name="password" placeholder="Confirm password to disable" required
                                class="px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none w-56">
                            <button type="submit" class="px-3 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                                Disable 2FA
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        @endif

    </div>
</div>

<script>
function copyCodes() {
    const codes = @json(session('recovery_codes', []));
    navigator.clipboard.writeText(codes.join('\n')).then(() => {
        alert('Codes copied to clipboard!');
    });
}
</script>
</x-app-layout>