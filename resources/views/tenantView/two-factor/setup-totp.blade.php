<x-app-layout>
    <x-slot name="header">
        Set Up Authenticator App
    </x-slot>
<div class="py-6">
    <div class="max-w-lg mx-auto px-4">

        <a href="{{ route('two-factor.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 mb-6">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to 2FA Settings
        </a>

        <div class="bg-white rounded-xl border p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Set Up Authenticator App</h2>
            <p class="text-sm text-gray-500 mb-6">Scan this QR code with your authenticator app.</p>

            <!-- QR Code -->
            <div class="flex justify-center mb-6">
                <img src="{{ $qrUrl }}" alt="QR Code" class="w-52 h-52 border rounded-lg p-2">
            </div>

            <!-- Manual Key -->
            <div class="mb-6">
                <p class="text-xs text-gray-500 mb-2">If you can't scan, enter this key manually:</p>
                <div class="flex items-center gap-2">
                    <code class="flex-1 px-3 py-2 bg-gray-100 rounded-lg text-sm font-mono text-gray-700 select-all">{{ $secret }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $secret }}')" class="px-3 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition" title="Copy">
                        <i class="fa-solid fa-copy text-gray-500 text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Verify Code -->
            <form method="POST" action="{{ route('two-factor.enable-totp') }}">
                @csrf
                @error('code')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ $message }}
                </div>
                @enderror

                @error('error')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ $message }}
                </div>
                @enderror

                <label class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                <div class="flex gap-3">
                    <input type="text" name="code" placeholder="000000" maxlength="6" inputmode="numeric" pattern="[0-9]*" required
                        class="flex-1 px-4 py-3 text-center text-xl font-bold tracking-[0.5em] border rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition">
                        Verify & Enable
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
</x-app-layout>