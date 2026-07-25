<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Security Guard import kiya

class Patient extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id', // (Guard ke liye zaroori hai fillable mein)
        'name',
        'phone',
        'cnic',
        'age',
        'gender',
        'address',
        'emergency_contact',
        'blood_group',
        'allergies',
        'medical_history',
        'branch_id',
    ];

    protected $casts = [
        'gender' => 'string',
    ];


        public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    
public function branch()
{
    return $this->belongsTo(Branch::class);
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





// ──  Scopes ──

public function scopeForBranch($query, int $branchId)
{
    return $query->where('branch_id', $branchId);
}

public function scopeByGender($query, string $gender)
{
    return $query->where('gender', $gender);
}

public function scopeSearch($query, string $term)
{
    return $query->where('name', 'LIKE', "%{$term}%")
        ->orWhere('phone', 'LIKE', "%{$term}%")
        ->orWhere('cnic', 'LIKE', "%{$term}%");
}

// ── Accessors ──

public function getAgeLabelAttribute(): string
{
    return $this->age . ' ' . ($this->age == 1 ? 'year' : 'years');
}

public function getFullNameAttribute(): string
{
    return $this->name;
}
}