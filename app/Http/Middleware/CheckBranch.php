<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranch
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // If user has no branches assigned, skip
        $branches = $user->branches()->where('is_active', true)->get();
        if ($branches->isEmpty()) {
            return $next($request);
        }

        // Get current branch from session
        $currentBranchId = session('current_branch_id');

        // If no branch selected, set default
        if (!$currentBranchId) {
            $default = $user->getDefaultBranch();
            if ($default) {
                session(['current_branch_id' => $default->id]);
            } else {
                session(['current_branch_id' => $branches->first()->id]);
            }
        }

        // Validate that selected branch belongs to user
        $currentBranchId = session('current_branch_id');
        if ($currentBranchId && !$user->hasBranchAccess($currentBranchId)) {
            session()->forget('current_branch_id');
            $default = $user->getDefaultBranch();
            session(['current_branch_id' => $default ? $default->id : $branches->first()->id]);
        }

        // Store in app container for easy access
        $currentBranch = $user->branches()->where('id', session('current_branch_id'))->first();
        app()->instance('currentBranch', $currentBranch);

        return $next($request);
    }
}