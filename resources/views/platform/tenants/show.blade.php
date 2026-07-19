@extends('platform.layouts.app')

@section('header', 'Manage: ' . $tenant->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left: Info & Actions -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Tenant Info Card -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-700 text-xl font-bold">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $tenant->name }}</h3>
                    <p class="text-sm text-gray-400">{{ $tenant->domain }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Owner:</span><span class="font-medium">{{ $tenant->owner_name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Email:</span><span class="font-medium text-indigo-600">{{ $tenant->owner_email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status:</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold 
                        @if($tenant->status === 'active') bg-emerald-50 text-emerald-700 
                        @elseif($tenant->status === 'suspended') bg-red-50 text-red-700 
                        @elseif($tenant->status === 'expired') bg-red-100 text-red-800
                        @else bg-amber-50 text-amber-700 @endif">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Subscription Management -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h4 class="font-bold text-gray-900 mb-4">Subscription Management</h4>
            
            <div class="mb-4 p-4 rounded-lg border bg-gray-50">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-500">Current Plan:</span>
                    <span class="text-sm font-bold text-gray-900">{{ $tenant->plan->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-500">Billing Cycle:</span>
                    <span class="text-sm font-bold uppercase text-gray-900">{{ $tenant->plan->billing_cycle ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Expiry Date:</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-{{ $tenant->will_expire_at && $tenant->will_expire_at->isPast() ? 'red-600' : 'gray-900' }}">
                            {{ $tenant->will_expire_at ? $tenant->will_expire_at->format('M d, Y') : 'N/A' }}
                        </span>
                        @if($tenant->will_expire_at)
                            @if($tenant->will_expire_at->isPast())
                                <span class="block text-xs text-red-600 font-bold">Expired</span>
                            @elseif($tenant->will_expire_at->gt(now()) && $tenant->will_expire_at->lt(now()->addDays(8)))
                                <span class="block text-xs text-amber-600 font-bold">Expiring Soon</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Renew Button (Sirf expiry near ho ya past ho toh dikhe) -->
            @if($tenant->will_expire_at && ($tenant->will_expire_at->isPast() || ($tenant->will_expire_at->gt(now()) && $tenant->will_expire_at->lt(now()->addDays(8)))))
            <button onclick="renewTenant({{ $tenant->id }})" class="w-full bg-emerald-600 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-emerald-700 transition mb-3 flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> Renew Subscription Now
            </button>
            @endif

            <!-- Change Plan (Upgrade/Downgrade) -->
            <form id="changePlanForm" class="mb-4" action="{{ route('platform.tenants.update', $tenant) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $tenant->name }}">
                <input type="hidden" name="domain" value="{{ $tenant->domain }}">
                <input type="hidden" name="owner_name" value="{{ $tenant->owner_name }}">
                <input type="hidden" name="owner_email" value="{{ $tenant->owner_email }}">

                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Change Plan</label>
                <div class="flex gap-2">
                    <select name="plan_id" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white outline-none">
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ $tenant->plan_id == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ ucfirst($plan->billing_cycle) }})
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-indigo-700">Update</button>
                </div>
                <p class="text-xs text-gray-400 mt-1">*Changing plan will reset the billing cycle.</p>
            </form>

            <!-- Manual Payment Form (Offline Record) -->
            <form id="paymentForm" class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                <p class="text-xs font-bold text-emerald-700 mb-2">Log Manual Payment (Offline)</p>
                <input type="number" step="0.01" name="amount" placeholder="Amount ($)" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm mb-2" required>
                <input type="text" name="notes" placeholder="Notes (Optional)" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm mb-2">
                <button type="submit" class="w-full bg-emerald-600 text-white text-sm font-medium py-1.5 rounded hover:bg-emerald-700 transition">Record Payment & Extend</button>
            </form>

            @if($tenant->status !== 'suspended')
            <button onclick="toggleStatus({{ $tenant->id }})" class="w-full mt-3 bg-red-50 text-red-600 text-sm font-medium py-2.5 rounded-lg hover:bg-red-100 transition">
                <i class="fa-solid fa-ban"></i> Suspend Tenant
            </button>
            @else
            <button onclick="toggleStatus({{ $tenant->id }})" class="w-full mt-3 bg-emerald-50 text-emerald-600 text-sm font-medium py-2.5 rounded-lg hover:bg-emerald-100 transition">
                <i class="fa-solid fa-play"></i> Activate Tenant
            </button>
            @endif
        </div>
    </div>

    <!-- Right: Module Controls & History -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Module Controls -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-puzzle-piece text-purple-600"></i> Module Access Control
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="modulesGrid">
                @php
                    $mods = $tenant->enabled_modules ?? [];
                    $defaultModules = [
                        'pos'         => ['label' => 'POS', 'icon' => 'fa-cash-register'],
                        'inventory'   => ['label' => 'Inventory', 'icon' => 'fa-boxes-stacked'],
                        'purchases'   => ['label' => 'Purchases', 'icon' => 'fa-cart-shopping'],
                        'accounts'    => ['label' => 'Accounts', 'icon' => 'fa-calculator'],
                        'reports'     => ['label' => 'Reports', 'icon' => 'fa-chart-pie'],
                        'restaurant'  => ['label' => 'Restaurant', 'icon' => 'fa-utensils'],
                        'clinic'      => ['label' => 'Clinic', 'icon' => 'fa-stethoscope'],
                        'pharmacy'    => ['label' => 'Pharmacy', 'icon' => 'fa-pills'],
                        'hr'          => ['label' => 'HR', 'icon' => 'fa-users-gear'],
                        'crm'         => ['label' => 'CRM', 'icon' => 'fa-handshake'],
                        'payroll'     => ['label' => 'Payroll', 'icon' => 'fa-money-check-dollar'],
                    ];
                @endphp
                @foreach($defaultModules as $key => $mod)
                @php $isActive = isset($mods[$key]) && $mods[$key]; @endphp
                <div class="p-4 border rounded-xl flex flex-col items-center justify-center text-center transition hover:shadow-md {{ $isActive ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-200 opacity-60' }}" id="mod-{{ $key }}">
                    <i class="fa-solid {{ $mod['icon'] }} text-2xl mb-2 {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                    <span class="text-sm font-semibold {{ $isActive ? 'text-indigo-800' : 'text-gray-500' }}">{{ $mod['label'] }}</span>
                    <button type="button" onclick="toggleModule('{{ $key }}')" class="mt-2 relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors {{ $isActive ? 'bg-indigo-600' : 'bg-gray-300' }}">
                        <span class="inline-block w-5 h-5 transform rounded-full bg-white shadow transition-transform {{ $isActive ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Subscription History -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h4 class="font-bold text-gray-900 mb-4">Subscription & Payment History</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tenant->subscriptions->sortByDesc('created_at') as $log)
                        <tr>
                            <td class="px-4 py-3 text-gray-600">{{ $log->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold 
                                    @if($log->type === 'trial_extend') bg-amber-50 text-amber-700 
                                    @else bg-emerald-50 text-emerald-700 @endif">
                                    {{ str_replace('_', ' ', ucfirst($log->type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium">${{ number_format($log->amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-400">{{ $log->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">No history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Back Button -->
<div class="mt-6">
    <a href="{{ route('platform.tenants.index') }}" class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1">
        <i class="fa-solid fa-arrow-left"></i> Back to Tenants List
    </a>
</div>

<script>
    // Module Toggle (AJAX)
    function toggleModule(modName) {
        fetch(`{{ route('platform.tenants.toggle-module', $tenant) }}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ module: modName })
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); })
    }

    // Renew Function
    function renewTenant(id) {
        if(!confirm('Are you sure you want to renew this subscription based on the current plan?')) return;
        fetch(`{{ route("tenants.renew", 0) }}`.replace('/0', '/' + id), {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }

    // Toggle Status Function
    function toggleStatus(id) {
        fetch(`{{ route("platform.tenants.toggle-status", 0) }}`.replace('/0', '/' + id), {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }

    // Change Plan Form (AJAX)
    document.getElementById('changePlanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message || 'Error'); });
    });

    // Payment Form (AJAX)
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('type', 'payment');
        
        fetch(`{{ route('platform.tenants.subscription-log', $tenant) }}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    });
</script>
@endsection