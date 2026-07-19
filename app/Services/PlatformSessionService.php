<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PlatformSessionService
{
    /**
     * Get all active sessions for current admin
     */
    public static function getActiveSessions(int $adminId): array
    {
        $sessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays(7)->timestamp)
            ->get();

        $result = [];
        $currentSessionId = Session::getId();

        foreach ($sessions as $session) {
            $payload = @unserialize(@base64_decode($session->payload));

            if (!$payload || !isset($payload['platform_admin_id']) || $payload['platform_admin_id'] != $adminId) {
                continue;
            }

            $ua = $payload['user_agent'] ?? '';
            $device = self::parseDevice($ua);

            $result[] = [
                'id' => $session->id,
                'ip' => $session->ip_address,
                'device_type' => $device['type'],
                'browser' => $device['browser'],
                'os' => $device['os'],
                'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current' => $session->id === $currentSessionId,
            ];
        }

        return $result;
    }

    /**
     * Kill a specific session (not current)
     */
    public static function killSession(string $sessionId): bool
    {
        if ($sessionId === Session::getId()) {
            return false;
        }
        return DB::table('sessions')->where('id', $sessionId)->delete() > 0;
    }

    /**
     * Kill all other sessions (keep current)
     */
    public static function killAllOtherSessions(int $adminId): int
    {
        $sessions = DB::table('sessions')->get();
        $currentId = Session::getId();
        $killed = 0;

        foreach ($sessions as $session) {
            if ($session->id === $currentId) continue;

            $payload = @unserialize(@base64_decode($session->payload));
            if ($payload && isset($payload['platform_admin_id']) && $payload['platform_admin_id'] == $adminId) {
                DB::table('sessions')->where('id', $session->id)->delete();
                $killed++;
            }
        }

        return $killed;
    }

    private static function parseDevice(string $ua): array
    {
        $result = ['type' => 'desktop', 'browser' => 'Unknown', 'os' => 'Unknown'];

        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) $result['type'] = 'mobile';
        elseif (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) $result['type'] = 'tablet';

        foreach (['Edg(e|A|iOS)?\/' => 'Edge', 'OPR\/' => 'Opera', 'Firefox\/' => 'Firefox', 'Chrome\/' => 'Chrome', 'Version\/.*Safari' => 'Safari'] as $pattern => $name) {
            if (preg_match('/' . $pattern . '(\d+[\.\d]*)/i', $ua, $m)) {
                $result['browser'] = $name;
                break;
            }
        }

        foreach (['Windows NT' => 'Windows', 'Mac OS X' => 'macOS', 'Linux' => 'Linux', 'Android' => 'Android', 'iPhone OS' => 'iOS'] as $pattern => $name) {
            if (stripos($ua, $pattern) !== false) {
                $result['os'] = $name;
                break;
            }
        }

        return $result;
    }
}