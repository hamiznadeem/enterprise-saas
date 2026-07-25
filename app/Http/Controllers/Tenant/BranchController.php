<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Switch active branch
     */
    public function switchBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|integer|exists:user_branches,id',
        ]);

        $user = Auth::user();

        if (!$user->hasBranchAccess($request->branch_id)) {
            return back()->withErrors(['error' => 'You do not have access to this branch.']);
        }

        session(['current_branch_id' => $request->branch_id]);

        $branchName = $user->branches()->where('id', $request->branch_id)->value('branch_name') ?? 'Branch';

        return back()->with('status', "Switched to {$branchName}.");
    }

    /**
     * Get branches for AJAX dropdown
     */
    public function getBranches()
    {
        $user = Auth::user();
        $branches = $user->branches()->where('is_active', true)->get(['id', 'branch_name', 'is_default']);
        $currentId = session('current_branch_id');

        return response()->json([
            'branches' => $branches,
            'current_id' => $currentId,
        ]);
    }
}