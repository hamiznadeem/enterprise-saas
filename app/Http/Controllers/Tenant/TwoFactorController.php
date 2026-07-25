<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    // ── Settings Page ──

    public function index()
    {
        $user = Auth::user();
        $recoveryCodes = $user->two_factor_enabled ? TwoFactorService::getRecoveryCodes($user) : [];
        $showCodes = session('show_recovery_codes', false);

        return view('tenantView.two-factor.index', compact('user', 'recoveryCodes', 'showCodes'));
    }

    // ── Enable Email 2FA ──

    public function enableEmail(Request $request)
    {
        $user = Auth::user();
        $codes = TwoFactorService::enableEmail2FA($user);

        return redirect()->route('two-factor.index')
            ->with('show_recovery_codes', true)
            ->with('recovery_codes', $codes)
            ->with('status', 'Email 2FA enabled!');
    }

    // ── Setup TOTP (Show QR) ──

    public function setupTOTP()
    {
        $user = Auth::user();
        $secret = TwoFactorService::generateSecret();
        $qrUrl = TwoFactorService::getQRCodeUrl($user->email, $secret);

        session(['totp_setup_secret' => $secret]);

        return view('tenantView.two-factor.setup-totp', compact('secret', 'qrUrl'));
    }

    // ── Confirm & Enable TOTP ──

    public function enableTOTP(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $secret = session('totp_setup_secret');
        if (!$secret) {
            return redirect()->route('two-factor.index')->withErrors(['error' => 'Setup expired. Try again.']);
        }

        if (!TwoFactorService::verifyTOTP($secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid code. Try again.']);
        }

        $user = Auth::user();
        $codes = TwoFactorService::enableTOTP2FA($user, $secret);
        session()->forget('totp_setup_secret');

        return redirect()->route('two-factor.index')
            ->with('show_recovery_codes', true)
            ->with('recovery_codes', $codes)
            ->with('status', 'Authenticator 2FA enabled!');
    }

    // ── Disable 2FA ──

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if (!\Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        TwoFactorService::disable2FA(Auth::user());
        session()->forget('two_factor_verified');

        return redirect()->route('two-factor.index')->with('status', '2FA disabled.');
    }

    // ── Regenerate Recovery Codes ──

    public function regenerateCodes(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if (!\Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        $codes = TwoFactorService::generateRecoveryCodes();
        TwoFactorService::storeRecoveryCodes(Auth::user(), $codes);

        return redirect()->route('two-factor.index')
            ->with('show_recovery_codes', true)
            ->with('recovery_codes', $codes)
            ->with('status', 'New recovery codes generated.');
    }

    // ── Challenge Page (After Login) ──

    public function showChallenge()
    {
        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('tenant.dashboard');
        }

        if (session('two_factor_verified')) {
            return redirect()->route('tenant.dashboard');
        }

        // Auto-send email OTP if method is email
        if ($user->two_factor_method === 'email' && !session('2fa_otp_sent')) {
            $code = TwoFactorService::generateEmailOTP($user->id);
            $user->notify(new \App\Notifications\TwoFactorOTPNotification($code));
            session(['2fa_otp_sent' => true]);
        }

        return view('tenantView.two-factor.challenge', compact('user'));
    }

    // ── Verify Challenge ──

    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'is_recovery' => 'sometimes|boolean',
        ]);

        $user = Auth::user();

        if ($request->boolean('is_recovery')) {
            $valid = TwoFactorService::verifyRecoveryCode($user, $request->code);
            $errorField = 'recovery_code';
        } else {
            if ($user->two_factor_method === 'email') {
                $valid = TwoFactorService::verifyEmailOTP($user->id, $request->code);
            } else {
                $secret = TwoFactorService::getDecryptedSecret($user);
                $valid = TwoFactorService::verifyTOTP($secret, $request->code);
            }
            $errorField = 'code';
        }

        if (!$valid) {
            $errorMsg = $request->boolean('is_recovery') ? 'Invalid recovery code.' : 'Invalid code.';
            $attempts = (int) cache()->get("2fa:attempts:{$user->id}", 0);
            if ($attempts >= 4) {
                $errorMsg .= ' Too many attempts. Request a new code.';
            }
            return back()->withErrors([$errorField => $errorMsg])->withInput();
        }

        session(['two_factor_verified' => true]);
        session()->forget('2fa_otp_sent');

        return redirect()->route('tenant.dashboard');
    }

    // ── Resend Email OTP ──

    public function resendOTP()
    {
        $user = Auth::user();

        if ($user->two_factor_method !== 'email') {
            return back();
        }

        $code = TwoFactorService::generateEmailOTP($user->id);
        $user->notify(new \App\Notifications\TwoFactorOTPNotification($code));

        return back()->with('status', 'New code sent!');
    }
}