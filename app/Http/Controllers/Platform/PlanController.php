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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days'    => 'nullable|integer|min:0|max:365',
            'is_active'     => 'nullable|boolean',
            'limits'        => 'required|array',
            'limits.branches' => 'required|integer|min:1',
            'limits.users'    => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
            'features'      => 'nullable|array',
            'features.*'    => 'nullable',
        ]);

        // Clean up optional fields
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['features']  = $request->input('features', []);
        $validated['limits']    = [
            'branches' => (int) $request->input('limits.branches', 1),
            'users'    => (int) $request->input('limits.users', 5),
            'products' => (int) $request->input('limits.products', 100),
        ];

        $plan = Plan::create($validated);

        AuditLog::log('plan.create', "Created plan: {$plan->name}", $plan);

        return response()->json([
            'success' => true,
            'message' => 'Plan created successfully!',
            'plan'   => $plan,
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'trial_days'    => 'nullable|integer|min:0|max:365',
            'is_active'     => 'nullable|boolean',
            'limits'        => 'required|array',
            'limits.branches' => 'required|integer|min:1',
            'limits.users'    => 'required|integer|min:1',
            'limits.products' => 'required|integer|min:1',
            'features'      => 'nullable|array',
            'features.*'    => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['features']  = $request->input('features', []);
        $validated['limits']    = [
            'branches' => (int) $request->input('limits.branches', 1),
            'users'    => (int) $request->input('limits.users', 5),
            'products' => (int) $request->input('limits.products', 100),
        ];

        $plan->update($validated);

        // ✅ FIX: "Updated" not "Created"
        AuditLog::log('plan.update', "Updated plan: {$plan->name}", $plan);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully!',
            'plan'   => $plan->fresh(),
        ]);
    }

    public function destroy(Plan $plan)
    {
        // Active tenants check
        if ($plan->tenants()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete plan — active tenants are subscribed to it.',
            ], 400);
        }

        $name = $plan->name; // ✅ FIX: Capture name BEFORE delete
        $plan->delete();

        // ✅ FIX: "Deleted" not "Created" + use $name not $validated
        AuditLog::log('plan.delete', "Deleted plan: {$name}");

        return response()->json(['success' => true, 'message' => 'Plan deleted.']);
    }
}