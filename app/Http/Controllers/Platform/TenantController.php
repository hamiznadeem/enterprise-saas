<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Models\Plan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', 1)->get();
        
        $query = Tenant::with('plan');
        
        if ($search = request()->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%")
                  ->orWhere('owner_email', 'like', "%{$search}%");
            });
        }
        
        if ($status = request()->get('status')) {
            $query->where('status', $status);
        }
        
        if ($planId = request()->get('plan_id')) {
            $query->where('plan_id', $planId);
        }
        
        if (request()->ajax()) {
            return response()->json($query->latest()->get());
        }

        $tenants = $query->latest()->get();
        return view('platform.tenants.index', compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->whereNull('deleted_at'),
                Rule::unique('domains', 'domain'),
            ],
            'plan_id' => 'required|exists:plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        $expiryDate = null;
        $status = 'active';

        if ($plan->trial_days > 0) {
            $expiryDate = now()->addDays($plan->trial_days);
            $status = 'trial';
        } else {
            $expiryDate = now()->addDays($plan->duration_in_days ?? 30);
        }

        DB::beginTransaction();

        try {
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'location' => $validated['location'] ?? null,
                'plan_id' => $validated['plan_id'],
                'status' => $status,
                'is_active' => 1,
                'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                'will_expire_at' => $expiryDate,
                'enabled_modules' => $plan->features ?? ['clinic' => true, 'pos' => true, 'pharmacy' => true],
            ]);

            Domain::create([
                'domain' => $validated['domain'] . '.yoursaas.com',
                'tenant_id' => $tenant->id,
            ]);

            $password = Str::random(12);

            $owner = User::create([
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($password),
                'tenant_id' => $tenant->id,
                'role' => 'owner',
                'email_verified_at' => now(),
            ]);

            $owner->assignRole('owner');

            $tenant->subscriptions()->create([
                'plan_id' => $validated['plan_id'],
                'starts_at' => now(),
                'ends_at' => $expiryDate,
                'status' => $status,
                'trial_days' => $plan->trial_days,
            ]);

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_created',
                'description' => "Created tenant: {$tenant->name}",
                'properties' => [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->name,
                    'domain' => $validated['domain'] . '.yoursaas.com',
                    'owner_email' => $validated['owner_email'],
                    'plan' => $plan->name,
                    'plan_id' => $plan->id,
                    'status' => $status,
                ],
            ]);

            DB::commit();

            try {
                $tenant->notify(new \App\Notifications\TenantCreatedNotification($tenant, [
                    'email' => $validated['owner_email'],
                    'password' => $password,
                ]));
            } catch (\Exception $e) {
                // Email fail hone pe tenant creation rokna nahi chahiye
            }

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully!',
                'credentials' => [
                    'email' => $validated['owner_email'],
                    'password' => $password,
                ],
                'tenant' => [
                    'name' => $tenant->name,
                    'domain' => $validated['domain'] . '.yoursaas.com',
                    'plan' => $plan->name,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $errorMsg = 'Error creating tenant.';
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'Unique')) {
                $errorMsg = 'Owner email or domain already exists.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
            ], 500);
        }
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->ignore($tenant->id)->whereNull('deleted_at'),
                Rule::unique('domains', 'domain')->ignore($tenant->domains()->value('id')),
            ],
            'plan_id' => 'required|exists:plans,id',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        DB::beginTransaction();

        try {
            $oldData = $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']);
            $planChanged = $tenant->plan_id != $plan->id;

            $updateData = [
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'plan_id' => $validated['plan_id'],
                'phone' => $validated['phone'] ?? null,
                'city' => $validated['city'] ?? null,
                'location' => $validated['location'] ?? null,
            ];

            if ($planChanged) {
                $updateData['will_expire_at'] = now()->addDays($plan->duration_in_days ?? 30);
                $updateData['status'] = 'active';
                $updateData['trial_ends_at'] = null;
            }

            $tenant->update($updateData);

            $tenant->domains()->update([
                'domain' => $validated['domain'] . '.yoursaas.com',
            ]);

            $owner = $tenant->users()->where('role', 'owner')->first();
            if ($owner) {
                $owner->update([
                    'name' => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                ]);
            }

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_updated',
                'description' => "Updated tenant: {$tenant->name}",
                'properties' => [
                    'tenant_id' => $tenant->id,
                    'old' => $oldData,
                    'new' => $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']),
                    'plan_changed' => $planChanged,
                ],
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Tenant updated successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating tenant.'], 500);
        }
    }

    public function renew(Tenant $tenant)
    {
        $plan = $tenant->plan;
        
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'No plan assigned to this tenant.'], 422);
        }
        
        $oldExpiry = $tenant->will_expire_at?->toDateTimeString();
        $baseDate = $tenant->will_expire_at && $tenant->will_expire_at->isFuture() ? $tenant->will_expire_at : Carbon::now();
        
        $tenant->update([
            'will_expire_at' => $baseDate->copy()->addDays($plan->duration_in_days ?? 30),
            'status' => 'active',
            'is_active' => 1,
        ]);

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'tenant_renewed',
            'description' => "Renewed tenant: {$tenant->name} for " . ($plan->duration_in_days ?? 30) . " days",
            'properties' => [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'plan' => $plan->name,
                'days_added' => $plan->duration_in_days ?? 30,
                'old_expiry' => $oldExpiry,
                'new_expiry' => $tenant->will_expire_at->toDateTimeString(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Subscription renewed successfully!']);
    }

    public function toggleStatus(Tenant $tenant)
    {
        $oldStatus = $tenant->status;
        $newStatus = $tenant->is_active ? 0 : 1;
        $tenant->update([
            'is_active' => $newStatus,
            'status' => $newStatus ? 'active' : 'suspended'
        ]);

        $action = $newStatus ? 'activated' : 'suspended';

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'tenant_status_toggled',
            'description' => "{$action} tenant: {$tenant->name}",
            'properties' => [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'old_status' => $oldStatus,
                'new_status' => $tenant->status,
            ],
        ]);

        return response()->json(['success' => true, 'message' => "Tenant {$action}!"]);
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('subscriptions.plan', 'plan');
        $modules = $tenant->enabled_modules ?? ($tenant->plan ? $tenant->plan->features : []);
        $plans = Plan::where('is_active', 1)->get();
        return view('platform.tenants.show', compact('tenant', 'modules', 'plans'));
    }

    public function toggleModule(Request $request, Tenant $tenant)
    {
        $request->validate(['module' => 'required|string']);
        
        $modules = $tenant->enabled_modules ?? [];
        $oldState = $modules[$request->module] ?? false;
        $modules[$request->module] = !$oldState;
        
        $tenant->update(['enabled_modules' => $modules]);

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'module_toggled',
            'description' => ucfirst($request->module) . ' ' . ($oldState ? 'disabled' : 'enabled') . ' for: ' . $tenant->name,
            'properties' => [
                'tenant_id' => $tenant->id,
                'module' => $request->module,
                'old_state' => $oldState,
                'new_state' => !$oldState,
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Module updated!']);
    }

    public function addSubscriptionLog(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'type' => 'required|in:trial_extend,payment',
            'days' => 'nullable|integer|min:1|required_if:type,trial_extend',
            'amount' => 'nullable|numeric|min:0|required_if:type,payment',
            'notes' => 'nullable|string',
        ]);

        $endsAt = null;

        if ($validated['type'] === 'trial_extend') {
            $days = (int) $validated['days']; 
            $endsAt = $tenant->will_expire_at ? $tenant->will_expire_at->copy()->addDays($days) : now()->addDays($days);
            $tenant->update(['will_expire_at' => $endsAt, 'trial_ends_at' => $endsAt, 'status' => 'trial']);
        } elseif ($validated['type'] === 'payment') {
            $baseDate = $tenant->will_expire_at && $tenant->will_expire_at->isFuture() ? $tenant->will_expire_at : now();
            $endsAt = $baseDate->copy()->addDays($tenant->plan->duration_in_days ?? 30);
            $tenant->update(['will_expire_at' => $endsAt, 'status' => 'active']);
        }

        $sub = $tenant->subscriptions()->create([
            'plan_id' => $tenant->plan_id,
            'type' => $validated['type'],
            'amount' => $validated['amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'starts_at' => now(),
            'ends_at' => $endsAt,
        ]);

        $invoiceId = null;

        if ($validated['type'] === 'payment' && $sub->amount > 0) {
            $lastInvoice = \App\Models\PlatformInvoice::count();
            $invoiceNum = 'INV-' . str_pad($lastInvoice + 1, 5, '0', STR_PAD_LEFT);
            
            $invoice = \App\Models\PlatformInvoice::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => $sub->id,
                'invoice_number' => $invoiceNum,
                'amount' => $sub->amount,
                'tax' => 0,
                'total' => $sub->amount,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $invoiceId = $invoice->id;
        }

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action' => 'subscription_log',
            'description' => "Added {$validated['type']} log for: {$tenant->name}",
            'properties' => [
                'tenant_id' => $tenant->id,
                'type' => $validated['type'],
                'amount' => $sub->amount,
                'new_expiry' => $endsAt?->toDateTimeString(),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Subscription updated & Invoice generated!']);
    }
    
    public function destroy(Tenant $tenant)
    {
        DB::beginTransaction();
        try {
            $deletedData = [
                'tenant' => $tenant->toArray(),
                'users_count' => $tenant->users()->count(),
                'domains' => $tenant->domains()->pluck('domain')->toArray(),
            ];

            $tenant->users()->delete();
            $tenant->domains()->delete();
            $tenant->subscriptions()->delete();
            \App\Models\PlatformInvoice::where('tenant_id', $tenant->id)->delete();
            $tenant->delete();

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action' => 'tenant_deleted',
                'description' => "Deleted tenant: {$tenant->name}",
                'properties' => $deletedData,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tenant deleted.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error deleting tenant.'], 500);
        }
    }
}