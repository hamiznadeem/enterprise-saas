<x-app-layout>
    <x-slot name="header">Inventory Alerts</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-red-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-red-900">Low Stock Medicines</h2>
                        <p class="text-xs text-red-600">Stock ≤ 10 units</p>
                    </div>
                </div>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-200 text-red-800 text-sm font-bold">{{ $lowStockMedicines->count() }}</span>
            </div>
            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                @forelse($lowStockMedicines as $med)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $med->name }}</p>
                        <p class="text-xs text-gray-400">{{ $med->generic_name ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600">{{ $med->stock_quantity }} left</p>
                        <p class="text-xs text-gray-400">Rs. {{ number_format($med->sale_price) }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm text-gray-400">All medicines are well stocked!</div>
                @endforelse
            </div>
        </div>

        <!-- Expiring Soon -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-amber-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-amber-900">Expiring Soon</h2>
                        <p class="text-xs text-amber-600">Within next 30 days</p>
                    </div>
                </div>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-200 text-amber-800 text-sm font-bold">{{ $expiringMedicines->count() }}</span>
            </div>
            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                @forelse($expiringMedicines as $med)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $med->name }}</p>
                        <p class="text-xs text-gray-400">Batch: {{ $med->batch_number ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-amber-600">{{ $med->expiry_date->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $med->expiry_date->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-sm text-gray-400">No medicines expiring soon!</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>