@extends('platform.layouts.app')

@section('header', 'Platform Sales Report')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 text-[11px] font-bold rounded-md border border-indigo-100 uppercase tracking-wider">Super Admin</span>
                <span class="text-xs text-slate-400 font-medium">• Tenant Subscriptions & Billing</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Platform Sales & Subscriptions</h2>
            <p class="text-xs text-slate-500 mt-0.5">Filter SaaS subscription sales by date presets, tenant accounts, and plans.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-print text-indigo-600"></i>
                <span>Print Report</span>
            </button>
            <a href="{{ route('platform.reports.revenue') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-xs"></i>
                <span>Platform Revenue Report</span>
            </a>
        </div>
    </div>

    <!-- Filter Control Form -->
    <form method="GET" action="{{ route('platform.reports.sales') }}" class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-indigo-600"></i> Filter Platform Sales
            </span>

            <!-- Date Preset Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('platform.reports.sales', ['preset' => 'today']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'today' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Today</a>
                <a href="{{ route('platform.reports.sales', ['preset' => 'yesterday']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'yesterday' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Yesterday</a>
                <a href="{{ route('platform.reports.sales', ['preset' => 'last_7_days']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_7_days' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last 7 Days</a>
                <a href="{{ route('platform.reports.sales', ['preset' => 'this_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'this_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">This Month</a>
                <a href="{{ route('platform.reports.sales', ['preset' => 'last_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last Month</a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
            <!-- Tenant Store Filter -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tenant Store</label>
                <select name="tenant_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium outline-none focus:border-indigo-600 cursor-pointer">
                    <option value="">All Stores</option>
                    @foreach($tenants as $t)
                        <option value="{{ $t->id }}" {{ request('tenant_id') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->domain }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Subscription Plan Filter -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Subscription Plan</label>
                <select name="plan_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium outline-none focus:border-indigo-600 cursor-pointer">
                    <option value="">All Subscription Plans</option>
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Custom Date From -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from', $startDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
            </div>

            <!-- Custom Date To & Submit -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date To</label>
                <div class="flex items-center gap-2">
                    <input type="date" name="date_to" value="{{ request('date_to', $endDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
                    <input type="hidden" name="preset" value="custom">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- 4 Standalone KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Total Platform Subscription Revenue -->
        <div class="bg-indigo-600 rounded-xl p-5 text-white shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Collected Subscriptions</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-700 flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">${{ number_format($totalSalesAmount, 2) }}</div>
            <p class="text-[11px] text-indigo-100 font-medium mt-1">{{ $startDate->format('M d') }} — {{ $endDate->format('M d, Y') }}</p>
        </div>

        <!-- Card 2: Total Invoices Count -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Invoices Generated</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-invoice"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalOrdersCount) }}</div>
            <p class="text-[11px] text-emerald-600 font-bold mt-1">Total platform billing invoices</p>
        </div>

        <!-- Card 3: Paid Invoices -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Paid Invoices</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($paidOrdersCount) }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Successfully settled accounts</p>
        </div>

        <!-- Card 4: Avg Subscription Value -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Avg Subscription Value</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calculator"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">${{ number_format($avgOrderValue, 2) }}</div>
            <p class="text-[11px] text-purple-600 font-bold mt-1">Average per paid plan</p>
        </div>

    </div>

    <!-- Detailed Platform Sales & Invoices Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list text-indigo-600"></i>
                <h3 class="text-sm font-bold text-slate-900">Platform Subscription Sales Register</h3>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ count($invoices) }} Invoices Found</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-4 py-3">Invoice #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Tenant Store</th>
                        <th class="px-4 py-3">Plan Name</th>
                        <th class="px-4 py-3">Billing Cycle</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">
                            #INV-{{ str_pad($inv->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-4 py-3 font-medium">
                            {{ $inv->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-slate-900 block">{{ $inv->tenant->name ?? 'Deleted Tenant' }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $inv->tenant->domain ?? '' }}.yoursaas.com</span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $inv->plan->name ?? 'Custom SaaS Plan' }}
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-600">
                            Monthly
                        </td>
                        <td class="px-4 py-3">
                            @if($inv->status === 'paid')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i> Paid
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fa-solid fa-clock text-[10px]"></i> Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-extrabold text-slate-900 text-sm">
                            ${{ number_format($inv->total, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('platform.invoices.show', $inv->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-[11px] font-bold rounded-md transition">
                                View Invoice
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">No platform sales transactions recorded for the selected filter period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
