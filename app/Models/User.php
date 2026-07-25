<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Branch;
use App\Models\UserBranch;
use App\Models\LoginLog;
use App\Models\UserPasswordHistory;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant, HasRoles, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'role',
        'doctor_id',
        'is_active',
        'username',
        'last_login_at',
        'two_factor_remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_remember_token',

    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'locked_until' => 'datetime',
        'last_login_at' => 'datetime',
        'username' => 'string',
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

    public function branch()
{
    // Current active branch — session se set hota hai
    return $this->belongsTo(Branch::class);
}

public function passwordHistory()
{
    return $this->hasMany(UserPasswordHistory::class)->orderByDesc('created_at');
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

    // ── Notification Overrides ──

    /**
     * Override password reset to use tenant notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\TenantPasswordResetNotification($token));
    }

    /**
     * Override email verification to use tenant route name
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\TenantEmailVerification);
    }



    public function scopeForBranch($query, int $branchId)
{
    return $query->whereHas('branches', fn($q) => $q->where('branches.id', $branchId));
}

public function scopeActive($query)
{
    return $query->where('is_active', true);
}

public function getLoginIdentifierAttribute(): string
{
    return $this->username ?? $this->email;
}

public function getLastLoginDiffAttribute(): ?string
{
    if (!$this->last_login_at) return null;
    return $this->last_login_at->diffForHumans();
}




// ── Password History Check ──

public function isOldPassword(string $password): bool
{
    return $this->passwordHistory()
        ->take(5) // Last 5 passwords check karo
        ->get()
        ->contains(fn($record) => Hash::check($password, $record->password));
}

// ── Login Tracking ──

public function recordLogin(): void
{
    $this->update(['last_login_at' => now()]);
}
}