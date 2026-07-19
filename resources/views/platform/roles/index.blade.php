@extends('platform.layouts.app')

@section('header', 'Roles & Permissions')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Roles & Permissions</h2>
        <p class="text-sm text-gray-500 mt-1">Manage platform access levels.</p>
    </div>
    <button onclick="openModal()" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Create Role
    </button>
</div>

<!-- Roles Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
    @foreach($roles as $role)
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition relative group">
        
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg {{ $role->name === 'super-admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-50 text-blue-600' }} flex items-center justify-center">
                    <i class="fa-solid fa-{{ $role->name === 'super-admin' ? 'crown' : 'user-shield' }} text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 capitalize">{{ str_replace('-', ' ', $role->name) }}</h3>
                    <p class="text-xs text-gray-400">{{ $role->permissions->count() }} permissions assigned</p>
                </div>
            </div>
            
            @if($role->name !== 'super-admin')
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                <button onclick='openEditModal({{ $role->toJson() }})' class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition" title="Edit">
                    <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <button onclick="deleteRole({{ $role->id }}, '{{ $role->name }}')" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Delete">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- Permissions Tags -->
        <div class="flex flex-wrap gap-1.5">
            @foreach($role->permissions->take(5) as $perm)
            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-medium rounded-md capitalize">
                {{ str_replace('.', ' → ', $perm->name) }}
            </span>
            @endforeach
            @if($role->permissions->count() > 5)
            <span class="px-2 py-0.5 bg-gray-50 text-gray-400 text-[10px] font-medium rounded-md">
                +{{ $role->permissions->count() - 5 }} more
            </span>
            @endif
        </div>

        @if($role->name === 'super-admin')
        <div class="absolute top-3 right-3">
            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-[10px] font-bold rounded-full uppercase">Protected</span>
        </div>
        @endif
    </div>
    @endforeach
</div>

<!-- Modal -->
<div id="roleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white rounded-t-2xl z-10">
            <h3 id="modalTitle" class="text-lg font-bold">Create New Role</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="roleForm" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="_method" value="POST" id="methodField">
            <input type="hidden" id="editRoleId" value="">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                <input type="text" name="name" id="roleName" required placeholder="e.g. content-manager" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500 outline-none lowercase">
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-semibold text-gray-700">Assign Permissions</label>
                    <button type="button" onclick="toggleAllPerms()" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Toggle All</button>
                </div>
                
                <div class="space-y-4 max-h-64 overflow-y-auto pr-2 border border-gray-100 rounded-lg p-4 bg-gray-50/50">
                    @foreach($allPermissions as $module => $perms)
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ ucfirst($module) }}</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($perms as $perm)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 perm-checkbox">
                                <span class="text-xs text-gray-600 group-hover:text-gray-900 transition">{{ explode('.', $perm->name)[1] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" id="submitBtn" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <span id="submitText">Save Role</span>
                    <svg id="submitSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').innerText = 'Create New Role';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('roleForm').action = '{{ route("platform.roles.store") }}';
        document.getElementById('roleName').value = '';
        document.getElementById('roleName').disabled = false;
        document.getElementById('editRoleId').value = '';
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
        toggleModal(true);
    }

    function openEditModal(role) {
        document.getElementById('modalTitle').innerText = 'Edit Role: ' + role.name.replace(/-/g, ' ');
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('roleForm').action = '{{ route("platform.roles.update", 0) }}'.replace('/0', '/' + role.id);
        document.getElementById('roleName').value = role.name;
        document.getElementById('roleName').disabled = (role.name === 'super-admin');
        document.getElementById('editRoleId').value = role.id;
        
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = role.permissions.some(p => p.name === cb.value);
        });
        
        toggleModal(true);
    }

    function closeModal() { toggleModal(false); }

    function toggleModal(show) {
        const modal = document.getElementById('roleModal');
        if(show) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        else { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    function toggleAllPerms() {
        const boxes = document.querySelectorAll('.perm-checkbox');
        const allChecked = Array.from(boxes).every(cb => cb.checked);
        boxes.forEach(cb => cb.checked = !allChecked);
    }

    document.getElementById('roleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        let formData = new FormData(form);
        formData.append('_method', document.getElementById('methodField').value);

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
            submitBtn.disabled = false;
            submitText.innerText = 'Save Role';
            submitSpinner.classList.add('hidden');
            if(data.success) location.reload();
            else alert(data.message || 'Error');
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitText.innerText = 'Save Role';
            submitSpinner.classList.add('hidden');
            alert('Error saving role.');
        });
    });

    function deleteRole(id, name) {
        if(!confirm(`Delete role "${name.replace(/-/g, ' ')}"? This cannot be undone.`)) return;
        fetch(`{{ route("platform.roles.destroy", 0) }}`.replace('/0', '/' + id), {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => { if(data.success) location.reload(); else alert(data.message); });
    }
</script>
@endsection