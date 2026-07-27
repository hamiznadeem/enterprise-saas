<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TrialController extends Controller
{
    public function showForm()
    {
        return view('trial.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email',
            'phone'         => 'required|string|max:20',
            'city'          => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'password'      => 'required|string|min:8|confirmed',
            'business_type' => 'required|string|in:mart,restaurant,cafe,retail,clinic,general_store',
            'outlets'       => 'required|string|in:1,2-5,6-10,10+',
            'domain'        => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                Rule::unique('tenants', 'domain')->whereNull('deleted_at'),
                Rule::unique('domains', 'domain'),
            ],
            'website'       => 'nullable|string|max:1',
        ]);

        // Free plan dhundo ya bana do
        $plan = Plan::where('price', 0)->first();
        if (!$plan) {
            $plan = Plan::create([
                'name'          => 'Free Trial',
                'slug'          => 'free-trial',
                'price'         => 0,
                'billing_cycle' => 'one-time',
                'trial_days'    => 14,
                'is_active'     => 1,
                'limits'        => ['terminals' => 1, 'products' => 200, 'users' => 3],
                'features'      => [
                    'clinic'     => true,
                    'pos'        => true,
                    'pharmacy'   => true,
                    'restaurant' => true,
                    'retail'     => true,
                ],
            ]);
        }

        $trialDays = $plan->trial_days > 0 ? $plan->trial_days : 14;
        $trialEndsAt = now()->addDays($trialDays);
        $fullDomain = $validated['domain'] . '.yoursaas.com';

        DB::beginTransaction();

        try {
            $tenant = Tenant::create([
                'name'            => $validated['business_name'],
                'domain'          => $validated['domain'],
                'owner_name'      => $validated['owner_name'],
                'owner_email'     => $validated['email'],
                'phone'           => $validated['phone'],
                'city'            => $validated['city'],
                'location'        => $validated['location'],
                'web_access_url'  => $fullDomain,
                'plan_id'         => $plan->id,
                'status'          => 'trial',
                'is_active'       => 1,
                'business_type'   => $validated['business_type'],
                'outlets'         => $validated['outlets'],
                'trial_ends_at'   => $trialEndsAt,
                'will_expire_at'  => $trialEndsAt,
                'enabled_modules' => $plan->features,
            ]);

            Domain::create([
                'domain'    => $fullDomain,
                'tenant_id' => $tenant->id,
            ]);

            $user = User::create([
                'name'              => $validated['owner_name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'tenant_id'         => $tenant->id,
                'role'              => 'owner',
                'is_active'         => 1,
                'email_verified_at' => null, // Leave unverified during trial; required upon renewal
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('owner');
            }

            $tenant->subscriptions()->create([
                'plan_id'    => $plan->id,
                'starts_at'  => now(),
                'ends_at'    => $trialEndsAt,
                'status'     => 'trial',
                'trial_days' => $trialDays,
                'type'       => 'trial',
                'amount'     => 0,
            ]);

            DB::commit();

            // Send Welcome Trial Email Notification
            try {
                $user->notify(new \App\Notifications\WelcomeTrialNotification($tenant, $validated['email'], $trialDays));
            } catch (\Exception $mailEx) {
                // Log mail exception if SMTP fails silently
                \Illuminate\Support\Facades\Log::error('Trial Welcome Email Failed: ' . $mailEx->getMessage());
            }

            Auth::login($user);

            return redirect()
                ->route('tenant.dashboard')
                ->with('success', "Welcome! Your {$trialDays}-day free trial has started.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Something went wrong. Please try again.'])
                ->withInput();
        }
    }
}