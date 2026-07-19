<?php

namespace App\Services;

use App\Models\PlatformAdmin;
use App\Models\PlatformPasswordHistory;
use Illuminate\Support\Facades\Hash;

class PlatformPasswordService
{
    const HISTORY_LIMIT = 5;

    /**
     * Password strength check — returns score 0-4
     */
    public static function strength(string $password): array
    {
        $score = 0;
        $feedback = [];

        if (strlen($password) >= 8) {
            $score++;
        } else {
            $feedback[] = 'Minimum 8 characters required';
        }

        if (preg_match('/[a-z]/', $password)) $score++;
        else $feedback[] = 'Add lowercase letters';

        if (preg_match('/[A-Z]/', $password)) $score++;
        else $feedback[] = 'Add uppercase letters';

        if (preg_match('/[0-9]/', $password)) $score++;
        else $feedback[] = 'Add numbers';

        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score++;
        else $feedback[] = 'Add special characters (!@#$%)';

        $labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        $colors = ['red', 'orange', 'yellow', 'emerald', 'green'];

        return [
            'score' => $score,
            'label' => $labels[$score],
            'color' => $colors[$score],
            'feedback' => $feedback,
        ];
    }

    /**
     * Check if password was used recently
     */
    public static function isOldPassword(PlatformAdmin $admin, string $password): bool
    {
        return $admin->passwordHistory()
            ->take(self::HISTORY_LIMIT)
            ->get()
            ->contains(function ($history) use ($password) {
                return Hash::check($password, $history->password);
            });
    }

    /**
     * Record password change in history
     */
    public static function recordHistory(PlatformAdmin $admin, string $hashedPassword): void
    {
        PlatformPasswordHistory::create([
            'platform_admin_id' => $admin->id,
            'password' => $hashedPassword,
        ]);

        // Keep only last N records
        $count = $admin->passwordHistory()->count();
        if ($count > self::HISTORY_LIMIT) {
            $ids = $admin->passwordHistory()
                ->orderBy('created_at')
                ->take($count - self::HISTORY_LIMIT)
                ->pluck('id');
            PlatformPasswordHistory::destroy($ids);
        }
    }
}