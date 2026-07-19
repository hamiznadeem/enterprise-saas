<x-app-layout>
    <x-slot name="header">Write Prescription</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Prescription Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Info Bar -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap items-center gap-4">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-bold">#{{ $token->token_number }}</span>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $token->patient->name }}</p>
                    <p class="text-xs text-gray-400">{{ $token->patient->age }}y · {{ ucfirst($token->patient->gender) }} · {{ $token->patient->phone }}</p>
                </div>
                @if($token->patient->allergies)
                <span class="ml-auto inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">⚠ {{ $token->patient->allergies }}</span>
                @endif
            </div>

            <!-- Prescription Form -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <form method="POST" action="{{ route('prescriptions.store', $token->id) }}" id="prescriptionForm">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                            <textarea name="diagnosis" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-none" placeholder="Enter diagnosis...">{{ old('diagnosis') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-none" placeholder="Additional notes, follow-up instructions...">{{ old('notes') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date</label>
                            <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}" class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        <!-- Medicine Items -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-medium text-gray-700">Medicines</label>
                                <button type="button" onclick="addMedicineRow()" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                    Add Medicine
                                </button>
                            </div>
                            <div id="medicineRows" class="space-y-3">
                                <div class="medicine-row p-4 rounded-lg bg-gray-50 border border-gray-200 space-y-3">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-1">
                                            <input type="text" name="medicines[]" placeholder="Search medicine by name, brand, or generic..." class="medicine-search w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" autocomplete="off">
                                            <div class="search-results hidden mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto"></div>
                                        </div>
                                        <button type="button" onclick="this.closest('.medicine-row').remove()" class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition mt-0.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3">
                                        <input type="text" name="dosage[]" placeholder="e.g. 1-1-1" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                        <input type="number" name="days[]" placeholder="Days" min="1" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                        <input type="text" name="instructions[]" placeholder="e.g. After meal" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">Save Prescription</button>
                            <a href="{{ route('tokens.doctor.dashboard') }}" class="px-6 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Patient History Sidebar -->
        <div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-20">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Previous Visits</h3>
                @if(isset($token->patient) && $token->patient->tokens && $token->patient->tokens->count() > 1)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($token->patient->tokens->where('id', '!=', $token->id)->sortByDesc('created_at')->take(5) as $prev)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-400">{{ $prev->created_at->format('M d, Y') }}</p>
                        @if($prev->prescription)
                        <p class="text-sm text-gray-700 mt-1">{{ $prev->prescription->diagnosis ?? 'No diagnosis' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-400">No previous visits</p>
                @endif
            </div>
        </div>
    </div>

            <script>
        function addMedicineRow() {
            const container = document.getElementById('medicineRows');
            const row = document.createElement('div');
            row.className = 'medicine-row p-4 rounded-lg bg-gray-50 border border-gray-200 space-y-3';
            row.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <input type="text" name="medicines[]" placeholder="Search medicine..." class="medicine-search w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" autocomplete="off">
                        <div class="search-results hidden mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
                    </div>
                    <button type="button" onclick="this.closest('.medicine-row').remove()" class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition mt-0.5">
                        <i class="fa-solid fa-xmark w-4 h-4"></i>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <input type="text" name="dosage[]" placeholder="e.g. 1-1-1" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <input type="number" name="days[]" placeholder="Days" min="1" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <input type="text" name="instructions[]" placeholder="e.g. After meal" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>`;
            container.appendChild(row);
            initMedicineSearch(row.querySelector('.medicine-search'));
        }

        function initMedicineSearch(input) {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const q = this.value.trim();
                const results = this.nextElementSibling;
                if (q.length < 2) { results.classList.add('hidden'); return; }
                timeout = setTimeout(() => {
                    fetch(`{{ route('prescriptions.search-medicine') }}?q=${encodeURIComponent(q)}`)
                        .then(r => { if (!r.ok) throw new Error('Network error'); return r.json(); })
                        .then(data => {
                            if (!data.length) { 
                                results.innerHTML = '<div class="p-4 text-sm text-gray-400 text-center">No medicines found</div>'; 
                                results.classList.remove('hidden'); 
                                return; 
                            }
                            
                            results.innerHTML = data.map(m => `
                                <button type="button" class="w-full text-left px-4 py-3 hover:bg-indigo-50 transition border-b border-gray-100 last:border-0" onclick='selectMedicine(this, ${m.id}, ${JSON.stringify(m.name)}, ${m.stock})'>
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900">${m.name}</p>
                                        ${m.stock === 0 
                                            ? '<span class="text-[10px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">OUT OF STOCK</span>' 
                                            : '<span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Stock: ' + m.stock + '</span>'
                                        }
                                    </div>
                                    ${m.generic_name ? `<p class="text-xs text-gray-400 mt-0.5">${m.generic_name}</p>` : ''}
                                    
                                    ${m.alternatives && m.alternatives.length ? `
                                        <div class="mt-2 p-2 bg-amber-50/60 border border-amber-100 rounded-lg">
                                            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                                <i class="fa-solid fa-arrows-rotate text-[9px]"></i> Alternatives
                                            </p>
                                            <div class="space-y-1">
                                                ${m.alternatives.map(a => `
                                                    <button type="button" class="w-full flex items-center justify-between text-left text-xs bg-white px-2.5 py-1.5 rounded-md border border-amber-200 hover:border-indigo-300 hover:bg-indigo-50 transition group" onclick='event.stopPropagation(); selectMedicine(this.closest("button").parentElement.closest("button"), ${a.id}, ${JSON.stringify(a.name)}, ${a.stock})'>
                                                        <span class="font-medium text-gray-700 group-hover:text-indigo-700">${a.name}</span>
                                                        <span class="font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">${a.stock} left</span>
                                                    </button>
                                                `).join('')}
                                            </div>
                                        </div>
                                    ` : ''}
                                </button>
                            `).join('');
                            results.classList.remove('hidden');
                        }).catch(err => console.error('Search error:', err));
                }, 300);
            });
        }

        function selectMedicine(btn, id, name, stock) {
            const row = btn.closest('.medicine-row');
            const input = row.querySelector('input[name="medicines[]"]');
            input.value = name;
            input.dataset.medicineId = id;
            btn.closest('.search-results').classList.add('hidden');
            
            if (stock === 0) {
                input.classList.add('border-red-400', 'bg-red-50');
            } else {
                input.classList.remove('border-red-400', 'bg-red-50');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.medicine-search') && !e.target.closest('.search-results')) {
                document.querySelectorAll('.search-results').forEach(r => r.classList.add('hidden'));
            }
        });

        // Initiate search on existing inputs
        document.querySelectorAll('.medicine-search').forEach(initMedicineSearch);
    </script>
</x-app-layout>