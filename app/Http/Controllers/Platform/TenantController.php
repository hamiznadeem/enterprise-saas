<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Models\Plan;
use App\Models\AuditLog;
use App\Models\PlatformInvoice;
use App\Notifications\TenantCreatedNotification;
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
            $query->where(function ($q) use ($search) {
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
            'name'        => 'required|string|max:255',
            'domain'      => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->whereNull('deleted_at'),
                Rule::unique('domains', 'domain'),
            ],
            'plan_id'     => 'required|exists:plans,id',
            'owner_name'  => 'required|string|max:255',
            'owner_email' => 'required|email|max:255|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        // Trial ya direct active?
        if ($plan->trial_days > 0) {
            $status       = 'trial';
            $expiryDate   = now()->addDays($plan->trial_days);
            $trialEndsAt  = now()->addDays($plan->trial_days);
        } else {
            $status       = 'active';
            $expiryDate   = now()->addDays($plan->duration_in_days ?? 30);
            $trialEndsAt  = null;
        }

        $fullDomain = $validated['domain'] . '.yoursaas.com';

        DB::beginTransaction();

        try {
            $tenant = Tenant::create([
                'name'            => $validated['name'],
                'domain'          => $validated['domain'],
                'owner_name'      => $validated['owner_name'],
                'owner_email'     => $validated['owner_email'],
                'phone'           => $validated['phone'] ?? null,
                'city'            => $validated['city'] ?? null,
                'location'        => $validated['location'] ?? null,
                'plan_id'         => $validated['plan_id'],
                'status'          => $status,
                'is_active'       => 1,
                'on_trial'        => $status === 'trial',
                'trial_ends_at'   => $trialEndsAt,
                'will_expire_at'  => $expiryDate,
                'web_access_url'  => $fullDomain,
                'enabled_modules' => $plan->features ?? ['clinic' => true, 'pos' => true, 'pharmacy' => true],
            ]);

            // Domain table mein full domain save (middleware isse match karega)
            Domain::create([
                'domain'    => $fullDomain,
                'tenant_id' => $tenant->id,
            ]);

            // Owner user create karo
            $password = Str::random(12);

            $owner = User::create([
                'name'             => $validated['owner_name'],
                'email'            => $validated['owner_email'],
                'password'         => Hash::make($password),
                'tenant_id'        => $tenant->id,
                'role'             => 'owner',
                'is_active'        => 1,
                'email_verified_at' => now(),
            ]);

            $owner->assignRole('owner');

            // Subscription log
            $tenant->subscriptions()->create([
                'plan_id'   => $validated['plan_id'],
                'type'      => $status,
                'amount'    => $status === 'active' ? $plan->price : 0,
                'starts_at' => now(),
                'ends_at'   => $expiryDate,
            ]);

            // Audit log
            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action'            => 'tenant_created',
                'description'       => "Created tenant: {$tenant->name}",
                'properties'        => [
                    'tenant_id'   => $tenant->id,
                    'tenant_name' => $tenant->name,
                    'domain'      => $fullDomain,
                    'owner_email' => $validated['owner_email'],
                    'plan'        => $plan->name,
                    'plan_id'     => $plan->id,
                    'status'      => $status,
                ],
            ]);

            DB::commit();

            // Email bhejo — fail hone pe tenant creation rokna nahi
            try {
                $tenant->notify(new TenantCreatedNotification($tenant, [
                    'email'    => $validated['owner_email'],
                    'password' => $password,
                ]));
            } catch (\Exception $e) {
                // Silent fail
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Tenant created successfully!',
                'credentials' => [
                    'email'    => $validated['owner_email'],
                    'password' => $password,
                ],
                'tenant' => [
                    'name'   => $tenant->name,
                    'domain' => $fullDomain,
                    'plan'   => $plan->name,
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
                'debug'   => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'domain'      => [
                'required', 'string', 'max:255',
                Rule::unique('tenants', 'domain')->ignore($tenant->id)->whereNull('deleted_at'),
                Rule::unique('domains', 'domain')->ignore($tenant->domains()->value('id')),
            ],
            'plan_id'     => 'required|exists:plans,id',
            'owner_name'  => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:255',
        ]);

        $plan = Plan::find($validated['plan_id']);

        DB::beginTransaction();

        try {
            $oldData     = $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']);
            $planChanged = $tenant->plan_id != $plan->id;
            $fullDomain  = $validated['domain'] . '.yoursaas.com';

            $updateData = [
                'name'        => $validated['name'],
                'domain'      => $validated['domain'],
                'owner_name'  => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'plan_id'     => $validated['plan_id'],
                'phone'       => $validated['phone'] ?? null,
                'city'        => $validated['city'] ?? null,
                'location'    => $validated['location'] ?? null,
                'web_access_url' => $fullDomain,
            ];

            // Plan change ho toh expiry update karo
            if ($planChanged) {
                $updateData['will_expire_at'] = now()->addDays($plan->duration_in_days ?? 30);
                $updateData['status']         = 'active';
                $updateData['trial_ends_at']  = null;
                $updateData['on_trial']       = false;
            }

            $tenant->update($updateData);

            // Domain table bhi update karo
            $tenant->domains()->update([
                'domain' => $fullDomain,
            ]);

            // Owner user update karo
            $owner = $tenant->users()->where('role', 'owner')->first();
            if ($owner) {
                $owner->update([
                    'name'  => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                ]);
            }

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action'            => 'tenant_updated',
                'description'       => "Updated tenant: {$tenant->name}",
                'properties'        => [
                    'tenant_id'   => $tenant->id,
                    'old'         => $oldData,
                    'new'         => $tenant->only(['name', 'domain', 'owner_name', 'owner_email', 'plan_id', 'status']),
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

        $daysToAdd = $plan->duration_in_days ?? 30;
        $oldExpiry = $tenant->will_expire_at?->toDateTimeString();

        // Agar expiry future mein hai toh usse aage badhao, warna aaj se start karo
        $baseDate = ($tenant->will_expire_at && $tenant->will_expire_at->isFuture())
            ? $tenant->will_expire_at
            : Carbon::now();

        $newExpiry = $baseDate->copy()->addDays($daysToAdd);

        $tenant->update([
            'will_expire_at' => $newExpiry,
            'status'         => 'active',
            'is_active'      => 1,
            'on_trial'       => false,
            'trial_ends_at'  => null,
        ]);

        // Subscription log create karo
        $subscription = $tenant->subscriptions()->create([
            'plan_id'   => $tenant->plan_id,
            'type'      => 'renewal',
            'amount'    => $plan->price,
            'starts_at' => $baseDate,
            'ends_at'   => $newExpiry,
            'notes'     => "Renewed for {$daysToAdd} days",
        ]);

        // Invoice generate karo
        $lastInvoice = PlatformInvoice::count();
        $invoiceNum  = 'INV-' . str_pad($lastInvoice + 1, 5, '0', STR_PAD_LEFT);

        PlatformInvoice::create([
            'tenant_id'       => $tenant->id,
            'subscription_id' => $subscription->id,
            'invoice_number'  => $invoiceNum,
            'amount'          => $plan->price,
            'tax'             => 0,
            'total'           => $plan->price,
            'status'          => 'paid',
            'paid_at'         => now(),
        ]);

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action'            => 'tenant_renewed',
            'description'       => "Renewed tenant: {$tenant->name} for {$daysToAdd} days",
            'properties'        => [
                'tenant_id'   => $tenant->id,
                'tenant_name' => $tenant->name,
                'plan'        => $plan->name,
                'days_added'  => $daysToAdd,
                'old_expiry'  => $oldExpiry,
                'new_expiry'  => $newExpiry->toDateTimeString(),
                'invoice'     => $invoiceNum,
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Subscription renewed successfully!']);
    }

    public function toggleStatus(Tenant $tenant)
    {
        $oldStatus   = $tenant->status;
        $oldIsActive = $tenant->is_active;

        if ($tenant->is_active) {
            // ── DEACTIVATE ──
            $tenant->update([
                'is_active' => 0,
                'status'    => 'suspended',
            ]);
            $action = 'suspended';
        } else {
            // ── REACTIVATE ──
            // ✅ FIX: Check on_trial and trial_ends_at to restore 'trial' if still within trial period
            $isStillTrial = $tenant->on_trial || ($tenant->trial_ends_at && $tenant->trial_ends_at->isFuture());
            $restoreStatus = $isStillTrial ? 'trial' : 'active';

            $tenant->update([
                'is_active' => 1,
                'status'    => $restoreStatus,
                'on_trial'  => $isStillTrial,
            ]);
            $action = $restoreStatus === 'trial' ? 'reactivated (trial)' : 'reactivated';
        }

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action'            => 'tenant_status_toggled',
            'description'       => ucfirst($action) . " tenant: {$tenant->name}",
            'properties'        => [
                'tenant_id'   => $tenant->id,
                'tenant_name' => $tenant->name,
                'old_status'  => $oldStatus,
                'old_is_active' => $oldIsActive,
                'new_status'  => $tenant->status,
                'new_is_active' => $tenant->is_active,
            ],
        ]);

        return response()->json(['success' => true, 'message' => "Tenant {$action}!"]);
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('subscriptions.plan', 'plan');
        $modules = $tenant->enabled_modules ?? ($tenant->plan ? $tenant->plan->features : []);
        $plans   = Plan::where('is_active', 1)->get();

        // Recent audit logs for this tenant
        $recentLogs = AuditLog::where('properties->tenant_id', $tenant->id)
            ->latest()
            ->take(20)
            ->get();

        return view('platform.tenants.show', compact('tenant', 'modules', 'plans', 'recentLogs'));
    }

    public function toggleModule(Request $request, Tenant $tenant)
    {
        $request->validate(['module' => 'required|string']);

        $modules  = $tenant->enabled_modules ?? [];
        $oldState = $modules[$request->module] ?? false;
        $modules[$request->module] = !$oldState;

        $tenant->update(['enabled_modules' => $modules]);

        $stateText = $oldState ? 'disabled' : 'enabled';

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action'            => 'module_toggled',
            'description'       => ucfirst($request->module) . " {$stateText} for: {$tenant->name}",
            'properties'        => [
                'tenant_id'  => $tenant->id,
                'module'     => $request->module,
                'old_state'  => $oldState,
                'new_state'  => !$oldState,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->module) . " {$stateText}!",
            'state'   => !$oldState,
        ]);
    }

    public function addSubscriptionLog(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'type'   => 'required|in:trial_extend,payment',
            'days'   => 'nullable|integer|min:1|required_if:type,trial_extend',
            'amount' => 'nullable|numeric|min:0|required_if:type,payment',
            'notes'  => 'nullable|string',
        ]);

        $endsAt = null;

        if ($validated['type'] === 'trial_extend') {
            $days    = (int) $validated['days'];
            $base    = $tenant->trial_ends_at && $tenant->trial_ends_at->isFuture()
                        ? $tenant->trial_ends_at
                        : now();
            $endsAt  = $base->copy()->addDays($days);

            $tenant->update([
                'trial_ends_at'  => $endsAt,
                'will_expire_at' => $endsAt,
                'status'         => 'trial',
                'on_trial'       => true,
            ]);

        } elseif ($validated['type'] === 'payment') {
            $base   = $tenant->will_expire_at && $tenant->will_expire_at->isFuture()
                        ? $tenant->will_expire_at
                        : now();
            $endsAt = $base->copy()->addDays($tenant->plan->duration_in_days ?? 30);

            $tenant->update([
                'will_expire_at' => $endsAt,
                'status'         => 'active',
                'on_trial'       => false,
                'trial_ends_at'  => null,
            ]);
        }

        $sub = $tenant->subscriptions()->create([
            'plan_id'   => $tenant->plan_id,
            'type'      => $validated['type'],
            'amount'    => $validated['amount'] ?? 0,
            'notes'     => $validated['notes'] ?? null,
            'starts_at' => now(),
            'ends_at'   => $endsAt,
        ]);

        // Payment ke liye invoice banao
        $invoiceInfo = null;

        if ($validated['type'] === 'payment' && $sub->amount > 0) {
            $lastInvoice = PlatformInvoice::count();
            $invoiceNum  = 'INV-' . str_pad($lastInvoice + 1, 5, '0', STR_PAD_LEFT);

            $invoice = PlatformInvoice::create([
                'tenant_id'       => $tenant->id,
                'subscription_id' => $sub->id,
                'invoice_number'  => $invoiceNum,
                'amount'          => $sub->amount,
                'tax'             => 0,
                'total'           => $sub->amount,
                'status'          => 'paid',
                'paid_at'         => now(),
            ]);

            $invoiceInfo = [
                'id'            => $invoice->id,
                'invoice_number' => $invoiceNum,
                'total'         => $sub->amount,
            ];
        }

        AuditLog::create([
            'platform_admin_id' => auth('platform')->id(),
            'action'            => 'subscription_log',
            'description'       => "Added {$validated['type']} log for: {$tenant->name}",
            'properties'        => [
                'tenant_id'  => $tenant->id,
                'type'       => $validated['type'],
                'amount'     => $sub->amount,
                'days'       => $validated['days'] ?? null,
                'new_expiry' => $endsAt?->toDateTimeString(),
                'invoice'    => $invoiceInfo,
            ],
        ]);

        $message = $validated['type'] === 'trial_extend'
            ? "Trial extended by {$validated['days']} days!"
            : 'Payment recorded & invoice generated!';

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function destroy(Tenant $tenant)
    {
        DB::beginTransaction();
        try {
            // Backup for audit
            $deletedData = [
                'tenant'      => $tenant->toArray(),
                'users_count' => $tenant->users()->count(),
                'domains'     => $tenant->domains()->pluck('domain')->toArray(),
            ];

            // Related data delete (hard delete — tenant itself is soft deleted)
            $tenant->users()->delete();
            $tenant->domains()->delete();
            $tenant->subscriptions()->delete();
            PlatformInvoice::where('tenant_id', $tenant->id)->delete();

            // Soft delete tenant
            $tenant->delete();

            AuditLog::create([
                'platform_admin_id' => auth('platform')->id(),
                'action'            => 'tenant_deleted',
                'description'       => "Deleted tenant: {$tenant->name}",
                'properties'        => $deletedData,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tenant deleted.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error deleting tenant.'], 500);
        }
    }
}