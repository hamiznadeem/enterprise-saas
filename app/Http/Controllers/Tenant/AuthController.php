<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\TenantActivityLog;
use App\Services\AccountLockService;
use App\Services\LoginLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenantView.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // 1. User not found
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // 2. Tenant status check
        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            throw ValidationException::withMessages([
                'email' => 'Account not linked to any business. Contact support.',
            ]);
        }

        if ($tenant->status === 'suspended') {
            LoginLogService::logFailed($request, 'tenant_suspended', $user);
            throw ValidationException::withMessages([
                'email' => 'Your business account has been suspended. Contact support.',
            ]);
        }

        // 3. Account locked check
        if (AccountLockService::isLocked($user)) {
            $minutes = AccountLockService::getRemainingLockMinutes($user);
            LoginLogService::logFailed($request, 'account_locked', $user);
            throw ValidationException::withMessages([
                'email' => "Account locked. Try again in {$minutes} minute(s).",
            ]);
        }

        // 4. Inactive user check
        if (!$user->is_active && !$user->locked_until) {
            LoginLogService::logFailed($request, 'account_inactive', $user);
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Contact your administrator.',
            ]);
        }

        // 5. Auth attempt
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $gotLocked = AccountLockService::recordFailedAttempt($user);

            if ($gotLocked) {
                $minutes = AccountLockService::LOCK_DURATION_MINUTES;
                LoginLogService::logFailed($request, 'account_locked', $user);
                throw ValidationException::withMessages([
                    'email' => "Too many failed attempts. Account locked for {$minutes} minutes.",
                ]);
            }

            $remaining = AccountLockService::getAttemptsRemaining($user);
            LoginLogService::logFailed($request, 'invalid_credentials', $user);
            throw ValidationException::withMessages([
                'email' => "Invalid credentials. {$remaining} attempt(s) remaining.",
            ]);
        }

        // 6. Success
        $user = Auth::user();
        AccountLockService::resetAttempts($user);
        LoginLogService::logSuccess($request, $user);
        $request->session()->regenerate();

        // Activity log — Direct create because tenant.identifier middleware
        // doesn't run on this public route, so TenantActivityService
        // won't be able to resolve tenant_id automatically
        TenantActivityLog::create([
            'tenant_id'   => $user->tenant_id,
            'user_id'     => $user->id,
            'action'      => 'login',
            'description' => 'User logged in',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        // JSON for AJAX/Fetch, Redirect for standard form
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('tenant.dashboard'),
            ]);
        }

        return redirect()->route('tenant.dashboard');
    }

    public function logout(Request $request)
    {
        // Capture user info BEFORE logout (auth() won't work after)
        $userId = auth()->id();
        $tenantId = auth()->user()?->tenant_id;

        // Activity log — same reason as login, direct create
        if ($userId && $tenantId) {
            TenantActivityLog::create([
                'tenant_id'   => $tenantId,
                'user_id'     => $userId,
                'action'      => 'logout',
                'description' => 'User logged out',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenantView.login');
    }
}