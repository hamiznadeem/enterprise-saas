<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use BelongsToTenant;

    protected $table = 'user_branches';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'branch_name',
        'branch_code',
        'address',
        'phone',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ── Helpers ──

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function makeDefault(): void
    {
        // Pehle is tenant ki saari branches ka default hata do
        static::where('tenant_id', $this->tenant_id)
            ->where('user_id', $this->user_id)
            ->update(['is_default' => false]);

        // Phir isko default bana do
        $this->update(['is_default' => true]);
    }
}