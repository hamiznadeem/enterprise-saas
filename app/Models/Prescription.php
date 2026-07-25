<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Prescription extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Prescriptions are strictly isolated by clinic

    protected $fillable = [
        'tenant_id',
        'patient_id',
        'doctor_id',
        'token_id',
        'diagnosis',
        'notes',
        'branch_id',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    // A prescription can have multiple medicines
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    

public function branch()
{
    return $this->belongsTo(Branch::class);
}

// ── Scopes ──

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeToday($query)
{
    return $query->whereDate('created_at', today());
}

public function scopeForDoctor($query, int $doctorId)
{
    return $query->where('doctor_id', $doctorId);
}
}