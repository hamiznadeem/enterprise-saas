<x-app-layout>
    <x-slot name="header">All Tokens</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-2">
            @php
                $allCount = $allCount ?? \App\Models\Token::count();
                $waitingCount = $waitingCount ?? \App\Models\Token::where('status','waiting')->count();
                $progressCount = $progressCount ?? \App\Models\Token::where('status','in-progress')->count();
                $completedCount = $completedCount ?? \App\Models\Token::where('status','completed')->count();
            @endphp
            <a href="{{ route('tokens.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !request('status') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">All ({{ $allCount }})</a>
            <a href="{{ route('tokens.index', ['status' => 'waiting']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === 'waiting' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">Waiting ({{ $waitingCount }})</a>
            <a href="{{ route('tokens.index', ['status' => 'in-progress']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === 'in-progress' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">In Progress ({{ $progressCount }})</a>
            <a href="{{ route('tokens.index', ['status' => 'completed']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">Completed ({{ $completedCount }})</a>
        </div>
        <a href="{{ route('tokens.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Token
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Token</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Patient</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Doctor</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Service</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Time</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tokens as $token)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-bold">#{{ $token->token_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $token->patient->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ $token->patient->phone ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $token->doctor->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $token->service->name ?? 'General' }}</td>
                        <td class="px-6 py-4">
                            @php $colors = ['waiting'=>'bg-amber-50 text-amber-700','in-progress'=>'bg-blue-50 text-blue-700','completed'=>'bg-emerald-50 text-emerald-700','cancelled'=>'bg-red-50 text-red-700']; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$token->status] ?? '' }}">{{ str_replace('-',' ',ucfirst($token->status)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $token->created_at->format('M d, h:i A') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                @if($token->prescription)
                                <a href="{{ route('prescriptions.show', $token->prescription) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-indigo-600 transition" title="Prescription">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </a>
                                @endif
                                @if($token->invoice)
                                <a href="{{ route('invoices.show', $token->invoice) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-emerald-600 transition" title="Invoice">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                </a>
                                @endif
                                @if($token->status === 'completed' && !$token->invoice)
                                <form method="POST" action="{{ route('invoices.generate', $token->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 transition" title="Generate Invoice">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-16 text-center text-sm text-gray-500">No tokens found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>