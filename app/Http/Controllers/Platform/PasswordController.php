<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use App\Models\PlatformPasswordReset;
use App\Services\PlatformPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    // ── Forgot Password ──

    public function showForgotForm()
    {
        return view('platform.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $admin = PlatformAdmin::where('email', $request->email)->first();

        if ($admin) {
            $token = Str::random(60);

            DB::table('platform_password_resets')->updateOrInsert(
                ['email' => $admin->email],
                ['email' => $admin->email, 'token' => Hash::make($token), 'created_at' => now()]
            );

            // Dev mode — return token. Production mein email bhejo.
            return response()->json([
                'success' => true,
                'message' => 'Reset link generated.',
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'If the email exists, a reset link will be sent.',
        ]);
    }

    // ── Reset Password ──

    public function showResetForm(Request $request, $token)
    {
        return view('platform.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('platform_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token.',
            ], 422);
        }

        $admin = PlatformAdmin::where('email', $request->email)->first();
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email.',
            ], 422);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Password too weak. Use uppercase, numbers, and special characters.',
                'strength' => $strength,
            ], 422);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reuse a recent password.',
            ], 422);
        }

        $hashed = Hash::make($request->password);
        PlatformPasswordService::recordHistory($admin, $hashed);

        $admin->update([
            'password' => $hashed,
            'login_attempts' => 0,
            'locked_until' => null,
            'is_active' => true,
        ]);

        DB::table('platform_password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
            'redirect' => route('platform.login'),
        ]);
    }

    // ── Change Password ──

    public function showChangeForm()
    {
        return view('platform.settings.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::guard('platform')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Password too weak.',
                'strength' => $strength,
            ], 422);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reuse a recent password.',
            ], 422);
        }

        $hashed = Hash::make($request->password);
        PlatformPasswordService::recordHistory($admin, $hashed);

        $admin->update(['password' => $hashed]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}