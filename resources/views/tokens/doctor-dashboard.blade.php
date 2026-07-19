<x-app-layout>
    <x-slot name="header">Doctor Dashboard</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Patient -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        Current Patient
                    </h2>
                </div>
                @if($currentToken)
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-700 text-xl font-bold">
                            #{{ $currentToken->token_number ?? 'No Token' }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">{{ $currentToken->patient->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $currentToken->patient->phone }} · {{ $currentToken->patient->age }}y · {{ ucfirst($currentToken->patient->gender) }}</p>
                            @if($currentToken->patient->allergies)
                            <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                                ⚠ Allergies: {{ $currentToken->patient->allergies }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @if($currentToken->prescription)
                        <!-- Agar prescription already likhi hai toh View/Edit dikhao -->
                        <a href="{{ route('prescriptions.edit', $currentToken->prescription->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition shadow-sm">
                            <i class="fa-solid fa-file-prescription w-4 h-4"></i>
                            View / Edit Prescription
                        </a>
                        @else
                        
                        <a href="{{ route('prescriptions.create', $currentToken->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                            Write Prescription
                        </a>
                        @endif
                        <form method="POST" action="{{ route('tokens.doctor.complete', $currentToken->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Complete Visit
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No patient in progress</p>
                    <p class="text-sm text-gray-400 mt-1">Call the next patient from the queue</p>
                    <form method="POST" action="{{ route('tokens.doctor.call-next') }}" class="mt-4 inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"/></svg>
                            Call Next Patient
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Waiting Queue -->
        <div>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900">Waiting Queue</h3>
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">{{ $waitingTokens->count() }}</span>
                </div>
                <div class="max-h-[500px] overflow-y-auto">
                    @if($waitingTokens->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($waitingTokens as $i => $token)
                        <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-gray-50 transition">
                            <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $token->patient->name }}</p>
                                <p class="text-xs text-gray-400">{{ $token->patient->phone }}</p>
                            </div>
                            <span class="text-xs text-gray-400">#{{ $token->token_number }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-8 text-center text-sm text-gray-400">Queue is empty</div>
                    @endif
                </div>
                @if(!$currentToken && $waitingTokens->count() > 0)
                <div class="p-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('tokens.doctor.call-next') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            Call Next Patient
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Today's Stats -->
            <div class="mt-4 bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Today's Stats</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Completed</span>
                        <span class="text-sm font-bold text-emerald-600">{{ $completedToday ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Waiting</span>
                        <span class="text-sm font-bold text-amber-600">{{ $waitingTokens->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $waitingTokens->count() > 0 ? min(($completedToday ?? 0) / max($waitingTokens->count() + ($completedToday ?? 0), 1) * 100, 100) : 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>