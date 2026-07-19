<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class AccountLockService
{
    /**
     * Max failed attempts before lock
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Lock duration in minutes
     */
    const LOCK_DURATION_MINUTES = 15;

    /**
     * Check if user is currently locked
     */
    public static function isLocked(User $user): bool
    {
        if (!$user->locked_until) {
            return false;
        }

        // Agar lock time guzar gaya toh auto-unlock
        if ($user->locked_until->isPast()) {
            self::unlock($user);
            return false;
        }

        return true;
    }

    /**
     * Get remaining lock time in minutes
     */
    public static function getRemainingLockMinutes(User $user): ?int
    {
        if (!$user->locked_until || $user->locked_until->isPast()) {
            return null;
        }

        return now()->diffInMinutes($user->locked_until);
    }

    /**
     * Record a failed attempt — return true if account gets locked
     */
    public static function recordFailedAttempt(User $user): bool
    {
        $user->increment('login_attempts');

        // Refresh to get updated value
        $user->refresh();

        if ($user->login_attempts >= self::MAX_ATTEMPTS) {
            self::lock($user);
            return true; // locked
        }

        return false; // not locked yet
    }

    /**
     * Lock the account
     */
    public static function lock(User $user): void
    {
        $user->update([
            'login_attempts' => self::MAX_ATTEMPTS,
            'locked_until'   => now()->addMinutes(self::LOCK_DURATION_MINUTES),
            'is_active'     => false,
        ]);
    }

    /**
     * Unlock the account
     */
    public static function unlock(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until'   => null,
            'is_active'     => true,
        ]);
    }

    /**
     * Reset attempts on successful login
     */
    public static function resetAttempts(User $user): void
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);
    }

    /**
     * Get attempts remaining before lock
     */
    public static function getAttemptsRemaining(User $user): int
    {
        return max(0, self::MAX_ATTEMPTS - $user->login_attempts);
    }
}