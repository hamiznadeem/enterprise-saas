@extends('platform.layouts.app')

@section('header', 'Security')

@section('content')
<div class="relative">
    <!-- Background Pattern -->
    <div class="absolute inset-0 grid-pattern opacity-40 pointer-events-none" style="background-image:linear-gradient(rgba(148,163,184,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(148,163,184,0.06) 1px,transparent 1px);background-size:40px 40px;"></div>

    <div class="relative max-w-xl mx-auto py-12">
        <!-- Logo Icon -->
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-500 rounded-2xl shadow-lg">
                <i class="fa-solid fa-shield-halved text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight mt-4">Change Password</h1>
            <p class="text-gray-500 text-sm mt-1.5">Update your admin password. Must be different from your last 5 passwords.</p>
        </div>

        <div class="card-shine rounded-2xl border border-gray-200/80 shadow-xl shadow-gray-200/50 overflow-hidden fade-in">
            <div class="h-1.5 bg-gradient-to-r from-blue-500 via-blue-600 to-cyan-500"></div>

            <div class="p-8">
                <!-- Error Box -->
                <div id="msgBox" class="hidden rounded-xl p-4 mb-6 text-sm border"></div>

                <form id="changeForm" class="space-y-5">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="current_password" placeholder="Enter current password" required
                                class="w-full pl-11 pr-12 py-3 bg-white border border-gray-300 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="togglePw('current_password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-eye text-sm pw-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-key text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="new_password" name="password" placeholder="Min 8 characters" required minlength="8" oninput="checkStrength(this.value)"
                                class="w-full pl-11 pr-12 py-3 bg-white border border-gray-300 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                            <button type="button" onclick="togglePw('new_password', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-eye text-sm pw-icon"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div id="strengthFill" class="h-full rounded-full transition-all duration-300" style="width:0%;background:#ef4444;"></div>
                            </div>
                            <p id="strengthText" class="text-xs text-gray-400 mt-1.5 font-semibold"></p>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password" required minlength="8"
                                class="w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-blue-600/30 transition-all hover:-translate-y-0.5">
                        <span id="btnText">Update Password</span>
                        <div id="btnSpinner" class="spinner" style="display:none;"></div>
                    </button>
                </form>
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
            if (f.type === 'password') { f.type = 'text'; i.className = 'fa-solid fa-eye-slash pw-icon'; }
            else { f.type = 'password'; i.className = 'fa-solid fa-eye pw-icon'; }
        }

        document.getElementById('changeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const box = document.getElementById('msgBox');
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Updating...';
            spinner.style.display = 'inline-block';
            box.classList.add('hidden');

            fetch('{{ route('platform.password.update.post') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-XSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({
                    current_password: document.getElementById('current_password').value,
                    password: document.getElementById('new_password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                })
            })
            .then(r => r.json())
            .then(data => {
                box.classList.remove('hidden');
                if (data.success) {
                    box.className = 'rounded-xl p-4 mb-6 text-sm bg-emerald-50 text-emerald-700 border border-emerald-200';
                    box.textContent = data.message;
                    document.getElementById('changeForm').reset();
                    document.getElementById('strengthFill').style.width = '0%';
                    document.getElementById('strengthText').textContent = '';
                } else {
                    box.className = 'rounded-xl p-4 mb-6 text-sm bg-red-50 text-red-700 border border-red-200';
                    box.textContent = data.message;
                    if (data.strength) {
                        document.getElementById('strengthFill').style.width = (data.strength.score * 20) + '%';
                        document.getElementById('strengthFill').style.background = data.strength.color;
                        document.getElementById('strengthText').textContent = data.strength.label;
                        document.getElementById('strengthText').style.color = data.strength.color;
                    }
                }
            })
            .catch(() => {
                box.classList.remove('hidden');
                box.className = 'rounded-xl p-4 mb-6 text-sm bg-red-50 text-red-700 border border-red-200';
                box.textContent = 'Something went wrong.';
            })
            .finally(() => {
                btn.disabled = false;
                text.textContent = 'Update Password';
                spinner.style.display = 'none';
            });
        });
    </script>
@endsection