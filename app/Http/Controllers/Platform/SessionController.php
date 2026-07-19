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
            return response()->json(['success' => false, 'message' => 'Cannot kill current session.'], 422);
        }
        return response()->json(['success' => true, 'message' => 'Session terminated.']);
    }

    public function killAll(Request $request)
    {
        $admin = Auth::guard('platform')->user();
        $killed = PlatformSessionService::killAllOtherSessions($admin->id);
        return response()->json(['success' => true, 'message' => "{$killed} other session(s) terminated."]);
    }
}