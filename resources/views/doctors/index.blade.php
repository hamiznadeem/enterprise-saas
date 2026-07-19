<x-app-layout>
    <x-slot name="header">Manage Doctors</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Doctors Panel</h2>
            <p class="text-sm text-gray-500 mt-1">Manage doctors, their specializations and consultation fees.</p>
        </div>
        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Doctor
        </button>
    </div>

    <!-- Doctors Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Doctor</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Specialization</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Fee</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Daily Limit</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($doctors as $doctor)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-700 text-xs font-bold">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $doctor->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $doctor->phone ?? 'No phone' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $doctor->specialization ?? 'General' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rs. {{ number_format($doctor->consultation_fee) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $doctor->daily_patient_limit ?? '∞' }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('doctors.toggle-status', $doctor) }}" class="inline-flex">
                                @csrf
                                <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors duration-200 ease-in-out {{ $doctor->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="{{ $doctor->is_active ? 'Click to Deactivate' : 'Click to Activate' }}">
                                    <span class="inline-block w-5 h-5 transform rounded-full bg-white shadow transform -translate-x-1 {{ $doctor->is_active ? 'translate-x-5' : '-translate-x-0.5' }} transition-transform duration-200 ease-in-out"></span>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1 justify-end">
                                <!-- Edit Button -->
                                <button onclick="openEditModal({{ $doctor }})" class="p-1.5 rounded-lg hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 transition" title="Edit Doctor">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                                <!-- Delete Button -->
                                <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition" title="Delete Doctor">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            <p class="text-sm text-gray-500">No doctors added yet</p>
                            <button onclick="openAddModal()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-medium">+ Add First Doctor</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Doctor Modal (Add / Edit) -->
    <div id="doctorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add New Doctor</h3>
                <button onclick="closeModal()" class="p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="doctorForm" method="POST" action="{{ route('doctors.store') }}" class="p-6 space-y-4">
                @csrf
                @method('PUT') <!-- Yeh hidden rehta hai, JS edit karne par show karega -->
                <input type="hidden" name="doctor_id" id="doctor_id" value="">
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="doc_name" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Dr. John Doe">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                        <input type="text" name="specialization" id="doc_spec" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="e.g. Cardiologist">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="doc_phone" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="03XX-XXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Consultation Fee (Rs.) *</label>
                        <input type="number" name="consultation_fee" id="doc_fee" required min="0" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="1500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Daily Patient Limit</label>
                        <input type="number" name="daily_patient_limit" id="doc_limit" min="1" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="e.g. 30">
                    </div>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="doc_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Immediately Active</span>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Save Doctor</button>
                    <button type="button" onclick="closeModal()" class="px-4 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').innerText = "Add New Doctor";
            document.getElementById('doctorForm').action = "{{ route('doctors.store') }}";
            document.getElementById('doctorForm').querySelector('input[name="_method"]').remove(); // Remove PUT method
            document.getElementById('doctor_id').value = "";
            document.getElementById('doc_name').value = "";
            document.getElementById('doc_spec').value = "";
            document.getElementById('doc_phone').value = "";
            document.getElementById('doc_fee').value = "";
            document.getElementById('doc_limit').value = "";
            document.getElementById('doc_active').checked = true;
            document.getElementById('doctorModal').classList.remove('hidden');
        }

             function openEditModal(doctor) {
            document.getElementById('modalTitle').innerText = "Edit Doctor";
            // Yahan URL mein placeholder daal diya hai jo JS replace karega
            document.getElementById('doctorForm').action = "{{ route('doctors.update', ['doctor' => 'ID_PLACEHOLDER']) }}".replace('ID_PLACEHOLDER', doctor.id);
            
            // Yahan hidden input add kar rahe hain jo PUT method aur ID bhejega
            let existingMethod = document.querySelector('input[name="_method"]');
            if(!existingMethod) {
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('doctorForm').appendChild(methodInput);
            }

            document.getElementById('doctor_id').value = doctor.id;
            document.getElementById('doc_name').value = doctor.name;
            document.getElementById('doc_spec').value = doctor.specialization || '';
            document.getElementById('doc_phone').value = doctor.phone || '';
            document.getElementById('doc_fee').value = doctor.consultation_fee;
            document.getElementById('doc_limit').value = doctor.daily_patient_limit || '';
            document.getElementById('doc_active').checked = doctor.is_active == 1;
            document.getElementById('doctorModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('doctorModal').classList.add('hidden');
        }
    </script>
</x-app-layout>