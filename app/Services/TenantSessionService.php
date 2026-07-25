<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TenantSessionService
{
    /**
     * Get all active sessions for a tenant user
     */
    public static function getActiveSessions(int $userId): array
    {
        $sessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subDays(7)->timestamp)
            ->get();

        $result = [];
        $currentSessionId = Session::getId();

        foreach ($sessions as $session) {
            $payload = @unserialize(@base64_decode($session->payload));

            if (!$payload || !isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'])) {
                continue;
            }

            $userIdFromSession = $payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];

            if ($userIdFromSession != $userId) {
                continue;
            }

            $ua = $payload['user_agent'] ?? '';
            $device = self::parseDevice($ua);

            $result[] = [
                'id'            => $session->id,
                'ip'            => $session->ip_address,
                'device_type'   => $device['type'],
                'device_icon'   => $device['icon'],
                'browser'       => $device['browser'],
                'browser_ver'   => $device['version'],
                'os'            => $device['os'],
                'last_activity' => self::timeAgo($session->last_activity),
                'is_current'    => $session->id === $currentSessionId,
            ];
        }

        // Sort: current first, then by last_activity desc
        usort($result, function ($a, $b) {
            if ($a['is_current']) return -1;
            if ($b['is_current']) return 1;
            return 0;
        });

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
     * Kill all other sessions except current
     */
    public static function killAllOtherSessions(int $userId): int
    {
        $sessions = DB::table('sessions')->get();
        $currentId = Session::getId();
        $killed = 0;

        foreach ($sessions as $session) {
            if ($session->id === $currentId) continue;

            $payload = @unserialize(@base64_decode($session->payload));

            if ($payload && isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'])) {
                if ($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'] == $userId) {
                    DB::table('sessions')->where('id', $session->id)->delete();
                    $killed++;
                }
            }
        }

        return $killed;
    }

    /**
     * Parse user agent string
     */
    private static function parseDevice(string $ua): array
    {
        $result = [
            'type'    => 'desktop',
            'icon'    => 'fa-desktop',
            'browser' => 'Unknown',
            'version' => '',
            'os'      => 'Unknown',
        ];

        // Device type
        if (preg_match('/Mobile|Android.*Mobile|iPhone|iPod/i', $ua)) {
            $result['type'] = 'mobile';
            $result['icon'] = 'fa-mobile-screen';
        } elseif (preg_match('/iPad|Android(?!.*Mobile)|Tablet/i', $ua)) {
            $result['type'] = 'tablet';
            $result['icon'] = 'fa-tablet-screen-button';
        }

        // Browser
        $browsers = [
            ['name' => 'Edge',    'pattern' => '/Edg(?:e|A|iOS)?\/(\d+[\.\d]*)/'],
            ['name' => 'Opera',   'pattern' => '/OPR\/(\d+[\.\d]*)/'],
            ['name' => 'Firefox', 'pattern' => '/Firefox\/(\d+[\.\d]*)/'],
            ['name' => 'Chrome',  'pattern' => '/Chrome\/(\d+[\.\d]*)/'],
            ['name' => 'Safari',  'pattern' => '/Version\/(\d+[\.\d]*).*Safari/'],
            ['name' => 'Brave',   'pattern' => '/Brave\/(\d+[\.\d]*)/'],
        ];

        foreach ($browsers as $b) {
            if (preg_match($b['pattern'], $ua, $m)) {
                $result['browser'] = $b['name'];
                $result['version'] = $m[1] ?? '';
                break;
            }
        }

        // OS
        $oss = [
            ['name' => 'Windows', 'pattern' => '/Windows NT (\d+[\.\d]*)/'],
            ['name' => 'macOS',   'pattern' => '/Mac OS X (\d+[._\d]*)/'],
            ['name' => 'Linux',   'pattern' => '/Linux/i'],
            ['name' => 'Android', 'pattern' => '/Android (\d+[\.\d]*)/'],
            ['name' => 'iOS',     'pattern' => '/iPhone OS (\d+[_\d]*)/'],
        ];

        foreach ($oss as $o) {
            if (preg_match($o['pattern'], $ua, $m)) {
                $result['os'] = $o['name'];
                break;
            }
        }

        return $result;
    }

    /**
     * Human-readable time ago
     */
    private static function timeAgo(int $timestamp): string
    {
        $diff = now()->timestamp - $timestamp;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' min ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('M j, Y', $timestamp);
    }
}