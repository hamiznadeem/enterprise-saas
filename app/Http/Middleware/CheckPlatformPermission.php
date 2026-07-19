<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatformPermission
{
    /**
     * Handle an incoming request.
     * Usage in route: ->middleware('permission:tenants.create')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = auth('platform')->user();

        if (!$admin || !$admin->hasPermissionTo($permission, 'platform')) {
            // Agar AJAX request hai toh JSON do, warna 403 page dikhao
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You do not have permission to perform this action.'
                ], 403);
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}