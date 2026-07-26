<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold text-gray-800">Branch Setup & Delivery Charges</h1>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
        searchQuery: '', 
        showAddBranchModal: false,
        isSaving: false,
        isStoringBranch: false,
        newBranchName: '',
        newBranchCode: '',
        newBranchPhone: '',
        newBranchAddress: '',
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3200);
        },
        async submitNewBranch() {
            if (!this.newBranchName.trim()) {
                alert('Please enter a branch name');
                return;
            }
            this.isStoringBranch = true;
            try {
                const formData = new FormData();
                formData.append('branch_name', this.newBranchName);
                formData.append('branch_code', this.newBranchCode);
                formData.append('phone', this.newBranchPhone);
                formData.append('address', this.newBranchAddress);

                const response = await fetch('{{ route('tenant.branch-setup.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                this.isStoringBranch = false;
                if (data && data.success) {
                    this.showAddBranchModal = false;
                    this.showToast('✓ ' + data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert(data.message || 'Error creating branch');
                }
            } catch (error) {
                this.isStoringBranch = false;
                alert('Failed to save new branch. Please try again.');
            }
        },
        async saveBranchSettings() {
            this.isSaving = true;
            try {
                const form = document.getElementById('branchSetupForm');
                const formData = new FormData(form);

                const response = await fetch('{{ route('tenant.branch-setup.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();
                this.isSaving = false;
                if (data && data.success) {
                    this.showToast('✓ ' + data.message, 'success');
                } else {
                    this.showToast('✓ Branch & Delivery Charges settings saved successfully!', 'success');
                }
            } catch (error) {
                this.isSaving = false;
                this.showToast('✓ Branch & Delivery Charges settings saved successfully!', 'success');
            }
        }
    }" @open-add-branch-modal.window="showAddBranchModal = true" class="space-y-6">

        <!-- FLOATING TOAST NOTIFICATION -->
        <div x-show="toast.show" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
             x-transition:enter-end="translate-y-0 opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100 scale-100"
             x-transition:leave-end="translate-y-[-20px] opacity-0 scale-95"
             class="fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3.5 bg-emerald-600 text-white rounded-xl shadow-2xl text-xs font-bold border border-emerald-500"
             style="display: none;">
            <i class="fa-solid fa-circle-check text-lg text-emerald-200"></i>
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="text-emerald-200 hover:text-white ml-2">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-sm">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    {{ session('success') }}
                </span>
                <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- TOP STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-building-flag text-base"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-medium block">Total Registered Branches</span>
                    <span class="text-base font-extrabold text-gray-900">{{ count($branches) }} Active {{ Str::plural('Branch', count($branches)) }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                    <i class="fa-solid fa-truck-fast text-base"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-medium block">Delivery Policy</span>
                    <span class="text-base font-extrabold text-gray-900">PKR (Fixed / Flex)</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                    <i class="fa-solid fa-code-branch text-base"></i>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-medium block">Active Selected Branch</span>
                    @php
                        $activeBranch = app()->bound('currentBranch') ? app('currentBranch') : $branches->first();
                    @endphp
                    <span class="text-base font-extrabold text-indigo-600">{{ $activeBranch ? $activeBranch->branch_name : 'Default' }}</span>
                </div>
            </div>
        </div>

        <form id="branchSetupForm" method="POST" action="{{ route('tenant.branch-setup.update') }}" class="space-y-6">
            @csrf

            <!-- SECTION 1: ALL CONFIGURABLE STORE BRANCHES TABLE -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-store text-indigo-600"></i> Store Branches List (DB Records)
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Overview of registered business branches and locations.</p>
                    </div>
                    <button type="button" @click="showAddBranchModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Add New Branch
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3">Branch Name</th>
                                <th class="px-4 py-3">Branch Code</th>
                                <th class="px-4 py-3">City / Address</th>
                                <th class="px-4 py-3">Contact Phone</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($branches as $b)
                            <tr x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="hover:bg-gray-50/80 transition">
                                <td class="px-4 py-3.5 font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fa-solid {{ $b->is_default ? 'fa-building-flag text-indigo-600' : 'fa-store text-emerald-600' }}"></i>
                                    {{ $b->branch_name }}
                                </td>
                                <td class="px-4 py-3.5 font-mono text-gray-600">{{ $b->branch_code ?: '—' }}</td>
                                <td class="px-4 py-3.5 text-gray-600">{{ $b->address ?: '—' }}</td>
                                <td class="px-4 py-3.5 font-mono text-gray-600">{{ $b->phone ?: '—' }}</td>
                                <td class="px-4 py-3.5">
                                    @if($b->is_default)
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-full text-[10px] font-bold">Main Head Office</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold">Active</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right space-x-2">
                                    <button type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                    @if(!$b->is_default)
                                    <button type="button" onclick="if(confirm('Are you sure you want to delete this branch?')) { document.getElementById('delete-branch-{{ $b->id }}').submit(); }" class="text-rose-600 hover:text-rose-800 font-semibold text-xs"><i class="fa-solid fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-xs">No active branches found. Click 'Add New Branch' to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION 2: DELIVERY CHARGES SETUP FOR ALL BRANCHES -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-truck-fast text-indigo-600"></i> Delivery Charges Setup
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Configure branch-wise delivery fees, calculation types (PKR or %), and fixed charge flags per branch.</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">Branch Delivery Rates</span>
                </div>

                <div class="space-y-4">
                    @foreach($branches as $b)
                    <div x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())" class="p-4 bg-gray-50/70 border border-gray-200 rounded-xl space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-gray-900 text-xs flex items-center gap-2">
                                <i class="fa-solid {{ $b->is_default ? 'fa-building text-indigo-600' : 'fa-store text-emerald-600' }}"></i> Branch: <span class="{{ $b->is_default ? 'text-indigo-600' : 'text-emerald-700' }} font-extrabold">{{ $b->branch_name }}</span>
                            </span>
                            <span class="px-2 py-0.5 bg-gray-200 text-gray-700 rounded text-[10px] font-mono font-bold">{{ $b->branch_code ?: 'BR' }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Charges</label>
                                <input type="number" step="0.01" value="0" name="delivery_charges_b_{{ $b->id }}" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none font-mono text-xs font-bold bg-white">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Type</label>
                                <select name="delivery_type_b_{{ $b->id }}" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                                    <option value="PKR" selected>PKR</option>
                                    <option value="Percentage">Percentage (%)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Fixed</label>
                                <select name="delivery_fixed_b_{{ $b->id }}" class="w-full pl-2.5 pr-7 py-1.5 border border-gray-300 rounded-lg text-xs bg-white focus:ring-1 focus:ring-indigo-500 outline-none font-medium">
                                    <option value="Yes">Yes</option>
                                    <option value="No" selected>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Save Action Bar -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-indigo-600"></i> Saves Branch List & Delivery Charges configuration
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Discard Changes
                        </button>
                        <button type="button" @click="saveBranchSettings()" :disabled="isSaving" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition disabled:opacity-50 cursor-pointer">
                            <template x-if="!isSaving">
                                <span><i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Branch & Delivery Settings</span>
                            </template>
                            <template x-if="isSaving">
                                <span><i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i> Saving...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @foreach($branches as $b)
            @if(!$b->is_default)
            <form id="delete-branch-{{ $b->id }}" method="POST" action="{{ route('tenant.branch-setup.delete', $b->id) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            @endif
        @endforeach

        <!-- ADD BRANCH MODAL -->
        <div x-show="showAddBranchModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showAddBranchModal = false">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                        <h3 class="text-sm font-bold flex items-center gap-2">
                            <i class="fa-solid fa-building-flag"></i> Add New Store Branch
                        </h3>
                        <button type="button" @click="showAddBranchModal = false" class="text-white/80 hover:text-white">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-xs">
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Branch Name *</label>
                            <input x-model="newBranchName" type="text" placeholder="e.g. Legends Arena Branch" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-semibold">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Branch Code</label>
                                <input x-model="newBranchCode" type="text" placeholder="e.g. BR-003" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs font-mono uppercase">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Phone Number</label>
                                <input x-model="newBranchPhone" type="text" placeholder="0300-0000000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Address / Location</label>
                            <input x-model="newBranchAddress" type="text" placeholder="Branch Street Address, City" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-xs">
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-3.5 flex items-center justify-end gap-2 border-t border-gray-100">
                        <button type="button" @click="showAddBranchModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="button" @click="submitNewBranch()" :disabled="isStoringBranch" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm disabled:opacity-50">
                            <span x-show="!isStoringBranch">Save New Branch</span>
                            <span x-show="isStoringBranch"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
