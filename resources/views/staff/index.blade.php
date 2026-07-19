<x-app-layout>
    <x-slot name="header">Staff Management</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Team Members</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your clinic staff, their roles and access rights.</p>
        </div>
        <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <i class="fa-solid fa-plus w-4 h-4"></i>
            Add Staff
        </button>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Member</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Joined</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($staff as $member)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 text-xs font-bold">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($member->role === 'doctor') bg-blue-50 text-blue-700 
                                @elseif($member->role === 'pharmacist') bg-cyan-50 text-cyan-700 
                                @elseif($member->role === 'cashier') bg-amber-50 text-amber-700 
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($member->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $member->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('staff.toggle-status', $member) }}" class="inline-flex">
                                @csrf
                                <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors duration-200 ease-in-out {{ $member->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="{{ $member->is_active ? 'Click to Deactivate' : 'Click to Activate' }}">
                                    <span class="inline-block w-5 h-5 transform rounded-full bg-white shadow -translate-x-1 {{ $member->is_active ? 'translate-x-5' : '-translate-x-0.5' }} transition-transform duration-200 ease-in-out"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1 justify-end">
                                <button onclick='openEditModal(@json($member))' class="p-1.5 rounded-lg hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                                </button>
                                <form method="POST" action="{{ route('staff.destroy', $member) }}" onsubmit="return confirm('Are you sure you want to remove this staff member?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Remove Staff">
                                        <i class="fa-solid fa-trash w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <i class="fa-solid fa-users-slash text-gray-300 text-4xl mx-auto mb-3 block"></i>
                            <p class="text-sm text-gray-500">No staff members yet</p>
                            <button onclick="openModal()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-medium">+ Add First Staff Member</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="staffModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Add Staff Member</h3>
                <button onclick="closeModal()" class="p-1 rounded-lg hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark w-5 h-5 text-gray-400"></i>
                </button>
            </div>
            <form id="staffForm" method="POST" action="{{ route('staff.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="staff_id" id="staff_id" value="">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="s_name" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="s_email" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="staff@clinic.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="s_phone" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="03XX-XXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select name="role" id="s_role" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                            <option value="">Select Role</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="doctor">Doctor</option>
                            <option value="pharmacist">Pharmacist</option>
                            <option value="cashier">Cashier</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>
                </div>

                <!-- Password Section -->
                <div>
                    <h4 id="passHint" class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-200 pb-2 hidden">Leave blank to keep password unchanged</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span id="passStar" class="text-red-500">*</span></label>
                            <input type="password" name="password" id="s_pass" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="•••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="s_pass_conf" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="•••••••">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="s_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Account is Active</span>
                </div>
                
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Save Staff</button>
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').innerText = "Add Staff Member";
            document.getElementById('staffForm').action = "{{ route('staff.store') }}";
            document.getElementById('staffForm').reset();
            document.getElementById('staff_id').value = '';
            
            // Add wale mode mein password required hai
            document.getElementById('passHint').classList.add('hidden');
            document.getElementById('passStar').classList.remove('hidden');
            document.getElementById('s_active').checked = true;
            
            // Agar pehle se koi _method hidden input hai toh hata do
            let existingMethod = document.querySelector('#staffForm input[name="_method"]');
            if(existingMethod) existingMethod.remove();

            document.getElementById('staffModal').classList.remove('hidden');
        }

        function openEditModal(member) {
            document.getElementById('modalTitle').innerText = "Edit Staff Member";
            
            // URL mein ID replace karo
            document.getElementById('staffForm').action = "{{ route('staff.update', ['staff' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', member.id);
            
            // PUT method add karo
            let existingMethod = document.querySelector('#staffForm input[name="_method"]');
            if(!existingMethod) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('staffForm').appendChild(methodInput);
            }

            // Form ko data se fill karo (Yahan correct IDs use ki hain)
            document.getElementById('staff_id').value = member.id;
            document.getElementById('s_name').value = member.name;
            document.getElementById('s_email').value = member.email;
            document.getElementById('s_phone').value = member.phone || '';
            document.getElementById('s_role').value = member.role;
            document.getElementById('s_pass').value = '';
            document.getElementById('s_pass_conf').value = '';
            document.getElementById('s_active').checked = member.is_active == 1;
            
            // Edit mode mein password optional hai
            document.getElementById('passHint').classList.remove('hidden');
            document.getElementById('passStar').classList.add('hidden');

            // Modal kholo
            document.getElementById('staffModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('staffModal').classList.add('hidden');
            document.getElementById('staffForm').reset();
        }
    </script>
</x-app-layout>