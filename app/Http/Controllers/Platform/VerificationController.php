<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        $admin = Auth::guard('platform')->user();

        if ($admin->isVerified()) {
            return redirect()->route('platform.dashboard');
        }

        return view('platform.auth.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $admin = PlatformAdmin::findOrFail($id);

        if (!hash_equals(sha1($admin->getEmailForVerification()), $hash)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification link.'], 422);
        }

        if ($admin->isVerified()) {
            return response()->json(['success' => true, 'message' => 'Email already verified.', 'redirect' => route('platform.dashboard')]);
        }

        $admin->markEmailVerified();

        return response()->json(['success' => true, 'message' => 'Email verified successfully.', 'redirect' => route('platform.dashboard')]);
    }

    public function resend(Request $request)
    {
        $admin = Auth::guard('platform')->user();

        if ($admin->isVerified()) {
            return response()->json(['success' => false, 'message' => 'Email already verified.'], 422);
        }

        $verificationUrl = URL::temporarySignedRoute(
            'platform.verification.verify',
            now()->addMinutes(60),
            ['id' => $admin->id, 'hash' => sha1($admin->getEmailForVerification())]
        );

        // Dev mode — return URL. Production mein email bhejo.
        return response()->json([
            'success' => true,
            'message' => 'Verification link generated.',
            'url' => $verificationUrl,
        ]);
    }
}