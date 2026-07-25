<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Security Guard import kiya

class Doctor extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'specialization',
        'consultation_fee',
        'phone',
        'is_active',
        'daily_patient_limit',
        'branch_id',
    ];

    // Ye batata hai ke fee ko hamesha number (decimal) mein treat kare
    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

// ── Relationships ──

public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function user()
{
    return $this->hasOne(User::class, 'doctor_id');
}

public function tokens()
{
    return $this->hasMany(Token::class);
}

public function prescriptions()
{
    return $this->hasMany(Prescription::class);
}

// ──  Scopes ──

public function scopeActive($query)
{
    return $query->where('is_active', true);
}

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeBySpecialization($query, string $spec)
{
    return $query->where('specialization', $spec);
}

// ── Accessors ──

public function getTodayTokenCountAttribute(): int
{
    return $this->tokens()
        ->whereDate('created_at', today())
        ->count();
}

public function getRemainingCapacityAttribute(): int
{
    return max(0, $this->daily_patient_limit - $this->today_token_count);
}
}