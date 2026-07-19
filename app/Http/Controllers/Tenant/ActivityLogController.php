<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TenantActivityLog::with('user');

        // Filter by action
        if ($action = $request->get('action')) {
            $query->where('action', $action);
        }

        // Filter by user
        if ($userId = $request->get('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by date
        if ($date = $request->get('date')) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->latest()->paginate(25);
        $users = \App\Models\User::where('tenant_id', auth()->user()->tenant_id)->get();

        // Action list for filter dropdown
        $actions = TenantActivityLog::select('action')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('tenantView.activity-logs.index', compact('logs', 'users', 'actions'));
    }
}