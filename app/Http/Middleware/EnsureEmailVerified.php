<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Fetch tenant using tenant_id directly
            $tenant = $user->tenant_id ? Tenant::find($user->tenant_id) : null;

            // 1. FREE TRIAL ACCOUNT -> NO VERIFICATION NEEDED! Full access freely.
            if ($tenant && ($tenant->status === 'trial' || $tenant->on_trial || ($tenant->trial_ends_at && $tenant->trial_ends_at->isFuture()))) {
                return $next($request);
            }

            // 2. ACTIVE / RENEWED ACCOUNT -> ENFORCE EMAIL VERIFICATION
            if ($tenant && $tenant->status === 'active' && !$user->hasVerifiedEmail()) {
                if (!$request->routeIs('tenant.verification.*') && !$request->routeIs('logout')) {
                    return redirect()->route('tenant.verification.notice')
                        ->with('warning', 'Your subscription is active/renewed. Please verify your email address to access your dashboard.');
                }
            }
        }

        return $next($request);
    }
}