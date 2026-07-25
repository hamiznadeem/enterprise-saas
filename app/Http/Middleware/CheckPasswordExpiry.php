<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PasswordExpiryService;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && PasswordExpiryService::isExpired(auth()->user())) {
            return redirect()->route('password.change')
                ->with('error', 'Your password has expired. Please set a new password.');
        }

        return $next($request);
    }
}