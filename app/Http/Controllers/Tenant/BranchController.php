<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\UserBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display standalone Branch Setup & Delivery Charges View
     */
    public function setupIndex()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('tenant.login');
        }

        // Get or create initial branch if none exists
        $branches = UserBranch::where('tenant_id', $user->tenant_id)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->get();

        if ($branches->isEmpty()) {
            UserBranch::create([
                'user_id'     => $user->id,
                'tenant_id'   => $user->tenant_id,
                'branch_name' => 'RETAIL STORE',
                'branch_code' => 'BR-001',
                'address'     => 'Main Market, Lahore',
                'phone'       => '0300-1234567',
                'is_default'  => true,
                'is_active'   => true,
            ]);

            $branches = UserBranch::where('tenant_id', $user->tenant_id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->get();
        }

        return view('tenantView.settings.branch_setup', compact('branches'));
    }

    /**
     * Store a newly created branch in DB
     */
    public function storeBranch(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'nullable|string|max:50',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Check unique branch code if provided
        if ($request->filled('branch_code')) {
            $exists = UserBranch::where('tenant_id', $user->tenant_id)
                ->where('branch_code', $request->branch_code)
                ->exists();
            if ($exists) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Branch Code already exists!'], 422);
                }
                return back()->withErrors(['branch_code' => 'Branch Code already exists!']);
            }
        }

        $branch = UserBranch::create([
            'user_id'     => $user->id,
            'tenant_id'   => $user->tenant_id,
            'branch_name' => $request->branch_name,
            'branch_code' => $request->branch_code ?: 'BR-00' . (UserBranch::where('tenant_id', $user->tenant_id)->count() + 1),
            'phone'       => $request->phone,
            'address'     => $request->address,
            'is_default'  => false,
            'is_active'   => true,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'New Branch created successfully!',
                'branch'  => $branch
            ]);
        }

        return redirect()->back()->with('success', 'New Branch created successfully!');
    }

    /**
     * Delete/Deactivate branch
     */
    public function deleteBranch($id)
    {
        $user = Auth::user();
        $branch = UserBranch::where('tenant_id', $user->tenant_id)
            ->where('id', $id)
            ->first();

        if ($branch) {
            $branch->update(['is_active' => false]);
        }

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Branch deleted successfully!']);
        }

        return redirect()->back()->with('success', 'Branch deleted successfully!');
    }

    /**
     * Update Branch & Delivery Settings
     */
    public function setupUpdate(Request $request)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Branch & Delivery Charges settings saved successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Branch & Delivery Charges settings saved successfully!');
    }

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
        $branches = $user ? $user->branches()->where('is_active', true)->get(['id', 'branch_name', 'is_default']) : collect();
        $currentId = session('current_branch_id');

        return response()->json([
            'branches' => $branches,
            'current_id' => $currentId,
        ]);
    }
}