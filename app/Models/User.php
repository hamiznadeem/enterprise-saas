<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToTenant;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant, HasRoles, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'role',
        'doctor_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'locked_until' => 'datetime',
    ];
    // ── Relationships ──

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branches()
    {
        return $this->hasMany(UserBranch::class);
    }

    public function defaultBranch()
    {
        return $this->hasOne(UserBranch::class)->where('is_default', true)->where('is_active', true);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // ── Branch Helpers ──

    public function hasBranchAccess(int $branchId): bool
    {
        return $this->branches()
            ->where('id', $branchId)
            ->where('is_active', true)
            ->exists();
    }

    public function getActiveBranches()
    {
        return $this->branches()->active()->get();
    }

    public function getDefaultBranch(): ?UserBranch
    {
        return $this->defaultBranch ?? $this->branches()->active()->first();
    }

    public function assignBranch(array $data): UserBranch
    {
        if (!empty($data['is_default']) && $data['is_default']) {
            $this->branches()->where('tenant_id', $data['tenant_id'] ?? $this->tenant_id)
                ->update(['is_default' => false]);
        }

        return UserBranch::create(array_merge([
            'user_id'   => $this->id,
            'tenant_id' => $this->tenant_id,
            'is_active' => true,
            'is_default' => false,
        ], $data));
    }


        // ── Account Lock Helpers ──

    public function isLocked(): bool
    {
        return \App\Services\AccountLockService::isLocked($this);
    }

    public function getLockRemainingMinutes(): ?int
    {
        return \App\Services\AccountLockService::getRemainingLockMinutes($this);
    }

    public function getAttemptsRemaining(): int
    {
        return \App\Services\AccountLockService::getAttemptsRemaining($this);
    }
        /**
     * Override to use custom notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\TenantPasswordResetNotification($token));
    }
}