<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('price')->get();
        return view('platform.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days' => 'nullable|integer|min:0',
            'limits.branches' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
        ]);

        $validated['limits'] = [
            'branches' => $request->input('limits.branches'),
            'users' => $request->input('limits.users'),
            'products' => $request->input('limits.products'),
        ];

        Plan::create($validated);

        AuditLog::log('create', "Created Plan: {$validated['name']}");
    
        return response()->json(['success' => true, 'message' => 'Plan created successfully!']);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days' => 'nullable|integer|min:0',
            'limits.branches' => 'required|integer|min:1',
            'limits.users' => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
        ]);

        $validated['limits'] = [
            'branches' => $request->input('limits.branches'),
            'users' => $request->input('limits.users'),
            'products' => $request->input('limits.products'),
        ];

        $plan->update($validated);

        AuditLog::log('create', "Created Plan: {$validated['name']}");

        return response()->json(['success' => true, 'message' => 'Plan updated successfully!']);
    }

    public function destroy(Plan $plan)
    {
        if($plan->tenants()->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete plan assigned to tenants.'], 400);
        }
        $plan->delete();

        AuditLog::log('create', "Created Plan: {$validated['name']}");
        
        return response()->json(['success' => true, 'message' => 'Plan deleted.']);
    }
}