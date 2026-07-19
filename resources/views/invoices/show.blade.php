<x-app-layout>
    <x-slot name="header">Invoice #{{ $invoice->id }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">INVOICE</h1>
                        <p class="text-slate-400 text-sm mt-1">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    @if($invoice->payment_status === 'paid')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> PAID
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/20 text-amber-300 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> UNPAID
                    </span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <!-- Patient & Doctor Info -->
                <div class="grid grid-cols-2 gap-6 mb-8 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Patient</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $invoice->patient->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $invoice->patient->phone ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Doctor</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $invoice->token->doctor->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $invoice->created_at->format('M d, Y · h:i A') }}</p>
                    </div>
                </div>

                <!-- Charges -->
                <table class="w-full mb-6">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase">Description</th>
                            <th class="pb-3 text-xs font-semibold text-gray-400 uppercase text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 text-sm text-gray-700">Consultation Fee</td>
                            <td class="py-3 text-sm text-gray-900 text-right font-medium">Rs. {{ number_format($invoice->doctor_fee) }}</td>
                        </tr>
                        @if($invoice->service_fee > 0)
                        <tr>
                            <td class="py-3 text-sm text-gray-700">Service Fee ({{ $invoice->token->service->name ?? 'Additional' }})</td>
                            <td class="py-3 text-sm text-gray-900 text-right font-medium">Rs. {{ number_format($invoice->service_fee) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-900">
                            <td class="pt-4 text-sm font-bold text-gray-900">Total</td>
                            <td class="pt-4 text-lg font-bold text-gray-900 text-right">Rs. {{ number_format($invoice->total_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Actions -->
                @if($invoice->payment_status !== 'paid')
                <form method="POST" action="{{ route('invoices.pay', $invoice) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Mark as Paid
                    </button>
                </form>
                @endif
                <a href="{{ route('tokens.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition ml-2">
                    Back to Tokens
                </a>
            </div>
        </div>
    </div>
</x-app-layout>