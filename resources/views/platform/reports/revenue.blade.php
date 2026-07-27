@extends('platform.layouts.app')

@section('header', 'Platform Revenue Report')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-md border border-emerald-100 uppercase tracking-wider">Super Admin</span>
                <span class="text-xs text-slate-400 font-medium">• Platform SaaS Revenue</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Platform Financial Revenue</h2>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive MRR, plan revenue distribution, and collected subscription income.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-print text-indigo-600"></i>
                <span>Print Report</span>
            </button>
            <a href="{{ route('platform.reports.sales') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-list text-xs"></i>
                <span>Platform Sales Register</span>
            </a>
        </div>
    </div>

    <!-- Filter Control Form -->
    <form method="GET" action="{{ route('platform.reports.revenue') }}" class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-indigo-600"></i> Date & Plan Filters
            </span>

            <!-- Date Preset Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('platform.reports.revenue', ['preset' => 'today']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'today' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Today</a>
                <a href="{{ route('platform.reports.revenue', ['preset' => 'yesterday']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'yesterday' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Yesterday</a>
                <a href="{{ route('platform.reports.revenue', ['preset' => 'last_7_days']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_7_days' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last 7 Days</a>
                <a href="{{ route('platform.reports.revenue', ['preset' => 'this_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'this_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">This Month</a>
                <a href="{{ route('platform.reports.revenue', ['preset' => 'last_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last Month</a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-1">
            <!-- Plan Filter -->
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

            <!-- Custom Date To -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to', $endDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <input type="hidden" name="preset" value="custom">
                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                    Generate Revenue Report
                </button>
            </div>
        </div>
    </form>

    <!-- 4 Standalone Revenue Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: MRR (Monthly Recurring Revenue) -->
        <div class="bg-indigo-600 rounded-xl p-5 text-white shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Monthly Recurring (MRR)</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-700 flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-chart-line"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">${{ number_format($mrr, 2) }}</div>
            <p class="text-[11px] text-indigo-100 font-medium mt-1">Current month recurring revenue</p>
        </div>

        <!-- Card 2: Collected Net Revenue -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Net Collected Revenue</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">${{ number_format($collectedNet, 2) }}</div>
            <p class="text-[11px] text-emerald-600 font-bold mt-1">Settled payments in period</p>
        </div>

        <!-- Card 3: Pending Invoices -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending / Unpaid Revenue</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">${{ number_format($pendingRevenue, 2) }}</div>
            <p class="text-[11px] text-amber-600 font-bold mt-1">Outstanding tenant invoices</p>
        </div>

        <!-- Card 4: Gross Invoiced Revenue -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Gross Invoiced Volume</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">${{ number_format($grossRevenue, 2) }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Total billed to stores</p>
        </div>

    </div>

    <!-- Revenue Contribution by Plan -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Revenue Contribution by Subscription Plan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($planBreakdown as $planItem)
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-900 block">{{ $planItem['plan_name'] }}</span>
                    <span class="text-[11px] text-slate-500 font-medium">{{ $planItem['count'] }} Paid Subscriptions</span>
                </div>
                <div class="text-right">
                    <span class="text-base font-extrabold text-indigo-600 block">${{ number_format($planItem['revenue'], 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Revenue Audit Breakdown Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-indigo-600"></i>
                <h3 class="text-sm font-bold text-slate-900">Daily Revenue Audit Stream</h3>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ count($dailyBreakdown) }} Days Recorded</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-center">Invoices Generated</th>
                        <th class="px-4 py-3 text-right">Net Collected Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dailyBreakdown as $row)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $row['date'] }} <span class="text-[10px] font-normal text-slate-400">({{ $row['day_name'] }})</span>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">
                            {{ $row['invoices'] }}
                        </td>
                        <td class="px-4 py-3 text-right font-extrabold text-slate-900 text-sm">
                            ${{ number_format($row['net_revenue'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">No platform revenue records found for the selected period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
