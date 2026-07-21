<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;

class LoginLogService
{
    /**
     * Successful login log
     */
    public static function logSuccess(Request $request, User $user): LoginLog
    {
        return self::createLog($request, 'success', null, $user);
    }

    /**
     * Failed login log
     */
    public static function logFailed(Request $request, string $reason, ?User $user = null): LoginLog
    {
        return self::createLog($request, 'failed', $reason, $user);
    }

    /**
     * Core log creator
     */
    private static function createLog(
        Request $request,
        string $status,
        ?string $reason,
        ?User $user
    ): LoginLog {
        $device = self::parseDevice($request);

        // Agar user nahi mila toh email se dhundhne ki koshish (tenant_id ke liye)
        if (!$user && $request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        }

        return LoginLog::create([
            'user_id'        => $user?->id,
            'tenant_id'      => $user?->tenant_id,
            'email'          => $request->input('email'),
            'ip_address'     => $request->ip() ?? '127.0.0.1',
            'user_agent'     => $request->userAgent() ?? 'CLI/Tinker',
            'device_type'    => $device['type'],
            'browser'        => $device['browser'],
            'browser_version'=> $device['browser_version'],
            'os'             => $device['os'],
            'os_version'     => $device['os_version'],
            'status'         => $status,
            'reason'         => $reason,
            'created_at'     => now(),
        ]);
    }

    /**
     * User agent parse karo — bina package ke (no jenssegers/agent needed)
     */
    private static function parseDevice(Request $request): array
    {
        $ua = $request->userAgent() ?? '';
        $result = [
            'type'            => 'desktop',
            'browser'         => 'Unknown',
            'browser_version' => '',
            'os'              => 'Unknown',
            'os_version'      => '',
        ];

        // ── Device Type ──
        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) {
            $result['type'] = 'mobile';
        } elseif (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) {
            $result['type'] = 'tablet';
        }

        // ── Browser ──
        $browsers = [
            'Edge'            => '/Edg(?:e|A|iOS)?\/(\d+[\.\d]*)/',
            'Opera'           => '/OPR\/(\d+[\.\d]*)/',
            'Firefox'         => '/Firefox\/(\d+[\.\d]*)/',
            'Chrome'          => '/Chrome\/(\d+[\.\d]*)/',
            'Safari'          => '/Version\/(\d+[\.\d]*).*Safari/',
            'Brave'           => '/Brave\/(\d+[\.\d]*)/',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $ua, $matches)) {
                $result['browser'] = $name;
                $result['browser_version'] = $matches[1] ?? '';
                break;
            }
        }

        // ── OS ──
        $oss = [
            'Windows'  => '/Windows NT (\d+[\.\d]*)/',
            'macOS'    => '/Mac OS X (\d+[._\d]*)/',
            'Linux'    => '/Linux/i',
            'Android'  => '/Android (\d+[\.\d]*)/',
            'iOS'      => '/iPhone OS (\d+[_\d]*)/',
        ];

        foreach ($oss as $name => $pattern) {
            if (preg_match($pattern, $ua, $matches)) {
                $result['os'] = $name;
                $result['os_version'] = str_replace('_', '.', $matches[1] ?? '');
                break;
            }
        }

        return $result;
    }
}