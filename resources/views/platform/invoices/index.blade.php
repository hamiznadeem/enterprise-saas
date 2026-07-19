@extends('platform.layouts.app')

@section('header', 'Platform Invoices')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">All Invoices</h2>
    <p class="text-sm text-gray-500 mt-1">Generated automatically when payments are recorded.</p>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-3.5">Invoice #</th>
                <th class="px-6 py-3.5">Tenant</th>
                <th class="px-6 py-3.5">Amount</th>
                <th class="px-6 py-3.5">Status</th>
                <th class="px-6 py-3.5">Date</th>
                <th class="px-6 py-3.5 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($invoices as $inv)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-mono font-medium text-indigo-600">{{ $inv->invoice_number }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $inv->tenant->name }}</td>
                <td class="px-6 py-4 text-sm font-bold text-gray-900">${{ number_format($inv->total, 2) }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">{{ ucfirst($inv->status) }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $inv->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('platform.invoices.show', $inv) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="fa-solid fa-receipt mr-1"></i> View Slip
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No invoices yet. Record a payment to generate one.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection