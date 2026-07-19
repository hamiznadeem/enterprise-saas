<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }} - Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>body { background: #f3f4f6; } @media print { body { background: white; } .no-print { display: none; } }</style>
</head>
<body class="p-8">
    
    <div class="no-print mb-6 flex justify-between items-center">
        <a href="{{ route('platform.invoices.index') }}" class="text-sm text-gray-600 hover:text-gray-900"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Invoices</a>
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700"><i class="fa-solid fa-print mr-1"></i> Print Invoice</button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-10 rounded-2xl shadow-lg border border-gray-200">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-indigo-600">{{ $invoice->invoice_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">Payment Receipt</p>
            </div>
            <div class="text-right text-sm text-gray-600">
                <p class="font-bold text-gray-900">SaaS Platform Inc.</p>
                <p>123 Business Park</p>
                <p>support@saas.com</p>
            </div>
        </div>

        <!-- Bill To -->
        <div class="mb-8 grid grid-cols-2 gap-8 text-sm">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Billed To:</p>
                <p class="font-bold text-gray-900 text-base">{{ $invoice->tenant->name }}</p>
                <p class="text-gray-500">{{ $invoice->tenant->owner_name }}</p>
                <p class="text-gray-500">{{ $invoice->tenant->owner_email }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Details:</p>
                <p>Plan: <span class="font-medium text-gray-900">{{ $invoice->subscription->plan->name ?? 'N/A' }}</span></p>
                <p>Date: <span class="font-medium text-gray-900">{{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : 'N/A' }}</span></p>
                <p>Status: <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">{{ ucfirst($invoice->status) }}</span></p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full mb-8 text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="border-b border-gray-200">
                <tr>
                    <td class="px-4 py-4 text-gray-800">Subscription Payment ({{ $invoice->subscription->plan->billing_cycle ?? 'N/A' }})</td>
                    <td class="px-4 py-4 text-right font-medium text-gray-900">${{ number_format($invoice->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 text-gray-500 text-xs">Tax</td>
                    <td class="px-4 py-2 text-right text-gray-500">${{ number_format($invoice->tax, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-indigo-50">
                    <td class="px-4 py-4 text-indigo-800 font-bold text-base">Total Paid</td>
                    <td class="px-4 py-4 text-right text-indigo-800 font-bold text-xl">${{ number_format($invoice->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-400 border-t border-gray-100 pt-6">
            Thank you for your business! This is a system generated receipt.
        </div>
    </div>
</body>
</html>