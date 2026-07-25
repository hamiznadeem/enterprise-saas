<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class TwoFactorService
{
    private static function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        foreach (str_split($data) as $char) {
            $bits .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }
        $base32 = '';
        for ($i = 0; $i + 5 <= strlen($bits); $i += 5) {
            $base32 .= $alphabet[bindec(substr($bits, $i, 5))];
        }
        while (strlen($base32) % 8 !== 0) {
            $base32 .= '=';
        }
        return $base32;
    }

    private static function base32Decode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $data = rtrim($data, '=');
        $bits = '';
        foreach (str_split($data) as $char) {
            $pos = strpos($alphabet, $char);
            if ($pos === false) continue;
            $bits .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }
        $binary = '';
        for ($i = 0; $i + 8 <= strlen($bits); $i += 8) {
            $binary .= chr(bindec(substr($bits, $i, 8)));
        }
        return $binary;
    }

    // ── Secret Generation (TOTP) ──

    public static function generateSecret(): string
    {
        return self::base32Encode(random_bytes(20));
    }

    public static function getQRCodeUrl(string $email, string $secret): string
    {
        $params = http_build_query([
            'secret' => $secret,
            'issuer' => 'ClinicPOS',
            'algorithm' => 'SHA1',
            'digits' => 6,
            'period' => 30,
        ]);
        return 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='
            . urlencode("otpauth://totp/ClinicPOS:{$email}?{$params}");
    }

    public static function getCurrentTOTPCode(string $secret): string
    {
        return self::calculateTOTP($secret, time());
    }

    public static function verifyTOTP(string $secret, string $code): bool
    {
        $code = str_pad($code, 6, '0', STR_PAD_LEFT);
        // Check current, previous, and next time step (90 second window)
        for ($i = -1; $i <= 1; $i++) {
            if (self::calculateTOTP($secret, time() + ($i * 30)) === $code) {
                return true;
            }
        }
        return false;
    }

    private static function calculateTOTP(string $secret, int $timestamp): string
    {
        $timeSlice = floor($timestamp / 30);
        $time = pack('N', $timeSlice);
        $key = self::base32Decode($secret);
        $hash = hash_hmac('sha1', $time, $key, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset + 0]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        ) % 1000000;
        return str_pad((string) $code, 6, '0', STR_PAD_LEFT);
    }

    // ── Email OTP ──

    public static function generateEmailOTP(int $userId): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put("2fa:otp:{$userId}", $code, now()->addMinutes(5));
        Cache::put("2fa:attempts:{$userId}", 0, now()->addMinutes(5));
        return $code;
    }

    public static function verifyEmailOTP(int $userId, string $code): bool
    {
        $cached = Cache::get("2fa:otp:{$userId}");
        $attempts = (int) Cache::get("2fa:attempts:{$userId}", 0);

        if ($attempts >= 5) {
            Cache::forget("2fa:otp:{$userId}");
            return false;
        }

        Cache::increment("2fa:attempts:{$userId}");

        if ($cached && $cached === $code) {
            Cache::forget("2fa:otp:{$userId}");
            Cache::forget("2fa:attempts:{$userId}");
            return true;
        }

        return false;
    }

    // ── Recovery Codes ──

    public static function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(16)), 0, 8));
        }
        return $codes;
    }

    public static function storeRecoveryCodes(User $user, array $codes): void
    {
        $user->two_factor_recovery_codes = Crypt::encryptString(json_encode($codes));
        $user->save();
    }

    public static function getRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) return [];
        return json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true) ?: [];
    }

    public static function verifyRecoveryCode(User $user, string $code): bool
    {
        $code = strtoupper(trim($code));
        $codes = self::getRecoveryCodes($user);

        $key = array_search($code, $codes);
        if ($key !== false) {
            array_splice($codes, $key, 1);
            self::storeRecoveryCodes($user, $codes);
            return true;
        }

        return false;
    }

    // ── Enable / Disable ──

    public static function enableEmail2FA(User $user): array
    {
        $codes = self::generateRecoveryCodes();
        $user->two_factor_enabled = true;
        $user->two_factor_method = 'email';
        $user->two_factor_secret = null;
        self::storeRecoveryCodes($user, $codes);
        return $codes;
    }

    public static function enableTOTP2FA(User $user, string $secret): array
    {
        $codes = self::generateRecoveryCodes();
        $user->two_factor_enabled = true;
        $user->two_factor_method = 'totp';
        $user->two_factor_secret = Crypt::encryptString($secret);
        self::storeRecoveryCodes($user, $codes);
        return $codes;
    }

    public static function disable2FA(User $user): void
    {
        $user->two_factor_enabled = false;
        $user->two_factor_method = null;
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();
    }

    public static function getDecryptedSecret(User $user): ?string
    {
        if (!$user->two_factor_secret) return null;
        return Crypt::decryptString($user->two_factor_secret);
    }
}