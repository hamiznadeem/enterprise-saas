@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-md border border-emerald-100 uppercase tracking-wider">Financials</span>
                <span class="text-xs text-slate-400 font-medium">• Revenue & Income Summary</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Revenue Report</h2>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive financial breakdown of store sales and clinic consultation income.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 transition shadow-xs flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-print text-indigo-600"></i>
                <span>Print Report</span>
            </button>
            <a href="{{ route('tenant.reports.sales') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-indigo-600/20 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-xs"></i>
                <span>View Sales Log</span>
            </a>
        </div>
    </div>

    <!-- Filter Control Form -->
    <form method="GET" action="{{ route('tenant.reports.revenue') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-indigo-600"></i> Date & Branch Filters
            </span>

            <!-- Preset Pills -->
            <div class="flex flex-wrap items-center gap-1.5">
                <a href="{{ route('tenant.reports.revenue', ['preset' => 'today']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'today' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Today</a>
                <a href="{{ route('tenant.reports.revenue', ['preset' => 'yesterday']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'yesterday' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Yesterday</a>
                <a href="{{ route('tenant.reports.revenue', ['preset' => 'last_7_days']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_7_days' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last 7 Days</a>
                <a href="{{ route('tenant.reports.revenue', ['preset' => 'this_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'this_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">This Month</a>
                <a href="{{ route('tenant.reports.revenue', ['preset' => 'last_month']) }}" class="px-3 py-1 text-xs font-semibold rounded-lg border transition {{ $rangePreset == 'last_month' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Last Month</a>
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

            <!-- Custom From Date -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from', $startDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
            </div>

            <!-- Custom To Date -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to', $endDate->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium outline-none focus:border-indigo-600">
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <input type="hidden" name="preset" value="custom">
                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition cursor-pointer">
                    Generate Report
                </button>
            </div>
        </div>
    </form>

    <!-- 4 Standalone Revenue Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Card 1: Gross Revenue -->
        <div class="bg-indigo-600 rounded-2xl p-5 text-white shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-200">Gross Billing Revenue</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-700 flex items-center justify-center text-white text-xs">
                    <i class="fa-solid fa-chart-area"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold tracking-tight">PKR {{ number_format($grossRevenue, 2) }}</div>
            <p class="text-[11px] text-indigo-100 font-medium mt-1">Total revenue before deductions</p>
        </div>

        <!-- Card 2: Discounts Allowed -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Discounts Granted</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-tags"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">PKR {{ number_format($totalDiscounts, 2) }}</div>
            <p class="text-[11px] text-amber-600 font-bold mt-1">Customer promo & manual discounts</p>
        </div>

        <!-- Card 3: Tax Collected -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Taxes Collected</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">PKR {{ number_format($posTaxes, 2) }}</div>
            <p class="text-[11px] text-slate-500 font-medium mt-1">Government sales tax collected</p>
        </div>

        <!-- Card 4: Net Collected Revenue -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Net Revenue Collected</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 tracking-tight">PKR {{ number_format($netRevenue, 2) }}</div>
            <p class="text-[11px] text-emerald-600 font-bold mt-1">Net income in period</p>
        </div>

    </div>

    <!-- Revenue Streams Split -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-base font-bold">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">POS Product Sales Stream</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Retail, Mart & Pharmacy inventory billing</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-extrabold text-slate-900">PKR {{ number_format($posRevenue, 2) }}</div>
                <div class="text-[10px] font-bold text-indigo-600">
                    {{ $grossRevenue > 0 ? round(($posRevenue / $grossRevenue) * 100) : 0 }}% of Total
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-base font-bold">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Clinic & Doctor Services Stream</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Patient token fees & doctor consultation</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg font-extrabold text-slate-900">PKR {{ number_format($invoiceRevenue, 2) }}</div>
                <div class="text-[10px] font-bold text-emerald-600">
                    {{ $grossRevenue > 0 ? round(($invoiceRevenue / $grossRevenue) * 100) : 0 }}% of Total
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Audit Breakdown Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-indigo-600"></i>
                <h3 class="text-sm font-bold text-slate-900">Daily Revenue Audit Log</h3>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ count($dailyBreakdown) }} Days Recorded</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-center">Receipts / Invoices</th>
                        <th class="px-4 py-3 text-right">POS Sales</th>
                        <th class="px-4 py-3 text-right">Doctor / Clinic Fees</th>
                        <th class="px-4 py-3 text-right">Discounts</th>
                        <th class="px-4 py-3 text-right">Net Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dailyBreakdown as $row)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $row['date'] }} <span class="text-[10px] font-normal text-slate-400">({{ $row['day_name'] }})</span>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-slate-800">
                            {{ $row['receipts'] }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-slate-700">
                            PKR {{ number_format($row['pos_revenue'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-slate-700">
                            PKR {{ number_format($row['inv_revenue'], 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-amber-600">
                            {{ $row['discounts'] > 0 ? '-PKR ' . number_format($row['discounts'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right font-extrabold text-slate-900 text-sm">
                            PKR {{ number_format($row['net_revenue'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">No financial revenue records found for the selected period.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
