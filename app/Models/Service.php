<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant; // Imported the security guard

class Service extends Model
{
    use HasFactory;
    use BelongsToTenant; // Applied the guard! Data is now isolated by clinic

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'fee',
        'is_active',
    ];

    // Ensure proper data types when accessing these fields
    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];


    
public function tokens()
{
    return $this->hasMany(Token::class);
}

// ── Scopes ──

public function scopeActive($query)
{
    return $query->where('is_active', true);
}
}