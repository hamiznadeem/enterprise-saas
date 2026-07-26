<x-app-layout>
    <x-slot name="header">Clinic Dashboard</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Today's Patients -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Patients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $todayPatients }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Completed: {{ $todayCompleted }}</p>
        </div>

        <!-- Waiting Queue -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Waiting Queue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $waitingTokens }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">In Progress: {{ $inProgressTokens }}</p>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">Rs. {{ number_format($todayRevenue) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs text-emerald-600 mt-3 font-medium">Paid invoices</p>
        </div>

        <!-- Alerts -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Inventory Alerts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $lowStockCount + $expiringSoonCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">{{ $lowStockCount }} low stock · {{ $expiringSoonCount }} expiring</p>
        </div>
    </div>

    <!-- Quick Actions + Recent Tokens -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('tokens.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">New Token</p>
                        <p class="text-xs text-indigo-500">Register patient & create queue token</p>
                    </div>
                </a>
                <a href="{{ route('patients.index') }}" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 transition group">
                    <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center group-hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Search Patient</p>
                        <p class="text-xs text-gray-500">Find existing patient records</p>
                    </div>
                </a>
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 transition group">
                    <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center group-hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Pharmacy POS</p>
                        <p class="text-xs text-gray-500">Walk-in medicine sales</p>
                    </div>
                </a>
                <a href="{{ route('tokens.doctor.dashboard') }}" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 transition group">
                    <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center group-hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Doctor View</p>
                        <p class="text-xs text-gray-500">Manage patient queue</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Tokens -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="flex items-center justify-between p-6 pb-4">
                <h2 class="text-base font-semibold text-gray-900">Recent Tokens</h2>
                <a href="{{ route('tokens.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All →</a>
            </div>
            @if($recentTokens->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-t border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Token</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentTokens as $token)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">#{{ $token->token_number }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-sm font-medium text-gray-900">{{ $token->patient->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $token->patient->phone ?? '' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-sm text-gray-600">{{ $token->doctor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3.5">
                                @php
                                    $statusColors = [
                                        'waiting' => 'bg-amber-50 text-amber-700',
                                        'in-progress' => 'bg-blue-50 text-blue-700',
                                        'completed' => 'bg-emerald-50 text-emerald-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                    ];
                                    $color = $statusColors[$token->status] ?? 'bg-gray-50 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ str_replace('-', ' ', ucfirst($token->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-400">{{ $token->created_at->format('h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                <p class="text-sm text-gray-500">No tokens created yet</p>
                <a href="{{ route('tokens.create') }}" class="inline-flex items-center gap-1.5 mt-3 text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Create first token →
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
