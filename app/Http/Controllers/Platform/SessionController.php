<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\PlatformSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('platform')->user();
        $sessions = PlatformSessionService::getActiveSessions($admin->id);
        return view('platform.sessions.index', compact('sessions'));
    }

    public function destroy(Request $request, $sessionId)
    {
        $killed = PlatformSessionService::killSession($sessionId);

        if (!$killed) {
            return back()->withErrors(['session' => 'Cannot terminate current session.']);
        }

        return back()->with('status', 'Session terminated.');
    }

    public function killAll(Request $request)
    {
        $admin = Auth::guard('platform')->user();
        $killed = PlatformSessionService::killAllOtherSessions($admin->id);

        return back()->with('status', "{$killed} other session(s) terminated.");
    }
}