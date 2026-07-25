<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class PasswordExpiryService
{
    const DAYS = 90; // Password expires after 90 days

    public static function isExpired(User $user): bool
    {
        if (!$user->password_changed_at) {
            // Never changed — expire from created_at
            return $user->created_at->diffInDays(now()) >= self::DAYS;
        }
        return $user->password_changed_at->diffInDays(now()) >= self::DAYS;
    }

    public static function getDaysRemaining(User $user): int
    {
        if (!$user->password_changed_at) {
            return max(0, self::DAYS - $user->created_at->diffInDays(now()));
        }
        return max(0, self::DAYS - $user->password_changed_at->diffInDays(now()));
    }

    public static function getWarningThreshold(): int
    {
        return 14; // Warn 14 days before expiry
    }

    public static function shouldWarn(User $user): bool
    {
        $remaining = self::getDaysRemaining($user);
        return $remaining > 0 && $remaining <= self::getWarningThreshold();
    }

    public static function markAsChanged(User $user): void
    {
        $user->password_changed_at = now();
        $user->save();
    }
}