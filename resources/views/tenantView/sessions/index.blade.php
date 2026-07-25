<x-app-layout>
    <x-slot name="header">
        Sessions
    </x-slot>
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Active Sessions</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your logged-in devices.</p>
            </div>
            <form method="POST" action="{{ route('tenant.sessions.kill-all') }}" onsubmit="return confirm('Logout from all other devices?')">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition flex items-center gap-2">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    Logout All Others
                </button>
            </form>
        </div>

        @if(session('status'))
        <div class="mb-5 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden divide-y">
            @foreach($sessions as $session)
            <div class="flex items-center gap-4 p-4 hover:bg-gray-50 transition {{ $session['is_current'] ? 'bg-blue-50/50' : '' }}">
                
                <!-- Device Icon -->
                <div class="w-10 h-10 rounded-lg {{ $session['is_current'] ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid {{ $session['device_icon'] }} text-sm"></i>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900 capitalize">{{ $session['browser'] }}</p>
                        @if($session['version'])
                            <span class="text-xs text-gray-400">v{{ $session['browser_ver'] }}</span>
                        @endif
                        @if($session['is_current'])
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase">This Device</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <span class="capitalize">{{ $session['os'] }}</span>
                        <span class="mx-1">·</span>
                        <span>{{ $session['device_type'] }}</span>
                        <span class="mx-1">·</span>
                        <span class="font-mono">{{ $session['ip'] }}</span>
                    </p>
                </div>

                <!-- Time + Action -->
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-400 mb-1">{{ $session['last_activity'] }}</p>
                    @unless($session['is_current'])
                    <button onclick="killSession('{{ $session['id'] }}')" 
                            class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                        Terminate
                    </button>
                    @endunless
                </div>
            </div>
            @endforeach

            @if($sessions->isEmpty())
            <div class="p-8 text-center text-gray-500 text-sm">No active sessions found.</div>
            @endif
        </div>
    </div>
</div>

<script>
function killSession(id) {
    if (!confirm('Terminate this session?')) return;

    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Terminating...';
    btn.disabled = true;

    fetch(`/sessions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            btn.textContent = originalText;
            btn.disabled = false;
            alert(data.message || 'Error');
        }
    })
    .catch(() => {
        btn.textContent = originalText;
        btn.disabled = false;
    });
}
</script>

</x-app-layout>