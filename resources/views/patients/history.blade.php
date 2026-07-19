<x-app-layout>
    <x-slot name="header">Patient History</x-slot>

    <!-- Back + Patient Info -->
    <div class="mb-6">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition mb-4">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to Patients
        </a>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Name</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $patient->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Phone</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $patient->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Age / Gender</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $patient->age }} years · {{ ucfirst($patient->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Blood Group</p>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $patient->blood_group ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @if($patient->allergies || $patient->medical_history)
            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($patient->allergies)
                <div class="p-3 rounded-lg bg-red-50 border border-red-100">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Allergies</p>
                    <p class="text-sm text-red-800 mt-0.5">{{ $patient->allergies }}</p>
                </div>
                @endif
                @if($patient->medical_history)
                <div class="p-3 rounded-lg bg-amber-50 border border-amber-100">
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Medical History</p>
                    <p class="text-sm text-amber-800 mt-0.5">{{ $patient->medical_history }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Visit History -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-900">Visit History</h2>
        </div>
        @if(isset($patient->tokens) && $patient->tokens->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($patient->tokens->load(['doctor', 'prescription', 'invoice'])->sortByDesc('created_at') as $token)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-bold shrink-0">#{{ $token->token_number }}</span>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $token->doctor->name ?? 'N/A' }}
                                @if($token->service)
                                <span class="text-gray-400 font-normal"> — {{ $token->service->name }}</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $token->created_at->format('M d, Y · h:i A') }}</p>
                            @if($token->prescription)
                            <div class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-gray-700">Diagnosis:</span> {{ $token->prescription->diagnosis ?? 'N/A' }}
                                @if($token->prescription->items && $token->prescription->items->count() > 0)
                                <p class="text-xs text-gray-400 mt-1">Medicines: {{ $token->prescription->items->pluck('medicine.name')->join(', ') }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @php
                            $statusColors = ['waiting'=>'bg-amber-50 text-amber-700','in-progress'=>'bg-blue-50 text-blue-700','completed'=>'bg-emerald-50 text-emerald-700','cancelled'=>'bg-red-50 text-red-700'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$token->status] ?? 'bg-gray-50 text-gray-700' }}">
                            {{ str_replace('-', ' ', ucfirst($token->status)) }}
                        </span>
                        @if($token->prescription)
                        <a href="{{ route('prescriptions.show', $token->prescription) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-indigo-600 transition" title="View Prescription">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </a>
                        @endif
                        @if($token->invoice)
                        <a href="{{ route('invoices.show', $token->invoice) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-emerald-600 transition" title="View Invoice">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-gray-500">No visits recorded yet</p>
        </div>
        @endif
    </div>
</x-app-layout>