<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('admin')->latest()->paginate(50);
        return view('platform.audit-logs.index', compact('logs'));
    }
}