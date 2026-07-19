<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\PlatformInvoice;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\PlatformSale;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pehle expired tenants ko update kar do
        Tenant::chunk(100, function ($tenants) {
            foreach ($tenants as $tenant) {
                $tenant->markAsExpired();
            }
        });

        // 2. Tenant Counts
        $totalTenants      = Tenant::count();
        $activeTenants     = Tenant::where('status', 'active')->count();
        $trialTenants      = Tenant::where('status', 'trial')->count();
        $expiredTenants    = Tenant::where('status', 'expired')->count();
        $suspendedTenants  = Tenant::where('status', 'suspended')->count();

        // 3. Platform Level Counts
        $totalPlans = Plan::count();

        // 4. Total Users
        $totalUsers = User::count();

        // 5. Monthly Revenue
        $monthlyRevenue = PlatformInvoice::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total') ?? 0;

        // 6. New Registrations (is mahine ke naye tenants)
        $newRegistrations = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 7. Active Sessions (last 5 minute mein active)
        $activeSessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->count();

            // 8. Total Platform Sales
            $totalSales = PlatformSale::where('status', 'completed')->sum('total') ?? 0;

        // 9. Total Storage (DB size in MB)
        $result = DB::select(
            "SELECT SUM(data_length + index_length) AS size 
            FROM information_schema.tables 
            WHERE table_schema = ?",
            [config('database.connections.' . config('database.default') . '.database')]
        );
        $totalStorage = isset($result[0]->size) 
            ? number_format(round($result[0]->size / 1024 / 1024, 2), 2) 
            : '0.00';

        // 10. Recent Activities (Audit Logs se last 5)
        $recentActivities = AuditLog::with('admin')->latest()->take(5)->get();

        return view('platform.dashboard', compact(
            'totalTenants', 'activeTenants', 'trialTenants', 'expiredTenants', 'suspendedTenants',
            'totalPlans', 'totalUsers', 'totalSales', 'totalStorage', 'monthlyRevenue',
            'newRegistrations', 'activeSessions', 'recentActivities'
        ));
    }
}