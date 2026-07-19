<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    // Yeh model BelongsToTenant NAHI use karta — security logs hain,
    // tenant identify hone se pehle bhi log hona chahiye
    protected $table = 'login_logs';

    // updated_at nahi chahiye — sirf created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'email',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'browser_version',
        'os',
        'os_version',
        'status',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // ── Scopes ──

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLast24Hours($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    // ── Helpers ──

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getDeviceIcon(): string
    {
        return match ($this->device_type) {
            'mobile'  => 'fa-mobile-screen',
            'tablet'  => 'fa-tablet-screen-button',
            default    => 'fa-desktop',
        };
    }

    public function getStatusColor(): string
    {
        return $this->isSuccessful() ? 'emerald' : 'red';
    }
}