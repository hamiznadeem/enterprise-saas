<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Invoice;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Sales Report (POS Orders, Items, Payment Methods)
     */
    public function salesReport(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $branches = Branch::where('tenant_id', $tenantId)->get();

        // 1. Date Range Handling
        $rangePreset = $request->input('preset', 'this_month');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($rangePreset === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($rangePreset === 'yesterday') {
            $startDate = Carbon::yesterday();
            $endDate = Carbon::yesterday()->endOfDay();
        } elseif ($rangePreset === 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($rangePreset === 'last_7_days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } elseif ($rangePreset === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($rangePreset === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($rangePreset === 'custom' && $dateFrom && $dateTo) {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $rangePreset = 'this_month';
        }

        // 2. Base Query
        $query = Sale::with(['patient', 'user', 'branch', 'items'])
            ->where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        $sales = $query->latest()->get();

        // 3. KPI Calculations
        $totalSalesAmount = $sales->sum('total_amount');
        $totalOrdersCount = $sales->count();
        $avgOrderValue    = $totalOrdersCount > 0 ? ($totalSalesAmount / $totalOrdersCount) : 0;
        $totalDiscounts   = $sales->sum('discount_amount');
        $totalTaxes       = $sales->sum('tax_amount');

        // Payment Method Breakdown
        $cashSales   = $sales->where('payment_method', 'cash')->sum('total_amount');
        $cardSales   = $sales->where('payment_method', 'card')->sum('total_amount');
        $onlineSales = $sales->where('payment_method', 'online')->sum('total_amount');
        $otherSales  = $totalSalesAmount - ($cashSales + $cardSales + $onlineSales);

        return view('tenantView.reports.sales', compact(
            'sales', 'branches', 'rangePreset', 'startDate', 'endDate',
            'totalSalesAmount', 'totalOrdersCount', 'avgOrderValue',
            'totalDiscounts', 'totalTaxes', 'cashSales', 'cardSales',
            'onlineSales', 'otherSales'
        ));
    }

    /**
     * Revenue & Financial Report (Gross, Net, Invoices, Daily Trend)
     */
    public function revenueReport(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;
        $branches = Branch::where('tenant_id', $tenantId)->get();

        // 1. Date Range Handling
        $rangePreset = $request->input('preset', 'this_month');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($rangePreset === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($rangePreset === 'yesterday') {
            $startDate = Carbon::yesterday();
            $endDate = Carbon::yesterday()->endOfDay();
        } elseif ($rangePreset === 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($rangePreset === 'last_7_days') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } elseif ($rangePreset === 'this_month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($rangePreset === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
        } elseif ($rangePreset === 'custom' && $dateFrom && $dateTo) {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $rangePreset = 'this_month';
        }

        // 2. Fetch POS Sales
        $salesQuery = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        if ($request->filled('branch_id')) {
            $salesQuery->where('branch_id', $request->input('branch_id'));
        }
        $sales = $salesQuery->get();

        // 3. Fetch Invoices (Clinic / Doctor / Service Fees)
        $invoiceQuery = Invoice::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('branch_id')) {
            $invoiceQuery->where('branch_id', $request->input('branch_id'));
        }
        $invoices = $invoiceQuery->get();

        // 4. Financial Calculations
        $posRevenue      = $sales->sum('total_amount');
        $invoiceRevenue  = $invoices->sum('total_amount');
        $grossRevenue    = $posRevenue + $invoiceRevenue;

        $posDiscounts    = $sales->sum('discount_amount');
        $invoiceDiscounts = $invoices->sum('discount');
        $totalDiscounts  = $posDiscounts + $invoiceDiscounts;

        $posTaxes        = $sales->sum('tax_amount');
        $netRevenue      = $grossRevenue; // net collected revenue

        // 5. Daily Breakdown Table Grouping
        $dailyBreakdown = [];
        $daysCount = $startDate->diffInDays($endDate) + 1;
        $period = new \DatePeriod(
            $startDate->copy()->startOfDay(),
            new \DateInterval('P1D'),
            $endDate->copy()->endOfDay()
        );

        foreach ($period as $date) {
            $dayStr = $date->format('Y-m-d');
            $daySales = $sales->filter(fn($s) => $s->created_at->format('Y-m-d') === $dayStr);
            $dayInvoices = $invoices->filter(fn($i) => $i->created_at->format('Y-m-d') === $dayStr);

            $posTotal = $daySales->sum('total_amount');
            $invTotal = $dayInvoices->sum('total_amount');
            $discTotal = $daySales->sum('discount_amount') + $dayInvoices->sum('discount');
            $netTotal = $posTotal + $invTotal;

            if ($netTotal > 0 || $daysCount <= 31) {
                $dailyBreakdown[] = [
                    'date'         => $date->format('M d, Y'),
                    'day_name'     => $date->format('D'),
                    'receipts'     => $daySales->count() + $dayInvoices->count(),
                    'pos_revenue'  => $posTotal,
                    'inv_revenue'  => $invTotal,
                    'discounts'    => $discTotal,
                    'net_revenue'  => $netTotal,
                ];
            }
        }

        return view('tenantView.reports.revenue', compact(
            'branches', 'rangePreset', 'startDate', 'endDate',
            'posRevenue', 'invoiceRevenue', 'grossRevenue', 'totalDiscounts',
            'posTaxes', 'netRevenue', 'dailyBreakdown'
        ));
    }
}
