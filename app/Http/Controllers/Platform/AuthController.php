<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use App\Services\PlatformSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('platform')->check()) {
            return redirect()->route('platform.dashboard');
        }
        return view('platform.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $admin = PlatformAdmin::where('email', $request->email)->first();

        // Check lock
        if ($admin && $admin->isLocked()) {
            $mins = $admin->getLockRemainingMinutes();
            return response()->json([
                'success' => false,
                'message' => "Account locked. Try again in {$mins} minute(s).",
            ], 423);
        }

        // Check active
        if ($admin && !$admin->is_active && !$admin->locked_until) {
            return response()->json([
                'success' => false,
                'message' => 'Account deactivated. Contact system admin.',
            ], 403);
        }

        // Attempt
        if (!Auth::guard('platform')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if ($admin) {
                $locked = $admin->recordFailedAttempt();
                if ($locked) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many failed attempts. Account locked for 15 minutes.',
                    ], 423);
                }
                $remaining = $admin->getAttemptsRemaining();
                return response()->json([
                    'success' => false,
                    'message' => "Invalid credentials. {$remaining} attempt(s) remaining.",
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 422);
        }

        // Success
        $admin = Auth::guard('platform')->user();
        $admin->resetLoginAttempts();

        session(['platform_admin_id' => $admin->id]);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => route('platform.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('platform')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('platform.login');
    }

    public function logoutAllDevices(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::guard('platform')->user();

        if (!Auth::guard('platform')->validate(['email' => $user->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }

        $killed = PlatformSessionService::killAllOtherSessions($user->id);

        return back()->with('status', "Successfully logged out of {$killed} other device(s).");
    }
}