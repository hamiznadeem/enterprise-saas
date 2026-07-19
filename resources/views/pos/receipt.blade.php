<x-app-layout>
    <x-slot name="header">Sale Receipt</x-slot>

    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-emerald-600 px-8 py-6 text-center">
                <h1 class="text-2xl font-bold text-white">SALE COMPLETED</h1>
                <p class="text-emerald-100 text-sm mt-1">Receipt #{{ $sale->id }} · {{ $sale->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <div class="p-8">
                <!-- Customer Info -->
                <div class="mb-6 pb-6 border-b border-dashed border-gray-300">
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Customer</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $sale->patient->name ?? 'Walk-in Customer' }}</p>
                </div>

                <!-- Items -->
                <table class="w-full mb-6">
                    <thead>
                        <tr class="text-left border-b border-gray-200">
                            <th class="pb-2 text-xs font-semibold text-gray-500 uppercase">Item</th>
                            <th class="pb-2 text-xs font-semibold text-gray-500 uppercase text-center">Qty</th>
                            <th class="pb-2 text-xs font-semibold text-gray-500 uppercase text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="py-3 text-sm text-gray-700">{{ $item->item_name }}</td>
                            <td class="py-3 text-sm text-gray-600 text-center">{{ $item->qty }}</td>
                            <td class="py-3 text-sm text-gray-900 text-right font-medium">Rs. {{ number_format($item->price * $item->qty) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Totals -->
                <div class="space-y-2 pt-4 border-t-2 border-gray-900">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span>Rs. {{ number_format($sale->subtotal + $sale->discount_amount) }}</span>
                    </div>
                    @if($sale->discount_amount > 0)
                    <div class="flex justify-between text-sm text-red-500">
                        <span>Discount</span>
                        <span>- Rs. {{ number_format($sale->discount_amount) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold text-gray-900 pt-2">
                        <span>Total Paid</span>
                        <span>Rs. {{ number_format($sale->total_amount) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 mt-8">
                    <button onclick="window.print()" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 7.131s0 0 0 0"/></svg>
                        Print Receipt
                    </button>
                    <a href="{{ route('pos.index') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        New Sale
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 