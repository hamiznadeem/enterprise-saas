@extends('platform.layouts.app')

@section('header', 'Global Settings')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Global Settings</h2>
    <p class="text-sm text-gray-500 mt-1">Manage platform appearance, emails, SMS, and system preferences.</p>
</div>

<!-- Tabs Navigation -->
<div class="mb-6 flex flex-wrap gap-2 border-b border-gray-200 pb-3">
    <button onclick="switchTab('general')" id="tab-general" class="px-4 py-2.5 text-sm font-medium border-b-2 border-purple-600 text-purple-600 transition">General</button>
    <button onclick="switchTab('branding')" id="tab-branding" class="px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">Branding</button>
    <button onclick="switchTab('smtp')" id="tab-smtp" class="px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">Email / SMTP</button>
    <button onclick="switchTab('sms')" id="tab-sms" class="px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">SMS Gateway</button>
    <button onclick="switchTab('password')" id="tab-password" class="px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">Change Password</button>
    <button onclick="switchTab('system')" id="tab-system" class="px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-800 transition">System</button>
</div>

<form method="POST" action="{{ route('platform.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
    @csrf
    
    <!-- GENERAL TAB -->
    <div id="panel-general" class="max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Basic Info</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Platform Name *</label>
                <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'SaaS Control Center' }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Default Currency *</label>
                <select name="currency" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="USD" {{ ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                    <option value="EUR" {{ ($settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    <option value="PKR" {{ ($settings['currency'] ?? '') === 'PKR' ? 'selected' : '' }}>PKR (Rs)</option>
                    <option value="AED" {{ ($settings['currency'] ?? '') === 'AED' ? 'selected' : '' }}>AED (د.إ)</option>
                    <option value="GBP" {{ ($settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Default Language</label>
                <select name="default_language" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="en" {{ ($settings['default_language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ur" {{ ($settings['default_language'] ?? '') === 'ur' ? 'selected' : '' }}>اردو</option>
                    <option value="ar" {{ ($settings['default_language'] ?? '') === 'ar' ? 'selected' : '' }}>العربية</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time Zone</label>
                <select name="timezone" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="">Select Timezone...</option>
                    @foreach($timezones as $tz)
                        <option value="{{ $tz }}" {{ $tz }} @if(($settings['timezone'] ?? 'UTC') === $tz) selected @endif></option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- BRANDING TAB (Hidden) -->
    <div id="panel-branding" class="hidden max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
            <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Logo & Favicon</h3>
            
            <div>
                <label class="block text-sm font-80 text-gray-700 mb-2">Platform Logo</label>
                <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:border file:border-gray-300 rounded-lg cursor-pointer">
                @if(!empty($settings['logo']))
                    <p class="text-xs text-emerald-600 mt-1">Current: {{ $settings['logo'] }} (Leave blank to keep current)</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                <input type="file" name="favicon" accept="image/png,image/x-icon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:border file:border-gray-300 rounded-lg cursor-pointer">
                @if(!empty($settings['favicon']))
                    <p class="text-xs text-emerald-600 mt-1">Current: {{ $settings['favicon'] }} (Leave blank to keep current)</p>
                @endif
            </div>
        </div>
    </div>

    <!-- SMTP TAB (Hidden) -->
    <div id="panel-smtp" class="hidden max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">SMTP Configuration (Email)</h3>
            <p class="text-xs text-gray-400 mb-6">Used to send system emails, receipts, and notifications.</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}" placeholder="smtp.gmail.com" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                    <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] ?? '587' }}" placeholder="587" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}" placeholder="your@gmail.com" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="smtp_password" value="{{ $settings['smtp_password'] ?? '' }}" placeholder="••••••••" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                    <select name="smtp_encryption" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:ring-purple-500 focus:border-purple-500 outline-none">
                        <option value="" {{ empty($settings['smtp_encryption']) ? 'selected' : '' }}>Select Encryption</option>
                        <option value="tls" {{ ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                <input type="email" name="smtp_from_address" value="{{ $settings['smtp_from_address'] ?? 'noreply@yoursaas.com' }}" placeholder="noreply@yoursaas.com" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">This email will appear as the "From" address in sent emails.</p>
            </div>
        </div>
    </div>

    <!-- SMS TAB (Hidden) -->
    <div id="panel-sms" class="hidden max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">SMS Gateway</h3>
            <p class="text-xs text-gray-400 mb-6">Connect an SMS provider for OTP and notifications.</p>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMS Provider</label>
                    <select name="sms_provider" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm bg-white focus:ring-purple-500 focus:border-purple-500 outline-none">
                        <option value="">Select Provider</option>
                        <option value="twilio" {{ ($settings['sms_provider'] ?? '') === 'twilio' ? 'selected' : '' }}>Twilio</option>
                        <option value="sms broadcasts" {{ ($settings['sms_provider'] ?? '') === 'sms broadcasts' ? 'selected' : '' }}>SMS Broadcasts</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                    <input type="password" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" placeholder="Enter API Key" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sender ID / Phone</label>
                    <input type="text" name="sms_sender" value="{{ $settings['sms_sender'] ?? '' }}" placeholder="+1234567890" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Phone number registered with your SMS provider.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PASSWORD TAB (Hidden) -->
    <div id="panel-password" class="hidden max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Change Password</h3>
            <p class="text-xs text-gray-400 mb-6">Update your admin password. Must be different from your last 5 passwords.</p>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input type="password" id="cp" placeholder="Enter current password" required
                            class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-key text-gray-400 text-sm"></i>
                        </div>
                        <input type="password" id="np" placeholder="Min 8 characters" required minlength="8" oninput="checkStrengthLocal(this.value)"
                            class="w-full pl-11 pr-12 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 transition">
                            <button type="button" onclick="togglePwLocal('np', this)" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-solid fa-eye text-sm pw-icon"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div id="localStrengthFill" class="h-full rounded-full transition-all duration-300" style="width:0%;background:#ef4444;"></div>
                            </div>
                            <p id="localStrengthText" class="text-xs text-gray-400 mt-1.5 font-semibold"></p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input type="password" id="cnp" placeholder="Re-enter new password" required minlength="8"
                            class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 transition">
                        </div>
                    </div>

                <!-- Submit -->
                <button type="button" onclick="changePwLocal()" id="cpBtn" class="w-full py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg text-sm hover:shadow-lg hover:shadow-purple-600/30 transition-all hover:-translate-y-0.5">
                    <span id="cpBtnText">Update Password</span>
                    <div id="cpSpinner" class="spinner" style="display:none;"></div>
                </button>
            </div>
        </div>

    <!-- SYSTEM TAB (Hidden) -->
    <div id="panel-system" class="hidden max-w-3xl">
        <div class="bg-white rounded-xl border border-red-200 p-6 space-y-5">
            <h3 class="text-base font-bold text-red-600 border-b border-red-100 pb-3 mb-4">Maintenance Mode</h3>
            <p class="text-xs text-red-400 mb-6">⚠️ <strong>Warning:</strong> Enabling this will block all tenants from accessing the system and show them a maintenance message.</p>
            
            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                <div>
                    <p class="text-sm font-semibold text-red-800">Enable Maintenance Mode</p>
                    <p class="text-xs text-red-500 mt-0.5">Users will see a custom message below.</p>
                </div>
                <label class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'bg-red-500' : 'bg-gray-300' }}">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-5 h-5 transform rounded-full bg-white shadow transform -translate-x-0.5 {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'translate-x-5' : '' }} transition-transform duration-200"></div>
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                <textarea name="maintenance_message" rows="4" placeholder="We are doing some maintenance. Please try again in 10 minutes." class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none resize-none">{{ $settings['maintenance_message'] ?? 'We are doing some maintenance. Please try again in 10 minutes.' }}</textarea>
            </div>
        </div>
    </div>

    <!-- Save Button (Fixed at bottom) -->
    <div class="mt-8 flex justify-end sticky bottom-0 bg-white border-t border-gray-200 pt-4 pb-20">
        <button type="submit" class="px-8 py-3 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i> Save All Settings
        </button>
    </div>
</form>

<script>
    function switchTab(tabName) {
        // Sab panels hide karo
        document.querySelectorAll('[id^="panel-"]').forEach(p => p.classList.add('hidden'));
        
        // Clicked tab ko dikhao
        document.getElementById('panel-' + tabName).classList.remove('hidden');
        
        // Tabs ki active state change karo
        document.querySelectorAll('[id^="tab-"]').forEach(t => {
            t.classList.remove('border-purple-600', 'text-purple-600');
            t.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-' + tabName).classList.add('border-purple-600', 'text-purple-600');
    }

    // Page load par General tab active ho
    switchTab('general');
</script>

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