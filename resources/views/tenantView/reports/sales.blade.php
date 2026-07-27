@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 text-[11px] font-bold rounded-md border border-indigo-100 uppercase tracking-wider">Reports</span>
                <span class="text-xs text-slate-400 font-medium">• POS Sales Analytics</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Store Sales Report</h2>
            <p class="text-xs text-slate-500 mt-0.5">Filter sales by date range, branch outlets, and payment methods.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-print text-indigo-600"></i>
                <span>Print Report</span>
            </button>
            <a href="{{ route('tenant.reports.revenue') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-indigo-600/20 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-xs"></i>
                <span>View Revenue Report</span>
            </a>
        </div>
    </div>

    <!-- Filter Control Form -->
    <form method="GET" action="{{ route('tenant.reports.sales') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-indigo-600"></i> Report Filters
            </span>

            <!-- Preset Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('tenant.reports.sales', ['preset' => 'today']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'today' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Today</a>
                <a href="{{ route('tenant.reports.sales', ['preset' => 'yesterday']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'yesterday' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Yesterday</a>
                <a href="{{ route('tenant.reports.sales', ['preset' => 'last_7_days']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_7_days' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last 7 Days</a>
                <a href="{{ route('tenant.reports.sales', ['preset' => 'this_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'this_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">This Month</a>
                <a href="{{ route('tenant.reports.sales', ['preset' => 'last_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last Month</a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
            <!-- Branch Filter -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Branch / Outlet</label>
                <select name="branch_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600 cursor-pointer">
                    <option value="">All Branch Outlets</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600 cursor-pointer">
                    <option value="">All Payment Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online Transfer</option>
                </select>
            </div>

            <!-- Custom From Date -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from', $startDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
            </div>

            <!-- Custom To Date & Submit -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date To</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_to" value="{{ request('date_to', $endDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
                    <input type="hidden" name="preset" value="custom">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition cursor-pointer">
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- KPI Summary Row (4 Standalone Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Total Sales Amount -->
        <div class="bg-indigo-600 rounded-2xl p-5 text-white shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Total Net Sales</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-700 flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">PKR {{ number_format($totalSalesAmount, 2) }}</div>
            <p class="text-[11px] text-indigo-100 font-medium mt-1">{{ $startDate->format('M d') }} — {{ $endDate->format('M d, Y') }}</p>
        </div>

        <!-- Card 2: Orders Count -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Completed Orders</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalOrdersCount) }}</div>
            <p class="text-[11px] text-emerald-600 font-bold mt-1">Total Checkout Receipts</p>
        </div>

        <!-- Card 3: Avg Order Value (AOV) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Avg Order Value (AOV)</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calculator"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">PKR {{ number_format($avgOrderValue, 2) }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Average spent per ticket</p>
        </div>

        <!-- Card 4: Discounts & Tax -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Discounts & Tax</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-percent"></i>
                </span>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 tracking-tight">
                -PKR {{ number_format($totalDiscounts, 2) }}
            </div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Taxes Collected: PKR {{ number_format($totalTaxes, 2) }}</p>
        </div>

    </div>

    <!-- Payment Breakdown Pills -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-800 block">Cash Payments</span>
                    <span class="text-[11px] text-slate-500 font-medium">Physical Cash Received</span>
                </div>
            </div>
            <span class="text-sm font-extrabold text-slate-900">PKR {{ number_format($cashSales, 2) }}</span>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-800 block">Card Payments</span>
                    <span class="text-[11px] text-slate-500 font-medium">Credit / Debit Cards</span>
                </div>
            </div>
            <span class="text-sm font-extrabold text-slate-900">PKR {{ number_format($cardSales, 2) }}</span>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-800 block">Online / Bank Transfer</span>
                    <span class="text-[11px] text-slate-500 font-medium">Digital Transfer</span>
                </div>
            </div>
            <span class="text-sm font-extrabold text-slate-900">PKR {{ number_format($onlineSales, 2) }}</span>
        </div>
    </div>

    <!-- Detailed Sales Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-table text-indigo-600"></i>
                <h3 class="text-sm font-bold text-slate-900">Detailed Sales Register</h3>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ count($sales) }} Receipts Found</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-4 py-3">Receipt #</th>
                        <th class="px-4 py-3">Date & Time</th>
                        <th class="px-4 py-3">Branch Outlet</th>
                        <th class="px-4 py-3">Customer / Cashier</th>
                        <th class="px-4 py-3">Payment Method</th>
                        <th class="px-4 py-3 text-center">Items</th>
                        <th class="px-4 py-3 text-right">Discount</th>
                        <th class="px-4 py-3 text-right">Total Amount</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">
                            {{ $sale->sale_number }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $sale->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $sale->branch->name ?? 'Main Branch' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-slate-900 block">{{ $sale->patient->name ?? 'Walk-in Customer' }}</span>
                            <span class="text-[10px] text-slate-400">Cashier: {{ $sale->user->name ?? 'System' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($sale->payment_method == 'cash')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-money-bill-wave text-[10px]"></i> Cash
                                </span>
                            @elseif($sale->payment_method == 'card')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <i class="fa-solid fa-credit-card text-[10px]"></i> Card
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-cyan-50 text-cyan-700 border border-cyan-200">
                                    <i class="fa-solid fa-globe text-[10px]"></i> Online
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">
                            {{ $sale->items->sum('quantity') }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-amber-600">
                            {{ $sale->discount_amount > 0 ? '-PKR ' . number_format($sale->discount_amount, 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right font-extrabold text-slate-900 text-sm">
                            PKR {{ number_format($sale->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('pos.receipt', $sale->id) }}" target="_blank" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-[11px] font-bold rounded-md transition">
                                Receipt
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">No sales transactions found for the selected filter period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
