@extends('platform.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Active Sessions</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your active login sessions across devices.</p>
        </div>
        <button onclick="killAll()" id="killAllBtn" class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition flex items-center gap-2">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout All Devices
        </button>
    </div>

    <div id="msgBox" class="hidden rounded-xl p-4 mb-6 text-sm"></div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if(count($sessions) === 0)
            <div class="p-12 text-center">
                <i class="fa-solid fa-desktop text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">No active sessions found.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($sessions as $session)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid {{ $session['device_type'] === 'mobile' ? 'fa-mobile-screen' : ($session['device_type'] === 'tablet' ? 'fa-tablet-screen-button' : 'fa-desktop') }} text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $session['browser'] }} on {{ $session['os'] }}
                                    @if($session['is_current'])
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Current</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">
                                    IP: {{ $session['ip'] }} · {{ $session['last_activity'] }}
                                </p>
                            </div>
                        </div>
                        @if(!$session['is_current'])
                            <button onclick="killSession('{{ $session['id'] }}')" class="text-xs text-red-500 hover:text-red-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-red-50 transition">Revoke</button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
function killSession(id) {
    fetch(`/super-admin/sessions/${id}`, {
        method: 'DELETE',
        headers: {'Accept':'application/json','X-XSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
    })
    .then(r=>r.json())
    .then(data => { if(data.success) location.reload(); })
    .catch(()=>{});
}

function killAll() {
    const box = document.getElementById('msgBox');
    const btn = document.getElementById('killAllBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Logging out...';
    box.classList.add('hidden');

    fetch('/super-admin/sessions/kill-all', {
        method: 'POST',
        headers: {'Accept':'application/json','X-XSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}
    })
    .then(r=>r.json())
    .then(data => {
        box.classList.remove('hidden');
        box.className = 'rounded-xl p-4 mb-6 text-sm bg-emerald-50 text-emerald-700 border border-emerald-200';
        box.textContent = data.message;
        setTimeout(() => location.reload(), 1500);
    })
    .catch(() => {
        box.classList.remove('hidden');
        box.className = 'rounded-xl p-4 mb-6 text-sm bg-red-50 text-red-700 border border-red-200';
        box.textContent = 'Failed to logout other devices.';
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-right-from-bracket"></i> Logout All Devices';
    });
}
</script>
@endsection