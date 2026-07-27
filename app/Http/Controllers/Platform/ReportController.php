<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlatformSale;
use App\Models\PlatformInvoice;
use App\Models\Tenant;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Platform Sales Report (Tenant Subscription Purchases & Plan Sales)
     */
    public function sales(Request $request)
    {
        $tenants = Tenant::all();
        $plans   = Plan::all();

        // Date Range Handling
        $rangePreset = $request->input('preset', 'this_month');
        $dateFrom    = $request->input('date_from');
        $dateTo      = $request->input('date_to');

        if ($rangePreset === 'today') {
            $startDate = Carbon::today();
            $endDate   = Carbon::today()->endOfDay();
        } elseif ($rangePreset === 'yesterday') {
            $startDate = Carbon::yesterday();
            $endDate   = Carbon::yesterday()->endOfDay();
        } elseif ($rangePreset === 'last_7_days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate   = Carbon::now()->endOfDay();
        } elseif ($rangePreset === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();
        } elseif ($rangePreset === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate   = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($rangePreset === 'custom' && $dateFrom && $dateTo) {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate   = Carbon::parse($dateTo)->endOfDay();
        } else {
            $startDate   = Carbon::now()->startOfMonth();
            $endDate     = Carbon::now()->endOfMonth();
            $rangePreset = 'this_month';
        }

        // Query Platform Sales & Invoices
        $query = PlatformInvoice::with(['tenant', 'plan'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->input('tenant_id'));
        }

        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->input('plan_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $invoices = $query->latest()->get();

        // KPI Calculations
        $totalSalesAmount = $invoices->where('status', 'paid')->sum('total');
        $totalOrdersCount = $invoices->count();
        $paidOrdersCount  = $invoices->where('status', 'paid')->count();
        $avgOrderValue    = $paidOrdersCount > 0 ? ($totalSalesAmount / $paidOrdersCount) : 0;

        return view('platform.reports.sales', compact(
            'invoices', 'tenants', 'plans', 'rangePreset', 'startDate', 'endDate',
            'totalSalesAmount', 'totalOrdersCount', 'paidOrdersCount', 'avgOrderValue'
        ));
    }

    /**
     * Platform Revenue Report (MRR, Plan Breakdown, Daily Revenue Audit)
     */
    public function revenue(Request $request)
    {
        $plans   = Plan::all();
        $tenants = Tenant::all();

        // Date Range Handling
        $rangePreset = $request->input('preset', 'this_month');
        $dateFrom    = $request->input('date_from');
        $dateTo      = $request->input('date_to');

        if ($rangePreset === 'today') {
            $startDate = Carbon::today();
            $endDate   = Carbon::today()->endOfDay();
        } elseif ($rangePreset === 'yesterday') {
            $startDate = Carbon::yesterday();
            $endDate   = Carbon::yesterday()->endOfDay();
        } elseif ($rangePreset === 'last_7_days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate   = Carbon::now()->endOfDay();
        } elseif ($rangePreset === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();
        } elseif ($rangePreset === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate   = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($rangePreset === 'custom' && $dateFrom && $dateTo) {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate   = Carbon::parse($dateTo)->endOfDay();
        } else {
            $startDate   = Carbon::now()->startOfMonth();
            $endDate     = Carbon::now()->endOfMonth();
            $rangePreset = 'this_month';
        }

        // Fetch Invoices
        $invoicesQuery = PlatformInvoice::with(['tenant', 'plan'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('plan_id')) {
            $invoicesQuery->where('plan_id', $request->input('plan_id'));
        }

        $invoices = $invoicesQuery->get();

        // Financial KPIs
        $grossRevenue    = $invoices->sum('total');
        $collectedNet    = $invoices->where('status', 'paid')->sum('total');
        $pendingRevenue  = $invoices->where('status', 'unpaid')->sum('total');

        // Monthly Recurring Revenue (MRR) calculation
        $mrr = PlatformInvoice::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total');

        // Plan Breakdown
        $planBreakdown = [];
        foreach ($plans as $plan) {
            $planInvoices = $invoices->where('plan_id', $plan->id)->where('status', 'paid');
            $planBreakdown[] = [
                'plan_name' => $plan->name,
                'count'     => $planInvoices->count(),
                'revenue'   => $planInvoices->sum('total'),
            ];
        }

        // Daily Revenue Table
        $dailyBreakdown = [];
        $daysCount = $startDate->diffInDays($endDate) + 1;
        $period = new \DatePeriod(
            $startDate->copy()->startOfDay(),
            new \DateInterval('P1D'),
            $endDate->copy()->endOfDay()
        );

        foreach ($period as $date) {
            $dayStr = $date->format('Y-m-d');
            $dayInvoices = $invoices->filter(fn($i) => $i->created_at->format('Y-m-d') === $dayStr);

            $paidSum = $dayInvoices->where('status', 'paid')->sum('total');
            $totalCount = $dayInvoices->count();

            if ($totalCount > 0 || $daysCount <= 31) {
                $dailyBreakdown[] = [
                    'date'        => $date->format('M d, Y'),
                    'day_name'    => $date->format('D'),
                    'invoices'    => $totalCount,
                    'net_revenue' => $paidSum,
                ];
            }
        }

        return view('platform.reports.revenue', compact(
            'plans', 'rangePreset', 'startDate', 'endDate',
            'grossRevenue', 'collectedNet', 'pendingRevenue', 'mrr',
            'planBreakdown', 'dailyBreakdown'
        ));
    }
}
