<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        // Agar user login hai
        if (auth()->check()) {
            $user = auth()->user();

            // Agar user kisi Tenant (Clinic) se belong karta hai (Super Admin nahi hai)
            if ($user->tenant_id) {
                $tenant = $user->tenant;

                // Agar tenant ka trial khatam ho gaya hai
                if ($tenant->trial_ends_at && $tenant->trial_ends_at->isPast()) {
                    
                    // Agar user 'billing' (payment) page par ja raha hai, toh usko jane do
                    // Warna usko billing page par redirect kar do
                    if (!$request->is('billing')) {
                        return redirect()->route('billing')->with('error', 'Your 14-day trial has expired. Please upgrade to continue.');
                    }
                }
            }
        }

        // Agar trial nahi khatam, toh user ko aage jaane do (Dashboard par)
        return $next($request);
    }
}