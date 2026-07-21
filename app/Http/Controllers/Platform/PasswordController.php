<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
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

            // ✅ FIX: Actual email bhejo
            $admin->sendPasswordResetNotification($token);
        }

        // ✅ FIX: Consistent redirect (security best practice — same message either way)
        return back()->with('status', 'If the email exists, a reset link will be sent.');
    }

    // ── Reset Password ──

    public function showResetForm($token)
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
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // ✅ FIX: Token expiry check add kiya
        if (now()->diffInMinutes($reset->created_at) > 60) {
            DB::table('platform_password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This reset link has expired.']);
        }

        $admin = PlatformAdmin::where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return back()->withErrors(['password' => 'Password too weak. Use uppercase, numbers, and special characters.']);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return back()->withErrors(['password' => 'Cannot reuse a recent password.']);
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

        return redirect()->route('platform.login')->with('status', 'Password reset successfully.');
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
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // ✅ FIX: New password same as current check
        if (Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'New password must be different from current password.']);
        }

        $strength = PlatformPasswordService::strength($request->password);
        if ($strength['score'] < 2) {
            return back()->withErrors(['password' => 'Password too weak. Use uppercase, numbers, and special characters.']);
        }

        if (PlatformPasswordService::isOldPassword($admin, $request->password)) {
            return back()->withErrors(['password' => 'Cannot reuse a recent password.']);
        }

        $hashed = Hash::make($request->password);
        PlatformPasswordService::recordHistory($admin, $hashed);

        $admin->update(['password' => $hashed]);

        return back()->with('status', 'Password changed successfully.');
    }
}