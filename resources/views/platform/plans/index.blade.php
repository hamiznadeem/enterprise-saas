@extends('platform.layouts.app')

@section('header', 'Subscription Plans')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manage Plans</h2>
        <p class="text-sm text-gray-500 mt-1">Define limits and pricing for your SaaS tiers.</p>
    </div>
    <button onclick="openModal()" class="px-5 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Create Plan
    </button>
</div>

<!-- Plans Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="plansContainer">
    @foreach($plans as $plan)
    <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-lg transition-shadow">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                <p class="text-xs text-gray-400 uppercase">{{ $plan->billing_cycle }}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openEditModal({{ $plan }})" class="text-gray-400 hover:text-purple-600 transition"><i class="fa-solid fa-pen-to-square"></i></button>
                <button onclick="deletePlan({{ $plan->id }})" class="text-gray-400 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
        
        <div class="mb-6">
            <span class="text-4xl font-extrabold text-gray-900">${{ number_format($plan->price, 2) }}</span>
            @if($plan->trial_days > 0)
                <span class="ml-2 text-sm text-emerald-600 font-medium">+ {{$plan->trial_days}} days trial</span>
            @endif
        </div>

        <ul class="space-y-3 text-sm text-gray-600 border-t border-gray-100 pt-4">
            <li class="flex items-center gap-2"><i class="fa-solid fa-building text-purple-500 w-4"></i> {{ $plan->limits['branches'] ?? 'Unlimited' }} Branches</li>
            <li class="flex items-center gap-2"><i class="fa-solid fa-users text-purple-500 w-4"></i> {{ $plan->limits['users'] ?? 'Unlimited' }} Users</li>
            <li class="flex items-center gap-2"><i class="fa-solid fa-box text-purple-500 w-4"></i> {{ $plan->limits['products'] ?? 'Unlimited' }} Products</li>
        </ul>
    </div>
    @endforeach
</div>

<!-- Modal -->
<div id="planModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 id="modalTitle" class="text-lg font-bold">Create Plan</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="planForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" value="POST" id="methodField">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                <input type="text" name="name" id="p_name" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" id="p_price" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                    <select name="billing_cycle" id="p_cycle" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm bg-white">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                        <option value="lifetime">Lifetime</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trial Days</label>
                <input type="number" name="trial_days" id="p_trial" value="0" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500 outline-none">
            </div>

            <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                <p class="text-sm font-semibold text-gray-700">Limits</p>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs text-gray-500">Branches</label>
                        <input type="number" name="limits[branches]" id="p_branches" value="1" min="1" required class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Users</label>
                        <input type="number" name="limits[users]" id="p_users" value="3" min="1" required class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Products</label>
                        <input type="number" name="limits[products]" id="p_products" value="500" min="1" required class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Save Plan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let editId = null;

    function openModal() {
        editId = null;
        document.getElementById('modalTitle').innerText = 'Create Plan';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('planForm').action = '{{ route("platform.plans.store") }}';
        document.getElementById('planForm').reset();
        document.getElementById('planModal').classList.remove('hidden');
        document.getElementById('planModal').classList.add('flex');
    }

    function openEditModal(plan) {
        editId = plan.id;
        document.getElementById('modalTitle').innerText = 'Edit Plan';
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('planForm').action = '{{ route("platform.plans.update", 0) }}'.replace('/0', '/' + plan.id);
        
        document.getElementById('p_name').value = plan.name;
        document.getElementById('p_price').value = plan.price;
        document.getElementById('p_cycle').value = plan.billing_cycle;
        document.getElementById('p_trial').value = plan.trial_days;
        document.getElementById('p_branches').value = plan.limits?.branches || 1;
        document.getElementById('p_users').value = plan.limits?.users || 3;
        document.getElementById('p_products').value = plan.limits?.products || 500;
        
        document.getElementById('planModal').classList.remove('hidden');
        document.getElementById('planModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('planModal').classList.add('hidden');
        document.getElementById('planModal').classList.remove('flex');
    }

    // AJAX Form Submit
    document.getElementById('planForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const action = form.action;
        const method = document.getElementById('methodField').value;
        
        let formData = new FormData(form);
        formData.append('_method', method); // Spoof method for Laravel

        fetch(action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) {
                closeModal();
                location.reload(); // Simple reload for now to show new card
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('Error saving plan.'));
    });

    function deletePlan(id) {
        if(!confirm('Are you sure?')) return;
        fetch(`{{ route("platform.plans.destroy", 0) }}`.replace('/0', '/' + id), {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if(data.success) location.reload();
            else alert(data.message);
        });
    }
</script>
@endsection