@extends('platform.layouts.app')

@section('header', 'Tenant Management')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">All Tenants</h2>
        <p class="text-sm text-gray-500 mt-1">Manage registered businesses on your platform.</p>
    </div>
    <button onclick="openModal()" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Register Tenant
    </button>
</div>

<!-- Tenants Table -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    
    <!-- Filters Bar -->
    <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row gap-3">
        <input type="text" id="searchInput" placeholder="Search by name or domain..." class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 outline-none">
        
        <select id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-blue-500 focus:border-blue-500 outline-none">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="trial">Trial</option>
            <option value="suspended">Suspended</option>
            <option value="expired">Expired</option>
        </select>

        <select id="planFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:ring-blue-500 focus:border-blue-500 outline-none">
            <option value="">All Plans</option>
            @foreach($plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Business</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Plan</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Expiry Date</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tenantsTableBody">
                @forelse($tenants as $tenant)
                <tr class="hover:bg-gray-50 transition tenant-row" data-id="{{ $tenant->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold">
                                {{ strtoupper(substr($tenant->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('platform.tenants.show', $tenant) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition">{{ $tenant->name }}</a>
                                <p class="text-xs text-gray-400">{{ $tenant->domain }} · {{ $tenant->owner_email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $tenant->plan ? $tenant->plan->name : 'N/A' }}
                        @if($tenant->plan)
                            <span class="block text-xs text-gray-400 capitalize">{{ $tenant->plan->billing_cycle }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($tenant->status === 'active') bg-emerald-50 text-emerald-700 
                            @elseif($tenant->status === 'suspended') bg-red-50 text-red-700 
                            @elseif($tenant->status === 'expired') bg-red-100 text-red-800 font-bold 
                            @else bg-amber-50 text-amber-700 @endif">
                            @if($tenant->status === 'expired')<i class="fa-solid fa-lock text-[10px] mr-1"></i> @endif
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($tenant->will_expire_at)
                            <span class="text-gray-600">{{ $tenant->will_expire_at->format('M d, Y') }}</span>
                            
                            @if($tenant->will_expire_at->isPast())
                                <span class="block text-xs text-red-600 font-bold">Expired</span>
                            @elseif($tenant->will_expire_at->gt(now()) && $tenant->will_expire_at->lt(now()->addDays(8)))
                                <span class="block text-xs text-amber-600 font-bold">Expiring Soon</span>
                            @endif
                            
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1 justify-end">
                            
                            @if($tenant->will_expire_at && ($tenant->will_expire_at->isPast() || ($tenant->will_expire_at->gt(now()) && $tenant->will_expire_at->lt(now()->addDays(8)))))
                            <button onclick="renewTenant({{ $tenant->id }})" class="p-1.5 rounded-lg hover:bg-emerald-50 text-emerald-600 transition" title="Renew Subscription">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                            @endif

                            <a href="{{ route('platform.tenants.show', $tenant) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            
                            <button onclick='openEditModal(@json($tenant))' class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition" title="Edit/Upgrade">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            <button onclick="toggleStatus({{ $tenant->id }})" class="p-1.5 rounded-lg hover:bg-amber-50 text-gray-400 hover:text-amber-600 transition" title="{{ $tenant->is_active ? 'Suspend' : 'Activate' }}">
                                <i class="fa-solid fa-{{ $tenant->is_active ? 'pause' : 'play' }}"></i>
                            </button>
                            
                            <button onclick="deleteTenant({{ $tenant->id }})" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-building-circle-xmark text-4xl mb-3 block"></i>
                        No tenants registered yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Register/Edit Modal -->
<div id="tenantModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 id="modalTitle" class="text-lg font-bold">Register New Tenant</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="tenantForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" value="POST" id="methodField">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Business Name *</label>
                <input type="text" name="name" id="t_name" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domain / Subdomain *</label>
                <div class="flex">
                    <input type="text" name="domain" id="t_domain" required class="rounded-l-lg border border-r-0 border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none flex-1" placeholder="">
                    <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg px-4 py-2.5 text-sm text-gray-500 whitespace-nowrap">.yoursaas.com</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Owner Name *</label>
                    <input type="text" name="owner_name" id="t_owner" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Owner Email *</label>
                    <input type="email" name="owner_email" id="t_email" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" id="t_phone" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="t_city" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="t_location" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Plan *</label>
                <select name="plan_id" id="t_plan" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm bg-white focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Select Plan</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ ucfirst($plan->billing_cycle) }}) - ${{ number_format($plan->price, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" id="submitBtn" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <span id="submitText">Save Tenant</span>
                    <svg id="submitSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ CREDENTIALS MODAL -->
<div id="credentialsModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Tenant Created!</h3>
                    <p class="text-blue-100 text-xs">Share these credentials with the owner</p>
                </div>
            </div>
        </div>

        <!-- Tenant Info -->
        <div class="px-6 pt-5 pb-4 border-b border-gray-100">
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Business</p>
                    <p id="credTenant" class="text-gray-900 font-semibold mt-0.5">—</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Domain</p>
                    <p id="credDomain" class="text-gray-900 font-semibold mt-0.5">—</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Plan</p>
                    <p id="credPlan" class="text-gray-900 font-semibold mt-0.5">—</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Trial Ends</p>
                    <p id="credTrial" class="text-gray-900 font-semibold mt-0.5">—</p>
                </div>
            </div>
        </div>

        <!-- Credentials Box -->
        <div class="px-6 py-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Owner Login Credentials</p>
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-envelope text-gray-400 w-4 text-center"></i>
                        <span class="text-xs text-gray-500 font-medium w-12">Email</span>
                    </div>
                    <p id="credEmail" class="text-sm font-mono font-semibold text-gray-900 bg-white px-3 py-1.5 rounded-lg border border-gray-200 select-all">—</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-lock text-gray-400 w-4 text-center"></i>
                        <span class="text-xs text-gray-500 font-medium w-12">Password</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <p id="credPassword" class="text-sm font-mono font-semibold text-gray-900 bg-white px-3 py-1.5 rounded-lg border border-gray-200 select-all">—</p>
                        <button id="copyPwBtn" onclick="copyPassword()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-600 hover:bg-blue-50 transition">
                            <i class="fa-regular fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex items-start gap-2 p-3 bg-amber-50 rounded-lg border border-amber-200">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 shrink-0"></i>
                <p class="text-xs text-amber-700">Save these credentials now. This password won't be shown again. The owner can reset it from their profile page.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="closeCredentials()" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                Done
            </button>
        </div>
    </div>
</div>

<script>
    let editId = null;

    function fetchTenants() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const planId = document.getElementById('planFilter').value;
        
        if(search.length > 0 && search.length < 2) return;

        const params = new URLSearchParams();
        if(search) params.append('search', search);
        if(status) params.append('status', status);
        if(planId) params.append('plan_id', planId);

        fetch(`{{ route('platform.tenants.index') }}?${params.toString()}`, { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        })
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('tenantsTableBody');
            if(!data.length) { tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-gray-400">No results found</td></tr>'; return; }
            
            tbody.innerHTML = data.map(t => {
                let expiryHtml = '<span class="text-gray-400">N/A</span>';
                let renewBtn = '';
                
                if (t.will_expire_at) {
                    const dateObj = new Date(t.will_expire_at);
                    const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                    const now = new Date();
                    const nextWeek = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
                    
                    let badge = '';
                    if (dateObj < now) {
                        badge = '<span class="block text-xs text-red-600 font-bold">Expired</span>';
                        renewBtn = `<button onclick="renewTenant(${t.id})" class="p-1.5 rounded-lg hover:bg-emerald-50 text-emerald-600 transition" title="Renew"><i class="fa-solid fa-rotate-right"></i></button>`;
                    } else if (dateObj >= now && dateObj < nextWeek) {
                        badge = '<span class="block text-xs text-amber-600 font-bold">Expiring Soon</span>';
                        renewBtn = `<button onclick="renewTenant(${t.id})" class="p-1.5 rounded-lg hover:bg-emerald-50 text-emerald-600 transition" title="Renew"><i class="fa-solid fa-rotate-right"></i></button>`;
                    }
                    
                    expiryHtml = `<span class="text-gray-600">${formattedDate}</span>${badge}`;
                }

                let statusClass = 'bg-amber-50 text-amber-700';
                if(t.status==='active') statusClass = 'bg-emerald-50 text-emerald-700';
                else if(t.status==='suspended') statusClass = 'bg-red-50 text-red-700';
                else if(t.status==='expired') statusClass = 'bg-red-100 text-red-800 font-bold';

                return `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold">${t.name.charAt(0).toUpperCase()}</div>
                            <div>
                                <a href="/super-admin/tenants/${t.id}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition">${t.name}</a>
                                <p class="text-xs text-gray-400">${t.domain}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        ${t.plan ? t.plan.name : 'N/A'}
                        ${t.plan ? `<span class="block text-xs text-gray-400 capitalize">${t.plan.billing_cycle}</span>` : ''}
                    </td>
                    <td class="px-6 py-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">${t.status.charAt(0).toUpperCase() + t.status.slice(1)}</span></td>
                    <td class="px-6 py-4 text-sm">${expiryHtml}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1 justify-end">
                            ${renewBtn}
                            <a href="/super-admin/tenants/${t.id}" class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition" title="View"><i class="fa-solid fa-eye"></i></a>
                            <button onclick='openEditModal(${JSON.stringify(t)})' class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="toggleStatus(${t.id})" class="p-1.5 rounded-lg hover:bg-amber-50 text-gray-400 hover:text-amber-600 transition" title="Suspend/Activate"><i class="fa-solid fa-${t.is_active ? 'pause' : 'play'}"></i></button>
                            <button onclick="deleteTenant(${t.id})" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        });
    }

    document.getElementById('searchInput').addEventListener('input', fetchTenants);
    document.getElementById('statusFilter').addEventListener('change', fetchTenants);
    document.getElementById('planFilter').addEventListener('change', fetchTenants);

    function openModal() {
        editId = null;
        document.getElementById('modalTitle').innerText = 'Register New Tenant';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('tenantForm').action = '{{ route("platform.tenants.store") }}';
        document.getElementById('tenantForm').reset();
        toggleModal(true);
    }

    function openEditModal(tenant) {
        editId = tenant.id;
        document.getElementById('modalTitle').innerText = 'Edit Tenant';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('tenantForm').action = '{{ route("platform.tenants.update", 0) }}'.replace('/0', '/' + tenant.id);
        
        document.getElementById('t_name').value = tenant.name;
        document.getElementById('t_domain').value = tenant.domain;
        document.getElementById('t_owner').value = tenant.owner_name;
        document.getElementById('t_email').value = tenant.owner_email;
        document.getElementById('t_plan').value = tenant.plan_id;
        document.getElementById('t_phone').value = tenant.phone || '';
        document.getElementById('t_city').value = tenant.city || '';
        document.getElementById('t_location').value = tenant.location || '';
        
        toggleModal(true);
    }

    function closeModal() { toggleModal(false); }

    function toggleModal(show) {
        const modal = document.getElementById('tenantModal');
        if(show) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        else { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    //CREDENTIALS MODAL FUNCTIONS
    function showCredentials(credentials, tenant) {
        document.getElementById('credEmail').innerText = credentials.email;
        document.getElementById('credPassword').innerText = credentials.password;
        document.getElementById('credTenant').innerText = tenant.name;
        document.getElementById('credDomain').innerText = tenant.domain;
        document.getElementById('credPlan').innerText = tenant.plan;
        document.getElementById('credTrial').innerText = tenant.trial_ends;
        
        const credModal = document.getElementById('credentialsModal');
        credModal.classList.remove('hidden');
        credModal.classList.add('flex');
    }

    function closeCredentials() {
        const credModal = document.getElementById('credentialsModal');
        credModal.classList.add('hidden');
        credModal.classList.remove('flex');
        location.reload();
    }

    function copyPassword() {
        const pw = document.getElementById('credPassword').innerText;
        navigator.clipboard.writeText(pw).then(() => {
            const btn = document.getElementById('copyPwBtn');
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            btn.classList.remove('text-blue-600', 'hover:bg-blue-50');
            btn.classList.add('text-emerald-600', 'bg-emerald-50');
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-regular fa-copy"></i> Copy';
                btn.classList.remove('text-emerald-600', 'bg-emerald-50');
                btn.classList.add('text-blue-600', 'hover:bg-blue-50');
            }, 2000);
        });
    }

    // FORM SUBMIT with loading + credentials modal
    document.getElementById('tenantForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        let formData = new FormData(form);
        formData.append('_method', document.getElementById('methodField').value);

        // Loading state
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');
        submitBtn.disabled = true;
        submitText.innerText = 'Saving...';
        submitSpinner.classList.remove('hidden');

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            // Reset loading state
            submitBtn.disabled = false;
            submitText.innerText = 'Save Tenant';
            submitSpinner.classList.add('hidden');

        if(data.success) {
            closeModal();
            
            // Pass data.credentials and data.tenant separately
            if(data.credentials) {
                showCredentials(data.credentials, data.tenant);
            } else {
                location.reload();
            }
        } else {
                alert(data.message || 'Error');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitText.innerText = 'Save Tenant';
            submitSpinner.classList.add('hidden');
            alert('Error saving tenant.');
        });
    });

    function renewTenant(id) {
        if(!confirm('Renew this subscription based on the current plan?')) return;
        fetch(`{{ route("tenants.renew", 0) }}`.replace('/0', '/' + id), {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }

    function toggleStatus(id) {
        fetch(`{{ route("platform.tenants.toggle-status", 0) }}`.replace('/0', '/' + id), {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }

    function deleteTenant(id) {
        if(!confirm('Are you sure you want to delete this tenant? This is irreversible!')) return;
        fetch(`{{ route("platform.tenants.destroy", 0) }}`.replace('/0', '/' + id), {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
</script>
@endsection