<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AccountLockService;
use App\Services\LoginLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // ── Step 1: Check if account is locked ──
        if ($user && AccountLockService::isLocked($user)) {
            $minutes = AccountLockService::getRemainingLockMinutes($user);

            LoginLogService::logFailed($request, 'account_locked', $user);

            throw ValidationException::withMessages([
                'email' => "Account locked due to too many failed attempts. Try again in {$minutes} minute(s).",
            ]);
        }

        // ── Step 2: Check if account is inactive (manual deactivation, not lock) ──
        if ($user && !$user->is_active && !$user->locked_until) {
            LoginLogService::logFailed($request, 'account_inactive', $user);

            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Contact your administrator.',
            ]);
        }

        // ── Step 3: Attempt authentication ──
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // FAILED LOGIN
            if ($user) {
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

            // User not found — still log
            LoginLogService::logFailed($request, 'invalid_credentials');
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // ── Step 4: Login successful ──
        $user = Auth::user();

        // Reset failed attempts
        AccountLockService::resetAttempts($user);

        // Log success
        LoginLogService::logSuccess($request, $user);

        // Clear rate limiter
        RateLimiter::clear($request->throttleKey());

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}