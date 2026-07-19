<x-app-layout>
    <x-slot name="header">Prescription</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-cyan-500 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">PRESCRIPTION</h1>
                        <p class="text-indigo-100 text-sm mt-1">Token #{{ $prescription->token->token_number ?? '' }} · {{ $prescription->created_at->format('M d, Y') }}</p>
                    </div>
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white text-sm font-medium rounded-lg hover:bg-white/30 transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 7.131s0 0 0 0"/></svg>
                        Print
                    </button>
                </div>
            </div>

            <div class="p-8">
                <!-- Patient & Doctor -->
                <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Patient</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $prescription->patient->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $prescription->patient->age ?? '' }}y · {{ ucfirst($prescription->patient->gender ?? '') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Doctor</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $prescription->doctor->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Diagnosis -->
                @if($prescription->diagnosis)
                <div class="mb-6">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Diagnosis</p>
                    <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $prescription->diagnosis }}</p>
                </div>
                @endif

                <!-- Medicines -->
                @if($prescription->items && $prescription->items->count() > 0)
                <div class="mb-6">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-3">Medicines</p>
                    <div class="space-y-3">
                        @foreach($prescription->items as $item)
                        <div class="flex items-start gap-4 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center text-cyan-700 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->medicine->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $item->dosage }} · {{ $item->days }} days
                                    @if($item->instructions) · {{ $item->instructions }}@endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Notes & Follow-up -->
                @if($prescription->notes || $prescription->follow_up_date)
                <div class="space-y-3 pt-4 border-t border-gray-200">
                    @if($prescription->notes)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Notes</p>
                        <p class="text-sm text-gray-700">{{ $prescription->notes }}</p>
                    </div>
                    @endif
                    @if($prescription->follow_up_date)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Follow-up Date</p>
                        <p class="text-sm font-medium text-indigo-600">{{ \Carbon\Carbon::parse($prescription->follow_up_date)->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('tokens.doctor.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                        Back to Doctor Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>