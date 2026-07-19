<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // Super Admin Dashboard (Global Stats)
  public function dashboard()
{
    $tenants = \App\Models\Tenant::withCount(['users', 'invoices'])->latest()->get();
    $stats = [
        'total_tenants' => $tenants->count(),
        'active_trials' => $tenants->where('trial_ends_at', '>', now())->count(),
        'expired_trials' => $tenants->where('trial_ends_at', '<=', now())->count(),
        'global_revenue' => \App\Models\Invoice::sum('total_amount'),
    ];
    return view('super-admin.dashboard', compact('tenants', 'stats'));
}
}