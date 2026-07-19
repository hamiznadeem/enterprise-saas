<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Domain;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pehle check karo domain se aaya hai?
        $hostname = $request->getHost();
        $domain = Domain::where('domain', $hostname)->first();

        if ($domain) {
            app()->instance('currentTenant', $domain->tenant);
        } 
        // 2. Agar localhost hai aur user logged in hai
        elseif (Auth::check() && Auth::user()->tenant_id) {
            app()->instance('currentTenant', Auth::user()->tenant);
        }

        return $next($request);
    }
}