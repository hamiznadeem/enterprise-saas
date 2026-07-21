<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->tenant_id) {
                $tenant = $user->tenant;

                // ── 1. Already expired ──
                if ($tenant->status === 'expired') {
                    if (!$request->routeIs('tenant.billing')) {
                        return redirect()->route('tenant.billing')
                            ->with('error', 'Your subscription has expired. Please renew to continue.');
                    }
                }

                // ── 2. Trial expired — auto mark ──
                if ($tenant->status === 'trial' && $tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
                    $tenant->update(['status' => 'expired', 'on_trial' => false]);

                    if (!$request->routeIs('tenant.billing')) {
                        return redirect()->route('tenant.billing')
                            ->with('error', 'Your 14-day trial has expired. Please upgrade to continue.');
                    }
                }

                // ── 3. Active plan expired — auto mark ──
                if ($tenant->status === 'active' && $tenant->will_expire_at && $tenant->will_expire_at->isPast()) {
                    $tenant->update(['status' => 'expired']);

                    if (!$request->routeIs('tenant.billing')) {
                        return redirect()->route('tenant.billing')
                            ->with('error', 'Your subscription has expired. Please renew to continue.');
                    }
                }

                // ── 4. Suspended or inactive — force logout ──
                if ($tenant->status === 'suspended' || !$tenant->is_active) {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('tenantView.login')
                        ->withErrors(['email' => 'Your account has been suspended. Contact support.']);
                }
            }
        }

        return $next($request);
    }
}