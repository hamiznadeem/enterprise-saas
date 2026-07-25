<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Branch extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'address',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Relationships ──

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Users jo is branch ko access kar sakte hain
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_branches')
            ->withPivot('is_default', 'is_active')
            ->withTimestamps();
    }

    // Branch ke andar ke patients
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Accessors ──

    public function getDisplayLabelAttribute(): string
    {
        return $this->code ? "{$this->name} ({$this->code})" : $this->name;
    }
}