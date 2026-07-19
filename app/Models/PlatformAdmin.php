<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class PlatformAdmin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'platform';
    protected $table = 'platform_admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // ── Relationships ──

    public function passwordHistory()
    {
        return $this->hasMany(PlatformPasswordHistory::class)->orderByDesc('created_at');
    }

    // ── Account Lock ──

    public function isLocked(): bool
    {
        if (!$this->locked_until) return false;
        if ($this->locked_until->isPast()) {
            $this->update(['login_attempts' => 0, 'locked_until' => null, 'is_active' => true]);
            return false;
        }
        return true;
    }

    public function getLockRemainingMinutes(): ?int
    {
        if (!$this->locked_until || $this->locked_until->isPast()) return null;
        return now()->diffInMinutes($this->locked_until);
    }

    public function recordFailedAttempt(): bool
    {
        $this->increment('login_attempts');
        $this->refresh();

        if ($this->login_attempts >= 5) {
            $this->update([
                'login_attempts' => 5,
                'locked_until' => now()->addMinutes(15),
                'is_active' => false,
            ]);
            return true;
        }
        return false;
    }

    public function resetLoginAttempts(): void
    {
        $this->update(['login_attempts' => 0, 'locked_until' => null]);
    }

    public function getAttemptsRemaining(): int
    {
        return max(0, 5 - $this->login_attempts);
    }

    // ── Email Verification ──

    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailVerified(): void
    {
        $this->update(['email_verified_at' => now()]);
    }
}